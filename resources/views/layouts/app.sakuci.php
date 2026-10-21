<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    {{-- Terapkan tema tersimpan sebelum apa pun dirender, supaya tidak ada flash warna --}}
    <script>
        (function () {
            var saved = localStorage.getItem('sakuci-theme');
            var theme = saved || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

    {{-- Bootstrap 5.3.8 -- file lokal, tidak butuh internet --}}
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        /* Pengaturan Layout Sidebar Permanen (Layar Besar / Laptop) */
        @media (min-width: 992px) {
            .sidebar-fixed {
                width: 260px;
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1000;
                overflow-y: auto;
            }
            .main-content-shifted {
                margin-left: 260px; /* Memberikan ruang selebar sidebar di sebelah kiri */
            }
        }
    </style>
</head>
<body class="bg-body-tertiary">

<div class="container-fluid p-0">
    <div class="row g-0">
        
        {{-- Kolom Sidebar (Permanen di Kiri) --}}
        <div class="col-lg-auto sidebar-fixed bg-body border-end">
            @include('partials.navbar')
        </div>

        {{-- Kolom Konten Utama di Kanan --}}
        <div class="col main-content-shifted d-flex flex-column min-vh-100">
            <main class="container-fluid px-4 flex-grow-1 py-4">
                @include('partials.flash')

                @yield('content')
            </main>

            @include('partials.footer')
        </div>

    </div>
</div>

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/theme.js') }}"></script>
@yield('scripts')

</body>
</html>