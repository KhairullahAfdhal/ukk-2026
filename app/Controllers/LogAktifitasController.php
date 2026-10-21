<?php

namespace App\Controllers;

use App\Models\LogAktifitas;

class LogAktifitasController
{
    public function index()
    {
        // Mengambil data log dari database
        $datap = LogAktifitas::latest()->paginate(10);
        
        // Memanggil view log
        return view('core.admin.log.index', compact('datap'));
    }
}