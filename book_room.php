<?php
session_start();
include 'db.php';
// Khởi tạo các biến để chứa kết quả hoặc lỗi
$error_message = null;

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['userid'];
    $room_id = $_POST['room_id'];
    $checkin = $_POST['checkin_date'];
    $checkout = $_POST['checkout_date'];

    // Thay thế die() bằng cách gán lỗi vào biến $error_message
    if (empty($checkin) || empty($checkout) || $checkin >= $checkout) {
        $error_message = "Ngày check-in và check-out không hợp lệ. Vui lòng chọn lại.";
    } else {
        // Kiểm tra phòng đã được đặt chưa
        $stmt = $conn->prepare("SELECT BookingID FROM Booking WHERE RoomID = ? AND Status = 'Confirmed' AND CheckinDate < ? AND CheckOutDate > ?");
        $stmt->bind_param("iss", $room_id, $checkout, $checkin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Thay thế die() bằng cách gán lỗi vào biến $error_message
            $error_message = "Rất tiếc! Phòng đã được đặt trong khoảng thời gian này.";
        } else {
            // Nếu không có lỗi, tiếp tục lấy thông tin để hiển thị form
            $priceStmt = $conn->prepare("SELECT Price, HotelName FROM Room WHERE RoomID = ?");
            $priceStmt->bind_param("i", $room_id);
            $priceStmt->execute();
            $room = $priceStmt->get_result()->fetch_assoc();
            $pricePerDay = $room['Price'];
            $hotelName = $room['HotelName'];

            $days = (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24);
            if ($days < 1) $days = 1;
            $total = $days * $pricePerDay;

            $totalFormatted = number_format($total, 0, ',', '.');
            $checkinFormatted = date("d M Y", strtotime($checkin));
            $checkoutFormatted = date("d M Y", strtotime($checkout));
        }
        $stmt->close();
    }
} else {
    // Nếu không phải là POST request, chuyển hướng về trang chủ
    header("Location: codeweb.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác Nhận & Thanh Toán</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
</head>
<body class="bg-[#0f172a] min-h-screen flex items-center justify-center font-[Inter] px-4">
    <?php if ($error_message !== null): ?>
        <div class="bg-[#1e293b] p-8 rounded-2xl w-full max-w-md shadow-lg text-center text-white">
            <div class="text-red-500 text-6xl mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold mb-2">Phòng Không Có Sẵn</h1>
            <p class="text-gray-300 mb-6"><?= htmlspecialchars($error_message) ?></p>
            <a href="room_details.php?room_id=<?= htmlspecialchars($room_id) ?>" 
               class="inline-block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                Quay Lại & Chọn Ngày Khác
            </a>
        </div>

    <?php else: ?>
        <form action="process_payment.php" method="POST" class="bg-[#1e293b] p-8 rounded-2xl w-full max-w-md shadow-lg space-y-6 text-white">
            <h1 class="text-3xl font-bold text-center">💳 Thanh toán</h1>

            <input type="hidden" name="room_id" value="<?= htmlspecialchars($room_id) ?>">
            <input type="hidden" name="checkin" value="<?= htmlspecialchars($checkin) ?>">
            <input type="hidden" name="checkout" value="<?= htmlspecialchars($checkout) ?>">
            <input type="hidden" name="total" value="<?= $total ?>">

            <div>
                <label class="block text-sm font-semibold mb-1">Số thẻ</label>
                <input type="text" name="card_number" class="w-full p-3 rounded-lg bg-[#334155] text-white border border-gray-600 focus:outline-none" required>
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block text-sm font-semibold mb-1">Ngày hết hạn</label>
                    <input type="text" name="card_expiry" placeholder="MM/YY" class="w-full p-3 rounded-lg bg-[#334155] text-white border border-gray-600 focus:outline-none" required>
                </div>
                <div class="w-1/2">
                    <label class="block text-sm font-semibold mb-1">CVV</label>
                    <input type="text" name="card_cvv" class="w-full p-3 rounded-lg bg-[#334155] text-white border border-gray-600 focus:outline-none" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Phương thức thanh toán</label>
                <select name="method" class="w-full p-3 rounded-lg bg-[#334155] text-white border border-gray-600 focus:outline-none">
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                </select>
            </div>

            <div class="bg-[#334155] rounded-xl p-4 mt-4 space-y-2">
                <p><span class="text-gray-300">Tổng:</span> <span class="font-bold text-white">VND <?= $totalFormatted ?></span></p>
                <p class="text-lg font-semibold"><?= htmlspecialchars($hotelName) ?></p>
                <p class="text-sm text-gray-300"><?= $checkinFormatted ?> – <?= $checkoutFormatted ?></p>
            </div>

            <button type="submit" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                Xác nhận thanh toán
            </button>
        </form>

    <?php endif; ?>

</body>
</html>
