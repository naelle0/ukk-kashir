@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to bottom right, #fdfbfb, #ebedee);
    }

    .dashboard-wrapper {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 3rem;
        color: #2c3e50;
        animation: fadeIn 0.7s ease;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .card-box {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        text-align: center;
        padding: 2rem 1.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: fadeInUp 0.8s ease;
    }

    .card-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .card-box .icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: white;
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        border-radius: 50%;
    }

    .bg-primary { background: #007bff; }
    .bg-success { background: #28a745; }
    .bg-warning { background: #ffc107; }
    .bg-danger  { background: #dc3545; }

    .card-box .label {
        font-size: 1.1rem;
        color: #555;
        font-weight: 500;
    }

    .card-box .value {
        font-size: 2rem;
        font-weight: 700;
        color: #222;
        margin-top: 0.5rem;
    }

    .card-box .sub-info {
        font-size: 0.9rem;
        margin-top: 0.5rem;
        color: #888;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="dashboard-wrapper">
    <h1 class="dashboard-title">Selamat Datang, {{ substr(auth()->user()->role, 0, 15) }}!</h1>

    <div class="dashboard-grid">
        <!-- Produk -->
        <div class="card-box">
            <div class="icon bg-success"><i class="fas fa-box"></i></div>
            <div class="label">Produk</div>
            <div class="value">{{ $productCount }}</div>
        </div>

        <!-- Penjualan -->
        <div class="card-box">
            <div class="icon bg-warning"><i class="fas fa-shopping-cart"></i></div>
            <div class="label">Penjualan</div>
            <div class="value">{{ $salesCount }}</div>
            <div class="sub-info">Member: {{ $memberSalesCount }} | Non: {{ $nonMemberSalesCount }}</div>
        </div>

        <!-- User (Superadmin Only) -->
        @if(auth()->user()->role == 'superadmin')
        <div class="card-box">
            <div class="icon bg-primary"><i class="fas fa-users-cog"></i></div>
            <div class="label">User</div>
            <div class="value">{{ $userCount }}</div>
        </div>
        @endif

        <!-- Member -->
        <div class="card-box">
            <div class="icon bg-danger"><i class="fas fa-user-friends"></i></div>
            <div class="label">Member</div>
            <div class="value">{{ $memberCount }}</div>
        </div>
    </div>
</div>
@endsection
