<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['absen', 'store']);
    }

    public function absen()
    {
        return view('pages.absensi');
    }

    public function store(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');

        if ($now->format('H:i') < '07:00') {
            return redirect()
                ->back()
                ->withErrors(['absen' => 'Absensi hanya dapat dilakukan mulai pukul 07:00 WIB']);
        }

        $request->validate([
            'name' => 'required',
            'status' => 'required|in:Absen Masuk,Absen Keluar',
            'image' => 'required',
            'waktu_masuk' => 'required',
        ]);

        // ========================
        // CEK DUPLIKAT ABSENSI
        // ========================
        $tanggalAbsen = Carbon::parse($request->waktu_masuk, 'Asia/Jakarta')->toDateString();

        $sudahAbsen = Absensi::where('name', $request->name)->where('status', $request->status)->whereDate('waktu_masuk', $tanggalAbsen)->exists();

        if ($sudahAbsen) {
            return redirect()
                ->back()
                ->withErrors([
                    'absen' => "❌ {$request->name} sudah melakukan {$request->status} pada tanggal {$tanggalAbsen}",
                ]);
        }

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
        $waktu = Carbon::parse($request->waktu_masuk, 'Asia/Jakarta');

        // 1 = Senin ... 6 = Sabtu, 7 = Minggu
        $hari = $waktu->isoWeekday();

        $keterangan = 'Tepat Waktu';

        // ========================
        // WEEKEND = LEMBUR
        // ========================
        if ($hari == 6 || $hari == 7) {
            $keterangan = 'Lembur';
        } else {
            // ========================
            // HARI KERJA
            // ========================
            if ($status === 'Absen Masuk' && $waktu->format('H:i') > '08:30') {
                $keterangan = 'Terlambat';
            }

            if ($status === 'Absen Keluar' && $waktu->format('H:i') > '19:00') {
                $keterangan = 'Lembur';
            }
        }

        // ========================
        // SIMPAN KE DATABASE
        // ========================
        Absensi::create([
            'name' => $request->name,
            'photo_name' => $imageName,
            'status' => $status,
            'keterangan' => $keterangan,
            'waktu_masuk' => $request->waktu_masuk,
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil disimpan');
    }
}
