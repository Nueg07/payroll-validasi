<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Validasi Data Payroll</title>

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
            width: 92%;
            max-width: 1250px;
            margin: 35px auto;
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
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 22px;
            box-shadow:
                0 5px 20px
                rgba(15, 23, 42, 0.06);
        }

        .period-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .period-info h2 {
            margin: 0 0 10px;
            color: #123b70;
        }

        .period-info p {
            margin: 5px 0;
            color: #64748b;
        }

        .period-select {
            min-width: 280px;
        }

        .period-select select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d7dee8;
            border-radius: 8px;
            background: white;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 8px;
            padding: 11px 18px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-primary {
            background: #174a8b;
            color: white;
        }

        .btn-primary:hover {
            background: #123b70;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

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
                rgba(15, 23, 42, 0.06);
        }

        .kpi-title {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 10px;
        }

        .kpi-value {
            font-size: 28px;
            font-weight: 700;
        }

        .kpi.blue .kpi-value {
            color: #174a8b;
        }

        .kpi.green .kpi-value {
            color: #16a34a;
        }

        .kpi.red .kpi-value {
            color: #dc2626;
        }

        .kpi.orange .kpi-value {
            color: #ea580c;
        }

        .progress-wrapper {
            margin-top: 15px;
        }

        .progress {
            height: 9px;
            background: #e5e7eb;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: #16a34a;
            border-radius: 20px;
        }

        .progress-text {
            margin-top: 8px;
            font-size: 13px;
            color: #64748b;
        }

        .section-title {
            font-size: 19px;
            font-weight: 700;
            color: #123b70;
            margin-bottom: 18px;
        }

        .check-list {
            display: grid;
            grid-template-columns:
                repeat(2, 1fr);
            gap: 14px;
        }

        .check-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 15px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .check-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .check-valid {
            background: #dcfce7;
            color: #15803d;
        }

        .check-invalid {
            background: #fee2e2;
            color: #b91c1c;
        }

        .check-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .check-description {
            color: #64748b;
            font-size: 13px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            background: #f1f5f9;
            color: #475569;
            padding: 13px;
            font-size: 13px;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-success {
            background: #dcfce7;
            color: #15803d;
        }

        .empty {
            padding: 35px;
            text-align: center;
            color: #64748b;
        }

        .action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .success-message {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .warning-message {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .error-list {
            margin: 0;
            padding-left: 18px;
        }

        @media (max-width: 900px) {

            .kpi-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .check-list {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 600px) {

            .container {
                width: 95%;
            }

            .kpi-grid {
                grid-template-columns: 1fr;
            }

            .period-select {
                width: 100%;
            }

            .header h1 {
                font-size: 24px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <!-- HEADER -->

    <div class="header">

        <h1>
            Validasi Data Payroll
        </h1>

        <p>
            Memeriksa kelengkapan dan validitas
            data payroll sebelum dilakukan perbandingan.
        </p>

    </div>


    @if (!$snapshot)

        <div class="card">

            <div class="empty">

                <h3>
                    Belum ada data payroll
                </h3>

                <p>
                    Silakan upload Monthly Report Excel
                    terlebih dahulu.
                </p>

                <a
                    href="{{ route('payroll.import') }}"
                    class="btn btn-primary"
                >
                    Upload Monthly Report
                </a>

            </div>

        </div>

    @else

        <!-- PILIH SNAPSHOT -->

        <div class="card">

            <div class="period-header">

                <div class="period-info">

                    <h2>
                        {{ $snapshot->payroll_period }}
                    </h2>

                    <p>
                        Tanggal laporan:
                        <strong>
                            {{ $snapshot->report_date?->format('d-m-Y') }}
                        </strong>
                    </p>

                    <p>
                        Periode:
                        <strong>
                            {{ $snapshot->periode_bulan }}
                            /
                            {{ $snapshot->periode_tahun }}
                        </strong>
                    </p>

                </div>

                <div class="period-select">

                    <form
                        method="GET"
                        action="{{ route('payroll.validation') }}"
                    >

                        <label>
                            Pilih Snapshot Payroll
                        </label>

                        <select
                            name="snapshot"
                            onchange="this.form.submit()"
                        >

                            @foreach ($snapshots as $item)

                                <option
                                    value="{{ $item->id }}"
                                    {{ $snapshot->id == $item->id ? 'selected' : '' }}
                                >

                                    {{ $item->payroll_period }}
                                    -
                                    {{ $item->report_date?->format('d-m-Y') }}

                                </option>

                            @endforeach

                        </select>

                    </form>

                </div>

            </div>

        </div>


        <!-- KPI -->

        <div class="kpi-grid">

            <div class="kpi blue">

                <div class="kpi-title">
                    TOTAL DATA
                </div>

                <div class="kpi-value">
                    {{ number_format($validation['totalData']) }}
                </div>

                <div class="progress-text">
                    Data karyawan
                </div>

            </div>


            <div class="kpi green">

                <div class="kpi-title">
                    DATA VALID
                </div>

                <div class="kpi-value">
                    {{ number_format($validation['totalValid']) }}
                </div>

                <div class="progress-text">
                    Data tanpa error
                </div>

            </div>


            <div class="kpi red">

                <div class="kpi-title">
                    DATA ERROR
                </div>

                <div class="kpi-value">
                    {{ number_format($validation['totalError']) }}
                </div>

                <div class="progress-text">
                    Data perlu diperiksa
                </div>

            </div>


            <div class="kpi orange">

                <div class="kpi-title">
                    PERSENTASE VALID
                </div>

                <div class="kpi-value">
                    {{ $validation['percentageValid'] }}%
                </div>

                <div class="progress-wrapper">

                    <div class="progress">

                        <div
                            class="progress-bar"
                            style="
                                width:
                                {{ $validation['percentageValid'] }}%;
                            "
                        ></div>

                    </div>

                </div>

            </div>

        </div>


        <!-- DETAIL VALIDASI -->

        <div class="card">

            <div class="section-title">
                Detail Validasi
            </div>

            <div class="check-list">

                @foreach ($validation['checks'] as $check)

                    <div class="check-item">

                        <div
                            class="
                                check-icon
                                {{
                                    $check['valid']
                                    ? 'check-valid'
                                    : 'check-invalid'
                                }}
                            "
                        >

                            {{ $check['valid'] ? '✓' : '!' }}

                        </div>

                        <div>

                            <div class="check-name">
                                {{ $check['name'] }}
                            </div>

                            <div class="check-description">
                                {{ $check['description'] }}
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>


        <!-- DATA ERROR -->

        <div class="card">

            <div class="section-title">
                Detail Data Error
            </div>

            @if ($validation['totalError'] === 0)

                <div class="success-message">

                    ✓
                    <strong>
                        Data valid.
                    </strong>

                    Tidak ditemukan error pada
                    {{ $validation['totalData'] }}
                    data karyawan.

                </div>

            @else

                <div class="warning-message">

                    Terdapat
                    <strong>
                        {{ $validation['totalError'] }}
                    </strong>
                    data yang perlu diperiksa.

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
                                    Masalah
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($validation['errors'] as $error)

                                <tr>

                                    <td>
                                        <strong>
                                            {{ $error['employee_no'] }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $error['name'] }}
                                    </td>

                                    <td>
                                        {{ $error['department'] }}
                                    </td>

                                    <td>

                                        <ul class="error-list">

                                            @foreach (
                                                $error['issues']
                                                as $issue
                                            )

                                                <li>
                                                    {{ $issue }}
                                                </li>

                                            @endforeach

                                        </ul>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>


        <!-- RINGKASAN SNAPSHOT -->

        <div class="card">

            <div class="section-title">
                Ringkasan Payroll
            </div>

            <div class="table-wrapper">

                <table>

                    <tbody>

                        <tr>

                            <td>
                                Total Salary
                            </td>

                            <td>
                                <strong>
                                    Rp
                                    {{ number_format(
                                        $snapshot->total_salary,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </strong>
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Total Income
                            </td>

                            <td>
                                <strong>
                                    Rp
                                    {{ number_format(
                                        $snapshot->total_income,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </strong>
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Total Overtime
                            </td>

                            <td>
                                Rp
                                {{ number_format(
                                    $snapshot->total_overtime,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Total Deduction
                            </td>

                            <td>
                                Rp
                                {{ number_format(
                                    $snapshot->total_deduction,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Total Tax
                            </td>

                            <td>
                                Rp
                                {{ number_format(
                                    $snapshot->total_tax,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Total Net Salary
                            </td>

                            <td>
                                <strong>
                                    Rp
                                    {{ number_format(
                                        $snapshot->total_net_salary,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </strong>
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- ACTION -->

        <div class="card">

            <div class="action">

                <div>

                    @if ($validation['canContinue'])

                        <strong>
                            ✓ Data siap dianalisis
                        </strong>

                        <div class="progress-text">

                            Tidak ditemukan error.
                            Silakan lanjut ke proses
                            perbandingan payroll.

                        </div>

                    @else

                        <strong>
                            ⚠ Data perlu diperiksa
                        </strong>

                        <div class="progress-text">

                            Perbaiki data terlebih dahulu
                            sebelum melakukan perbandingan.

                        </div>

                    @endif

                </div>


                @if ($validation['canContinue'])

                    <a
                        href="#"
                        class="btn btn-success"
                    >
                        Lanjut ke Perbandingan
                    </a>

                @else

                    <button
                        class="btn btn-secondary"
                        disabled
                    >
                        Data Belum Valid
                    </button>

                @endif

            </div>

        </div>

    @endif

</div>

</body>

</html>