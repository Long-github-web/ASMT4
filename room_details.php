<?php
session_start();
include 'db.php';
$booking_success = null;
$booking_error = null;
$feedback_success = null;
$feedback_error = null;
if (isset($_GET['feedback_success'])) {
    $feedback_success = "Cảm ơn bạn đã gửi phản hồi!";
}
if (isset($_GET['feedback_error'])) {
    $feedback_error = "Đã có lỗi xảy ra. Vui lòng điền đầy đủ thông tin.";
}
// Nếu form được gửi (qua POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'])) {
    $user_id = $_SESSION['userid'] ?? null;
    $room_id = $_POST['room_id'];
    $checkin = $_POST['checkin_date'];
    $checkout = $_POST['checkout_date'];

    if (!$user_id) {
        $booking_error = "Bạn cần đăng nhập để đặt phòng.";
    } elseif ($checkin >= $checkout) {
        $booking_error = "Ngày check-out phải sau ngày check-in.";
    } else {
        // Kiểm tra phòng có trống không
        $stmt = $conn->prepare("SELECT * FROM Booking WHERE RoomID = ? AND CheckinDate < ? AND CheckOutDate > ?");
        $stmt->bind_param("iss", $room_id, $checkout, $checkin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $booking_error = "Phòng đã được đặt trong khoảng thời gian này.";
        } else {
            $priceStmt = $conn->prepare("SELECT Price FROM Room WHERE RoomID = ?");
            $priceStmt->bind_param("i", $room_id);
            $priceStmt->execute();
            $priceResult = $priceStmt->get_result()->fetch_assoc();
            $pricePerDay = $priceResult['Price'];

            $days = (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24);
            $total = $days * $pricePerDay;

            $insert = $conn->prepare("INSERT INTO Booking (UserID, RoomID, CheckinDate, CheckOutDate, TotalAmount, Status, CreatedAt, UpdatedAt) 
                                      VALUES (?, ?, ?, ?, ?, 'Confirmed', NOW(), NOW())");
            $insert->bind_param("iissd", $user_id, $room_id, $checkin, $checkout, $total);
            $insert->execute();

            $booking_success = "🎉 Đặt phòng thành công!";
        }
    }
}

if (!isset($_GET['room_id']) || !is_numeric($_GET['room_id'])) {
    die("Invalid Room ID.");
}
$roomId = $_GET['room_id'];

// Lấy thông tin phòng
$roomStmt = $conn->prepare("SELECT r.*, rt.TypeName, rt.Description as TypeDescription FROM Room r JOIN RoomType rt ON r.RoomTypeID = rt.UniqueID WHERE r.RoomID = ?");
$roomStmt->bind_param("i", $roomId);

$roomStmt->execute();
$roomResult = $roomStmt->get_result();
if ($roomResult->num_rows === 0) {
    die("Room not found.");
}
$room = $roomResult->fetch_assoc();
$roomStmt->close();

// LẤY BỘ ẢNH THẬT TỪ DATABASE
$galleryImages = [];
$imageStmt = $conn->prepare("SELECT ImageURL FROM RoomImage WHERE RoomID = ?");
$imageStmt->bind_param("i", $roomId);
$imageStmt->execute();
$imageResult = $imageStmt->get_result();
if ($imageResult->num_rows > 0) {
    while($img = $imageResult->fetch_assoc()) {
        $galleryImages[] = $img['ImageURL'];
    }
} else {
    $galleryImages[] = 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=1920&q=80';
}
$imageStmt->close();

$conn->close();

