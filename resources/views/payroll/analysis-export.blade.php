<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>Analisis Payroll</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9px;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #123b70;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 10px;
        }

        .info {
            width: 100%;
            margin-bottom: 15px;
        }

        .info td {
            padding: 4px 7px;
            border: 1px solid #ddd;
        }

        .info .label {
            width: 150px;
            font-weight: bold;
            background: #f1f5f9;
        }

        .section-title {
            background: #123b70;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 7px;
            margin-top: 12px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th {
            background: #174a8b;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            border: 1px solid #aaa;
        }

        td {
            padding: 4px 5px;
            border: 1px solid #ccc;
            vertical-align: middle;
        }

        .number {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .increase {
            color: #198754;
            font-weight: bold;
        }

        .decrease {
            color: #dc3545;
            font-weight: bold;
        }

        .new {
            color: #0d6efd;
            font-weight: bold;
        }

        .missing {
            color: #dc3545;
            font-weight: bold;
        }

        .summary-table {
            width: 60%;
        }

        .summary-table td:first-child {
            font-weight: bold;
            width: 260px;
            background: #f8fafc;
        }

        .page-break {
            page-break-before: always;
        }

        .small {
            font-size: 8px;
        }
    </style>
</head>

<body>

    @php
        $comparison = $comparison ?? [];

        $payroll = $comparison['payroll'] ?? [];
        $summary = $comparison['summary'] ?? [];

        $currentGroup = $currentGroup ?? null;
        $previousGroup = $previousGroup ?? null;

        $formatMoney = function ($value) {
            return number_format(
                (float) ($value ?? 0),
                0,
                ',',
                '.'
            );
        };
    @endphp

    <div class="header">
        <h1>ANALISIS PAYROLL</h1>

        <p>
            Perbandingan Payroll
            {{ $currentGroup['label'] ?? '-' }}
        </p>
    </div>

    <table class="info">
        <tr>
            <td class="label">Periode Sekarang</td>
            <td>{{ $currentGroup['label'] ?? '-' }}</td>

            <td class="label">Periode Sebelumnya</td>
            <td>{{ $previousGroup['label'] ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">Mode</td>
            <td>{{ ucfirst($mode ?? 'monthly') }}</td>

            <td class="label">Payday</td>
            <td>{{ $payday ?? 'all' }}</td>
        </tr>
    </table>

    <div class="section-title">
        RINGKASAN PAYROLL
    </div>

    <table class="summary-table">
        <tr>
            <td>Payroll Sebelumnya</td>
            <td class="number">
                Rp {{ $formatMoney($payroll['previous'] ?? 0) }}
            </td>
        </tr>

        <tr>
            <td>Payroll Sekarang</td>
            <td class="number">
                Rp {{ $formatMoney($payroll['current'] ?? 0) }}
            </td>
        </tr>

        <tr>
            <td>Selisih Payroll</td>
            <td class="number">
                Rp {{ $formatMoney($payroll['difference'] ?? 0) }}
            </td>
        </tr>

        <tr>
            <td>Persentase Perubahan</td>
            <td class="number">
                {{ $payroll['percentage'] !== null ? $payroll['percentage'] . '%' : '-' }}
            </td>
        </tr>
    </table>

    <div class="section-title">
        RINGKASAN PERUBAHAN
    </div>

    <table class="summary-table">
        <tr>
            <td>Naik</td>
            <td>{{ $summary['increase'] ?? 0 }}</td>
        </tr>

        <tr>
            <td>Turun</td>
            <td>{{ $summary['decrease'] ?? 0 }}</td>
        </tr>

        <tr>
            <td>Sama</td>
            <td>{{ $summary['same'] ?? 0 }}</td>
        </tr>

        <tr>
            <td>Komponen Baru</td>
            <td>{{ $summary['new'] ?? 0 }}</td>
        </tr>

        <tr>
            <td>Komponen Hilang</td>
            <td>{{ $summary['missing'] ?? 0 }}</td>
        </tr>

        <tr>
            <td>Karyawan Baru</td>
            <td>{{ $summary['newEmployees'] ?? 0 }}</td>
        </tr>

        <tr>
            <td>Karyawan Tidak Ada</td>
            <td>{{ $summary['missingEmployees'] ?? 0 }}</td>
        </tr>

        <tr>
            <td>Total Kenaikan</td>
            <td class="number">
                Rp {{ $formatMoney($summary['totalIncrease'] ?? 0) }}
            </td>
        </tr>

        <tr>
            <td>Total Penurunan</td>
            <td class="number">
                Rp {{ $formatMoney($summary['totalDecrease'] ?? 0) }}
            </td>
        </tr>

        <tr>
            <td>Total Employee</td>
            <td>{{ $summary['totalEmployees'] ?? 0 }}</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <div class="section-title">
        TOP 10 PERUBAHAN PAYROLL
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Employee No</th>
                <th>Nama</th>
                <th>Department</th>
                <th>Komponen</th>
                <th>Periode Sebelumnya</th>
                <th>Periode Sekarang</th>
                <th>Selisih</th>
                <th>Persentase</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($comparison['topChanges'] ?? [] as $index => $row)

                <tr>
                    <td class="center">
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

                    <td class="number">
                        Rp {{ $formatMoney($row['previous'] ?? 0) }}
                    </td>

                    <td class="number">
                        Rp {{ $formatMoney($row['current'] ?? 0) }}
                    </td>

                    <td class="number">
                        Rp {{ $formatMoney($row['difference'] ?? 0) }}
                    </td>

                    <td class="number">
                        {{ $row['percentage'] !== null ? $row['percentage'] . '%' : '-' }}
                    </td>

                    <td class="center">
                        {{ strtoupper($row['status'] ?? '-') }}
                    </td>

                    <td>
                        {{ $row['description'] ?? '-' }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="11" class="center">
                        Tidak ada perubahan.
                    </td>
                </tr>

            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">
        DETAIL PERUBAHAN PAYROLL
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Employee No</th>
                <th>Nama</th>
                <th>Department</th>
                <th>Komponen</th>
                <th>Periode Sebelumnya</th>
                <th>Periode Sekarang</th>
                <th>Selisih</th>
                <th>Persentase</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($comparison['rows'] ?? [] as $index => $row)

                <tr>
                    <td class="center">
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

                    <td class="number">
                        Rp {{ $formatMoney($row['previous'] ?? 0) }}
                    </td>

                    <td class="number">
                        Rp {{ $formatMoney($row['current'] ?? 0) }}
                    </td>

                    <td class="number">
                        Rp {{ $formatMoney($row['difference'] ?? 0) }}
                    </td>

                    <td class="number">
                        {{ $row['percentage'] !== null ? $row['percentage'] . '%' : '-' }}
                    </td>

                    <td class="center">
                        {{ strtoupper($row['status'] ?? '-') }}
                    </td>

                    <td>
                        {{ $row['description'] ?? '-' }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="11" class="center">
                        Tidak ada detail perubahan.
                    </td>
                </tr>

            @endforelse
        </tbody>
    </table>

</body>
</html>