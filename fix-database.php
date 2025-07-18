<?php
// Include koneksi database
require_once 'koneksi.php';

// Mulai output buffering
ob_start();

// Fungsi untuk menampilkan pesan
function showMessage($message, $isError = false) {
    $prefix = ($isError ? "ERROR: " : "INFO: ");
    echo $prefix . $message . "<br>\n";
    // Juga tampilkan di terminal
    file_put_contents('php://stderr', $prefix . $message . "\n");
}

// Cek apakah tabel services ada
$result = $conn->query("SHOW TABLES LIKE 'services'");
if ($result->num_rows == 0) {
    showMessage("Tabel services tidak ditemukan. Membuat tabel services...");
    
    // Buat tabel services
    $sql = "CREATE TABLE services (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        icon VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        showMessage("Tabel services berhasil dibuat.");
        
        // Buat services default
        $services = [
            "Pengembangan Kompetensi",
            "Kenaikan Pangkat",
            "Peta Karir",
            "Angka Kredit"
        ];
        
        foreach ($services as $i => $service) {
            $id = $i + 1; // Pastikan ID dimulai dari 1
            $sql = "INSERT INTO services (id, name) VALUES ($id, '$service')";
            
            if ($conn->query($sql) === TRUE) {
                showMessage("Service '$service' berhasil ditambahkan dengan ID $id.");
            } else {
                showMessage("Error menambahkan service: " . $conn->error, true);
            }
        }
    } else {
        showMessage("Error membuat tabel services: " . $conn->error, true);
    }
} else {
    showMessage("Tabel services sudah ada.");
    
    // Periksa apakah ID 1-4 ada di tabel services
    for ($i = 1; $i <= 4; $i++) {
        $result = $conn->query("SELECT * FROM services WHERE id = $i");
        if ($result->num_rows == 0) {
            // Jika ID tidak ada, tambahkan
            $service_name = "Layanan $i";
            $sql = "INSERT INTO services (id, name) VALUES ($i, '$service_name')";
            
            if ($conn->query($sql) === TRUE) {
                showMessage("Service dengan ID $i berhasil ditambahkan.");
            } else {
                showMessage("Error menambahkan service dengan ID $i: " . $conn->error, true);
            }
        }
    }
}

// Cek apakah tabel service_link ada
$result = $conn->query("SHOW TABLES LIKE 'service_link'");
if ($result->num_rows == 0) {
    showMessage("Tabel service_link tidak ditemukan. Membuat tabel service_link...");
    
    // Buat tabel service_link
    $sql = "CREATE TABLE service_link (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        service_id INT(11) NOT NULL,
        link_title VARCHAR(100) NOT NULL,
        url VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($sql) === TRUE) {
        showMessage("Tabel service_link berhasil dibuat.");
    } else {
        showMessage("Error membuat tabel service_link: " . $conn->error, true);
    }
} else {
    showMessage("Tabel service_link sudah ada.");
    
    // Periksa foreign key constraint
    $result = $conn->query("
        SELECT 
            TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM
            INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE
            REFERENCED_TABLE_NAME = 'services'
            AND TABLE_SCHEMA = '$dbname'
            AND TABLE_NAME = 'service_link'
    ");
    
    if ($result->num_rows == 0) {
        showMessage("Foreign key constraint tidak ditemukan. Menambahkan foreign key constraint...");
        
        // Backup data service_link
        $backup_data = [];
        $result = $conn->query("SELECT * FROM service_link");
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $backup_data[] = $row;
            }
            showMessage("Data service_link di-backup: " . count($backup_data) . " records.");
        }
        
        // Drop tabel service_link dan buat ulang dengan foreign key constraint
        $conn->query("DROP TABLE service_link");
        
        $sql = "CREATE TABLE service_link (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            service_id INT(11) NOT NULL,
            link_title VARCHAR(100) NOT NULL,
            url VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
        )";
        
        if ($conn->query($sql) === TRUE) {
            showMessage("Tabel service_link berhasil dibuat ulang dengan foreign key constraint.");
            
            // Restore data yang valid
            foreach ($backup_data as $row) {
                // Periksa apakah service_id valid
                $check = $conn->query("SELECT id FROM services WHERE id = " . $row['service_id']);
                if ($check && $check->num_rows > 0) {
                    $sql = "INSERT INTO service_link (id, service_id, link_title, url) 
                            VALUES ({$row['id']}, {$row['service_id']}, '{$row['link_title']}', '{$row['url']}')";
                    
                    if ($conn->query($sql) === TRUE) {
                        showMessage("Data link ID {$row['id']} berhasil di-restore.");
                    } else {
                        showMessage("Error restore data link ID {$row['id']}: " . $conn->error, true);
                    }
                } else {
                    showMessage("Skip data link ID {$row['id']} dengan service_id {$row['service_id']} yang tidak valid.");
                }
            }
        } else {
            showMessage("Error membuat ulang tabel service_link: " . $conn->error, true);
        }
    }
}

// Periksa data di tabel service_link yang tidak valid
$result = $conn->query("
    SELECT sl.* 
    FROM service_link sl 
    LEFT JOIN services s ON sl.service_id = s.id 
    WHERE s.id IS NULL
");

if ($result && $result->num_rows > 0) {
    showMessage("Ditemukan " . $result->num_rows . " data tidak valid di tabel service_link. Menghapus data tidak valid...");
    
    while ($row = $result->fetch_assoc()) {
        $link_id = $row['id'];
        $service_id = $row['service_id'];
        
        $conn->query("DELETE FROM service_link WHERE id = $link_id");
        showMessage("Menghapus link ID $link_id dengan service_id $service_id yang tidak valid.");
    }
}

// Tampilkan struktur tabel services
showMessage("<h2>Struktur Tabel Services</h2>");
$result = $conn->query("DESCRIBE services");
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
}

// Tampilkan struktur tabel service_link
showMessage("<h2>Struktur Tabel Service Link</h2>");
$result = $conn->query("DESCRIBE service_link");
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
}

// Tampilkan foreign keys
showMessage("<h2>Foreign Keys</h2>");
$result = $conn->query("
    SELECT 
        TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM
        INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE
        REFERENCED_TABLE_NAME IS NOT NULL
        AND TABLE_SCHEMA = '$dbname'
");

if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Table</th><th>Column</th><th>Constraint Name</th><th>Referenced Table</th><th>Referenced Column</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['TABLE_NAME'] . "</td>";
        echo "<td>" . $row['COLUMN_NAME'] . "</td>";
        echo "<td>" . $row['CONSTRAINT_NAME'] . "</td>";
        echo "<td>" . $row['REFERENCED_TABLE_NAME'] . "</td>";
        echo "<td>" . $row['REFERENCED_COLUMN_NAME'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table><br>";
} else {
    showMessage("Tidak ada foreign keys yang ditemukan.");
}

showMessage("Proses perbaikan database selesai.");

// Ambil output buffer
$html_content = ob_get_clean();

// Simpan ke file
file_put_contents('db-fix-result.html', $html_content);

// Tampilkan pesan di terminal
echo "Hasil perbaikan database telah disimpan ke file db-fix-result.html\n";
?> 