<?php
session_start();
include "database.php";

// Tambah barang
if (isset($_POST['tambah'])) {
    $nama_barang   = $_POST['nama_barang'];
    $jumlah_barang = $_POST['jumlah_barang'];

    mysqli_query($conn, "INSERT INTO barang (nama_barang, jumlah_barang)
                         VALUES ('$nama_barang', '$jumlah_barang')");

    header("Location: daftar_barang.php");
    exit;
}

// hapus barang
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM barang WHERE id_barang='$id'");
    header("Location: daftar_barang.php");
    exit;
}

// edit barang
if (isset($_POST['edit'])) {
    $id            = $_POST['id_barang'];
    $nama_barang   = $_POST['nama_barang'];
    $jumlah_barang = $_POST['jumlah_barang'];

    mysqli_query($conn, "UPDATE barang SET
                        nama_barang='$nama_barang',
                        jumlah_barang='$jumlah_barang'
                        WHERE id_barang='$id'");

    header("Location: daftar_barang.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="nav">
    <a class="nav-link" href="home.php">Beranda</a>
    <a class="nav-link" href="daftar_permintaan.php">Daftar Permintaan</a>
    <a class="nav-link" href="daftar_pegawai.php">Daftar Pegawai</a>
    <a class="nav-link" href="daftar_barang.php">Daftar Barang</a>
</nav>

<h2 style="text-align:center; margin-top:20px; font-family:sans-serif; color:#224c95;">Daftar Barang</h2>

<!-- FORM TAMBAH -->
<form action="" method="POST" class="form-pegawai">
    <h3 class="form-title">Tambah Barang</h3>

    <label class="form-label">Nama Barang:</label>
    <input type="text" name="nama_barang" class="form-input" required>

    <label class="form-label">Jumlah Barang:</label>
    <input type="number" name="jumlah_barang" class="form-input" required>

    <button type="submit" name="tambah" class="btn-submit">Tambah</button>
</form>


<hr>

<!-- TABEL BARANG -->
<table class="barang-table">
    <thead>
        <tr>
            <th class="col-no">No</th>
            <th class="col-nama">Nama Barang</th>
            <th class="col-jumlah">Jumlah</th>
            <th class="col-aksi">Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php
    $no = 1;
    $data = mysqli_query($conn, "SELECT * FROM barang ORDER BY id_barang DESC");
    while ($row = mysqli_fetch_assoc($data)) {
    ?>
        <tr>
            <td class="td-no"><?= $no++; ?></td>

            <td class="td-nama"><?= htmlspecialchars($row['nama_barang']); ?></td>

            <td class="td-jumlah"><?= $row['jumlah_barang']; ?></td>

            <td class="td-aksi aksi-cell">
                <button class="btn-edit"
                    onclick="editBarang('<?= $row['id_barang']; ?>','<?= htmlspecialchars($row['nama_barang']); ?>','<?= $row['jumlah_barang']; ?>')">
                    Edit
                </button>

                <a class="btn-hapus" href="daftar_barang.php?hapus=<?= $row['id_barang']; ?>"
                    onclick="return confirm('Hapus barang ini?')">Hapus</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>


<!-- POPUP EDIT -->
<div id="popupEdit" class="popup-overlay">
    <form action="" method="POST" class="popup-box">

        <h3 class="popup-title">Edit Barang</h3>

        <input type="hidden" name="id_barang" id="edit_id">

        <label class="popup-label">Nama Barang:</label>
        <input type="text" name="nama_barang" id="edit_nama" class="popup-input" required>

        <label class="popup-label">Jumlah Barang:</label>
        <input type="number" name="jumlah_barang" id="edit_jumlah" class="popup-input" required>

        <div class="popup-buttons">
            <button type="submit" name="edit" class="btn-save btn-equal">Simpan</button>
            <button type="button" class="btn-cancel btn-equal" onclick="closeEdit()">Batal</button>
        </div>

    </form>
</div>

<script>
function editBarang(id, nama, jumlah) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_nama").value = nama;
    document.getElementById("edit_jumlah").value = jumlah;
    document.getElementById("popupEdit").style.display = "flex";
}

function closeEdit() {
    document.getElementById("popupEdit").style.display = "none";
}
</script>

</body>
</html>
