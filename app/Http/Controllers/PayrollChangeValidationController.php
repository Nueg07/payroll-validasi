<?php

namespace App\Http\Controllers;

use App\Models\PayrollSnapshot;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PayrollChangeValidationExport;

class PayrollChangeValidationController extends Controller
{
    /**
     * =========================================================
     * HALAMAN DETAIL VALIDASI PERUBAHAN
     * =========================================================
     */
    public function index(Request $request)
    {
        /*
         * =====================================================
         * AMBIL SEMUA SNAPSHOT
         * =====================================================
         *
         * Urutan periode ditentukan dari:
         * periode_tahun + periode_bulan
         *
         * BUKAN dari report_date.
         *
         * Jika terdapat beberapa snapshot pada periode yang sama,
         * gunakan payroll_period kemudian id sebagai pembeda.
         */
        $snapshots = PayrollSnapshot::query()
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')
            ->orderByDesc('payroll_period')
            ->orderByDesc('id')
            ->get();

        /*
         * =====================================================
         * BELUM ADA DATA
         * =====================================================
         */
        if ($snapshots->isEmpty()) {
            return view('payroll.change-validation', [
                'snapshots' => $snapshots,
                'currentSnapshot' => null,
                'previousSnapshot' => null,
                'validation' => null,
                'filter' => 'all',
            ]);
        }

        /*
         * =====================================================
         * SNAPSHOT SEKARANG
         * =====================================================
         */
        $currentSnapshot = $this->getCurrentSnapshot($request);

        if (!$currentSnapshot) {
            return view('payroll.change-validation', [
                'snapshots' => $snapshots,
                'currentSnapshot' => null,
                'previousSnapshot' => null,
                'validation' => null,
                'filter' => 'all',
            ]);
        }

        /*
         * =====================================================
         * SNAPSHOT SEBELUMNYA
         * =====================================================
         */
        $previousSnapshot = $this->getPreviousSnapshot(
            $request,
            $currentSnapshot
        );

        /*
         * =====================================================
         * BELUM ADA PERIODE SEBELUMNYA
         * =====================================================
         */
        if (!$previousSnapshot) {
            return view('payroll.change-validation', [
                'snapshots' => $snapshots,
                'currentSnapshot' => $currentSnapshot,
                'previousSnapshot' => null,
                'validation' => null,
                'filter' => 'all',
            ]);
        }

        /*
         * =====================================================
         * VALIDASI PAYROLL
         * =====================================================
         */
        $validation = $this->validatePayroll(
            $currentSnapshot,
            $previousSnapshot
        );

        /*
         * =====================================================
         * FILTER
         * =====================================================
         */
        $filter = $request->input('filter', 'all');

        if ($filter !== 'all') {
            $validation['details'] = collect($validation['details'])
                ->where('status', $filter)
                ->values()
                ->all();
        }

        /*
         * =====================================================
         * RETURN VIEW
         * =====================================================
         */
        return view('payroll.change-validation', [
            'snapshots' => $snapshots,
            'currentSnapshot' => $currentSnapshot,
            'previousSnapshot' => $previousSnapshot,
            'validation' => $validation,
            'filter' => $filter,
        ]);
    }


    /**
     * =========================================================
     * EXPORT EXCEL
     * =========================================================
     */
    public function exportExcel(Request $request)
    {
        $currentSnapshot = $this->getCurrentSnapshot($request);

        if (!$currentSnapshot) {
            return back()->with(
                'error',
                'Data periode sekarang tidak ditemukan.'
            );
        }

        $previousSnapshot = $this->getPreviousSnapshot(
            $request,
            $currentSnapshot
        );

        if (!$previousSnapshot) {
            return back()->with(
                'error',
                'Data periode sebelumnya tidak ditemukan.'
            );
        }

        /*
         * Validasi payroll
         */
        $validation = $this->validatePayroll(
            $currentSnapshot,
            $previousSnapshot
        );

        /*
         * Filter sesuai halaman
         */
        $filter = $request->input('filter', 'all');

        if ($filter !== 'all') {
            $validation['details'] = collect($validation['details'])
                ->where('status', $filter)
                ->values()
                ->all();
        }

        /*
         * Nama file
         */
        $filename =
            'detail-validasi-perubahan-' .
            $this->formatPeriod($currentSnapshot) .
            '.xlsx';

        return Excel::download(
            new PayrollChangeValidationExport(
                $currentSnapshot,
                $previousSnapshot,
                $validation,
                $filter
            ),
            $filename
        );
    }


