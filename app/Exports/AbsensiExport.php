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
    public function collection()
    {
        return Absensi::select('name', 'waktu_masuk', 'photo_name')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Waktu Masuk', 'Foto'];
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
            '', // kolom foto
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // =============================
                // JUMLAH KOLOM
                // =============================
                $columnCount = count($this->headings());
                $lastColumn = Coordinate::stringFromColumnIndex($columnCount);

                // =============================
                // JUDUL
                // =============================
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue('A1', 'REKAP ABSENSI KARYAWAN');
                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // =============================
                // HEADER
                // =============================
                $sheet->getStyle("A2:{$lastColumn}2")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // =============================
                // LEBAR KOLOM (ANTI BERDEMPET)
                // =============================
                $sheet->getColumnDimension('A')->setWidth(25); // Nama
                $sheet->getColumnDimension('B')->setWidth(22); // Waktu Masuk
                $sheet->getColumnDimension('C')->setWidth(18); // Foto

                // Center kolom waktu
                $sheet->getStyle('B:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

                // =============================
                // GAMBAR + FALLBACK
                // =============================
                $rowStart = 3;
                foreach (Absensi::all() as $index => $absensi) {
                    $currentRow = $rowStart + $index;
                    $photoColumn = 'C';

                    // tinggi baris default
                    $sheet->getRowDimension($currentRow)->setRowHeight(75);

                    $path = $absensi->photo_name ? public_path('storage/absensi/' . $absensi->photo_name) : null;

                    if ($path && file_exists($path)) {
                        $drawing = new Drawing();
                        $drawing->setPath($path);
                        $drawing->setHeight(60);
                        $drawing->setCoordinates($photoColumn . $currentRow);
                        $drawing->setOffsetX(10);
                        $drawing->setOffsetY(5);
                        $drawing->setWorksheet($sheet);
                    } else {
                        // FALLBACK
                        $sheet->setCellValue($photoColumn . $currentRow, 'No Photo');
                        $sheet->getStyle($photoColumn . $currentRow)->applyFromArray([
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                            'font' => [
                                'italic' => true,
                                'color' => ['rgb' => '9CA3AF'],
                            ],
                        ]);

                        $sheet->getRowDimension($currentRow)->setRowHeight(30);
                    }
                }
                
                // =============================
                // BORDER HEADER + ISI TABEL
                // =============================
                $dataCount = Absensi::count();
                $startRow = 2; // header row
                $endRow = 2 + $dataCount;

                $sheet
                    ->getStyle("A{$startRow}:{$lastColumn}{$endRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}
