<?php

namespace App\Models;

use Sakuci\Database\Model;

class Kategori extends Model
{
    protected static ?string $table = 'kategori';
    protected string $primaryKey = 'id_kategori';
    protected array $fillable = ['nama_kategori','kode_kategori','keterangan'];

    public function buku()
    {
        return $this -> hasMany(Buku::class, 'id_kategori', 'id_kategori');
    }
}