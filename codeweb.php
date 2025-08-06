<?php session_start();
include 'check_remember_me.php';
$heroImages = [
    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c', // Ảnh gốc
    'https://images.unsplash.com/photo-1566073771259-6a8506099945', // Ảnh mới 1
    'https://images.unsplash.com/photo-1582719508461-905c673771fd', // Ảnh mới 2
    'https://images.unsplash.com/photo-1618773928121-c32242e63f39'  // Ảnh mới 3
];
 ?>
<!DOCTYPE html> 
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Booking</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <!-- Thêm CSS của SwiperJS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
          borderRadius: {
            xl: '1.5rem',
          },
          boxShadow: {
            card: '0 4px 12px rgba(0, 0, 0, 0.08)',
          },
        },
      },
    };
  </script>
  <style>
    .swiper-button-disabled {
        opacity: 0 !important;
        cursor: auto !important;
        pointer-events: none !important;
    }
    html {
        scroll-behavior: smooth;
    }
    body.is-loading {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
  </style>
</head>

<body class="bg-secondary text-gray-300 font-[Inter] is-loading">
  <!-- Navigation Bar -->
  <header class="w-full bg-secondary fixed top-0 left-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
      <!-- === KHỐI MÃ MỚI CHO LOGO === -->
      <a href="codeweb.php" class="flex items-center gap-3">
        <!-- Thay thế URL trong src bằng URL logo của bạn -->
        <img src="https://png.pngtree.com/png-clipart/20200720/original/pngtree-luxury-golden-logo-and-icon-design-png-image_4358527.jpg" alt="HotelLux Logo" class="h-10 w-10">
        <span class="text-2xl font-bold text-gold">HotelLux</span>
      </a>
      <div class="space-x-4">
          <?php if (isset($_SESSION['userid'])): ?>
              <!-- Menu Người Dùng (Khi đã đăng nhập) -->
              <div class="relative">
                  <button id="user-menu-button" class="bg-gold text-secondary w-10 h-10 rounded-full flex items-center justify-center transition-transform duration-300 hover:scale-105">
                      <i class="fa-solid fa-user"></i>
                  </button>
                  <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl z-50 text-gray-300">
                      <div class="p-4 border-b border-gray-700">
                          <p class="font-semibold text-white"><?= htmlspecialchars($_SESSION['username']); ?></p>
                          <p class="text-sm text-gray-400">Customer</p>
                      </div>
                      <a href="profile.php" class="block px-4 py-2 text-sm hover:bg-gray-700">My Profile</a>
                      <a href="booking_history.php" class="block px-4 py-2 text-sm hover:bg-gray-700">Booking History</a>
                      <a href="logout.php" class="block px-4 py-2 text-sm text-red-400 hover:bg-gray-700 border-t border-gray-700">Logout</a>
                  </div>
              </div>
          <?php else: ?>
              <!-- Nút Login & Register (Khi chưa đăng nhập) -->
              <a href="login.php" class="border border-gold text-gold font-semibold px-4 py-2 rounded-full hover:bg-gold hover:text-secondary transition-transform duration-300 hover:scale-105">Login</a>
              <a href="register.php" class="border border-gold text-gold font-semibold px-4 py-2 rounded-full hover:bg-gold hover:text-secondary transition-transform duration-300 hover:scale-105">Register</a>
          <?php endif; ?>
        </div>
    </div>
</header>
  <div class="pt-28"></div>
<!-- Hero Section -->
<section class="relative h-[500px] flex items-center justify-center">
    <!-- Swiper Container cho các ảnh nền -->
    <div class="swiper hero-swiper absolute inset-0 w-full h-full">
        <div class="swiper-wrapper">
            <?php foreach ($heroImages as $image): ?>
                <div class="swiper-slide bg-center bg-cover" style="background-image: url('<?= htmlspecialchars($image) ?>');"></div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Lớp phủ màu đen và nội dung chữ (giữ nguyên, nằm bên trên slider) -->
    <div class="absolute inset-0 bg-gradient-to-tr from-black/60 to-black/30 z-10"></div>
    <div class="relative z-20 text-center text-white px-6 max-w-2xl">
      <h1 class="text-5xl font-bold leading-tight mb-4 drop-shadow-lg">Discover Your Next Getaway</h1>
      <p class="text-lg mb-6 opacity-90">Easy hotel bookings with a touch of luxury</p>
      <a href="#rooms" class="mt-8 inline-block bg-gold hover:bg-yellow-400 text-secondary font-bold px-8 py-3 rounded-full transition-all duration-300 shadow-lg hover:scale-105">
          Book Now
      </a>
    </div>
</section>
<!-- Search Form Section -->
<section id="search-form" class="py-16 bg-gray-900">
  <div class="max-w-4xl mx-auto px-4">
    <h2 class="text-3xl font-bold text-center text-white mb-8">Find Your Perfect Stay</h2>
   <form action="searchroom.php" method="GET" class="flex flex-col md:flex-row items-center justify-center gap-4 mt-6">
  <input type="date" name="checkin_date" class="px-4 py-2 rounded bg-gray-800 text-white border border-gray-700">
  <input type="date" name="checkout_date" class="px-4 py-2 rounded bg-gray-800 text-white border border-gray-700">

  <select name="room_type" class="px-4 py-2 rounded bg-gray-800 text-white border border-gray-700">
    <option value="">All Room Types</option>
    <?php
      // PHP để lấy danh sách loại phòng
      include 'db.php';
      $typeResult = $conn->query("SELECT UniqueID, TypeName FROM RoomType");
      while ($row = $typeResult->fetch_assoc()) {
          echo "<option value='{$row['UniqueID']}'>" . htmlspecialchars($row['TypeName']) . "</option>";
      }
      $conn->close();
    ?>
  </select>

 <div class="relative w-full md:w-60">
  <input type="text" name="search" id="search-box"
         placeholder="Hotel name..." autocomplete="off"
         value="<?= htmlspecialchars($search_query ?? '') ?>"
         class="px-4 py-2 w-full rounded bg-gray-800 text-white border border-gray-700">

  <!-- Khung gợi ý -->
  <ul id="suggestions" class="absolute left-0 right-0 bg-white text-black rounded shadow-lg mt-1 z-50 hidden"></ul>
</div>

  <button type="submit" class="bg-gold text-secondary font-bold px-6 py-2 rounded hover:bg-yellow-400 transition duration-300">
    Search
  </button>
</form>

  </div>
</section>
  
  <!-- Room Listing -->
  <section id="rooms" class="py-20 bg-secondary">
    <div class="max-w-7xl mx-auto px-4 space-y-16">
        <?php
            function create_room_slider($conn, $roomTypeId, $roomTypeName, $imageUrls) {
        ?>
            <div id="slider-<?php echo $roomTypeId; ?>" class="group">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-white"><?php echo htmlspecialchars($roomTypeName); ?>s</h2>
                </div>
                <div class="relative">
                    <div class="swiper room-swiper-<?php echo $roomTypeId; ?>">
                        <div class="swiper-wrapper py-4">
                            <?php
                                $roomsQuery = $conn->prepare("
    SELECT 
        r.*,
        IFNULL(f.avg_rating, 0) as avg_rating,
        IFNULL(f.review_count, 0) as review_count
    FROM Room r
    LEFT JOIN (
        SELECT 
            RoomID, 
            AVG(Rating) as avg_rating, 
            COUNT(FeedbackID) as review_count 
        FROM Feedback 
        GROUP BY RoomID
    ) AS f ON r.RoomID = f.RoomID
    WHERE r.RoomTypeID = ? 
    LIMIT 8
");
                                $roomsQuery->bind_param("i", $roomTypeId);
                                $roomsQuery->execute();
                                $roomsResult = $roomsQuery->get_result();
                                $roomIndex = 0;
                                if ($roomsResult && $roomsResult->num_rows > 0):
                                    while ($room = $roomsResult->fetch_assoc()):
                                        $imageUrl = $imageUrls[$roomIndex % count($imageUrls)];
                                        $hasOffer = !empty($room['OfferTag']);
                                        $priceInVND = $room['Price'];
                                        if ($hasOffer) { $originalPriceInVND = $priceInVND * 1.5; } else { $originalPriceInVND = $priceInVND; }
                            ?>
                                        <div class="swiper-slide h-auto" data-room-id="<?php echo $room['RoomID']; ?>">
                                            <a href="room_details.php?room_id=<?php echo $room['RoomID']; ?>" class="block bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden text-left transition-all duration-300 hover:border-gold hover:scale-105 h-full flex flex-col">
                                              <div class="relative">
                                                <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($room['HotelName']); ?>" class="w-full h-48 object-cover">
                                                <button class="absolute top-4 right-4 w-10 h-10 bg-black/50 rounded-full flex items-center justify-center text-white hover:bg-black/75 transition">
                                                  <i class="fa-regular fa-heart"></i>
                                                </button>
                                              </div>
                                              <div class="p-4 flex flex-col flex-grow">
                                                <span class="inline-block bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full mb-2 self-start">Genius</span>
                                                <h3 class="text-md font-bold text-white truncate"><?php echo htmlspecialchars($room['HotelName']); ?></h3>
                                                <p class="text-xs text-gray-400 mb-2"><?php echo htmlspecialchars($room['Location']); ?></p>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <div class="bg-secondary text-white text-sm font-bold px-2 py-1 rounded"><?= number_format($room['avg_rating'], 1) ?></div>
                                                    <span class="text-gray-400 text-xs"><?= number_format($room['review_count']) ?> đánh giá</span>
                                                </div>
                                                <?php if ($hasOffer): ?>
                                                    <span class="inline-block bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-full mb-4 self-start"><?php echo htmlspecialchars($room['OfferTag']); ?></span>
                                                <?php else: ?>
                                                    <div class="mb-4" style="height: 22px;"></div>
                                                <?php endif; ?>
                                                <div class="mt-auto text-right">
                                                   <p class="text-xs text-gray-500">1 đêm</p>
                                                   <?php if ($hasOffer): ?>
                                                       <s class="text-sm text-red-400">VND <?php echo number_format($originalPriceInVND, 0, ',', '.'); ?></s>
                                                   <?php endif; ?>
                                                   <p class="text-lg font-bold text-white">VND <?php echo number_format($priceInVND, 0, ',', '.'); ?></p>
                                                </div>
                                              </div>
                                            </a>
                                        </div>
                            <?php
                                        $roomIndex++;
                                    endwhile;
                                endif;
                                $roomsQuery->close();
                            ?>
                        </div>
                    </div>
                    <button class="swiper-button-prev-<?php echo $roomTypeId; ?> absolute top-1/2 -translate-y-1/2 left-0 -translate-x-1/2 w-12 h-12 rounded-full bg-white/80 text-secondary shadow-lg flex items-center justify-center z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="swiper-button-next-<?php echo $roomTypeId; ?> absolute top-1/2 -translate-y-1/2 right-0 translate-x-1/2 w-12 h-12 rounded-full bg-white/80 text-secondary shadow-lg flex items-center justify-center z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        <?php
            }
            include 'db.php';
            $standardImages = [ 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1598605272254-16f0c0ecdfa5?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=800&q=80', 'https://media.danitel.vn/uploads/2023/07/23c629b5-6f59-40b5-8654-adf775c5b67d/standard-02.jpg?width=1900&height=1267', 'https://media.danitel.vn/uploads/2023/07/23c629b5-6f59-40b5-8654-adf775c5b67d/standard-02.jpg?width=1900&height=1267', 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80', 'https://theranaldhotel.com/wp-content/uploads/2024/05/standard-room24.jpg' ];
            $deluxeImages = [ 'https://images.unsplash.com/photo-1568495248636-6432b97bd949?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?auto=format&fit=crop&w=800&q=80', 'https://media.licdn.com/dms/image/v2/D4D12AQFZV-WCSGcQ8Q/article-cover_image-shrink_600_2000/article-cover_image-shrink_600_2000/0/1679880757083?e=2147483647&v=beta&t=wNed3iPWP-H9ny4OyvBlR_hvDo2Xlgn5Xfkuxm7KkKc', 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=800&q=80', 'https://mondrianhotels.com/wp-content/uploads/sites/34/2024/04/hotel-cannes-rooms-suites-hero.jpeg', 'https://www.theexcelsiorhotel.com.ph/wp-content/uploads/2023/11/Deluxe-Room-Twin_11zon-scaled.webp' ];
            $suiteImages = [ 'https://images.unsplash.com/photo-1594563703937-fdc640497dcd?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=80', 'https://images.rosewoodhotels.com/is/image/rwhg/rwgzu-executive-suite-living-room-dusk', 'https://pistachiohotel.com/UploadFile/Gallery/Rooms/Executive-Suite/Executive-Suite-1.jpg', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1562438668-bcf0ca6578f0?auto=format&fit=crop&w=800&q=80', 'https://acaciahotelsmanila-com.b-cdn.net/wp-content/uploads/2022/04/Snapseed-3-scaled.jpg', 'https://diamondhotel.com/wp-content/uploads/2024/02/premier-executive3.jpg' ];

            create_room_slider($conn, 1, 'Standard Room', $standardImages);
            create_room_slider($conn, 2, 'Deluxe Room', $deluxeImages);
            create_room_slider($conn, 3, 'Executive Suite', $suiteImages);
            
            $conn->close();
        ?>
    </div>
  </section>
<!-- Thêm JS của SwiperJS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    // JavaScript cho menu người dùng
    const userMenuButton = document.getElementById('user-menu-button');
    if (userMenuButton) {
        const userMenu = document.getElementById('user-menu');
        userMenuButton.addEventListener('click', (e) => {
            e.preventDefault();
            userMenu.classList.toggle('hidden');
        });
        window.addEventListener('click', function(e) {
            if (userMenuButton && !userMenuButton.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }

    // KHỞI TẠO TẤT CẢ CÁC SLIDERS
    const swipers = {};
    swipers[1] = new Swiper('.room-swiper-1', { loop: false, slidesPerView: 1.2, spaceBetween: 16, navigation: { nextEl: '.swiper-button-next-1', prevEl: '.swiper-button-prev-1' }, breakpoints: { 640: { slidesPerView: 2 }, 768: { slidesPerView: 3 }, 1024: { slidesPerView: 4 } } });
    swipers[2] = new Swiper('.room-swiper-2', { loop: false, slidesPerView: 1.2, spaceBetween: 16, navigation: { nextEl: '.swiper-button-next-2', prevEl: '.swiper-button-prev-2' }, breakpoints: { 640: { slidesPerView: 2 }, 768: { slidesPerView: 3 }, 1024: { slidesPerView: 4 } } });
    swipers[3] = new Swiper('.room-swiper-3', { loop: false, slidesPerView: 1.2, spaceBetween: 16, navigation: { nextEl: '.swiper-button-next-3', prevEl: '.swiper-button-prev-3' }, breakpoints: { 640: { slidesPerView: 2 }, 768: { slidesPerView: 3 }, 1024: { slidesPerView: 4 } } });
    // Khởi tạo Swiper cho Hero Section
    const heroSwiper = new Swiper('.hero-swiper', {
        loop: true,               // Lặp lại vô tận
        effect: 'fade',           // Hiệu ứng "mờ dần" khi chuyển ảnh
        autoplay: {
            delay: 2500,              // Tự động chuyển sau mỗi 2.5 giây (2500ms)
            disableOnInteraction: false, // Vẫn chạy dù người dùng tương tác
        },
        speed: 1500,              // Tốc độ chuyển ảnh là 1.5 giây
        allowTouchMove: false,    // Không cho phép người dùng vuốt trên nền
    });
    // SCRIPT TỰ ĐỘNG JUMP/SCROLL VÀ HIỂN THỊ TRANG
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const returnToId = urlParams.get('return_to');
        const returnRoomId = urlParams.get('return_room_id');

        if (returnToId) {
            const targetElement = document.getElementById('slider-' + returnToId);
            if (targetElement) {
                // TẠM THỜI TẮT CUỘN MƯỢT ĐỂ JUMP NGAY LẬP TỨC
                document.documentElement.style.scrollBehavior = 'auto';
                targetElement.scrollIntoView({ block: 'center' });
                // BẬT LẠI CUỘN MƯỢT CHO CÁC HÀNH ĐỘNG SAU
                setTimeout(() => {
                    document.documentElement.style.scrollBehavior = 'smooth';
                }, 100);

                if (returnRoomId && swipers[returnToId]) {
                    const targetSlide = targetElement.querySelector(`[data-room-id="${returnRoomId}"]`);
                    if (targetSlide) {
                       const allSlides = Array.from(targetSlide.parentElement.children);
                       const slideIndex = allSlides.indexOf(targetSlide);
                       if (slideIndex > -1) {
                           // Logic mới: tính toán vị trí để slide nằm giữa
                           const slidesPerView = swipers[returnToId].params.breakpoints[1024].slidesPerView; // Lấy số slide trên màn hình lớn
                           let centeredIndex = slideIndex - Math.floor(slidesPerView / 2);
                           if (centeredIndex < 0) centeredIndex = 0;
                           swipers[returnToId].slideTo(centeredIndex, 0);
                       }
                    }
                }
            }
        }

        document.body.classList.remove('is-loading');

        // GÁN SỰ KIỆN SCROLL MƯỢT CHO CÁC LINK NEO
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
</script>
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
<!-- Footer Section -->
<footer class="bg-gray-900 text-gray-300 py-10 mt-10 border-t border-gray-800">
  <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center md:items-start justify-between gap-8">
    <!-- Logo & Project Name -->
    <div class="flex items-center gap-4 mb-6 md:mb-0">
      <img src="https://png.pngtree.com/png-clipart/20200720/original/pngtree-luxury-golden-logo-and-icon-design-png-image_4358527.jpg" alt="HotelLux Logo" class="w-14 h-14 rounded-xl shadow-lg bg-white p-2">
      <div>
        <h2 class="text-2xl font-bold text-gold">HotelLux</h2>
        <p class="text-sm text-gray-400">Your Luxury Stay Partner</p>
      </div>
    </div>
    <!-- Contact Info -->
    <div>
      <h3 class="font-semibold text-white mb-2">Contact Us</h3>
      <ul class="text-sm space-y-1">
        <li><i class="fa-solid fa-location-dot text-gold mr-2"></i>123 Luxury Street, District 1, Ho Chi Minh City</li>
        <li><i class="fa-solid fa-phone text-gold mr-2"></i>+84 28 1234 5678</li>
        <li><i class="fa-solid fa-envelope text-gold mr-2"></i>contact@hotellux.vn</li>
      </ul>
    </div>
    <!-- Social Links -->
    <div>
      <h3 class="font-semibold text-white mb-2">Follow Us</h3>
      <div class="flex gap-4">
        <a href="#" class="text-gold hover:text-yellow-400 text-xl"><i class="fab fa-facebook"></i></a>
        <a href="#" class="text-gold hover:text-yellow-400 text-xl"><i class="fab fa-instagram"></i></a>
        <a href="#" class="text-gold hover:text-yellow-400 text-xl"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
  </div>
  <div class="text-center text-xs text-gray-500 mt-8">
    &copy; 2025 HotelLux. All rights reserved.
  </div>
</footer>
<!-- END Footer Section -->
</body>

</html>
