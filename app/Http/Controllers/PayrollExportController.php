<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PayrollExportController extends Controller
{
    /**
     * Export hasil detail perubahan payroll ke CSV
     * CSV dapat dibuka langsung menggunakan Microsoft Excel.
     */
    public function excel(Request $request)
    {
        $mode = $request->get('mode', 'monthly');
        $current = $request->get('current');
        $previous = $request->get('previous');
        $payday = $request->get('payday', 'all');

        /*
        |--------------------------------------------------------------------------
        | Validasi parameter
        |--------------------------------------------------------------------------
        */

        if (!$current) {
            return back()->with(
                'error',
                'Periode payroll belum dipilih.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil data payroll
        |--------------------------------------------------------------------------
        |
        | BAGIAN INI perlu disesuaikan dengan struktur controller analisis
        | yang sudah kamu punya.
        |
        */

        $currentData = Payroll::query();

        $previousData = Payroll::query();

        /*
        |--------------------------------------------------------------------------
        | Filter payday
        |--------------------------------------------------------------------------
        */

        if ($payday !== 'all') {

            $currentData->where(
                'payroll_period',
                'MTH' . $payday
            );

            $previousData->where(
                'payroll_period',
                'MTH' . $payday
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil data
        |--------------------------------------------------------------------------
        */

        $currentRows = $currentData
            ->get();

        $previousRows = $previousData
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Buat response CSV
        |--------------------------------------------------------------------------
        */

        $filename =
            'detail-perubahan-payroll-' .
            $current .
            '.csv';


        return new StreamedResponse(
            function () use (
                $currentRows,
                $previousRows
            ) {

                $handle =
                    fopen(
                        'php://output',
                        'w'
                    );


                /*
                | BOM UTF-8
                */

                fprintf(
                    $handle,
                    chr(0xEF) .
                    chr(0xBB) .
                    chr(0xBF)
                );


                /*
                |--------------------------------------------------------------------------
                | Header Excel
                |--------------------------------------------------------------------------
                */

                fputcsv(
                    $handle,
                    [
                        'Employee No',
                        'Nama',
                        'Department',
                        'Komponen',
                        'Sebelumnya',
                        'Sekarang',
                        'Selisih',
                        'Persentase',
                        'Status',
                        'Keterangan'
                    ]
                );


                /*
                |--------------------------------------------------------------------------
                | Data
                |--------------------------------------------------------------------------
                */

                foreach (
                    $currentRows
                    as $row
                ) {

                    fputcsv(
                        $handle,
                        [
                            $row->employee_no ?? '',
                            $row->name ?? '',
                            $row->department ?? '',
                            $row->component ?? '',
                            0,
                            $row->amount ?? 0,
                            $row->amount ?? 0,
                            '',
                            'current',
                            'Data periode sekarang'
                        ]
                    );

                }


                fclose($handle);

            },
            200,
            [
                'Content-Type' =>
                    'text/csv; charset=UTF-8',

                'Content-Disposition' =>
                    'attachment; filename="' .
                    $filename .
                    '"',
            ]
        );
    }


    /**
     * Export hasil payroll ke PDF.
     */
    public function pdf(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Untuk PDF kita arahkan ke halaman hasil analisis.
        |--------------------------------------------------------------------------
        |
        | Versi awal menggunakan print browser.
        | Jadi tidak membutuhkan package PDF tambahan.
        |
        */

        return redirect()->route(
            'payroll.analysis',
            $request->query()
        );
    }
}