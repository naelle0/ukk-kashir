@extends('layouts.app')

@section('title', 'Konfirmasi Penjualan')

@section('content')
<div class="main-content-table">
    <section class="section">
        <div class="margin-content">
            <div class="container-sm">
                <div class="section-header">
                    <h1>Konfirmasi Penjualan</h1>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="section-body">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('sales.store') }}" method="POST" id="confirmation-form">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-4">
                                        <h5>Produk yang Dibeli</h5>
                                        @if (!empty($productData))
                                            <ul class="list-group">
                                                @foreach ($productData as $key => $product)
                                                    <li class="list-group-item">
                                                        <strong>{{ $key + 1 . '. ' . $product['name'] }}</strong>
                                                        <br>Harga: Rp {{ number_format($product['price'], 0, ',', '.') }}
                                                        <br>Jumlah: {{ $product['quantity'] }}
                                                        <br>Subtotal: Rp {{ number_format($product['price'] * $product['quantity'], 0, ',', '.') }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <h5 class="mt-3">Total: Rp {{ number_format($totalAmount, 0, ',', '.') }}</h5>
                                            <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                                            <input type="hidden" name="total_pay" value="{{ $totalPay }}">
                                            <input type="hidden" name="member_id" value="{{ $member->id }}">
                                            <input type="hidden" name="product_data" value="{{ json_encode($productData) }}">
                                        @else
                                            <p class="text-danger">Tidak ada produk yang dipilih.</p>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <h5>Informasi Member</h5>
                                        <div class="form-group mb-3">
                                            <label for="member_name">Nama Member</label>
                                            <input type="text" class="form-control" id="member_name" name="member_name" value="{{ $member->name }}" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="total_point">Jumlah Poin</label>
                                            <input type="text" class="form-control" id="total_point" name="total_point" value="{{ $member->points }}" readonly>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="use_point" class="d-flex align-items-center">
                                                <input type="hidden" name="use_point" value="0">
                                                <input type="checkbox" name="use_point" value="1" id="use_point" class="form-check-input me-2">
                                                Gunakan Poin
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('sales.create') }}" class="btn btn-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-primary">Tambah Penjualan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('confirmation-form');
        const usePointCheckbox = document.getElementById('use_point');
        const totalPointInput = document.getElementById('total_point');
        const totalAmount = {{ $totalAmount }};

        form.addEventListener('submit', function (event) {
            const usePoint = usePointCheckbox.checked;
            const totalPoints = parseInt(totalPointInput.value) || 0;

            if (usePoint && totalPoints <= 0) {
                event.preventDefault();
                alert('Member tidak memiliki poin yang cukup untuk digunakan.');
                return;
            }

            if (usePoint && totalPoints > 0) {
                const pointsToUse = Math.min(totalPoints, totalAmount);
                if (pointsToUse < totalAmount) {
                    alert(`Poin yang digunakan: ${pointsToUse}. Sisa yang harus dibayar: Rp ${(totalAmount - pointsToUse).toLocaleString('id-ID')}`);
                }
            }
        });
    });
</script>

<style>
    .main-content-table {
        padding: 20px;
    }

    .section-header h1 {
        font-size: 1.75rem;
        margin-bottom: 1.5rem;
        color: #007bff;
    }

    .list-group-item {
        border: none;
        padding: 1rem;
        margin-bottom: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
    }

    .form-group label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control[readonly] {
        background-color: #e9ecef;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-secondary {
        transition: background-color 0.3s;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    @media (max-width: 576px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>
@endpush
@endsection
