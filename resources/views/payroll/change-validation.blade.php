<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Detail Validasi Perubahan Payroll</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>

        body {
            background: #f4f7fb;
            color: #1f2937;
            font-family: Arial, Helvetica, sans-serif;
        }

        .page-wrapper {
            padding: 30px;
        }

        .page-header {
            background: linear-gradient(
                135deg,
                #123b70,
                #174a8b
            );
            color: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 8px 25px rgba(18, 59, 112, .15);
        }

        .page-header h3 {
            margin: 0;
            font-weight: 700;
        }

        .page-header p {
            margin: 6px 0 0;
            opacity: .85;
        }

        .card-custom {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
            margin-bottom: 25px;
        }

        .card-custom .card-header {
            background: transparent;
            border-bottom: 1px solid #edf0f5;
            padding: 18px 20px;
            font-weight: 700;
        }

        .card-custom .card-body {
            padding: 20px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
        }

        .form-select {
            border-radius: 10px;
            min-height: 44px;
        }

        .btn {
            border-radius: 9px;
            font-weight: 600;
        }

        .btn-check-data {
            background: #174a8b;
            color: white;
            border: none;
            min-height: 44px;
        }

        .btn-check-data:hover {
            background: #123b70;
            color: white;
        }

        .btn-excel {
            background: #198754;
            color: white;
            border: none;
        }

        .btn-excel:hover {
            background: #157347;
            color: white;
        }

        .btn-pdf {
            background: #dc3545;
            color: white;
            border: none;
        }

        .btn-pdf:hover {
            background: #bb2d3b;
            color: white;
        }

        .period-card {
            border-radius: 14px;
            padding: 20px;
            height: 100%;
        }

        .period-previous {
            background: #fff7ed;
            border: 1px solid #fed7aa;
        }

        .period-current {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }

        .period-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .period-value {
            font-size: 22px;
            font-weight: 700;
        }

        .period-date {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }

        .summary-card {
            background: white;
            border-radius: 14px;
            padding: 18px;
            height: 100%;
            border: 1px solid #edf0f5;
            box-shadow: 0 3px 12px rgba(0, 0, 0, .04);
        }

        .summary-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-size: 18px;
        }

        .summary-title {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 25px;
            font-weight: 700;
            color: #111827;
        }

        .icon-blue {
            background: #dbeafe;
            color: #2563eb;
        }

        .icon-orange {
            background: #ffedd5;
            color: #ea580c;
        }

        .icon-red {
            background: #fee2e2;
            color: #dc2626;
        }

        .icon-yellow {
            background: #fef3c7;
            color: #d97706;
        }

        .icon-green {
            background: #dcfce7;
            color: #16a34a;
        }

        .icon-gray {
            background: #f3f4f6;
            color: #4b5563;
        }

        .payroll-card {
            background: white;
            border-radius: 14px;
            border: 1px solid #edf0f5;
            padding: 20px;
            height: 100%;
        }

        .payroll-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 7px;
        }

        .payroll-value {
            font-size: 21px;
            font-weight: 700;
        }

        .payroll-difference {
            font-size: 13px;
            margin-top: 7px;
        }

        .increase {
            color: #16a34a;
        }

        .decrease {
            color: #dc2626;
        }

        .neutral {
            color: #6b7280;
        }

        .filter-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-tabs a {
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: #f3f4f6;
            color: #4b5563;
        }

        .filter-tabs a:hover {
            background: #e5e7eb;
        }

        .filter-tabs a.active {
            background: #174a8b;
            color: white;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
            min-width: 1200px;
        }

        .table thead th {
            background: #123b70;
            color: white;
            border: none;
            font-size: 12px;
            padding: 13px 10px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 12px 10px;
            vertical-align: middle;
            font-size: 12px;
            border-color: #edf0f5;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-new {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-missing {
            background: #ffedd5;
            color: #c2410c;
        }

        .status-large {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-inconsistent {
            background: #fef3c7;
            color: #92400e;
        }

        .status-normal {
            background: #dcfce7;
            color: #166534;
        }

        .money {
            white-space: nowrap;
            text-align: right;
            font-weight: 600;
        }

        .percentage {
            white-space: nowrap;
            font-weight: 600;
        }

        .description {
            min-width: 300px;
            color: #6b7280;
            line-height: 1.5;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6b7280;
        }

        .empty-state i {
            font-size: 45px;
            margin-bottom: 15px;
            color: #9ca3af;
        }

        .export-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .export-title {
            font-weight: 700;
            font-size: 16px;
        }

        .export-description {
            color: #6b7280;
            font-size: 12px;
            margin-top: 3px;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }

        @media (max-width: 768px) {

            .page-wrapper {
                padding: 15px;
            }

            .page-header {
                padding: 20px;
            }

            .page-header h3 {
                font-size: 20px;
            }

            .export-section {
                align-items: flex-start;
                flex-direction: column;
            }

        }

    </style>

</head>

<body>

<div class="page-wrapper">

    <!-- ===================================================== -->
    <!-- HEADER -->
    <!-- ===================================================== -->

    <div class="page-header">

        <div class="d-flex align-items-center gap-3">

            <div>
                <i class="fa-solid fa-code-compare fa-2x"></i>
            </div>

            <div>

                <h3>
                    Detail Validasi Perubahan Payroll
                </h3>

                <p>
                    Membandingkan perubahan data payroll antar periode
                    dan mendeteksi perubahan yang perlu diperiksa.
                </p>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- ALERT -->
    <!-- ===================================================== -->

    @if(session('error'))

        <div class="alert alert-danger mb-4">

            <i class="fa-solid fa-circle-exclamation me-2"></i>

            {{ session('error') }}

        </div>

    @endif


    @if(session('success'))

        <div class="alert alert-success mb-4">

            <i class="fa-solid fa-circle-check me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    <!-- ===================================================== -->
    <!-- FILTER PERIODE -->
    <!-- ===================================================== -->

    <div class="card-custom">

        <div class="card-header">

            <i class="fa-solid fa-filter me-2"></i>

            Pilih Periode Payroll

        </div>

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('payroll.change-validation') }}"
            >

                <div class="row g-3 align-items-end">

                    <!-- CURRENT -->
                    <div class="col-md-5">

                        <label class="form-label">
                            Periode Sekarang
                        </label>

                        <select
                            name="current"
                            class="form-select"
                        >

                            @foreach($snapshots as $snapshot)

                                <option
                                    value="{{ $snapshot->id }}"
                                    @selected(
                                        $currentSnapshot &&
                                        $currentSnapshot->id == $snapshot->id
                                    )
                                >

                                    {{ $snapshot->periode_bulan }}
                                    /
                                    {{ $snapshot->periode_tahun }}

                                    -
                                    {{ $snapshot->report_date }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- PREVIOUS -->
                    <div class="col-md-5">

                        <label class="form-label">
                            Periode Sebelumnya
                        </label>

                        <select
                            name="previous"
                            class="form-select"
                        >

                            <option value="">
                                Otomatis
                            </option>

                            @foreach($snapshots as $snapshot)

                                <option
                                    value="{{ $snapshot->id }}"
                                    @selected(
                                        $previousSnapshot &&
                                        $previousSnapshot->id == $snapshot->id
                                    )
                                >

                                    {{ $snapshot->periode_bulan }}
                                    /
                                    {{ $snapshot->periode_tahun }}

                                    -
                                    {{ $snapshot->report_date }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- BUTTON -->
                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="btn btn-check-data w-100"
                        >

                            <i class="fa-solid fa-magnifying-glass me-1"></i>

                            Periksa Data

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    @if($currentSnapshot && $previousSnapshot && $validation)


        <!-- ================================================= -->
        <!-- PERIODE -->
        <!-- ================================================= -->

        <div class="row g-3 mb-4">

            <div class="col-md-6">

                <div class="period-card period-previous">

                    <div class="period-title text-warning-emphasis">

                        <i class="fa-solid fa-clock-rotate-left me-1"></i>

                        Periode Sebelumnya

                    </div>

                    <div class="period-value">

                        {{ $previousSnapshot->periode_bulan }}
                        /
                        {{ $previousSnapshot->periode_tahun }}

                    </div>

                    <div class="period-date">

                        <i class="fa-regular fa-calendar me-1"></i>

                        Report Date:
                        {{ $previousSnapshot->report_date }}

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="period-card period-current">

                    <div class="period-title text-primary">

                        <i class="fa-solid fa-calendar-check me-1"></i>

                        Periode Sekarang

                    </div>

                    <div class="period-value">

                        {{ $currentSnapshot->periode_bulan }}
                        /
                        {{ $currentSnapshot->periode_tahun }}

                    </div>

                    <div class="period-date">

                        <i class="fa-regular fa-calendar me-1"></i>

                        Report Date:
                        {{ $currentSnapshot->report_date }}

                    </div>

                </div>

            </div>

        </div>


        <!-- ================================================= -->
        <!-- SUMMARY -->
        <!-- ================================================= -->

        <div class="row g-3 mb-4">

            <!-- KOMPONEN BARU -->
            <div class="col-md-6 col-lg-2">

                <div class="summary-card">

                    <div class="summary-icon icon-blue">

                        <i class="fa-solid fa-circle-plus"></i>

                    </div>

                    <div class="summary-title">
                        Komponen Baru
                    </div>

                    <div class="summary-value">

                        {{ $validation['summary']['newComponents'] }}

                    </div>

                </div>

            </div>


            <!-- KOMPONEN HILANG -->
            <div class="col-md-6 col-lg-2">

                <div class="summary-card">

                    <div class="summary-icon icon-orange">

                        <i class="fa-solid fa-circle-minus"></i>

                    </div>

                    <div class="summary-title">
                        Komponen Hilang
                    </div>

                    <div class="summary-value">

                        {{ $validation['summary']['missingComponents'] }}

                    </div>

                </div>

            </div>


            <!-- PERUBAHAN BESAR -->
            <div class="col-md-6 col-lg-2">

                <div class="summary-card">

                    <div class="summary-icon icon-red">

                        <i class="fa-solid fa-triangle-exclamation"></i>

                    </div>

                    <div class="summary-title">
                        Perubahan Besar
                    </div>

                    <div class="summary-value">

                        {{ $validation['summary']['largeChanges'] }}

                    </div>

                </div>

            </div>


            <!-- TIDAK KONSISTEN -->
            <div class="col-md-6 col-lg-2">

                <div class="summary-card">

                    <div class="summary-icon icon-yellow">

                        <i class="fa-solid fa-circle-exclamation"></i>

                    </div>

                    <div class="summary-title">
                        Tidak Konsisten
                    </div>

                    <div class="summary-value">

                        {{ $validation['summary']['inconsistent'] }}

                    </div>

                </div>

            </div>


            <!-- KARYAWAN BARU -->
            <div class="col-md-6 col-lg-2">

                <div class="summary-card">

                    <div class="summary-icon icon-green">

                        <i class="fa-solid fa-user-plus"></i>

                    </div>

                    <div class="summary-title">
                        Karyawan Baru
                    </div>

                    <div class="summary-value">

                        {{ $validation['summary']['newEmployees'] }}

                    </div>

                </div>

            </div>


            <!-- KARYAWAN HILANG -->
            <div class="col-md-6 col-lg-2">

                <div class="summary-card">

                    <div class="summary-icon icon-gray">

                        <i class="fa-solid fa-user-minus"></i>

                    </div>

                    <div class="summary-title">
                        Karyawan Hilang
                    </div>

                    <div class="summary-value">

                        {{ $validation['summary']['missingEmployees'] }}

                    </div>

                </div>

            </div>

        </div>


        <!-- ================================================= -->
        <!-- TOTAL PAYROLL -->
        <!-- ================================================= -->

        <div class="card-custom">

            <div class="card-header">

                <i class="fa-solid fa-money-bill-trend-up me-2"></i>

                Perbandingan Total Payroll

            </div>

            <div class="card-body">

                <div class="row g-3">

                    <!-- PREVIOUS -->
                    <div class="col-md-3">

                        <div class="payroll-card">

                            <div class="payroll-label">
                                Payroll Sebelumnya
                            </div>

                            <div class="payroll-value">

                                Rp
                                {{ number_format(
                                    $validation['payroll']['previous'],
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>

                        </div>

                    </div>


                    <!-- CURRENT -->
                    <div class="col-md-3">

                        <div class="payroll-card">

                            <div class="payroll-label">
                                Payroll Sekarang
                            </div>

                            <div class="payroll-value">

                                Rp
                                {{ number_format(
                                    $validation['payroll']['current'],
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>

                        </div>

                    </div>


                    <!-- DIFFERENCE -->
                    <div class="col-md-3">

                        <div class="payroll-card">

                            <div class="payroll-label">
                                Selisih
                            </div>

                            <div class="payroll-value">

                                Rp
                                {{ number_format(
                                    abs($validation['payroll']['difference']),
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>

                            @if($validation['payroll']['difference'] > 0)

                                <div class="payroll-difference increase">

                                    <i class="fa-solid fa-arrow-up"></i>

                                    Payroll meningkat

                                </div>

                            @elseif($validation['payroll']['difference'] < 0)

                                <div class="payroll-difference decrease">

                                    <i class="fa-solid fa-arrow-down"></i>

                                    Payroll menurun

                                </div>

                            @else

                                <div class="payroll-difference neutral">

                                    <i class="fa-solid fa-minus"></i>

                                    Tidak berubah

                                </div>

                            @endif

                        </div>

                    </div>


                    <!-- PERCENTAGE -->
                    <div class="col-md-3">

                        <div class="payroll-card">

                            <div class="payroll-label">
                                Persentase Perubahan
                            </div>

                            <div class="payroll-value">

                                @if(
                                    $validation['payroll']['percentage']
                                    !== null
                                )

                                    {{ number_format(
                                        $validation['payroll']['percentage'],
                                        2,
                                        ',',
                                        '.'
                                    ) }}%

                                @else

                                    -

                                @endif

                            </div>

                            <div class="payroll-difference neutral">

                                Perbandingan antar periode

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- ================================================= -->
        <!-- EXPORT -->
        <!-- ================================================= -->

        <div class="card-custom">

            <div class="card-body">

                <div class="export-section">

                    <div>

                        <div class="export-title">

                            <i class="fa-solid fa-file-export me-2"></i>

                            Export Data Validasi

                        </div>

                        <div class="export-description">

                            Export hasil validasi berdasarkan periode
                            dan filter yang sedang dipilih.

                        </div>

                    </div>


                    <div class="d-flex gap-2 flex-wrap">

                        <!-- EXCEL -->

                        <a
                            href="{{ route(
                                'payroll.change-validation.export.excel',
                                [
                                    'current' =>
                                        $currentSnapshot->id,

                                    'previous' =>
                                        $previousSnapshot->id,

                                    'filter' =>
                                        $filter
                                ]
                            ) }}"
                            class="btn btn-excel"
                        >

                            <i class="fa-solid fa-file-excel me-1"></i>

                            Export Excel

                        </a>


                        <!-- PDF -->

                        <a
                            href="{{ route(
                                'payroll.change-validation.export.pdf',
                                [
                                    'current' =>
                                        $currentSnapshot->id,

                                    'previous' =>
                                        $previousSnapshot->id,

                                    'filter' =>
                                        $filter
                                ]
                            ) }}"
                            class="btn btn-pdf"
                        >

                            <i class="fa-solid fa-file-pdf me-1"></i>

                            Export PDF

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- ================================================= -->
        <!-- FILTER STATUS -->
        <!-- ================================================= -->

        <div class="card-custom">

            <div class="card-header">

                <i class="fa-solid fa-list-filter me-2"></i>

                Filter Status Perubahan

            </div>

            <div class="card-body">

                <div class="filter-tabs">

                    <a
                        href="{{ route(
                            'payroll.change-validation',
                            [
                                'current' =>
                                    $currentSnapshot->id,

                                'previous' =>
                                    $previousSnapshot->id,

                                'filter' =>
                                    'all'
                            ]
                        ) }}"
                        class="{{ $filter === 'all' ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-list me-1"></i>

                        Semua

                    </a>


                    <a
                        href="{{ route(
                            'payroll.change-validation',
                            [
                                'current' =>
                                    $currentSnapshot->id,

                                'previous' =>
                                    $previousSnapshot->id,

                                'filter' =>
                                    'new_component'
                            ]
                        ) }}"
                        class="{{ $filter === 'new_component' ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-circle-plus me-1"></i>

                        Komponen Baru

                    </a>


                    <a
                        href="{{ route(
                            'payroll.change-validation',
                            [
                                'current' =>
                                    $currentSnapshot->id,

                                'previous' =>
                                    $previousSnapshot->id,

                                'filter' =>
                                    'missing_component'
                            ]
                        ) }}"
                        class="{{ $filter === 'missing_component' ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-circle-minus me-1"></i>

                        Komponen Hilang

                    </a>


                    <a
                        href="{{ route(
                            'payroll.change-validation',
                            [
                                'current' =>
                                    $currentSnapshot->id,

                                'previous' =>
                                    $previousSnapshot->id,

                                'filter' =>
                                    'large_change'
                            ]
                        ) }}"
                        class="{{ $filter === 'large_change' ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-triangle-exclamation me-1"></i>

                        Perubahan Besar

                    </a>


                    <a
                        href="{{ route(
                            'payroll.change-validation',
                            [
                                'current' =>
                                    $currentSnapshot->id,

                                'previous' =>
                                    $previousSnapshot->id,

                                'filter' =>
                                    'inconsistent'
                            ]
                        ) }}"
                        class="{{ $filter === 'inconsistent' ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-circle-exclamation me-1"></i>

                        Tidak Konsisten

                    </a>


                    <a
                        href="{{ route(
                            'payroll.change-validation',
                            [
                                'current' =>
                                    $currentSnapshot->id,

                                'previous' =>
                                    $previousSnapshot->id,

                                'filter' =>
                                    'normal_change'
                            ]
                        ) }}"
                        class="{{ $filter === 'normal_change' ? 'active' : '' }}"
                    >

                        <i class="fa-solid fa-circle-check me-1"></i>

                        Perubahan Normal

                    </a>

                </div>

            </div>

        </div>


        <!-- ================================================= -->
        <!-- DETAIL DATA -->
        <!-- ================================================= -->

        <div class="card-custom">

            <div class="card-header d-flex justify-content-between align-items-center">

                <div>

                    <i class="fa-solid fa-table me-2"></i>

                    Detail Perubahan Payroll

                </div>

                <span class="badge bg-secondary">

                    {{ count($validation['details']) }}

                    Data

                </span>

            </div>


            <div class="card-body p-0">

                <div class="table-wrapper">

                    <table class="table table-hover">

                        <thead>

                            <tr>

                                <th>
                                    No
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

                                <th>
                                    Status
                                </th>

                                <th class="text-end">
                                    Sebelumnya
                                </th>

                                <th class="text-end">
                                    Sekarang
                                </th>

                                <th class="text-end">
                                    Selisih
                                </th>

                                <th>
                                    Persentase
                                </th>

                                <th>
                                    Keterangan
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse(
                                $validation['details']
                                as $index => $row
                            )

                                <tr>

                                    <!-- NO -->

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <!-- EMPLOYEE NO -->

                                    <td>

                                        <strong>
                                            {{ $row['employee_no'] ?? '-' }}
                                        </strong>

                                    </td>


                                    <!-- NAME -->

                                    <td>
                                        {{ $row['name'] ?? '-' }}
                                    </td>


                                    <!-- DEPARTMENT -->

                                    <td>
                                        {{ $row['department'] ?? '-' }}
                                    </td>


                                    <!-- COMPONENT -->

                                    <td>

                                        <strong>
                                            {{ $row['component'] ?? '-' }}
                                        </strong>

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        @if(
                                            ($row['status'] ?? '')
                                            === 'new_component'
                                        )

                                            <span
                                                class="status-badge status-new"
                                            >

                                                <i class="fa-solid fa-circle-plus"></i>

                                                Komponen Baru

                                            </span>

                                        @elseif(
                                            ($row['status'] ?? '')
                                            === 'missing_component'
                                        )

                                            <span
                                                class="status-badge status-missing"
                                            >

                                                <i class="fa-solid fa-circle-minus"></i>

                                                Komponen Hilang

                                            </span>

                                        @elseif(
                                            ($row['status'] ?? '')
                                            === 'large_change'
                                        )

                                            <span
                                                class="status-badge status-large"
                                            >

                                                <i class="fa-solid fa-triangle-exclamation"></i>

                                                Perlu Diperiksa

                                            </span>

                                        @elseif(
                                            ($row['status'] ?? '')
                                            === 'inconsistent'
                                        )

                                            <span
                                                class="status-badge status-inconsistent"
                                            >

                                                <i class="fa-solid fa-circle-exclamation"></i>

                                                Tidak Konsisten

                                            </span>

                                        @else

                                            <span
                                                class="status-badge status-normal"
                                            >

                                                <i class="fa-solid fa-circle-check"></i>

                                                Perubahan Normal

                                            </span>

                                        @endif

                                    </td>


                                    <!-- PREVIOUS -->

                                    <td class="money">

                                        @if(
                                            $row['previous'] !== null
                                        )

                                            Rp
                                            {{ number_format(
                                                $row['previous'],
                                                0,
                                                ',',
                                                '.'
                                            ) }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <!-- CURRENT -->

                                    <td class="money">

                                        @if(
                                            $row['current'] !== null
                                        )

                                            Rp
                                            {{ number_format(
                                                $row['current'],
                                                0,
                                                ',',
                                                '.'
                                            ) }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <!-- DIFFERENCE -->

                                    <td class="money">

                                        @if(
                                            $row['difference'] !== null
                                        )

                                            @if(
                                                $row['difference'] > 0
                                            )

                                                <span class="increase">

                                                    <i class="fa-solid fa-arrow-up"></i>

                                                    Rp
                                                    {{ number_format(
                                                        $row['difference'],
                                                        0,
                                                        ',',
                                                        '.'
                                                    ) }}

                                                </span>

                                            @elseif(
                                                $row['difference'] < 0
                                            )

                                                <span class="decrease">

                                                    <i class="fa-solid fa-arrow-down"></i>

                                                    Rp
                                                    {{ number_format(
                                                        abs(
                                                            $row['difference']
                                                        ),
                                                        0,
                                                        ',',
                                                        '.'
                                                    ) }}

                                                </span>

                                            @else

                                                Rp 0

                                            @endif

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <!-- PERCENTAGE -->

                                    <td class="percentage">

                                        @if(
                                            $row['percentage'] !== null
                                        )

                                            @if(
                                                $row['percentage'] > 0
                                            )

                                                <span class="increase">

                                                    +{{ number_format(
                                                        $row['percentage'],
                                                        2,
                                                        ',',
                                                        '.'
                                                    ) }}%

                                                </span>

                                            @elseif(
                                                $row['percentage'] < 0
                                            )

                                                <span class="decrease">

                                                    {{ number_format(
                                                        $row['percentage'],
                                                        2,
                                                        ',',
                                                        '.'
                                                    ) }}%

                                                </span>

                                            @else

                                                0%

                                            @endif

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <!-- DESCRIPTION -->

                                    <td class="description">

                                        {{ $row['description'] ?? '-' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="11"
                                        class="empty-state"
                                    >

                                        <i
                                            class="fa-solid fa-circle-check d-block"
                                        ></i>

                                        <strong>
                                            Tidak ada data perubahan
                                        </strong>

                                        <div class="mt-1">
                                            Tidak ditemukan data
                                            berdasarkan filter yang dipilih.
                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


    @elseif($currentSnapshot && !$previousSnapshot)


        <!-- ================================================= -->
        <!-- BELUM ADA PERIODE SEBELUMNYA -->
        <!-- ================================================= -->

        <div class="card-custom">

            <div class="card-body">

                <div class="empty-state">

                    <i class="fa-solid fa-clock-rotate-left d-block"></i>

                    <h5>
                        Periode Sebelumnya Belum Ditemukan
                    </h5>

                    <p>
                        Silakan pilih periode sebelumnya terlebih dahulu
                        untuk melakukan validasi perubahan payroll.
                    </p>

                </div>

            </div>

        </div>


    @elseif(!$currentSnapshot)


        <!-- ================================================= -->
        <!-- BELUM ADA DATA -->
        <!-- ================================================= -->

        <div class="card-custom">

            <div class="card-body">

                <div class="empty-state">

                    <i class="fa-solid fa-database d-block"></i>

                    <h5>
                        Belum Ada Data Payroll
                    </h5>

                    <p>
                        Silakan import data payroll terlebih dahulu
                        sebelum melakukan validasi.
                    </p>

                    @if(Route::has('payroll.import'))

                        <a
                            href="{{ route('payroll.import') }}"
                            class="btn btn-primary mt-2"
                        >

                            <i class="fa-solid fa-file-import me-1"></i>

                            Import Data Payroll

                        </a>

                    @endif

                </div>

            </div>

        </div>

    @endif


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>