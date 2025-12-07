<?php
require('fpdf.php');
include "database.php";

if (!isset($_GET['bulan'])) {
    $bulan = date('Y-m');
} else {
    $bulan = $_GET['bulan'];
}
if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
    die("Format bulan tidak valid.");
}

$bulan_sql = mysqli_real_escape_string($conn, $bulan);

$query = "
    SELECT p.*, g.nama_pegawai, b.nama_barang
    FROM permintaan p
    LEFT JOIN pegawai g ON p.NIP = g.NIP
    LEFT JOIN barang b ON p.id_barang = b.id_barang
    WHERE DATE_FORMAT(p.waktu_permintaan, '%Y-%m') = '$bulan_sql'
    ORDER BY p.waktu_permintaan ASC
";
$res = mysqli_query($conn, $query);

// Buat PDF
class PDF extends FPDF {
    // header
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,8,'LAPORAN PERMINTAAN BARANG BULANAN',0,1,'C');
        $this->Ln(2);
    }
}

$pdf = new PDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

$pdf->Cell(40,7,'Bulan',0,0);
$pdf->Cell(0,7,": $bulan",0,1);

$pdf->Ln(4);

// Tabel header
$pdf->SetFont('Arial','B',9);
$pdf->Cell(10,8,'No',1,0,'C');
$pdf->Cell(30,8,'NIP',1,0,'C');
$pdf->Cell(60,8,'Nama Pegawai',1,0,'C');
$pdf->Cell(60,8,'Barang',1,0,'C');
$pdf->Cell(25,8,'Jumlah',1,0,'C');
$pdf->Cell(55,8,'Waktu Permintaan',1,1,'C');

$pdf->SetFont('Arial','',9);
$no = 1;
while ($row = mysqli_fetch_assoc($res)) {
    $pdf->Cell(10,7,$no++,1,0,'C');
    $pdf->Cell(30,7,$row['NIP'],1,0,'C');
    $pdf->Cell(60,7,substr($row['nama_pegawai'],0,40),1,0,'L');
    $pdf->Cell(60,7,substr($row['nama_barang'],0,40),1,0,'L');
    $pdf->Cell(25,7,$row['jumlah_permintaan'],1,0,'C');
    $pdf->Cell(55,7,$row['waktu_permintaan'],1,1,'C');
}

$pdf->Output('D', "laporan_permintaan_$bulan.pdf");
exit;
?>