    /**
     * =========================================================
     * EXPORT PDF
     * =========================================================
     */
    public function exportPdf(Request $request)
    {
        $currentSnapshot = $this->getCurrentSnapshot($request);

        if (!$currentSnapshot) {
            return back()->with(
                'error',
                'Data periode sekarang tidak ditemukan.'
            );
        }

        $previousSnapshot = $this->getPreviousSnapshot(
            $request,
            $currentSnapshot
        );

        if (!$previousSnapshot) {
            return back()->with(
                'error',
                'Data periode sebelumnya tidak ditemukan.'
            );
        }

        /*
         * Validasi payroll
         */
        $validation = $this->validatePayroll(
            $currentSnapshot,
            $previousSnapshot
        );

        /*
         * Filter sesuai halaman
         */
        $filter = $request->input('filter', 'all');

        if ($filter !== 'all') {
            $validation['details'] = collect($validation['details'])
                ->where('status', $filter)
                ->values()
                ->all();
        }

        /*
         * Nama file
         */
        $filename =
            'detail-validasi-perubahan-' .
            $this->formatPeriod($currentSnapshot) .
            '.pdf';

        return view('payroll.change-validation-pdf', [
            'currentSnapshot' => $currentSnapshot,
            'previousSnapshot' => $previousSnapshot,
            'validation' => $validation,
            'filter' => $filter,
            'filename' => $filename,
        ]);
    }


    /**
     * =========================================================
     * AMBIL SNAPSHOT SEKARANG
     * =========================================================
     */
    private function getCurrentSnapshot(Request $request)
    {
        $currentId = $request->input('current');

        /*
         * Jika user memilih snapshot secara manual
         */
        if ($currentId) {
            return PayrollSnapshot::with('details')
                ->find($currentId);
        }

        /*
         * Jika tidak memilih:
         * ambil snapshot dengan periode payroll terbaru.
         *
         * Prioritas:
         * 1. periode_tahun
         * 2. periode_bulan
         * 3. payroll_period
         * 4. id
         *
         * report_date TIDAK digunakan.
         */
        return PayrollSnapshot::with('details')
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')
            ->orderByDesc('payroll_period')
            ->orderByDesc('id')
            ->first();
    }


    /**
     * =========================================================
     * AMBIL SNAPSHOT SEBELUMNYA
     * =========================================================
     */
    private function getPreviousSnapshot(
        Request $request,
        PayrollSnapshot $currentSnapshot
    ) {
        $previousId = $request->input('previous');

        /*
         * =====================================================
         * PERIODE SEKARANG
         * =====================================================
         */
        $currentYear = (int) $currentSnapshot->periode_tahun;
        $currentMonth = (int) $currentSnapshot->periode_bulan;

        /*
         * =====================================================
         * JIKA USER MEMILIH PREVIOUS SECARA MANUAL
         * =====================================================
         */
        if ($previousId) {

            $previousSnapshot = PayrollSnapshot::with('details')
                ->find($previousId);

            /*
             * Previous tidak ditemukan
             */
            if (!$previousSnapshot) {
                return null;
            }

            /*
             * Tidak boleh memilih snapshot yang sama
             */
            if (
                (int) $previousSnapshot->id ===
                (int) $currentSnapshot->id
            ) {
                return null;
            }

            /*
             * Periode current
             */
            $currentPeriod =
                ($currentYear * 100) +
                $currentMonth;

            /*
             * Periode previous
             */
            $previousPeriod =
                ((int) $previousSnapshot->periode_tahun * 100) +
                (int) $previousSnapshot->periode_bulan;

            /*
             * Previous tidak boleh sama atau lebih baru
             */
            if ($previousPeriod >= $currentPeriod) {
                return null;
            }

            return $previousSnapshot;
        }


        /*
         * =====================================================
         * JIKA PREVIOUS TIDAK DIPILIH
         * =====================================================
         *
         * Cari snapshot yang periodenya paling dekat sebelum
         * periode current.
         *
         * Contoh:
         *
         * Current:
         * 07/2026
         *
         * Database:
         * 07/2026
         * 05/2026
         *
         * Maka previous:
         * 05/2026
         *
         * Tidak memaksa mencari 06/2026.
         */
        return PayrollSnapshot::with('details')
            ->where(function ($query) use (
                $currentYear,
                $currentMonth
            ) {

                /*
                 * Tahun lebih kecil
                 */
                $query->where(
                    'periode_tahun',
                    '<',
                    $currentYear
                );

                /*
                 * ATAU tahun sama tetapi bulan lebih kecil
                 */
                $query->orWhere(function ($query) use (
                    $currentYear,
                    $currentMonth
                ) {
                    $query
                        ->where(
                            'periode_tahun',
                            '=',
                            $currentYear
                        )
                        ->where(
                            'periode_bulan',
                            '<',
                            $currentMonth
                        );
                });
            })

            /*
             * Urutkan periode terbaru terlebih dahulu
             */
            ->orderByDesc('periode_tahun')
            ->orderByDesc('periode_bulan')

            /*
             * Jika dalam bulan yang sama ada beberapa
             * snapshot, pilih payroll_period terbaru.
             */
            ->orderByDesc('payroll_period')

            /*
             * Jika masih sama, gunakan ID terbaru.
             */
            ->orderByDesc('id')

            ->first();
    }


