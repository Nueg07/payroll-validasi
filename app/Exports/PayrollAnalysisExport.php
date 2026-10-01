<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayrollAnalysisExport implements
    FromArray,
    WithStyles,
    WithColumnWidths,
    WithEvents
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $comparison = $this->data['comparison'] ?? [];
        $currentGroup = $this->data['currentGroup'] ?? null;
        $previousGroup = $this->data['previousGroup'] ?? null;

        $rows = [];

        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'ANALISIS PAYROLL'
        ];

        $rows[] = [
            'Periode Sekarang',
            $currentGroup['label'] ?? '-'
        ];

        $rows[] = [
            'Periode Sebelumnya',
            $previousGroup['label'] ?? '-'
        ];

        $rows[] = [
            'Mode',
            $this->data['mode'] ?? 'monthly'
        ];

        $rows[] = [
            'Payday',
            $this->data['payday'] ?? 'all'
        ];

        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN PAYROLL
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'RINGKASAN PAYROLL'
        ];

        $rows[] = [
            'Payroll Sebelumnya',
            $comparison['payroll']['previous'] ?? 0
        ];

        $rows[] = [
            'Payroll Sekarang',
            $comparison['payroll']['current'] ?? 0
        ];

        $rows[] = [
            'Selisih Payroll',
            $comparison['payroll']['difference'] ?? 0
        ];

        $rows[] = [
            'Persentase',
            $comparison['payroll']['percentage'] !== null
                ? $comparison['payroll']['percentage'] / 100
                : null
        ];

        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN PERUBAHAN
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'RINGKASAN PERUBAHAN'
        ];

        $rows[] = [
            'Naik',
            $comparison['summary']['increase'] ?? 0
        ];

        $rows[] = [
            'Turun',
            $comparison['summary']['decrease'] ?? 0
        ];

        $rows[] = [
            'Sama',
            $comparison['summary']['same'] ?? 0
        ];

        $rows[] = [
            'Komponen Baru',
            $comparison['summary']['new'] ?? 0
        ];

        $rows[] = [
            'Komponen Hilang',
            $comparison['summary']['missing'] ?? 0
        ];

        $rows[] = [
            'Karyawan Baru',
            $comparison['summary']['newEmployees'] ?? 0
        ];

        $rows[] = [
            'Karyawan Tidak Ada',
            $comparison['summary']['missingEmployees'] ?? 0
        ];

        $rows[] = [
            'Total Kenaikan',
            $comparison['summary']['totalIncrease'] ?? 0
        ];

        $rows[] = [
            'Total Penurunan',
            $comparison['summary']['totalDecrease'] ?? 0
        ];

        $rows[] = [
            'Total Employee',
            $comparison['summary']['totalEmployees'] ?? 0
        ];

        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | TOP 10 PERUBAHAN
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'TOP 10 PERUBAHAN'
        ];

        $rows[] = [
            'No',
            'Employee No',
            'Nama',
            'Department',
            'Komponen',
            'Periode Sebelumnya',
            'Periode Sekarang',
            'Selisih',
            'Persentase',
            'Status',
            'Keterangan'
        ];

        $no = 1;

        foreach ($comparison['topChanges'] ?? [] as $row) {
            $rows[] = [
                $no++,
                $row['employee_no'] ?? '',
                $row['name'] ?? '',
                $row['department'] ?? '',
                $row['component'] ?? '',
                $row['previous'] ?? 0,
                $row['current'] ?? 0,
                $row['difference'] ?? 0,
                $row['percentage'] !== null
                    ? $row['percentage'] / 100
                    : null,
                strtoupper($row['status'] ?? ''),
                $row['description'] ?? '',
            ];
        }

        $rows[] = [];

        /*
        |--------------------------------------------------------------------------
        | DETAIL PERUBAHAN
        |--------------------------------------------------------------------------
        */

        $rows[] = [
            'DETAIL PERUBAHAN PAYROLL'
        ];

        $rows[] = [
            'No',
            'Employee No',
            'Nama',
            'Department',
            'Komponen',
            'Periode Sebelumnya',
            'Periode Sekarang',
            'Selisih',
            'Persentase',
            'Status',
            'Keterangan'
        ];

        $no = 1;

        foreach ($comparison['rows'] ?? [] as $row) {
            $rows[] = [
                $no++,
                $row['employee_no'] ?? '',
                $row['name'] ?? '',
                $row['department'] ?? '',
                $row['component'] ?? '',
                $row['previous'] ?? 0,
                $row['current'] ?? 0,
                $row['difference'] ?? 0,
                $row['percentage'] !== null
                    ? $row['percentage'] / 100
                    : null,
                strtoupper($row['status'] ?? ''),
                $row['description'] ?? '',
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        /*
        |--------------------------------------------------------------------------
        | DEFAULT
        |--------------------------------------------------------------------------
        */

        $sheet->getDefaultRowDimension()->setRowHeight(21);

        /*
        |--------------------------------------------------------------------------
        | TITLE
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:K1');

        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '123B70',
                ],
            ],
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(32);

        /*
        |--------------------------------------------------------------------------
        | META
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A2:B5')->applyFromArray([
            'font' => [
                'size' => 11,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:A5')->getFont()->setBold(true);

        /*
        |--------------------------------------------------------------------------
        | BORDER
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle(
            'A1:K' . $highestRow
        )->getBorders()->getAllBorders()->setBorderStyle(
            Border::BORDER_THIN
        );

        /*
        |--------------------------------------------------------------------------
        | CARI BARIS SECTION
        |--------------------------------------------------------------------------
        */

        for ($row = 1; $row <= $highestRow; $row++) {
            $value = $sheet->getCell('A' . $row)->getValue();

            if (
                in_array(
                    $value,
                    [
                        'RINGKASAN PAYROLL',
                        'RINGKASAN PERUBAHAN',
                        'TOP 10 PERUBAHAN',
                        'DETAIL PERUBAHAN PAYROLL',
                    ]
                )
            ) {
                $sheet->mergeCells(
                    'A' . $row . ':K' . $row
                );

                $sheet->getStyle(
                    'A' . $row . ':K' . $row
                )->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '174A8B',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_LEFT,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension($row)
                    ->setRowHeight(25);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER TOP 10 DAN DETAIL
        |--------------------------------------------------------------------------
        */

        for ($row = 1; $row <= $highestRow; $row++) {
            $value = $sheet->getCell('A' . $row)->getValue();

            if ($value === 'No') {
                $sheet->getStyle(
                    'A' . $row . ':K' . $row
                )->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FFFFFF',
                        ],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '123B70',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' =>
                            Alignment::HORIZONTAL_CENTER,
                        'vertical' =>
                            Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                $sheet->getRowDimension($row)
                    ->setRowHeight(30);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT ANGKA
        |--------------------------------------------------------------------------
        */

        for ($row = 1; $row <= $highestRow; $row++) {
            $header = $sheet->getCell('A' . $row)->getValue();

            if ($header === 'No') {
                $startRow = $row + 1;

                for (
                    $r = $startRow;
                    $r <= $highestRow;
                    $r++
                ) {
                    $sheet->getStyle(
                        'F' . $r . ':H' . $r
                    )->getNumberFormat()
                        ->setFormatCode('#,##0');

                    $sheet->getStyle(
                        'I' . $r
                    )->getNumberFormat()
                        ->setFormatCode('0.00%');
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | WRAP TEXT
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle(
            'A1:K' . $highestRow
        )->getAlignment()
            ->setVertical(
                Alignment::VERTICAL_CENTER
            );

        $sheet->getStyle(
            'A1:K' . $highestRow
        )->getAlignment()
            ->setWrapText(true);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 18,
            'C' => 28,
            'D' => 25,
            'E' => 32,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 15,
            'J' => 15,
            'K' => 55,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();

                $sheet->freezePane('A2');

                /*
                |--------------------------------------------------------------------------
                | PRINT SETTING
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setOrientation(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
                    );

                $sheet->getPageSetup()
                    ->setPaperSize(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
                    );

                $sheet->getPageSetup()
                    ->setFitToWidth(1);

                $sheet->getPageSetup()
                    ->setFitToHeight(0);

                $sheet->getPageMargins()
                    ->setTop(0.5);

                $sheet->getPageMargins()
                    ->setRight(0.3);

                $sheet->getPageMargins()
                    ->setLeft(0.3);

                $sheet->getPageMargins()
                    ->setBottom(0.5);

                $sheet->setAutoFilter(
                    'A1:K' . $highestRow
                );
            },
        ];
    }
}