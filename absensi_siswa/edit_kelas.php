<?php
include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "
    SELECT * FROM kelas
    WHERE id_kelas = '$id'
");

$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {

    $nama_kelas = $_POST['nama_kelas'];
    $jurusan    = $_POST['jurusan'];

    mysqli_query($koneksi, "
        UPDATE kelas SET
            nama_kelas = '$nama_kelas',
            jurusan = '$jurusan'
        WHERE id_kelas = '$id'
    ");

    echo "<script>
            alert('Data kelas berhasil diubah!');
            window.location='kelas.php';
          </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kelas</title>

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

    <h2>✏️ Edit Kelas</h2>

    <form method="POST">

        <label>Nama Kelas</label>

        <input type="text"
               name="nama_kelas"
               value="<?= $data['nama_kelas']; ?>"
               required>

        <label>Jurusan</label>

        <input type="text"
               name="jurusan"
               value="<?= $data['jurusan']; ?>"
               required>

        <button type="submit" name="update">
            💾 Update Kelas
        </button>

    </form>

</div>

</body>
</html>