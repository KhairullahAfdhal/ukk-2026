<?php

namespace App\Models;

use Sakuci\Database\Model;

class Kategori extends Model
{
    protected static ?string $table = 'kategoris';
    protected string $primaryKey = 'id_kategori';
    protected array $fillable = ['keterangan'];
}
