<?php

namespace App\Http\Controllers;

use App\Imports\PayrollImport;
use App\Models\PayrollSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PayrollImportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN UPLOAD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('payroll.import');
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES IMPORT
    |--------------------------------------------------------------------------
    */

    public function import(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI FILE
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:20480',
        ]);

        /*
        |--------------------------------------------------------------------------
        | BACA EXCEL
        |--------------------------------------------------------------------------
        */

        $import = new PayrollImport();

        Excel::import(
            $import,
            $request->file('file')
        );

        /*
        |--------------------------------------------------------------------------
        | CEK PAYROLL PERIOD
        |--------------------------------------------------------------------------
        */

        if (!$import->payrollPeriod) {

            return back()->withErrors([
                'file' =>
                    'Payroll Period tidak berhasil dibaca dari Excel.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK REPORT DATE
        |--------------------------------------------------------------------------
        */

        if (!$import->reportDate) {

            return back()->withErrors([
                'file' =>
                    'Tanggal laporan payroll tidak berhasil dibaca dari Excel.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | KONVERSI TANGGAL
        |--------------------------------------------------------------------------
        */

        $reportDate = date(
            'Y-m-d',
            strtotime($import->reportDate)
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE ANALISIS
        |--------------------------------------------------------------------------
        */

        $periodeBulan = (int) date(
            'm',
            strtotime($reportDate)
        );

        $periodeTahun = (int) date(
            'Y',
            strtotime($reportDate)
        );

        /*
        |--------------------------------------------------------------------------
        | CEK DATA KARYAWAN
        |--------------------------------------------------------------------------
        */

        if (count($import->employees) === 0) {

            return back()->withErrors([
                'file' =>
                    'Data karyawan tidak ditemukan di Excel.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT SNAPSHOT
        |--------------------------------------------------------------------------
        */

        $sudahAda = PayrollSnapshot::where(
            'payroll_period',
            $import->payrollPeriod
        )
        ->whereDate(
            'report_date',
            $reportDate
        )
        ->exists();

        if ($sudahAda) {

            return back()->withErrors([
                'file' =>
                    'Payroll dengan periode '
                    . $import->payrollPeriod
                    . ' dan tanggal laporan '
                    . $reportDate
                    . ' sudah pernah disimpan.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN KE DATABASE
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $import,
            $reportDate,
            $periodeBulan,
            $periodeTahun
        ) {

            /*
            |--------------------------------------------------------------------------
            | HITUNG TOTAL
            |--------------------------------------------------------------------------
            */

            $totalKaryawan = count(
                $import->employees
            );

            $totalSalary = collect(
                $import->employees
            )->sum('salary');

            $totalIncome = collect(
                $import->employees
            )->sum('total_income');

            $totalOvertime = collect(
                $import->employees
            )->sum('overtime');

            $totalThr = collect(
                $import->employees
            )->sum('thr');

            $totalDeduction = collect(
                $import->employees
            )->sum('total_deduction');

            $totalTax = collect(
                $import->employees
            )->sum('tax');

            $totalNetSalary = collect(
                $import->employees
            )->sum('net_salary');

            /*
            |--------------------------------------------------------------------------
            | BUAT SNAPSHOT
            |--------------------------------------------------------------------------
            */

            $snapshot = PayrollSnapshot::create([

                'payroll_period' =>
                    $import->payrollPeriod,

                'report_date' =>
                    $reportDate,

                'periode_bulan' =>
                    $periodeBulan,

                'periode_tahun' =>
                    $periodeTahun,

                'total_karyawan' =>
                    $totalKaryawan,

                'total_salary' =>
                    $totalSalary,

                'total_income' =>
                    $totalIncome,

                'total_overtime' =>
                    $totalOvertime,

                'total_thr' =>
                    $totalThr,

                'total_deduction' =>
                    $totalDeduction,

                'total_tax' =>
                    $totalTax,

                'total_net_salary' =>
                    $totalNetSalary,
            ]);

            /*
            |--------------------------------------------------------------------------
            | SIMPAN DETAIL KARYAWAN
            |--------------------------------------------------------------------------
            */

            foreach ($import->employees as $employee) {

                $employee['snapshot_id'] =
                    $snapshot->id;

                $snapshot
                    ->details()
                    ->create($employee);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',

            'Monthly Report berhasil disimpan sebagai snapshot '
            . $import->payrollPeriod
            . ' - '
            . date(
                'd-m-Y',
                strtotime($reportDate)
            )
            . '. Total '
            . count($import->employees)
            . ' karyawan.'
        );
    }
}