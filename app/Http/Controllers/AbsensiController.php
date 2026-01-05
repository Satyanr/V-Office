<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function absen()
    {
        return view('pages.absensi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required|in:Absen Masuk,Absen Keluar',
            'image' => 'required',
            'waktu_masuk' => 'required',
        ]);

        // ========================
        // SIMPAN FOTO
        // ========================
        $image = $request->image;
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
        $image = str_replace(' ', '+', $image);

        $imageName = 'absensi_' . time() . '_' . Str::random(5) . '.png';
        $path = public_path('storage/absensi');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($path . '/' . $imageName, base64_decode($image));

        // ========================
        // HITUNG KETERANGAN
        // ========================
        $status = $request->status;
        $waktu = \Carbon\Carbon::parse($request->waktu_masuk);

        $keterangan = 'Tepat Waktu';

        if ($status === 'Absen Masuk' && $waktu->format('H:i') > '08:30') {
            $keterangan = 'Terlambat';
        }

        if ($status === 'Absen Keluar' && $waktu->format('H:i') > '19:00') {
            $keterangan = 'Lembur';
        }

        // ========================
        // SIMPAN KE DATABASE
        // ========================
        Absensi::create([
            'name' => $request->name,
            'photo_name' => $imageName,
            'status' => $status,
            'keterangan' => $keterangan,
            'waktu_masuk' => now('Asia/Jakarta'),
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil disimpan');
    }
}
