<?php

namespace App\Http\Controllers;

use App\Models\OrderTbl;
use App\Models\ApprovalTbl;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function transaksi()
    {
        return view('pages.transaksi');
    }

    public function pengguna()
    {
        return view('pages.pengguna');
    }

    public function layanan()
    {
        return view('pages.layanan');
    }

    public function rekapabsensi()
    {
        return view('pages.rekapabsensi');
    }

    public function listpengajuan()
    {
        $pendingCount = ApprovalTbl::where('approval', 'Pending')->count();
        return view('pages.list-pengajuan', compact('pendingCount'));
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function project()
    {
        return view('pages.project');
    }

    public function konsumen()
    {
        return view('pages.konsumen');
    }
}
