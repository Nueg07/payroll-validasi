<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PayrollImport implements ToCollection
{
    public $employees = [];
    public $payrollPeriod = null;
    public $reportDate = null;

    public function collection(Collection $rows)
    {
        $headerIndex = 7;
        $subHeaderIndex = 8;

        if (!isset($rows[$headerIndex])) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Mapping kolom Excel -> kolom database
        |--------------------------------------------------------------------------
        */

        $mapping = [

            // Identitas
            1  => 'name',
            2  => 'employee_no',
            3  => 'nric',
            4  => 'gov_tax_file_no',
            5  => 'position',
            6  => 'department',
            7  => 'cost_center',
            8  => 'grade',
            9  => 'employment_status',
            10 => 'join_date',
            11 => 'terminate_date',
            12 => 'length_of_service',
            13 => 'tax_status',

            // Gaji
            14 => 'salary',

            // Allowance
            15 => 'allowance_bpjstk_jkm',
            16 => 'allowance_bpjstk_jkk',
            17 => 'allowance_bpjstk_jht',
            18 => 'tunjangan_jabatan',
            19 => 'overtime',
            20 => 'allowance',
            21 => 'tunjangan_prestasi',
            22 => 'tunjangan_operational',
            23 => 'reward',
            24 => 'thr',
            25 => 'tak',
            26 => 'rapel_gaji',
            27 => 'allowance_bpjsks_company',
            28 => 'allowance_bpjstk_jp_company',
            29 => 'tunjangan_keahlian',

            // Income
            30 => 'total_income',

            // Pajak
            31 => 'tax_borne_by_company',

            // Potongan
            32 => 'deduction',

            // PERHATIKAN:
            // Database menggunakan bpjsstk
            // bukan bpjstk
            33 => 'deduction_bpjsstk_jht_employee',

            34 => 'deduction_bpjstk_jp_employee',
            35 => 'deduction_bpjsks_company',
            36 => 'potongan_lain_lain',
            37 => 'deduction_bpjstk_jkk',
            38 => 'deduction_bpjstk_jkm',
            39 => 'deduction_bpjstk_jht',
            40 => 'deduction_bpjsks_employee',

            // Kolom ini ada di database,
            // tetapi tidak ada di Excel yang kita mapping
            // sehingga akan diisi 0
            //
            // deduction_bpjstk_jp_company

            41 => 'total_deduction',
            42 => 'tax',
            43 => 'net_salary',

            // Data tambahan
            44 => 'dasar_bpjstk',
            45 => 'netral_bpjstk_jp',
            46 => 'dasar_bpjsks',
            47 => 'total_ot_1',
            48 => 'total_ot_2',
            49 => 'total_ot_3',
            50 => 'total_ot_4',
            51 => 'total_overtime',
            52 => 'nilai_ot_mak',
        ];

        /*
        |--------------------------------------------------------------------------
        | Kolom angka
        |--------------------------------------------------------------------------
        */

        $numericFields = [

            'salary',

            'allowance_bpjstk_jkm',
            'allowance_bpjstk_jkk',
            'allowance_bpjstk_jht',
            'tunjangan_jabatan',
            'overtime',
            'allowance',
            'tunjangan_prestasi',
            'tunjangan_operational',
            'reward',
            'thr',
            'tak',
            'rapel_gaji',
            'allowance_bpjsks_company',
            'allowance_bpjstk_jp_company',
            'tunjangan_keahlian',

            'total_income',

            'tax_borne_by_company',

            'deduction',
            'deduction_bpjsstk_jht_employee',
            'deduction_bpjstk_jp_employee',
            'deduction_bpjsks_company',
            'potongan_lain_lain',
            'deduction_bpjstk_jkk',
            'deduction_bpjstk_jkm',
            'deduction_bpjstk_jht',
            'deduction_bpjsks_employee',

            'deduction_bpjstk_jp_company',

            'total_deduction',

            'tax',
            'net_salary',

            'dasar_bpjstk',
            'netral_bpjstk_jp',
            'dasar_bpjsks',

            'total_ot_1',
            'total_ot_2',
            'total_ot_3',
            'total_ot_4',
            'total_overtime',
            'nilai_ot_mak',
        ];

        /*
        |--------------------------------------------------------------------------
        | Baca data karyawan
        |--------------------------------------------------------------------------
        */

        $employees = [];

        for (
            $i = $subHeaderIndex + 1;
            $i < $rows->count();
            $i++
        ) {
            $row = $rows[$i]->values();

            /*
            |--------------------------------------------------------------------------
            | Validasi Employee No
            |--------------------------------------------------------------------------
            */

            $employeeNo = trim(
                (string) ($row[2] ?? '')
            );

            if (
                $employeeNo === ''
                ||
                !preg_match('/^EMP/i', $employeeNo)
            ) {
                continue;
            }

            $employee = [];

            /*
            |--------------------------------------------------------------------------
            | Isi kolom berdasarkan mapping
            |--------------------------------------------------------------------------
            */

            foreach (
                $mapping
                as $excelIndex => $databaseField
            ) {
                $value = $row[$excelIndex] ?? null;

                if (is_string($value)) {
                    $value = trim($value);
                }

                /*
                |--------------------------------------------------------------------------
                | Perbaikan grade
                |--------------------------------------------------------------------------
                */

                if (
                    $databaseField === 'grade'
                    &&
                    is_string($value)
                    &&
                    str_starts_with($value, '=')
                ) {
                    $value = $row[5] ?? null;
                }

                /*
                |--------------------------------------------------------------------------
                | Angka
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $databaseField,
                        $numericFields,
                        true
                    )
                ) {
                    $value = $this->angka($value);
                }

                /*
                |--------------------------------------------------------------------------
                | Tanggal
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $databaseField,
                        [
                            'join_date',
                            'terminate_date',
                        ],
                        true
                    )
                ) {
                    $value = $this->tanggal($value);
                }

                $employee[$databaseField] = $value;
            }

            /*
            |--------------------------------------------------------------------------
            | Kolom database yang tidak ada di Excel
            |--------------------------------------------------------------------------
            |
            | deduction_bpjstk_jp_company ada di database
            | tetapi tidak ada pada mapping Excel.
            |
            */

            $employee['deduction_bpjstk_jp_company'] = 0;

            $employees[] = $employee;
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Payroll Period
        |--------------------------------------------------------------------------
        */

        $payrollPeriod = $this->bersihkanNilai(
            $this->ambilNilai(
                $rows,
                'Payroll Period'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Ambil tanggal laporan
        |--------------------------------------------------------------------------
        */

        $reportDate = $this->bersihkanNilai(
            $this->ambilNilai(
                $rows,
                'Date'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan hasil
        |--------------------------------------------------------------------------
        */

        $this->employees = $employees;
        $this->payrollPeriod = $payrollPeriod;
        $this->reportDate = $reportDate;
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil nilai berdasarkan label
    |--------------------------------------------------------------------------
    */

    private function ambilNilai(
        Collection $rows,
        string $label
    ) {
        foreach ($rows as $row) {

            foreach ($row as $key => $value) {

                if (
                    strtolower(trim((string) $value))
                    === strtolower($label)
                ) {
                    return $row[$key + 1] ?? null;
                }
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Bersihkan nilai
    |--------------------------------------------------------------------------
    */

    private function bersihkanNilai($value)
    {
        if ($value === null) {
            return null;
        }

        return trim(
            ltrim(
                (string) $value,
                ':'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Konversi angka
    |--------------------------------------------------------------------------
    */

    private function angka($value)
    {
        if (
            $value === null
            ||
            $value === ''
            ||
            $value === '-'
        ) {
            return 0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = str_replace(
            [
                ',',
                'Rp',
                ' ',
            ],
            '',
            (string) $value
        );

        return is_numeric($value)
            ? (float) $value
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Konversi tanggal Excel
    |--------------------------------------------------------------------------
    */

    private function tanggal($value)
    {
        if (
            $value === null
            ||
            $value === ''
            ||
            $value === '-'
        ) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_numeric($value)) {

            try {

                return \PhpOffice\PhpSpreadsheet\Shared\Date
                    ::excelToDateTimeObject($value)
                    ->format('Y-m-d');

            } catch (\Exception $e) {

                return null;
            }
        }

        $timestamp = strtotime(
            (string) $value
        );

        if ($timestamp === false) {
            return null;
        }

        return date(
            'Y-m-d',
            $timestamp
        );
    }
}