<?php

namespace App\Models;

use Sakuci\Database\Model;

class Peminjaman extends Model
{
    protected static ?string $table = 'peminjaman';

    protected string $primaryKey = 'id_peminjaman';

    protected array $fillable = [
    'id_peminjaman',
    'tanggal_pengembalian',
    'terlambat_hari',
    'denda',
    'kondisi_barang',
    'catatan',
    ];
}