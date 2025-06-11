<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeuKeringanan;

class KeuKeringananController extends Controller
{
 /**
 * @OA\Get(
 *     path="/api/keringanan",
 *     summary="Tampilkan semua keringanan",
 *     tags={"Keringanan"},
 *     @OA\Response(
 *         response=200,
 *         description="Berhasil mengambil data",
 *         @OA\JsonContent(type="array", @OA\Items(
 *             @OA\Property(property="id_keringanan", type="integer"),
 *             @OA\Property(property="nim", type="string"),
 *             @OA\Property(property="id_thn_ak", type="string"),
 *             @OA\Property(property="jenis_keringanan", type="string"),
 *             @OA\Property(property="jumlah_potongan", type="integer"),
 *             @OA\Property(property="deskripsi_keringanan", type="string"),
 *             @OA\Property(property="status_keringanan", type="string"),
 *             @OA\Property(property="tgl_konfirmasi", type="string", format="date"),
 *             @OA\Property(property="id_tagihan", type="integer")
 *         ))
 *     )
 * )
 */
    // Tampilkan semua keringanan
    public function index()
    {
        return response()->json(KeuKeringanan::all());
    }
/**
 * @OA\Post(
 *     path="/api/keringanan",
 *     summary="Tambah data keringanan",
 *     tags={"Keringanan"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"nim","id_thn_ak","jenis_keringanan","jumlah_potongan"},
 *             @OA\Property(property="nim", type="string", example="1234567890123456"),
 *             @OA\Property(property="id_thn_ak", type="string", example="20245"),
 *             @OA\Property(property="jenis_keringanan", type="string", example="Potongan Prestasi"),
 *             @OA\Property(property="jumlah_potongan", type="integer", example=500000),
 *             @OA\Property(property="deskripsi_keringanan", type="string", example="Potongan karena juara olimpiade"),
 *             @OA\Property(property="status_keringanan", type="string", example="Disetujui"),
 *             @OA\Property(property="tgl_konfirmasi", type="string", format="date", example="2025-01-20"),
 *             @OA\Property(property="id_tagihan", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Data keringanan berhasil ditambahkan"
 *     )
 * )
 */
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

    /**
 * @OA\Get(
 *     path="/api/keringanan/{id}",
 *     summary="Detail keringanan",
 *     tags={"Keringanan"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Berhasil mengambil detail",
 *         @OA\JsonContent(
 *             @OA\Property(property="id_keringanan", type="integer"),
 *             @OA\Property(property="nim", type="string"),
 *             @OA\Property(property="id_thn_ak", type="string"),
 *             @OA\Property(property="jenis_keringanan", type="string"),
 *             @OA\Property(property="jumlah_potongan", type="integer"),
 *             @OA\Property(property="deskripsi_keringanan", type="string"),
 *             @OA\Property(property="status_keringanan", type="string"),
 *             @OA\Property(property="tgl_konfirmasi", type="string", format="date"),
 *             @OA\Property(property="id_tagihan", type="integer")
 *         )
 *     )
 * )
 */
    // Detail keringanan
    public function show($id)
    {
        $keringanan = KeuKeringanan::findOrFail($id);
        return response()->json($keringanan);
    }

    /**
 * @OA\Put(
 *     path="/api/keringanan/{id}",
 *     summary="Update data keringanan",
 *     tags={"Keringanan"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="jenis_keringanan", type="string"),
 *             @OA\Property(property="jumlah_potongan", type="integer"),
 *             @OA\Property(property="deskripsi_keringanan", type="string"),
 *             @OA\Property(property="status_keringanan", type="string"),
 *             @OA\Property(property="tgl_konfirmasi", type="string", format="date"),
 *             @OA\Property(property="id_tagihan", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Data keringanan berhasil diupdate"
 *     )
 * )
 */
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
/**
 * @OA\Delete(
 *     path="/api/keringanan/{id}",
 *     summary="Hapus data keringanan",
 *     tags={"Keringanan"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Data keringanan berhasil dihapus"
 *     )
 * )
 */
    // Hapus
    public function destroy($id)
    {
        $keringanan = KeuKeringanan::findOrFail($id);
        $keringanan->delete();

        return response()->json(['message' => 'Data keringanan berhasil dihapus']);
    }
/**
 * @OA\Get(
 *     path="/api/keringanan/nim/{nim}",
 *     summary="Tampilkan semua keringanan berdasarkan NIM",
 *     tags={"Keringanan"},
 *     @OA\Parameter(
 *         name="nim",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Berhasil mengambil data",
 *         @OA\JsonContent(type="array", @OA\Items(
 *             @OA\Property(property="id_keringanan", type="integer"),
 *             @OA\Property(property="nim", type="string"),
 *             @OA\Property(property="id_thn_ak", type="string"),
 *             @OA\Property(property="jenis_keringanan", type="string"),
 *             @OA\Property(property="jumlah_potongan", type="integer"),
 *             @OA\Property(property="deskripsi_keringanan", type="string"),
 *             @OA\Property(property="status_keringanan", type="string"),
 *             @OA\Property(property="tgl_konfirmasi", type="string", format="date"),
 *             @OA\Property(property="id_tagihan", type="integer")
 *         ))
 *     )
 * )
 */
    // (Opsional) Ambil keringanan berdasarkan NIM
    public function byNim($nim)
    {
        return response()->json(KeuKeringanan::where('nim', $nim)->get());
    }
}