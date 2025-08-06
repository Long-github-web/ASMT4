<?php
session_start();
include 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['userid']) || (int)$_SESSION['role'] !== 2) {
    header("Location: login.php");
    exit();
}

// === PHẦN LẤY DỮ LIỆU CHO BIỂU ĐỒ ===

// 1. Dữ liệu Doanh thu hàng tháng (12 tháng gần nhất)
// Chỉ tính các đơn hàng đã 'Confirmed'
$revenueQuery = "
    SELECT 
        YEAR(CreatedAt) as year, 
        MONTH(CreatedAt) as month, 
        SUM(TotalAmount) as monthly_revenue
    FROM Booking
    WHERE Status = 'Confirmed' AND CreatedAt >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY YEAR(CreatedAt), MONTH(CreatedAt)
    ORDER BY year, month;
";
$revenueResult = $conn->query($revenueQuery);
$revenueLabels = [];
$revenueData = [];
if ($revenueResult) {
    while($row = $revenueResult->fetch_assoc()) {
        // Chuyển số tháng thành tên tháng (e.g., 7 -> "July")
        $monthName = date("F", mktime(0, 0, 0, $row['month'], 10));
        $revenueLabels[] = $monthName . " " . $row['year'];
        $revenueData[] = $row['monthly_revenue'];
    }
}

// 2. Dữ liệu Thống kê các loại phòng được đặt nhiều nhất (Pie Chart)
$roomTypeQuery = "
    SELECT rt.TypeName, COUNT(b.BookingID) as booking_count
    FROM Booking b
    JOIN Room r ON b.RoomID = r.RoomID
    JOIN RoomType rt ON r.RoomTypeID = rt.UniqueID
    WHERE b.Status = 'Confirmed'
    GROUP BY rt.TypeName
    ORDER BY booking_count DESC;
";
$roomTypeResult = $conn->query($roomTypeQuery);
$roomTypeLabels = [];
$roomTypeData = [];
if ($roomTypeResult) {
    while($row = $roomTypeResult->fetch_assoc()) {
        $roomTypeLabels[] = $row['TypeName'];
        $roomTypeData[] = $row['booking_count'];
    }
}


// Chuyển dữ liệu PHP thành chuỗi JSON để JavaScript có thể đọc
$jsonRevenueLabels = json_encode($revenueLabels);
$jsonRevenueData = json_encode($revenueData);
$jsonRoomTypeLabels = json_encode($roomTypeLabels);
$jsonRoomTypeData = json_encode($roomTypeData);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Statistics - HotelLux</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Thêm thư viện Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
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
      <a href="approve_bookings.php" class="hover:text-gold">📅 Approve Bookings</a>
      <a href="manage_users.php" class="hover:text-gold">👤 Manage Users</a>
      <!-- Đánh dấu trang hiện tại -->
      <a href="admin_statistics.php" class="text-gold font-semibold">📊 Statistics</a>
      <a href="logout.php" class="text-red-400 hover:text-white">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-10 overflow-y-auto">
    <h1 class="text-3xl font-bold text-white mb-8">Statistics & Reports</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      
      <!-- Biểu đồ Doanh thu -->
      <div class="bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-white mb-4">Monthly Revenue (Last 12 Months)</h2>
        <!-- Thẻ canvas để Chart.js vẽ biểu đồ vào -->
        <canvas id="revenueChart"></canvas>
      </div>

      <!-- Biểu đồ Loại phòng -->
      <div class="bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-white mb-4">Popular Room Types</h2>
        <div class="max-w-xs mx-auto">
          <canvas id="roomTypeChart"></canvas>
        </div>
      </div>

    </div>
  </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // === DỮ LIỆU TỪ PHP ĐƯỢC CHUYỂN SANG JAVASCRIPT ===
    const revenueLabels = <?= $jsonRevenueLabels ?>;
    const revenueData = <?= $jsonRevenueData ?>;
    const roomTypeLabels = <?= $jsonRoomTypeLabels ?>;
    const roomTypeData = <?= $jsonRoomTypeData ?>;

    // === CẤU HÌNH VÀ VẼ BIỂU ĐỒ DOANH THU ===
    const ctxRevenue = document.getElementById('revenueChart');
    if (ctxRevenue) {
        new Chart(ctxRevenue, {
            type: 'bar', // Loại biểu đồ: cột
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue (VND)',
                    data: revenueData,
                    backgroundColor: 'rgba(251, 191, 36, 0.6)', // Màu vàng gold
                    borderColor: 'rgba(251, 191, 36, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // === CẤU HÌNH VÀ VẼ BIỂU ĐỒ LOẠI PHÒNG ===
    const ctxRoomType = document.getElementById('roomTypeChart');
    if (ctxRoomType) {
        new Chart(ctxRoomType, {
            type: 'doughnut', // Loại biểu đồ: tròn (doughnut)
            data: {
                labels: roomTypeLabels,
                datasets: [{
                    label: 'Bookings',
                    data: roomTypeData,
                    backgroundColor: [ // Mảng các màu cho từng phần
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
            }
        });
    }
});
</script>

</body>
</html>