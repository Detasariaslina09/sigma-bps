<?php
// Include koneksi database
require_once 'koneksi.php';

// Mulai output buffering
ob_start();

echo "<h1>Struktur Database SIGAP</h1>";

// Cek tabel services
echo "<h2>Tabel Services</h2>";
$result = $conn->query("SHOW TABLES LIKE 'services'");
if ($result->num_rows > 0) {
    // Tampilkan struktur tabel
    echo "<h3>Struktur Tabel:</h3>";
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
    
    // Tampilkan isi tabel
    echo "<h3>Isi Tabel:</h3>";
    $result = $conn->query("SELECT * FROM services");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Icon</th><th>Created At</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . ($row['description'] ?? '') . "</td>";
            echo "<td>" . ($row['icon'] ?? '') . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table><br>";
    } else {
        echo "Tidak ada data dalam tabel services.<br>";
    }
} else {
    echo "Tabel services tidak ditemukan.<br>";
}

// Cek tabel service_link
echo "<h2>Tabel Service Link</h2>";
$result = $conn->query("SHOW TABLES LIKE 'service_link'");
if ($result->num_rows > 0) {
    // Tampilkan struktur tabel
    echo "<h3>Struktur Tabel:</h3>";
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
    
    // Tampilkan isi tabel
    echo "<h3>Isi Tabel:</h3>";
    $result = $conn->query("SELECT * FROM service_link");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Service ID</th><th>Link Title</th><th>URL</th><th>Created At</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['service_id'] . "</td>";
            echo "<td>" . $row['link_title'] . "</td>";
            echo "<td>" . $row['url'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table><br>";
    } else {
        echo "Tidak ada data dalam tabel service_link.<br>";
    }
} else {
    echo "Tabel service_link tidak ditemukan.<br>";
}

// Tampilkan foreign keys
echo "<h2>Foreign Keys</h2>";
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
    echo "Tidak ada foreign keys yang ditemukan.<br>";
}

// Ambil output buffer
$html_content = ob_get_clean();

// Simpan ke file
file_put_contents('db-info.html', $html_content);

// Tampilkan pesan di terminal
echo "Informasi database telah disimpan ke file db-info.html\n";
?> 