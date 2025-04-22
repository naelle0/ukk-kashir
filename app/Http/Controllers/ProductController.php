<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search') && $request->search !== null) {
            $search = strtolower($request->search);
            $products = Product::whereRaw('LOWER(name) LIKE ?', ['%'.$search.'%'])
                ->orderByRaw('LOWER(name) ASC')
                ->paginate(10)
                ->appends($request->only('search'));
        } else {
            $products = Product::orderByRaw('LOWER(name) ASC')->paginate(10);
        }

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);
    
        $price = $request->price;
        $discount = $request->discount;
    
        // Hitung harga akhir jika ada diskon
        if ($discount && $discount > 0) {
            $price = $price - ($price * ($discount / 100));
        }
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
        }
    
        Product::create([
            'name' => $request->name,
            'image' => $imagePath ?? null,
            'quantity' => $request->quantity,
            'price' => round($price), 
            'discount' => $discount,

        ]);
    
        return redirect()->route('products.index')->with('message', 'Product created successfully.');
    }
    
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $data = [
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ];

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('images/products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->quantity = $request->quantity;
        $product->save();

        return redirect()->route('products.index')->with('message', 'Stock updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
