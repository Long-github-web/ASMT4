<?php
session_start();
include 'db.php'; // Kết nối database

// === PHẦN KIỂM TRA QUYỀN ĐÃ ĐƯỢC SỬA LỖI ===
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 2) {
    header("Location: login.php");
    exit();
}

// === PHẦN LẤY DỮ LIỆU ===
$totalRooms = 0;
$totalBookings = 0;
$totalUsers = 0;
$totalFeedback = 0; 

// 1. Đếm tổng số phòng
$resultRooms = $conn->query("SELECT COUNT(*) as count FROM Room");
if ($resultRooms) {
    $totalRooms = $resultRooms->fetch_assoc()['count'];
}

// 2. Đếm tổng số đơn đặt hàng
$resultBookings = $conn->query("SELECT COUNT(*) as count FROM Booking");
if ($resultBookings) {
    $totalBookings = $resultBookings->fetch_assoc()['count'];
}

// 3. Đếm tổng số người dùng
$resultUsers = $conn->query("SELECT COUNT(*) as count FROM User");
if ($resultUsers) {
    $totalUsers = $resultUsers->fetch_assoc()['count'];
}
// 4. Đếm tổng số phản hồi (MỚI)
$resultFeedback = $conn->query("SELECT COUNT(*) as count FROM Feedback");
if ($resultFeedback) {
    $totalFeedback = $resultFeedback->fetch_assoc()['count'];
}
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - HotelLux</title>
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
  <aside class="w-64 bg-gray-900 p-6 space-y-6">
    <div class="text-white text-2xl font-bold mb-8">
      Admin Panel
    </div>
    <nav class="flex flex-col space-y-4">
      <a href="admin_dashboard.php" class="text-gold font-semibold hover:text-white">🏠 Dashboard</a>
      <a href="admin_manage_rooms.php" class="hover:text-gold">🛏️ Manage Rooms</a>
      <a href="approve_bookings.php" class="hover:text-gold">📅 Approve Bookings</a>
      <a href="manage_users.php" class="hover:text-gold">👤 Manage Users</a>
      <a href="admin_statistics.php" class="hover:text-gold">📊 Statistics</a>
      <a href="admin_view_feedback.php" class="hover:text-gold">💬 View Feedback</a>
      <a href="logout.php" class="text-red-400 hover:text-white">🚪 Logout</a>
    </nav>
  </aside>

  <main class="flex-1 p-10">
    <h1 class="text-3xl font-bold text-white mb-6">Welcome, Admin 👋</h1>
    <p class="text-gray-400 text-lg">This is your control center where you can manage rooms, bookings, users, and more.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
      <div class="bg-gray-800 rounded-xl p-6 text-center shadow-lg hover:scale-105 transition">
        <p class="text-lg text-gray-400">Total Rooms</p>
        <p class="text-3xl font-bold text-white"><?= number_format($totalRooms) ?></p>
      </div>
      <div class="bg-gray-800 rounded-xl p-6 text-center shadow-lg hover:scale-105 transition">
        <p class="text-lg text-gray-400">Bookings</p>
        <p class="text-3xl font-bold text-white"><?= number_format($totalBookings) ?></p>
      </div>
      <div class="bg-gray-800 rounded-xl p-6 text-center shadow-lg hover:scale-105 transition">
        <p class="text-lg text-gray-400">Users</p>
        <p class="text-3xl font-bold text-white"><?= number_format($totalUsers) ?></p>
      </div>
      <div class="bg-gray-800 rounded-xl p-6 text-center shadow-lg hover:scale-105 transition">
        <p class="text-lg text-gray-400">Feedback</p>
        <p class="text-3xl font-bold text-white"><?= number_format($totalFeedback) ?></p>
      </div>
    </div>
  </main>
</div>
</body>
</html>