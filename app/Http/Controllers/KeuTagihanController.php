<?php

namespace App\Http\Controllers;

use App\Models\KeuTagihan;
use App\Models\KeuKeringanan;
use Illuminate\Http\Request;

class KeuTagihanController extends Controller
{
    // Tampilkan semua tagihan
    public function index()
    {
        return response()->json(KeuTagihan::all());
    }

    // Simpan data tagihan baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|string|size:16',
            'nama_tagihan' => 'required|string|max:100',
            'id_thn_ak' => 'required|string|size:5',
            'id_kategori_ukt' => 'required|integer|exists:tabel_kategori_ukt,id_kategori_ukt',
            'status_tagihan' => 'in:0,1',
            'tgl_terbit' => 'required|date',
        ]);
        $tagihan = KeuTagihan::create($data);
        return response()->json(['message' => 'Tagihan berhasil ditambahkan.', 'data' => $tagihan], 201);
    }

    // Detail tagihan
    public function show($id)
    {
        $tagihan = KeuTagihan::with('kategoriUkt')->findOrFail($id);

    return response()->json([
        'id_tagihan' => $tagihan->id_tagihan,
        'nim' => $tagihan->nim,
        'nama_tagihan' => $tagihan->nama_tagihan,
        'id_thn_ak' => $tagihan->id_thn_ak,
        'status_tagihan' => $tagihan->status_tagihan,
        'tgl_terbit' => $tagihan->tgl_terbit,
        'id_kategori_ukt' => $tagihan->id_kategori_ukt,
        'kategori_ukt' => $tagihan->kategoriUkt ? $tagihan->kategoriUkt->kategori : null,
        'nominal' => $tagihan->kategoriUkt ? $tagihan->kategoriUkt->nominal : null,
    ]);
    }

    // Update tagihan
    public function update(Request $request, $id)
    {
        $tagihan = KeuTagihan::findOrFail($id);
        $data = $request->validate([
            'nim' => 'sometimes|string|size:16',
            'nama_tagihan' => 'sometimes|string|max:100',
            'id_thn_ak' => 'sometimes|string|size:5',
            'id_kategori_ukt' => 'sometimes|integer|exists:tabel_kategori_ukt,id_kategori_ukt',
            'status_tagihan' => 'in:0,1',
            'tgl_terbit' => 'sometimes|date',
        ]);
        $tagihan->update($data);
        return response()->json(['message' => 'Tagihan berhasil diupdate.', 'data' => $tagihan]);
    }

    // Hapus tagihan
    public function destroy($id)
    {
        $tagihan = KeuTagihan::findOrFail($id);
        $tagihan->delete();
        return response()->json(['message' => 'Tagihan berhasil dihapus.']);
    }

    // Hitung nominal akhir tagihan setelah potongan keringanan
    public function nominalAkhir($id)
    {
    $tagihan = KeuTagihan::with('kategoriUkt')->findOrFail($id);

    // Ambil nominal UKT dari relasi kategori UKT
    $nominalUkt = $tagihan->kategoriUkt ? $tagihan->kategoriUkt->nominal : 0;

    // Hitung total potongan keringanan yang Disetujui untuk tagihan ini
    $totalPotongan = KeuKeringanan::where('id_tagihan', $id)
        ->where('status_keringanan', 'Disetujui')
        ->sum('jumlah_potongan');

    $nominalAkhir = max($nominalUkt - $totalPotongan, 0);

    return response()->json([
        'id_tagihan' => $tagihan->id_tagihan,
        'nim' => $tagihan->nim,
        'nama_tagihan' => $tagihan->nama_tagihan,
        'kategori_ukt' => $tagihan->kategoriUkt ? $tagihan->kategoriUkt->kategori : null,
        'nominal_ukt' => $nominalUkt,
        'total_potongan' => $totalPotongan,
        'nominal_akhir' => $nominalAkhir
    ]);
}
}
