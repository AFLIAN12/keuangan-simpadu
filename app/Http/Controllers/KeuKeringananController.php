<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeuKeringanan;

class KeuKeringananController extends Controller
{
    // Tampilkan semua keringanan
    public function index()
    {
        return response()->json(KeuKeringanan::all());
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|string|size:16',
            'id_thn_ak' => 'required|string|size:5',
            'jenis_keringanan' => 'required|string|max:50',
            'jumlah_potongan' => 'required|integer|min:0',
            'deskripsi_keringanan' => 'nullable|string',
            'status_keringanan' => 'nullable|in:Disetujui,Ditolak',
            'tgl_konfirmasi' => 'nullable|date',
            'id_tagihan' => 'nullable|integer',
        ]);

        $keringanan = KeuKeringanan::create($data);

        return response()->json([
            'message' => 'Data keringanan berhasil ditambahkan.',
            'data' => $keringanan
        ], 201);
    }

    // Detail keringanan
    public function show($id)
    {
        $keringanan = KeuKeringanan::findOrFail($id);
        return response()->json($keringanan);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $keringanan = KeuKeringanan::findOrFail($id);
        $data = $request->validate([
            'jenis_keringanan' => 'sometimes|string|max:50',
            'jumlah_potongan' => 'sometimes|integer|min:0',
            'deskripsi_keringanan' => 'nullable|string',
            'status_keringanan' => 'nullable|in:Disetujui,Ditolak',
            'tgl_konfirmasi' => 'nullable|date',
            'id_tagihan' => 'nullable|integer',
        ]);

        $keringanan->update($data);

        return response()->json([
            'message' => 'Data keringanan berhasil diupdate.',
            'data' => $keringanan
        ]);
    }

    // Hapus
    public function destroy($id)
    {
        $keringanan = KeuKeringanan::findOrFail($id);
        $keringanan->delete();

        return response()->json(['message' => 'Data keringanan berhasil dihapus']);
    }

    // (Opsional) Ambil keringanan berdasarkan NIM
    public function byNim($nim)
    {
        return response()->json(KeuKeringanan::where('nim', $nim)->get());
    }
}