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
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link href="css/custom-styles.css" rel="stylesheet" />
<link href="css/font-awesome.css" rel="stylesheet" />
<link href="css/service-category.css" rel="stylesheet" />
 
</head>
<body>
    <?php include_once 'includes/sidebar.php'; ?>
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
<script src="js/auth.js"></script>
</body>
</html>