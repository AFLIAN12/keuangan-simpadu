<?php

namespace App\Http\Controllers;

use App\Models\KeuTagihan;
use App\Models\KeuKeringanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Keuangan Simpadu API",
 *     description="Dokumentasi API Keuangan Simpadu"
 * )
 */

class KeuTagihanController extends Controller
{
  /**
 * @OA\Get(
 *     path="/api/tagihan",
 *     summary="Tampilkan semua tagihan",
 *     tags={"Tagihan"},
 *     @OA\Response(
 *         response=200,
 *         description="Berhasil mengambil data",
 *         @OA\JsonContent(type="array", @OA\Items(
 *             @OA\Property(property="id_tagihan", type="integer"),
 *             @OA\Property(property="nim", type="string"),
 *             @OA\Property(property="nama_tagihan", type="string"),
 *             @OA\Property(property="id_thn_ak", type="string"),
 *             @OA\Property(property="id_kategori_ukt", type="integer"),
 *             @OA\Property(property="status_tagihan", type="integer"),
 *             @OA\Property(property="tgl_terbit", type="string", format="date")
 *         ))
 *     )
 * )
 */
    // Tampilkan semua tagihan
    public function index()
    {
        return response()->json(KeuTagihan::all());
    }

    /**
 * @OA\Post(
 *     path="/api/tagihan",
 *     summary="Tambah tagihan baru",
 *     tags={"Tagihan"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nim","nama_tagihan","id_thn_ak","id_kategori_ukt","tgl_terbit"},
 *             @OA\Property(property="nim", type="string", example="1234567890123456"),
 *             @OA\Property(property="nama_tagihan", type="string", example="UKT Semester Genap 2025"),
 *             @OA\Property(property="id_thn_ak", type="string", example="20245"),
 *             @OA\Property(property="id_kategori_ukt", type="integer", example=1),
 *             @OA\Property(property="status_tagihan", type="integer", example=0),
 *             @OA\Property(property="tgl_terbit", type="string", format="date", example="2025-01-10")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Tagihan berhasil ditambahkan"
 *     )
 * )
 */

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

/**
 * @OA\Get(
 *     path="/api/tagihan/{id}",
 *     summary="Detail tagihan",
 *     tags={"Tagihan"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success",
 *         @OA\JsonContent(
 *             @OA\Property(property="id_tagihan", type="integer"),
 *             @OA\Property(property="nim", type="string"),
 *             @OA\Property(property="nama_tagihan", type="string"),
 *             @OA\Property(property="id_thn_ak", type="string"),
 *             @OA\Property(property="nama_thn_ak", type="string"),
 *             @OA\Property(property="status_tagihan", type="integer"),
 *             @OA\Property(property="tgl_terbit", type="string", format="date"),
 *             @OA\Property(property="id_kategori_ukt", type="integer"),
 *             @OA\Property(property="kategori_ukt", type="string"),
 *             @OA\Property(property="nominal", type="integer")
 *         )
 *     )
 * )
 */
    // Detail tagihan
    public function show($id)
    {
        $tagihan = KeuTagihan::with('kategoriUkt')->findOrFail($id);

    // Ambil nama_thn_ak dari microservice
    $nama_thn_ak = null;
    try {
        $response = Http::get('http://alamat-microservice-akademik/api/thn-ak/' . $tagihan->id_thn_ak);
        if ($response->ok()) {
            $thnAk = $response->json();
            $nama_thn_ak = $thnAk['nama_thn_ak'] ?? null;
        }
    } catch (\Exception $e) {
        $nama_thn_ak = null;
    }

    return response()->json([
        'id_tagihan' => $tagihan->id_tagihan,
        'nim' => $tagihan->nim,
        'nama_tagihan' => $tagihan->nama_tagihan,
        'id_thn_ak' => $tagihan->id_thn_ak,
        'nama_thn_ak' => $nama_thn_ak,
        'status_tagihan' => $tagihan->status_tagihan,
        'tgl_terbit' => $tagihan->tgl_terbit,
        'id_kategori_ukt' => $tagihan->id_kategori_ukt,
        'kategori_ukt' => $tagihan->kategoriUkt ? $tagihan->kategoriUkt->kategori_ukt : null,
        'nominal' => $tagihan->kategoriUkt ? $tagihan->kategoriUkt->nominal : null,
    ]);
    }

    /**
 * @OA\Put(
 *     path="/api/tagihan/{id}",
 *     summary="Update tagihan",
 *     tags={"Tagihan"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="nim", type="string"),
 *             @OA\Property(property="nama_tagihan", type="string"),
 *             @OA\Property(property="id_thn_ak", type="string"),
 *             @OA\Property(property="id_kategori_ukt", type="integer"),
 *             @OA\Property(property="status_tagihan", type="integer"),
 *             @OA\Property(property="tgl_terbit", type="string", format="date")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tagihan berhasil diupdate"
 *     )
 * )
 */

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

    /**
 * @OA\Delete(
 *     path="/api/tagihan/{id}",
 *     summary="Hapus tagihan",
 *     tags={"Tagihan"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tagihan berhasil dihapus"
 *     )
 * )
 */
    // Hapus tagihan
    public function destroy($id)
    {
        $tagihan = KeuTagihan::findOrFail($id);
        $tagihan->delete();
        return response()->json(['message' => 'Tagihan berhasil dihapus.']);
    }
    
/**
 * @OA\Get(
 *     path="/api/tagihan/{id}/nominal-akhir",
 *     summary="Hitung nominal akhir tagihan setelah potongan keringanan",
 *     tags={"Tagihan"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Nominal akhir tagihan",
 *         @OA\JsonContent(
 *             @OA\Property(property="id_tagihan", type="integer"),
 *             @OA\Property(property="nim", type="string"),
 *             @OA\Property(property="nama_tagihan", type="string"),
 *             @OA\Property(property="kategori_ukt", type="string"),
 *             @OA\Property(property="nominal_ukt", type="integer"),
 *             @OA\Property(property="total_potongan", type="integer"),
 *             @OA\Property(property="nominal_akhir", type="integer")
 *         )
 *     )
 * )
 */
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
        'kategori_ukt' => $tagihan->kategoriUkt ? $tagihan->kategoriUkt->kategori_ukt : null,
        'nominal_ukt' => $nominalUkt,
        'total_potongan' => $totalPotongan,
        'nominal_akhir' => $nominalAkhir
    ]);
}
}
