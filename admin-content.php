<?php
// Mulai session
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {     // Redirect ke halaman login jika belum login atau bukan admin
    header("Location: login.php");
    exit;
}
$is_logged_in = isset($_SESSION['user_id']); // Definisi variabel status login dan admin
$is_admin = $is_logged_in && $_SESSION['role'] === 'admin';

require_once 'koneksi.php';  // Include koneksi database
$success = false; // Inisialisasi variabel
$error = '';

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = 1; // Selalu gunakan ID 1 untuk konten utama
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $imageName = '';

    try {
        $conn = check_connection($conn); // Pastikan koneksi aktif
        
        $oldContentQuery = $conn->query("SELECT * FROM konten WHERE id=1"); // Ambil informasi konten lama, termasuk gambar
        $oldImage = 'konten.webp'; // Default jika tidak ada data sebelumnya
        
        if ($oldContentQuery && $oldContentQuery->num_rows > 0) {
            $oldContent = $oldContentQuery->fetch_assoc();
            $oldImage = $oldContent['image'];
        }
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { // Cek jika ada upload gambar baru
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
        if (!$error) {// Jika tidak ada error, lakukan update data
            if (!$conn->query("DELETE FROM konten")) { // Hapus semua data konten yang ada terlebih dahulu
                throw new Exception("Error deleting old content: " . $conn->error);
            }
            $imageToUse = '';
            if ($imageName) {
                $imageToUse = $imageName;
            } else {
                $imageToUse = $oldImage; // Jika tidak ada gambar baru, gunakan gambar lama
            }
            
            $sql = "INSERT INTO konten (id, title, description, image) VALUES (1, '$title', '$description', '$imageToUse')"; // Simpan data baru ke database
            
            if ($conn->query($sql)) {
                $success = true; // Jika berhasil menyimpan data baru dan ada gambar baru, hapus gambar lama
                if ($imageName && $oldImage != 'konten.webp' && $oldImage != $imageName) {
                    $oldImagePath = 'img/' . $oldImage;
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
            } else {
                $error = 'Gagal menyimpan perubahan: ' . $conn->error; // Jika gagal menyimpan data baru dan ada gambar baru, hapus gambar baru
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
$conn = check_connection($conn); // Pastikan koneksi aktif sebelum mengambil data
$id = 1; // Ambil data konten dari database
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
    try {     // Tambahkan data default ke database
        $conn->query("DELETE FROM konten"); // Hapus data lama jika ada
        if (!$conn->query("INSERT INTO konten (id, title, description, image) VALUES (1, '$title', '$description', '$image')")) {
            $error = 'Gagal menambahkan data default: ' . $conn->error;
        }
    } catch (Exception $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}
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
    <?php include_once 'includes/sidebar.php'; ?>
    <div id="wrapper">
        <section id="content">
            <div class="container">
                <div class="admin-content">
                    <div class="admin-header clearfix">
                        <div class="row">
                            <div class="col-md-6">
                                <h2><i class="fa fa-file-text"></i> Manajemen Konten</h2>
                                <p>Kelola konten di halaman utama website</p>
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
        <?php include_once 'includes/footer.php'; ?>
    </div>
    
    <a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/admin-content.js"></script>
</body>
</html>
<?php $conn->close(); ?> 