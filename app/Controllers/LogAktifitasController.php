<?php

namespace App\Controllers;

use App\Models\LogAktifitas;
use Sakuci\Controller;

class LogAktifitasController extends Controller
{
    public function index()
    {
        // Mengambil data log dari database
        $datap = LogAktifitas::latest()->paginate(10);
        
        // Memanggil view log
        return view('core.admin.log.index', compact('datap'));
    }
}