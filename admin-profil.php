<?php
session_start();
require_once 'koneksi.php';

// Periksa status login - jika belum login atau bukan admin, redirect ke halaman login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

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

// Inisialisasi variabel pesan
$message = '';
$messageType = '';

// Proses hapus profil
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Ambil informasi foto sebelum menghapus
    $stmt = $conn->prepare("SELECT foto FROM profil WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $foto = $row['foto'];
        // Hapus file foto jika bukan default
        if ($foto != 'default-male.jpg' && $foto != 'default-female.jpg' && $foto != 'kepala.jpg' && $foto != 'kasubbag.jpg') {
            $file_path = "img/staff/" . $foto;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    $stmt->close();
    
    // Hapus data dari database
    $stmt = $conn->prepare("DELETE FROM profil WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $message = "Profil berhasil dihapus!";
        $messageType = "success";
    } else {
        $message = "Error: " . $stmt->error;
        $messageType = "danger";
    }
    $stmt->close();
}

// Proses tambah/edit profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save_profile'])) {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $nama = $_POST['nama'];
        $jabatan = $_POST['jabatan'];
        $link = $_POST['link'];
        $old_foto = isset($_POST['old_foto']) ? $_POST['old_foto'] : '';
        $foto = $old_foto;
        
        // Proses upload foto jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['foto']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($ext), $allowed)) {
                $new_filename = uniqid() . '.' . $ext;
                $upload_path = 'img/staff/' . $new_filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                    // Hapus foto lama jika bukan default
                    if ($old_foto != '' && $old_foto != 'default-male.jpg' && $old_foto != 'default-female.jpg' && $old_foto != 'kepala.jpg' && $old_foto != 'kasubbag.jpg') {
                        $old_path = "img/staff/" . $old_foto;
                        if (file_exists($old_path)) {
                            unlink($old_path);
                        }
                    }
                    $foto = $new_filename;
                } else {
                    $message = "Error: Gagal upload foto!";
                    $messageType = "danger";
                }
            } else {
                $message = "Error: Format file tidak diizinkan! Gunakan format JPG, JPEG, PNG, atau GIF.";
                $messageType = "danger";
            }
        }
        
        // Jika tidak ada error, simpan ke database
        if ($messageType != "danger") {
            if ($id) {
                // Update profil
                $stmt = $conn->prepare("UPDATE profil SET nama = ?, jabatan = ?, foto = ?, link = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $nama, $jabatan, $foto, $link, $id);
            } else {
                // Tambah profil baru
                $stmt = $conn->prepare("INSERT INTO profil (nama, jabatan, foto, link) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nama, $jabatan, $foto, $link);
            }
            
            if ($stmt->execute()) {
                $message = ($id ? "Profil berhasil diperbarui!" : "Profil baru berhasil ditambahkan!");
                $messageType = "success";
            } else {
                $message = "Error: " . $stmt->error;
                $messageType = "danger";
            }
            $stmt->close();
        }
    }
}

// Ambil data profil untuk ditampilkan
$profiles = [];
$sql = "SELECT * FROM profil ORDER BY id ASC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $profiles[] = $row;
    }
}

// Ambil data profil untuk diedit jika ada parameter edit
$edit_profile = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM profil WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $edit_profile = $result->fetch_assoc();
    }
    $stmt->close();
}

// Tutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manajemen Profil - BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Manajemen Profil BPS Kota Bandar Lampung" />
    <meta name="author" content="" />
    <!-- css -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="css/jcarousel.css" rel="stylesheet" />
    <link href="css/flexslider.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    
    <style>
        .profile-img-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid #1a3c6e;
        }
        
        .profile-img-table {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #1a3c6e;
        }
        
        .form-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-responsive {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-action {
            margin: 2px;
        }
        
        .alert {
            margin-top: 20px;
        }
        
        .required-field::after {
            content: " *";
            color: red;
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
            <li><a href="about.php">Layanan</a></li>
            <li><a href="services.php">Pusat Aplikasi</a></li>
            <li><a href="pricing.php">Dokumentasi</a></li>
            <li><a href="contact.php">Pengaduan</a></li>
            
            <!-- Menu Admin - hanya ditampilkan jika role adalah admin -->
            <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
            <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
            <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
            <li class="admin-menu active"><a href="admin-profil.php"><i class="fa fa-id-card"></i> Manajemen Profil</a></li>
            
            <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        </ul>
    </div>

    <div id="wrapper">
        <section id="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-title">
                            <h2>Manajemen Profil</h2>
                            <p>Kelola data profil pegawai BPS Kota Bandar Lampung</p>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($message)): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Form Tambah/Edit Profil -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-section">
                            <h3><?php echo $edit_profile ? 'Edit Profil' : 'Tambah Profil Baru'; ?></h3>
                            <form action="admin-profil.php" method="post" enctype="multipart/form-data">
                                <?php if ($edit_profile): ?>
                                <input type="hidden" name="id" value="<?php echo $edit_profile['id']; ?>">
                                <input type="hidden" name="old_foto" value="<?php echo $edit_profile['foto']; ?>">
                                <?php endif; ?>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group text-center">
                                            <label>Foto Profil</label><br>
                                            <img id="preview-image" class="profile-img-preview" src="<?php echo $edit_profile ? 'img/staff/' . $edit_profile['foto'] : 'img/staff/default-male.jpg'; ?>" alt="Preview">
                                            <input type="file" name="foto" id="foto" class="form-control" onchange="previewImage(this);">
                                            <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Ukuran maksimal: 2MB.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="nama" class="required-field">Nama Lengkap</label>
                                            <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $edit_profile ? $edit_profile['nama'] : ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jabatan" class="required-field">Jabatan</label>
                                            <input type="text" name="jabatan" id="jabatan" class="form-control" value="<?php echo $edit_profile ? $edit_profile['jabatan'] : ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="link">Link Profil Canva</label>
                                            <input type="text" name="link" id="link" class="form-control" value="<?php echo $edit_profile ? $edit_profile['link'] : ''; ?>" placeholder="https://www.canva.com/design/...">
                                            <small class="text-muted">Masukkan link profil dari Canva (opsional).</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group text-center">
                                    <button type="submit" name="save_profile" class="btn btn-primary">
                                        <i class="fa fa-save"></i> <?php echo $edit_profile ? 'Update Profil' : 'Simpan Profil'; ?>
                                    </button>
                                    <?php if ($edit_profile): ?>
                                    <a href="admin-profil.php" class="btn btn-default">
                                        <i class="fa fa-times"></i> Batal
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Data Profil -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <h3>Daftar Profil Pegawai</h3>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Link Profil</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($profiles)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data profil.</td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach ($profiles as $profile): ?>
                                        <tr>
                                            <td><?php echo $profile['id']; ?></td>
                                            <td>
                                                <img src="img/staff/<?php echo $profile['foto']; ?>" alt="<?php echo $profile['nama']; ?>" class="profile-img-table">
                                            </td>
                                            <td><?php echo $profile['nama']; ?></td>
                                            <td><?php echo $profile['jabatan']; ?></td>
                                            <td>
                                                <?php if (!empty($profile['link'])): ?>
                                                <a href="<?php echo $profile['link']; ?>" target="_blank" title="<?php echo $profile['link']; ?>">
                                                    <i class="fa fa-external-link"></i> Lihat Profil
                                                </a>
                                                <?php else: ?>
                                                <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="admin-profil.php?edit=<?php echo $profile['id']; ?>" class="btn btn-sm btn-primary btn-action">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $profile['id']; ?>)" class="btn btn-sm btn-danger btn-action">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
    <script src="js/owl-carousel/owl.carousel.js"></script>
    
    <script>
        // Preview image before upload
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Confirm delete
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus profil ini?")) {
                window.location.href = "admin-profil.php?delete=" + id;
            }
        }
    </script>
</body>
</html> 