    /**
     * =========================================================
     * VALIDASI PAYROLL
     * =========================================================
     */
    private function validatePayroll(
        PayrollSnapshot $current,
        PayrollSnapshot $previous
    ) {
        /*
         * =====================================================
         * DAFTAR KOMPONEN PAYROLL
         * =====================================================
         */
        $components = [
            'salary' =>
                'Gaji Pokok',

            'allowance_bpjstk_jkm' =>
                'Allowance BPJSTK JKM',

            'allowance_bpjstk_jkk' =>
                'Allowance BPJSTK JKK',

            'allowance_bpjstk_jht' =>
                'Allowance BPJSTK JHT',

            'tunjangan_jabatan' =>
                'Tunjangan Jabatan',

            'overtime' =>
                'Overtime',

            'allowance' =>
                'Tunjangan',

            'tunjangan_prestasi' =>
                'Tunjangan Prestasi',

            'tunjangan_operational' =>
                'Tunjangan Operational',

            'reward' =>
                'Reward',

            'thr' =>
                'THR',

            'tak' =>
                'TAK',

            'rapel_gaji' =>
                'Rapel Gaji',

            'allowance_bpjsks_company' =>
                'Allowance BPJSKS Company',

            'allowance_bpjstk_jp_company' =>
                'Allowance BPJSTK JP Company',

            'tunjangan_keahlian' =>
                'Tunjangan Keahlian',
        ];


        /*
         * =====================================================
         * DATA EMPLOYEE
         * =====================================================
         */
        $currentEmployees = $current->details;
        $previousEmployees = $previous->details;


        /*
         * =====================================================
         * INDEX PERIODE SEBELUMNYA
         * =====================================================
         */
        $previousByEmployee = [];

        foreach ($previousEmployees as $employee) {

            $employeeNo = strtoupper(
                trim((string) $employee->employee_no)
            );

            if ($employeeNo !== '') {
                $previousByEmployee[$employeeNo] = $employee;
            }
        }


        /*
         * =====================================================
         * INDEX PERIODE SEKARANG
         * =====================================================
         */
        $currentByEmployee = [];

        foreach ($currentEmployees as $employee) {

            $employeeNo = strtoupper(
                trim((string) $employee->employee_no)
            );

            if ($employeeNo !== '') {
                $currentByEmployee[$employeeNo] = $employee;
            }
        }


        /*
         * =====================================================
         * DETAIL PERUBAHAN
         * =====================================================
         */
        $details = [];


        /*
         * =====================================================
         * SUMMARY
         * =====================================================
         */
        $summary = [
            'employeesCurrent' =>
                count($currentByEmployee),

            'employeesPrevious' =>
                count($previousByEmployee),

            'matchedEmployees' =>
                0,

            'newEmployees' =>
                0,

            'missingEmployees' =>
                0,

            'newComponents' =>
                0,

            'missingComponents' =>
                0,

            'largeChanges' =>
                0,

            'inconsistent' =>
                0,

            'normal' =>
                0,

            'totalIncrease' =>
                0,

            'totalDecrease' =>
                0,
        ];


        /*
         * =====================================================
         * PERIKSA KARYAWAN PERIODE SEKARANG
         * =====================================================
         */
        foreach ($currentEmployees as $currentEmployee) {

            $employeeNo = strtoupper(
                trim((string) $currentEmployee->employee_no)
            );


            /*
             * =================================================
             * EMPLOYEE BARU
             * =================================================
             */
            if (!isset($previousByEmployee[$employeeNo])) {

                $summary['newEmployees']++;

                foreach ($components as $field => $label) {

                    $currentValue = $this->number(
                        $currentEmployee->{$field} ?? 0
                    );

                    if ($currentValue <= 0) {
                        continue;
                    }

                    $summary['newComponents']++;

                    $details[] = [
                        'employee_no' =>
                            $currentEmployee->employee_no,

                        'name' =>
                            $currentEmployee->name,

                        'department' =>
                            $currentEmployee->department,

                        'component' =>
                            $label,

                        'status' =>
                            'new_component',

                        'status_label' =>
                            'Komponen Baru',

                        'previous' =>
                            0,

                        'current' =>
                            $currentValue,

                        'difference' =>
                            $currentValue,

                        'percentage' =>
                            null,

                        'description' =>
                            'Komponen memiliki nilai pada periode sekarang tetapi karyawan tidak ditemukan pada periode sebelumnya.',
                    ];
                }

                continue;
            }


            /*
             * =================================================
             * EMPLOYEE DITEMUKAN DI KEDUA PERIODE
             * =================================================
             */
            $summary['matchedEmployees']++;

            $previousEmployee =
                $previousByEmployee[$employeeNo];


            /*
             * =================================================
             * BANDINGKAN SETIAP KOMPONEN
             * =================================================
             */
            foreach ($components as $field => $label) {

                $previousValue = $this->number(
                    $previousEmployee->{$field} ?? 0
                );

                $currentValue = $this->number(
                    $currentEmployee->{$field} ?? 0
                );


                /*
                 * =============================================
                 * SELISIH
                 * =============================================
                 */
                $difference =
                    $currentValue -
                    $previousValue;


                /*
                 * =============================================
                 * TIDAK BERUBAH
                 * =============================================
                 */
                if (abs($difference) < 0.01) {

                    $summary['normal']++;

                    continue;
                }


                /*
                 * =============================================
                 * KOMPONEN BARU
                 * =============================================
                 */
                if (
                    abs($previousValue) < 0.01 &&
                    $currentValue > 0.01
                ) {

                    $summary['newComponents']++;

                    $details[] = [
                        'employee_no' =>
                            $currentEmployee->employee_no,

                        'name' =>
                            $currentEmployee->name,

                        'department' =>
                            $currentEmployee->department,

                        'component' =>
                            $label,

                        'status' =>
                            'new_component',

                        'status_label' =>
                            'Komponen Baru',

                        'previous' =>
                            $previousValue,

                        'current' =>
                            $currentValue,

                        'difference' =>
                            $difference,

                        'percentage' =>
                            null,

                        'description' =>
                            'Komponen sebelumnya bernilai Rp0 dan sekarang memiliki nilai.',
                    ];

                    continue;
                }


                /*
                 * =============================================
                 * KOMPONEN HILANG
                 * =============================================
                 */
                if (
                    $previousValue > 0.01 &&
                    abs($currentValue) < 0.01
                ) {

                    $summary['missingComponents']++;

                    $details[] = [
                        'employee_no' =>
                            $currentEmployee->employee_no,

                        'name' =>
                            $currentEmployee->name,

                        'department' =>
                            $currentEmployee->department,

                        'component' =>
                            $label,

                        'status' =>
                            'missing_component',

                        'status_label' =>
                            'Komponen Hilang',

                        'previous' =>
                            $previousValue,

                        'current' =>
                            $currentValue,

                        'difference' =>
                            $difference,

                        'percentage' =>
                            -100,

                        'description' =>
                            'Komponen memiliki nilai pada periode sebelumnya tetapi sekarang menjadi Rp0.',
                    ];

                    continue;
                }


                /*
                 * =============================================
                 * PERSENTASE PERUBAHAN
                 * =============================================
                 */
                $percentage = null;

                if (abs($previousValue) > 0.01) {

                    $percentage = round(
                        (
                            $difference /
                            abs($previousValue)
                        ) * 100,
                        2
                    );
                }


                /*
                 * =============================================
                 * PERUBAHAN BESAR
                 * =============================================
                 */
                if (
                    $percentage !== null &&
                    abs($percentage) >= 50
                ) {

                    $summary['largeChanges']++;

                    if ($difference > 0) {

                        $summary['totalIncrease'] +=
                            $difference;

                    } else {

                        $summary['totalDecrease'] +=
                            abs($difference);
                    }

                    $details[] = [
                        'employee_no' =>
                            $currentEmployee->employee_no,

                        'name' =>
                            $currentEmployee->name,

                        'department' =>
                            $currentEmployee->department,

                        'component' =>
                            $label,

                        'status' =>
                            'large_change',

                        'status_label' =>
                            'Perlu Diperiksa',

                        'previous' =>
                            $previousValue,

                        'current' =>
                            $currentValue,

                        'difference' =>
                            $difference,

                        'percentage' =>
                            $percentage,

                        'description' =>
                            'Perubahan komponen mencapai ' .
                            abs($percentage) .
                            '%. Perubahan besar perlu diperiksa.',
                    ];

                    continue;
                }


                /*
                 * =============================================
                 * PERUBAHAN BIASA
                 * =============================================
                 */
                if ($difference > 0) {

                    $summary['totalIncrease'] +=
                        $difference;

                } else {

                    $summary['totalDecrease'] +=
                        abs($difference);
                }

                $details[] = [
                    'employee_no' =>
                        $currentEmployee->employee_no,

                    'name' =>
                        $currentEmployee->name,

                    'department' =>
                        $currentEmployee->department,

                    'component' =>
                        $label,

                    'status' =>
                        'normal_change',

                    'status_label' =>
                        'Perubahan Normal',

                    'previous' =>
                        $previousValue,

                    'current' =>
                        $currentValue,

                    'difference' =>
                        $difference,

                    'percentage' =>
                        $percentage,

                    'description' =>
                        'Komponen mengalami perubahan tetapi masih berada di bawah batas perubahan besar.',
                ];
            }


            /*
             * =================================================
             * VALIDASI TOTAL PAYROLL KARYAWAN
             * =================================================
             */
            $salary = $this->number(
                $currentEmployee->salary
            );

            $totalIncome = $this->number(
                $currentEmployee->total_income
            );

            $totalDeduction = $this->number(
                $currentEmployee->total_deduction
            );

            $netSalary = $this->number(
                $currentEmployee->net_salary
            );


            /*
             * =============================================
             * TOTAL INCOME < SALARY
             * =============================================
             */
            if (
                $totalIncome > 0 &&
                $totalIncome < $salary
            ) {

                $summary['inconsistent']++;

                $details[] = [
                    'employee_no' =>
                        $currentEmployee->employee_no,

                    'name' =>
                        $currentEmployee->name,

                    'department' =>
                        $currentEmployee->department,

                    'component' =>
                        'Total Income',

                    'status' =>
                        'inconsistent',

                    'status_label' =>
                        'Data Tidak Konsisten',

                    'previous' =>
                        null,

                    'current' =>
                        $totalIncome,

                    'difference' =>
                        null,

                    'percentage' =>
                        null,

                    'description' =>
                        'Total Income lebih kecil daripada Gaji Pokok.',
                ];
            }


            /*
             * =============================================
             * NET SALARY > TOTAL INCOME
             * =============================================
             */
            if (
                $totalIncome > 0 &&
                $netSalary > $totalIncome
            ) {

                $summary['inconsistent']++;

                $details[] = [
                    'employee_no' =>
                        $currentEmployee->employee_no,

                    'name' =>
                        $currentEmployee->name,

                    'department' =>
                        $currentEmployee->department,

                    'component' =>
                        'Net Salary',

                    'status' =>
                        'inconsistent',

                    'status_label' =>
                        'Data Tidak Konsisten',

                    'previous' =>
                        null,

                    'current' =>
                        $netSalary,

                    'difference' =>
                        null,

                    'percentage' =>
                        null,

                    'description' =>
                        'Net Salary lebih besar daripada Total Income.',
                ];
            }


            /*
             * =============================================
             * DEDUCTION > INCOME
             * =============================================
             */
            if (
                $totalIncome > 0 &&
                $totalDeduction > $totalIncome
            ) {

                $summary['inconsistent']++;

                $details[] = [
                    'employee_no' =>
                        $currentEmployee->employee_no,

                    'name' =>
                        $currentEmployee->name,

                    'department' =>
                        $currentEmployee->department,

                    'component' =>
                        'Total Deduction',

                    'status' =>
                        'inconsistent',

                    'status_label' =>
                        'Data Tidak Konsisten',

                    'previous' =>
                        null,

                    'current' =>
                        $totalDeduction,

                    'difference' =>
                        null,

                    'percentage' =>
                        null,

                    'description' =>
                        'Total Deduction lebih besar daripada Total Income.',
                ];
            }
        }


        /*
         * =====================================================
         * KARYAWAN YANG HILANG
         * =====================================================
         */
        foreach ($previousEmployees as $previousEmployee) {

            $employeeNo = strtoupper(
                trim((string) $previousEmployee->employee_no)
            );

            /*
             * Jika masih ada di periode sekarang,
             * tidak termasuk karyawan hilang.
             */
            if (isset($currentByEmployee[$employeeNo])) {
                continue;
            }

            $summary['missingEmployees']++;


            foreach ($components as $field => $label) {

                $previousValue = $this->number(
                    $previousEmployee->{$field} ?? 0
                );

                if ($previousValue <= 0) {
                    continue;
                }

                $summary['missingComponents']++;

                $details[] = [
                    'employee_no' =>
                        $previousEmployee->employee_no,

                    'name' =>
                        $previousEmployee->name,

                    'department' =>
                        $previousEmployee->department,

                    'component' =>
                        $label,

                    'status' =>
                        'missing_component',

                    'status_label' =>
                        'Komponen Hilang',

                    'previous' =>
                        $previousValue,

                    'current' =>
                        0,

                    'difference' =>
                        -$previousValue,

                    'percentage' =>
                        -100,

                    'description' =>
                        'Karyawan tidak ditemukan pada periode sekarang sehingga komponen payroll sebelumnya tidak muncul.',
                ];
            }
        }


        /*
         * =====================================================
         * PAYROLL KESELURUHAN
         * =====================================================
         */
        $previousPayroll = $this->number(
            $previous->total_net_salary
        );

        $currentPayroll = $this->number(
            $current->total_net_salary
        );

        $payrollDifference =
            $currentPayroll -
            $previousPayroll;

        $payrollPercentage = null;

        if ($previousPayroll != 0) {

            $payrollPercentage = round(
                (
                    $payrollDifference /
                    abs($previousPayroll)
                ) * 100,
                2
            );
        }


        /*
         * =====================================================
         * RETURN
         * =====================================================
         */
        return [
            'summary' =>
                $summary,

            'details' =>
                $details,

            'payroll' => [
                'previous' =>
                    $previousPayroll,

                'current' =>
                    $currentPayroll,

                'difference' =>
                    $payrollDifference,

                'percentage' =>
                    $payrollPercentage,
            ],
        ];
    }


    /**
     * =========================================================
     * KONVERSI ANGKA
     * =========================================================
     */
    private function number($value)
    {
        if (
            $value === null ||
            $value === ''
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


    /**
     * =========================================================
     * FORMAT NAMA PERIODE
     * =========================================================
     */
    private function formatPeriod($snapshot)
    {
        $bulan = str_pad(
            $snapshot->periode_bulan,
            2,
            '0',
            STR_PAD_LEFT
        );

        return $snapshot->periode_tahun . '-' . $bulan;
    }
}