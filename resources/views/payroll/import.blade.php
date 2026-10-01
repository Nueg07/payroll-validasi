<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Validasi Gaji Bulanan</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0 0 10px 0;
            color: #0f2747;
            font-size: 30px;
        }

        .header p {
            margin: 0;
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
        }

        .info-box {
            background: #eff6ff;
            border-left: 5px solid #2563eb;
            padding: 15px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            color: #1e3a8a;
            font-size: 14px;
            line-height: 1.6;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 15px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 15px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .form-group {
            margin-top: 25px;
        }

        .form-label {
            display: block;
            font-weight: bold;
            color: #334155;
            margin-bottom: 10px;
        }

        .file-wrapper {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 25px;
            background: #f8fafc;
            transition: 0.2s;
        }

        .file-wrapper:hover {
            border-color: #2563eb;
            background: #f1f5ff;
        }

        input[type="file"] {
            width: 100%;
            padding: 12px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        input[type="file"]::file-selector-button {
            background: #163e7a;
            color: white;
            border: none;
            padding: 10px 16px;
            margin-right: 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="file"]::file-selector-button:hover {
            background: #0f2d5c;
        }

        .file-info {
            margin-top: 10px;
            color: #64748b;
            font-size: 13px;
        }

        .button-wrapper {
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            background: #163e7a;
            color: white;
            border: none;
            padding: 13px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn:hover {
            background: #0f2d5c;
        }

        .btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        .loading {
            display: none;
            margin-left: 10px;
            color: #475569;
            font-size: 14px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #94a3b8;
            font-size: 13px;
        }

        @media (max-width: 600px) {

            .container {
                padding: 20px 15px;
            }

            .card {
                padding: 25px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <!-- HEADER -->
        <div class="header">

            <h1>
                Validasi Gaji Bulanan
            </h1>

            <p>
                Upload Monthly Report Excel untuk membaca,
                menyimpan, dan melakukan validasi data payroll bulanan.
            </p>

        </div>


        <!-- INFORMASI -->
        <div class="info-box">

            <strong>Format file yang didukung:</strong>

            <br>

            Excel
            <strong>.xlsx</strong>
            atau
            <strong>.xls</strong>

            <br>

            Pastikan file yang diupload merupakan
            Monthly Report Payroll.

        </div>


        <!-- SUCCESS MESSAGE -->
        @if(session('success'))

            <div class="success">

                <strong>Berhasil!</strong>

                <br>

                {{ session('success') }}

            </div>

        @endif


        <!-- ERROR MESSAGE -->
        @if($errors->any())

            <div class="error">

                <strong>Terjadi kesalahan:</strong>

                <br>

                {{ $errors->first() }}

            </div>

        @endif


        <!-- FORM UPLOAD -->
        <form
            action="{{ route('payroll.import.process') }}"
            method="POST"
            enctype="multipart/form-data"
            id="uploadForm"
        >

            @csrf


            <div class="form-group">

                <label
                    for="file"
                    class="form-label"
                >
                    Pilih File Monthly Report
                </label>


                <div class="file-wrapper">

                    <input
                        type="file"
                        id="file"
                        name="file"
                        accept=".xlsx,.xls"
                        required
                    >

                    <div class="file-info">

                        Pilih file Excel Monthly Report
                        dari komputer Anda.

                    </div>

                </div>

            </div>


            <!-- BUTTON -->
            <div class="button-wrapper">

                <button
                    type="submit"
                    class="btn"
                    id="submitButton"
                >
                    Baca File Excel
                </button>


                <span
                    class="loading"
                    id="loadingText"
                >
                    Sedang membaca file...
                </span>

            </div>

        </form>


        <!-- FOOTER -->
        <div class="footer">

            Sistem Validasi Gaji Bulanan
            <br>
            Laravel Payroll Validation System

        </div>

    </div>

</div>


<script>

    const fileInput = document.getElementById('file');

    const uploadForm = document.getElementById('uploadForm');

    const submitButton = document.getElementById('submitButton');

    const loadingText = document.getElementById('loadingText');


    /*
     * Ketika file dipilih
     */
    fileInput.addEventListener('change', function () {

        if (this.files.length > 0) {

            const fileName = this.files[0].name;

            const fileSize =
                (this.files[0].size / 1024 / 1024).toFixed(2);

            console.log(
                'File dipilih:',
                fileName
            );

            console.log(
                'Ukuran file:',
                fileSize,
                'MB'
            );
        }

    });


    /*
     * Ketika form dikirim
     */
    uploadForm.addEventListener('submit', function () {

        submitButton.disabled = true;

        submitButton.innerText =
            'Sedang diproses...';

        loadingText.style.display =
            'inline';

    });

</script>

</body>

</html>