<?php
// Mulai session
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect ke halaman login jika belum login atau bukan admin
    header("Location: login.php");
    exit;
}

// Definisi variabel status login dan admin
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && $_SESSION['role'] === 'admin';

// Include koneksi database
require_once 'koneksi.php';

// Fungsi check_connection sudah dipindahkan ke includes/database.php
// dan tersedia melalui include koneksi.php

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
    <link href="css/admin-services.css" rel="stylesheet" />
    
    <!-- Meta tags for JavaScript -->
    <meta name="username" content="<?php echo htmlspecialchars($_SESSION['username']); ?>">
    <meta name="role" content="<?php echo htmlspecialchars($_SESSION['role']); ?>">
</head>
<body>
    <?php include_once 'includes/sidebar.php'; ?>
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
        <?php include_once 'includes/footer.php'; ?>
    </div>

    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/admin-services.js"></script>
</body>
</html> 