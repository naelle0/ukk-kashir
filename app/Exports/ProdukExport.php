<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProdukExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::all();
    }
    public function headings(): array
    {
        return [
            'no',
            'nama',
            'harga',
            'stok'
        ];
    }

    private static $counter = 1;

    public function map($product): array
    {
        $id = self::$counter++;

        return [
            $id,
            $product->name,
            $product->price,
            $product->quantity,
            
        ];
    }
}
