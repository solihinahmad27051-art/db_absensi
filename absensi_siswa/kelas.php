<?php
include "koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM kelas");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kelas</title>

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

    <h2>🏫 Data Kelas</h2>

    <a href="dashboard.php" class="kembali">
        ← Kembali ke Dashboard
    </a>

    <br>

    <a href="tambah_kelas.php" class="tambah">
        + Tambah Kelas
    </a>

    <table>

        <tr>
            <th>No</th>
            <th>ID Kelas</th>
            <th>Nama Kelas</th>
            <th>Jurusan</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= $data['id_kelas']; ?></td>
            <td><?= $data['nama_kelas']; ?></td>
            <td><?= $data['jurusan']; ?></td>

            <td>
                <a href="edit_kelas.php?id=<?= $data['id_kelas']; ?>"
                   class="edit">
                    Edit
                </a>

                <a href="hapus_kelas.php?id=<?= $data['id_kelas']; ?>"
                   class="hapus"
                   onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                    Hapus
                </a>
            </td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>