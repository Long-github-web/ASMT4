<?php
session_start();

// Thêm logic xóa token trong DB trước khi hủy session
if (isset($_SESSION['userid'])) {
    include 'db.php';
    $userid = $_SESSION['userid'];
    $stmt = $conn->prepare("UPDATE User SET remember_token_selector = NULL, remember_token_validator_hash = NULL WHERE UserID = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $stmt->close();
}

// Xóa cookie bằng cách đặt thời gian hết hạn trong quá khứ
if (isset($_COOKIE['remember_me_selector'])) {
    setcookie('remember_me_selector', '', time() - 3600, '/');
}
if (isset($_COOKIE['remember_me_validator'])) {
    setcookie('remember_me_validator', '', time() - 3600, '/');
}

session_unset();
session_destroy();
header("location: codeweb.php");
exit;
?>