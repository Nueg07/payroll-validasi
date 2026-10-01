```blade
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Perbandingan Payroll
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family:
                Arial,
                Helvetica,
                sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .container {
            width: 94%;
            max-width: 1450px;
            margin: 30px auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 30px;
            color: #123b70;
        }

        .header p {
            margin: 0;
            color: #64748b;
            line-height: 1.6;
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 22px;
            box-shadow:
                0 5px 20px
                rgba(
                    15,
                    23,
                    42,
                    0.06
                );
        }

        .section-title {
            font-size: 19px;
            font-weight: 700;
            color: #123b70;
            margin-bottom: 18px;
        }

        /* =========================
           FILTER
        ========================= */

        .filter-grid {
            display: grid;
            grid-template-columns:
                1fr
                1fr
                1fr
                auto;
            gap: 18px;
            align-items: end;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }

        select {
            width: 100%;
            padding: 12px;
            border:
                1px solid #d7dee8;
            border-radius: 8px;
            background: white;
            font-size: 14px;
            color: #1f2937;
        }

        select:focus {
            outline: none;
            border-color: #174a8b;
            box-shadow:
                0 0 0 3px
                rgba(
                    23,
                    74,
                    139,
                    0.08
                );
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 12px 22px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-block;
        }

        .btn-primary {
            background: #174a8b;
            color: white;
        }

        .btn-primary:hover {
            background: #123b70;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        /* =========================
           EXPORT BUTTON
        ========================= */

        .export-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .export-btn {
            border: none;
            border-radius: 8px;
            padding: 11px 18px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: 0.2s ease;
        }

        .export-btn:hover {
            transform: translateY(-1px);
        }

        .export-excel {
            background: #198754;
            color: white;
        }

        .export-excel:hover {
            background: #157347;
        }

        .export-pdf {
            background: #dc3545;
            color: white;
        }

        .export-pdf:hover {
            background: #bb2d3b;
        }

        /* =========================
           INFO FILTER
        ========================= */

        .filter-info {
            margin-top: 18px;
            padding: 14px 16px;
            background: #eff6ff;
            border:
                1px solid #bfdbfe;
            border-radius: 10px;
            color: #1e40af;
            font-size: 13px;
            line-height: 1.6;
        }

        .filter-info strong {
            color: #123b70;
        }

        /* =========================
           PERIOD COMPARISON
        ========================= */

        .period-comparison {
            display: grid;
            grid-template-columns:
                1fr
                80px
                1fr;
            align-items: center;
            gap: 20px;
        }

        .period-box {
            padding: 22px;
            background: #f8fafc;
            border:
                1px solid #e2e8f0;
            border-radius: 12px;
        }

        .period-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 7px;
            font-weight: 600;
        }

        .period-name {
            font-size: 22px;
            font-weight: 700;
            color: #123b70;
        }

        .period-date {
            margin-top: 7px;
            color: #64748b;
            font-size: 13px;
        }

        .period-files {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .file-badge {
            display: inline-block;
            padding: 5px 9px;
            background: #e0ecff;
            color: #174a8b;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }

        .arrow {
            width: 55px;
            height: 55px;
            margin: auto;
            border-radius: 50%;
            background: #174a8b;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        /* =========================
           KPI
        ========================= */

        .kpi-grid {
            display: grid;
            grid-template-columns:
                repeat(4, 1fr);
            gap: 18px;
        }

        .kpi {
            background: white;
            border-radius: 14px;
            padding: 22px;
            box-shadow:
                0 5px 20px
                rgba(
                    15,
                    23,
                    42,
                    0.06
                );
        }

        .kpi-title {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 9px;
        }

        .kpi-value {
            font-size: 25px;
            font-weight: 700;
            color: #123b70;
            word-break: break-word;
        }

        .kpi-description {
            margin-top: 8px;
            color: #64748b;
            font-size: 12px;
            line-height: 1.5;
        }

        .green {
            color: #16a34a !important;
        }

        .red {
            color: #dc2626 !important;
        }

        .blue {
            color: #2563eb !important;
        }

        .orange {
            color: #ea580c !important;
        }

        /* =========================
           STATUS
        ========================= */

        .status-grid {
            display: grid;
            grid-template-columns:
                repeat(5, 1fr);
            gap: 12px;
        }

        .status-box {
            padding: 18px;
            border-radius: 10px;
            background: #f8fafc;
            border:
                1px solid #e2e8f0;
        }

        .status-number {
            font-size: 24px;
            font-weight: 700;
        }

        .status-name {
            font-size: 13px;
            color: #64748b;
            margin-top: 5px;
        }

        /* =========================
           FILE LIST
        ========================= */

        .file-grid {
            display: grid;
            grid-template-columns:
                repeat(2, 1fr);
            gap: 16px;
        }

        .file-period {
            padding: 17px;
            border:
                1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
        }

        .file-period-title {
            font-weight: 700;
            color: #123b70;
            margin-bottom: 10px;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom:
                1px solid #e5e7eb;
            font-size: 13px;
        }

        .file-item:last-child {
            border-bottom: none;
        }

        .file-name {
            font-weight: 600;
            color: #334155;
        }

        .file-date {
            color: #64748b;
            font-size: 12px;
        }

        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1050px;
        }

        th {
            text-align: left;
            background: #f1f5f9;
            color: #475569;
            padding: 13px;
            font-size: 12px;
            white-space: nowrap;
        }

        td {
            padding: 13px;
            border-bottom:
                1px solid #e5e7eb;
            font-size: 13px;
            white-space: nowrap;
        }

        tr:hover td {
            background: #fafcff;
        }

        .amount {
            text-align: right;
        }

        /* =========================
           BADGE
        ========================= */

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-increase {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-decrease {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-new {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-missing {
            background: #ffedd5;
            color: #c2410c;
        }

        .badge-same {
            background: #f1f5f9;
            color: #475569;
        }

        /* =========================
           ALERT
        ========================= */

        .alert {
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .alert-warning {
            background: #fff7ed;
            border:
                1px solid #fed7aa;
            color: #9a3412;
        }

        .alert-success {
            background: #ecfdf5;
            border:
                1px solid #bbf7d0;
            color: #166534;
        }

        /* =========================
           EMPTY
        ========================= */

        .empty {
            padding: 45px;
            text-align: center;
            color: #64748b;
        }

        .empty h2 {
            color: #123b70;
            margin-bottom: 10px;
        }

        /* =========================
           DIFFERENCE
        ========================= */

        .difference-positive {
            color: #15803d;
            font-weight: 700;
        }

        .difference-negative {
            color: #b91c1c;
            font-weight: 700;
        }

        .difference-zero {
            color: #64748b;
        }

        /* =========================
           SMALL TEXT
        ========================= */

        .muted {
            color: #64748b;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {

            .filter-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .filter-grid > div:last-child {
                grid-column: span 2;
            }

            .kpi-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .status-grid {
                grid-template-columns:
                    repeat(3, 1fr);
            }

            .file-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 800px) {

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .filter-grid > div:last-child {
                grid-column: auto;
            }

            .kpi-grid {
                grid-template-columns: 1fr;
            }

            .status-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .period-comparison {
                grid-template-columns: 1fr;
            }

            .arrow {
                transform: rotate(90deg);
            }

            .export-bar {
                justify-content: stretch;
            }

            .export-btn {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 500px) {

            .container {
                width: 96%;
                margin:
                    15px auto;
            }

            .card {
                padding: 17px;
            }

            .header h1 {
                font-size: 24px;
            }

            .status-grid {
                grid-template-columns: 1fr;
            }

            .period-name {
                font-size: 19px;
            }

            .export-bar {
                flex-direction: column;
            }

            .export-btn {
                width: 100%;
            }
        }

        /* =========================
           PRINT / PDF
        ========================= */

        @media print {

            body {
                background: white;
            }

            .container {
                width: 100%;
                max-width: none;
                margin: 0;
            }

            .export-bar,
            .filter-grid,
            .filter-info {
                display: none !important;
            }

            .card,
            .kpi {
                box-shadow: none;
                break-inside: avoid;
            }

            .header {
                margin-bottom: 15px;
            }

            table {
                min-width: 0;
            }

            th,
            td {
                font-size: 9px;
                padding: 6px;
            }

            .period-comparison {
                gap: 10px;
            }

            .kpi-grid {
                gap: 8px;
            }

            .kpi {
                padding: 10px;
            }

            .kpi-value {
                font-size: 16px;
            }

            @page {
                size: landscape;
                margin: 10mm;
            }
        }

    </style>
</head>


<body>

<div class="container">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="header">

        <h1>
            Proses & Perbandingan Payroll
        </h1>

        <p>
            Bandingkan payroll berdasarkan periode
            <strong>bulanan</strong> atau
            <strong>tahunan</strong>.
            File MTH05, MTH15, MTH25, dan seterusnya
            diperlakukan sebagai periode/tanggal gajian
            di dalam bulan tersebut.
        </p>

    </div>


    {{-- =========================================================
         FILTER
    ========================================================== --}}

    <div class="card">

        <div class="section-title">
            Pilih Periode Perbandingan
        </div>

        <form
            method="GET"
            action="{{ route('payroll.analysis') }}"
        >

            <div class="filter-grid">

                {{-- MODE --}}

                <div class="form-group">

                    <label>
                        Jenis Perbandingan
                    </label>

                    <select
                        name="mode"
                        onchange="this.form.submit()"
                    >

                        <option
                            value="monthly"
                            {{ ($mode ?? 'monthly') === 'monthly' ? 'selected' : '' }}
                        >
                            Bulanan
                        </option>

                        <option
                            value="yearly"
                            {{ ($mode ?? 'monthly') === 'yearly' ? 'selected' : '' }}
                        >
                            Tahunan
                        </option>

                    </select>

                </div>


                {{-- CURRENT --}}

                <div class="form-group">

                    <label>
                        Periode Sekarang
                    </label>

                    <select
                        name="current"
                        required
                    >

                        @if (($mode ?? 'monthly') === 'monthly')

                            @foreach ($monthlyGroups ?? [] as $group)

                                <option
                                    value="{{ $group['key'] }}"
                                    {{ ($currentGroup['key'] ?? '') === $group['key'] ? 'selected' : '' }}
                                >
                                    {{ $group['label'] }}
                                    -
                                    {{ $group['file_count'] }}
                                    file
                                </option>

                            @endforeach

                        @else

                            @foreach ($yearlyGroups ?? [] as $group)

                                <option
                                    value="{{ $group['key'] }}"
                                    {{ ($currentGroup['key'] ?? '') === $group['key'] ? 'selected' : '' }}
                                >
                                    {{ $group['label'] }}
                                    -
                                    {{ $group['file_count'] }}
                                    file
                                </option>

                            @endforeach

                        @endif

                    </select>

                </div>


                {{-- PREVIOUS --}}

                <div class="form-group">

                    <label>
                        Bandingkan Dengan
                    </label>

                    <select
                        name="previous"
                    >

                        <option value="">
                            Otomatis pilih periode sebelumnya
                        </option>

                        @if (($mode ?? 'monthly') === 'monthly')

                            @foreach ($monthlyGroups ?? [] as $group)

                                <option
                                    value="{{ $group['key'] }}"
                                    {{ ($previousGroup['key'] ?? '') === $group['key'] ? 'selected' : '' }}
                                >
                                    {{ $group['label'] }}
                                    -
                                    {{ $group['file_count'] }}
                                    file
                                </option>

                            @endforeach

                        @else

                            @foreach ($yearlyGroups ?? [] as $group)

                                <option
                                    value="{{ $group['key'] }}"
                                    {{ ($previousGroup['key'] ?? '') === $group['key'] ? 'selected' : '' }}
                                >
                                    {{ $group['label'] }}
                                    -
                                    {{ $group['file_count'] }}
                                    file
                                </option>

                            @endforeach

                        @endif

                    </select>

                </div>


                {{-- PAYDAY --}}

                <div class="form-group">

                    <label>
                        Periode Gajian
                    </label>

                    <select
                        name="payday"
                    >

                        <option
                            value="all"
                            {{ ($payday ?? 'all') === 'all' ? 'selected' : '' }}
                        >
                            Semua MTH
                        </option>

                        <option
                            value="05"
                            {{ ($payday ?? '') === '05' ? 'selected' : '' }}
                        >
                            MTH05
                        </option>

                        <option
                            value="15"
                            {{ ($payday ?? '') === '15' ? 'selected' : '' }}
                        >
                            MTH15
                        </option>

                        <option
                            value="25"
                            {{ ($payday ?? '') === '25' ? 'selected' : '' }}
                        >
                            MTH25
                        </option>

                    </select>

                </div>


                {{-- BUTTON --}}

                <div>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Proses Perbandingan
                    </button>

                </div>

            </div>


            <div class="filter-info">

                <strong>Catatan:</strong>

                Jika memilih
                <strong>Semua MTH</strong>,
                sistem akan menggabungkan seluruh file
                MTH05, MTH15, MTH25, dan lainnya
                yang tersedia dalam periode tersebut.

                Jika memilih MTH tertentu,
                perbandingan hanya menggunakan
                payday tersebut.

            </div>

        </form>

    </div>


    {{-- =========================================================
         JIKA BELUM ADA PEMBANDING
    ========================================================== --}}

    @if (!$previousGroup)

        <div class="card">

            <div class="empty">

                <h2>
                    Belum ada periode pembanding
                </h2>

                <p>
                    Database belum memiliki minimal
                    dua periode payroll yang dapat
                    dibandingkan.
                </p>

                <p>
                    Upload Monthly Report periode
                    berikutnya untuk mulai melakukan
                    analisis perbandingan.
                </p>

                <a
                    href="{{ route('payroll.import') }}"
                    class="btn btn-primary"
                >
                    Upload Payroll Berikutnya
                </a>

            </div>

        </div>

    @else


        {{-- =====================================================
             DOWNLOAD HASIL
        ====================================================== --}}

        <div class="export-bar">

            {{-- EXPORT EXCEL --}}

            <a
                href="{{ route('payroll.analysis.export.excel', [
                    'mode' => $mode ?? 'monthly',
                    'current' => $currentGroup['key'] ?? '',
                    'previous' => $previousGroup['key'] ?? '',
                    'payday' => $payday ?? 'all',
                ]) }}"
                class="export-btn export-excel"
            >
                📊 Download Excel
            </a>


            {{-- EXPORT PDF --}}

            <a
                href="{{ route('payroll.analysis.export.pdf', [
                    'mode' => $mode ?? 'monthly',
                    'current' => $currentGroup['key'] ?? '',
                    'previous' => $previousGroup['key'] ?? '',
                    'payday' => $payday ?? 'all',
                ]) }}"
                class="export-btn export-pdf"
            >
                📄 Download PDF
            </a>

        </div>


        {{-- =====================================================
             PERIODE
        ====================================================== --}}

        <div class="card">

            <div class="period-comparison">

                {{-- PREVIOUS --}}

                <div class="period-box">

                    <div class="period-label">
                        PERIODE SEBELUMNYA
                    </div>

                    <div class="period-name">
                        {{ $previousGroup['label'] ?? '-' }}
                    </div>

                    <div class="period-date">

                        @if (($mode ?? 'monthly') === 'monthly')

                            Perbandingan bulan sebelumnya

                        @else

                            Perbandingan tahun sebelumnya

                        @endif

                    </div>


                    @if (!empty($previousGroup['files']))

                        <div class="period-files">

                            @foreach ($previousGroup['files'] as $file)

                                <span class="file-badge">
                                    {{ $file['payroll_period'] }}
                                </span>

                            @endforeach

                        </div>

                    @endif

                </div>


                {{-- ARROW --}}

                <div class="arrow">
                    →
                </div>


                {{-- CURRENT --}}

                <div class="period-box">

                    <div class="period-label">
                        PERIODE SEKARANG
                    </div>

                    <div class="period-name">
                        {{ $currentGroup['label'] ?? '-' }}
                    </div>

                    <div class="period-date">

                        @if (($mode ?? 'monthly') === 'monthly')

                            Periode bulanan

                        @else

                            Periode tahunan

                        @endif

                    </div>


                    @if (!empty($currentGroup['files']))

                        <div class="period-files">

                            @foreach ($currentGroup['files'] as $file)

                                <span class="file-badge">
                                    {{ $file['payroll_period'] }}
                                </span>

                            @endforeach

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
             FILE YANG DIGABUNGKAN
        ====================================================== --}}

        <div class="card">

            <div class="section-title">
                File Payroll yang Digabungkan
            </div>

            <div class="file-grid">


                {{-- PREVIOUS FILES --}}

                <div class="file-period">

                    <div class="file-period-title">
                        {{ $previousGroup['label'] ?? 'Periode Sebelumnya' }}
                    </div>


                    @forelse (
                        $previousGroup['files'] ?? []
                        as $file
                    )

                        <div class="file-item">

                            <div>

                                <div class="file-name">
                                    {{ $file['payroll_period'] }}
                                </div>

                                <div class="file-date">
                                    {{ $file['report_date'] ?? '-' }}
                                </div>

                            </div>

                            <div class="muted">

                                {{ number_format($file['total_karyawan'] ?? 0) }}

                                karyawan

                            </div>

                        </div>

                    @empty

                        <div class="muted">
                            Tidak ada file.
                        </div>

                    @endforelse

                </div>


                {{-- CURRENT FILES --}}

                <div class="file-period">

                    <div class="file-period-title">
                        {{ $currentGroup['label'] ?? 'Periode Sekarang' }}
                    </div>


                    @forelse (
                        $currentGroup['files'] ?? []
                        as $file
                    )

                        <div class="file-item">

                            <div>

                                <div class="file-name">
                                    {{ $file['payroll_period'] }}
                                </div>

                                <div class="file-date">
                                    {{ $file['report_date'] ?? '-' }}
                                </div>

                            </div>

                            <div class="muted">

                                {{ number_format($file['total_karyawan'] ?? 0) }}

                                karyawan

                            </div>

                        </div>

                    @empty

                        <div class="muted">
                            Tidak ada file.
                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =====================================================
             KPI PAYROLL
        ====================================================== --}}

        <div class="kpi-grid">

            {{-- PREVIOUS --}}

            <div class="kpi">

                <div class="kpi-title">
                    NET PAYROLL SEBELUMNYA
                </div>

                <div class="kpi-value">

                    Rp

                    {{ number_format(
                        $comparison['payroll']['previous'] ?? 0,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

                <div class="kpi-description">
                    {{ $previousGroup['label'] ?? '-' }}
                </div>

            </div>


            {{-- CURRENT --}}

            <div class="kpi">

                <div class="kpi-title">
                    NET PAYROLL SEKARANG
                </div>

                <div class="kpi-value">

                    Rp

                    {{ number_format(
                        $comparison['payroll']['current'] ?? 0,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

                <div class="kpi-description">
                    {{ $currentGroup['label'] ?? '-' }}
                </div>

            </div>


            {{-- DIFFERENCE --}}

            <div class="kpi">

                <div class="kpi-title">
                    PERUBAHAN PAYROLL
                </div>

                <div
                    class="
                        kpi-value
                        {{
                            ($comparison['payroll']['difference'] ?? 0) >= 0
                            ? 'green'
                            : 'red'
                        }}
                    "
                >

                    {{
                        ($comparison['payroll']['difference'] ?? 0) >= 0
                        ? '+'
                        : ''
                    }}

                    Rp

                    {{ number_format(
                        abs($comparison['payroll']['difference'] ?? 0),
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

                <div class="kpi-description">

                    {{
                        ($comparison['payroll']['percentage'] ?? null) !== null
                        ? (
                            ($comparison['payroll']['percentage'] > 0 ? '+' : '')
                            . $comparison['payroll']['percentage']
                            . '%'
                        )
                        : '-'
                    }}

                    dibanding periode sebelumnya

                </div>

            </div>


            {{-- EMPLOYEE --}}

            <div class="kpi">

                <div class="kpi-title">
                    JUMLAH KARYAWAN
                </div>

                <div class="kpi-value">

                    {{
                        number_format(
                            $comparison['summary']['totalEmployees'] ?? 0
                        )
                    }}

                </div>

                <div class="kpi-description">
                    Karyawan unik dari kedua periode
                </div>

            </div>

        </div>


        {{-- =====================================================
             STATUS PERUBAHAN
        ====================================================== --}}

        <div class="card">

            <div class="section-title">
                Ringkasan Perubahan
            </div>

            <div class="status-grid">


                {{-- NAIK --}}

                <div class="status-box">

                    <div class="status-number green">

                        {{
                            number_format(
                                $comparison['summary']['increase'] ?? 0
                            )
                        }}

                    </div>

                    <div class="status-name">
                        Naik
                    </div>

                </div>


                {{-- TURUN --}}

                <div class="status-box">

                    <div class="status-number red">

                        {{
                            number_format(
                                $comparison['summary']['decrease'] ?? 0
                            )
                        }}

                    </div>

                    <div class="status-name">
                        Turun
                    </div>

                </div>


                {{-- BARU --}}

                <div class="status-box">

                    <div class="status-number blue">

                        {{
                            number_format(
                                $comparison['summary']['new'] ?? 0
                            )
                        }}

                    </div>

                    <div class="status-name">
                        Komponen Baru
                    </div>

                </div>


                {{-- HILANG --}}

                <div class="status-box">

                    <div class="status-number orange">

                        {{
                            number_format(
                                $comparison['summary']['missing'] ?? 0
                            )
                        }}

                    </div>

                    <div class="status-name">
                        Komponen Hilang
                    </div>

                </div>


                {{-- SAMA --}}

                <div class="status-box">

                    <div class="status-number">

                        {{
                            number_format(
                                $comparison['summary']['same'] ?? 0
                            )
                        }}

                    </div>

                    <div class="status-name">
                        Tetap
                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             TOTAL KENAIKAN / PENURUNAN
        ====================================================== --}}

        <div class="kpi-grid">


            {{-- TOTAL KENAIKAN --}}

            <div class="kpi">

                <div class="kpi-title">
                    TOTAL KENAIKAN
                </div>

                <div class="kpi-value green">

                    Rp

                    {{ number_format(
                        $comparison['summary']['totalIncrease'] ?? 0,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

                <div class="kpi-description">
                    Akumulasi nilai kenaikan payroll
                </div>

            </div>


            {{-- TOTAL PENURUNAN --}}

            <div class="kpi">

                <div class="kpi-title">
                    TOTAL PENURUNAN
                </div>

                <div class="kpi-value red">

                    Rp

                    {{ number_format(
                        $comparison['summary']['totalDecrease'] ?? 0,
                        0,
                        ',',
                        '.'
                    ) }}

                </div>

                <div class="kpi-description">
                    Akumulasi nilai penurunan payroll
                </div>

            </div>


            {{-- KARYAWAN BARU --}}

            <div class="kpi">

                <div class="kpi-title">
                    KARYAWAN BARU
                </div>

                <div class="kpi-value blue">

                    {{
                        number_format(
                            $comparison['summary']['newEmployees'] ?? 0
                        )
                    }}

                </div>

                <div class="kpi-description">
                    Tidak ditemukan pada periode sebelumnya
                </div>

            </div>


            {{-- KARYAWAN HILANG --}}

            <div class="kpi">

                <div class="kpi-title">
                    KARYAWAN TIDAK ADA
                </div>

                <div class="kpi-value orange">

                    {{
                        number_format(
                            $comparison['summary']['missingEmployees'] ?? 0
                        )
                    }}

                </div>

                <div class="kpi-description">
                    Ada sebelumnya tetapi tidak ditemukan sekarang
                </div>

            </div>

        </div>


        {{-- =====================================================
             TOP 10
        ====================================================== --}}

        <div class="card">

            <div class="section-title">
                Top 10 Perubahan Terbesar
            </div>

            <div
                class="muted"
                style="margin-bottom: 15px;"
            >
                Daftar perubahan payroll berdasarkan
                nilai selisih terbesar.
            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Employee No
                            </th>

                            <th>
                                Nama
                            </th>

                            <th>
                                Department
                            </th>

                            <th>
                                Komponen
                            </th>

                            <th class="amount">
                                Sebelumnya
                            </th>

                            <th class="amount">
                                Sekarang
                            </th>

                            <th class="amount">
                                Selisih
                            </th>

                            <th>
                                Persentase
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse (
                            $comparison['topChanges'] ?? []
                            as $index => $row
                        )

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $row['employee_no'] }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $row['name'] }}
                                </td>

                                <td>
                                    {{ $row['department'] }}
                                </td>

                                <td>
                                    {{ $row['component'] }}
                                </td>

                                <td class="amount">

                                    Rp

                                    {{ number_format(
                                        $row['previous'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <td class="amount">

                                    Rp

                                    {{ number_format(
                                        $row['current'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <td
                                    class="
                                        amount
                                        {{
                                            $row['difference'] > 0
                                            ? 'difference-positive'
                                            : (
                                                $row['difference'] < 0
                                                ? 'difference-negative'
                                                : 'difference-zero'
                                            )
                                        }}
                                    "
                                >

                                    {{
                                        $row['difference'] > 0
                                        ? '+'
                                        : ''
                                    }}

                                    Rp

                                    {{ number_format(
                                        abs($row['difference']),
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <td>

                                    @if ($row['percentage'] !== null)

                                        {{
                                            $row['percentage'] > 0
                                            ? '+'
                                            : ''
                                        }}

                                        {{ $row['percentage'] }}%

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>

                                    @if (
                                        $row['status'] === 'increase'
                                    )

                                        <span
                                            class="
                                                badge
                                                badge-increase
                                            "
                                        >
                                            Naik
                                        </span>

                                    @elseif (
                                        $row['status'] === 'decrease'
                                    )

                                        <span
                                            class="
                                                badge
                                                badge-decrease
                                            "
                                        >
                                            Turun
                                        </span>

                                    @elseif (
                                        $row['status'] === 'new'
                                    )

                                        <span
                                            class="
                                                badge
                                                badge-new
                                            "
                                        >
                                            Komponen Baru
                                        </span>

                                    @elseif (
                                        $row['status'] === 'missing'
                                    )

                                        <span
                                            class="
                                                badge
                                                badge-missing
                                            "
                                        >
                                            Komponen Hilang
                                        </span>

                                    @else

                                        <span
                                            class="
                                                badge
                                                badge-same
                                            "
                                        >
                                            Tetap
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="empty"
                                >
                                    Tidak ditemukan perubahan
                                    pada payroll.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             DETAIL SEMUA PERUBAHAN
        ====================================================== --}}

        <div class="card">

            <div class="section-title">
                Detail Semua Perubahan
            </div>

            <div
                class="muted"
                style="margin-bottom: 15px;"
            >
                Seluruh perubahan komponen payroll
                antara kedua periode yang dipilih.
            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                Employee No
                            </th>

                            <th>
                                Nama
                            </th>

                            <th>
                                Department
                            </th>

                            <th>
                                Komponen
                            </th>

                            <th class="amount">
                                Sebelumnya
                            </th>

                            <th class="amount">
                                Sekarang
                            </th>

                            <th class="amount">
                                Selisih
                            </th>

                            <th>
                                %
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Keterangan
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse (
                            $comparison['rows'] ?? []
                            as $row
                        )

                            <tr>

                                <td>
                                    {{ $row['employee_no'] }}
                                </td>

                                <td>
                                    {{ $row['name'] }}
                                </td>

                                <td>
                                    {{ $row['department'] }}
                                </td>

                                <td>
                                    {{ $row['component'] }}
                                </td>

                                <td class="amount">

                                    Rp

                                    {{ number_format(
                                        $row['previous'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <td class="amount">

                                    Rp

                                    {{ number_format(
                                        $row['current'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <td
                                    class="
                                        amount
                                        {{
                                            $row['difference'] > 0
                                            ? 'difference-positive'
                                            : (
                                                $row['difference'] < 0
                                                ? 'difference-negative'
                                                : 'difference-zero'
                                            )
                                        }}
                                    "
                                >

                                    {{
                                        $row['difference'] > 0
                                        ? '+'
                                        : ''
                                    }}

                                    Rp

                                    {{ number_format(
                                        abs($row['difference']),
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <td>

                                    @if (
                                        $row['percentage'] !== null
                                    )

                                        {{
                                            $row['percentage'] > 0
                                            ? '+'
                                            : ''
                                        }}

                                        {{ $row['percentage'] }}%

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>

                                    @if (
                                        $row['status']
                                        === 'increase'
                                    )

                                        <span
                                            class="
                                                badge
                                                badge-increase
                                            "
                                        >
                                            Naik
                                        </span>

                                    @elseif (
                                        $row['status']
                                        === 'decrease'
                                    )

                                        <span
                                            class="
                                                badge
                                                badge-decrease
                                            "
                                        >
                                            Turun
                                        </span>

                                    @elseif (
                                        $row['status']
                                        === 'new'
                                    )

                                        <span
                                            class="
                                                badge
                                                badge-new
                                            "
                                        >
                                            Baru
                                        </span>

                                    @elseif (
                                        $row['status']
                                        === 'missing'
                                    )

                                        <span
                                            class="
                                                badge
                                                badge-missing
                                            "
                                        >
                                            Hilang
                                        </span>

                                    @else

                                        <span
                                            class="
                                                badge
                                                badge-same
                                            "
                                        >
                                            Tetap
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $row['description'] ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="empty"
                                >
                                    Tidak ada perubahan
                                    pada periode yang dibandingkan.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    @endif

</div>

</body>

</html>
```
