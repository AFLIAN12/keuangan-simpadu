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
            'nim' => 'required|string|max:20',
            'tahun_ajaran' => 'required|string|max:10',
            'jenis_keringanan' => 'required|string|max:50',
            'jumlah_potongan' => 'required|numeric|min:0',
            'deskripsi_keringanan' => 'nullable|string',
            'status_keringanan' => 'in:Disetujui,Ditolak',
            'tgl_konfirmasi' => 'nullable|date',
            'catatan_admin' => 'nullable|string',
            'id_user' => 'required|integer', // Wajib sesuai microservice
        ]);

        $keringanan = KeuKeringanan::create($data);
        return response()->json(['message' => 'Data tagihan berhasil ditambahkan.',
        'data' => $keringanan], 201);
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
            'jumlah_potongan' => 'sometimes|numeric|min:0',
            'deskripsi_keringanan' => 'nullable|string',
            'status_keringanan' => 'in:Disetujui,Ditolak',
            'tgl_konfirmasi' => 'nullable|date',
            'catatan_admin' => 'nullable|string',
            'id_user' => 'sometimes|integer',
        ]);

        $keringanan->update($data);
        return response()->json(['message' => 'Data tagihan berhasil diganti.',
        'data' => $keringanan]);
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
