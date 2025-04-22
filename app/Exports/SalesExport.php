<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Sale;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Sale::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Invoice',
            'Nama Pelanggan',
            'Tanggal Penjualan',
            'Produk',
            'Total Harga',
            'Total Bayar',
            'Kembalian',
            'Diskon',
            'Dibuat Oleh'
        ];
    }

    private static $counter = 1;

    public function map($sale): array
    {
        $id = self::$counter++;

        $productData = is_string($sale->product_data) ? json_decode($sale->product_data, true) : $sale->product_data;

        if (!is_array($productData)) {
            $productData = [];
        }

        $totalProductPrice = array_reduce($productData, function ($carry, $item) {
            return $carry + ((float) $item['price'] * (int) $item['quantity']);
        }, 0);

        $discount = $totalProductPrice - (float) $sale->total_amount;

        return [
            $id,
            $sale->invoice_number,
            $sale->customer_name,
            $sale->created_at->format('d-m-Y H:i'),
            json_encode($productData, JSON_UNESCAPED_UNICODE),
            'Rp ' . number_format($sale->total_amount, 0, ',', '.'),
            'Rp ' . number_format($sale->payment_amount, 0, ',', '.'),
            'Rp ' . number_format($sale->change_amount, 0, ',', '.'),
            'Rp ' . number_format($discount, 0, ',', '.'),
            DB::table('users')->where('id', $sale->user_id)->value('name'),
        ];
    }

}
