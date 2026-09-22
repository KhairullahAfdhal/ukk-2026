<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Siswa;
use App\Models\User;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
         $siswa = siswa::orderBy('id_siswa', 'desc')->paginate(5);
       return view ('siswa.index', compact('siswa'));
    }

    public function create(Request $request)
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswa,nis',
            'kelas' => 'required|string|max:10',
        ]);

        $user = User::create([
            'username' => $request->nis,
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'siswa',
        ]
        );

        Siswa::create([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'id_user' => $user->id,
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit($id_siswa)
    {
        $siswa = siswa::findOrFail($id_siswa);
        return $this->view('siswa.edit', compact('siswa'));
    }

     public function update(Request $request, $id_siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswa,nis',
            'kelas' => 'required|string|max:10',
        ]);

        $siswa = Siswa::findOrFail($id_siswa);
        $siswa->update([
            'nama' => $request->input('nama'),
            'nis' => $request->input('nis'),
            'kelas' => $request->input('kelas'),
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }
}