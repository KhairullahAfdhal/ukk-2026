<?php

namespace App\Middleware;

/** Hanya role "petugas" yang boleh lewat. */
class PetugasOnly extends EnsureRole
{
    protected function roles(): array
    {
        return ['petugas'];
    }
}