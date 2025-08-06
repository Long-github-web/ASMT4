<?php
session_start();
include 'db.php';

$availableRooms = [];
$checkin_date = $_GET['checkin_date'] ?? '';
$checkout_date = $_GET['checkout_date'] ?? '';
$room_type = $_GET['room_type'] ?? '';
$search_query = $_GET['search'] ?? '';

// Lấy danh sách RoomType cho dropdown
$roomTypes = [];
$typeResult = $conn->query("SELECT UniqueID, TypeName FROM RoomType");
if ($typeResult && $typeResult->num_rows > 0) {
    while ($row = $typeResult->fetch_assoc()) {
        $roomTypes[] = $row;
    }
}

// Xây dựng query động
$query = "
    SELECT 
        r.*, rt.TypeName,
        IFNULL(f.avg_rating, 0) as avg_rating,
        IFNULL(f.review_count, 0) as review_count
    FROM Room r
    JOIN RoomType rt ON r.RoomTypeID = rt.UniqueID
    LEFT JOIN (
        SELECT 
            RoomID, 
            AVG(Rating) as avg_rating, 
            COUNT(FeedbackID) as review_count 
        FROM Feedback 
        GROUP BY RoomID
    ) AS f ON r.RoomID = f.RoomID
    WHERE 1=1
";

$params = [];
$types = '';

// Nếu có chọn ngày → lọc theo phòng còn trống
if (!empty($checkin_date) && !empty($checkout_date)) {
    $query .= " AND r.Status = 'Available'
                AND r.RoomID NOT IN (
                    SELECT RoomID FROM Booking
                    WHERE CheckinDate < ? AND CheckOutDate > ?
                )";
    $params[] = $checkout_date;
    $params[] = $checkin_date;
    $types .= 'ss';
}

// Lọc theo loại phòng nếu có chọn
if (!empty($room_type)) {
    $query .= " AND r.RoomTypeID = ?";
    $params[] = $room_type;
    $types .= 'i';
}

// Lọc theo tên khách sạn nếu có nhập
if (!empty($search_query)) {
    $query .= " AND r.HotelName LIKE ?";
    $params[] = "%$search_query%";
    $types .= 's';
}

