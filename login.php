<?php
session_start();
// Khởi tạo biến thông báo lỗi
$login_error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php';
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if (empty($username) || empty($password)) {
        $login_error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $query = "SELECT UserID, Username, Password, Role FROM User WHERE Username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['Password'])) {
                // ĐĂNG NHẬP THÀNH CÔNG
                // Lưu thông tin vào session
                $_SESSION['userid'] = $user['UserID'];
                $_SESSION['username'] = $user['Username'];
                $_SESSION['role'] = (int)$user['Role'];
                if (isset($_POST['remember_me'])) {
                    // 1. Tạo các token an toàn
                    $selector = bin2hex(random_bytes(16));
                    $validator = bin2hex(random_bytes(32));
                    // 2. Mã hóa validator trước khi lưu vào DB
                    $validator_hash = password_hash($validator, PASSWORD_DEFAULT);      
                    // 3. Đặt cookie (thời hạn 30 ngày)
                    // Cookie chứa selector và validator chưa mã hóa
                    setcookie('remember_me_selector', $selector, time() + (86400 * 30), "/");
                    setcookie('remember_me_validator', $validator, time() + (86400 * 30), "/");
                    // 4. Lưu selector và validator đã mã hóa vào DB
                    $updateTokenStmt = $conn->prepare("UPDATE User SET remember_token_selector = ?, remember_token_validator_hash = ? WHERE UserID = ?");
                    $updateTokenStmt->bind_param("ssi", $selector, $validator_hash, $user['UserID']);
                    $updateTokenStmt->execute();
                    $updateTokenStmt->close();
                }
                if ($_SESSION['role'] === 2) {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: codeweb.php");
                }
                exit;
            } else {
                $login_error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
            }
        } else {
            $login_error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - HotelLux</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#2563EB',
            secondary: '#1E293B',
          },
          backgroundImage: {
            'login-bg': "url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1920&q=80')",
          },
        },
      },
    };
  </script>
</head>

<body class="bg-login-bg bg-cover bg-center text-secondary font-[Inter] min-h-screen flex items-center justify-center">
  <div class="bg-white bg-opacity-90 backdrop-blur-sm p-10 rounded-2xl shadow-xl w-full max-w-md">
    <h2 class="text-3xl font-bold text-center mb-6">Login to Your Account</h2>
    
    <?php if ($login_error): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $login_error; ?></span>
      </div>
    <?php endif; ?>

    <form class="space-y-5" action="login.php" method="POST">
      <input type="text" name="username" placeholder="Username" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-primary" required autofocus />
      <input type="password" name="password" placeholder="Password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-primary" required />
      <div class="flex items-center">
          <input type="checkbox" name="remember_me" id="remember_me" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
          <label for="remember_me" class="ml-2 block text-sm text-gray-900">
              Remember pass
          </label>
      </div>
      <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-blue-700 transition">Login</button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
      Don't have an account? 
      <a href="register.php" class="text-primary font-medium">Register here</a>
    </p>
  </div>
</body>
</html>
