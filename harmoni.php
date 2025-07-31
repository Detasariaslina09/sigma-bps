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
<title>Layanan Harmoni</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Layanan Harmoni untuk menyampaikan keluhan dan saran" />
<meta name="author" content="http://webthemez.com" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
<link href="css/harmoni.css" rel="stylesheet" />

</head>
<body>
    <?php include_once 'includes/sidebar.php'; ?>
<div id="wrapper">
    <section id="content">
    <div class="container">
        <div class="section-title">
            <h2>Layanan Harmoni</h2>
            <p>Platform Aspirasi dan Komunikasi BPS Kota Bandar Lampung</p>
        </div>
        
        <div class="harmoni-container">
            <img src="img/pengaduan.png" alt="Layanan Harmoni" class="harmoni-image">
            
            <div class="harmoni-content">
                <h3>Hamparan Saran dan Komentar untuk Kantor Nyaman BPS Kota Bandar Lampung</h3>
                <p>Harmoni adalah platform komunikasi terpadu yang disediakan oleh BPS Kota Bandar Lampung untuk memfasilitasi aspirasi, saran, dan komunikasi antar pegawai. Platform ini dirancang untuk menciptakan lingkungan kerja yang harmonis dan kolaboratif.</p>
                
                <div class="harmoni-cta">
                    <a href="http://s.bps.go.id/Harmony1871" target="_blank" class="btn-harmoni">
                        <i class="fa fa-comments"></i><span>Akses Harmoni</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php include_once 'includes/footer.php'; ?>
    </div>

    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
<script src="js/jquery.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.fancybox.pack.js"></script>
<script src="js/jquery.fancybox-media.js"></script> 
<script src="js/portfolio/jquery.quicksand.js"></script>
<script src="js/portfolio/setting.js"></script>
<script src="js/jquery.flexslider.js"></script>
<script src="js/animate.js"></script>
<script src="js/custom.js"></script>
<script src="js/harmoni.js"></script>
</body>
</html>