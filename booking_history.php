<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];
$username = $_SESSION['username'];

$stmt = $conn->prepare("
    SELECT b.*, r.HotelName, r.Location 
    FROM Booking b
    JOIN Room r ON b.RoomID = r.RoomID
    WHERE b.UserID = ?
    ORDER BY b.CreatedAt DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booking History - HotelLux</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
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
  <header class="w-full bg-secondary fixed top-0 left-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
      <a href="codeweb.php" class="text-2xl font-bold text-gold">HotelLux</a>
      <a href="codeweb.php" class="border border-gold text-gold px-4 py-2 rounded-full hover:bg-gold hover:text-secondary transition-transform duration-300 hover:scale-105">Back to Home</a>
    </div>
  </header>

  <div class="pt-28"></div>

  <main class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-white text-center mb-10">Your Booking History</h1>

    <?php if (count($bookings) > 0): ?>
      <div class="grid grid-cols-1 gap-6">
        <?php foreach ($bookings as $booking): ?>
          <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h2 class="text-xl font-bold text-gold"><?= htmlspecialchars($booking['HotelName']) ?></h2>
            <p class="text-sm text-gray-400 mb-2"><?= htmlspecialchars($booking['Location']) ?></p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
              <div><strong>Check-in:</strong> <?= htmlspecialchars($booking['CheckinDate']) ?></div>
              <div><strong>Check-out:</strong> <?= htmlspecialchars($booking['CheckOutDate']) ?></div>
              <div><strong>Status:</strong> <span class="text-green-400"><?= htmlspecialchars($booking['Status']) ?></span></div>
              <div><strong>Total:</strong> VND <?= number_format($booking['TotalAmount'], 0, ',', '.') ?></div>
            </div>
            <p class="mt-2 text-xs text-gray-500">Booked at: <?= htmlspecialchars($booking['CreatedAt']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-400">You haven’t booked any rooms yet.</p>
    <?php endif; ?>
  </main>
</body>
</html>
