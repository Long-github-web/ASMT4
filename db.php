<?php
$servername = "localhost";     // máy chủ MySQL, mặc định là localhost
$username = "root";            // tài khoản mặc định của XAMPP
$password = "";                // mặc định không có mật khẩu trong XAMPP
$dbname = "hotel2";             // tên database bạn đã đặt

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// echo "Kết nối thành công!";
?>
