@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="auth-card">
    <h1>Daftar akun</h1>
    <p class="sub">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>

    <form method="POST" action="{{ route('register.attempt') }}">
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

        <div class="field">
            <label for="password_confirmation">Konfirmasi password</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        @if (count($roles) > 1)
            <div class="field">
                <label for="role">Daftar sebagai</label>
                <select id="role" name="role" class="{{ errors()->has('role') ? 'invalid' : '' }}">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role') <div class="galat">{{ $message }}</div> @enderror
            </div>
        @else
            <input type="hidden" name="role" value="{{ $roles[0]->name }}">
        @endif

        <button class="btn-accent" type="submit">Daftar</button>
    </form>
</div>
@endsection