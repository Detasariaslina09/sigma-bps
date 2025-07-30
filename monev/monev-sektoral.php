<?php
// Mulai session
session_start();

// Periksa status login - jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cek status login dan role
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Monev Tim Nerwilis - BPS Kota Bandar Lampung</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Monitoring dan Evaluasi Tim Neraca dan Analisis Statistik BPS Kota Bandar Lampung" />
<meta name="author" content="" />
<!-- css -->
<link href="../css/bootstrap.min.css" rel="stylesheet" />
<link href="../css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="../css/jcarousel.css" rel="stylesheet" />
<link href="../css/flexslider.css" rel="stylesheet" />
<link href="../css/style.css" rel="stylesheet" />
<link href="../css/custom-styles.css" rel="stylesheet" />
<link href="../css/font-awesome.css" rel="stylesheet" />
<link href="../css/monev-styles.css" rel="stylesheet" />
 
</head>
<body>
<div id="wrapper" style="margin-left: 0; width: 100%;">
    <section id="content">
        <div class="container">
            <!-- Header & Back Button -->
            <div class="row header-back">
                <div class="col-md-6">
                    <h2><i class="fa fa-bar-chart"></i> Monitoring dan Evaluasi Tim Statistik Sektoral</h2>
                </div>
                <div class="col-md-6 text-right">
                    <a href="../monev.php" class="btn-back"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Tim</a>
                </div>
            </div>
            
            <!-- Apps List -->
            <div class="service-category-container">
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Desa Cantik</h3>
                            <ul class="service-links-list">
                                <li><a href="https://docs.google.com/spreadsheets/d/1h8T41Wu0bHTcAlb1cWxz9kGMwausHQS7NJAKmUhk1lw/edit?usp=sharing" target="_blank">Tim Kelurahan</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Rencana Kerja PPS</h3>
                            <ul class="service-links-list">
                                <li><a href="https://docs.google.com/spreadsheets/d/15NbHr8DnSPx6zMxN_MKSziO1-tFknYH2frJag7FpQc4/edit?usp=sharing" target="_blank">Jadwal Pembinaan Statistik Sektoral Tahun 2025</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h4>Badan Pusat Statistik Kota Bandar Lampung</h4>
                    <address>
                        Jl. Sutan Syahrir No. 30, Pahoman, Bandar Lampung, 35215<br>
                        Telp. (0721) 255980. Mailbox : bps1871@bps.go.id
                    </address>
                    <div class="copyright">
                        <div class="text-center">
                            <p>Hak Cipta © 2025 Badan Pusat Statistik Kota Bandar Lampung</p>
                            <p>Semua Hak Dilindungi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>
<a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
<script src="../js/jquery.js"></script>
<script src="../js/jquery.easing.1.3.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery.fancybox.pack.js"></script>
<script src="../js/jquery.fancybox-media.js"></script> 
<script src="../js/portfolio/jquery.quicksand.js"></script>
<script src="../js/portfolio/setting.js"></script>
<script src="../js/jquery.flexslider.js"></script>
<script src="../js/animate.js"></script>
<script src="../js/custom.js"></script>
<script src="../js/monev-scripts.js"></script>
</body>
</html> 