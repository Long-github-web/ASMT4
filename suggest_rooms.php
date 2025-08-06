<?php
include 'db.php';

$term = $_GET['term'] ?? '';

if (!empty($term)) {
    $stmt = $conn->prepare("SELECT RoomID, HotelName FROM Room WHERE HotelName LIKE ? LIMIT 5");
    $likeTerm = "%$term%";
    $stmt->bind_param("s", $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];

    while ($row = $result->fetch_assoc()) {
        $roomID = $row['RoomID'];

        // Lấy ảnh đại diện từ bảng RoomImage
        $imgStmt = $conn->prepare("SELECT ImageURL FROM RoomImage WHERE RoomID = ? LIMIT 1");
        $imgStmt->bind_param("i", $roomID);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();

        if ($imgResult->num_rows > 0) {
            $imageUrl = $imgResult->fetch_assoc()['ImageURL'];
        } else {
            $imageUrl = 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80'; // ảnh mặc định
        }
        $imgStmt->close();

        $suggestions[] = [
            'id' => $roomID,
            'name' => $row['HotelName'],
            'image' => $imageUrl
        ];
    }

    echo json_encode($suggestions);
}
?>
