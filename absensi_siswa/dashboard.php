<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Absensi Siswa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f6fc;
            margin: 0;
        }

        .header {
            background: #007bff;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .icon {
            font-size: 40px;
        }

        .card h3 {
            margin-bottom: 5px;
        }

        @media (max-width: 600px) {
            .menu {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="header">
    <h1>📚 Aplikasi Absensi Siswa</h1>
    <p>Dashboard</p>
</div>

<div class="container">
    <div class="menu">

        <!-- Data Kelas di sebelah kiri -->
        <a href="kelas.php" class="card">
            <div class="icon">🏫</div>
            <h3>Data Kelas</h3>
            <p>Melihat data kelas</p>
        </a>

        <!-- Data Siswa di sebelah kanan -->
        <a href="siswa.php" class="card">
            <div class="icon">👨‍🎓</div>
            <h3>Data Siswa</h3>
            <p>Melihat data siswa</p>
        </a>

        <!-- Menu Absensi -->
        <a href="absensi.php" class="card">
            <div class="icon">✅</div>
            <h3>Absensi</h3>
            <p>Mencatat kehadiran siswa</p>
        </a>

        <!-- Menu Rekap Absensi -->
        <a href="rekap.php" class="card">
            <div class="icon">📋</div>
            <h3>Rekap Absensi</h3>
            <p>Melihat data kehadiran</p>
        </a>

    </div>
</div>

</body>
</html>