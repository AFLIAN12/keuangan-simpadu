<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeuTagihan;

class KeuTagihanController extends Controller
{
    // Ambil semua data tagihan
    public function index()
    {
        return response()->json(KeuTagihan::all());
    }

    // Simpan data tagihan baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|string|max:20',
            'nama_tagihan' => 'required|string|max:100',
            'tahun_ajaran' => 'required|string|max:10',
            'nominal' => 'required|numeric|min:0',
            'status_tagihan' => 'in:0,1',
            'kategori_ukt' => 'nullable|string|max:100',
            'tgl_terbit' => 'required|date',
            'tgl_registrasi' => 'nullable|date',
            'id_user' => 'required|integer', // penyesuaian untuk microservice
        ]);

        $tagihan = KeuTagihan::create($data);
        return response()->json([
        'message' => 'Data tagihan berhasil ditambahkan.',
        'data' => $tagihan
    ], 201);
    }

    // Ambil detail tagihan
    public function show($id)
    {
        return response()->json(KeuTagihan::findOrFail($id));
    }

    // Perbarui tagihan
    public function update(Request $request, $id)
    {
        $tagihan = KeuTagihan::findOrFail($id);

        $data = $request->validate([
            'nama_tagihan' => 'sometimes|string|max:100',
            'tahun_ajaran' => 'sometimes|string|max:10',
            'nominal' => 'sometimes|numeric|min:0',
            'status_tagihan' => 'in:0,1',
            'kategori_ukt' => 'nullable|string|max:100',
            'tgl_terbit' => 'sometimes|date',
            'tgl_registrasi' => 'nullable|date',
            'id_user' => 'sometimes|integer',
        ]);

        $tagihan->update($data);
        return response()->json(['message' => 'Data tagihan berhasil diganti.',
        'data' => $tagihan]);
    }

    // Hapus tagihan
    public function destroy($id)
    {
        $tagihan = KeuTagihan::findOrFail($id);
        $tagihan->delete();

        return response()->json(['message' => 'Data tagihan berhasil dihapus']);
    }

    // (Opsional) Ambil tagihan berdasarkan NIM
    public function byNim($nim)
    {
        return response()->json(KeuTagihan::where('nim', $nim)->get());
    }
}
