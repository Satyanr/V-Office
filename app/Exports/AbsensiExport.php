<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AbsensiExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell, WithMapping
{
    protected $fromDate;
    protected $toDate;
    protected $data; // SIMPAN DATA FILTER
    protected $summary;

    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function collection()
    {
        $query = Absensi::query();

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('waktu_masuk', [$this->fromDate . ' 00:00:00', $this->toDate . ' 23:59:59']);
        }

        $this->data = $query->orderBy('waktu_masuk', 'ASC')->get();

        // ===== SUMMARY PER NAMA =====
        $this->summary = $this->data->groupBy('name')->map(function ($rows) {
            return [
                'tepat_waktu' => $rows->where('keterangan', 'Tepat Waktu')->count(),
                'terlambat' => $rows->where('keterangan', 'Terlambat')->count(),
                'lembur' => $rows->where('keterangan', 'Lembur')->count(),
                'izin' => $rows->where('status', 'Izin Tidak Masuk')->count(),
                'sakit' => $rows->where('status', 'Sakit')->count(),
                'cuti' => $rows->where('status', 'Cuti')->count(),
                'total' => $rows->count(),
            ];
        });

        return $this->data;
    }

    public function headings(): array
    {
        return ['Nama', 'Tanggal & Waktu', 'Status', 'Keterangan', 'Foto'];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->waktu_masuk,
            ucfirst($row->status),
            $row->keterangan,
            '', // kolom foto
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $columnCount = count($this->headings());
                $lastColumn = Coordinate::stringFromColumnIndex($columnCount);

                /* ================= JUDUL ================= */
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue('A1', 'REKAP ABSENSI KARYAWAN');
                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(32);

                /* ================= HEADER ================= */
                $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->setAutoFilter("A2:{$lastColumn}2");

                /* ================= LEBAR KOLOM ================= */
                $sheet->getColumnDimension('A')->setWidth(25);
                $sheet->getColumnDimension('B')->setWidth(22);
                $sheet->getColumnDimension('C')->setWidth(14);
                $sheet->getColumnDimension('D')->setWidth(18);
                $sheet->getColumnDimension('E')->setWidth(20);

                /* ================= FOTO ================= */
                $rowStart = 3;

                foreach ($this->data as $index => $absensi) {
                    $row = $rowStart + $index;
                    $photoColumn = 'E';

                    $sheet->getRowDimension($row)->setRowHeight(75);

                    $path = $absensi->photo_name ? public_path('storage/absensi/' . $absensi->photo_name) : null;

                    if ($path && file_exists($path)) {
                        $drawing = new Drawing();
                        $drawing->setPath($path);
                        $drawing->setHeight(60);
                        $drawing->setCoordinates($photoColumn . $row);
                        $drawing->setOffsetX(10);
                        $drawing->setOffsetY(5);
                        $drawing->setWorksheet($sheet);
                    } else {
                        $sheet->setCellValue($photoColumn . $row, 'No Photo');
                        $sheet->getStyle($photoColumn . $row)->applyFromArray([
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                            'font' => [
                                'italic' => true,
                                'color' => ['rgb' => '9CA3AF'],
                            ],
                        ]);
                        $sheet->getRowDimension($row)->setRowHeight(30);
                    }
                }

                /* ================= BORDER ================= */
                $endRow = 2 + $this->data->count();

                $sheet
                    ->getStyle("A2:{$lastColumn}{$endRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                /* ================= SHEET REKAP ================= */
                $spreadsheet = $event->sheet->getParent();
                $rekapSheet = $spreadsheet->createSheet();
                $rekapSheet->setTitle('Rekap Absensi');

                // Header
                $rekapSheet->fromArray([['Nama', 'Tepat Waktu', 'Terlambat', 'Lembur', 'Total']], null, 'A1');

                // Style header
                $rekapSheet->getStyle('A1:E1')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $row = 2;

                foreach ($this->summary as $name => $count) {
                    $rekapSheet->fromArray([[$name, $count['tepat_waktu'], $count['terlambat'], $count['lembur'], $count['total']]], null, 'A' . $row);

                    $row++;
                }

                // Auto width
                foreach (range('A', 'E') as $col) {
                    $rekapSheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Border
                $rekapSheet
                    ->getStyle('A1:E' . ($row - 1))
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
