<?php
session_start();
include 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['userid']) || (int)$_SESSION['role'] !== 2) {
    header("Location: login.php");
    exit();
}

// === PHẦN XỬ LÝ HÀNH ĐỘNG (Phê duyệt / Hủy) ===
// Chúng ta sẽ dùng phương thức GET để xử lý cho đơn giản

// 1. Xử lý Phê duyệt
if (isset($_GET['approve_id']) && is_numeric($_GET['approve_id'])) {
    $booking_id_to_approve = (int)$_GET['approve_id'];
    $stmt = $conn->prepare("UPDATE Booking SET Status = 'Confirmed' WHERE BookingID = ?");
    $stmt->bind_param("i", $booking_id_to_approve);
    $stmt->execute();
    $stmt->close();
    // Chuyển hướng lại để làm mới trang và xóa tham số khỏi URL
    header("Location: approve_bookings.php");
    exit();
}

// 2. Xử lý Hủy đơn
if (isset($_GET['decline_id']) && is_numeric($_GET['decline_id'])) {
    $booking_id_to_decline = (int)$_GET['decline_id'];
    $stmt = $conn->prepare("UPDATE Booking SET Status = 'Cancelled' WHERE BookingID = ?");
    $stmt->bind_param("i", $booking_id_to_decline);
    $stmt->execute();
    $stmt->close();
    header("Location: approve_bookings.php");
    exit();
}


// === PHẦN LẤY DỮ LIỆU ĐỂ HIỂN THỊ ===
$bookings = [];
// Câu lệnh JOIN để lấy thông tin từ nhiều bảng (Booking, User, Room)
$query = "
    SELECT b.BookingID, b.CheckinDate, b.CheckOutDate, b.TotalAmount, b.Status, b.CreatedAt,
           u.Username,
           r.HotelName
    FROM Booking b
    JOIN User u ON b.UserID = u.UserID
    JOIN Room r ON b.RoomID = r.RoomID
    ORDER BY b.CreatedAt DESC
";

$result = $conn->query($query);
if ($result) {
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Approve Bookings - HotelLux</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#2563EB',
            secondary: '#1E293B',
            gold: '#FBBF24',
          },
        },
      },
    };
  </script>
</head>
<body class="bg-secondary text-gray-300 font-[Inter]">

<div class="flex h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-gray-900 p-6 space-y-6">
    <div class="text-white text-2xl font-bold mb-8">Admin Panel</div>
    <nav class="flex flex-col space-y-4">
      <a href="admin_dashboard.php" class="hover:text-gold">🏠 Dashboard</a>
      <a href="admin_manage_rooms.php" class="hover:text-gold">🛏️ Manage Rooms</a>
      <!-- Đánh dấu trang hiện tại -->
      <a href="approve_bookings.php" class="text-gold font-semibold">📅 Approve Bookings</a>
      <a href="manage_users.php" class="hover:text-gold">👤 Manage Users</a>
      <a href="admin_statistics.php" class="hover:text-gold">📊 Statistics</a>
      <a href="admin_view_feedback.php" class="hover:text-gold">💬 View Feedback</a>
      <a href="logout.php" class="text-red-400 hover:text-white">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-10 overflow-y-auto">
    <h1 class="text-3xl font-bold text-white mb-6">Approve Bookings</h1>

    <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-md">
      <table class="w-full text-left">
        <thead class="bg-gray-700 text-white">
          <tr>
            <th class="px-6 py-3">ID</th>
            <th class="px-6 py-3">User</th>
            <th class="px-6 py-3">Hotel</th>
            <th class="px-6 py-3">Dates</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-300">
          <?php if (empty($bookings)): ?>
            <tr>
              <td colspan="6" class="text-center py-10">No bookings found.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
              <tr class="border-b border-gray-700 hover:bg-gray-700">
                <td class="px-6 py-4">#<?= $booking['BookingID'] ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($booking['Username']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($booking['HotelName']) ?></td>
                <td class="px-6 py-4"><?= date("d M Y", strtotime($booking['CheckinDate'])) ?> → <?= date("d M Y", strtotime($booking['CheckOutDate'])) ?></td>
                <td class="px-6 py-4">
                  <?php
                    // Đặt màu cho từng trạng thái
                    $status = htmlspecialchars($booking['Status']);
                    $statusClass = '';
                    if ($status == 'Confirmed') {
                        $statusClass = 'bg-green-600';
                    } elseif ($status == 'Cancelled') {
                        $statusClass = 'bg-red-600';
                    } else { // 'Pending' or other statuses
                        $statusClass = 'bg-yellow-600';
                    }
                  ?>
                  <span class="px-3 py-1 text-sm text-white rounded-full <?= $statusClass ?>">
                    <?= $status ?>
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <?php if ($booking['Status'] == 'Pending'): ?>
                    <a href="?approve_id=<?= $booking['BookingID'] ?>" class="text-green-400 hover:text-green-200 font-semibold" onclick="return confirm('Approve this booking?');">Approve</a>
                    <span class="mx-2 text-gray-500">|</span>
                    <a href="?decline_id=<?= $booking['BookingID'] ?>" class="text-red-400 hover:text-red-200 font-semibold" onclick="return confirm('Decline this booking?');">Decline</a>
                  <?php else: ?>
                    <span class="text-gray-500">No actions</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>