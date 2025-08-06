<?php
// Bắt đầu session ngay từ đầu tệp
session_start();

// Khởi tạo các biến để chứa thông báo
$success_message = null;
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include 'db.php';
  
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];
  $email = $_POST['email'];
  $role = 1; // Mặc định là Customer

  // Xử lý lỗi và gán vào biến $error_message thay vì echo script
  if ($password !== $confirmPassword) {
    $error_message = "Mật khẩu và xác nhận mật khẩu không khớp.";
  } elseif (strlen($password) < 8) {
    $error_message = "Mật khẩu phải có ít nhất 8 ký tự.";
  } else {
      // Kiểm tra độ mạnh mật khẩu...
      $strength = 0;
      if (preg_match('/[a-z]/', $password)) $strength++;
      if (preg_match('/[A-Z]/', $password)) $strength++;
      if (preg_match('/[0-9]/', $password)) $strength++;
      if (preg_match('/[^a-zA-Z0-9]/', $password)) $strength++;

      if ($strength < 2) {
          $error_message = "Mật khẩu quá yếu. Vui lòng thêm số, chữ hoa và ký tự đặc biệt.";
      }
  }

  // Nếu không có lỗi nào ở trên, tiếp tục xử lý
  if ($error_message === null) {
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      // Kiểm tra username đã tồn tại
      $checkQuery = "SELECT * FROM User WHERE Username = ?";
      $stmt = $conn->prepare($checkQuery);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          $error_message = "Username này đã tồn tại. Vui lòng chọn tên khác.";
      } else {
          // Thêm người dùng mới
          $insertQuery = "INSERT INTO User (Username, Password, Email, Role, CreatedAt, UpdatedAt) VALUES (?, ?, ?, ?, NOW(), NOW())";
          $stmt = $conn->prepare($insertQuery);
          $stmt->bind_param("sssi", $username, $passwordHash, $email, $role);

          if ($stmt->execute()) {
              // TỰ ĐỘNG ĐĂNG NHẬP SAU KHI ĐĂNG KÝ THÀNH CÔNG
              $newUserId = $conn->insert_id;
              $_SESSION['userid'] = $newUserId;
              $_SESSION['username'] = $username;
              $_SESSION['role'] = $role;

              // Gán thông báo thành công vào biến
              $success_message = "Đăng ký thành công! Bạn sẽ được tự động chuyển hướng...";

          } else {
              $error_message = "Lỗi khi đăng ký. Vui lòng thử lại.";
          }
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
  <title>Register - HotelLux</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#2563EB',
            secondary: '#1E293B',
            weak: '#f87171',
            medium: '#facc15',
            strong: '#4ade80'
          },
          backgroundImage: {
            'register-bg': "url('https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=1920&q=80')",
          },
        },
      },
    };
  </script>
</head>

<body class="bg-register-bg bg-cover bg-center text-secondary font-[Inter] flex items-center justify-center min-h-screen py-10">
  
  <?php if ($success_message): ?>
    <!-- KHỐI CODE BỊ THIẾU ĐÃ ĐƯỢC THÊM LẠI -->
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-8 rounded-lg shadow-xl text-center">
        <h3 class="text-2xl font-bold text-green-600 mb-4">Thành công!</h3>
        <p class="text-gray-700"><?php echo $success_message; ?></p>
      </div>
    </div>
    <script>
      setTimeout(function() {
        window.location.href = 'codeweb.php';
      }, 2000); // Chuyển hướng sau 2 giây
    </script>
  <?php endif; ?>

  <div class="bg-white bg-opacity-95 backdrop-blur-sm p-10 rounded-2xl shadow-lg w-full max-w-md">
    <h2 class="text-3xl font-bold text-center mb-6">Create an Account</h2>

    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo $error_message; ?></span>
        </div>
    <?php endif; ?>

    <form class="space-y-5" action="register.php" method="POST">
      <input type="text" name="username" placeholder="Username" class="w-full p-3 border border-gray-300 rounded-lg" required />
      <input type="email" name="email" placeholder="Email Address" class="w-full p-3 border border-gray-300 rounded-lg" required />
      
      <div class="relative">
        <input type="password" name="password" id="password" placeholder="Password (min 8 characters)" class="w-full p-3 border border-gray-300 rounded-lg" required onkeyup="checkPasswordStrength()" />
        <i id="togglePasswordIcon" class="fa-solid fa-eye absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer text-gray-500" onclick="togglePasswordVisibility('password', 'togglePasswordIcon')"></i>
      </div>
      
      <div class="relative">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="w-full p-3 border border-gray-300 rounded-lg" required />
        <i id="toggleConfirmPasswordIcon" class="fa-solid fa-eye absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer text-gray-500" onclick="togglePasswordVisibility('confirm_password', 'toggleConfirmPasswordIcon')"></i>
      </div>

      <div id="password-strength" class="text-sm font-medium"></div>
      <div class="w-full h-2 rounded bg-gray-200">
        <div id="password-bar" class="h-full rounded transition-all duration-300"></div>
      </div>
      <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-blue-700 transition">Register</button>
    </form>
    <p class="text-center text-sm text-gray-500 mt-6">Already have an account? <a href="login.php" class="text-primary font-medium">Login here</a></p>
  </div>

  <script>
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function checkPasswordStrength() {
      const strengthText = document.getElementById("password-strength");
      const bar = document.getElementById("password-bar");
      const password = document.getElementById("password").value;
      let strength = 0;

      if (password.length >= 8) strength++;
      if (/[A-Z]/.test(password)) strength++;
      if (/[0-9]/.test(password)) strength++;
      if (/[^A-Za-z0-9]/.test(password)) strength++;

      const baseBarClass = "h-full rounded transition-all duration-300";

      if (password.length === 0) {
        strengthText.textContent = "";
        strengthText.className = "text-sm font-medium";
        bar.style.width = "0%";
        bar.className = baseBarClass;
      } else if (strength <= 1) {
        strengthText.textContent = "Low Strength";
        strengthText.className = "text-sm font-medium text-weak";
        bar.style.width = "33%";
        bar.className = `${baseBarClass} bg-weak`;
      } else if (strength === 2 || strength === 3) {
        strengthText.textContent = "Medium Strength";
        strengthText.className = "text-sm font-medium text-medium";
        bar.style.width = "66%";
        bar.className = `${baseBarClass} bg-medium`;
      } else {
        strengthText.textContent = "High Strength";
        strengthText.className = "text-sm font-medium text-strong";
        bar.style.width = "100%";
        bar.className = `${baseBarClass} bg-strong`;
      }
    }
  </script>
</body>
</html>