@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <h3 class="mb-4 text-center">Register</h3>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Role -->
        <div class="form-group mb-4">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="" disabled selected>Pilih Role</option>
                <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="new-password">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}" class="text-decoration-underline text-muted">Already registered?</a>
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </form>
</div>
@endsection
