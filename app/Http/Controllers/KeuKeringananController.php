<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeuKeringanan;
use App\Models\KeuTagihan;
use App\Models\KeuPembayaran;
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
            'status_keringanan' => 'nullable|in:Disetujui,Ditolak',
            'tgl_konfirmasi' => 'nullable|date',
            'catatan_admin' => 'nullable|string',
            'id_user' => 'required|integer', // Wajib sesuai microservice
            'id_tagihan' => 'nullable|integer', // opsional, boleh NULL
        ]);

        $keringanan = KeuKeringanan::create($data);

        if ($keringanan->status_keringanan === 'Disetujui' && $keringanan->id_tagihan) {
            $tagihan = KeuTagihan::find($keringanan->id_tagihan);
            if ($tagihan) {
                $tagihan->nominal -= $keringanan->jumlah_potongan;
                if ($tagihan->nominal < 0) $tagihan->nominal = 0;
                $tagihan->save();
    }
}
        return response()->json(['message' => 'Data keringanan berhasil ditambahkan.',
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
            'status_keringanan' => 'nullable|in:Disetujui,Ditolak',
            'tgl_konfirmasi' => 'nullable|date',
            'catatan_admin' => 'nullable|string',
            'id_user' => 'sometimes|integer',
            'id_tagihan' => 'nullable|integer', // opsional, boleh NULL
        ]);

        $keringanan->update($data);
                if ($keringanan->status_keringanan === 'Disetujui' && $keringanan->id_tagihan) {
            $tagihan = KeuTagihan::find($keringanan->id_tagihan);
            if ($tagihan) {
                $tagihan->nominal -= $keringanan->jumlah_potongan;
                if ($tagihan->nominal < 0) $tagihan->nominal = 0;
                $tagihan->save();
        }
    }
        return response()->json(['message' => 'Data tagihan berhasil diganti.',
        'data' => $keringanan]);
    }

    // Hapus
    public function destroy($id)
    {
        $keringanan = KeuKeringanan::findOrFail($id);
         // Kembalikan nominal tagihan jika status = Disetujui dan ada id_tagihan
    if ($keringanan->status_keringanan === 'Disetujui' && $keringanan->id_tagihan) {
        $tagihan = \App\Models\KeuTagihan::find($keringanan->id_tagihan);
        if ($tagihan) {
            $tagihan->nominal += $keringanan->jumlah_potongan;
            $tagihan->save();

            // Cek kembali status lunas
            $totalBayar = \App\Models\KeuPembayaran::where('id_tagihan', $tagihan->id_tagihan)
                ->where('status_verifikasi', 'Terverifikasi')
                ->sum('jumlah_bayar');

            $tagihan->status_tagihan = $totalBayar >= $tagihan->nominal ? 1 : 0;
            $tagihan->save();
        }
    }
        $keringanan->delete();

        return response()->json(['message' => 'Data keringanan berhasil dihapus']);
    }

    // (Opsional) Ambil keringanan berdasarkan NIM
    public function byNim($nim)
    {
        return response()->json(KeuKeringanan::where('nim', $nim)->get());
    }
}
