@extends('layouts.app')

@section('title', 'Beranda — Digital Library Platform')

@section('content')

<style>
    /* RESET & LAYOUT FIX */
    .dashboard-container {
        width: 100%;
        max-width: 100%;
    }

    /* Hero Title Styling */
    .hero-title {
        color: var(--bs-body-color);
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .hero-title-gradient {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Card & Search Box */
    .dash-card {
        background-color: var(--bs-tertiary-bg) !important;
        border: 1px solid var(--bs-border-color) !important;
        border-radius: 12px;
    }

    .dash-search-input {
        background: transparent !important;
        color: var(--bs-body-color) !important;
    }

    .dash-search-input::placeholder {
        color: var(--bs-secondary-color) !important;
    }

    /* Stats Section */
    .dash-stats-box {
        background-color: var(--bs-tertiary-bg) !important;
        border: 1px solid var(--bs-border-color) !important;
        border-radius: 12px;
    }

    .stat-divider {
        border-right: 1px solid var(--bs-border-color);
    }

    /* Table Container & Table Styling */
    .dash-table-card {
        background-color: var(--bs-tertiary-bg) !important;
        border: 1px solid var(--bs-border-color) !important;
        border-radius: 12px;
        overflow: hidden;
    }

    .dash-table {
        margin-bottom: 0;
        color: var(--bs-body-color) !important;
        width: 100%;
    }

    .dash-table thead th {
        background-color: var(--bs-secondary-bg) !important;
        color: var(--bs-body-color) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--bs-border-color) !important;
        padding: 1rem 1.25rem;
    }

    .dash-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--bs-border-color) !important;
        vertical-align: middle;
        background-color: var(--bs-tertiary-bg) !important;
        color: var(--bs-body-color) !important;
    }

    .dash-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    .book-cover-box {
        width: 40px;
        height: 54px;
        border-radius: 6px;
        background-color: var(--bs-secondary-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        border: 1px solid var(--bs-border-color);
        flex-shrink: 0;
    }

    /* Responsif untuk layar HP/Tablet */
    @media (max-width: 767.98px) {
        .stat-divider {
            border-right: none !important;
            border-bottom: 1px solid var(--bs-border-color);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .stat-divider:last-child {
            border-bottom: none !important;
            margin-bottom: 0;
            padding-bottom: 0;
        }
    }
</style>

<div class="dashboard-container">

    {{-- Hero Section --}}
    <section class="text-center py-4 py-md-5">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 dash-card shadow-sm">
            <span class="badge bg-primary rounded-pill">Global Standard</span>
            <small class="fw-semibold text-body">Next-Gen Digital Library</small>
        </div>

        <h1 class="display-5 hero-title mb-3">
            Empowering Minds Through <br class="d-none d-md-inline">
            <span class="hero-title-gradient">Seamless Digital Reading</span>
        </h1>

        <p class="text-body-secondary mx-auto mb-4" style="max-width: 650px; font-size: 1.05rem;">
            Access thousands of books anytime, anywhere. Facilitating school library access digitally—supporting literacy with ease.
        </p>

        <!-- Search Bar -->
        <div class="mx-auto mb-4" style="max-width: 580px;">
            <form action="#" method="GET" class="p-2 dash-card d-flex gap-2 align-items-center shadow-sm">
                <i class="bi bi-search text-body-secondary ms-2"></i>
                <input type="text" class="form-control border-0 shadow-none dash-search-input" placeholder="Search by title, author, or ISBN...">
                <button class="btn btn-primary px-4 rounded-3 fw-semibold" type="submit">Explore</button>
            </form>
        </div>
    </section>

    {{-- Live Stats Section --}}
    <section class="p-4 mb-4 dash-stats-box shadow-sm">
        <div class="row g-3 text-center">
            <div class="col-6 col-md-3 stat-divider">
                <h3 class="fw-bold mb-0 text-primary">10,000+</h3>
                <p class="text-body-secondary small mb-0 fw-medium">Digital Titles</p>
            </div>
            <div class="col-6 col-md-3 stat-divider">
                <h3 class="fw-bold mb-0 text-primary">2,500+</h3>
                <p class="text-body-secondary small mb-0 fw-medium">Active Readers</p>
            </div>
            <div class="col-6 col-md-3 stat-divider">
                <h3 class="fw-bold mb-0 text-primary">24/7</h3>
                <p class="text-body-secondary small mb-0 fw-medium">Online Access</p>
            </div>
            <div class="col-6 col-md-3 stat-divider">
                <h3 class="fw-bold mb-0 text-primary">100%</h3>
                <p class="text-body-secondary small mb-0 fw-medium">Automated System</p>
            </div>
        </div>
    </section>

    {{-- Section Tabel Katalog Buku --}}
    <section class="py-2" id="katalog-buku">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
            <div>
                <span class="text-primary fw-bold text-uppercase tracking-wider small">Live Borrowing Catalog</span>
                <h3 class="fw-bold mt-1 hero-title mb-0">Popular Library Books</h3>
            </div>
            <a href="#" class="btn btn-sm btn-outline-secondary rounded-3 px-3">View All Books &rarr;</a>
        </div>

        <div class="dash-table-card table-responsive shadow-sm">
            <table class="table dash-table align-middle">
                <thead>
                    <tr>
                        <th scope="col" style="min-width: 260px;">Book Title & Author</th>
                        <th scope="col" style="min-width: 140px;">Category</th>
                        <th scope="col" style="min-width: 150px;">ISBN / Code</th>
                        <th scope="col" class="text-center" style="min-width: 100px;">Rating</th>
                        <th scope="col" class="text-center" style="min-width: 130px;">Status</th>
                        <th scope="col" class="text-end" style="min-width: 120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="book-cover-box">📘</div>
                                <div>
                                    <div class="fw-bold text-body">Laskar Pelangi</div>
                                    <div class="text-body-secondary small">Andrea Hirata</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-2">Literary Fiction</span></td>
                        <td class="font-monospace small text-body-secondary">978-979-3062-79-2</td>
                        <td class="text-center"><span class="text-warning font-monospace fw-bold">★ 4.9</span></td>
                        <td class="text-center"><span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Tersedia (5)</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-primary rounded-2 px-3 fw-semibold">Pinjam</button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="book-cover-box">📗</div>
                                <div>
                                    <div class="fw-bold text-body">Atomic Habits</div>
                                    <div class="text-body-secondary small">James Clear</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-2">Self Improvement</span></td>
                        <td class="font-monospace small text-body-secondary">978-073-5211-29-2</td>
                        <td class="text-center"><span class="text-warning font-monospace fw-bold">★ 4.8</span></td>
                        <td class="text-center"><span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Tersedia (2)</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-primary rounded-2 px-3 fw-semibold">Pinjam</button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="book-cover-box">📙</div>
                                <div>
                                    <div class="fw-bold text-body">Bumi (Earth)</div>
                                    <div class="text-body-secondary small">Tere Liye</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary-emphasis rounded-2">Sci-Fi / Fantasy</span></td>
                        <td class="font-monospace small text-body-secondary">978-602-0301-12-9</td>
                        <td class="text-center"><span class="text-warning font-monospace fw-bold">★ 4.7</span></td>
                        <td class="text-center"><span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1">Dipinjam</span></td>
                        <td class="text-end"><button class="btn btn-sm btn-secondary rounded-2 px-3 fw-semibold" disabled>Antri</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

</div>

@endsection