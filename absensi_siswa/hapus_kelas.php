<?php

include_once "koneksi.php";

if (isset($_GET['id'])) {

    $id_kelas = $_GET['id'];

    // 1. Hapus absensi berdasarkan siswa di kelas
    mysqli_query($koneksi, "
        DELETE a
        FROM absensi a
        INNER JOIN siswa s ON a.id_siswa = s.id_siswa
        WHERE s.id_kelas = '$id_kelas'
    ");

    // 2. Hapus siswa berdasarkan kelas
    mysqli_query($koneksi, "
        DELETE FROM siswa
        WHERE id_kelas = '$id_kelas'
    ");

    // 3. Hapus kelas
    mysqli_query($koneksi, "
        DELETE FROM kelas
        WHERE id_kelas = '$id_kelas'
    ");

    header("Location: kelas.php");
    exit;
}

?>