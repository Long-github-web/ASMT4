<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['userid'];
    $room_id = $_POST['room_id'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $total = $_POST['total'];

    $card_number = $_POST['card_number'];
    $card_expiry = $_POST['card_expiry'];
    $card_cvv = $_POST['card_cvv'];
    $method = $_POST['method'];

    if (empty($card_number) || empty($card_expiry) || empty($card_cvv) || empty($method)) {
        die("❌ Vui lòng điền đầy đủ thông tin thanh toán.");
    }

    // Bắt đầu một transaction để đảm bảo cả hai lệnh cùng thành công hoặc thất bại
    $conn->begin_transaction();

    try {
        // Tạo đơn đặt phòng
        $bookingStmt = $conn->prepare("INSERT INTO Booking (UserID, RoomID, CheckinDate, CheckOutDate, TotalAmount, Status, CreatedAt, UpdatedAt) 
                                   VALUES (?, ?, ?, ?, ?, 'Pending', NOW(), NOW())");
        $bookingStmt->bind_param("iissd", $user_id, $room_id, $checkin, $checkout, $total);
        $bookingStmt->execute();
        $booking_id = $bookingStmt->insert_id;
        $bookingStmt->close();

        // GHI THÔNG TIN THANH TOÁN (ĐÃ SỬA LẠI)
        // Đã xóa UserID và RoomID khỏi câu lệnh INSERT này
        $paymentStmt = $conn->prepare("INSERT INTO Payment 
            (BookingID, Amount, PaymentMethod, CardNumber, CardExpiry, CardCVV, Status, CreatedAt) 
            VALUES (?, ?, ?, ?, ?, ?, 'Paid', NOW())");
        // Đã sửa lại bind_param cho đúng với số lượng tham số
        $paymentStmt->bind_param("idssss", $booking_id, $total, $method, $card_number, $card_expiry, $card_cvv);
        $paymentStmt->execute();
        $paymentStmt->close();

        // Nếu mọi thứ thành công, commit transaction
        $conn->commit();

        // Lấy tên khách sạn để hiển thị
        $roomStmt = $conn->prepare("SELECT HotelName FROM Room WHERE RoomID = ?");
        $roomStmt->bind_param("i", $room_id);
        $roomStmt->execute();
        $hotel_name = $roomStmt->get_result()->fetch_assoc()['HotelName'];
        $roomStmt->close();

        // Format ngày & tiền
        $checkin_fmt = date("d M Y", strtotime($checkin));
        $checkout_fmt = date("d M Y", strtotime($checkout));
        $formatted_total = number_format($total, 0, ',', '.');

        echo '
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <title>Đặt phòng thành công</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-900 text-white flex items-center justify-center min-h-screen px-4">
            <div class="bg-gray-800 rounded-xl p-8 max-w-lg w-full text-center shadow-lg">
                <div class="text-green-400 text-6xl mb-4">✅</div>
                <h2 class="text-3xl font-bold mb-2">Thanh toán thành công!</h2>
                <p class="text-lg mb-6">Bạn đã đặt phòng thành công tại <span class="font-semibold text-yellow-400">'. htmlspecialchars($hotel_name) .'</span>.</p>

                <div class="bg-gray-700 p-4 rounded-lg text-left mb-6">
                    <p><strong>🗓️ Ngày:</strong> '. $checkin_fmt .' – '. $checkout_fmt .'</p>
                    <p><strong>💵 Tổng tiền:</strong> '. $formatted_total .' VND</p>
                </div>

                <a href="codeweb.php" class="inline-block px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition duration-300">
                    🔙 Về trang chủ
                </a>
            </div>
        </body>
        </html>';

    } catch (mysqli_sql_exception $exception) {
        $conn->rollback(); // Nếu có lỗi, hủy bỏ mọi thay đổi
        die("❌ Giao dịch thất bại. Vui lòng thử lại.");
    }

    $conn->close();
} else {
    echo "❌ Truy cập không hợp lệ.";
}
?>