<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $nama_kelas = $_POST['nama_kelas'];
    $jurusan    = $_POST['jurusan'];

    mysqli_query($koneksi, "
        INSERT INTO kelas (nama_kelas, jurusan)
        VALUES ('$nama_kelas', '$jurusan')
    ");

    echo "<script>
            alert('Kelas berhasil ditambahkan!');
            window.location='kelas.php';
          </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kelas</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f4f7;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        .kembali {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="kelas.php" class="kembali">
        ← Kembali
    </a>

    <h2>🏫 Tambah Kelas</h2>

    <form method="POST">

        <label>Nama Kelas</label>

        <input type="text"
               name="nama_kelas"
               placeholder="Contoh: XII RPL"
               required>

        <label>Jurusan</label>

        <input type="text"
               name="jurusan"
               placeholder="Contoh: RPL"
               required>

        <button type="submit" name="simpan">
            💾 Simpan Kelas
        </button>

    </form>

</div>

</body>
</html>z