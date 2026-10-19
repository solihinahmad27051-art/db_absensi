<?php
include "koneksi.php";

$query = mysqli_query($koneksi, "
    SELECT siswa.*, kelas.nama_kelas
    FROM siswa
    LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Siswa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            padding: 30px;
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

        .tambah {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        .edit {
            background: #ffc107;
            color: black;
            padding: 6px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .hapus {
            background: #dc3545;
            color: white;
            padding: 6px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .kembali {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>👨‍🎓 Data Siswa</h2>

    <a href="dashboard.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <br>

    <a href="tambah_siswa.php" class="tambah">
        + Tambah Siswa
    </a>

    <table>

        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= $data['nis']; ?></td>
            <td><?= $data['nama_siswa']; ?></td>
            <td><?= $data['nama_kelas']; ?></td>
            <td><?= $data['alamat']; ?></td>

            <td>
                <a href="edit_siswa.php?id=<?= $data['id_siswa']; ?>"
                   class="edit">
                    Edit
                </a>

                <a href="hapus_siswa.php?id=<?= $data['id_siswa']; ?>"
                   class="hapus"
                   onclick="return confirm('Yakin ingin menghapus siswa ini?')">
                    Hapus
                </a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>