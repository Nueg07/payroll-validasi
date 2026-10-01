<?php

namespace App\Http\Controllers;

use App\Models\PayrollSnapshot;
use Illuminate\Http\Request;

class PayrollValidationController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil semua snapshot
        |--------------------------------------------------------------------------
        */

        $snapshots = PayrollSnapshot::orderByDesc('report_date')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Tentukan snapshot yang dipilih
        |--------------------------------------------------------------------------
        */

        if ($request->filled('snapshot')) {

            $snapshot = PayrollSnapshot::with('details')
                ->findOrFail($request->snapshot);

        } else {

            $snapshot = PayrollSnapshot::with('details')
                ->orderByDesc('report_date')
                ->orderByDesc('id')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Jika belum ada data
        |--------------------------------------------------------------------------
        */

        if (!$snapshot) {

            return view(
                'payroll.validation',
                [
                    'snapshots' => $snapshots,
                    'snapshot' => null,
                    'validation' => null,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil detail karyawan
        |--------------------------------------------------------------------------
        */

        $details = $snapshot->details;

        $totalData = $details->count();

        /*
        |--------------------------------------------------------------------------
        | Cari duplicate employee_no
        |--------------------------------------------------------------------------
        */

        $duplicateEmployeeNumbers = $details
            ->groupBy('employee_no')
            ->filter(function ($items) {
                return $items->count() > 1;
            })
            ->keys()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Validasi setiap karyawan
        |--------------------------------------------------------------------------
        */

        $errors = [];

        foreach ($details as $detail) {

            $issues = [];

            /*
            |--------------------------------------------------------------------------
            | Employee Number
            |--------------------------------------------------------------------------
            */

            if (
                empty(
                    trim(
                        (string) $detail->employee_no
                    )
                )
            ) {
                $issues[] = 'Employee No kosong';
            }

            /*
            |--------------------------------------------------------------------------
            | Nama
            |--------------------------------------------------------------------------
            */

            if (
                empty(
                    trim(
                        (string) $detail->name
                    )
                )
            ) {
                $issues[] = 'Nama karyawan kosong';
            }

            /*
            |--------------------------------------------------------------------------
            | Department
            |--------------------------------------------------------------------------
            */

            if (
                empty(
                    trim(
                        (string) $detail->department
                    )
                )
            ) {
                $issues[] = 'Department kosong';
            }

            /*
            |--------------------------------------------------------------------------
            | Salary
            |--------------------------------------------------------------------------
            */

            if (
                $detail->salary === null
                ||
                (float) $detail->salary < 0
            ) {
                $issues[] = 'Gaji pokok tidak valid';
            }

            /*
            |--------------------------------------------------------------------------
            | Total Income
            |--------------------------------------------------------------------------
            */

            if (
                $detail->total_income === null
                ||
                (float) $detail->total_income < 0
            ) {
                $issues[] = 'Total income tidak valid';
            }

            /*
            |--------------------------------------------------------------------------
            | Total Deduction
            |--------------------------------------------------------------------------
            */

            if (
                $detail->total_deduction === null
                ||
                (float) $detail->total_deduction < 0
            ) {
                $issues[] = 'Total deduction tidak valid';
            }

            /*
            |--------------------------------------------------------------------------
            | Net Salary
            |--------------------------------------------------------------------------
            */

            if (
                $detail->net_salary === null
                ||
                (float) $detail->net_salary < 0
            ) {
                $issues[] = 'Net salary tidak valid';
            }

            /*
            |--------------------------------------------------------------------------
            | Duplicate Employee No
            |--------------------------------------------------------------------------
            */

            if (
                $duplicateEmployeeNumbers
                    ->contains($detail->employee_no)
            ) {
                $issues[] = 'Employee No duplikat';
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan error
            |--------------------------------------------------------------------------
            */

            if (count($issues) > 0) {

                $errors[] = [
                    'employee_no' => $detail->employee_no,
                    'name' => $detail->name,
                    'department' => $detail->department,
                    'issues' => $issues,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung statistik
        |--------------------------------------------------------------------------
        */

        $totalError = count($errors);

        $totalValid = max(
            $totalData - $totalError,
            0
        );

        $percentageValid = $totalData > 0
            ? round(
                ($totalValid / $totalData) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Validasi struktur snapshot
        |--------------------------------------------------------------------------
        */

        $checks = [

            [
                'name' => 'Payroll Period',
                'valid' => !empty($snapshot->payroll_period),
                'description' => !empty($snapshot->payroll_period)
                    ? 'Payroll Period tersedia'
                    : 'Payroll Period kosong',
            ],

            [
                'name' => 'Tanggal laporan',
                'valid' => !empty($snapshot->report_date),
                'description' => !empty($snapshot->report_date)
                    ? 'Tanggal laporan tersedia'
                    : 'Tanggal laporan kosong',
            ],

            [
                'name' => 'Data karyawan',
                'valid' => $totalData > 0,
                'description' => $totalData > 0
                    ? $totalData . ' data karyawan ditemukan'
                    : 'Data karyawan tidak ditemukan',
            ],

            [
                'name' => 'Employee No',
                'valid' => !collect($errors)
                    ->contains(function ($error) {
                        return in_array(
                            'Employee No kosong',
                            $error['issues']
                        );
                    }),
                'description' => 'Employee No diperiksa',
            ],

            [
                'name' => 'Department',
                'valid' => !collect($errors)
                    ->contains(function ($error) {
                        return in_array(
                            'Department kosong',
                            $error['issues']
                        );
                    }),
                'description' => 'Department diperiksa',
            ],

            [
                'name' => 'Data payroll',
                'valid' => $totalError === 0,
                'description' => $totalError === 0
                    ? 'Tidak ditemukan error payroll'
                    : $totalError . ' data memiliki error',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Data validasi untuk view
        |--------------------------------------------------------------------------
        */

        $validation = [
            'totalData' => $totalData,
            'totalValid' => $totalValid,
            'totalError' => $totalError,
            'percentageValid' => $percentageValid,
            'errors' => $errors,
            'checks' => $checks,
            'canContinue' => $totalError === 0,
        ];

        return view(
            'payroll.validation',
            compact(
                'snapshots',
                'snapshot',
                'validation'
            )
        );
    }
}