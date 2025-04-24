<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProdukExport;

class ProdukExportController extends Controller
{
    public function export()
    {
        return Excel::download(new ProdukExport, 'produk.xlsx');
    }
}
