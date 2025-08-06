<?php
session_start();
include 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION['userid'];
$successMsg = $errorMsg = "";

// Xử lý cập nhật thông tin cá nhân
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];

    $stmt = $conn->prepare("UPDATE User SET Fullname = ?, Email = ?, Phonenumber = ?, UpdatedAt = NOW() WHERE UserID = ?");
    $stmt->bind_param("sssi", $fullname, $email, $phonenumber, $userid);
    
    if ($stmt->execute()) {
        $successMsg = "Cập nhật thông tin thành công.";
    } else {
        $errorMsg = "Lỗi khi cập nhật thông tin.";
    }
    $stmt->close();
}

// Xử lý đổi mật khẩu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Lấy mật khẩu cũ từ DB
    $stmt = $conn->prepare("SELECT Password FROM User WHERE UserID = ?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $hashed = $result->fetch_assoc()['Password'];

    if (!password_verify($current_pass, $hashed)) {
        $errorMsg = "Mật khẩu hiện tại không đúng.";
    } elseif ($new_pass !== $confirm_pass) {
        $errorMsg = "Mật khẩu mới không khớp.";
    } else {
        $new_hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE User SET Password = ?, UpdatedAt = NOW() WHERE UserID = ?");
        $update->bind_param("si", $new_hashed, $userid);
        if ($update->execute()) {
            $successMsg = "Đổi mật khẩu thành công.";
        } else {
            $errorMsg = "Đã có lỗi xảy ra khi đổi mật khẩu.";
        }
        $update->close();
    }
    $stmt->close();
}

// Lấy thông tin người dùng
$stmt = $conn->prepare("SELECT Username, Fullname, Email, Phonenumber, CreatedAt FROM User WHERE UserID = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Profile - HotelLux</title>
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
<body class="bg-secondary text-white font-[Inter]">

<!-- HEADER -->
<header class="w-full bg-secondary fixed top-0 left-0 z-50">
  <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
    <a href="codeweb.php" class="text-2xl font-bold text-gold">HotelLux</a>
    <a href="codeweb.php" class="border border-gold text-gold px-4 py-2 rounded-full hover:bg-gold hover:text-secondary transition duration-300">Back to Home</a>
  </div>
</header>
<div class="pt-28"></div>

<!-- MAIN -->
<main class="max-w-3xl mx-auto px-4 py-10">
  <h2 class="text-3xl font-bold mb-6 text-gold text-center">Thông Tin Cá Nhân</h2>

  <?php if ($successMsg): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= $successMsg ?></div>
  <?php elseif ($errorMsg): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= $errorMsg ?></div>
  <?php endif; ?>

  <form method="POST" class="space-y-4 bg-gray-800 p-6 rounded-lg shadow">
    <div>
      <label class="block mb-1 font-semibold">Tên người dùng</label>
      <input type="text" value="<?= htmlspecialchars($user['Username']) ?>" class="w-full bg-gray-700 text-white px-4 py-2 rounded" disabled />
    </div>
    <div>
      <label class="block mb-1 font-semibold">Họ và tên</label>
      <input type="text" name="fullname" value="<?= htmlspecialchars($user['Fullname']) ?>" class="w-full bg-gray-700 text-white px-4 py-2 rounded" required />
    </div>
    <div>
      <label class="block mb-1 font-semibold">Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['Email']) ?>" class="w-full bg-gray-700 text-white px-4 py-2 rounded" required />
    </div>
    <div>
      <label class="block mb-1 font-semibold">Số điện thoại</label>
      <input type="text" name="phonenumber" value="<?= htmlspecialchars($user['Phonenumber']) ?>" class="w-full bg-gray-700 text-white px-4 py-2 rounded" />
    </div>
    <button type="submit" name="update_profile" class="bg-gold text-secondary font-bold px-6 py-2 rounded hover:bg-yellow-400 transition">Cập nhật thông tin</button>
  </form>

  <!-- ĐỔI MẬT KHẨU -->
  <div class="mt-12">
    <h2 class="text-2xl font-bold mb-4 text-white text-center">Đổi Mật Khẩu</h2>
    <form method="POST" class="space-y-4 bg-gray-800 p-6 rounded-lg shadow">
      <div>
        <label class="block mb-1 font-semibold">Mật khẩu hiện tại</label>
        <input type="password" name="current_password" class="w-full bg-gray-700 text-white px-4 py-2 rounded" required />
      </div>
      <div>
        <label class="block mb-1 font-semibold">Mật khẩu mới</label>
        <input type="password" name="new_password" class="w-full bg-gray-700 text-white px-4 py-2 rounded" required />
      </div>
      <div>
        <label class="block mb-1 font-semibold">Nhập lại mật khẩu mới</label>
        <input type="password" name="confirm_password" class="w-full bg-gray-700 text-white px-4 py-2 rounded" required />
      </div>
      <button type="submit" name="change_password" class="bg-gold text-secondary font-bold px-6 py-2 rounded hover:bg-yellow-400 transition">
  Đổi mật khẩu
</button>

    </form>
  </div>
</main>

<?php $conn->close(); ?>
</body>
</html>
