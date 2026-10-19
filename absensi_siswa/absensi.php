<?php
include "koneksi.php";

// Daftar jurusan
$daftar_jurusan = [
    'RPL',
    'DPB',
    'AKL',
    'HOTEL',
    'TKI',
    'TKKR',
    'ULW'
];

// Jika tombol Simpan ditekan
if (isset($_POST['simpan'])) {

    $tanggal = mysqli_real_escape_string(
        $koneksi,
        $_POST['tanggal'] ?? ''
    );

    $jurusan = $_POST['jurusan'] ?? '';

    // Validasi tanggal dan jurusan
    if (
        !in_array($jurusan, $daftar_jurusan, true) ||
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal) ||
        !checkdate(
            (int) substr($tanggal, 5, 2),
            (int) substr($tanggal, 8, 2),
            (int) substr($tanggal, 0, 4)
        )
    ) {
        echo "<script>
            alert('Tanggal atau jurusan tidak valid!');
            window.location='absensi.php';
        </script>";
        exit;
    }

    $jurusan = mysqli_real_escape_string($koneksi, $jurusan);

    if (
        isset($_POST['status']) &&
        is_array($_POST['status']) &&
        count($_POST['status']) > 0
    ) {

        $berhasil = true;

        foreach ($_POST['status'] as $id_siswa => $status) {

            $id_siswa = (int) $id_siswa;

            $status_valid = [
                'Hadir',
                'Izin',
                'Sakit',
                'Alpa'
            ];

            if (
                $id_siswa <= 0 ||
                !in_array($status, $status_valid, true)
            ) {
                $berhasil = false;
                break;
            }

            $status = mysqli_real_escape_string(
                $koneksi,
                $status
            );

            $query = mysqli_query($koneksi, "
                INSERT INTO absensi
                    (id_siswa, tanggal, jurusan, status)
                VALUES
                    ($id_siswa, '$tanggal', '$jurusan', '$status')
            ");

            if (!$query) {
                $berhasil = false;
                break;
            }
        }

        if ($berhasil) {
            echo "<script>
                alert('Absensi berhasil disimpan!');
                window.location='absensi.php';
            </script>";
            exit;
        } else {
            echo "<script>
                alert('Gagal menyimpan absensi. Periksa struktur tabel database!');
            </script>";
        }

    } else {
        echo "<script>
            alert('Silakan pilih status absensi siswa!');
        </script>";
    }
}

// Mengambil data siswa
$siswa = mysqli_query($koneksi, "
    SELECT
        siswa.id_siswa,
        siswa.nis,
        siswa.nama_siswa,
        kelas.nama_kelas
    FROM siswa
    LEFT JOIN kelas
        ON siswa.id_kelas = kelas.id_kelas
    ORDER BY siswa.nama_siswa ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Absensi Siswa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            overflow-x: auto;
        }

        h2 {
            text-align: center;
        }

        .kembali {
            text-decoration: none;
            color: #007bff;
        }

        .tanggal {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        input[type="date"],
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 7px;
        }

        .pilihan-jurusan {
            margin: 20px 0;
        }

        .pilihan-jurusan select {
            margin-top: 8px;
            min-width: 220px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        @media (max-width: 600px) {
            body {
                padding: 8px;
            }

            .container {
                padding: 12px;
            }

            table {
                min-width: 650px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h2>✅ Absensi Siswa</h2>

    <a href="dashboard.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <form method="POST">

        <div class="tanggal">
            <label>
                <b>Tanggal Absensi:</b>
            </label>
            <br>

            <input
                type="date"
                name="tanggal"
                value="<?= date('Y-m-d'); ?>"
                required
            >
        </div>

        <div class="pilihan-jurusan">
            <label for="jurusan">
                <b>Pilih Jurusan:</b>
            </label>
            <br>

            <select name="jurusan" id="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>

                <?php foreach ($daftar_jurusan as $j): ?>
                    <option value="<?= htmlspecialchars($j); ?>">
                        <?= htmlspecialchars($j); ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Status</th>
            </tr>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($siswa)) {
            ?>

            <tr>
                <td><?= $no++; ?></td>

                <td>
                    <?= htmlspecialchars($data['nis'] ?? ''); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_siswa'] ?? ''); ?>
                </td>

                <td>
                    <?= htmlspecialchars($data['nama_kelas'] ?? ''); ?>
                </td>

                <td>
                    <select
                        name="status[<?= (int) $data['id_siswa']; ?>]"
                        required
                    >
                        <option value="">Pilih</option>
                        <option value="Hadir">Hadir</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Alpa">Alpa</option>
                    </select>
                </td>
            </tr>

            <?php } ?>

        </table>

        <button type="submit" name="simpan">
            💾 Simpan Semua Absensi
        </button>

    </form>

</div>

</body>
</html>