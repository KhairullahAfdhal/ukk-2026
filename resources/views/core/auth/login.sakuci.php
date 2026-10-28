@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <h1>Masuk</h1>
    <p class="sub">Masuk untuk meminjam atau mengelola buku.</p>

    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf

        <div class="field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" class="{{ errors()->has('username') ? 'invalid' : '' }}" autofocus>
            @error('username') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="{{ errors()->has('password') ? 'invalid' : '' }}">
            @error('password') <div class="galat">{{ $message }}</div> @enderror
        </div>

        <button class="btn-accent" type="submit">Masuk</button>
    </form>

    @php
        $canRegister = false;
        try {
            $canRegister = \App\Models\Role::where('can_register', 1)->exists();
        } catch (\Throwable $e) {
            $canRegister = false;
        }
    @endphp
    @if ($canRegister)
        <p class="auth-foot">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
    @endif
</div>
@endsection