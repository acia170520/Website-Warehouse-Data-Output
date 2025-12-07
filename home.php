<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Halaman Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="nav">

        <a class="nav-link" href="home.php">Beranda</a>
        <a class="nav-link" href="daftar_permintaan.php">Daftar Permintaan</a>
        <a class="nav-link" href="daftar_pegawai.php">Daftar Pegawai</a>
        <a class="nav-link" href="daftar_barang.php">Daftar Barang</a>

        <!-- PROFILE ICON -->
        <div class="profile-container">
            <div class="profile-icon" id="profileBtn">
                <?php echo strtoupper($_SESSION['full_name'][0]); ?>
            </div>

            <!-- POPUP PROFILE -->
            <div class="profile-popup" id="profilePopup">
                <div class="popup-header">
                    <div class="popup-icon">
                        <?php echo strtoupper($_SESSION['full_name'][0]); ?>
                    </div>
                    <p class="popup-username">
                        <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                    </p>
                </div>

                <a href="logout.php" class="logout-btn">Keluar</a>
            </div>
        </div>

    </nav>

    <center><img src="foto/main_sanbe.jpeg" class="mainsanbe"></center>

    <center>
        <h1 class="great">
            Selamat Datang <?php echo htmlspecialchars($_SESSION['full_name']); ?> di Sistem Warehouse Data Output!
        </h1>
    </center>

    <!-- FAQ SECTION -->
    <div class="faq-container">
        <h2 class="faq-title">FAQ (Pertanyaan yang Sering Diajukan)</h2>

        <div class="faq-item">
            <button class="faq-question">1. Apa tujuan dari Sistem Warehouse Data Output?</button>
            <div class="faq-answer">
                <p>Sistem ini digunakan untuk mengelola data permintaan barang gudang secara terstruktur.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">2. Bagaimana cara mengajukan permintaan barang?</button>
            <div class="faq-answer">
                <p>Anda bisa langsung datang ke tempat penyedia barang, lalu admin akan menyimpan data pengambilan.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">3. Siapa saja yang dapat mengakses sistem ini?</button>
            <div class="faq-answer">
                <p>Hanya pegawai terdaftar yang bisa login.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">4. Apakah ada batas pengambilan barang?</button>
            <div class="faq-answer">
                <p>Tergantung stok dan izin admin.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">5. Bagaimana memperbarui data barang?</button>
            <div class="faq-answer">
                <p>Dapat diperbarui melalui menu Daftar Barang.</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
<footer class="footer-home">
    <div class="footer-content">
        <div class="footer-left">
            <h3>Contact Center</h3>
            <p>📞 022-4207725</p>
            <p>📧 Email : info@sanbe-farma.com</p>
            <p>🌐 Website : www.sanbe-farma.com</p>
            <p>📍 Jalan Taman Sari No 10, Bandung, Indonesia</p>
        </div>

        <div class="footer-map">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.935878963228!2d107.6070974!3d-6.896548199999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e63f03e6c2b7%3A0x98e035a6687edee!2sPT%20Sanbe%20Farma!5e0!3m2!1sid!2sid!4v1730" 
                width="350" 
                height="200" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>

    <p class="footer-copy">© 2025 Sanbe Farma</p>
</footer>


<script>
// Script dropdown FAQ
const questions = document.querySelectorAll(".faq-question");
questions.forEach(q => {
    q.addEventListener("click", () => {
        q.classList.toggle("active");
        let answer = q.nextElementSibling;
        if (answer.style.maxHeight) {
            answer.style.maxHeight = null;
        } else {
            answer.style.maxHeight = answer.scrollHeight + "px";
        }
    });
});

// Script popup profile
const btn = document.getElementById("profileBtn");
const popup = document.getElementById("profilePopup");

btn.addEventListener("click", () => {
    popup.style.display = popup.style.display === "block" ? "none" : "block";
});

document.addEventListener("click", function(e) {
    if (!btn.contains(e.target) && !popup.contains(e.target)) {
        popup.style.display = "none";
    }
});
</script>

</body>
</html>
