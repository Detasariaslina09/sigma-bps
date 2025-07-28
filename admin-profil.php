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
if (!isset($conn) || !$conn instanceof mysqli) {
    $servername = "127.0.0.1";
    $username   = "root";
    $password   = "";
    $dbname     = "sigap";
    $port       = 3306;
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        die("Initial Connection failed: " . $conn->connect_error);
    }
}

$conn = check_connection($conn);

// Inisialisasi variabel pesan
$message = '';
$messageType = '';

// Fungsi untuk menangani jabatan khusus
function handleSpecialPosition($conn, $jabatan_input, $nama, $foto, $link) {
    $jabatan_lower = trim(strtolower($jabatan_input));

    $isKepala = strpos($jabatan_lower, 'kepala bps kota bandar lampung') !== false;
    $isKasubbag = strpos($jabatan_lower, 'kepala subbagian umum') !== false || strpos($jabatan_lower, 'kasubbag umum') !== false;
    
    if ($isKepala || $isKasubbag) {
        $searchTerm = '';
        if ($isKepala) {
            $searchTerm = 'Kepala BPS Kota Bandar Lampung';
        } else if ($isKasubbag) {
            $searchTerm = 'Kepala Subbagian Umum';
        }
        
        $stmt_check = $conn->prepare("SELECT id, foto FROM profil WHERE jabatan LIKE ?");
        $like_term = '%' . $searchTerm . '%';
        $stmt_check->bind_param("s", $like_term);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($row_check = $result_check->fetch_assoc()) {
            $oldFoto = $row_check['foto'];
            $stmt_update = $conn->prepare("UPDATE profil SET nama = ?, jabatan = ?, foto = ?, link = ? WHERE id = ?");
            $stmt_update->bind_param("ssssi", $nama, $jabatan_input, $foto, $link, $row_check['id']);
            
            if ($stmt_update->execute()) {
                if ($oldFoto != 'default-male.jpg' && $oldFoto != 'default-female.jpg' && 
                    $oldFoto != 'kepala.jpg' && $oldFoto != 'kasubbag.jpg' && $oldFoto != $foto) {
                    $old_path = "img/staff/" . $oldFoto;
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
                $stmt_update->close();
                $stmt_check->close();
                return true;
            }
            $stmt_update->close();
        }
        $stmt_check->close();
    }
    return false;
}


// Proses hapus profil
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $conn->prepare("SELECT foto FROM profil WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $foto = $row['foto'];
        if ($foto != 'default-male.jpg' && $foto != 'default-female.jpg' && $foto != 'kepala.jpg' && $foto != 'kasubbag.jpg') {
            $file_path = "img/staff/" . $foto;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    $stmt->close();
    
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

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['foto']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($ext), $allowed)) {
                $new_filename = uniqid() . '.' . $ext;
                $upload_path = 'img/staff/' . $new_filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                    if (!empty($old_foto) && $old_foto != 'default-male.jpg' && $old_foto != 'default-female.jpg' && 
                        $old_foto != 'kepala.jpg' && $old_foto != 'kasubbag.jpg' && $old_foto != $new_filename) {
                        $old_path_to_delete = "img/staff/" . $old_foto;
                        if (file_exists($old_path_to_delete)) {
                            unlink($old_path_to_delete);
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
        
        if ($messageType != "danger") {
            if (handleSpecialPosition($conn, $jabatan, $nama, $foto, $link)) {
                $message = "Profil jabatan khusus (" . htmlspecialchars($jabatan) . ") berhasil diperbarui!";
                $messageType = "success";
            } else {
                if ($id) {
                    $stmt = $conn->prepare("UPDATE profil SET nama = ?, jabatan = ?, foto = ?, link = ? WHERE id = ?");
                    $stmt->bind_param("ssssi", $nama, $jabatan, $foto, $link, $id);
                } else {
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
}


// Ambil data profil untuk ditampilkan di tabel
$profiles = [];
$sql_select_all = "SELECT * FROM profil ORDER BY id ASC";
$result_all = $conn->query($sql_select_all);
if ($result_all) {
    while ($row_all = $result_all->fetch_assoc()) {
        $profiles[] = $row_all;
    }
}

// Ambil data profil untuk diedit jika ada parameter edit
$edit_profile = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $stmt_edit = $conn->prepare("SELECT * FROM profil WHERE id = ?");
    $stmt_edit->bind_param("i", $id_edit);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    if ($result_edit->num_rows > 0) {
        $edit_profile = $result_edit->fetch_assoc();
    }
    $stmt_edit->close();
}

// Tutup koneksi database
if ($conn instanceof mysqli) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manajemen Profil - BPS Kota Bandar Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Manajemen Profil BPS Kota Bandar Lampung" />
    <meta name="author" content="" />
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
        
        .table-responsive {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-action {
            margin: 2px;
            padding: 6px 12px;
            border-radius: 4px;
            transition: all 0.3s ease;
            color: #fff !important;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-action.btn-primary {
            background-color: #ff9800;
            border-color: #ff9800;
            color: #fff !important;
        }

        .btn-action.btn-primary:hover {
            background-color: #f57c00;
            border-color: #f57c00;
            color: #fff !important;
        }

        .btn-action.btn-danger {
            background-color: #d9534f; /* Default red for danger */
            border-color: #d43f3a;
            color: #fff !important;
        }

        .btn-action.btn-danger:hover {
            background-color: #c9302c;
            border-color: #ac2925;
            color: #fff !important;
        }

        .btn-action i {
            margin-right: 5px;
            color: #fff !important;
        }

        /* Tambahan untuk memastikan teks selalu putih */
        .btn-action span,
        .btn-action:link,
        .btn-action:visited,
        .btn-action:hover,
        .btn-action:active {
            color: #fff !important;
            text-decoration: none !important;
        }

        .btn-action:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.25);
            outline: none;
        }

        .btn-action:active {
            background-color: #ef6c00 !important;
            border-color: #ef6c00 !important;
        }
        
        .alert {
            margin-top: 20px;
        }
        
        .required-field::after {
            content: " *";
            color: red;
        }
        .modal {
            z-index: 9999;
        }
        
        .modal-backdrop {
            z-index: 9998;
        }

        .modal-dialog {
            margin: 30px auto;
        }

        .modal-content {
            border-radius: 8px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.2);
        }

        .modal-header {
            background: #f8f9fa;
            border-radius: 8px 8px 0 0;
            border-bottom: 1px solid #e9ecef;
        }

        .modal-footer {
            background: #f8f9fa;
            border-radius: 0 0 8px 8px;
            border-top: 1px solid #e9ecef;
        }

        /* Style untuk tombol di modal */
        .modal-footer .btn-default {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }

        .modal-footer .btn-default:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: #fff;
        }

        .modal-footer .btn {
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        /* --- STYLE BARU UNTUK JUdul Tabel dan Tombol Tambah Profil --- */
        .table-header-row {
            display: flex; /* Menggunakan flexbox */
            justify-content: space-between; /* Menjaga elemen di ujung-ujung */
            align-items: center; /* Memusatkan secara vertikal */
            margin-bottom: 15px; /* Jarak antara header tabel dan tabel */
            padding-right: 20px; /* Sesuaikan dengan padding tabel */
            padding-left: 20px; /* Sesuaikan dengan padding tabel */
        }
        .table-header-row h3 {
            margin: 0; /* Menghilangkan margin default h3 */
            font-size: 24px; /* Sesuaikan ukuran font jika perlu */
            color: #333; /* Warna teks judul tabel */
        }
        .table-header-row .btn-add-profile-table {
            background: #ff9800;
            color: #fff;
            border: none;
            padding: 8px 15px; /* Sedikit lebih kecil dari tombol utama */
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
            white-space: nowrap; /* Mencegah tombol pecah baris */
        }
        .table-header-row .btn-add-profile-table:hover {
            background: #e65100;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,152,0,0.15);
        }
        /* --- AKHIR STYLE BARU --- */

    </style>
</head>
<body>
    <button class="mobile-menu-toggle">
        <i class="fa fa-bars"></i> Menu
    </button>
    
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
                <li class="admin-menu"><a href="admin-users.php"><i class="fa fa-users"></i> Manajemen User</a></li>
                <li class="admin-menu"><a href="admin-services.php"><i class="fa fa-cogs"></i> Manajemen Layanan</a></li>
                <li class="admin-menu"><a href="admin-content.php"><i class="fa fa-file-text"></i> Manajemen Konten</a></li>
                <li class="admin-menu active"><a href="admin-profil.php"><i class="fa fa-user"></i> Manajemen Profil</a></li>
            <?php endif; ?>
            
            <li class="logout-menu"><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        </ul>
    </div>

    <div id="wrapper">
        <section id="content">
            <div class="container">
                <div class="admin-content">
                    <div class="section-title">
                        <h2>Manajemen Profil</h2>
                        <p>Kelola data profil pegawai BPS Kota Bandar Lampung</p>
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
                    
                    <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="profileModalLabel">Tambah Profil Baru</h4>
                                </div>
                                <div class="modal-body">
                                    <form action="admin-profil.php" method="post" enctype="multipart/form-data" id="profileForm">
                                        <input type="hidden" name="id" id="profile_id">
                                        <input type="hidden" name="old_foto" id="old_foto">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group text-center">
                                                    <label>Foto Profil</label><br>
                                                    <img id="preview-image" class="profile-img-preview" src="img/staff/default-male.jpg" alt="Preview">
                                                    <input type="file" name="foto" id="foto" class="form-control" onchange="previewImage(this);">
                                                    <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Ukuran maksimal: 2MB.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="nama" class="required-field">Nama Lengkap</label>
                                                    <input type="text" name="nama" id="nama" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="jabatan" class="required-field">Jabatan</label>
                                                    <input type="text" name="jabatan" id="jabatan" class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="link">Link Profil Canva</label>
                                                    <input type="text" name="link" id="link" class="form-control" placeholder="https://www.canva.com/design/...">
                                                    <small class="text-muted">Masukkan link profil dari Canva (opsional).</small>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        <i class="fa fa-times"></i> Batal
                                    </button>
                                    <button type="submit" form="profileForm" name="save_profile" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Simpan Profil
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div class="table-header-row">
                            <h3>Daftar Profil Pegawai</h3>
                            <button type="button" class="btn btn-primary btn-add-profile-table" data-toggle="modal" data-target="#profileModal">
                                <i class="fa fa-plus"></i> Tambah Profil Baru
                            </button>
                        </div>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
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
                                    <td colspan="5" class="text-center">Tidak ada data profil.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($profiles as $profile): ?>
                                    <tr>
                                        <td>
                                            <img src="img/staff/<?php echo htmlspecialchars($profile['foto']); ?>" alt="<?php echo htmlspecialchars($profile['nama']); ?>" class="profile-img-table">
                                        </td>
                                        <td><?php echo htmlspecialchars($profile['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($profile['jabatan']); ?></td>
                                        <td>
                                            <?php if (!empty($profile['link'])): ?>
                                            <a href="<?php echo htmlspecialchars($profile['link']); ?>" target="_blank" title="<?php echo htmlspecialchars($profile['link']); ?>">
                                                <i class="fa fa-external-link"></i> Lihat Profil
                                            </a>
                                            <?php else: ?>
                                            <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="editProfile(<?php echo htmlspecialchars(json_encode($profile)); ?>)" class="btn btn-sm btn-primary btn-action">
                                                <i class="fa fa-edit"></i><span>Edit</span>
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo htmlspecialchars($profile['id']); ?>)" class="btn btn-sm btn-danger btn-action">
                                                <i class="fa fa-trash"></i><span>Hapus</span>
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

        // Edit profile
        function editProfile(profile) {
            // Set modal title
            $('#profileModalLabel').text('Edit Profil');
            
            // Fill form with profile data
            $('#profile_id').val(profile.id);
            $('#nama').val(profile.nama);
            $('#jabatan').val(profile.jabatan);
            $('#link').val(profile.link);
            $('#old_foto').val(profile.foto);
            $('#preview-image').attr('src', 'img/staff/' + profile.foto);
            
            // Show modal
            $('#profileModal').modal('show');
        }

        // Reset form when modal is closed
        $('#profileModal').on('hidden.bs.modal', function () {
            $('#profileForm')[0].reset();
            $('#profile_id').val('');
            $('#old_foto').val('');
            $('#preview-image').attr('src', 'img/staff/default-male.jpg'); // Reset to default image
            $('#profileModalLabel').text('Tambah Profil Baru');
        });

        // Show success message in modal if exists (ini logika untuk halaman lain jika alert muncul setelah redirect)
        $(document).ready(function() {
            <?php if (!empty($message) && $messageType == 'success'): ?>
            // Logika untuk menampilkan alert jika perlu, atau ini bisa dihilangkan jika pesan sudah muncul otomatis
            // alert('<?php echo $message; ?>');
            <?php endif; ?>

            // Trigger modal for add new profile (menggunakan class btn-add-profile-table yang baru)
            $('.btn-add-profile-table').click(function() {
                $('#profileModalLabel').text('Tambah Profil Baru');
                $('#profileModal').modal('show');
            });
        });
    </script>
</body>
</html>