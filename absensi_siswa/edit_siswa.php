<?php
include "koneksi.php";

$id = $_GET['id'];

// Mengambil data siswa
$query = mysqli_query($koneksi, "
    SELECT * FROM siswa
    WHERE id_siswa = '$id'
");

$data = mysqli_fetch_assoc($query);

// Jika tombol Update ditekan
if (isset($_POST['update'])) {

    $nis        = $_POST['nis'];
    $nama_siswa = $_POST['nama_siswa'];
    $id_kelas   = $_POST['id_kelas'];
    $alamat     = $_POST['alamat'];

    mysqli_query($koneksi, "
        UPDATE siswa SET
            nis = '$nis',
            nama_siswa = '$nama_siswa',
            id_kelas = '$id_kelas',
            alamat = '$alamat'
        WHERE id_siswa = '$id'
    ");

    echo "<script>
            alert('Data siswa berhasil diubah!');
            window.location='siswa.php';
          </script>";
}

// Mengambil data kelas
$kelas = mysqli_query($koneksi, "SELECT * FROM kelas");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Edit Siswa</title>

    <style>

        body {
            font-family: Arial, sans-serif;
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

        input, select, textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        textarea {
            height: 80px;
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
            cursor: pointer;
        }

        .kembali {
            text-decoration: none;
            color: #007bff;
        }

    </style>

</head>

<body>

<div class="container">

    <a href="siswa.php" class="kembali">
        ← Kembali
    </a>

    <h2>✏️ Edit Siswa</h2>

    <form method="POST">

        <label>NIS</label>

        <input type="text"
               name="nis"
               value="<?= $data['nis']; ?>"
               required>


        <label>Nama Siswa</label>

        <input type="text"
               name="nama_siswa"
               value="<?= $data['nama_siswa']; ?>"
               required>


        <label>Kelas</label>

        <select name="id_kelas" required>

            <option value="">-- Pilih Kelas --</option>

            <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>

                <option value="<?= $k['id_kelas']; ?>"
                    <?= ($k['id_kelas'] == $data['id_kelas']) ? 'selected' : ''; ?>>

                    <?= $k['nama_kelas']; ?>

                </option>

            <?php } ?>

        </select>


        <label>Alamat</label>

        <textarea name="alamat" required><?= $data['alamat']; ?></textarea>


        <button type="submit" name="update">
            💾 Update Data
        </button>

    </form>

</div>

</body>
</html>