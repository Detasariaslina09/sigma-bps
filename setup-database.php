<?php
// Include koneksi database
require_once 'koneksi.php';

// Fungsi untuk menampilkan struktur tabel
function showTableStructure($conn, $tableName) {
    echo "<h3>Struktur tabel $tableName:</h3>";
    $result = $conn->query("DESCRIBE $tableName");
    
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table><br>";
    } else {
        echo "Tidak dapat menampilkan struktur tabel $tableName: " . $conn->error . "<br>";
    }
}

// Fungsi untuk menampilkan isi tabel
function showTableContent($conn, $tableName) {
    echo "<h3>Isi tabel $tableName:</h3>";
    $result = $conn->query("SELECT * FROM $tableName");
    
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        
        // Tampilkan header tabel
        $firstRow = $result->fetch_assoc();
        echo "<tr>";
        foreach ($firstRow as $key => $value) {
            echo "<th>$key</th>";
        }
        echo "</tr>";
        
        // Tampilkan baris pertama
        echo "<tr>";
        foreach ($firstRow as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
        
        // Tampilkan baris-baris lainnya
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        
        echo "</table><br>";
    } else {
        echo "Tidak ada data dalam tabel $tableName atau terjadi error: " . $conn->error . "<br>";
    }
}

// Cek apakah tabel users sudah ada
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows == 0) {
    echo "Tabel users belum ada. Membuat tabel users...<br>";
    
    // Buat tabel users jika belum ada
    $sql = "CREATE TABLE users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Tabel users berhasil dibuat.<br>";
        
        // Buat admin default
        $admin_username = "admin";
        $admin_password = password_hash("admin123", PASSWORD_DEFAULT);
        $admin_role = "admin";
        
        $sql = "INSERT INTO users (username, password, role) 
                VALUES ('$admin_username', '$admin_password', '$admin_role')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Admin default berhasil dibuat. Username: admin, Password: admin123<br>";
        } else {
            echo "Error membuat admin default: " . $conn->error . "<br>";
        }
    } else {
        echo "Error membuat tabel users: " . $conn->error . "<br>";
    }
} else {
    echo "Tabel users sudah ada.<br>";
}

// Cek apakah tabel services sudah ada
$result = $conn->query("SHOW TABLES LIKE 'services'");
if ($result->num_rows == 0) {
    echo "Tabel services belum ada. Membuat tabel services...<br>";
    
    // Buat tabel services jika belum ada
    $sql = "CREATE TABLE services (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        icon VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Tabel services berhasil dibuat.<br>";
        
        // Buat services default
        $services = [
            "Pengembangan Kompetensi",
            "Kenaikan Pangkat",
            "Peta Karir",
            "Angka Kredit"
        ];
        
        foreach ($services as $service) {
            $sql = "INSERT INTO services (name) VALUES ('$service')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Service '$service' berhasil ditambahkan.<br>";
            } else {
                echo "Error menambahkan service: " . $conn->error . "<br>";
            }
        }
    } else {
        echo "Error membuat tabel services: " . $conn->error . "<br>";
    }
} else {
    echo "Tabel services sudah ada.<br>";
    
    // Periksa struktur tabel services untuk memastikan kolom name ada
    $result = $conn->query("SHOW COLUMNS FROM services LIKE 'name'");
    if ($result->num_rows == 0) {
        echo "Kolom name tidak ditemukan. Menambahkan kolom name...<br>";
        
        // Tambahkan kolom name jika belum ada
        $sql = "ALTER TABLE services ADD COLUMN name VARCHAR(100) NOT NULL AFTER id";
        
        if ($conn->query($sql) === TRUE) {
            echo "Kolom name berhasil ditambahkan.<br>";
            
            // Update data services yang sudah ada
            $services = [
                1 => "Pengembangan Kompetensi",
                2 => "Kenaikan Pangkat",
                3 => "Peta Karir",
                4 => "Angka Kredit"
            ];
            
            foreach ($services as $id => $name) {
                $sql = "UPDATE services SET name = '$name' WHERE id = $id";
                
                if ($conn->query($sql) === TRUE) {
                    echo "Service ID $id berhasil diupdate menjadi '$name'.<br>";
                } else {
                    echo "Error mengupdate service ID $id: " . $conn->error . "<br>";
                }
            }
        } else {
            echo "Error menambahkan kolom name: " . $conn->error . "<br>";
        }
    }
}

// Cek apakah tabel service_link sudah ada
$result = $conn->query("SHOW TABLES LIKE 'service_link'");
if ($result->num_rows == 0) {
    echo "Tabel service_link belum ada. Membuat tabel service_link...<br>";
    
    // Buat tabel service_link jika belum ada
    $sql = "CREATE TABLE service_link (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        service_id INT(11) NOT NULL,
        link_title VARCHAR(100) NOT NULL,
        url VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Tabel service_link berhasil dibuat.<br>";
        
        // Buat service_link default
        $links = [
            [1, "Spreadsheet Pengembangan Kompetensi", "https://docs.google.com/spreadsheets/pengembangan-kompetensi"],
            [1, "Informasi Webinar/Pelatihan LMS", "#webinar-pelatihan"],
            [1, "Spreadsheet Link Sertifikat", "https://docs.google.com/spreadsheets/sertifikat"],
            [1, "Kegiatan Kompetensi BPS Balam", "#kegiatan-kompetensi"],
            
            [2, "Peraturan Kenaikan Pangkat", "https://lampung.bps.go.id/peraturan-kp"],
            [2, "Nominasi Kenaikan Pangkat", "https://lampung.bps.go.id/nominasi-kp"],
            [2, "Form Usul Kenaikan Pangkat", "https://lampung.bps.go.id/form-usul-kp"],
            
            [3, "Buku Peta SDM 5 Tahun Kedepan", "#peta-sdm"],
            [3, "Informasi Uji Kompetensi", "https://lampung.bps.go.id/uji-kompetensi"],
            
            [4, "Angka Kredit Pegawai", "#angka-kredit-pegawai"],
            [4, "PAK Konversi", "#pak-konversi"]
        ];
        
        foreach ($links as $link) {
            $sql = "INSERT INTO service_link (service_id, link_title, url) 
                    VALUES ({$link[0]}, '{$link[1]}', '{$link[2]}')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Link '{$link[1]}' berhasil ditambahkan.<br>";
            } else {
                echo "Error menambahkan link: " . $conn->error . "<br>";
            }
        }
    } else {
        echo "Error membuat tabel service_link: " . $conn->error . "<br>";
    }
} else {
    echo "Tabel service_link sudah ada.<br>";
}

// Tampilkan struktur dan isi tabel untuk debugging
echo "<h2>Informasi Debugging</h2>";

// Cek tabel services
$result = $conn->query("SHOW TABLES LIKE 'services'");
if ($result->num_rows > 0) {
    showTableStructure($conn, "services");
    showTableContent($conn, "services");
} else {
    echo "Tabel services tidak ditemukan.<br>";
}

// Cek tabel service_link
$result = $conn->query("SHOW TABLES LIKE 'service_link'");
if ($result->num_rows > 0) {
    showTableStructure($conn, "service_link");
    showTableContent($conn, "service_link");
} else {
    echo "Tabel service_link tidak ditemukan.<br>";
}

// Tutup koneksi
$conn->close();

echo "<p>Setup database selesai. <a href='login.php'>Kembali ke halaman login</a>.</p>";
?> 