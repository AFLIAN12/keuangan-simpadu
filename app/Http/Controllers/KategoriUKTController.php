<?php

namespace App\Http\Controllers;

use App\Models\KategoriUKT;
use Illuminate\Http\Request;

class KategoriUKTController extends Controller
{
    // Tampilkan semua kategori UKT
    public function index()
    {
        return response()->json(KategoriUKT::all());
    }

    // Simpan kategori UKT baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori' => 'required|string|max:100',
            'nominal' => 'required|integer|min:0',
            'level' => 'required|integer',
        ]);
        $kategori = KategoriUKT::create($data);
        return response()->json(['message' => 'Kategori UKT berhasil ditambahkan.', 'data' => $kategori], 201);
    }

    // Detail kategori UKT
    public function show($id)
    {
        $kategori = KategoriUKT::findOrFail($id);
        return response()->json($kategori);
    }

    // Update kategori UKT
    public function update(Request $request, $id)
    {
        $kategori = KategoriUKT::findOrFail($id);
        $data = $request->validate([
            'kategori' => 'sometimes|string|max:100',
            'nominal' => 'sometimes|integer|min:0',
            'level' => 'sometimes|integer',
        ]);
        $kategori->update($data);
        return response()->json(['message' => 'Kategori UKT berhasil diupdate.', 'data' => $kategori]);
    }

    // Hapus kategori UKT
    public function destroy($id)
    {
        $kategori = KategoriUKT::findOrFail($id);
        $kategori->delete();
        return response()->json(['message' => 'Kategori UKT berhasil dihapus.']);
    }
}