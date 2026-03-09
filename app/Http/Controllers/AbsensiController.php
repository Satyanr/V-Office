<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\ApprovalTbl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\KaryawanTbl;

class AbsensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['absen', 'store', 'pengajuan']);
    }

    // VIEW TIDAK PERLU DATA LAGI
    public function absen()
    {
        return view('pages.absensi');
    }

    public function pengajuan()
    {
        return view('pages.pengajuan');
    }

    public function store(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');

        if ($now->format('H:i') < '07:00') {
            return back()->withErrors([
                'absen' => 'Absensi hanya dapat dilakukan mulai pukul 07:00 WIB',
            ]);
        }

        $request->validate([
            'name' => ['required', fn($attr, $value, $fail) => !KaryawanTbl::where('name', $value)->exists() && $fail('Nama karyawan tidak terdaftar.')],
            'status' => 'required',
            'image' => 'required_if:status,Absen Masuk,Absen Pulang',
            'waktu_masuk' => 'required_if:status,Absen Masuk,Absen Pulang',
            'tanggal_mulai_izin' => 'required_if:status,Izin Tidak Masuk,Sakit,Cuti',
            'tanggal_berakhir_izin' => 'required_if:status,Izin Tidak Masuk,Sakit,Cuti',
        ]);

        $tanggal = Carbon::parse($request->waktu_masuk, 'Asia/Jakarta')->toDateString();

        $exists = Absensi::where('name', $request->name)->where('status', $request->status)->whereDate('waktu_masuk', $tanggal)->exists();

        if ($exists && $request->status !== 'Cuti') {
            return back()->withErrors([
                'absen' => "❌ {$request->name} sudah melakukan absen pada {$tanggal}",
            ]);
        }

        $imageName = null;

        if ($request->hasFile('image')) {
            // upload file biasa (pengajuan)
            $file = $request->file('image');

            $imageName = 'absensi_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('storage/absensi'), $imageName);
        } elseif ($request->filled('image')) {
            // base64 image (absensi kamera)
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $request->image);
            $image = str_replace(' ', '+', $image);

            $imageName = 'absensi_' . time() . '_' . Str::random(5) . '.png';

            $path = public_path('storage/absensi');

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            file_put_contents("$path/$imageName", base64_decode($image));
        }

        $waktu = Carbon::parse($request->waktu_masuk, 'Asia/Jakarta');
        $hari = $waktu->isoWeekday();

        $keterangan = 'Tepat Waktu';

        if ($hari >= 6) {
            $keterangan = 'Lembur';
        } elseif ($request->status === 'Absen Masuk' && $waktu->format('H:i') > '08:30') {
            $keterangan = 'Terlambat';
        } elseif ($request->status === 'Absen Pulang' && $waktu->format('H:i') > '19:00') {
            $keterangan = 'Lembur';
        } elseif ($request->status === 'Absen Pulang' && $waktu->format('H:i') < '16:30') {
            $keterangan = 'Pulang Awal';
        } elseif (in_array($request->status, ['Izin Tidak Masuk', 'Sakit', 'Cuti'])) {
            $keterangan = $request->status;
        }

        if (in_array($request->status, ['Cuti', 'Izin Tidak Masuk', 'Sakit'])) {
            ApprovalTbl::create([
                'name' => $request->name,
                'photo_name' => $imageName,
                'status' => $request->status,
                'keterangan' => $request->deskripsi,
                'waktu_masuk' => $request->waktu_masuk,
                'tanggal_awal' => $request->tanggal_mulai_izin,
                'tanggal_akhir' => $request->tanggal_berakhir_izin,
            ]);
        } else {
            Absensi::create([
                'name' => $request->name,
                'photo_name' => $imageName,
                'status' => $request->status,
                'keterangan' => $keterangan,
                'waktu_masuk' => $request->waktu_masuk,
            ]);
        }

        return redirect()->route('absensi')->with('success', 'Berhasil dikirim');
    }
}
