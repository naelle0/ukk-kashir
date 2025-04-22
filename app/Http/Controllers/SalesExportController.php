<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesExportController extends Controller
{
    public function export()
    {
        return Excel::download(new SalesExport, 'sales.xlsx');
    }
}
