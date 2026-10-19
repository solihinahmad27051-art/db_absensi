<?php
include "koneksi.php";

// Filter bulan dan kelas
$bulan = $_GET['bulan'] ?? '2026-10';
$id_kelas = $_GET['id_kelas'] ?? '';
$edit = (int) ($_GET['edit'] ?? 0);

// Validasi bulan
if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan)) {
    $bulan = '2026-10';
}

$tahun = (int) substr($bulan, 0, 4);
$bulan_angka = (int) substr($bulan, 5, 2);

function e($nilai) {
    return htmlspecialchars((string)($nilai ?? ''), ENT_QUOTES, 'UTF-8');
}

// Simpan rekap manual
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id_siswa = (int) ($_POST['id_siswa'] ?? 0);
    $bulan_post = $_POST['bulan'] ?? '2026-10';
    $kelas_post = $_POST['id_kelas'] ?? '';

    if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $bulan_post)) {
        die("Format bulan tidak valid.");
    }

    $tahun_post = (int) substr($bulan_post, 0, 4);
    $bulan_post_angka = (int) substr($bulan_post, 5, 2);
    $jumlah = [];

    foreach (['hadir', 'sakit', 'izin', 'alpa'] as $jenis) {
        $nilai = filter_var(
            $_POST[$jenis] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($nilai === false || $nilai === null || $nilai < 0) {
            die("Jumlah absensi harus berupa angka nol atau lebih.");
        }

        $jumlah[$jenis] = $nilai;
    }

    // Pastikan siswa ada
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT id_siswa FROM siswa WHERE id_siswa = ?"
    );
    mysqli_stmt_bind_param($cek, "i", $id_siswa);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) === 0) {
        mysqli_stmt_close($cek);
        die("Siswa tidak ditemukan.");
    }
    mysqli_stmt_close($cek);

    // Simpan atau perbarui rekap siswa per bulan
    $sql_simpan = "
        INSERT INTO rekap_manual
        (id_siswa, bulan, tahun, hadir, sakit, izin, alpa)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            hadir = VALUES(hadir),
            sakit = VALUES(sakit),
            izin = VALUES(izin),
            alpa = VALUES(alpa)
    ";

    $stmt = mysqli_prepare($koneksi, $sql_simpan);

    mysqli_stmt_bind_param(
        $stmt,
        "iiiiiii",
        $id_siswa,
        $bulan_post_angka,
        $tahun_post,
        $jumlah['hadir'],
        $jumlah['sakit'],
        $jumlah['izin'],
        $jumlah['alpa']
    );

    if (!mysqli_stmt_execute($stmt)) {
        die("Gagal menyimpan rekap: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);

    header(
        "Location: rekap.php?bulan=" . urlencode($bulan_post) .
        "&id_kelas=" . urlencode((string)$kelas_post)
    );
    exit;
}

// Ambil daftar kelas
$kelas = mysqli_query(
    $koneksi,
    "SELECT * FROM kelas ORDER BY nama_kelas ASC"
);

if (!$kelas) {
    die("Gagal mengambil data kelas: " . mysqli_error($koneksi));
}

// Ambil data siswa dan rekap
$sql = "
    SELECT
        s.id_siswa,
        s.nis,
        s.nama_siswa,
        k.id_kelas,
        k.nama_kelas,
        r.hadir AS rekap_hadir,
        r.sakit AS rekap_sakit,
        r.izin AS rekap_izin,
        r.alpa AS rekap_alpa
    FROM siswa s
    LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
    LEFT JOIN rekap_manual r
        ON r.id_siswa = s.id_siswa
        AND r.bulan = $bulan_angka
        AND r.tahun = $tahun
";

if ($id_kelas !== '' && ctype_digit((string)$id_kelas)) {
    $kelas_aman = (int) $id_kelas;
    $sql .= " WHERE s.id_kelas = $kelas_aman";
}

$sql .= " ORDER BY s.nama_siswa ASC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query rekap gagal: " . mysqli_error($koneksi));
}

// Nama bulan
$nama_bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
    4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$judul_bulan = $nama_bulan[$bulan_angka] . ' ' . $tahun;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rekap Absensi Siswa</title>

<style>
* { box-sizing: border-box; }

body {
    font-family: Arial, sans-serif;
    background: #f2f4f7;
    padding: 20px;
    margin: 0;
    color: #26364a;
}

.container {
    background: white;
    padding: 25px;
    border-radius: 12px;
    max-width: 1200px;
    margin: auto;
}

h2 { text-align: center; }

.kembali {
    color: #007bff;
    text-decoration: none;
}

.filter {
    margin-top: 20px;
    padding: 15px;
    background: #f5f7fa;
    border-radius: 8px;
}

