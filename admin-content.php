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

// Inisialisasi variabel
$success = false;
$error = '';

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = 1; // Selalu gunakan ID 1 untuk konten utama
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $imageName = '';

    try {
        // Pastikan koneksi aktif
        $conn = check_connection($conn);
        
        // Ambil informasi konten lama, termasuk gambar
        $oldContentQuery = $conn->query("SELECT * FROM konten WHERE id=1");
        $oldImage = 'konten.webp'; // Default jika tidak ada data sebelumnya
        
        if ($oldContentQuery && $oldContentQuery->num_rows > 0) {
            $oldContent = $oldContentQuery->fetch_assoc();
            $oldImage = $oldContent['image'];
        }
        
        // Cek jika ada upload gambar baru
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imgTmp = $_FILES['image']['tmp_name'];
            $imgName = basename($_FILES['image']['name']);
            $imgExt = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($imgExt, $allowed)) {
                $newImgName = 'konten_' . time() . '.' . $imgExt;
                $imgPath = 'img/' . $newImgName;
                
                if (move_uploaded_file($imgTmp, $imgPath)) {
                    $imageName = $newImgName;
                } else {
                    $error = 'Gagal upload gambar.';
                }
            } else {
                $error = 'Format gambar tidak didukung.';
            }
        }
        
        // Jika tidak ada error, lakukan update data
        if (!$error) {
            // Hapus semua data konten yang ada terlebih dahulu
            if (!$conn->query("DELETE FROM konten")) {
                throw new Exception("Error deleting old content: " . $conn->error);
            }
            
            // Tentukan gambar mana yang akan digunakan
            $imageToUse = '';
            
            if ($imageName) {
                // Jika ada gambar baru, gunakan gambar baru
                $imageToUse = $imageName;
            } else {
                // Jika tidak ada gambar baru, gunakan gambar lama
                $imageToUse = $oldImage;
            }
            
            // Simpan data baru ke database
            $sql = "INSERT INTO konten (id, title, description, image) VALUES (1, '$title', '$description', '$imageToUse')";
            
            if ($conn->query($sql)) {
                $success = true;
                
                // Jika berhasil menyimpan data baru dan ada gambar baru, hapus gambar lama
                if ($imageName && $oldImage != 'konten.webp' && $oldImage != $imageName) {
                    $oldImagePath = 'img/' . $oldImage;
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
            } else {
                $error = 'Gagal menyimpan perubahan: ' . $conn->error;
                
                // Jika gagal menyimpan data baru dan ada gambar baru, hapus gambar baru
                if ($imageName) {
                    $newImagePath = 'img/' . $imageName;
                    if (file_exists($newImagePath)) {
                        @unlink($newImagePath);
                    }
                }
            }
        }
    } catch (Exception $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

// Pastikan koneksi aktif sebelum mengambil data
$conn = check_connection($conn);

// Ambil data konten dari database
$id = 1;
$sql = "SELECT * FROM konten WHERE id=$id LIMIT 1";
$result = $conn->query($sql);

// Periksa apakah data konten ada
if ($result && $result->num_rows > 0) {
    $konten = $result->fetch_assoc();
    $title = $konten['title'] ?? '';
    $description = $konten['description'] ?? '';
    $image = $konten['image'] ?? 'konten.webp';
} else {
    // Jika tidak ada data, gunakan nilai default
    $title = 'Selamat Datang di BPS Kota Bandar Lampung';
    $description = 'Silakan isi konten halaman utama website.';
    $image = 'konten.webp';
    
    // Tambahkan data default ke database
    try {
        // Pastikan koneksi aktif
        $conn = check_connection($conn);
        
        $conn->query("DELETE FROM konten"); // Hapus data lama jika ada
        if (!$conn->query("INSERT INTO konten (id, title, description, image) VALUES (1, '$title', '$description', '$image')")) {
            $error = 'Gagal menambahkan data default: ' . $conn->error;
        }
    } catch (Exception $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

// Pastikan file gambar ada
if (!$image || !file_exists('img/' . $image)) {
    $image = 'konten.webp';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manajemen Konten - BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Halaman manajemen konten BPS Kota Bandar Lampung" />
    <meta name="author" content="BPS Kota Bandar Lampung" />
    
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/admin-content.css" rel="stylesheet" />
    
    <meta name="username" content="<?php echo htmlspecialchars($_SESSION['username']); ?>">
    <meta name="role" content="<?php echo htmlspecialchars($_SESSION['role']); ?>">
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <i class="fa fa-spinner fa-spin fa-4x"></i>
            <p>Memproses...</p>
        </div>
    </div>

    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>
    
    <div class="sidebar">
        <a class="navbar-brand" href="index.php"><img src="img/sigma.png" alt="logo"/></a>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Beranda</a></li>
            <li><a href="profil.php">Profil dan Roadmap</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <?php if ($is_logged_in): ?>
                <li><a href="monev.php">Monev</a></li>
                <li><a href="layanan.php">Layanan</a></li>
                <li><a href="dokumentasi.php">Dokumentasi</a></li>
                <li><a href="harmoni.php">Harmoni</a></li>
                <?php if ($is_admin): ?>
                    <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                    <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                    <li class="admin-menu active"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
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
                <div class="admin-content">
                    <div class="admin-header clearfix">
                        <div class="row">
                            <div class="col-md-6">
                                <h2><i class="fa fa-file-text"></i> Manajemen Konten</h2>
                                <p>Kelola konten halaman utama website</p>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" id="headerSaveBtn" form="contentForm" class="btn btn-add-user"><i class="fa fa-save"></i> Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                
                    <div class="alert alert-success" id="successAlert" style="display:<?php echo $success ? 'block' : 'none'; ?>">
                        <i class="fa fa-check-circle"></i> Konten berhasil disimpan.
                    </div>
                    <div class="alert alert-danger" id="errorAlert" style="display:<?php echo $error ? 'block' : 'none'; ?>">
                        <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                    
                    <form id="contentForm" method="post" action="admin-content.php" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="content_id" value="1">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title"><i class="fa fa-header"></i> Judul</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description"><i class="fa fa-align-left"></i> Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="8" required><?php echo htmlspecialchars($description); ?></textarea>
                                    <small class="text-muted">Deskripsi akan ditampilkan pada halaman utama website</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="image"><i class="fa fa-image"></i> Ganti Gambar</label>
                                    <div class="file-upload-wrapper">
                                        <div class="file-upload-button">
                                            <i class="fa fa-upload"></i> Pilih File Gambar
                                        </div>
                                        <input type="file" class="file-upload-input" id="image" name="image" accept="image/*">
                                    </div>
                                    <p class="help-block"><i class="fa fa-info-circle"></i> Format gambar: JPG, PNG, WEBP. Maksimal 2MB</p>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <strong><i class="fa fa-eye"></i> Preview Gambar</strong>
                                    </div>
                                    <div class="image-preview-container" id="imagePreviewContainer">
                                        <img id="imagePreview" src="img/<?php echo htmlspecialchars($image); ?>" class="img-responsive" alt="Preview">
                                    </div>
                                    <div class="card-body text-center">
                                        <small>Simpan untuk melihat perubahan</small>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" id="submitBtn" class="btn btn-add-user btn-block">
                                        <i class="fa fa-save"></i> Simpan Perubahan
                                    </button>
                                    <button type="reset" class="btn btn-default btn-block">
                                        <i class="fa fa-refresh"></i> Reset Form
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h4>BADAN PUSAT STATISTIK KOTA BANDAR LAMPUNG</h4>
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
        </footer>
    </div>
    
    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
    
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/admin-content.js"></script>
</body>
</html>
<?php $conn->close(); ?> 