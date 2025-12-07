<?php 
include "database.php";

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date("m");
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date("Y");

$query = mysqli_query($conn, "
    SELECT 
        barang.nama_barang,
        SUM(permintaan.jumlah_barang) AS total_diambil
    FROM permintaan
    JOIN barang ON permintaan.id_barang = barang.id_barang
    WHERE MONTH(permintaan.tanggal_permintaan) = '$bulan'
      AND YEAR(permintaan.tanggal_permintaan) = '$tahun'
    GROUP BY permintaan.id_barang
    ORDER BY total_diambil DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2 style="text-align:center">Laporan Pengambilan Barang Bulanan</h2>

<form method="GET" class="form-search">
    <select name="bulan" class="search-input">
        <option value="1">Januari</option>
        <option value="2">Februari</option>
        <option value="3">Maret</option>
        <option value="4">April</option>
        <option value="5">Mei</option>
        <option value="6">Juni</option>
        <option value="7">Juli</option>
        <option value="8">Agustus</option>
        <option value="9">September</option>
        <option value="10">Oktober</option>
        <option value="11">November</option>
        <option value="12">Desember</option>
    </select>

    <input type="number" name="tahun" class="search-input" placeholder="Tahun" value="<?= $tahun ?>">

    <button type="submit" class="search-btn">Tampilkan</button>
</form>

<table class="barang-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Total Diambil</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td><?= $row['total_diambil']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<button onclick="window.print()" class="search-btn" style="margin:20px auto; display:block;">
    Cetak Laporan
</button>

</body>
</html>
