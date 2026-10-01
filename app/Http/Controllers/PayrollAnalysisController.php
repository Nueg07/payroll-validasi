<?php

namespace App\Http\Controllers;

use App\Models\PayrollSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PayrollAnalysisController extends Controller
{
    /**
     * ==========================================================
     * HALAMAN UTAMA ANALISIS PAYROLL
     * ==========================================================
     *
     * Mode:
     * - monthly = per bulan
     * - yearly  = per tahun
     *
     * Payday:
     * - all
     * - 05
     * - 15
     * - 25
     */
    public function index(Request $request)
    {
        /**
         * ----------------------------------------------------------
         * Ambil semua snapshot beserta detail
         * ----------------------------------------------------------
         */
        $snapshots = PayrollSnapshot::with('details')
            ->orderBy('periode_tahun')
            ->orderBy('periode_bulan')
            ->orderBy('report_date')
            ->orderBy('id')
            ->get();

        /**
         * ----------------------------------------------------------
         * Mode perbandingan
         * ----------------------------------------------------------
         */
        $mode = $request->input('mode', 'monthly');

        if (!in_array($mode, ['monthly', 'yearly'])) {
            $mode = 'monthly';
        }

        /**
         * ----------------------------------------------------------
         * Payday / MTH
         * ----------------------------------------------------------
         */
        $payday = $request->input('payday', 'all');

        $allowedPaydays = [
            'all',
            '05',
            '15',
            '25',
        ];

        if (!in_array($payday, $allowedPaydays)) {
            $payday = 'all';
        }

        /**
         * ----------------------------------------------------------
         * Buat kelompok bulanan dan tahunan
         * ----------------------------------------------------------
         */
        $monthlyGroups = $this->buildMonthlyGroups($snapshots);

        $yearlyGroups = $this->buildYearlyGroups($snapshots);

        /**
         * ----------------------------------------------------------
         * Pilih kelompok sekarang
         * ----------------------------------------------------------
         */
        $groups = $mode === 'monthly'
            ? $monthlyGroups
            : $yearlyGroups;

        /**
         * ----------------------------------------------------------
         * Jika belum ada data
         * ----------------------------------------------------------
         */
        $currentGroup = null;
        $previousGroup = null;

        if ($groups->count() > 0) {

            /**
             * ------------------------------------------------------
             * Current
             * ------------------------------------------------------
             */
            $requestedCurrent = $request->input('current');

            if ($requestedCurrent) {

                $currentGroup = $groups->firstWhere(
                    'key',
                    $requestedCurrent
                );
            }

            /**
             * ------------------------------------------------------
             * Jika current tidak ditemukan,
             * gunakan kelompok terakhir
             * ------------------------------------------------------
             */
            if (!$currentGroup) {

                $currentGroup = $groups->last();
            }

            /**
             * ------------------------------------------------------
             * Previous
             * ------------------------------------------------------
             */
            $requestedPrevious = $request->input('previous');

            if ($requestedPrevious) {

                $previousGroup = $groups->firstWhere(
                    'key',
                    $requestedPrevious
                );

            } else {

                /**
                 * --------------------------------------------------
                 * Cari kelompok sebelum current
                 * --------------------------------------------------
                 */
                $currentIndex = $groups->search(
                    fn ($group) =>
                        $group['key'] === $currentGroup['key']
                );

                if (
                    $currentIndex !== false &&
                    $currentIndex > 0
                ) {

                    $previousGroup = $groups->get(
                        $currentIndex - 1
                    );
                }
            }
        }

        /**
         * ----------------------------------------------------------
         * Ambil snapshot berdasarkan group + payday
         * ----------------------------------------------------------
         */
        $currentSnapshots = collect();

        $previousSnapshots = collect();

        if ($currentGroup) {

            $currentSnapshots = $this->getGroupSnapshots(
                $snapshots,
                $currentGroup['key'],
                $mode,
                $payday
            );
        }

        if ($previousGroup) {

            $previousSnapshots = $this->getGroupSnapshots(
                $snapshots,
                $previousGroup['key'],
                $mode,
                $payday
            );
        }

        /**
         * ----------------------------------------------------------
         * Lakukan perbandingan
         * ----------------------------------------------------------
         */
        $comparison = $this->compareGroups(
            $previousSnapshots,
            $currentSnapshots
        );

        /**
         * ----------------------------------------------------------
         * Kirim ke Blade
         * ----------------------------------------------------------
         */
        return view('payroll.analysis', compact(
            'snapshots',
            'mode',
            'payday',
            'monthlyGroups',
            'yearlyGroups',
            'currentGroup',
            'previousGroup',
            'comparison'
        ));
    }


    /**
     * ==========================================================
     * EXPORT HASIL ANALISIS KE EXCEL
     * ==========================================================
     *
     * Hasil akan didownload sebagai CSV.
     *
     * CSV dapat langsung dibuka menggunakan Microsoft Excel.
     */
    public function exportExcel(Request $request)
    {
        $data = $this->buildExportData($request);

        $comparison = $data['comparison'];

        $currentGroup = $data['currentGroup'];

        $period = $currentGroup['key'] ?? 'periode';

        $filename =
            'analisis-payroll-' .
            str_replace(
                ['/', '\\', ' '],
                '-',
                $period
            ) .
            '.csv';

        return response()->streamDownload(
            function () use ($comparison, $data) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                /**
                 * --------------------------------------------------
                 * UTF-8 BOM
                 * --------------------------------------------------
                 *
                 * Supaya Excel dapat membaca karakter Indonesia
                 * dengan benar.
                 */
                fwrite(
                    $handle,
                    "\xEF\xBB\xBF"
                );

                /**
                 * --------------------------------------------------
                 * Judul
                 * --------------------------------------------------
                 */
                fputcsv(
                    $handle,
                    [
                        'ANALISIS PAYROLL'
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Periode',
                        $currentGroupLabel = (
                            $currentGroup['label']
                            ?? '-'
                        )
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Mode',
                        $data['mode']
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Payday',
                        $data['payday']
                    ]
                );

                fputcsv(
                    $handle,
                    []
                );

                /**
                 * --------------------------------------------------
                 * Ringkasan payroll
                 * --------------------------------------------------
                 */
                fputcsv(
                    $handle,
                    [
                        'RINGKASAN PAYROLL'
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Payroll Sebelumnya',
                        $comparison['payroll']['previous'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Payroll Sekarang',
                        $comparison['payroll']['current'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Selisih Payroll',
                        $comparison['payroll']['difference'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Persentase',
                        $comparison['payroll']['percentage'] !== null
                            ? $comparison['payroll']['percentage'] . '%'
                            : '-'
                    ]
                );

                fputcsv(
                    $handle,
                    []
                );

                /**
                 * --------------------------------------------------
                 * Summary perubahan
                 * --------------------------------------------------
                 */
                fputcsv(
                    $handle,
                    [
                        'RINGKASAN PERUBAHAN'
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Naik',
                        $comparison['summary']['increase'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Turun',
                        $comparison['summary']['decrease'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Sama',
                        $comparison['summary']['same'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Komponen Baru',
                        $comparison['summary']['new'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Komponen Hilang',
                        $comparison['summary']['missing'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Karyawan Baru',
                        $comparison['summary']['newEmployees'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Karyawan Tidak Ada',
                        $comparison['summary']['missingEmployees'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Total Kenaikan',
                        $comparison['summary']['totalIncrease'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Total Penurunan',
                        $comparison['summary']['totalDecrease'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    [
                        'Total Employee',
                        $comparison['summary']['totalEmployees'] ?? 0
                    ]
                );

                fputcsv(
                    $handle,
                    []
                );

                /**
                 * --------------------------------------------------
                 * Header detail perubahan
                 * --------------------------------------------------
                 */
                fputcsv(
                    $handle,
                    [
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
                    ]
                );

                /**
                 * --------------------------------------------------
                 * Detail perubahan
                 * --------------------------------------------------
                 */
                foreach (
                    $comparison['rows'] ?? []
                    as $row
                ) {

                    $percentage =
                        $row['percentage'] !== null
                            ? $row['percentage'] . '%'
                            : '-';

                    fputcsv(
                        $handle,
                        [
                            $row['employee_no'] ?? '',
                            $row['name'] ?? '',
                            $row['department'] ?? '',
                            $row['component'] ?? '',
                            $row['previous'] ?? 0,
                            $row['current'] ?? 0,
                            $row['difference'] ?? 0,
                            $percentage,
                            strtoupper(
                                $row['status'] ?? ''
                            ),
                            $row['description'] ?? ''
                        ]
                    );
                }

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' =>
                    'text/csv; charset=UTF-8',
            ]
        );
    }


    /**
     * ==========================================================
     * EXPORT PDF
     * ==========================================================
     *
     * Menampilkan hasil analisis dalam halaman khusus PDF.
     *
     * Dari browser:
     * Ctrl + P
     * kemudian pilih Save as PDF.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->buildExportData($request);

        return view(
            'payroll.analysis-export',
            $data
        );
    }


    /**
     * ==========================================================
     * DATA UNTUK EXPORT
     * ==========================================================
     *
     * Fungsi ini menggunakan logika yang sama dengan halaman
     * analysis agar hasil export tidak berbeda dengan hasil
     * perbandingan yang tampil di halaman utama.
     */
    private function buildExportData(Request $request): array
    {
        /**
         * ----------------------------------------------------------
         * Ambil semua snapshot beserta detail
         * ----------------------------------------------------------
         */
        $snapshots = PayrollSnapshot::with('details')
            ->orderBy('periode_tahun')
            ->orderBy('periode_bulan')
            ->orderBy('report_date')
            ->orderBy('id')
            ->get();

        /**
         * ----------------------------------------------------------
         * Mode
         * ----------------------------------------------------------
         */
        $mode = $request->input(
            'mode',
            'monthly'
        );

        if (!in_array($mode, ['monthly', 'yearly'])) {
            $mode = 'monthly';
        }

        /**
         * ----------------------------------------------------------
         * Payday
         * ----------------------------------------------------------
         */
        $payday = $request->input(
            'payday',
            'all'
        );

        if (!in_array(
            $payday,
            ['all', '05', '15', '25']
        )) {

            $payday = 'all';
        }

        /**
         * ----------------------------------------------------------
         * Group
         * ----------------------------------------------------------
         */
        $monthlyGroups =
            $this->buildMonthlyGroups(
                $snapshots
            );

        $yearlyGroups =
            $this->buildYearlyGroups(
                $snapshots
            );

        $groups =
            $mode === 'monthly'
                ? $monthlyGroups
                : $yearlyGroups;

        /**
         * ----------------------------------------------------------
         * Current dan Previous
         * ----------------------------------------------------------
         */
        $currentGroup = null;

        $previousGroup = null;

        if ($groups->count() > 0) {

            $requestedCurrent =
                $request->input('current');

            if ($requestedCurrent) {

                $currentGroup =
                    $groups->firstWhere(
                        'key',
                        $requestedCurrent
                    );
            }

            if (!$currentGroup) {

                $currentGroup =
                    $groups->last();
            }

            $requestedPrevious =
                $request->input('previous');

            if ($requestedPrevious) {

                $previousGroup =
                    $groups->firstWhere(
                        'key',
                        $requestedPrevious
                    );

            } else {

                $currentIndex =
                    $groups->search(
                        fn ($group) =>
                            $group['key'] ===
                            $currentGroup['key']
                    );

                if (
                    $currentIndex !== false &&
                    $currentIndex > 0
                ) {

                    $previousGroup =
                        $groups->get(
                            $currentIndex - 1
                        );
                }
            }
        }

        /**
         * ----------------------------------------------------------
         * Snapshot current
         * ----------------------------------------------------------
         */
        $currentSnapshots =
            collect();

        /**
         * ----------------------------------------------------------
         * Snapshot previous
         * ----------------------------------------------------------
         */
        $previousSnapshots =
            collect();

        if ($currentGroup) {

            $currentSnapshots =
                $this->getGroupSnapshots(
                    $snapshots,
                    $currentGroup['key'],
                    $mode,
                    $payday
                );
        }

        if ($previousGroup) {

            $previousSnapshots =
                $this->getGroupSnapshots(
                    $snapshots,
                    $previousGroup['key'],
                    $mode,
                    $payday
                );
        }

        /**
         * ----------------------------------------------------------
         * Perbandingan
         * ----------------------------------------------------------
         */
        $comparison =
            $this->compareGroups(
                $previousSnapshots,
                $currentSnapshots
            );

        return [
            'snapshots' =>
                $snapshots,

            'mode' =>
                $mode,

            'payday' =>
                $payday,

            'monthlyGroups' =>
                $monthlyGroups,

            'yearlyGroups' =>
                $yearlyGroups,

            'currentGroup' =>
                $currentGroup,

            'previousGroup' =>
                $previousGroup,

            'comparison' =>
                $comparison,
        ];
    }


    /**
     * ==========================================================
     * GROUP BULANAN
     * ==========================================================
     */
    private function buildMonthlyGroups(
        Collection $snapshots
    ): Collection {

        return $snapshots
            ->groupBy(function ($snapshot) {

                return sprintf(
                    '%04d-%02d',
                    $snapshot->periode_tahun,
                    $snapshot->periode_bulan
                );
            })
            ->map(function ($items, $key) {

                $first = $items->first();

                $monthNames = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ];

                $bulan =
                    (int) $first->periode_bulan;

                return [

                    'key' =>
                        $key,

                    'label' =>
                        (
                            $monthNames[$bulan]
                            ?? 'Bulan'
                        )
                        . ' '
                        . $first->periode_tahun,

                    'year' =>
                        (int) $first->periode_tahun,

                    'month' =>
                        $bulan,

                    'file_count' =>
                        $items->count(),

                    'employee_count' =>
                        $this->countUniqueEmployees(
                            $items
                        ),

                    'total_net_salary' =>
                        $items->sum(
                            fn ($snapshot) =>
                                $this->number(
                                    $snapshot->total_net_salary
                                )
                        ),

                    'files' =>
                        $items
                            ->map(
                                function ($snapshot) {

                                    return [

                                        'id' =>
                                            $snapshot->id,

                                        'payroll_period' =>
                                            $snapshot->payroll_period,

                                        'report_date' =>
                                            optional(
                                                $snapshot->report_date
                                            )->format(
                                                'd-m-Y'
                                            ),

                                        'total_karyawan' =>
                                            $snapshot->total_karyawan,

                                        'total_net_salary' =>
                                            $this->number(
                                                $snapshot->total_net_salary
                                            ),
                                    ];
                                }
                            )
                            ->values()
                            ->all(),
                ];
            })
            ->sortBy('key')
            ->values();
    }


    /**
     * ==========================================================
     * GROUP TAHUNAN
     * ==========================================================
     */
    private function buildYearlyGroups(
        Collection $snapshots
    ): Collection {

        return $snapshots
            ->groupBy(function ($snapshot) {

                return (string)
                    $snapshot->periode_tahun;
            })
            ->map(function ($items, $key) {

                return [

                    'key' =>
                        $key,

                    'label' =>
                        'Tahun ' . $key,

                    'year' =>
                        (int) $key,

                    'file_count' =>
                        $items->count(),

                    'employee_count' =>
                        $this->countUniqueEmployees(
                            $items
                        ),

                    'total_net_salary' =>
                        $items->sum(
                            fn ($snapshot) =>
                                $this->number(
                                    $snapshot->total_net_salary
                                )
                        ),

                    'files' =>
                        $items
                            ->map(
                                function ($snapshot) {

                                    return [

                                        'id' =>
                                            $snapshot->id,

                                        'payroll_period' =>
                                            $snapshot->payroll_period,

                                        'report_date' =>
                                            optional(
                                                $snapshot->report_date
                                            )->format(
                                                'd-m-Y'
                                            ),

                                        'total_karyawan' =>
                                            $snapshot->total_karyawan,

                                        'total_net_salary' =>
                                            $this->number(
                                                $snapshot->total_net_salary
                                            ),
                                    ];
                                }
                            )
                            ->values()
                            ->all(),
                ];
            })
            ->sortBy('key')
            ->values();
    }


    /**
     * ==========================================================
     * AMBIL SNAPSHOT DALAM GROUP
     * ==========================================================
     */
    private function getGroupSnapshots(
        Collection $snapshots,
        string $groupKey,
        string $mode,
        string $payday
    ): Collection {

        $filtered =
            $snapshots->filter(
                function ($snapshot) use (
                    $groupKey,
                    $mode
                ) {

                    if ($mode === 'monthly') {

                        $key = sprintf(
                            '%04d-%02d',
                            $snapshot->periode_tahun,
                            $snapshot->periode_bulan
                        );

                    } else {

                        $key =
                            (string)
                            $snapshot->periode_tahun;
                    }

                    return $key === $groupKey;
                }
            );

        /**
         * ----------------------------------------------------------
         * Filter MTH
         * ----------------------------------------------------------
         */
        if ($payday !== 'all') {

            $filtered =
                $filtered->filter(
                    function ($snapshot) use (
                        $payday
                    ) {

                        return
                            $this->extractPayday(
                                $snapshot->payroll_period
                            ) === $payday;
                    }
                );
        }

        return $filtered->values();
    }


    /**
     * ==========================================================
     * EXTRACT MTH05 / MTH15 / MTH25
     * ==========================================================
     */
    private function extractPayday(
        ?string $payrollPeriod
    ): ?string {

        if (!$payrollPeriod) {
            return null;
        }

        if (
            preg_match(
                '/MTH\s*0*(\d{1,2})/i',
                $payrollPeriod,
                $matches
            )
        ) {

            return str_pad(
                (int) $matches[1],
                2,
                '0',
                STR_PAD_LEFT
            );
        }

        return null;
    }


    /**
     * ==========================================================
     * HITUNG KARYAWAN UNIK
     * ==========================================================
     */
    private function countUniqueEmployees(
        Collection $snapshots
    ): int {

        return $snapshots
            ->flatMap(
                function ($snapshot) {

                    return $snapshot->details;
                }
            )
            ->map(
                function ($detail) {

                    return
                        $this->normalizeEmployeeNo(
                            $detail->employee_no
                        );
                }
            )
            ->filter()
            ->unique()
            ->count();
    }


    /**
     * ==========================================================
     * PERBANDINGAN GROUP
     * ==========================================================
     */
    private function compareGroups(
        Collection $previousSnapshots,
        Collection $currentSnapshots
    ): array {

        /**
         * ----------------------------------------------------------
         * Gabungkan detail dari seluruh file
         * ----------------------------------------------------------
         */
        $previousEmployees =
            $this->mergeEmployees(
                $previousSnapshots
            );

        $currentEmployees =
            $this->mergeEmployees(
                $currentSnapshots
            );

        /**
         * ----------------------------------------------------------
         * Index previous employee
         * ----------------------------------------------------------
         */
        $previousByEmployeeNo = [];

        $previousByNameDepartment = [];

        foreach (
            $previousEmployees
            as $key => $employee
        ) {

            if (
                $employee['employee_no'] !== ''
            ) {

                $previousByEmployeeNo[
                    $employee['employee_no']
                ] = $key;
            }

            $nameDepartment =
                $this->normalizeText(
                    $employee['name']
                )
                . '|'
                . $this->normalizeText(
                    $employee['department']
                );

            if ($nameDepartment !== '|') {

                $previousByNameDepartment[
                    $nameDepartment
                ] = $key;
            }
        }

        $usedPrevious = [];

        $rows = [];

        $topChanges = [];

        /**
         * ----------------------------------------------------------
         * Summary
         * ----------------------------------------------------------
         */
        $increase = 0;

        $decrease = 0;

        $same = 0;

        $newComponents = 0;

        $missingComponents = 0;

        $newEmployees = 0;

        $missingEmployees = 0;

        $totalIncrease = 0;

        $totalDecrease = 0;

        /**
         * ----------------------------------------------------------
         * Bandingkan employee current
         * ----------------------------------------------------------
         */
        foreach (
            $currentEmployees
            as $currentKey => $current
        ) {

            $previousKey = null;

            /**
             * ------------------------------------------------------
             * 1. Match Employee No
             * ------------------------------------------------------
             */
            if (
                $current['employee_no'] !== ''
                &&
                isset(
                    $previousByEmployeeNo[
                        $current['employee_no']
                    ]
                )
            ) {

                $candidate =
                    $previousByEmployeeNo[
                        $current['employee_no']
                    ];

                if (
                    !isset(
                        $usedPrevious[$candidate]
                    )
                ) {

                    $previousKey =
                        $candidate;
                }
            }

            /**
             * ------------------------------------------------------
             * 2. Match Name + Department
             * ------------------------------------------------------
             */
            if ($previousKey === null) {

                $nameDepartment =
                    $this->normalizeText(
                        $current['name']
                    )
                    . '|'
                    . $this->normalizeText(
                        $current['department']
                    );

                if (
                    isset(
                        $previousByNameDepartment[
                            $nameDepartment
                        ]
                    )
                ) {

                    $candidate =
                        $previousByNameDepartment[
                            $nameDepartment
                        ];

                    if (
                        !isset(
                            $usedPrevious[$candidate]
                        )
                    ) {

                        $previousKey =
                            $candidate;
                    }
                }
            }

            /**
             * ------------------------------------------------------
             * Employee Baru
             * ------------------------------------------------------
             */
            if ($previousKey === null) {

                $newEmployees++;

                $netSalary =
                    $this->number(
                        $current['net_salary']
                    );

                $totalIncrease +=
                    $netSalary;

                $rows[] = [

                    'employee_no' =>
                        $current['employee_no'],

                    'name' =>
                        $current['name'],

                    'department' =>
                        $current['department'],

                    'component' =>
                        'Karyawan Baru',

                    'previous' =>
                        0,

                    'current' =>
                        $netSalary,

                    'difference' =>
                        $netSalary,

                    'percentage' =>
                        null,

                    'status' =>
                        'new',

                    'description' =>
                        'Karyawan terdapat pada periode sekarang tetapi tidak ditemukan pada periode sebelumnya.',
                ];

                continue;
            }

            /**
             * ------------------------------------------------------
             * Tandai previous sudah digunakan
             * ------------------------------------------------------
             */
            $usedPrevious[
                $previousKey
            ] = true;

            $previous =
                $previousEmployees[
                    $previousKey
                ];

            /**
             * ------------------------------------------------------
             * Net salary employee
             * ------------------------------------------------------
             */
            $previousNet =
                $this->number(
                    $previous['net_salary']
                );

            $currentNet =
                $this->number(
                    $current['net_salary']
                );

            $netDifference =
                $currentNet -
                $previousNet;

            if ($netDifference > 0) {

                $increase++;

                $totalIncrease +=
                    $netDifference;

            } elseif ($netDifference < 0) {

                $decrease++;

                $totalDecrease +=
                    abs($netDifference);

            } else {

                $same++;
            }

            /**
             * ------------------------------------------------------
             * Bandingkan seluruh komponen
             * ------------------------------------------------------
             */
            foreach (
                $this->components()
                as $field => $label
            ) {

                $previousValue =
                    $this->number(
                        $previous[$field] ?? 0
                    );

                $currentValue =
                    $this->number(
                        $current[$field] ?? 0
                    );

                $difference =
                    $currentValue -
                    $previousValue;

                if (
                    abs($difference) < 0.01
                ) {

                    continue;
                }

                /**
                 * --------------------------------------------------
                 * Komponen baru
                 * --------------------------------------------------
                 */
                if (
                    abs($previousValue) < 0.01
                    &&
                    abs($currentValue) >= 0.01
                ) {

                    $status =
                        'new';

                    $newComponents++;

                    $description =
                        'Komponen memiliki nilai pada periode sekarang tetapi sebelumnya tidak memiliki nilai.';
                }

                /**
                 * --------------------------------------------------
                 * Komponen hilang
                 * --------------------------------------------------
                 */
                elseif (
                    abs($previousValue) >= 0.01
                    &&
                    abs($currentValue) < 0.01
                ) {

                    $status =
                        'missing';

                    $missingComponents++;

                    $description =
                        'Komponen memiliki nilai pada periode sebelumnya tetapi sekarang menjadi 0.';
                }

                /**
                 * --------------------------------------------------
                 * Naik
                 * --------------------------------------------------
                 */
                elseif ($difference > 0) {

                    $status =
                        'increase';

                    $description =
                        'Nilai komponen meningkat dibandingkan periode sebelumnya.';
                }

                /**
                 * --------------------------------------------------
                 * Turun
                 * --------------------------------------------------
                 */
                else {

                    $status =
                        'decrease';

                    $description =
                        'Nilai komponen menurun dibandingkan periode sebelumnya.';
                }

                /**
                 * --------------------------------------------------
                 * Percentage
                 * --------------------------------------------------
                 */
                if (
                    abs($previousValue) > 0.01
                ) {

                    $percentage =
                        round(
                            (
                                $difference
                                /
                                abs($previousValue)
                            )
                            * 100,
                            2
                        );

                } else {

                    $percentage =
                        null;
                }

                $row = [

                    'employee_no' =>
                        $current['employee_no'],

                    'name' =>
                        $current['name'],

                    'department' =>
                        $current['department'],

                    'component' =>
                        $label,

                    'previous' =>
                        $previousValue,

                    'current' =>
                        $currentValue,

                    'difference' =>
                        $difference,

                    'percentage' =>
                        $percentage,

                    'status' =>
                        $status,

                    'description' =>
                        $description,
                ];

                $rows[] =
                    $row;

                $topChanges[] =
                    $row;
            }
        }

        /**
         * ----------------------------------------------------------
         * Cari employee yang sudah tidak ada
         * ----------------------------------------------------------
         */
        foreach (
            $previousEmployees
            as $previousKey => $previous
        ) {

            if (
                isset(
                    $usedPrevious[
                        $previousKey
                    ]
                )
            ) {

                continue;
            }

            $missingEmployees++;

            $netSalary =
                $this->number(
                    $previous['net_salary']
                );

            $totalDecrease +=
                $netSalary;

            $rows[] = [

                'employee_no' =>
                    $previous['employee_no'],

                'name' =>
                    $previous['name'],

                'department' =>
                    $previous['department'],

                'component' =>
                    'Karyawan Tidak Ada',

                'previous' =>
                    $netSalary,

                'current' =>
                    0,

                'difference' =>
                    -$netSalary,

                'percentage' =>
                    -100,

                'status' =>
                    'missing',

                'description' =>
                    'Karyawan terdapat pada periode sebelumnya tetapi tidak ditemukan pada periode sekarang.',
            ];
        }

        /**
         * ----------------------------------------------------------
         * Urutkan Top 10 berdasarkan absolute difference
         * ----------------------------------------------------------
         */
        usort(
            $topChanges,
            function ($a, $b) {

                return
                    abs(
                        $b['difference']
                    )
                    <=>
                    abs(
                        $a['difference']
                    );
            }
        );

        $topChanges =
            array_slice(
                $topChanges,
                0,
                10
            );

        /**
         * ----------------------------------------------------------
         * Payroll total
         * ----------------------------------------------------------
         */
        $previousPayroll =
            $previousSnapshots->sum(
                fn ($snapshot) =>
                    $this->number(
                        $snapshot->total_net_salary
                    )
            );

        $currentPayroll =
            $currentSnapshots->sum(
                fn ($snapshot) =>
                    $this->number(
                        $snapshot->total_net_salary
                    )
            );

        $payrollDifference =
            $currentPayroll -
            $previousPayroll;

        if (
            abs($previousPayroll) > 0.01
        ) {

            $payrollPercentage =
                round(
                    (
                        $payrollDifference
                        /
                        abs($previousPayroll)
                    )
                    * 100,
                    2
                );

        } else {

            $payrollPercentage =
                null;
        }

        /**
         * ----------------------------------------------------------
         * Employee total
         * ----------------------------------------------------------
         */
        $totalEmployees =
            count(
                array_unique(
                    array_merge(
                        array_keys(
                            $currentEmployees
                        ),
                        array_keys(
                            $previousEmployees
                        )
                    )
                )
            );

        return [

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

            'summary' => [

                'increase' =>
                    $increase,

                'decrease' =>
                    $decrease,

                'same' =>
                    $same,

                'new' =>
                    $newComponents,

                'missing' =>
                    $missingComponents,

                'newEmployees' =>
                    $newEmployees,

                'missingEmployees' =>
                    $missingEmployees,

                'totalIncrease' =>
                    $totalIncrease,

                'totalDecrease' =>
                    $totalDecrease,

                'totalEmployees' =>
                    $totalEmployees,
            ],

            'topChanges' =>
                $topChanges,

            'rows' =>
                $rows,
        ];
    }


    /**
     * ==========================================================
     * GABUNGKAN EMPLOYEE DARI BEBERAPA FILE
     * ==========================================================
     */
    private function mergeEmployees(
        Collection $snapshots
    ): array {

        $employees = [];

        foreach (
            $snapshots as $snapshot
        ) {

            foreach (
                $snapshot->details as $detail
            ) {

                $employeeNo =
                    $this->normalizeEmployeeNo(
                        $detail->employee_no
                    );

                $name =
                    trim(
                        (string)
                        $detail->name
                    );

                $department =
                    trim(
                        (string)
                        $detail->department
                    );

                /**
                 * --------------------------------------------------
                 * Gunakan Employee No sebagai key
                 * --------------------------------------------------
                 */
                if ($employeeNo !== '') {

                    $key =
                        'EMPLOYEE:' .
                        $employeeNo;

                } else {

                    $key =
                        'NAME:'
                        .
                        $this->normalizeText(
                            $name
                        )
                        .
                        '|'
                        .
                        $this->normalizeText(
                            $department
                        );
                }

                /**
                 * --------------------------------------------------
                 * Inisialisasi
                 * --------------------------------------------------
                 */
                if (
                    !isset(
                        $employees[$key]
                    )
                ) {

                    $employees[$key] = [

                        'employee_no' =>
                            $employeeNo,

                        'name' =>
                            $name,

                        'department' =>
                            $department,
                    ];

                    foreach (
                        $this->components()
                        as $field => $label
                    ) {

                        $employees[$key][$field] =
                            0;
                    }
                }

                /**
                 * --------------------------------------------------
                 * Jumlahkan nilai payroll
                 * --------------------------------------------------
                 */
                foreach (
                    $this->components()
                    as $field => $label
                ) {

                    $employees[$key][$field]
                        += $this->number(
                            $detail->{$field} ?? 0
                        );
                }
            }
        }

        return $employees;
    }


    /**
     * ==========================================================
     * KOMPONEN PAYROLL
     * ==========================================================
     */
    private function components(): array
    {
        return [

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
                'Allowance',

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

            'total_income' =>
                'Total Income',

            'tax_borne_by_company' =>
                'Tax Borne by Company',

            'deduction' =>
                'Deduction',

            'deduction_bpjsstk_jht_employee' =>
                'Deduction BPJSTK JHT Employee',

            'deduction_bpjstk_jp_employee' =>
                'Deduction BPJSTK JP Employee',

            'deduction_bpjsks_company' =>
                'Deduction BPJSKS Company',

            'potongan_lain_lain' =>
                'Potongan Lain-Lain',

            'deduction_bpjstk_jkk' =>
                'Deduction BPJSTK JKK',

            'deduction_bpjstk_jkm' =>
                'Deduction BPJSTK JKM',

            'deduction_bpjstk_jht' =>
                'Deduction BPJSTK JHT',

            'deduction_bpjsks_employee' =>
                'Deduction BPJSKS Employee',

            'deduction_bpjstk_jp_company' =>
                'Deduction BPJSTK JP Company',

            'total_deduction' =>
                'Total Deduction',

            'tax' =>
                'Tax',

            'net_salary' =>
                'Net Salary',

            'dasar_bpjstk' =>
                'Dasar BPJSTK',

            'netral_bpjstk_jp' =>
                'Netral BPJSTK JP',

            'dasar_bpjsks' =>
                'Dasar BPJSKS',

            'total_ot_1' =>
                'Total OT 1',

            'total_ot_2' =>
                'Total OT 2',

            'total_ot_3' =>
                'Total OT 3',

            'total_ot_4' =>
                'Total OT 4',

            'total_overtime' =>
                'Total Overtime',

            'nilai_ot_mak' =>
                'Nilai OT Mak',
        ];
    }


    /**
     * ==========================================================
     * NORMALISASI EMPLOYEE NO
     * ==========================================================
     */
    private function normalizeEmployeeNo(
        $value
    ): string {

        $value =
            strtoupper(
                trim(
                    (string)
                    $value
                )
            );

        $value =
            preg_replace(
                '/\s+/',
                '',
                $value
            );

        $value =
            preg_replace(
                '/\.0+$/',
                '',
                $value
            );

        return $value;
    }


    /**
     * ==========================================================
     * NORMALISASI TEXT
     * ==========================================================
     */
    private function normalizeText(
        $value
    ): string {

        return strtoupper(
            trim(
                preg_replace(
                    '/\s+/',
                    ' ',
                    (string)
                    $value
                )
            )
        );
    }


    /**
     * ==========================================================
     * ANGKA
     * ==========================================================
     */
    private function number(
        $value
    ): float {

        if (
            $value === null ||
            $value === ''
        ) {

            return 0;
        }

        if (is_numeric($value)) {

            return (float)
                $value;
        }

        $value =
            str_replace(
                [
                    'Rp',
                    'rp',
                    ' '
                ],
                '',
                (string)
                $value
            );

        $value =
            str_replace(
                ',',
                '',
                $value
            );

        return is_numeric($value)
            ? (float) $value
            : 0;
    }
}