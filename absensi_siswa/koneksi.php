<?php

$koneksi = mysqli_connect("localhost", "root", "", "db_absensi");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

?>