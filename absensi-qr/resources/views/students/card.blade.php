<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pelajar</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .card {
            width: 250px;
            height: 400px;
            border-radius: 12px;
            border: 2px solid #007BFF;
            background: linear-gradient(to bottom, #ffffff, #e6f0ff);
            padding: 20px 15px;
            position: relative;
            box-sizing: border-box;
        }
        .logo {
            width: 60px;
            height: 60px;
            display: block;
            margin: 0 auto;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #007BFF;
            margin: 10px 0;
        }
        .info {
            font-size: 13px;
            color: #333;
            line-height: 1.6;
            margin-top: 10px;
        }
        .qr {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }
        .footer {
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="card">
        {{-- Logo sekolah --}}
        <img src="{{ public_path('logo-sekolah.png') }}" alt="Logo" class="logo">

        <div class="title">
            KARTU PELAJAR<br>SMK CONTOH NEGERI
        </div>

        <div class="info">
            <strong>Nama:</strong> {{ $student->name }}<br>
            <strong>NISN:</strong> {{ $student->nisn ?? '-' }}<br>
            <strong>Kelas:</strong> {{ $student->classroom->name ?? '-' }}<br>
            <strong>TTL:</strong> {{ $student->birth_place }}, {{ $student->birth_date }}<br>
            <strong>Alamat:</strong> {{ $student->address }}
        </div>

        <div class="qr">
            <img src="data:image/png;base64, {!! DNS2D::getBarcodePNG($student->qr_code_id, 'QRCODE') !!}" width="100">
        </div>

        <div class="footer">
            Kartu ini digunakan sebagai identitas resmi siswa.
        </div>
    </div>
</body>
</html>