.filter input, .filter select {
    padding: 10px;
    margin: 6px 4px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

button, .btn {
    display: inline-block;
    padding: 10px 13px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    cursor: pointer;
    margin: 3px;
    color: white;
}

button { background: #007bff; }
.reset { background: #6c757d; }
.edit { background: #e99a17; }
.simpan { background: #198754; }

.table-wrap { overflow-x: auto; }

table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
    margin-top: 15px;
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

tbody tr:nth-child(even) { background: #f8faff; }

td input[type="number"] {
    width: 65px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.hadir { color: #198754; font-weight: bold; }
.sakit { color: #0d6efd; font-weight: bold; }
.izin { color: #d97706; font-weight: bold; }
.alpa { color: #dc3545; font-weight: bold; }

@media (max-width: 600px) {
    body { padding: 10px; }
    .container { padding: 12px; }
    .filter input, .filter select {
        width: 100%;
        margin: 6px 0;
    }
}
</style>
</head>

<body>
<div class="container">

<h2>📋 Rekap Absensi Siswa Bulanan</h2>

<a href="dashboard.php" class="kembali">← Kembali ke Dashboard</a>

<div class="filter">
    <form method="GET">
        <label><b>Bulan:</b></label>
        <input type="month" name="bulan" value="<?= e($bulan) ?>" required>

        <label><b>Kelas:</b></label>
        <select name="id_kelas">
            <option value="">Semua Kelas</option>
            <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                <option value="<?= e($k['id_kelas']) ?>"
                    <?= ((string)$id_kelas === (string)$k['id_kelas']) ? 'selected' : '' ?>>
                    <?= e($k['nama_kelas']) ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit">Tampilkan</button>
        <a href="rekap.php" class="btn reset">Reset</a>
    </form>
</div>

<h3>Rekap Bulan <?= e($judul_bulan) ?></h3>

<p>Klik <b>Edit</b> untuk memasukkan jumlah Hadir, Sakit, Izin, dan Alpa setiap siswa.</p>

<div class="table-wrap">
<table>
<thead>
<tr>
    <th>No</th>
    <th>NIS</th>
    <th>Nama Siswa</th>
    <th>Kelas</th>
    <th>Hadir</th>
    <th>Sakit</th>
    <th>Izin</th>
    <th>Alpa</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php
$no = 1;
$total_hadir = $total_sakit = $total_izin = $total_alpa = 0;

if (mysqli_num_rows($query) > 0) {
    while ($data = mysqli_fetch_assoc($query)) {
        // Rekap yang belum disimpan dimulai dari nol
        $hadir = $data['rekap_hadir'] !== null ? (int)$data['rekap_hadir'] : 0;
        $sakit = $data['rekap_sakit'] !== null ? (int)$data['rekap_sakit'] : 0;
        $izin  = $data['rekap_izin'] !== null ? (int)$data['rekap_izin'] : 0;
        $alpa  = $data['rekap_alpa'] !== null ? (int)$data['rekap_alpa'] : 0;

        $total_hadir += $hadir;
        $total_sakit += $sakit;
        $total_izin += $izin;
        $total_alpa += $alpa;

        $id = (int)$data['id_siswa'];
        $sedang_edit = ($edit === $id);
        $form_id = 'rekap_' . $id;
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= e($data['nis']) ?></td>
    <td style="text-align:left"><?= e($data['nama_siswa']) ?></td>
    <td><?= e($data['nama_kelas'] ?? 'Belum ada kelas') ?></td>

    <?php foreach (['hadir', 'sakit', 'izin', 'alpa'] as $jenis) {
        $nilai = $$jenis;
    ?>
    <td class="<?= e($jenis) ?>">
        <?php if ($sedang_edit) { ?>
            <input type="number"
                form="<?= e($form_id) ?>"
                name="<?= e($jenis) ?>"
                value="<?= $nilai ?>"
                min="0" max="9999" required>
        <?php } else { ?>
            <?= $nilai ?>
        <?php } ?>
    </td>
    <?php } ?>

    <td>
        <?php if ($sedang_edit) { ?>
            <form id="<?= e($form_id) ?>" method="POST">
                <input type="hidden" name="id_siswa" value="<?= $id ?>">
                <input type="hidden" name="bulan" value="<?= e($bulan) ?>">
                <input type="hidden" name="id_kelas" value="<?= e($id_kelas) ?>">
            </form>

            <button type="submit" form="<?= e($form_id) ?>"
                name="simpan" value="1" class="simpan">Simpan</button>

            <a class="btn reset"
                href="rekap.php?bulan=<?= e($bulan) ?>&id_kelas=<?= e($id_kelas) ?>">Batal</a>
        <?php } else { ?>
            <a class="btn edit"
                href="rekap.php?bulan=<?= e($bulan) ?>&id_kelas=<?= e($id_kelas) ?>&edit=<?= $id ?>">Edit</a>
        <?php } ?>
    </td>
</tr>
<?php
    }
} else {
?>
<tr>
    <td colspan="9">Belum ada data siswa.</td>
</tr>
<?php } ?>
</tbody>

<tfoot>
<tr>
    <th colspan="4">Total Seluruh Siswa</th>
    <th><?= $total_hadir ?></th>
    <th><?= $total_sakit ?></th>
    <th><?= $total_izin ?></th>
    <th><?= $total_alpa ?></th>
    <th>-</th>
</tr>
</tfoot>
</table>
</div>

</div>
</body>
</html>