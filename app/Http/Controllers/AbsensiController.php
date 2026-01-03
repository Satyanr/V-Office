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
            'image' => 'required',
        ]);

        $image = $request->image;
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        $imageName = 'absensi_' . time() . '_' . Str::random(5) . '.png';

        $path = public_path('storage/absensi');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($path . '/' . $imageName, base64_decode($image));

        Absensi::create([
            'name' => $request->name,
            'photo_name' => $imageName,
        ]);

        // 5. Response
        return redirect()->back()->with('success', 'Absensi berhasil disimpan');
    }
}
