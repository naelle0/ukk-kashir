<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search') && $request->search !== null) {
            $search = strtolower($request->search);
            $sales = Sale::whereRaw('LOWER(customer_name) LIKE ?', ['%' . $search . '%'])
                ->paginate(10)
                ->appends($request->only('search'));
        } else {
            $sales = Sale::latest()->paginate(10);
        }

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();
        $members = Member::all();

        if ($products->isEmpty() && $members->isEmpty()) {
            return view('sales.create', compact('products', 'members'))->with('warning', 'Tidak ada produk atau member yang tersedia.');
        }

        return view('sales.create', compact('products', 'members'));
    }

    public function confirmationStore(Request $request)
    {
        $filteredQuantities = [];
        foreach ($request->input('quantities', []) as $key => $value) {
            if ($value > 0) {
                $filteredQuantities[$key] = $value;
            }
        }

        if (empty($filteredQuantities)) {
            return redirect()->back()->withErrors(['quantities' => 'Pilih setidaknya satu produk dengan jumlah lebih dari 0']);
        }

        $products = Product::whereIn('id', array_keys($filteredQuantities))->get();
        if ($products->isEmpty()) {
            return redirect()->back()->withErrors(['products' => 'Produk yang dipilih tidak ditemukan']);
        }

        $totalAmount = $products->sum(function ($product) use ($filteredQuantities) {
            return $product->price * $filteredQuantities[$product->id];
        });

        $members = Member::all();

        if ($totalAmount <= 0) {
            return redirect()->back()->withErrors(['total_amount' => 'Total amount tidak valid']);
        }

        return view('sales.confirmation', compact('products', 'totalAmount', 'members', 'filteredQuantities'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_data' => 'required',
            'total_amount' => 'required|numeric|min:0',
            'total_pay' => 'required|numeric|min:0', // Pastikan total_pay tidak null dan minimal 0
            'member_id' => 'nullable|exists:members,id',
            'is_member' => 'nullable|in:yes,no',
            'use_point' => 'nullable|in:0,1',
            'total_point' => 'nullable|numeric|min:0',
        ]);

        // Ambil data dari request
        $totalPay = floatval($request->input('total_pay'));
        $totalAmount = floatval($request->input('total_amount'));

        // Pastikan total_pay lebih besar atau sama dengan total_amount
        if ($totalPay < $totalAmount) {
            return redirect()->route('sales.create')
                ->withErrors(['total_pay' => 'Jumlah bayar harus lebih besar atau sama dengan total: Rp ' . number_format($totalAmount, 0, ',', '.')])
                ->withInput();
        }
        // Validasi member jika is_member dipilih
        if ($request->is_member == 'yes' && empty($request->member_id)) {
            return redirect()->back()->withErrors(['member_id' => 'Silakan pilih member terlebih dahulu']);
        }

        // Decode product_data
        $productData = is_string($request->input('product_data'))
            ? json_decode($request->input('product_data'), true)
            : $request->input('product_data');

        if (!is_array($productData) || empty($productData)) {
            return redirect()->back()->withErrors(['product_data' => 'Invalid product data format']);
        }

        // Generate invoice number
        $invoiceNumber = 'INV-' . strtoupper(Str::random(8));

        // Inisialisasi member
        $memberName = $invoiceNumber;
        $memberId = null;
        $member = null;

        if (!empty($request->member_id)) {
            $member = Member::find($request->member_id);
            if ($member) {
                $memberId = $member->id;
                $memberName = $member->name;
            } else {
                return redirect()->back()->withErrors(['member_id' => 'Member tidak ditemukan']);
            }
        }

        // Jika menggunakan role member, arahkan ke view member
        if ($request->is_member == 'yes') {
            if (!$member) {
                return redirect()->back()->withErrors(['member_id' => 'Silakan pilih member terlebih dahulu']);
            }
            return view('sales.member', compact('member', 'productData', 'totalAmount', 'totalPay'));
        }

        $discount = 0;
        $memberPoint = 0;

        DB::beginTransaction();
        try {
            if ($member && $request->use_point == 1) {
                $totalPoint = floatval($request->total_point);
                if ($totalPoint > $member->points) {
                    return redirect()->back()->withErrors(['total_point' => 'Poin member tidak cukup']);
                }
                $totalAmount = $totalAmount - $totalPoint;
                $discount = $totalPoint;
                $memberPoint = -$totalPoint;
                Member::where('id', $memberId)->decrement('points', $totalPoint);
            } else {
                $addPoint = floor($totalAmount / 100);
                if ($member) {
                    $memberPoint = $addPoint;
                    Member::where('id', $memberId)->increment('points', $addPoint);
                }
            }

            // Simpan data penjualan
            $sale = Sale::create([
                'id' => Str::uuid(),
                'invoice_number' => $invoiceNumber,
                'customer_name' => $memberName,
                'user_id' => Auth::user()->id,
                'member_id' => $memberId,
                'product_data' => json_encode($productData),
                'total_amount' => $totalAmount,
                'payment_amount' => $totalPay,
                'change_amount' => $totalPay - $totalAmount,
                'notes' => '-',
            ]);

            // Kurangi stok produk
            foreach ($productData as $product) {
                Product::where('id', $product['id'])->decrement('quantity', $product['quantity']);
            }

            DB::commit();

            return view('sales.invoice', compact(
                'invoiceNumber',
                'totalAmount',
                'totalPay',
                'memberName',
                'memberId',
                'productData',
                'discount',
                'memberPoint'
            ));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data penjualan. Silakan coba lagi.']);
        }
    }

    public function showInvoice($id)
    {
        $sale = Sale::where('id', $id)->firstOrFail();
        $productData = is_string($sale->product_data)
            ? json_decode($sale->product_data, true)
            : $sale->product_data;

        if (!is_array($productData)) {
            return redirect()->back()->withErrors(['error' => 'Invalid product data format in sale']);
        }

        $totalProductPrice = array_reduce($productData, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $discount = $totalProductPrice - $sale->total_amount;

        return view('sales.invoice-detail', [
            'invoiceNumber' => $sale->invoice_number,
            'memberName' => $sale->customer_name,
            'memberId' => $sale->member_id,
            'productData' => $productData,
            'totalAmount' => $sale->total_amount,
            'totalPay' => $sale->payment_amount,
            'changeAmount' => $sale->change_amount,
            'discount' => $discount,
            'createdAt' => $sale->created_at,
        ]);
    }

    public function show(Sale $sale)
    {
        $sale->product_data = is_string($sale->product_data)
            ? json_decode($sale->product_data, true)
            : $sale->product_data;

        if (!is_array($sale->product_data)) {
            return redirect()->back()->withErrors(['error' => 'Invalid product data format in sale']);
        }

        return view('sales.show', compact('sale'));
    }
}