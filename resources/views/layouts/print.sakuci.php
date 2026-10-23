<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color: #111; margin: 24px; background: #fff; }
        .print-toolbar { margin-bottom: 16px; display: flex; gap: 8px; align-items: center; }
        .print-toolbar button, .print-toolbar a {
            padding: 8px 14px; border-radius: 6px; border: 1px solid #999;
            background: #f4f4f4; cursor: pointer; text-decoration: none; color: #111; font-size: 14px;
        }
        .laporan-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #111; padding-bottom: 10px; }
        .laporan-header h1 { margin: 0 0 4px; font-size: 20px; }
        .laporan-header p { margin: 0; font-size: 13px; color: #444; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 16px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #eee; }
        tfoot td { font-weight: bold; background: #f7f7f7; }
        .laporan-footer { margin-top: 32px; display: flex; justify-content: flex-end; }
        .ttd { text-align: center; width: 220px; }
        .ttd .garis { margin-top: 60px; border-top: 1px solid #111; }

        @media print {
            .print-toolbar { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="print-toolbar">
        <button onclick="window.print()">🖨 Cetak</button>
        <a href="javascript:history.back()">&larr; Kembali</a>
    </div>

    @yield('content')
</body>
</html>