<?php
session_start();
include "database.php";

// tambah permingaan
if (isset($_POST['tambah'])) {

    $NIP = $_POST['NIP'];
    $id_barang = $_POST['id_barang'];
    $jumlah_permintaan = (int) $_POST['jumlah_permintaan'];

    // Cek stok barang saat ini
    $cek = mysqli_query($conn, "SELECT jumlah_barang FROM barang WHERE id_barang='$id_barang'");
    $stok = mysqli_fetch_assoc($cek)['jumlah_barang'];

    if ($stok < $jumlah_permintaan) {
        echo "<script>alert('Stok barang tidak cukup!'); window.location='daftar_permintaan.php';</script>";
        exit;
    }

    // Kurangi stok
    mysqli_query($conn, "
        UPDATE barang SET jumlah_barang = jumlah_barang - $jumlah_permintaan
        WHERE id_barang='$id_barang'
    ");

    // Tambah permintaan
    mysqli_query($conn, "
        INSERT INTO permintaan (NIP, id_barang, jumlah_permintaan)
        VALUES ('$NIP', '$id_barang', '$jumlah_permintaan')
    ");

    header("Location: daftar_permintaan.php");
    exit;
}

// hapus permintaan
if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    // Ambil jumlah permintaan & id_barang sebelum dihapus
    $data = mysqli_query($conn, "SELECT id_barang, jumlah_permintaan FROM permintaan WHERE id_permintaan='$id'");
    $row = mysqli_fetch_assoc($data);

    // Kembalikan stok
    mysqli_query($conn, "
        UPDATE barang SET jumlah_barang = jumlah_barang + {$row['jumlah_permintaan']}
        WHERE id_barang='{$row['id_barang']}'
    ");

    // Hapus permintaan
    mysqli_query($conn, "DELETE FROM permintaan WHERE id_permintaan='$id'");

    header("Location: daftar_permintaan.php");
    exit;
}

// edit permintaan
if (isset($_POST['edit'])) {

    $id = $_POST['id_permintaan'];
    $NIP = $_POST['NIP'];
    $id_barang_baru = $_POST['id_barang'];
    $jumlah_baru = (int) $_POST['jumlah_permintaan'];

    // Ambil data lama
    $lama = mysqli_query($conn, "SELECT * FROM permintaan WHERE id_permintaan='$id'");
    $old = mysqli_fetch_assoc($lama);

    $id_barang_lama = $old['id_barang'];
    $jumlah_lama = $old['jumlah_permintaan'];

    // 1. Kembalikan stok berdasarkan data lama
    mysqli_query($conn, "
        UPDATE barang SET jumlah_barang = jumlah_barang + $jumlah_lama
        WHERE id_barang='$id_barang_lama'
    ");

    // 2. Cek stok untuk barang baru
    $stok = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT jumlah_barang FROM barang WHERE id_barang='$id_barang_baru'
    "))['jumlah_barang'];

    if ($stok < $jumlah_baru) {
        echo "<script>alert('Stok barang tidak cukup untuk permintaan baru!'); window.location='daftar_permintaan.php';</script>";
        exit;
    }

    // 3. Kurangi stok sesuai jumlah baru
    mysqli_query($conn, "
        UPDATE barang SET jumlah_barang = jumlah_barang - $jumlah_baru
        WHERE id_barang='$id_barang_baru'
    ");

    // 4. Update permintaan
    mysqli_query($conn, "
        UPDATE permintaan SET
            NIP='$NIP',
            id_barang='$id_barang_baru',
            jumlah_permintaan='$jumlah_baru'
        WHERE id_permintaan='$id'
    ");

    header("Location: daftar_permintaan.php");
    exit;
}

// load data tampilan

$pegawai_res = mysqli_query($conn, "SELECT NIP, nama_pegawai FROM pegawai ORDER BY nama_pegawai");
$barang_res = mysqli_query($conn, "SELECT id_barang, nama_barang, jumlah_barang FROM barang ORDER BY nama_barang");

$permintaan_res = mysqli_query($conn, "
    SELECT p.*, g.nama_pegawai, b.nama_barang 
    FROM permintaan p
    LEFT JOIN pegawai g ON p.NIP = g.NIP
    LEFT JOIN barang b ON p.id_barang = b.id_barang
    ORDER BY p.waktu_permintaan DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Permintaan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="nav">
    <a class="nav-link" href="home.php">Beranda</a>
    <a class="nav-link" href="daftar_permintaan.php">Daftar Permintaan</a>
    <a class="nav-link" href="daftar_pegawai.php">Daftar Pegawai</a>
    <a class="nav-link" href="daftar_barang.php">Daftar Barang</a>
</nav>

<div class="container">

<h2 class="title-page">Daftar Permintaan</h2>

<!-- FORM TAMBAH -->
<div class="card">
    <h3 class="card-title">Tambah Permintaan</h3>
    <form action="" method="POST" class="form-grid">

        <div class="form-group">
            <label>Nama Pegawai:</label>
            <select name="NIP" required>
                <option value="">-- Pilih Pegawai --</option>
                <?php while ($pg = mysqli_fetch_assoc($pegawai_res)) : ?>
                    <option value="<?= $pg['NIP']; ?>"><?= htmlspecialchars($pg['nama_pegawai']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Nama Barang:</label>
            <select name="id_barang" required>
                <option value="">-- Pilih Barang --</option>
                <?php mysqli_data_seek($barang_res, 0); ?>
                <?php while ($br = mysqli_fetch_assoc($barang_res)) : ?>
                    <option value="<?= $br['id_barang']; ?>">
                        <?= htmlspecialchars($br['nama_barang']); ?> — Stok: <?= $br['jumlah_barang']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah Permintaan:</label>
            <input type="number" name="jumlah_permintaan" min="1" required>
        </div>

        <div class="form-group full">
            <button type="submit" name="tambah" class="btn-primary">Tambah Permintaan</button>
        </div>
    </form>
</div>

<!-- CETAK -->
<div class="action-row">

    <form id="pdfForm" action="generate_pdf.php" method="GET">
        <input type="hidden" name="bulan" value="<?= date('Y-m'); ?>">
        <button class="btn-primary">Cetak PDF</button>
    </form>
</div>

<!-- TABEL -->
<div class="table-wrapper">
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama Pegawai</th>
            <th>Barang</th>
            <th>Jumlah</th>
            <th>Waktu</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    <?php $no = 1; ?>
    <?php while ($row = mysqli_fetch_assoc($permintaan_res)) : ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['NIP']; ?></td>
            <td><?= htmlspecialchars($row['nama_pegawai']); ?></td>
            <td><?= htmlspecialchars($row['nama_barang']); ?></td>
            <td><?= $row['jumlah_permintaan']; ?></td>
            <td><?= $row['waktu_permintaan']; ?></td>
            <td>
                <button class="btn-edit" 
                    onclick="openEdit(
                        <?= $row['id_permintaan']; ?>,
                        '<?= addslashes($row['NIP']); ?>',
                        '<?= addslashes($row['id_barang']); ?>',
                        <?= $row['jumlah_permintaan']; ?>
                    )">Edit</button>

                <a class="btn-delete" 
                   href="daftar_permintaan.php?hapus=<?= $row['id_permintaan']; ?>" 
                   onclick="return confirm('Hapus permintaan ini?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>
</div>

</div>

<!-- POPUP EDIT -->
<div id="popupEdit" class="popup">
    <div class="popup-inner">

        <h3>Edit Permintaan</h3>
        <form action="" method="POST" class="form-grid">

            <input type="hidden" name="id_permintaan" id="edit_id">

            <div class="form-group">
                <label>Pegawai:</label>
                <select name="NIP" id="edit_NIP" required>
                    <option value="">-- Pilih Pegawai --</option>
                    <?php mysqli_data_seek($pegawai_res, 0);
                    while ($pg = mysqli_fetch_assoc($pegawai_res)) : ?>
                        <option value="<?= $pg['NIP']; ?>"><?= $pg['nama_pegawai']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Barang:</label>
                <select name="id_barang" id="edit_id_barang" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php mysqli_data_seek($barang_res, 0);
                    while ($br = mysqli_fetch_assoc($barang_res)) : ?>
                        <option value="<?= $br['id_barang']; ?>"><?= $br['nama_barang']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Jumlah:</label>
                <input type="number" name="jumlah_permintaan" id="edit_jumlah" required>
            </div>

            <div class="form-group full">
                <button class="btn-primary" name="edit">Simpan</button>
                <button type="button" class="btn-secondary" onclick="closeEdit()">Batal</button>
            </div>

        </form>
    </div>
</div>

<script>
function openEdit(id, NIP, id_barang, jumlah) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_NIP').value = NIP;
    document.getElementById('edit_id_barang').value = id_barang;
    document.getElementById('edit_jumlah').value = jumlah;
    document.getElementById('popupEdit').style.display = 'flex';
}
function closeEdit() {
    document.getElementById('popupEdit').style.display = 'none';
}
</script>

</body>
</html>