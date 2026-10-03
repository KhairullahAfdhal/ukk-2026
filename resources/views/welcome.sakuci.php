@extends('layouts.app')

@section('title', config('app.name') . ' — Digital Library Platform')

@section('content')

    <style>
        /* Trik Full Width Responsive */
        .lib-full-bleed {
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            background-color: var(--bs-body-bg) !important;
            color: var(--bs-body-color) !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Title Styling */
        .lib-title-main {
            color: var(--bs-body-color) !important;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .lib-title-gradient {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .lib-subtitle {
            color: var(--bs-secondary-color) !important;
            font-size: 1.125rem;
            line-height: 1.7;
        }

        /* Search Bar & Badge */
        .lib-badge-container {
            background-color: var(--bs-tertiary-bg) !important;
            border: 1px solid var(--bs-border-color) !important;
            color: var(--bs-body-color) !important;
        }

        .lib-search-box {
            background-color: var(--bs-tertiary-bg) !important;
            border: 1px solid var(--bs-border-color) !important;
            transition: all 0.2s ease;
        }

        .lib-search-input {
            color: var(--bs-body-color) !important;
            background: transparent !important;
        }

        .lib-search-input::placeholder {
            color: var(--bs-secondary-color) !important;
            opacity: 0.7;
        }

        /* Buttons */
        .btn-lib-primary {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            color: #ffffff !important;
            font-weight: 600;
        }

        .btn-lib-primary:hover {
            background-color: #0b5ed7 !important;
            border-color: #0a58ca !important;
        }

        .btn-lib-secondary {
            background-color: var(--bs-tertiary-bg) !important;
            border: 1px solid var(--bs-border-color) !important;
            color: var(--bs-body-color) !important;
            font-weight: 600;
        }

        /* ==================================================== */
        /*   STYLING GARIS & TABEL DENGAN KONTRAS TINGGI        */
        /* ==================================================== */

        /* 1. Baris Statistik (10.000+) */
        .lib-stats-bar {
            background-color: var(--bs-tertiary-bg) !important;
            border-top: 2px solid var(--bs-border-color) !important;
            border-bottom: 2px solid var(--bs-border-color) !important;
        }

        .stat-column {
            border-right: 2px solid var(--bs-border-color) !important;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .stat-column:last-child {
            border-right: none !important;
        }

        /* 2. Kartu & Tabel Utama */
        .lib-table-card {
            background-color: var(--bs-tertiary-bg) !important;
            border: 2px solid var(--bs-border-color) !important;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .lib-custom-table {
            color: var(--bs-body-color) !important;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .lib-custom-table thead th {
            background-color: var(--bs-secondary-bg) !important;
            color: var(--bs-body-color) !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            padding: 1.1rem 1.25rem;
            border-bottom: 2px solid var(--bs-border-color) !important;
        }

        .lib-custom-table tbody td {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid var(--bs-border-color) !important;
            vertical-align: middle;
            background-color: var(--bs-tertiary-bg) !important;
        }

        .lib-custom-table tbody tr:last-child td {
            border-bottom: none !important;
        }

        .book-cover-thumb {
            width: 42px;
            height: 58px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--bs-border-color);
            background-color: var(--bs-secondary-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* ==================================================== */
        /*   OPTIMASI KHUSUS TAMPILAN LIGHT MODE (TERANG)       */
        /* ==================================================== */
        [data-bs-theme="light"] .lib-stats-bar,
        body:not([data-bs-theme="dark"]) .lib-stats-bar {
            background-color: #f8f9fa !important;
            border-top: 2px solid #cbd5e1 !important;
            border-bottom: 2px solid #cbd5e1 !important;
        }

        [data-bs-theme="light"] .stat-column,
        body:not([data-bs-theme="dark"]) .stat-column {
            border-right: 2px solid #cbd5e1 !important;
        }

        [data-bs-theme="light"] .lib-table-card,
        body:not([data-bs-theme="dark"]) .lib-table-card {
            border: 2px solid #cbd5e1 !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        }

        [data-bs-theme="light"] .lib-custom-table thead th,
        body:not([data-bs-theme="dark"]) .lib-custom-table thead th {
            background-color: #e2e8f0 !important;
            color: #0f172a !important;
            border-bottom: 2px solid #94a3b8 !important;
        }

        [data-bs-theme="light"] .lib-custom-table tbody td,
        body:not([data-bs-theme="dark"]) .lib-custom-table tbody td {
            border-bottom: 1px solid #e2e8f0 !important;
            background-color: #ffffff !important;
        }

        [data-bs-theme="light"] .lib-custom-table tbody tr:hover td,
        body:not([data-bs-theme="dark"]) .lib-custom-table tbody tr:hover td {
            background-color: #f1f5f9 !important;
        }

        /* Responsif Mobile */
        @media (max-width: 767.98px) {
            .stat-column {
                border-right: none !important;
                border-bottom: 2px solid var(--bs-border-color) !important;
                padding-bottom: 1rem;
            }
            [data-bs-theme="light"] .stat-column,
            body:not([data-bs-theme="dark"]) .stat-column {
                border-bottom: 2px solid #cbd5e1 !important;
            }
            .stat-column:last-child {
                border-bottom: none !important;
            }
        }

        @media (max-width: 576px) {
            .lib-search-box {
                flex-direction: column;
                padding: 0.75rem !important;
            }
            .lib-search-box button {
                width: 100%;
            }
            .btn-mobile-full {
                width: 100%;
            }
        }
    </style>

    <div class="lib-full-bleed py-4">
        {{-- Hero Section --}}
        <section class="text-center py-4 py-md-5 container">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-4 rounded-pill lib-badge-container">
                <span class="badge btn-lib-primary rounded-pill">Global Standard</span>
                <small class="fw-semibold">Next-Gen Digital Library</small>
            </div>

            <h1 class="display-4 lib-title-main mb-3">
                Empowering Minds Through <br class="d-none d-md-inline">
                <span class="lib-title-gradient">Seamless Digital Reading</span>
            </h1>

            <p class="lib-subtitle mx-auto mb-4 fw-normal px-2" style="max-width: 680px;">
                Access thousands of books anytime, anywhere. Facilitating school library access digitally—supporting Indonesia's golden era through global literacy standards.
            </p>

            <div class="mx-auto mb-4 px-2" style="max-width: 600px;">
                <form action="#" method="GET" class="p-2 rounded-4 lib-search-box d-flex gap-2 align-items-center shadow-sm">
                    <input type="text" class="form-control border-0 shadow-none ps-3 lib-search-input" placeholder="Search by title, author, or ISBN..." aria-label="Search">
                    <button class="btn btn-lib-primary px-4 py-2 rounded-3 d-flex align-items-center gap-2 justify-content-center" type="submit">
                        <span>Explore</span>
                    </button>
                </form>
            </div>

            <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center mb-4 px-2">
                <a class="btn btn-lib-primary btn-lg px-4 rounded-3 btn-mobile-full" href="#katalog-buku">
                    Get Started
                </a>
                <a class="btn btn-lib-secondary btn-lg px-4 rounded-3 btn-mobile-full" href="https://github.com/indrabsus/sakuci-framework" target="_blank">
                    GitHub Repository
                </a>
            </div>

            <p class="text-body-secondary small mb-0">
                Developer Notice: Follow setup guide in <code class="px-2 py-1 rounded bg-body-tertiary text-primary border">TUTORIAL.md</code>
            </p>
        </section>

        {{-- Live Stats Section (Dengan Garis Terang & Jelas di Light Mode) --}}
        <section class="py-4 my-4 lib-stats-bar">
            <div class="container">
                <div class="row g-0 text-center">
                    <div class="col-6 col-md-3 stat-column">
                        <h3 class="fw-bold mb-0 text-primary">10,000+</h3>
                        <p class="text-body-secondary small mb-0 fw-medium">Digital Titles</p>
                    </div>
                    <div class="col-6 col-md-3 stat-column">
                        <h3 class="fw-bold mb-0 text-primary">2,500+</h3>
                        <p class="text-body-secondary small mb-0 fw-medium">Active Readers</p>
                    </div>
                    <div class="col-6 col-md-3 stat-column">
                        <h3 class="fw-bold mb-0 text-primary">24/7</h3>
                        <p class="text-body-secondary small mb-0 fw-medium">Online Access</p>
                    </div>
                    <div class="col-6 col-md-3 stat-column">
                        <h3 class="fw-bold mb-0 text-primary">100%</h3>
                        <p class="text-body-secondary small mb-0 fw-medium">Automated System</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section Tabel Katalog Buku --}}
        <section class="py-5 container" id="katalog-buku">
            <div class="d-flex flex-wrap align-items-end justify-content-between mb-4 gap-2">
                <div>
                    <span class="text-primary fw-bold text-uppercase tracking-wider small">Live Borrowing Catalog</span>
                    <h2 class="fw-bold mt-1 lib-title-main mb-0">Popular Library Books</h2>
                </div>
                <a href="#" class="btn btn-sm btn-lib-secondary rounded-3 px-3">View All Books &rarr;</a>
            </div>

            <div class="lib-table-card table-responsive">
                <table class="table lib-custom-table align-middle">
                    <thead>
                        <tr>
                            <th scope="col" style="min-width: 280px;">Book Title & Author</th>
                            <th scope="col" style="min-width: 140px;">Category</th>
                            <th scope="col" style="min-width: 150px;">ISBN / Code</th>
                            <th scope="col" class="text-center" style="min-width: 120px;">Rating</th>
                            <th scope="col" class="text-center" style="min-width: 130px;">Status</th>
                            <th scope="col" class="text-end" style="min-width: 130px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="book-cover-thumb">📘</div>
                                    <div>
                                        <div class="fw-bold text-body">Laskar Pelangi</div>
                                        <div class="text-body-secondary small">Andrea Hirata</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-2">Literary Fiction</span></td>
                            <td class="font-monospace small text-body-secondary">978-979-3062-79-2</td>
                            <td class="text-center"><span class="text-warning font-monospace fw-bold">★ 4.9</span></td>
                            <td class="text-center"><span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Tersedia (5)</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-lib-primary rounded-2 px-3">Pinjam</button></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="book-cover-thumb">📗</div>
                                    <div>
                                        <div class="fw-bold text-body">Atomic Habits</div>
                                        <div class="text-body-secondary small">James Clear</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-2">Self Improvement</span></td>
                            <td class="font-monospace small text-body-secondary">978-073-5211-29-2</td>
                            <td class="text-center"><span class="text-warning font-monospace fw-bold">★ 4.8</span></td>
                            <td class="text-center"><span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Tersedia (2)</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-lib-primary rounded-2 px-3">Pinjam</button></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="book-cover-thumb">📙</div>
                                    <div>
                                        <div class="fw-bold text-body">Bumi (Earth)</div>
                                        <div class="text-body-secondary small">Tere Liye</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-2">Sci-Fi / Fantasy</span></td>
                            <td class="font-monospace small text-body-secondary">978-602-0301-12-9</td>
                            <td class="text-center"><span class="text-warning font-monospace fw-bold">★ 4.7</span></td>
                            <td class="text-center"><span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">Dipinjam</span></td>
                            <td class="text-end"><button class="btn btn-sm btn-lib-secondary rounded-2 px-3" disabled>Antri</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection