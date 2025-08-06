-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 06, 2025 lúc 07:36 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hotel2`
--

--
-- Đang đổ dữ liệu cho bảng `booking`
--

INSERT INTO `booking` (`BookingID`, `UserID`, `RoomID`, `CheckinDate`, `CheckOutDate`, `TotalAmount`, `Status`, `CreatedAt`, `UpdatedAt`) VALUES
(5, 2, 2, '2025-08-08 00:00:00', '2025-08-09 00:00:00', 1500000.00, 'Confirmed', '2025-08-05 14:51:09', '2025-08-05 14:51:09'),
(6, 2, 3, '2025-08-15 00:00:00', '2025-08-17 00:00:00', 3500000.00, 'Confirmed', '2025-08-05 14:56:41', '2025-08-05 14:56:41'),
(7, 2, 4, '2025-08-14 00:00:00', '2025-08-23 00:00:00', 15750000.00, 'Confirmed', '2025-08-05 14:57:58', '2025-08-05 14:57:58'),
(8, 2, 9, '2025-08-21 00:00:00', '2025-08-23 00:00:00', 6000000.00, 'Confirmed', '2025-08-05 15:24:33', '2025-08-05 15:24:33');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
