<?php
session_start();
include 'db.php';

// Chỉ xử lý nếu người dùng đã đăng nhập và gửi form bằng POST
if (!isset($_SESSION['userid'])) {
    die("Bạn cần đăng nhập để gửi phản hồi.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $user_id = $_SESSION['userid'];
    $room_id = $_POST['room_id'];
    $rating = $_POST['rating'];
    $comment = trim($_POST['comment']);

    // Xác thực dữ liệu đơn giản
    if (empty($room_id) || empty($rating) || empty($comment)) {
        // Chuyển hướng lại với thông báo lỗi
        header("Location: room_details.php?room_id=$room_id&feedback_error=1");
        exit();
    }
    
    // Lưu vào database một cách an toàn
    $stmt = $conn->prepare("INSERT INTO Feedback (UserID, RoomID, Rating, Comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $room_id, $rating, $comment);
    
    if ($stmt->execute()) {
        // Nếu thành công, chuyển hướng lại với thông báo thành công
        header("Location: room_details.php?room_id=$room_id&feedback_success=1");
    } else {
        // Nếu lỗi, chuyển hướng lại với thông báo lỗi
        header("Location: room_details.php?room_id=$room_id&feedback_error=1");
    }
    
    $stmt->close();
    $conn->close();
} else {
    // Nếu không phải POST, chuyển về trang chủ
    header("Location: codeweb.php");
    exit();
}
?>