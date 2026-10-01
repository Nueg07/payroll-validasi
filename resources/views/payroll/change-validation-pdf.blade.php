<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Detail Validasi Perubahan Payroll</title>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #222;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 12px;
            margin-top: 18px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 15px;
        }

        .period {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .period td {
            border: 1px solid #ddd;
            padding: 7px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .summary td {
            border: 1px solid #ddd;
            padding: 7px;
            text-align: center;
        }

        .summary-title {
            font-size: 8px;
            color: #666;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }

        table.detail {
            width: 100%;
            border-collapse: collapse;
        }

        table.detail th {
            background: #174a8b;
            color: white;
            font-weight: bold;
            padding: 5px;
            border: 1px solid #ccc;
            font-size: 8px;
        }

        table.detail td {
            padding: 5px;
            border: 1px solid #ccc;
            vertical-align: top;
            font-size: 7.5px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .status-new {
            color: #0066cc;
            font-weight: bold;
        }

        .status-missing {
            color: #d97706;
            font-weight: bold;
        }

        .status-large {
            color: #dc2626;
            font-weight: bold;
        }

        .status-inconsistent {
            color: #b45309;
            font-weight: bold;
        }

        .status-normal {
            color: #15803d;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            color: #777;
            font-size: 8px;
        }
    </style>
</head>

<body>

    <h1>Detail Validasi Perubahan Payroll</h1>

    <div class="subtitle">
        Laporan perbandingan data payroll antar periode
    </div>


    <!-- PERIODE -->
    <table class="period">
        <tr>
            <td width="50%">
                <strong>Periode Sebelumnya</strong><br>

                {{ $previousSnapshot->periode_bulan }}
                /
                {{ $previousSnapshot->periode_tahun }}

                <br>

                Report Date:
                {{ $previousSnapshot->report_date }}
            </td>

            <td width="50%">
                <strong>Periode Sekarang</strong><br>

                {{ $currentSnapshot->periode_bulan }}
                /
                {{ $currentSnapshot->periode_tahun }}

                <br>

                Report Date:
                {{ $currentSnapshot->report_date }}
            </td>
        </tr>
    </table>


    <!-- SUMMARY -->
    <table class="summary">

        <tr>

            <td>
                <div class="summary-title">
                    Komponen Baru
                </div>

                <div class="summary-value">
                    {{ $validation['summary']['newComponents'] }}
                </div>
            </td>

            <td>
                <div class="summary-title">
                    Komponen Hilang
                </div>

                <div class="summary-value">
                    {{ $validation['summary']['missingComponents'] }}
                </div>
            </td>

            <td>
                <div class="summary-title">
                    Perubahan Besar
                </div>

                <div class="summary-value">
                    {{ $validation['summary']['largeChanges'] }}
                </div>
            </td>

            <td>
                <div class="summary-title">
                    Tidak Konsisten
                </div>

                <div class="summary-value">
                    {{ $validation['summary']['inconsistent'] }}
                </div>
            </td>

            <td>
                <div class="summary-title">
                    Karyawan Baru
                </div>

                <div class="summary-value">
                    {{ $validation['summary']['newEmployees'] }}
                </div>
            </td>

            <td>
                <div class="summary-title">
                    Karyawan Hilang
                </div>

                <div class="summary-value">
                    {{ $validation['summary']['missingEmployees'] }}
                </div>
            </td>

        </tr>

    </table>


    <!-- PAYROLL -->
    <h2>Perbandingan Total Payroll</h2>

    <table class="period">

        <tr>
            <td>
                <strong>Payroll Sebelumnya</strong><br>

                Rp
                {{ number_format(
                    $validation['payroll']['previous'],
                    0,
                    ',',
                    '.'
                ) }}
            </td>

            <td>
                <strong>Payroll Sekarang</strong><br>

                Rp
                {{ number_format(
                    $validation['payroll']['current'],
                    0,
                    ',',
                    '.'
                ) }}
            </td>

            <td>
                <strong>Selisih</strong><br>

                Rp
                {{ number_format(
                    $validation['payroll']['difference'],
                    0,
                    ',',
                    '.'
                ) }}
            </td>

            <td>
                <strong>Perubahan</strong><br>

                @if($validation['payroll']['percentage'] !== null)
                    {{ number_format(
                        $validation['payroll']['percentage'],
                        2,
                        ',',
                        '.'
                    ) }}%
                @else
                    -
                @endif
            </td>
        </tr>

    </table>


    <!-- DETAIL -->
    <h2>
        Detail Perubahan
    </h2>

    <table class="detail">

        <thead>

            <tr>

                <th width="4%">
                    No
                </th>

                <th width="9%">
                    Employee No
                </th>

                <th width="12%">
                    Nama
                </th>

                <th width="10%">
                    Department
                </th>

                <th width="11%">
                    Komponen
                </th>

                <th width="10%">
                    Status
                </th>

                <th width="9%">
                    Sebelumnya
                </th>

                <th width="9%">
                    Sekarang
                </th>

                <th width="9%">
                    Selisih
                </th>

                <th width="7%">
                    %
                </th>

                <th width="10%">
                    Keterangan
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($validation['details'] as $index => $row)

                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $row['employee_no'] ?? '-' }}
                    </td>

                    <td>
                        {{ $row['name'] ?? '-' }}
                    </td>

                    <td>
                        {{ $row['department'] ?? '-' }}
                    </td>

                    <td>
                        {{ $row['component'] ?? '-' }}
                    </td>

                    <td>

                        @if(($row['status'] ?? '') === 'new_component')

                            <span class="status-new">
                                Komponen Baru
                            </span>

                        @elseif(($row['status'] ?? '') === 'missing_component')

                            <span class="status-missing">
                                Komponen Hilang
                            </span>

                        @elseif(($row['status'] ?? '') === 'large_change')

                            <span class="status-large">
                                Perlu Diperiksa
                            </span>

                        @elseif(($row['status'] ?? '') === 'inconsistent')

                            <span class="status-inconsistent">
                                Tidak Konsisten
                            </span>

                        @else

                            <span class="status-normal">
                                Perubahan Normal
                            </span>

                        @endif

                    </td>

                    <td class="text-right">

                        @if($row['previous'] !== null)

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

                    <td class="text-right">

                        @if($row['current'] !== null)

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

                    <td class="text-right">

                        @if($row['difference'] !== null)

                            Rp
                            {{ number_format(
                                $row['difference'],
                                0,
                                ',',
                                '.'
                            ) }}

                        @else
                            -
                        @endif

                    </td>

                    <td class="text-center">

                        @if($row['percentage'] !== null)

                            {{ number_format(
                                $row['percentage'],
                                2,
                                ',',
                                '.'
                            ) }}%

                        @else
                            -
                        @endif

                    </td>

                    <td>
                        {{ $row['description'] ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="11" class="text-center">
                        Tidak ada data perubahan.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="footer">
        Generated from Infomedia Payroll Validation System
    </div>

</body>

</html>