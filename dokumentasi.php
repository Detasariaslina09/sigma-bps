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
<title>Dokumentasi Rapat - BPS Kota Bandar Lampung</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Dokumentasi foto kegiatan rapat dan notulensi" />
<meta name="author" content="" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
<link href="css/pricing.css" rel="stylesheet" />

</head>
<body>
    <?php include_once 'includes/sidebar.php'; ?>
    <div id="wrapper">
    	<section id="content">
    	<div class="container">	 
    		<div class="section-title">
    			<h2>Dokumentasi Kegiatan</h2>
    			<p>Foto Kegiatan dan Notulensi Rapat</p>
    		</div>
    		
    		<div class="row category-container">
                <!-- Dokumentasi -->
                <div class="col-md-3">
                    <div class="category-item">
                        <div class="category-image" style="background-image: url('img/doc/dokument.png');"></div>
                        <div class="category-content">
                            <h3>Dokumentasi Foto</h3>
                            <p>Dokumentasi kegiatan BPS Kota Bandar Lampung</p>
                            <a href="https://drive.google.com/drive/folders/1QfSLRJ-h87sJojPMxbXgsHWqO3knwr5Z" target="_blank" class="btn-category">Lihat <i class="fa fa-camera"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Notulensi -->
                <div class="col-md-3">
                    <div class="category-item">
                        <div class="category-image" style="background-image: url('img/doc/notulen.png');"></div>
                        <div class="category-content">
                            <h3>Notulensi Rapat</h3>
                            <p>Notulensi dan hasil-hasil rapat BPS Kota Bandar Lampung</p>
                            <a href="https://drive.google.com/drive/folders/1OeEHKh1msHG8buJuFljlODQoDDJrYuOc" target="_blank" class="btn-category">Lihat <i class="fa fa-file-text"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Dokumentasi Humas -->
                <div class="col-md-3">
                    <div class="category-item">
                        <div class="category-image" style="background-image: url('img/doc/humas.png');"></div>
                        <div class="category-content">
                            <h3>Dokumentasi Humas</h3>
                            <p>Dokumentasi kegiatan kehumasan dan publikasi</p>
                            <a href="https://license365bps-my.sharepoint.com/:f:/g/personal/hadys_license365bps_onmicrosoft_com/EsUSJqITD7ZDutg_Bcd-UM8BU-oeINpzlblkvkZbg1U1eg?e=EikeAG" target="_blank" class="btn-category">Lihat <i class="fa fa-bullhorn"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Link Quotes -->
                <div class="col-md-3">
                    <div class="category-item">
                        <div class="category-image" style="background-image: url('img/doc/quote.png');"></div>
                        <div class="category-content">
                            <h3>Link Quotes</h3>
                            <p>Kumpulan quotes inspiratif untuk pegawai</p>
                            <a href="http://s.bps.go.id/recapquote1871" target="_blank" class="btn-category">Lihat <i class="fa fa-quote-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
    	</section>
    	<?php include_once 'includes/footer.php'; ?>
    </div>

    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
    <!-- javascript
        ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
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
    <script src="js/pricing.js"></script>
</body>
</html>