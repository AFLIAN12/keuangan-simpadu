<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeuLogAktivitas;

class KeuLogAktivitasController extends Controller
{
    // Ambil semua log aktivitas
    public function index()
    {
        return response()->json(KeuLogAktivitas::all());
    }

    // Simpan log baru (biasanya otomatis saat aksi dilakukan)
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_user' => 'required|integer',
            'aktivitas' => 'required|string|max:100',
            'entitas' => 'required|string|max:50',
            'entitas_id' => 'required|integer',
            'tgl_log' => 'nullable|date', // bisa diisi otomatis jika tidak dikirim
        ]);

        if (empty($data['tgl_log'])) {
            $data['tgl_log'] = now();
        }

        $log = KeuLogAktivitas::create($data);
        return response()->json($log, 201);
    }

    // Ambil detail log
    public function show($id)
    {
        return response()->json(KeuLogAktivitas::findOrFail($id));
    }

    // Update log tidak diizinkan (karena sifatnya historis)
    public function update()
    {
        return response()->json(['message' => 'Log tidak dapat diperbarui.'], 405);
    }

    // Hapus log (jika perlu, misal pembersihan rutin)
    public function destroy($id)
    {
        $log = KeuLogAktivitas::findOrFail($id);
        $log->delete();

        return response()->json(['message' => 'Log berhasil dihapus']);
    }
}
