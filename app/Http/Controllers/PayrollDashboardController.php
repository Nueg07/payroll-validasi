<?php

namespace App\Http\Controllers;

use App\Models\PayrollSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PayrollDashboardController extends Controller
{
    /**
     * Dashboard Payroll
     *
     * Menampilkan:
     * - Total payroll
     * - Total net salary
     * - Payroll ratio
     * - Jumlah karyawan
     * - Perubahan payroll
     * - Flagging naik/turun
     * - Analisis departemen
     * - Top 10 perubahan
     * - Summary bulanan
     * - Summary tahunan
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Ambil seluruh snapshot payroll
        |--------------------------------------------------------------------------
        */

        $snapshots = PayrollSnapshot::with('details')
            ->orderBy('periode_tahun')
            ->orderBy('periode_bulan')
            ->orderBy('report_date')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 2. Filter tanggal payroll
        |--------------------------------------------------------------------------
        */

        $payday = $request->input('payday', 'all');

        $allowedPaydays = [
            'all',
            '05',
            '15',
            '25',
            '28',
        ];

        if (!in_array($payday, $allowedPaydays, true)) {
            $payday = 'all';
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Filter snapshot berdasarkan tanggal payroll
        |--------------------------------------------------------------------------
        */

        $availableSnapshots = $snapshots
            ->filter(function ($snapshot) use ($payday) {

                if ($payday === 'all') {
                    return true;
                }

                if (!$snapshot->report_date) {
                    return false;
                }

                $date = $this->normalizeDate($snapshot->report_date);

                if (!$date) {
                    return false;
                }

                return $date->format('d') === $payday;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | 4. Tentukan snapshot yang dipilih
        |--------------------------------------------------------------------------
        */

        $selected = null;

        if ($request->filled('snapshot')) {
            $selected = $availableSnapshots->firstWhere(
                'id',
                (int) $request->input('snapshot')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Jika tidak ada pilihan, gunakan snapshot terbaru
        |--------------------------------------------------------------------------
        */

        if (!$selected) {
            $selected = $availableSnapshots->last();
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Cari snapshot sebelumnya
        |--------------------------------------------------------------------------
        */

        $previous = null;

        if ($selected) {

            $selectedIndex = $availableSnapshots->search(
                function ($item) use ($selected) {
                    return (int) $item->id === (int) $selected->id;
                }
            );

            if (
                $selectedIndex !== false &&
                $selectedIndex > 0
            ) {
                $previous = $availableSnapshots->get(
                    $selectedIndex - 1
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Summary utama
        |--------------------------------------------------------------------------
        */

        $summary = $this->summary(
            $selected,
            $previous
        );

        /*
        |--------------------------------------------------------------------------
        | 8. Analisis departemen
        |--------------------------------------------------------------------------
        */

        $departments = $this->departmentAnalysis(
            $selected
        );

        /*
        |--------------------------------------------------------------------------
        | 9. Top 10 perubahan payroll
        |--------------------------------------------------------------------------
        */

        $topChanges = $this->topChanges(
            $selected,
            $previous
        );

        /*
        |--------------------------------------------------------------------------
        | 10. Summary bulanan
        |--------------------------------------------------------------------------
        */

        $monthly = $this->monthlySummary(
            $snapshots,
            $payday
        );

        /*
        |--------------------------------------------------------------------------
        | 11. Summary tahunan
        |--------------------------------------------------------------------------
        */

        $yearly = $this->yearlySummary(
            $snapshots,
            $payday
        );

        /*
        |--------------------------------------------------------------------------
        | 12. Kirim data ke view
        |--------------------------------------------------------------------------
        */

        return view(
            'payroll.dashboard',
            compact(
                'snapshots',
                'availableSnapshots',
                'selected',
                'previous',
                'payday',
                'summary',
                'departments',
                'topChanges',
                'monthly',
                'yearly'
            )
        );
    }


    /**
     * Summary utama dashboard.
     */
    private function summary(
        $current,
        $previous
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Jika belum ada data
        |--------------------------------------------------------------------------
        */

        if (!$current) {
            return [
                'employees' => 0,
                'totalPayroll' => 0,
                'totalIncome' => 0,
                'netSalary' => 0,
                'ratio' => 0,
                'averagePayroll' => 0,
                'increase' => 0,
                'decrease' => 0,
                'change' => 0,
                'changePercent' => null,
                'flagged' => 0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Payroll saat ini
        |--------------------------------------------------------------------------
        */

        $currentPayroll = (float) $current->total_income;

        /*
        |--------------------------------------------------------------------------
        | Payroll sebelumnya
        |--------------------------------------------------------------------------
        */

        $previousPayroll = $previous
            ? (float) $previous->total_income
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Perubahan payroll
        |--------------------------------------------------------------------------
        */

        $change = $currentPayroll - $previousPayroll;

        /*
        |--------------------------------------------------------------------------
        | Persentase perubahan
        |--------------------------------------------------------------------------
        */

        $changePercent = $previousPayroll != 0
            ? ($change / $previousPayroll) * 100
            : null;

        /*
        |--------------------------------------------------------------------------
        | Flagging
        |--------------------------------------------------------------------------
        */

        $increase = 0;
        $decrease = 0;
        $flagged = 0;

        /*
        |--------------------------------------------------------------------------
        | Bandingkan detail karyawan
        |--------------------------------------------------------------------------
        */

        if ($previous) {

            $old = $previous->details
                ->keyBy(function ($row) {
                    return $this->employeeKey($row);
                });

            foreach ($current->details as $row) {

                $key = $this->employeeKey($row);

                $newValue = (float) $row->total_income;

                $oldValue = isset($old[$key])
                    ? (float) $old[$key]->total_income
                    : 0;

                $difference = $newValue - $oldValue;

                if (abs($difference) > 0.009) {

                    $flagged++;

                    if ($difference > 0) {
                        $increase++;
                    } else {
                        $decrease++;
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Jumlah karyawan
        |--------------------------------------------------------------------------
        */

        $employees = (int) $current->total_karyawan;

        /*
        |--------------------------------------------------------------------------
        | Return summary
        |--------------------------------------------------------------------------
        */

        return [

            'employees' =>
                $employees,

            'totalPayroll' =>
                $currentPayroll,

            'totalIncome' =>
                $currentPayroll,

            'netSalary' =>
                (float) $current->total_net_salary,

            /*
            |--------------------------------------------------------------------------
            | Payroll Ratio
            |
            | Net Salary / Total Income x 100
            |--------------------------------------------------------------------------
            */

            'ratio' =>
                $currentPayroll > 0
                    ? (
                        (
                            (float) $current->total_net_salary
                        ) / $currentPayroll
                    ) * 100
                    : 0,

            /*
            |--------------------------------------------------------------------------
            | Rata-rata payroll per karyawan
            |--------------------------------------------------------------------------
            */

            'averagePayroll' =>
                $employees > 0
                    ? $currentPayroll / $employees
                    : 0,

            /*
            |--------------------------------------------------------------------------
            | Jumlah naik
            |--------------------------------------------------------------------------
            */

            'increase' =>
                $increase,

            /*
            |--------------------------------------------------------------------------
            | Jumlah turun
            |--------------------------------------------------------------------------
            */

            'decrease' =>
                $decrease,

            /*
            |--------------------------------------------------------------------------
            | Nominal perubahan
            |--------------------------------------------------------------------------
            */

            'change' =>
                $change,

            /*
            |--------------------------------------------------------------------------
            | Persentase perubahan
            |--------------------------------------------------------------------------
            */

            'changePercent' =>
                $changePercent,

            /*
            |--------------------------------------------------------------------------
            | Total data berubah
            |--------------------------------------------------------------------------
            */

            'flagged' =>
                $flagged,
        ];
    }


    /**
     * Analisis payroll berdasarkan departemen.
     */
    private function departmentAnalysis(
        $snapshot
    ): Collection {

        if (!$snapshot) {
            return collect();
        }

        /*
        |--------------------------------------------------------------------------
        | Total income seluruh departemen
        |--------------------------------------------------------------------------
        */

        $total = (float) $snapshot->details
            ->sum(function ($row) {
                return (float) $row->total_income;
            });

        /*
        |--------------------------------------------------------------------------
        | Kelompokkan berdasarkan department
        |--------------------------------------------------------------------------
        */

        return $snapshot->details
            ->groupBy(function ($row) {

                $department = trim(
                    (string) $row->department
                );

                return $department !== ''
                    ? $department
                    : 'Tanpa Departemen';
            })

            ->map(function (
                Collection $rows,
                $department
            ) use ($total) {

                $income = (float) $rows
                    ->sum(function ($row) {
                        return (float) $row->total_income;
                    });

                $net = (float) $rows
                    ->sum(function ($row) {
                        return (float) $row->net_salary;
                    });

                return [

                    'department' =>
                        $department,

                    'employees' =>
                        $rows->count(),

                    'total_income' =>
                        $income,

                    'net_salary' =>
                        $net,

                    'ratio' =>
                        $total > 0
                            ? ($income / $total) * 100
                            : 0,
                ];
            })

            ->sortByDesc('total_income')
            ->values();
    }


    /**
     * Ambil 10 perubahan payroll terbesar.
     */
    private function topChanges(
        $current,
        $previous
    ): Collection {

        if (!$current || !$previous) {
            return collect();
        }

        /*
        |--------------------------------------------------------------------------
        | Index periode lama
        |--------------------------------------------------------------------------
        */

        $old = $previous->details
            ->keyBy(function ($row) {
                return $this->employeeKey($row);
            });

        /*
        |--------------------------------------------------------------------------
        | Bandingkan periode sekarang dengan periode sebelumnya
        |--------------------------------------------------------------------------
        */

        return $current->details

            ->map(function ($row) use ($old) {

                $key = $this->employeeKey($row);

                $currentValue =
                    (float) $row->total_income;

                $previousValue =
                    isset($old[$key])
                        ? (float) $old[$key]->total_income
                        : 0;

                $difference =
                    $currentValue - $previousValue;

                return [

                    'employee_no' =>
                        $row->employee_no,

                    'name' =>
                        $row->name,

                    'department' =>
                        $row->department
                            ?: 'Tanpa Departemen',

                    'previous' =>
                        $previousValue,

                    'current' =>
                        $currentValue,

                    'difference' =>
                        $difference,

                    'percentage' =>
                        $previousValue != 0
                            ? (
                                $difference /
                                $previousValue
                            ) * 100
                            : null,
                ];
            })

            ->filter(function ($row) {

                return abs(
                    $row['difference']
                ) > 0.009;
            })

            ->sortByDesc(function ($row) {

                return abs(
                    $row['difference']
                );
            })

            ->take(10)
            ->values();
    }


    /**
     * Summary bulanan.
     */
    private function monthlySummary(
        Collection $snapshots,
        string $payday
    ): Collection {

        return $this->aggregatePeriods(
            $snapshots,
            $payday,
            false
        )->take(12);
    }


    /**
     * Summary tahunan.
     */
    private function yearlySummary(
        Collection $snapshots,
        string $payday
    ): Collection {

        return $this->aggregatePeriods(
            $snapshots,
            $payday,
            true
        )->take(10);
    }


    /**
     * Menggabungkan data berdasarkan bulan/tahun.
     */
    private function aggregatePeriods(
        Collection $snapshots,
        string $payday,
        bool $yearly
    ): Collection {

        /*
        |--------------------------------------------------------------------------
        | Filter payday
        |--------------------------------------------------------------------------
        */

        $filtered = $snapshots->filter(
            function ($snapshot) use ($payday) {

                if ($payday === 'all') {
                    return true;
                }

                if (!$snapshot->report_date) {
                    return false;
                }

                $date = $this->normalizeDate(
                    $snapshot->report_date
                );

                if (!$date) {
                    return false;
                }

                return $date->format('d') === $payday;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Kelompokkan
        |--------------------------------------------------------------------------
        */

        return $filtered

            ->groupBy(
                function ($snapshot) use ($yearly) {

                    if ($yearly) {

                        return (string)
                            $snapshot->periode_tahun;
                    }

                    return sprintf(
                        '%04d-%02d',
                        $snapshot->periode_tahun,
                        $snapshot->periode_bulan
                    );
                }
            )

            ->map(
                function (
                    Collection $rows,
                    $key
                ) use ($yearly) {

                    $income = (float) $rows
                        ->sum(function ($row) {

                            return (float)
                                $row->total_income;
                        });

                    $net = (float) $rows
                        ->sum(function ($row) {

                            return (float)
                                $row->total_net_salary;
                        });

                    /*
                    |--------------------------------------------------------------------------
                    | Untuk jumlah karyawan:
                    |
                    | Jika satu bulan memiliki beberapa snapshot
                    | maka gunakan snapshot terakhir pada periode tersebut.
                    |--------------------------------------------------------------------------
                    */

                    $latestRow = $rows
                        ->sortByDesc(function ($row) {

                            $date = $this->normalizeDate(
                                $row->report_date
                            );

                            return $date
                                ? $date->timestamp
                                : 0;
                        })
                        ->last();

                    $employees = $latestRow
                        ? (int) $latestRow->total_karyawan
                        : 0;

                    return [

                        'key' =>
                            $key,

                        'label' =>
                            $yearly
                                ? $key
                                : $this->monthLabel($key),

                        'employees' =>
                            $employees,

                        'income' =>
                            $income,

                        'net' =>
                            $net,
                    ];
                }
            )

            ->sortByDesc('key')
            ->values();
    }


    /**
     * Membuat key unik karyawan.
     */
    private function employeeKey($row): string
    {
        $employeeNo = strtoupper(
            trim(
                (string) $row->employee_no
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Hilangkan spasi
        |--------------------------------------------------------------------------
        */

        $employeeNo = preg_replace(
            '/\s+/',
            '',
            $employeeNo
        );

        /*
        |--------------------------------------------------------------------------
        | Hilangkan .0 dari Excel
        |--------------------------------------------------------------------------
        */

        $employeeNo = preg_replace(
            '/\.0+$/',
            '',
            $employeeNo
        );

        /*
        |--------------------------------------------------------------------------
        | Jika employee number tersedia
        |--------------------------------------------------------------------------
        */

        if ($employeeNo !== '') {

            return 'EMP:' . $employeeNo;
        }

        /*
        |--------------------------------------------------------------------------
        | Jika employee number kosong
        |--------------------------------------------------------------------------
        */

        return 'NAME:' . strtoupper(
            trim(
                (string) $row->name
            )
        );
    }


    /**
     * Nama bulan Indonesia.
     */
    private function monthLabel(
        string $key
    ): string {

        $parts = explode(
            '-',
            $key
        );

        $year = isset($parts[0])
            ? (int) $parts[0]
            : 0;

        $month = isset($parts[1])
            ? (int) $parts[1]
            : 0;

        $months = [

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

        return
            ($months[$month] ?? 'Bulan')
            . ' '
            . $year;
    }


    /**
     * Normalisasi tanggal report_date.
     *
     * Bisa menerima:
     * - Carbon
     * - DateTime
     * - string tanggal
     */
    private function normalizeDate($date)
    {
        if (!$date) {
            return null;
        }

        if ($date instanceof \Carbon\Carbon) {
            return $date;
        }

        if ($date instanceof \DateTimeInterface) {
            return \Carbon\Carbon::instance($date);
        }

        try {
            return \Carbon\Carbon::parse($date);
        } catch (\Throwable $e) {
            return null;
        }
    }
}