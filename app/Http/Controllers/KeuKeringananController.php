<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeuKeringanan;
use Illuminate\Support\Facades\Http;

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
 *     summary="Detail keringanan berdasarkan ID (beserta data mahasiswa dan nama tahun akademik dari microservice)",
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
 *             @OA\Property(
 *                 property="mahasiswa",
 *                 type="object",
 *                 @OA\Property(property="nim", type="string"),
 *                 @OA\Property(property="nama", type="string"),
 *                 @OA\Property(property="prodi", type="string"),
 *                 @OA\Property(property="jenjang", type="string")
 *             ),
 *             @OA\Property(property="id_thn_ak", type="string"),
 *             @OA\Property(property="nama_thn_ak", type="string"),
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
public function show($id)
{
    $keringanan = KeuKeringanan::findOrFail($id);

    // Ambil nama_thn_ak dari microservice tahun akademik
    $nama_thn_ak = null;
    try {
        $response = Http::get('http://alamat-microservice-akademik/api/thn-ak/' . $keringanan->id_thn_ak);
        if ($response->ok()) {
            $thnAk = $response->json();
            $nama_thn_ak = $thnAk['nama_thn_ak'] ?? null;
        }
    } catch (\Exception $e) {
        $nama_thn_ak = null;
    }

    // Ambil data mahasiswa dari microservice mahasiswa
    $mahasiswa = null;
    try {
        $response = Http::get('http://alamat-microservice-mahasiswa/api/mahasiswa/' . $keringanan->nim);
        if ($response->ok()) {
            $mahasiswa = $response->json();
        }
    } catch (\Exception $e) {
        $mahasiswa = null;
    }

    return response()->json([
        'id_keringanan' => $keringanan->id_keringanan,
        'nim' => $keringanan->nim,
        'mahasiswa' => $mahasiswa,
        'id_thn_ak' => $keringanan->id_thn_ak,
        'nama_thn_ak' => $nama_thn_ak,
        'jenis_keringanan' => $keringanan->jenis_keringanan,
        'jumlah_potongan' => $keringanan->jumlah_potongan,
        'deskripsi_keringanan' => $keringanan->deskripsi_keringanan,
        'status_keringanan' => $keringanan->status_keringanan,
        'tgl_konfirmasi' => $keringanan->tgl_konfirmasi,
        'id_tagihan' => $keringanan->id_tagihan,
    ]);
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
}