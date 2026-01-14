<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ProjectTbl;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function orderan(Request $request)
    {
        $sttsbyr = $request->input('sttsbyr');
        $status  = $request->input('status');
        $start   = Carbon::createFromFormat('Y-m-d', $request->input('start'));
        $end     = Carbon::createFromFormat('Y-m-d', $request->input('end'));

        $query = ProjectTbl::with('pembayaran');

        // filter tanggal
        if ($start->equalTo($end)) {
            $query->whereDate('created_at', $start);
        } else {
            $query->whereBetween('created_at', [$start, $end]);
        }

        // filter status project
        if ($status) {
            $query->where('status', $status);
        }

        // filter status pembayaran
        if ($sttsbyr) {
            $query->whereHas('pembayaran', function ($q) use ($sttsbyr) {
                $q->where('status_pembayaran', $sttsbyr);
            });
        }

        $projects = $query->get();

        $data = [
            'projects' => $projects,
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.laporan-pdf', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

    public function barcode($id)
    {
        $project = ProjectTbl::findOrFail($id);

        $barcode = QrCode::size(500)->generate($project->kode_project);

        $data = [
            'code'    => $project->kode_project,
            'barcode' => $barcode,
            'project' => $project,
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.barcode', $data)
            ->setPaper([0, 0, 500, 279], 'landscape');

        return $pdf->stream('barcode-' . $project->kode_project . '.pdf');
    }
}
