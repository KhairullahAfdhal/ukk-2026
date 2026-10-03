<?php

namespace App\Models;

use Sakuci\Database\Model;

class Buku extends Model
{
    protected static ?string $table = 'buku';
    protected string $primaryKey = 'id_buku';
    protected array $fillable = ['id_buku','nama_buku','kode_buku','id_kategori'];

   public function kategori()
    { 
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
