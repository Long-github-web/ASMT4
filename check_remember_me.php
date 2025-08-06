<?php
// Tệp này sẽ được include, không cần session_start() ở đây

// Chỉ kiểm tra nếu người dùng chưa có session và có cookie
if (!isset($_SESSION['userid']) && isset($_COOKIE['remember_me_selector']) && isset($_COOKIE['remember_me_validator'])) {
    
    include_once 'db.php'; // Dùng include_once để tránh lỗi nếu db.php đã được nạp

    $selector = $_COOKIE['remember_me_selector'];
    $validator = $_COOKIE['remember_me_validator'];

    // 1. Tìm người dùng dựa trên selector
    $query = "SELECT * FROM User WHERE remember_token_selector = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selector);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // 2. Xác thực validator từ cookie với validator đã hash trong DB
        $validator_hash_from_db = $user['remember_token_validator_hash'];
        if (password_verify($validator, $validator_hash_from_db)) {
            // XÁC THỰC THÀNH CÔNG -> Tự động đăng nhập
            $_SESSION['userid'] = $user['UserID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = (int)$user['Role'];
        } else {
            // Nếu validator sai -> có thể là tấn công -> xóa token khỏi DB
            // (Thực hành tốt để tăng bảo mật)
            $updateTokenStmt = $conn->prepare("UPDATE User SET remember_token_selector = NULL, remember_token_validator_hash = NULL WHERE UserID = ?");
            $updateTokenStmt->bind_param("i", $user['UserID']);
            $updateTokenStmt->execute();
        }
    }
    $stmt->close();
}