<?php

namespace App\Http\Controllers;

use App\Models\KeuPembayaran;
use App\Models\KeuTagihan;
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
        if ($pembayaran->status_verifikasi === 'Terverifikasi') {
        $totalBayar = KeuPembayaran::where('id_tagihan', $pembayaran->id_tagihan)
            ->where('status_verifikasi', 'Terverifikasi')
            ->sum('jumlah_bayar');

        $tagihan = KeuTagihan::find($pembayaran->id_tagihan);
        if ($tagihan && $totalBayar >= $tagihan->nominal) {
            $tagihan->status_tagihan = 1; // Lunas
            $tagihan->save();
        }}
        return response()->json(['message' => 'Data pembayaran berhasil ditambahkan.',
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
        if ($pembayaran->status_verifikasi === 'Terverifikasi') {
        $totalBayar = KeuPembayaran::where('id_tagihan', $pembayaran->id_tagihan)
            ->where('status_verifikasi', 'Terverifikasi')
            ->sum('jumlah_bayar');

        $tagihan = KeuTagihan::find($pembayaran->id_tagihan);
        if ($tagihan && $totalBayar >= $tagihan->nominal) {
            $tagihan->status_tagihan = 1; // Lunas
            $tagihan->save();}}

        return response()->json(['message' => 'Data pembayaran berhasil diganti.',
        'data' => $pembayaran]);
    }

    // Hapus data pembayaran
    public function destroy($id)
{
    $pembayaran = KeuPembayaran::findOrFail($id);
    $idTagihan = $pembayaran->id_tagihan;
    $pembayaran->delete();

    // Update status tagihan jika perlu
    $tagihan = KeuTagihan::find($idTagihan);
    if ($tagihan) {
        $totalBayar = KeuPembayaran::where('id_tagihan', $idTagihan)
            ->where('status_verifikasi', 'Terverifikasi')
            ->sum('jumlah_bayar');
        $tagihan->status_tagihan = $totalBayar >= $tagihan->nominal ? 1 : 0;
        $tagihan->save();
    }

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
