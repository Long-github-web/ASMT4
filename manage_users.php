<?php
session_start();
include 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['userid']) || (int)$_SESSION['role'] !== 2) {
    header("Location: login.php");
    exit();
}

$current_admin_id = (int)$_SESSION['userid'];

// PHẦN XỬ LÝ HÀNH ĐỘNG (Cập nhật vai trò / Xóa)

// 1. Xử lý Cập nhật vai trò
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_id_to_update = (int)$_POST['user_id'];
    $new_role = (int)$_POST['role'];

    // Ngăn admin thay đổi vai trò của chính mình
    if ($user_id_to_update !== $current_admin_id) {
        if ($new_role === 1 || $new_role === 2) { // Chỉ chấp nhận vai trò hợp lệ
            $stmt = $conn->prepare("UPDATE User SET Role = ? WHERE UserID = ?");
            $stmt->bind_param("ii", $new_role, $user_id_to_update);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: manage_users.php");
    exit();
}

// 2. Xử lý Xóa người dùng
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $user_id_to_delete = (int)$_GET['delete_id'];

    // Ngăn admin tự xóa chính mình
    if ($user_id_to_delete !== $current_admin_id) {
        // Trước khi xóa User, cần xử lý các bản ghi liên quan
        $stmt_delete_bookings = $conn->prepare("DELETE FROM Booking WHERE UserID = ?");
        $stmt_delete_bookings->bind_param("i", $user_id_to_delete);
        $stmt_delete_bookings->execute();
        $stmt_delete_bookings->close();

        $stmt_delete_user = $conn->prepare("DELETE FROM User WHERE UserID = ?");
        $stmt_delete_user->bind_param("i", $user_id_to_delete);
        $stmt_delete_user->execute();
        $stmt_delete_user->close();
    }
    header("Location: manage_users.php");
    exit();
}

// PHẦN LẤY DỮ LIỆU ĐỂ HIỂN THỊ
$users = [];
$query = "SELECT UserID, Username, Email, Role, CreatedAt FROM User ORDER BY CreatedAt DESC";
$result = $conn->query($query);
if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Users - HotelLux</title>
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
      <a href="approve_bookings.php" class="hover:text-gold">📅 Approve Bookings</a>
      <!-- Đánh dấu trang hiện tại -->
      <a href="manage_users.php" class="text-gold font-semibold">👤 Manage Users</a>
      <a href="admin_statistics.php" class="hover:text-gold">📊 Statistics</a>
      <a href="admin_view_feedback.php" class="hover:text-gold">💬 View Feedback</a>
      <a href="logout.php" class="text-red-400 hover:text-white">🚪 Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-10 overflow-y-auto">
    <h1 class="text-3xl font-bold text-white mb-6">Manage Users</h1>

    <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-md">
      <table class="w-full text-left">
        <thead class="bg-gray-700 text-white">
          <tr>
            <th class="px-6 py-3">ID</th>
            <th class="px-6 py-3">Username</th>
            <th class="px-6 py-3">Email</th>
            <th class="px-6 py-3">Role</th>
            <th class="px-6 py-3">Joined Date</th>
            <th class="px-6 py-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-300">
          <?php if (empty($users)): ?>
            <tr><td colspan="6" class="text-center py-10">No users found.</td></tr>
          <?php else: ?>
            <?php foreach ($users as $user): ?>
              <tr class="border-b border-gray-700">
                <form method="POST">
                  <input type="hidden" name="user_id" value="<?= $user['UserID'] ?>">
                  <td class="px-6 py-4">#<?= $user['UserID'] ?></td>
                  <td class="px-6 py-4"><?= htmlspecialchars($user['Username']) ?></td>
                  <td class="px-6 py-4"><?= htmlspecialchars($user['Email']) ?></td>
                  <td class="px-6 py-4">
                    <?php if ((int)$user['UserID'] === $current_admin_id): ?>
                      <span class="font-bold text-gold">Admin (Current)</span>
                    <?php else: ?>
                      <select name="role" class="bg-gray-700 border border-gray-600 rounded px-2 py-1">
                        <option value="1" <?= (int)$user['Role'] === 1 ? 'selected' : '' ?>>Customer</option>
                        <option value="2" <?= (int)$user['Role'] === 2 ? 'selected' : '' ?>>Admin</option>
                      </select>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4"><?= date("d M Y", strtotime($user['CreatedAt'])) ?></td>
                  <td class="px-6 py-4 text-center">
                    <?php if ((int)$user['UserID'] !== $current_admin_id): ?>
                      <button type="submit" name="update_role" class="text-blue-400 hover:text-blue-200 font-semibold">Save</button>
                      <span class="mx-2 text-gray-500">|</span>
                      <a href="?delete_id=<?= $user['UserID'] ?>" class="text-red-400 hover:text-red-200 font-semibold" onclick="return confirm('WARNING: This will delete the user and all their bookings. Continue?');">Delete</a>
                    <?php else: ?>
                      <span class="text-gray-500">No actions</span>
                    <?php endif; ?>
                  </td>
                </form>
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
