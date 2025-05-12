<?php

namespace App\Http\Controllers;

use App\Models\KeuPembayaran;
use Illuminate\Http\Request;

class KeuPembayaranController extends Controller
{
    // Ambil semua data pembayaran
    public function index()
    {
        return response()->json(KeuPembayaran::all());
    }

    // Simpan data pembayaran baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_tagihan' => 'required|integer',
            'tgl_bayar' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode' => 'required|string|max:50',
            'status_verifikasi' => 'in:Terverifikasi,Gagal',
            'id_user' => 'required|integer', // disesuaikan untuk microservice
        ]);

        $pembayaran = KeuPembayaran::create($data);
        return response()->json(['message' => 'Data tagihan berhasil ditambahkan.',
        'data' => $pembayaran], 201);
    }

    // Ambil detail pembayaran berdasarkan ID
    public function show($id)
    {
        return response()->json(KeuPembayaran::findOrFail($id));
    }

    // Perbarui data pembayaran
    public function update(Request $request, $id)
    {
        $pembayaran = KeuPembayaran::findOrFail($id);

        $data = $request->validate([
            'tgl_bayar' => 'sometimes|date',
            'jumlah_bayar' => 'sometimes|numeric|min:0',
            'metode' => 'sometimes|string|max:50',
            'status_verifikasi' => 'in:Terverifikasi,Gagal',
            'id_user' => 'sometimes|integer',
        ]);

        $pembayaran->update($data);
        return response()->json(['message' => 'Data tagihan berhasil diganti.',
        'data' => $pembayaran]);
    }

    // Hapus data pembayaran
    public function destroy($id)
    {
        $pembayaran = KeuPembayaran::findOrFail($id);
        $pembayaran->delete();

        return response()->json(['message' => 'Data pembayaran berhasil dihapus']);
    }

    // (Opsional) Filter berdasarkan tagihan
    public function byTagihan($idTagihan)
    {
        return response()->json(
            KeuPembayaran::where('id_tagihan', $idTagihan)->get()
        );
    }
}
