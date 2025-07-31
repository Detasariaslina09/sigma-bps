<?php
session_start();
require_once 'koneksi.php';
require_once 'includes/admin-profil-functions.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { // Periksa status login - jika belum login atau bukan admin, redirect ke halaman login
    header("Location: login.php");
    exit;
}

// Definisi variabel status login dan admin
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = $is_logged_in && $_SESSION['role'] === 'admin';
$message = ''; // Inisialisasi variabel pesan
$messageType = '';

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

$profiles = [];
$sql_select_all = "SELECT * FROM profil ORDER BY id ASC";
$result_all = $conn->query($sql_select_all);
if ($result_all) {
    while ($row_all = $result_all->fetch_assoc()) {
        $profiles[] = $row_all;
    }
}
if ($conn instanceof mysqli) { // Tutup koneksi database
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
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/custom-styles.css" rel="stylesheet" />
    <link href="css/font-awesome.css" rel="stylesheet" />
    <link href="css/admin-profil.css" rel="stylesheet" />
</head>
<body>
    <?php include_once 'includes/sidebar.php'; ?>
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
                                                    <input type="file" name="foto" id="foto" class="form-control" onchange="previewImage(this)">
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
                                            <button type="button" onclick="editProfile(<?php echo htmlspecialchars(json_encode($profile), ENT_QUOTES, 'UTF-8'); ?>)" class="btn btn-sm btn-primary btn-action btn-edit-profile">
                                                <i class="fa fa-edit"></i><span>Edit</span>
                                            </button>
                                            <button type="button" onclick="confirmDelete(<?php echo htmlspecialchars($profile['id']); ?>)" class="btn btn-sm btn-danger btn-action btn-delete-profile" data-id="<?php echo htmlspecialchars($profile['id']); ?>">
                                                <i class="fa fa-trash"></i><span>Hapus</span>
                                            </button>
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
        <?php include_once 'includes/footer.php'; ?>
    </div>

    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.fancybox.pack.js"></script>
    <script src="js/jquery.fancybox-media.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/admin-profil.js"></script>
</body>
</html>