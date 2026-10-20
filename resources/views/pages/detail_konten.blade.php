<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            padding: 40px;
            background: #f8f9fa;
            color: #333;
        }

        .wrapper {
            max-width: 800px;
            background: white;
            margin: 40px auto;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        h1 {
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            color: #1e293b;
            margin-top: 0;
        }

        .main-image {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content {
            font-size: 16px;
            text-align: justify;
            color: #475569;
            white-space: pre-line;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <a href="/" class="back-link">← Kembali ke Halaman Utama</a>
        <h1>{{ $judul }}</h1>

        @if ($gambar)
            <img src="{{ asset('storage/' . $gambar) }}" class="main-image" alt="Visual Konten">
        @endif

        <div class="content">
            {{ $isi_lengkap }}
        </div>
    </div>
</body>

</html>
