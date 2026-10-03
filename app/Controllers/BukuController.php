<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $buku = Buku::join(
            'kategori',
            'buku.id_kategori',
            '=',
            'kategori.id_kategori'
        )
        ->select(
            'buku.*',
            'kategori.nama_kategori'
        )
        ->orderBy('buku.id_buku', 'desc')
        ->paginate(5);

        $kategori = Kategori::all();

        return view('buku.index', compact(
            'buku'
        ));
    }

    public function create(Request $request)
    {
        $kategori = Kategori::all();

        return view('buku.create', compact(
            'kategori'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'kode_buku' => 'required|string|max:50',
            'nama_buku' => 'required|string|max:255',
        ]);

        Sarpras::create([
            'id_kategori' => $request->input('id_kategori'),
            'kode_buku' => $request->input('kode_buku'),
            'nama_buku' => $request->input('nama_buku'),
        ]);

        return redirect()
            ->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit($id_buku)
    {
        $buku = Buku::findOrFail($id_buku);
        $kategori = Kategori::all();

        return view('buku.edit', compact(
            'buku',
            'kategori'
        ));
    }

    public function update(Request $request, $id_buku)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'kode_buku' => 'required|string|max:50',
            'nama_buku' => 'required|string|max:255',
        ]);

        $buku = Buku::findOrFail($id_buku);

        $buku->update([
            'id_kategori' => $request->input('id_kategori'),
            'kode_buku' => $request->input('kode_buku'),
            'nama_buku' => $request->input('nama_buku'),
        ]);

        return redirect()
            ->route('admin.buku.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function delete($id_buku)
    {
        $buku = Buku::findOrFail($id_buku);
        $buku->delete();

        return redirect()
            ->route('admin.buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}