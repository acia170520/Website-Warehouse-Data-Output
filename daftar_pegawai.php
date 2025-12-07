<?php
session_start();
include "database.php";

// ========= TAMBAH PEGAWAI =========
if (isset($_POST['tambah'])) {
    $NIP = $_POST['NIP'];
    $nama_pegawai = $_POST['nama_pegawai'];
    $jabatan = $_POST['jabatan'];

    // Cek apakah NIP sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM pegawai WHERE NIP='$NIP'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NIP sudah terdaftar!'); window.location.href='daftar_pegawai.php';</script>";
        exit;
    }

    mysqli_query($conn, "INSERT INTO pegawai (NIP, nama_pegawai, jabatan)
                         VALUES ('$NIP', '$nama_pegawai', '$jabatan')");

    header("Location: daftar_pegawai.php");
    exit;
}

// ========= HAPUS PEGAWAI =========
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM pegawai WHERE NIP='$id'");
    header("Location: daftar_pegawai.php");
    exit;
}

// ========= EDIT PEGAWAI =========
if (isset($_POST['edit'])) {
    $NIP = $_POST['NIP'];
    $nama_pegawai = $_POST['nama_pegawai'];
    $jabatan = $_POST['jabatan'];

    mysqli_query($conn, "UPDATE pegawai SET
                        nama_pegawai='$nama_pegawai',
                        jabatan='$jabatan'
                        WHERE NIP='$NIP'");

    header("Location: daftar_pegawai.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Pegawai</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body-permintaan">

<nav class="nav">
    <a class="nav-link" href="home.php">Beranda</a>
    <a class="nav-link" href="daftar_permintaan.php">Daftar Permintaan</a>
    <a class="nav-link" href="daftar_pegawai.php">Daftar Pegawai</a>
    <a class="nav-link" href="daftar_barang.php">Daftar Barang</a>
</nav>

<h2 style="text-align:center; margin-top:20px; font-family:sans-serif; color:#224c95;">Daftar Pegawai</h2>

<!-- FORM TAMBAH -->
<form action="" method="POST" class="form-pegawai">
    <h3 class="form-title">Tambah Pegawai</h3>

    <label class="form-label">NIP:</label>
    <input type="text" name="NIP" class="form-input" required>

    <label class="form-label">Nama Pegawai:</label>
    <input type="text" name="nama_pegawai" class="form-input" required>

    <label class="form-label">Jabatan:</label>
    <input type="text" name="jabatan" class="form-input" required>

    <button type="submit" name="tambah" class="btn-submit">Tambah</button>
</form>

<hr>

<!-- TABEL -->
<table class="barang-table">
    <thead>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama Pegawai</th>
            <th>Jabatan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    <?php
    $no = 1;
    $data = mysqli_query($conn, "SELECT * FROM pegawai ORDER BY NIP DESC");
    while ($row = mysqli_fetch_assoc($data)) {
    ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['NIP']); ?></td>
            <td><?= htmlspecialchars($row['nama_pegawai']); ?></td>
            <td><?= htmlspecialchars($row['jabatan']); ?></td>

            <!-- BUTTON EDIT DAN HAPUS-->
            <td>
                <button class="btn-edit" onclick="editPegawai('<?= $row['NIP']; ?>','<?= htmlspecialchars($row['nama_pegawai']); ?>','<?= $row['jabatan']; ?>')">Edit</button>
                <a class="btn-hapus" href="daftar_pegawai.php?hapus=<?= $row['NIP']; ?>" onclick="return confirm('Hapus pegawai ini?')">Hapus</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<!-- POPUP EDIT -->
<div id="popupEdit" class="popup-overlay">
    <form action="" method="POST" class="popup-box">
        <h3 class="popup-title">Edit Pegawai</h3>

        <label class="popup-label">NIP:</label>
        <input type="number" name="NIP" id="edit_id" readonly required>

        <label class="popup-label">Nama Pegawai:</label>
        <input type="text" name="nama_pegawai" id="edit_nama" required>

        <label class="popup-label">Jabatan:</label>
        <input type="text" name="jabatan" id="edit_jabatan" required>

        <button type="submit" name="edit" class="btn-save btn-equal">Simpan</button>
        <button type="button" onclick="closeEdit()" class="btn-cancel btn-equal">Batal</button>
    </form>
</div>

<script>
function editPegawai(NIP, nama, jabatan) {
    document.getElementById("edit_id").value = NIP;
    document.getElementById("edit_nama").value = nama;
    document.getElementById("edit_jabatan").value = jabatan;
    document.getElementById("popupEdit").style.display = "flex";
}
function closeEdit() {
    document.getElementById("popupEdit").style.display = "none";
}
</script>

</body>
</html>
