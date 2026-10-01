<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayrollChangeValidationExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    protected $currentSnapshot;
    protected $previousSnapshot;
    protected $validation;
    protected $filter;

    public function __construct(
        $currentSnapshot,
        $previousSnapshot,
        $validation,
        $filter = 'all'
    ) {
        $this->currentSnapshot = $currentSnapshot;
        $this->previousSnapshot = $previousSnapshot;
        $this->validation = $validation;
        $this->filter = $filter;
    }


    /**
     * Data detail
     */
    public function collection()
    {
        return collect(
            $this->validation['details'] ?? []
        );
    }


    /**
     * Header Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Employee No',
            'Nama Karyawan',
            'Department',
            'Komponen',
            'Status',
            'Periode Sebelumnya',
            'Periode Sekarang',
            'Selisih',
            'Persentase',
            'Keterangan',
        ];
    }


    /**
     * Mapping data
     */
    public function map($row): array
    {
        static $no = 0;

        $no++;

        return [
            $no,

            $row['employee_no'] ?? '-',

            $row['name'] ?? '-',

            $row['department'] ?? '-',

            $row['component'] ?? '-',

            $row['status_label']
                ?? $row['status']
                ?? '-',

            $this->currency(
                $row['previous'] ?? null
            ),

            $this->currency(
                $row['current'] ?? null
            ),

            $this->currency(
                $row['difference'] ?? null
            ),

            $this->percentage(
                $row['percentage'] ?? null
            ),

            $row['description'] ?? '-',
        ];
    }


    /**
     * Style Excel
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
            ],

            'fill' => [
                'fillType' => 'solid',
                'startColor' => [
                    'rgb' => '174A8B',
                ],
            ],

            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);

        $sheet->freezePane('A2');

        $sheet->setAutoFilter(
            'A1:K' . max(
                1,
                $this->collection()->count() + 1
            )
        );

        return [];
    }


    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 18,
            'C' => 28,
            'D' => 25,
            'E' => 32,
            'F' => 22,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 15,
            'K' => 60,
        ];
    }


    /**
     * Nama sheet
     */
    public function title(): string
    {
        return 'Detail Validasi';
    }


    private function currency($value)
    {
        if ($value === null) {
            return '-';
        }

        return 'Rp ' . number_format(
            (float) $value,
            0,
            ',',
            '.'
        );
    }


    private function percentage($value)
    {
        if ($value === null) {
            return '-';
        }

        return number_format(
            (float) $value,
            2,
            ',',
            '.'
        ) . '%';
    }
}