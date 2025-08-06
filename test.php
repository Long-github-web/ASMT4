<?php
include 'db.php'; // Kết nối với database

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
} else {
    echo "✅ Kết nối thành công với database 'hotel2'";
}
?>
