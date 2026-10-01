<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard Payroll | Infomedia Analysis</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f4f7fb;
            color: #1f2937;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar {
            width: 100%;
            background: #123b70;
            color: white;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: bold;
        }

        .navbar-menu {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            padding: 9px 13px;
            border-radius: 6px;
            font-size: 14px;
        }

        .navbar-menu a:hover {
            background: rgba(255,255,255,0.12);
        }

        /* =========================
           CONTAINER
        ========================= */

        .container {
            width: 94%;
            max-width: 1500px;
            margin: 0 auto;
            padding: 25px 0 50px;
        }

        /* =========================
           HEADER
        ========================= */

        .page-header {
            margin-bottom: 20px;
        }

        .page-header h1 {
            margin: 0 0 6px;
            color: #123b70;
            font-size: 28px;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        /* =========================
           FILTER
        ========================= */

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        .filter-title {
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: bold;
            color: #123b70;
        }

        .filter-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 15px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 7px;
            color: #374151;
        }

        .form-control {
            width: 100%;
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            padding: 0 12px;
            background: white;
            color: #374151;
            font-size: 14px;
            outline: none;
        }

        .form-control:focus {
            border-color: #174a8b;
            box-shadow: 0 0 0 3px rgba(23,74,139,0.1);
        }

        .btn {
            height: 42px;
            padding: 0 18px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-primary {
            background: #174a8b;
            color: white;
        }

        .btn-primary:hover {
            background: #123b70;
        }

        .btn-success {
            background: #15803d;
            color: white;
        }

        .btn-warning {
            background: #ea580c;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        /* =========================
           SNAPSHOT INFO
        ========================= */

        .snapshot-info {
            background: linear-gradient(135deg, #123b70, #174a8b);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .snapshot-info h2 {
            margin: 0 0 10px;
            font-size: 20px;
        }

        .snapshot-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .snapshot-item {
            background: rgba(255,255,255,0.10);
            padding: 12px;
            border-radius: 8px;
        }

        .snapshot-item small {
            display: block;
            opacity: .8;
            margin-bottom: 4px;
        }

        .snapshot-item strong {
            font-size: 15px;
        }

        /* =========================
           KPI
        ========================= */

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border-left: 5px solid #174a8b;
        }

        .kpi-card.green {
            border-left-color: #15803d;
        }

        .kpi-card.orange {
            border-left-color: #ea580c;
        }

        .kpi-card.red {
            border-left-color: #dc2626;
        }

        .kpi-title {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .kpi-value {
            font-size: 25px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
        }

        .kpi-subtitle {
            font-size: 12px;
            color: #6b7280;
        }

        .text-green {
            color: #15803d;
        }

        .text-red {
            color: #dc2626;
        }

        .text-orange {
            color: #ea580c;
        }

        /* =========================
           SECTION
        ========================= */

        .section-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .card-header {
            padding: 17px 20px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: bold;
            color: #123b70;
        }

        .card-body {
            padding: 20px;
        }

        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f6fa;
            color: #374151;
            font-size: 12px;
            text-align: left;
            padding: 11px;
            white-space: nowrap;
        }

        td {
            padding: 11px;
            border-bottom: 1px solid #edf0f3;
            font-size: 13px;
            color: #374151;
        }

        tr:hover td {
            background: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        /* =========================
           PROGRESS
        ========================= */

        .progress {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-bar {
            height: 100%;
            background: #174a8b;
            border-radius: 20px;
        }

        /* =========================
           BADGE
        ========================= */

        .badge {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        /* =========================
           SUMMARY TABLE
        ========================= */

        .full-card {
            margin-bottom: 20px;
        }

        /* =========================
           EMPTY
        ========================= */

        .empty-state {
            background: white;
            border-radius: 12px;
            padding: 50px 20px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        .empty-state h2 {
            color: #123b70;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 20px;
        }

        /* =========================
           ACTION
        ========================= */

        .action-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }

        .action-card h3 {
            margin-top: 0;
            color: #123b70;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer {
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            padding: 25px 0 0;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {

            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .snapshot-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-form {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media (max-width: 800px) {

            .section-grid {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .navbar-menu {
                width: 100%;
            }

        }

        @media (max-width: 600px) {

            .container {
                width: 92%;
            }

            .filter-form {
                grid-template-columns: 1fr;
            }

            .kpi-grid {
                grid-template-columns: 1fr;
            }

            .snapshot-grid {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 23px;
            }

            .navbar-menu {
                flex-direction: column;
            }

            .navbar-menu a {
                width: 100%;
            }

        }
    </style>
</head>

<body>

    {{-- =====================================================
         NAVBAR
    ====================================================== --}}

    <nav class="navbar">

        <div class="navbar-brand">
            Infomedia Payroll Analysis
        </div>

        <div class="navbar-menu">

            <a href="{{ route('payroll.dashboard') }}">
                Dashboard
            </a>

            @if(Route::has('payroll.import'))
                <a href="{{ route('payroll.import') }}">
                    Import Payroll
                </a>
            @endif

        </div>

    </nav>


    <div class="container">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="page-header">

            <h1>
                Dashboard Payroll
            </h1>

            <p>
                Analisis dan monitoring data payroll berdasarkan snapshot laporan.
            </p>

        </div>


        {{-- =====================================================
             FILTER
        ====================================================== --}}

        <div class="filter-card">

            <div class="filter-title">
                Filter Data Payroll
            </div>

            <form method="GET"
                  action="{{ route('payroll.dashboard') }}"
                  class="filter-form">

                {{-- SNAPSHOT --}}
                <div class="form-group">

                    <label for="snapshot">
                        Snapshot Payroll
                    </label>

                    <select
                        name="snapshot"
                        id="snapshot"
                        class="form-control"
                    >

                        @forelse($availableSnapshots ?? [] as $snapshot)

                            <option
                                value="{{ $snapshot->id }}"
                                {{ isset($selected) && $selected && $selected->id == $snapshot->id ? 'selected' : '' }}
                            >
                                {{ $snapshot->payroll_period ?? 'Periode' }}

                                @if(!empty($snapshot->report_date))
                                    -
                                    {{ \Carbon\Carbon::parse($snapshot->report_date)->format('d/m/Y') }}
                                @endif
                            </option>

                        @empty

                            <option value="">
                                Belum ada snapshot
                            </option>

                        @endforelse

                    </select>

                </div>


                {{-- PAYDAY --}}
                <div class="form-group">

                    <label for="payday">
                        Payday
                    </label>

                    <select
                        name="payday"
                        id="payday"
                        class="form-control"
                    >

                        <option
                            value="all"
                            {{ ($payday ?? 'all') == 'all' ? 'selected' : '' }}
                        >
                            Semua Payday
                        </option>

                        <option
                            value="05"
                            {{ ($payday ?? 'all') == '05' ? 'selected' : '' }}
                        >
                            Payday 05
                        </option>

                        <option
                            value="15"
                            {{ ($payday ?? 'all') == '15' ? 'selected' : '' }}
                        >
                            Payday 15
                        </option>

                        <option
                            value="25"
                            {{ ($payday ?? 'all') == '25' ? 'selected' : '' }}
                        >
                            Payday 25
                        </option>

                        <option
                            value="28"
                            {{ ($payday ?? 'all') == '28' ? 'selected' : '' }}
                        >
                            Payday 28
                        </option>

                    </select>

                </div>


                {{-- PERIODE --}}
                <div class="form-group">

                    <label>
                        Periode
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        readonly
                        value="{{ isset($selected) && $selected
                            ? ($selected->periode_bulan ?? '-') . ' / ' . ($selected->periode_tahun ?? '-')
                            : '-' }}"
                    >

                </div>


                {{-- BUTTON --}}
                <div class="form-group">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Tampilkan
                    </button>

                </div>

            </form>

        </div>


        {{-- =====================================================
             JIKA BELUM ADA DATA
        ====================================================== --}}

        @if(!isset($selected) || !$selected)

            <div class="empty-state">

                <h2>
                    Belum Ada Data Payroll
                </h2>

                <p>
                    Silakan import data payroll terlebih dahulu
                    agar dashboard dapat menampilkan hasil analisis.
                </p>

                @if(Route::has('payroll.import'))

                    <a
                        href="{{ route('payroll.import') }}"
                        class="btn btn-primary"
                    >
                        Import Data Payroll
                    </a>

                @endif

            </div>

        @else


            {{-- =====================================================
                 SNAPSHOT INFORMATION
            ====================================================== --}}

            <div class="snapshot-info">

                <h2>
                    {{ $selected->payroll_period ?? 'Payroll' }}
                </h2>

                <div class="snapshot-grid">

                    <div class="snapshot-item">

                        <small>
                            Report Date
                        </small>

                        <strong>

                            @if(!empty($selected->report_date))

                                {{ \Carbon\Carbon::parse($selected->report_date)->format('d F Y') }}

                            @else

                                -

                            @endif

                        </strong>

                    </div>


                    <div class="snapshot-item">

                        <small>
                            Periode
                        </small>

                        <strong>
                            {{ $selected->periode_bulan ?? '-' }}
                            /
                            {{ $selected->periode_tahun ?? '-' }}
                        </strong>

                    </div>


                    <div class="snapshot-item">

                        <small>
                            Jumlah Karyawan
                        </small>

                        <strong>
                            {{ number_format($summary['employees'] ?? 0, 0, ',', '.') }}
                        </strong>

                    </div>


                    <div class="snapshot-item">

                        <small>
                            Snapshot ID
                        </small>

                        <strong>
                            #{{ $selected->id }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 KPI
            ====================================================== --}}

            <div class="kpi-grid">

                {{-- TOTAL PAYROLL --}}
                <div class="kpi-card">

                    <div class="kpi-title">
                        Total Payroll
                    </div>

                    <div class="kpi-value">
                        Rp {{ number_format($summary['totalPayroll'] ?? 0, 0, ',', '.') }}
                    </div>

                    <div class="kpi-subtitle">
                        Total income payroll
                    </div>

                </div>


                {{-- NET SALARY --}}
                <div class="kpi-card green">

                    <div class="kpi-title">
                        Total Net Salary
                    </div>

                    <div class="kpi-value">
                        Rp {{ number_format($summary['netSalary'] ?? 0, 0, ',', '.') }}
                    </div>

                    <div class="kpi-subtitle">
                        {{ number_format($summary['ratio'] ?? 0, 2, ',', '.') }}%
                        dari total payroll
                    </div>

                </div>


                {{-- PERUBAHAN --}}
                <div class="kpi-card
                    {{ ($summary['change'] ?? 0) >= 0 ? 'green' : 'red' }}">

                    <div class="kpi-title">
                        Perubahan Payroll
                    </div>

                    <div class="kpi-value">

                        @if(($summary['change'] ?? 0) >= 0)

                            +

                        @endif

                        Rp {{ number_format(abs($summary['change'] ?? 0), 0, ',', '.') }}

                    </div>

                    <div class="kpi-subtitle">

                        @if(isset($summary['changePercent']) && $summary['changePercent'] !== null)

                            @if($summary['changePercent'] >= 0)
                                Naik
                            @else
                                Turun
                            @endif

                            {{ number_format(abs($summary['changePercent']), 2, ',', '.') }}%

                            dibanding periode sebelumnya.

                        @else

                            Tidak ada data periode sebelumnya.

                        @endif

                    </div>

                </div>


                {{-- EMPLOYEE CHANGE --}}
                <div class="kpi-card orange">

                    <div class="kpi-title">
                        Perubahan Karyawan
                    </div>

                    <div class="kpi-value">
                        {{ number_format($summary['flagged'] ?? 0, 0, ',', '.') }}
                    </div>

                    <div class="kpi-subtitle">

                        <span class="text-green">
                            ↑ {{ number_format($summary['increase'] ?? 0, 0, ',', '.') }}
                        </span>

                        &nbsp;|&nbsp;

                        <span class="text-red">
                            ↓ {{ number_format($summary['decrease'] ?? 0, 0, ',', '.') }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 DEPARTMENT + TOP CHANGES
            ====================================================== --}}

            <div class="section-grid">

                {{-- DEPARTMENT --}}
                <div class="card">

                    <div class="card-header">
                        Distribusi Payroll per Department
                    </div>

                    <div class="card-body">

                        <div class="table-wrapper">

                            <table>

                                <thead>

                                    <tr>
                                        <th>Department</th>
                                        <th>Karyawan</th>
                                        <th>Total Income</th>
                                        <th>Kontribusi</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($departments ?? [] as $department)

                                        <tr>

                                            <td>
                                                {{ $department['department']
                                                    ?? $department->department
                                                    ?? '-' }}
                                            </td>

                                            <td>
                                                {{ number_format(
                                                    $department['employees']
                                                    ?? $department->employees
                                                    ?? 0,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </td>

                                            <td>
                                                Rp
                                                {{ number_format(
                                                    $department['total_income']
                                                    ?? $department->total_income
                                                    ?? 0,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) }}
                                            </td>

                                            <td style="min-width: 120px;">

                                                <strong>

                                                    {{ number_format(
                                                        $department['ratio']
                                                        ?? $department->ratio
                                                        ?? 0,
                                                        2,
                                                        ',',
                                                        '.'
                                                    ) }}%

                                                </strong>

                                                <div class="progress">

                                                    <div
                                                        class="progress-bar"
                                                        style="width:
                                                            {{ min(
                                                                100,
                                                                max(
                                                                    0,
                                                                    (float)($department['ratio']
                                                                    ?? $department->ratio
                                                                    ?? 0)
                                                                )
                                                            ) }}%;"
                                                    ></div>

                                                </div>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="4"
                                                style="text-align:center;"
                                            >
                                                Tidak ada data department.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>


                {{-- TOP CHANGES --}}
                <div class="card">

                    <div class="card-header">
                        Top 10 Perubahan Payroll
                    </div>

                    <div class="card-body">

                        <div class="table-wrapper">

                            <table>

                                <thead>

                                    <tr>
                                        <th>Karyawan</th>
                                        <th>NIK</th>
                                        <th>Perubahan</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($topChanges ?? [] as $change)

                                        @php

                                            $difference =
                                                $change['difference']
                                                ?? $change->difference
                                                ?? 0;

                                            $percentage =
                                                $change['percentage']
                                                ?? $change->percentage
                                                ?? 0;

                                            $status =
                                                $change['status']
                                                ?? $change->status
                                                ?? '';

                                        @endphp

                                        <tr>

                                            <td>
                                                {{ $change['name']
                                                    ?? $change->name
                                                    ?? '-' }}
                                            </td>

                                            <td>
                                                {{ $change['employee_no']
                                                    ?? $change->employee_no
                                                    ?? '-' }}
                                            </td>

                                            <td>

                                                @if($difference > 0)

                                                    <span class="text-green">
                                                        +Rp
                                                        {{ number_format(
                                                            abs($difference),
                                                            0,
                                                            ',',
                                                            '.'
                                                        ) }}
                                                    </span>

                                                @elseif($difference < 0)

                                                    <span class="text-red">
                                                        -Rp
                                                        {{ number_format(
                                                            abs($difference),
                                                            0,
                                                            ',',
                                                            '.'
                                                        ) }}
                                                    </span>

                                                @else

                                                    Rp 0

                                                @endif


                                                <br>

                                                <small>

                                                    {{ number_format(
                                                        abs($percentage),
                                                        2,
                                                        ',',
                                                        '.'
                                                    ) }}%

                                                </small>

                                            </td>

                                            <td>

                                                @if($status === 'increase' || $difference > 0)

                                                    <span class="badge badge-success">
                                                        Naik
                                                    </span>

                                                @elseif($status === 'decrease' || $difference < 0)

                                                    <span class="badge badge-danger">
                                                        Turun
                                                    </span>

                                                @else

                                                    <span class="badge badge-secondary">
                                                        Tetap
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td
                                                colspan="4"
                                                style="text-align:center;"
                                            >
                                                Tidak ada perubahan payroll.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 MONTHLY SUMMARY
            ====================================================== --}}

            <div class="card full-card">

                <div class="card-header">
                    Ringkasan Payroll Bulanan
                </div>

                <div class="card-body">

                    <div class="table-wrapper">

                        <table>

                            <thead>

                                <tr>
                                    <th>Periode</th>
                                    <th>Karyawan</th>
                                    <th>Total Income</th>
                                    <th>Net Salary</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($monthly ?? [] as $row)

                                    <tr>

                                        <td>
                                            {{ $row['label']
                                                ?? $row->label
                                                ?? '-' }}
                                        </td>

                                        <td>
                                            {{ number_format(
                                                $row['employees']
                                                ?? $row->employees
                                                ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                        <td>
                                            Rp
                                            {{ number_format(
                                                $row['income']
                                                ?? $row->income
                                                ?? $row['total_income']
                                                ?? $row->total_income
                                                ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                        <td>
                                            Rp
                                            {{ number_format(
                                                $row['net']
                                                ?? $row->net
                                                ?? $row['net_salary']
                                                ?? $row->net_salary
                                                ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            style="text-align:center;"
                                        >
                                            Belum ada ringkasan bulanan.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 YEARLY SUMMARY
            ====================================================== --}}

            <div class="card full-card">

                <div class="card-header">
                    Ringkasan Payroll Tahunan
                </div>

                <div class="card-body">

                    <div class="table-wrapper">

                        <table>

                            <thead>

                                <tr>
                                    <th>Tahun</th>
                                    <th>Karyawan</th>
                                    <th>Total Income</th>
                                    <th>Net Salary</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($yearly ?? [] as $row)

                                    <tr>

                                        <td>
                                            {{ $row['label']
                                                ?? $row->label
                                                ?? $row['year']
                                                ?? $row->year
                                                ?? '-' }}
                                        </td>

                                        <td>
                                            {{ number_format(
                                                $row['employees']
                                                ?? $row->employees
                                                ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                        <td>
                                            Rp
                                            {{ number_format(
                                                $row['income']
                                                ?? $row->income
                                                ?? $row['total_income']
                                                ?? $row->total_income
                                                ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                        <td>
                                            Rp
                                            {{ number_format(
                                                $row['net']
                                                ?? $row->net
                                                ?? $row['net_salary']
                                                ?? $row->net_salary
                                                ?? 0,
                                                0,
                                                ',',
                                                '.'
                                            ) }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="4"
                                            style="text-align:center;"
                                        >
                                            Belum ada ringkasan tahunan.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 ACTION
            ====================================================== --}}

            <div class="action-card">

                <h3>
                    Menu Analisis
                </h3>

                <div class="action-buttons">

                    @if(Route::has('payroll.validation'))

                        <a
                            href="{{ route('payroll.validation', ['snapshot' => $selected->id]) }}"
                            class="btn btn-primary"
                        >
                            Validasi Payroll
                        </a>

                    @endif


                    @if(
                        Route::has('payroll.change-validation')
                        && isset($previous)
                        && $previous
                    )

                        <a
                            href="{{ route(
                                'payroll.change-validation',
                                [
                                    'current' => $selected->id,
                                    'previous' => $previous->id
                                ]
                            ) }}"
                            class="btn btn-warning"
                        >
                            Validasi Perubahan
                        </a>

                    @endif


                    @if(Route::has('payroll.analysis'))

                        <a
                            href="{{ route(
                                'payroll.analysis',
                                [
                                    'current' =>
                                        ($selected->periode_tahun ?? '')
                                        . '-'
                                        . str_pad(
                                            $selected->periode_bulan ?? 1,
                                            2,
                                            '0',
                                            STR_PAD_LEFT
                                        )
                                ]
                            ) }}"
                            class="btn btn-success"
                        >
                            Analisis Detail
                        </a>

                    @endif


                    @if(Route::has('payroll.import'))

                        <a
                            href="{{ route('payroll.import') }}"
                            class="btn btn-secondary"
                        >
                            Import Payroll
                        </a>

                    @endif

                </div>

            </div>

        @endif


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="footer">

            Payroll Validation & Analysis System
            &copy; {{ date('Y') }}

        </div>

    </div>

</body>
</html>