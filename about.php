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
 
<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<style>
    .service-category {
        margin-bottom: 40px;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }
    
    .service-category h3 {
        color: #ff9800;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #ccc;
    }
    
    .service-item {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .service-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .service-item img {
        max-width: 64px;
        margin-bottom: 15px;
    }
    
    .service-item h4 {
        color: #333;
        font-size: 18px;
        margin-top: 0;
    }
    
    .service-item p {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
    }
    
    .service-item .btn {
        background: #ff9800;
        color: white;
        border: none;
        padding: 5px 15px;
        transition: all 0.3s ease;
    }
    
    .service-item .btn:hover {
        background: #e65100;
    }
    
    /* Styling untuk kategori layanan baru */
    .service-category-container {
        margin-top: 30px;
    }
    
    .service-category-item {
        margin-bottom: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .service-category-content {
        display: flex;
        flex-direction: row;
    }
    
    .service-category-image {
        width: 120px;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #f5f5f5;
        padding: 20px;
    }
    
    .service-category-info {
        flex: 1;
        padding: 20px;
    }
    
    .service-category-info h3 {
        color: #ff9800;
        margin-top: 0;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #eee;
    }
    
    .service-links-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .service-links-list li {
        margin-bottom: 10px;
    }
    
    .service-links-list li a {
        color: #111 !important;
        text-decoration: none;
        display: block;
        padding: 8px 10px;
        background: #f9f9f9;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .service-links-list li a:hover {
        background: #ff9800;
        color: #111 !important;
        padding-left: 15px;
    }
    
    /* Mobile Menu Toggle Button */
    .mobile-menu-toggle {
        display: none;
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1000;
        background: #ff9800;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
    }
    
    /* Media query for mobile */
    @media (max-width: 768px) {
        .mobile-menu-toggle {
            display: block;
        }
        
        .sidebar {
            display: none;
        }
        
        .sidebar.open {
            display: block;
        }
        
        #wrapper {
            margin-left: 0;
        }
        
        .service-category-content {
            flex-direction: column;
        }
        
        .service-category-image {
            width: 100%;
            height: 100px;
        }
    }
</style>

</head>
<body>
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>
    
    <!-- Sidebar menu -->
    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/logoo.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="monev.php">Monev</a></li>
            <li class="active"><a href="about.php">Layanan</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <li><a href="pricing.php">Dokumentasi</a></li>
            <li><a href="contact.php">Pengaduan</a></li>
            
            <?php if ($is_admin): ?>
            <!-- Menu Admin - hanya ditampilkan jika role adalah admin -->
            <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
            <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
            <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
            <li class="admin-menu"><a href="admin-profil.php"><i class="fa fa-id-card"></i> Manajemen Profil</a></li>
            <?php endif; ?>
            
            <?php if ($is_logged_in): ?>
                <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fa fa-sign-in"></i> Login</a></li>
            <?php endif; ?>
        </ul>
    </div>


<div id="wrapper">
	<section id="content">
		<div class="container">
			<!-- Bagian Gambar Utama -->
			<div class="row">
				<div class="col-md-12">
					<img src="img/slides/b.png" class="service-image img-responsive" alt="Layanan Kami">
                    <div class="text-center">
                        <h2>Layanan BPS Kota Bandar Lampung</h2>
                        <p>Berikut adalah layanan-layanan yang tersedia di BPS Kota Bandar Lampung</p>
                    </div>
				</div>
			</div>
			
			<!-- Area untuk Layanan dengan format baru -->
			<div class="service-category-container">
                <?php
                // Daftar gambar yang sesuai untuk masing-masing layanan
                $service_images = [
                    1 => 'img/layanan/satu.png',
                    2 => 'img/layanan/dua.png',
                    3 => 'img/layanan/tiga.png',
                    4 => 'img/layanan/empat.png'
                ];
                
                // Cek apakah gambar ada, jika tidak gunakan gambar default dari folder img
                foreach ($service_images as $id => $path) {
                    if (!file_exists($path)) {
                        switch ($id) {
                            case 1:
                                $service_images[$id] = 'img/satu.png';
                                break;
                            case 2:
                                $service_images[$id] = 'img/dua.png';
                                break;
                            case 3:
                                $service_images[$id] = 'img/tiga.png';
                                break;
                            case 4:
                                $service_images[$id] = 'img/empat.png';
                                break;
                        }
                    }
                }
                
                // Loop untuk setiap layanan
                foreach ($services as $service_id => $service):
                    $image_path = isset($service_images[$service_id]) ? $service_images[$service_id] : 'img/layanan/default.png';
                ?>
                <div class="service-category-item">
                    <div class="service-category-content">
                        <div class="service-category-image" style="background-image: url('<?php echo $image_path; ?>');"></div>
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
                    <div class="copyright">
                        <p>Hak Cipta © 2025 Badan Pusat Statistik Kota Bandar Lampung<br>
                        Semua Hak Dilindungi</p>
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
<script>
    // Cek apakah sudah login
    function checkLogin() {
        var isLoggedIn = sessionStorage.getItem('isLoggedIn');
        if (!isLoggedIn) {
            // Jika belum login, alihkan ke halaman utama
            window.location.href = 'index.php';
        } else {
            // Jika sudah login, cek role untuk menampilkan/sembunyikan menu admin
            showHideAdminMenu();
        }
    }
    
    // Fungsi untuk menampilkan/menyembunyikan menu admin berdasarkan role
    function showHideAdminMenu() {
        var role = sessionStorage.getItem('userRole');
        if (role === 'admin') {
            // Tampilkan menu admin
            $('.admin-menu').show();
        } else {
            // Sembunyikan menu admin
            $('.admin-menu').hide();
        }
    }
    
    $(document).ready(function() {
        // Sembunyikan menu admin secara default
        $('.admin-menu').hide();
        
        // Cek login saat halaman dimuat
        checkLogin();
        
        // Handle logout button
        $('.logout-link').on('click', function(e) {
            e.preventDefault();
            
            // Hapus session storage
            sessionStorage.removeItem('isLoggedIn');
            sessionStorage.removeItem('username');
            sessionStorage.removeItem('userRole');
            
            // Alihkan ke halaman utama
            window.location.href = 'logout.php';
        });
    });
</script>
</body>
</html>