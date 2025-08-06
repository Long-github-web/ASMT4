<?php
session_start();
include 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['userid']) || (int)$_SESSION['role'] !== 2) {
    header("Location: login.php");
    exit();
}

// Xử lý xóa feedback
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM Feedback WHERE FeedbackID = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: admin_feedback.php");
    exit();
}

// Lấy danh sách feedback
$feedbacks = [];
$query = "
    SELECT f.*, u.Username, r.HotelName 
    FROM Feedback f
    JOIN User u ON f.UserID = u.UserID
    JOIN Room r ON f.RoomID = r.RoomID
    ORDER BY f.CreatedAt DESC
";
$result = $conn->query($query);
if ($result) {
    $feedbacks = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>View Feedback - HotelLux</title>
</head>
<body class="bg-secondary text-gray-300 font-[Inter]">
<div class="flex h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-gray-900 p-6 space-y-6">
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-10 overflow-y-auto">
    <h1 class="text-3xl font-bold text-white mb-6">Customer Feedback</h1>
    <div class="space-y-4">
      <?php if(empty($feedbacks)): ?>
        <p class="text-center text-gray-400 py-10">No feedback yet.</p>
      <?php else: ?>
        <?php foreach ($feedbacks as $fb): ?>
          <div class="bg-gray-800 rounded-lg p-4">
            <div class="flex justify-between items-start">
              <div>
                <p class="font-bold text-lg text-white"><?= htmlspecialchars($fb['Username']) ?> - <span class="text-gold"><?= str_repeat('★', $fb['Rating']) . str_repeat('☆', 5 - $fb['Rating']) ?></span></p>
                <p class="text-sm text-gray-400">về phòng: <?= htmlspecialchars($fb['HotelName']) ?></p>
                <p class="text-sm text-gray-500">vào lúc: <?= date("d M Y, H:i", strtotime($fb['CreatedAt'])) ?></p>
              </div>
              <a href="?delete_id=<?= $fb['FeedbackID'] ?>" onclick="return confirm('Are you sure you want to delete this feedback?');" class="text-red-500 hover:text-red-300">Delete</a>
            </div>
            <p class="mt-3 text-gray-300"><?= nl2br(htmlspecialchars($fb['Comment'])) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>
</div>
</body>
</html>
