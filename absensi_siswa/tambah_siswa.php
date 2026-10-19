<?php
include "koneksi.php";

// Jika tombol Simpan ditekan
if (isset($_POST['simpan'])) {

    $nis        = $_POST['nis'];
    $nama_siswa = $_POST['nama_siswa'];
    $id_kelas   = $_POST['id_kelas'];
    $alamat     = $_POST['alamat'];

    $query = mysqli_query($koneksi, "
        INSERT INTO siswa (nis, nama_siswa, id_kelas, alamat)
        VALUES ('$nis', '$nama_siswa', '$id_kelas', '$alamat')
    ");

    if ($query) {
        echo "<script>
                alert('Data siswa berhasil ditambahkan!');
                window.location='siswa.php';
              </script>";
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
}

// Mengambil data kelas
$kelas = mysqli_query($koneksi, "SELECT * FROM kelas");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Siswa</title>

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
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
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
            resize: none;
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

        button:hover {
            background: #0056b3;
        }

        .kembali {
            display: inline-block;
            margin-bottom: 10px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>

<body>

<div class="container">

    <a href="siswa.php" class="kembali">
        ← Kembali ke Data Siswa
    </a>

    <h2>👨‍🎓 Tambah Siswa</h2>

    <form method="POST">

        <label>NIS</label>
        <input type="text" name="nis" placeholder="Masukkan NIS" required>

        <label>Nama Siswa</label>
        <input type="text" name="nama_siswa" placeholder="Masukkan nama siswa" required>

        <label>Kelas</label>
        <select name="id_kelas" required>

            <option value="">-- Pilih Kelas --</option>

            <?php while ($data = mysqli_fetch_assoc($kelas)) { ?>

                <option value="<?= $data['id_kelas']; ?>">
                    <?= $data['nama_kelas']; ?>
                </option>

            <?php } ?>

        </select>

        <label>Alamat</label>
        <textarea name="alamat" placeholder="Masukkan alamat" required></textarea>

        <button type="submit" name="simpan">
            💾 Simpan Siswa
        </button>

    </form>

</div>

</body>
</html>