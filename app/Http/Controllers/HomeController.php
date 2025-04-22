<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Member;

class HomeController extends Controller
{
    public function index()
    {
        $userCount = null;
    
        if (auth()->user()->role == 'superadmin') {
            $userCount = User::count();
        }
    
        $productCount = Product::count();
        $salesCount = Sale::count();
        $memberCount = Member::count();
    
        // Tambahan: hitung penjualan member & non-member
        $memberSalesCount = Sale::whereNotNull('member_id')->count();
        $nonMemberSalesCount = Sale::whereNull('member_id')->count();
    
        // Ambil transaksi terakhir
        $lastTransaction = Sale::latest()->first();
    
        return view('home', compact(
            'userCount',
            'productCount',
            'salesCount',
            'memberCount',
            'memberSalesCount',
            'nonMemberSalesCount',
            'lastTransaction' // Tambahkan ini
        ));
    }
    

    public function blank()
    {
        return view('layouts.blank-page');
    }
}
