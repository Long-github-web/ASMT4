<?php
session_start();
include 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['userid']) || (int)$_SESSION['role'] !== 2) {
    header("Location: login.php");
    exit();
}

// Xử lý cập nhật phòng nếu có yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = (int)$_POST['edit_id'];
    $hotelName = $_POST['hotel_name'];
    $location = $_POST['location'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE Room SET HotelName = ?, Location = ?, Price = ? WHERE RoomID = ?");
    $stmt->bind_param("ssdi", $hotelName, $location, $price, $edit_id);
    $stmt->execute();
    $stmt->close();
}

// Xử lý xóa phòng nếu có yêu cầu
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];

    // Xóa phòng
    $stmt = $conn->prepare("DELETE FROM Room WHERE RoomID = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    // Reset AUTO_INCREMENT về giá trị nhỏ nhất chưa dùng
    $conn->query("SET @next_id := (SELECT MIN(RoomID)+1 FROM Room WHERE RoomID+1 NOT IN (SELECT RoomID FROM Room))");
    $conn->query("SET @sql := CONCAT('ALTER TABLE Room AUTO_INCREMENT = ', IFNULL(@next_id, (SELECT MAX(RoomID)+1 FROM Room)))");
    $conn->query("PREPARE stmt FROM @sql");
    $conn->query("EXECUTE stmt");
    $conn->query("DEALLOCATE PREPARE stmt");

    header("Location: admin_manage_rooms.php");
    exit();
}

// Lấy danh sách phòng
$rooms = [];
// Lấy danh sách phòng
$rooms = [];
$query = "
    SELECT 
        r.RoomID, 
        r.HotelName, 
        r.Location, 
        rt.TypeName, 
        r.Price,
        -- Đây là phần tính toán trạng thái động
        CASE 
            WHEN b.BookingID IS NOT NULL THEN 'Booked' 
            ELSE 'Available' 
        END AS current_status
    FROM Room r
    JOIN RoomType rt ON r.RoomTypeID = rt.UniqueID
    LEFT JOIN Booking b ON r.RoomID = b.RoomID 
        AND b.Status = 'Confirmed'
        AND CURDATE() BETWEEN b.CheckinDate AND b.CheckOutDate
    ORDER BY r.RoomID;
";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Rooms - HotelLux</title>
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
    <div class="text-white text-2xl font-bold mb-8">Admin Panel</div>
    <nav class="flex flex-col space-y-4">
      <a href="admin_dashboard.php" class="hover:text-gold">🏠 Dashboard</a>
      <a href="admin_manage_rooms.php" class="text-gold font-semibold">🛏️ Manage Rooms</a>
      <a href="approve_bookings.php" class="hover:text-gold">📅 Approve Bookings</a>
      <a href="manage_users.php" class="hover:text-gold">👤 Manage Users</a>
      <a href="admin_statistics.php" class="hover:text-gold">📊 Statistics</a>
      <a href="logout.php" class="text-red-400 hover:text-white">🚪 Logout</a>
    </nav>
  </aside>

  <main class="flex-1 p-10">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-white">Manage Rooms</h1>
      <a href="add_room.php" class="bg-gold text-secondary font-semibold px-4 py-2 rounded hover:bg-yellow-400 transition">➕ Add Room</a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full bg-gray-800 rounded-lg shadow-md">
        <thead>
          <tr class="bg-gray-700 text-white text-left">
            <th class="px-6 py-3">Room ID</th>
            <th class="px-6 py-3">Hotel Name</th>
            <th class="px-6 py-3">Location</th>
            <th class="px-6 py-3">Type</th>
            <th class="px-6 py-3">Price</th>
            <th class="px-6 py-3">Actions</th>
          </tr>
        </thead>
        <!-- ... phần thead của bảng ... -->
<thead class="bg-gray-700 text-white text-left">
  <tr>
    <th class="px-6 py-3">Room ID</th>
    <th class="px-6 py-3">Hotel Name</th>
    <th class="px-6 py-3">Location</th>
    <th class="px-6 py-3">Type</th>
    <th class="px-6 py-3">Price</th>
    <!-- THÊM CỘT MỚI -->
    <th class="px-6 py-3">Current Status</th> 
    <th class="px-6 py-3">Actions</th>
  </tr>
</thead>
<tbody>
  <?php foreach ($rooms as $room): ?>
    <tr class="border-b border-gray-700 hover:bg-gray-700">
      <form method="POST">
      <td class="px-6 py-4">#<?php echo $room['RoomID']; ?> <input type="hidden" name="edit_id" value="<?php echo $room['RoomID']; ?>"></td>
      <td class="px-6 py-4"><input type="text" name="hotel_name" value="<?php echo htmlspecialchars($room['HotelName']); ?>" class="bg-transparent border-b border-gray-500 w-full"></td>
      <td class="px-6 py-4"><input type="text" name="location" value="<?php echo htmlspecialchars($room['Location']); ?>" class="bg-transparent border-b border-gray-500 w-full"></td>
      <td class="px-6 py-4"><?php echo htmlspecialchars($room['TypeName']); ?></td>
      <td class="px-6 py-4"><input type="text" name="price" value="<?php echo number_format($room['Price'], 0, '.', '.'); ?>" class="bg-transparent border-b border-gray-500 w-full"></td>
      
      <!-- CẬP NHẬT CỘT TRẠNG THÁI -->
      <td class="px-6 py-4">
        <?php
            $status = $room['current_status'];
            $statusClass = $status == 'Available' ? 'text-green-400' : 'text-yellow-400';
        ?>
        <span class="font-semibold <?= $statusClass ?>"><?= $status ?></span>
      </td>

      <td class="px-6 py-4 space-x-2">
        <button type="submit" class="text-blue-400 hover:text-blue-200">Save</button>
        <a href="?delete=<?php echo $room['RoomID']; ?>" class="text-red-400 hover:text-red-200" onclick="return confirm('Xác nhận xóa phòng này?');">Delete</a>
      </td>
      </form>
    </tr>
  <?php endforeach; ?>
</tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
