<?php

namespace App\Http\Controllers;

use App\Models\KategoriUKT;
use Illuminate\Http\Request;

class KategoriUKTController extends Controller
{
    // Tampilkan semua kategori UKT
    public function index()
    {
        return response()->json(KategoriUKT::all());
    }

        // Simpan kategori UKT baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_prodi' => 'required|integer',
            'kategori_ukt' => 'required|string|max:100',
            'nominal' => 'required|integer|min:0',
        ]);
        $kategori = KategoriUKT::create($data);
        return response()->json(['message' => 'Kategori UKT berhasil ditambahkan.', 'data' => $kategori], 201);
    }

    // Detail kategori UKT
    public function show($id)
    {
        $kategori = KategoriUKT::findOrFail($id);

        // Ambil data prodi dari microservice (contoh sederhana, gunakan GuzzleHttp\Client atau Http::get)
        $prodi = null;
        try {
            $response = \Illuminate\Support\Facades\Http::get('http://alamat-microservice-prodi/api/prodi/' . $kategori->id_prodi);
            if ($response->ok()) {
                $prodi = $response->json();
            }
        } catch (\Exception $e) {
            $prodi = null;
        }

        return response()->json([
            'id_prodi' => $kategori->id_prodi,
            'nama_prodi' => $prodi['nama_prodi'] ?? null,
            'jenjang' => $prodi['jenjang'] ?? null,
            'id_kategori_ukt' => $kategori->id_kategori_ukt,
            'kategori_ukt' => $kategori->kategori_ukt,
            'nominal' => $kategori->nominal,
        ]);
    }

    // Update kategori UKT
    public function update(Request $request, $id)
    {
        $kategori = KategoriUKT::findOrFail($id);
        $data = $request->validate([
            'id_prodi' => 'required|integer',
            'kategori_ukt' => 'sometimes|string|max:100',
            'nominal' => 'sometimes|integer|min:0',
        ]);
        $kategori->update($data);
        return response()->json(['message' => 'Kategori UKT berhasil diupdate.', 'data' => $kategori]);
    }


    // Hapus kategori UKT
    public function destroy($id)
    {
        $kategori = KategoriUKT::findOrFail($id);
        $kategori->delete();
        return response()->json(['message' => 'Kategori UKT berhasil dihapus.']);
    }
}