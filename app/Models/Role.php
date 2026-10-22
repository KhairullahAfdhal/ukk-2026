<?php

namespace App\Models;

use Sakuci\Database\Model;

class Role extends Model
{
    protected static ?string $table = 'role';

    // Kolom yang boleh diisi lewat create()/update()
    protected array $fillable = ['name', 'can_register'];

    protected array $casts = ['can_register' => 'bool'];
}