$stmt = $conn->prepare($query);
if ($stmt) {
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $availableRooms = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Search Results - HotelLux</title>
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

<main class="max-w-7xl mx-auto px-4 py-12">
  <h1 class="text-4xl font-bold text-white text-center mb-4">Search Results</h1>

  <form method="GET" class="flex flex-col md:flex-row items-center justify-center gap-4 mb-8">
    <input type="date" name="checkin_date" value="<?= htmlspecialchars($checkin_date) ?>" class="px-4 py-2 rounded bg-gray-800 text-white border border-gray-700">
    <input type="date" name="checkout_date" value="<?= htmlspecialchars($checkout_date) ?>" class="px-4 py-2 rounded bg-gray-800 text-white border border-gray-700">

    <select name="room_type" class="px-4 py-2 rounded bg-gray-800 text-white border border-gray-700">
      <option value="">All Room Types</option>
      <?php foreach ($roomTypes as $type): ?>
        <option value="<?= $type['UniqueID'] ?>" <?= ($room_type == $type['UniqueID']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($type['TypeName']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <div class="relative w-full md:w-60">
  <input type="text" name="search" id="search-box"
         placeholder="Hotel name..." autocomplete="off"
         value="<?= htmlspecialchars($search_query ?? '') ?>"
         class="px-4 py-2 w-full rounded bg-gray-800 text-white border border-gray-700">

  <!-- Khung gợi ý -->
  <ul id="suggestions" class="absolute left-0 right-0 bg-white text-black rounded shadow-lg mt-1 z-50 hidden"></ul>
</div>




    <button type="submit" class="bg-gold text-secondary font-bold px-6 py-2 rounded hover:bg-yellow-400 transition duration-300">Search</button>
  </form>

  <?php if (!empty($checkin_date) && !empty($checkout_date)): ?>
    <p class="text-center text-lg text-gray-400 mb-12">
      Showing available rooms from <strong><?= htmlspecialchars($checkin_date) ?></strong> to <strong><?= htmlspecialchars($checkout_date) ?></strong>
    </p>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php if (!empty($availableRooms)): ?>
      <?php foreach ($availableRooms as $room): 
        $priceVND = $room['Price'];
        $imageUrl = 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80';
        
        $imgStmt = $conn->prepare("SELECT ImageURL FROM RoomImage WHERE RoomID = ? LIMIT 1");
        $imgStmt->bind_param("i", $room['RoomID']);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();
        if ($imgResult->num_rows > 0) {
            $imageUrl = $imgResult->fetch_assoc()['ImageURL'];
        }
        $imgStmt->close();
      ?>
        <a href="room_details.php?room_id=<?= $room['RoomID'] ?>" class="block bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden text-left transition-all duration-300 hover:border-gold hover:scale-105 h-full flex flex-col">
          <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($room['HotelName']) ?>" class="w-full h-48 object-cover">
          <div class="p-4 flex flex-col flex-grow">
            <h3 class="text-md font-bold text-white truncate"><?= htmlspecialchars($room['HotelName']) ?></h3>
            <p class="text-xs text-gray-400 mb-2"><?= htmlspecialchars($room['Location']) ?></p>
            <div class="flex items-center gap-2 mb-2">
              <div class="bg-secondary ..."><?= number_format($room['avg_rating'], 1) ?></div>
              <span class="text-gray-400 ..."><?= number_format($room['review_count']) ?> reviews</span>
            </div>
            <div class="mt-auto text-right">
              <p class="text-lg font-bold text-white">VND <?= number_format($priceVND, 0, ',', '.') ?></p>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-span-full text-center py-16">
        <i class="fa-solid fa-bed-pulse text-6xl text-gray-600 mb-4"></i>
        <h2 class="text-2xl font-bold text-white">No rooms found</h2>
        <p class="text-gray-400 mt-2">Try different filters or dates.</p>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php $conn->close(); ?>
<script>
document.getElementById('search-box').addEventListener('input', function () {
    const query = this.value;
    const suggestions = document.getElementById('suggestions');
    
    if (query.length < 1) {
        suggestions.innerHTML = '';
        suggestions.classList.add('hidden');
        return;
    }

    fetch(`suggest_rooms.php?term=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                suggestions.classList.add('hidden');
                return;
            }

            suggestions.innerHTML = data.map(name => `<li class="px-4 py-2 hover:bg-gray-200 cursor-pointer">${name}</li>`).join('');
            suggestions.classList.remove('hidden');

            // Gắn sự kiện khi click gợi ý
            Array.from(suggestions.children).forEach(li => {
                li.addEventListener('click', () => {
                    document.getElementById('search-box').value = li.textContent;
                    suggestions.classList.add('hidden');
                });
            });
        });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const searchBox = document.getElementById("search-box");
  const suggestionsBox = document.getElementById("suggestions");

  searchBox.addEventListener("input", function () {
    const query = this.value.trim();

    if (query.length === 0) {
      suggestionsBox.classList.add("hidden");
      suggestionsBox.innerHTML = "";
      return;
    }

    fetch(`suggest_rooms.php?term=${encodeURIComponent(query)}`)
      .then(response => response.json())
      .then(data => {
        if (data.length === 0) {
          suggestionsBox.classList.add("hidden");
          suggestionsBox.innerHTML = "";
          return;
        }

        suggestionsBox.innerHTML = "";
        data.forEach(item => {
          const li = document.createElement("li");
          li.className = "flex items-center gap-3 px-4 py-2 hover:bg-gray-200 cursor-pointer";

          const img = document.createElement("img");
          img.src = item.image;
          img.alt = item.name;
          img.className = "w-12 h-12 object-cover rounded";

          const span = document.createElement("span");
          span.textContent = item.name;
          span.className = "text-sm";

          li.appendChild(img);
          li.appendChild(span);

          li.addEventListener("click", () => {
            window.location.href = `room_details.php?room_id=${item.id}`;
          });

          suggestionsBox.appendChild(li);
        });

        suggestionsBox.classList.remove("hidden");
      });
  });

  document.addEventListener("click", function (e) {
    if (!searchBox.contains(e.target) && !suggestionsBox.contains(e.target)) {
      suggestionsBox.classList.add("hidden");
    }
  });
});
</script>

</body>
</html>
