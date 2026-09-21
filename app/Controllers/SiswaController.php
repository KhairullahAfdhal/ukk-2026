<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Siswa;
use App\Controllers;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $data = siswa::paginate(5);
        return $this->view('siswa.index', compact('data') );
    }
}
