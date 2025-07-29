<?php
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
?>
