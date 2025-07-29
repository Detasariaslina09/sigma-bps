<?php
function check_connection($conn) {
    if ($conn instanceof mysqli && !$conn->ping()) {
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

function get_database_connection() {
    $servername = "127.0.0.1";
    $username   = "root";
    $password   = "";
    $dbname     = "sigap";
    $port       = 3306;
    
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        die("Initial Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
