<?php
// Mulai session
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect ke halaman login jika belum login atau bukan admin
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

// Inisialisasi variabel untuk pesan
$success_msg = '';
$error_msg = '';

// Proses jika ada request POST (simpan perubahan)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Mulai transaksi
        $conn->begin_transaction();
        
        // Iterasi melalui setiap layanan
        for ($i = 1; $i <= 4; $i++) {
            $service_name = $_POST["service{$i}_name"];
            
            // Update nama layanan
            $stmt = $conn->prepare("UPDATE services SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $service_name, $i);
            $stmt->execute();
            
            // Ambil semua link untuk layanan ini
            $stmt = $conn->prepare("SELECT id FROM service_link WHERE service_id = ?");
            $stmt->bind_param("i", $i);
            $stmt->execute();
            $result = $stmt->get_result();
            $existing_links = [];
            
            while ($row = $result->fetch_assoc()) {
                $existing_links[] = $row['id'];
            }
            
            // Proses untuk masing-masing link (maksimal 4 per layanan)
            for ($j = 1; $j <= 4; $j++) {
                $link_name = $_POST["service{$i}_link{$j}_name"];
                $link_url = $_POST["service{$i}_link{$j}_url"];
                
                // Jika link name kosong, lewati
                if (empty($link_name)) {
                    continue;
                }
                
                // Cek apakah link sudah ada dalam array existing_links
                if (isset($existing_links[$j-1])) {
                    $link_id = $existing_links[$j-1];
                    
                    // Update link yang sudah ada
                    $stmt = $conn->prepare("UPDATE service_link SET link_title = ?, url = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $link_name, $link_url, $link_id);
                    $stmt->execute();
                    
                    // Hapus dari array untuk menandai sudah diproses
                    unset($existing_links[$j-1]);
                } else {
                    // Tambahkan link baru
                    $stmt = $conn->prepare("INSERT INTO service_link (service_id, link_title, url) VALUES (?, ?, ?)");
                    $stmt->bind_param("iss", $i, $link_name, $link_url);
                    $stmt->execute();
                }
            }
            
            // Hapus link yang tidak lagi diperlukan (tidak ada dalam form)
            foreach ($existing_links as $link_id) {
                $stmt = $conn->prepare("DELETE FROM service_link WHERE id = ?");
                $stmt->bind_param("i", $link_id);
                $stmt->execute();
            }
        }
        
        // Commit transaksi
        $conn->commit();
        $success_msg = "Perubahan berhasil disimpan";
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $conn->rollback();
        $error_msg = "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Ambil data layanan dan link dari database
$services = [];

// Query untuk mengambil semua layanan
$result = $conn->query("SELECT * FROM services ORDER BY id");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $service_id = $row['id'];
        $services[$service_id] = [
            'id' => $service_id,
            'name' => $row['name'],
            'links' => []
        ];
        
        // Query untuk mengambil link untuk layanan ini
        $links_result = $conn->query("SELECT * FROM service_link WHERE service_id = $service_id ORDER BY id");
        
        if ($links_result->num_rows > 0) {
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manajemen Layanan - BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Halaman Manajemen Layanan - BPS Kota Bandar Lampung" />
    <meta name="author" content="" />
    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    
    <script>
        // Simpan informasi login di sessionStorage
        sessionStorage.setItem('isLoggedIn', 'true');
        sessionStorage.setItem('username', '<?php echo addslashes($_SESSION['username']); ?>');
        sessionStorage.setItem('userRole', '<?php echo addslashes($_SESSION['role']); ?>');
    </script>
    <style>
        .admin-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .admin-header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .admin-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-top: 0;
        }
        
        .admin-header p {
            color: #777;
            margin-bottom: 0;
        }
        
        .service-panel {
            margin-bottom: 30px;
            border: 1px solid #eee;
            border-radius: 5px;
        }
        
        .service-header {
            padding: 15px;
            background-color: #f5f5f5;
            border-bottom: 1px solid #eee;
            border-radius: 5px 5px 0 0;
        }
        
        .service-header h3 {
            margin: 0;
            font-size: 18px;
            color: #ff9800;
        }
        
        .service-body {
            padding: 15px;
        }
        
        .link-group {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #eee;
        }
        
        .link-group:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }

        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        
        .btn-save {
            background-color: #ff9800;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .btn-save:hover {
            background-color: #e65100;
        }
        
        /* Mobile menu toggle - REMOVED to use global styling */
        
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
        <a class="navbar-brand" href="index.php"><img src="img/sigma.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="monev.php">Monev</a></li>
            <li><a href="about.php">Layanan</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <li><a href="pricing.php">Dokumentasi</a></li>
            <li><a href="harmoni.php">Harmoni</a></li>
            
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <!-- Menu Admin - hanya ditampilkan jika role adalah admin -->
                <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                <li class="admin-menu active"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
                <li class="admin-menu"><a href="admin-profil.php"><i class="fa fa-user"></i> Manajemen Profil</a></li>
            <?php endif; ?>
            
            <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        </ul>
    </div>

    <div id="wrapper">
        <section id="content">
            <div class="container">
                <!-- Pesan sukses/error -->
                <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success" id="successAlert">
                    <i class="fa fa-check-circle"></i> <?php echo $success_msg; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger" id="errorAlert">
                    <i class="fa fa-exclamation-circle"></i> <?php echo $error_msg; ?>
                </div>
                <?php endif; ?>
                
                <div class="admin-content">
                    <div class="admin-header clearfix">
                        <div class="row">
                            <div class="col-md-12">
                                <h2><i class="fa fa-cogs"></i> Manajemen Layanan</h2>
                                <p>Kelola 4 layanan utama dan sublink yang ditampilkan di halaman Layanan</p>
                            </div>
                        </div>
                    </div>
                    
                    <form id="servicesForm" method="post" action="">
                        <?php
                        // Loop untuk setiap layanan
                        for ($i = 1; $i <= 4; $i++):
                            $service = isset($services[$i]) ? $services[$i] : ['name' => '', 'links' => []];
                        ?>
                        <!-- Layanan <?php echo $i; ?>: <?php echo htmlspecialchars($service['name']); ?> -->
                        <div class="service-panel">
                            <div class="service-header">
                                <h3>Layanan <?php echo $i; ?>: <?php echo htmlspecialchars($service['name']); ?></h3>
                            </div>
                            <div class="service-body">
                                <div class="form-group">
                                    <label>Nama Layanan</label>
                                    <input type="text" class="form-control" name="service<?php echo $i; ?>_name" value="<?php echo htmlspecialchars($service['name']); ?>" required>
                                </div>
                                
                                <?php
                                // Loop untuk setiap link (maksimal 4 per layanan)
                                for ($j = 1; $j <= 4; $j++):
                                    $link = isset($service['links'][$j-1]) ? $service['links'][$j-1] : ['title' => '', 'url' => ''];
                                ?>
                                <!-- Link <?php echo $j; ?> -->
                                <div class="link-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Link <?php echo $j; ?> - Nama</label>
                                                <input type="text" class="form-control" name="service<?php echo $i; ?>_link<?php echo $j; ?>_name" value="<?php echo htmlspecialchars($link['title']); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Link <?php echo $j; ?> - URL</label>
                                                <input type="text" class="form-control" name="service<?php echo $i; ?>_link<?php echo $j; ?>_url" value="<?php echo htmlspecialchars($link['url']); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endfor; ?>
                                
                            </div>
                        </div>
                        <?php endfor; ?>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-save btn-lg"><i class="fa fa-save"></i> Simpan Semua Perubahan</button>
                        </div>
                    </form>
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

    <!-- javascript -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <script>
        $(document).ready(function() {
            // Otomatis sembunyikan pesan alert setelah 3 detik
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        });
    </script>
</body>
</html> 