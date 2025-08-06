<?php
session_start();
include 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['userid']) || (int)$_SESSION['role'] !== 2) {
    header("Location: login.php");
    exit();
}

// Xử lý hành động xóa feedback nếu có yêu cầu
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM Feedback WHERE FeedbackID = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_view_feedback.php"); // Tải lại trang để cập nhật danh sách
    exit();
}

// Lấy danh sách tất cả các feedback, JOIN với bảng User và Room để lấy thêm thông tin
$feedbacks = [];
$query = "
    SELECT f.FeedbackID, f.Rating, f.Comment, f.CreatedAt, 
           u.Username, 
           r.HotelName 
    FROM Feedback f
    JOIN User u ON f.UserID = u.UserID
    JOIN Room r ON f.RoomID = r.RoomID
    ORDER BY f.CreatedAt DESC
";
$result = $conn->query($query);
if ($result) {
    $feedbacks = $result->fetch_all(MYSQLI_ASSOC);
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>View Feedback - HotelLux</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
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
      <a href="admin_statistics.php" class="hover:text-gold">📊 Statistics</a>
      <!-- Thêm một liên kết mới để xem feedback -->
      <a href="admin_view_feedback.php" class="text-gold font-semibold">💬 View Feedback</a>
      <a href="logout.php" class="text-red-400 hover:text-white">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-10 overflow-y-auto">
    <h1 class="text-3xl font-bold text-white mb-6">Customer Feedback</h1>
    
    <div class="space-y-4">
      <?php if (empty($feedbacks)): ?>
        <p class="text-center text-gray-400 py-10">No feedback has been submitted yet.</p>
      <?php else: ?>
        <?php foreach ($feedbacks as $fb): ?>
          <div class="bg-gray-800 rounded-lg p-6 shadow-md">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-bold text-lg text-white"><?= htmlspecialchars($fb['Username']) ?></p>
                <p class="text-sm text-gray-400">về phòng: <span class="font-semibold"><?= htmlspecialchars($fb['HotelName']) ?></span></p>
                <p class="text-sm text-gray-500">vào lúc: <?= date("d M Y, H:i", strtotime($fb['CreatedAt'])) ?></p>
              </div>
              <a href="?delete_id=<?= $fb['FeedbackID'] ?>" onclick="return confirm('Are you sure you want to delete this feedback?');" class="text-red-500 hover:text-red-300 font-semibold">Delete</a>
            </div>
            <div class="my-4">
              <span class="text-yellow-400 text-xl"><?= str_repeat('★', $fb['Rating']) ?></span><span class="text-gray-600 text-xl"><?= str_repeat('☆', 5 - $fb['Rating']) ?></span>
            </div>
            <p class="text-gray-300 leading-relaxed"><?= nl2br(htmlspecialchars($fb['Comment'])) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>
</div>
</body>
</html>