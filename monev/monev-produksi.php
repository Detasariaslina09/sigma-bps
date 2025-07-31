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
<title>Monev Tim Produksi - BPS Kota Bandar Lampung</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Monitoring dan Evaluasi Tim Produksi BPS Kota Bandar Lampung" />
<meta name="author" content="" />
<!-- css -->
<link href="../css/bootstrap.min.css" rel="stylesheet" />
<link href="../css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="../css/jcarousel.css" rel="stylesheet" />
<link href="../css/flexslider.css" rel="stylesheet" />
<link href="../css/style.css" rel="stylesheet" />
<link href="../css/custom-styles.css" rel="stylesheet" />
<link href="../css/font-awesome.css" rel="stylesheet" />
<!-- CSS Terpusat Monev -->
<link href="../css/monev-styles.css" rel="stylesheet" />
 
</head>
<body>
<div id="wrapper" style="margin-left: 0; width: 100%;">
    <section id="content">
        <div class="container">
            <div class="row header-back">
                <div class="col-md-6">
                    <h2><i class="fa fa-industry"></i> Monitoring dan Evaluasi Tim Produksi</h2>
                </div>
                <div class="col-md-6 text-right">
                    <a href="../monev.php" class="btn-back"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Tim</a>
                </div>
            </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Monev Distribusi</h3>
                            <ul class="service-links-list">
                                <li><a href="https://docs.google.com/spreadsheets/d/13iYb9GNb_c_8It_TZdhxwufPri_EPavm/edit?usp=sharing&ouid=115956294358221492991&rtpof=true&sd=true" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="service-category-container">
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>KSA Padi</h3>
                            <ul class="service-links-list">
                                <li><a href="https://ksapro-manajemen.bps.go.id/" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>KSA Jagung</h3>
                            <ul class="service-links-list">
                                <li><a href="https://manajemen-ksapro.bps.go.id/" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Ubinan Padi</h3>
                            <ul class="service-links-list">
                                <li><a href="https://webapps.bps.go.id/simharga" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Ubinan Palawija</h3>
                            <ul class="service-links-list">
                                <li><a href="https://pengolahan.bps.go.id/produksi/TanamanPangan/" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Sistem sampel Ubinan Padi</h3>
                            <ul class="service-links-list">
                                <li><a href="https://iframe.bps.go.id/sampling/padi/" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3>Sistem sampel Ubinan Palawija</h3>
                            <ul class="service-links-list">
                                <li><a href="https://iframe.bps.go.id/sampling/palawija/" target="_blank">Buka Aplikasi</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
        </div>
    </section>
    <?php include_once '../includes/footer.php'; ?>
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