$amenities = [ 'wifi' => 'Free WiFi', 'tv' => 'Flat-screen TV', 'air-conditioning' => 'Air conditioning', 'mountain-sun' => 'Mountain view', 'square-parking' => 'Free parking', 'mug-saucer' => 'Tea/Coffee maker' ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($room['HotelName']); ?> - HotelLux</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
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
    <header class="w-full bg-secondary fixed top-0 left-0 z-50">
      <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
        <a href="codeweb.php" class="text-2xl font-bold text-gold">HotelLux</a>
        <a href="codeweb.php?return_to=<?php echo $room['RoomTypeID']; ?>&return_room_id=<?php echo $room['RoomID']; ?>" class="border border-gold text-gold px-4 py-2 rounded-full hover:bg-gold hover:text-secondary transition-transform duration-300 hover:scale-105">Back to Rooms</a>
      </div>
    </header>
    <div class="pt-28"></div> 

    <main class="max-w-6xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <div class="grid grid-cols-2 grid-rows-2 gap-2 rounded-2xl overflow-hidden h-96">
                    <a href="<?php echo $galleryImages[0] ?? ''; ?>" data-fancybox="gallery-<?php echo $room['RoomID']; ?>" class="col-span-2 row-span-2">
                        <img src="<?php echo $galleryImages[0] ?? ''; ?>" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity">
                    </a>
                    <?php if (isset($galleryImages[1])): ?>
                    <a href="<?php echo $galleryImages[1]; ?>" data-fancybox="gallery-<?php echo $room['RoomID']; ?>" class="hidden sm:block">
                        <img src="<?php echo $galleryImages[1]; ?>" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity">
                    </a>
                    <?php endif; ?>
                    <?php 
                        $visibleImagesOnGrid = 2;
                        $totalImages = count($galleryImages);
                        $remainingImages = $totalImages - $visibleImagesOnGrid; 
                        if ($totalImages >= 3): 
                    ?>
                    <a href="<?php echo $galleryImages[2]; ?>" data-fancybox="gallery-<?php echo $room['RoomID']; ?>" class="hidden sm:block relative cursor-pointer group">
                        <img src="<?php echo $galleryImages[2]; ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                        <?php if ($remainingImages > 1): ?>
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <span class="text-white text-4xl font-bold">+<?php echo $remainingImages - 1; ?></span>
                        </div>
                        <?php endif; ?>
                    </a>
                    <?php endif; ?>
                    <div class="hidden">
                        <?php for ($i = 3; $i < count($galleryImages); $i++): ?>
                            <a href="<?php echo $galleryImages[$i]; ?>" data-fancybox="gallery-<?php echo $room['RoomID']; ?>"></a>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="py-8 border-b border-gray-700">
                    <h1 class="text-4xl font-bold text-white"><?php echo htmlspecialchars($room['HotelName']); ?></h1>
                    <p class="text-lg text-gray-400 mt-2"><?php echo htmlspecialchars($room['Location']); ?></p>
                    <div class="mt-6">
                        <h2 class="text-2xl font-semibold text-white mb-4">About this room (<?php echo htmlspecialchars($room['TypeName']); ?>)</h2>
                        <p class="text-gray-400 leading-relaxed"><?php echo htmlspecialchars($room['TypeDescription']); ?></p>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-white mb-4">Amenities</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <?php foreach ($amenities as $icon => $text): ?>
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-<?php echo $icon; ?> text-gold"></i>
                                    <span><?php echo $text; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="py-8">
                    <h2 class="text-2xl font-semibold text-white mb-4">Guest Reviews</h2>
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-600 text-white text-2xl font-bold w-16 h-16 rounded-lg flex items-center justify-center"><?php echo htmlspecialchars($room['Rating']); ?></div>
                        <div>
                            <p class="text-xl font-bold text-white">Excellent</p>
                            <p class="text-gray-400">Based on <?php echo number_format($room['ReviewCount']); ?> reviews</p>
                        </div>
                    </div>
                </div>
                <!-- Feedback Section -->
<section id="feedback" class="py-8">
  <div class="max-w-xl mx-auto px-4">
    <h2 class="text-2xl font-bold text-center text-white mb-6">Leave Feedback</h2>

    <!-- Hiển thị thông báo feedback -->
    <?php if ($feedback_success): ?>
        <div class="bg-green-600 text-white p-3 rounded mb-4 text-center"><?= $feedback_success ?></div>
    <?php elseif ($feedback_error): ?>
        <div class="bg-red-600 text-white p-3 rounded mb-4 text-center"><?= $feedback_error ?></div>
    <?php endif; ?>

    <!-- Form đã được cập nhật -->
    <form action="process_feedback.php" method="POST" class="bg-gray-900 p-6 rounded-2xl space-y-4">
      <!-- Trường ẩn để gửi RoomID -->
      <input type="hidden" name="room_id" value="<?= htmlspecialchars($room['RoomID']) ?>">
      
      <div>
        <label class="block font-semibold mb-1">Your Rating (1-5)</label>
        <select name="rating" class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg text-white" required>
            <option value="">-- Select a rating --</option>
            <option value="5">★★★★★ (Excellent)</option>
            <option value="4">★★★★☆ (Great)</option>
            <option value="3">★★★☆☆ (Good)</option>
            <option value="2">★★☆☆☆ (Fair)</option>
            <option value="1">★☆☆☆☆ (Poor)</option>
        </select>
      </div>
      
      <div>
        <label class="block font-semibold mb-1">Your Comment</label>
        <textarea name="comment" placeholder="Share your experience..." class="w-full p-3 bg-gray-700 border border-gray-600 rounded-lg h-32 text-white" required></textarea>
      </div>
      
      <button type="submit" class="w-full bg-gold text-secondary font-bold rounded-full py-3 hover:bg-yellow-400 transition-transform duration-300 hover:scale-105">Submit Feedback</button>
    </form>
  </div>
</section>
            </div>
            <div class="lg:col-span-1">
                <div class="bg-gray-900 p-8 rounded-2xl sticky top-32">
                    <p class="text-2xl font-bold text-white">
                        VND <?php echo number_format($room['Price'], 0, ',', '.'); ?>
                        <span class="text-base font-normal text-gray-400">/ night</span>
                    </p>
                    <?php if ($booking_success): ?>
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow">
        <?= $booking_success ?>
    </div>
<?php elseif ($booking_error): ?>
    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow">
        <?= $booking_error ?>
    </div>
<?php endif; ?>

                    <div class="my-6 border-t border-gray-700"></div>
<form method="POST" action="book_room.php">
    <input type="hidden" name="room_id" value="<?= htmlspecialchars($room['RoomID']) ?>">
    <div>
        <label class="block text-sm font-semibold text-gray-400 mb-1">Check-in</label>
        <input type="date" name="checkin_date" required class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-400 mb-1">Check-out</label>
        <input type="date" name="checkout_date" required class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-400 mb-1">Guests</label>
        <select name="guests" class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white">
            <option value="1">1 guest</option>
            <option value="2" selected>2 guests</option>
            <option value="3">3 guests</option>
        </select>
    </div>
    <button type="submit" class="w-full bg-gold hover:bg-yellow-400 text-secondary font-bold py-3 rounded-lg transition-transform duration-300 hover:scale-105">
        Đặt phòng
    </button>
</form>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox^='gallery-']", {});
    </script>
</body>
</html>
