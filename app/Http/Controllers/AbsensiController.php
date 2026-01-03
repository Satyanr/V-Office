<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function absen()
    {
        return view('pages.absensi');
    }
}
