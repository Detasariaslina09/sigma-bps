<?php
// Mulai session
session_start();

// Periksa status login - jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include koneksi database
require_once 'koneksi.php';

// Fungsi untuk memeriksa koneksi database dan melakukan reconnect jika terputus
function check_connection($conn) {
    if (!$conn->ping()) {
        // Reconnect jika koneksi terputus
        $conn->close();
        $servername = "127.0.0.1";
        $username   = "root";
        $password   = "";
        $dbname     = "sigap";
        $port       = 3306;
        
        $conn = new mysqli($servername, $username, $password, $dbname, $port);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
    return $conn;
}

// Pastikan koneksi aktif
$conn = check_connection($conn);

// Ambil data layanan dan link dari database
$services = [];

// Query untuk mengambil semua layanan
$result = $conn->query("SELECT * FROM services ORDER BY id");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $service_id = $row['id'];
        $services[$service_id] = [
            'id' => $service_id,
            'name' => $row['name'],
            'links' => []
        ];
        
        // Query untuk mengambil link untuk layanan ini
        $links_result = $conn->query("SELECT * FROM service_link WHERE service_id = $service_id ORDER BY id");
        
        if ($links_result && $links_result->num_rows > 0) {
            while ($link_row = $links_result->fetch_assoc()) {
                $services[$service_id]['links'][] = [
                    'id' => $link_row['id'],
                    'title' => $link_row['link_title'],
                    'url' => $link_row['url']
                ];
            }
        }
    }
}

// Tutup koneksi database
$conn->close();

// Cek status login dan role
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Layanan - Sigap BPS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="" />
<meta name="author" content="http://webthemez.com" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
<link href="css/sidebar.css" rel="stylesheet" />
<link href="css/service-category.css" rel="stylesheet" />
 
</head>
<body>
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>
    
    <!-- Sidebar menu -->
    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/sigma.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <?php if ($is_logged_in): ?>
                <li><a href="monev.php">Monev</a></li>
                <li class="active"><a href="layanan.php">Layanan</a></li>
                <li><a href="dokumentasi.php">Dokumentasi</a></li>
                <li><a href="harmoni.php">Harmoni</a></li>
                <?php if ($is_admin): ?>
                    <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                    <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                    <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
                    <li class="admin-menu"><a href="admin-profil.php"><i class="fa fa-user"></i> Manajemen Profil</a></li>
                <?php endif; ?>
                <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>


<div id="wrapper">
	<section id="content">
		<div class="container">
			<!-- Bagian Gambar Utama -->
			<div class="row">
				<div class="col-md-12">
					<img src="img/kantorr.jpeg" class="service-image img-responsive" alt="Layanan Kami">
                    <div class="text-center">
                        <h2>Layanan BPS Kota Bandar Lampung</h2>
                        <p>Berikut adalah layanan-layanan yang tersedia di BPS Kota Bandar Lampung</p>
                    </div>
				</div>
			</div>
			
			<!-- Area untuk Layanan dengan format baru -->
			<div class="service-category-container">
                <?php foreach ($services as $service_id => $service): ?>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-info">
                            <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                            <ul class="service-links-list">
                                <?php foreach ($service['links'] as $link): ?>
                                <li><a href="<?php echo htmlspecialchars($link['url']); ?>"><?php echo htmlspecialchars($link['title']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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
<script src="js/auth.js"></script>
</body>
</html>