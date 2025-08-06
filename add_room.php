<?php
session_start();
include 'db.php';

// Kiểm tra quyền Admin
if (!isset($_SESSION['userid']) || (int)$_SESSION['role'] !== 2) {
    header("Location: login.php");
    exit();
}

$success = null;
$error = null;

// Lấy danh sách RoomType
$roomTypeResult = $conn->query("SELECT UniqueID, TypeName FROM RoomType");
$roomTypes = $roomTypeResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotelName = $_POST['hotel_name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $roomTypeID = $_POST['room_type_id'];
    $rating = $_POST['rating'];
    $reviewCount = $_POST['review_count'];

    // Kiểm tra dữ liệu hợp lệ và RoomType tồn tại
    $typeExists = false;
    foreach ($roomTypes as $type) {
        if ($type['UniqueID'] == $roomTypeID) {
            $typeExists = true;
            break;
        }
    }

    if (!$typeExists) {
        $error = "RoomTypeID không tồn tại.";
    } elseif ($hotelName && $location && $price && $roomTypeID && $rating && $reviewCount) {
        $stmt = $conn->prepare("INSERT INTO Room (HotelName, Location, Price, RoomTypeID, Rating, ReviewCount) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsdi", $hotelName, $location, $price, $roomTypeID, $rating, $reviewCount);
        if ($stmt->execute()) {
            $success = "Thêm phòng mới thành công!";
        } else {
            $error = "Lỗi khi thêm phòng: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Vui lòng điền đầy đủ thông tin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Thêm Phòng - HotelLux</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            secondary: '#1E293B',
            gold: '#FBBF24',
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
</head>
<body class="bg-secondary text-white font-[Inter] min-h-screen flex items-center justify-center px-4">

  <div class="w-full max-w-xl bg-gray-900 p-8 rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-gold text-center mb-6">➕ Thêm Phòng Mới</h1>

    <?php if ($success): ?>
      <div class="bg-green-600 text-white p-3 rounded mb-4"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-600 text-white p-3 rounded mb-4"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block font-semibold mb-1">Hotel Name:</label>
        <input type="text" name="hotel_name" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600" required>
      </div>

      <div>
        <label class="block font-semibold mb-1">Location:</label>
        <input type="text" name="location" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600" required>
      </div>

      <div>
        <label class="block font-semibold mb-1">Price (USD):</label>
        <input type="number" step="0.01" name="price" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600" required>
      </div>

      <div>
        <label class="block font-semibold mb-1">Room Type:</label>
        <select name="room_type_id" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600" required>
          <option value="">-- Chọn loại phòng --</option>
          <?php foreach ($roomTypes as $type): ?>
            <option value="<?php echo $type['UniqueID']; ?>"><?php echo htmlspecialchars($type['TypeName']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block font-semibold mb-1">Rating (1-5):</label>
        <input type="number" step="0.1" min="1" max="5" name="rating" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600" required>
      </div>

      <div>
        <label class="block font-semibold mb-1">Review Count:</label>
        <input type="number" name="review_count" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600" required>
      </div>

      <div class="flex justify-between items-center">
        <a href="admin_manage_rooms.php" class="text-sm text-gray-300 hover:underline">← Quay lại</a>
        <button type="submit" class="bg-gold text-secondary font-bold px-6 py-2 rounded hover:bg-yellow-400 transition">
          Thêm Phòng
        </button>
      </div>
    </form>
  </div>

</body>
</html>
