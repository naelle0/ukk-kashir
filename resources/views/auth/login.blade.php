@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f9fafb;">
    <div class="w-100" style="max-width: 400px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">

        <h4 class="mb-4 text-center" style="font-weight: bold;">Log In Form</h4>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success mb-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label" style="color: gray;">Email</label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label" style="color: gray;">Password</label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    style="background-color: #1D4ED8; color:white; padding: 6px; width: 100%; cursor: pointer;">
                    Log In
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
