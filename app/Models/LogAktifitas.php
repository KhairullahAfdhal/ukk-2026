<?php

namespace App\Models;

use Sakuci\Database\Model;

class LogAktifitas extends Model
{
    protected static ?string $table = 'log_aktifitas';

    protected string $primaryKey = 'id';

    protected array $fillable = [
        'id_user',
        'aktifitas'
    ];
}