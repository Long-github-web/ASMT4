-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 06, 2025 lúc 07:37 AM
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
-- Đang đổ dữ liệu cho bảng `room`
--

INSERT INTO `room` (`RoomID`, `RoomNumber`, `HotelName`, `RoomTypeID`, `Price`, `Status`, `Rating`, `ReviewCount`, `Location`, `OfferTag`, `CreatedAt`, `UpdatedAt`) VALUES
(1, '101', 'Serene Valley Hotel', 1, 1500000.00, 'Available', 8.5, 1102, 'City Center, District 1', 'Ưu đãi cuối tuần', NULL, NULL),
(2, '102', 'The Grand Mountain View', 1, 1500000.00, 'Available', 8.2, 985, 'Near Old Town', 'Ưu đãi trong thời gian có hạn', NULL, NULL),
(3, '103', 'Lakeside Peace Resort', 1, 1750000.00, 'Available', 8.8, 1520, 'City Center, District 1', NULL, NULL, NULL),
(4, '104', 'Oasis Urban Retreat', 1, 1750000.00, 'Available', 8.1, 854, 'Riverside View', 'Ưu đãi mùa du lịch', NULL, NULL),
(5, '105', 'The Royal Heritage Inn', 1, 2000000.00, 'Available', 8.9, 1893, 'City Center, District 1', NULL, NULL, NULL),
(6, '106', 'Azure Sky Apartments', 1, 2000000.00, 'Available', 8.4, 1050, 'Near Shopping Mall', NULL, NULL, NULL),
(7, '107', 'Golden Sands Motel', 1, 2250000.00, 'Available', 8.6, 1320, 'City Center, District 1', 'Ưu đãi trong thời gian có hạn', NULL, NULL),
(8, '108', 'The Crimson Lodge', 1, 2250000.00, 'Maintenance', 8.3, 991, 'Airport Proximity', NULL, NULL, NULL),
(9, '201', 'Celestial Waters Hotel', 2, 3000000.00, 'Available', 9.2, 2108, 'Beachfront, Da Nang', 'Ưu đãi cuối tuần', NULL, NULL),
(10, '202', 'The Emerald Boutique', 2, 3000000.00, 'Available', 9.0, 1950, 'Beachfront, Da Nang', NULL, NULL, NULL),
(11, '203', 'Sapphire Shores Villa', 2, 3500000.00, 'Available', 9.4, 2340, 'Beachfront, Da Nang', 'Ưu đãi trong thời gian có hạn', NULL, NULL),
(12, '204', 'The Platinum Panorama', 2, 3500000.00, 'Available', 9.1, 1880, 'Ocean View, Vung Tau', NULL, NULL, NULL),
(13, '205', 'Diamond Crest Residence', 2, 4000000.00, 'Available', 9.5, 2560, 'Ocean View, Vung Tau', 'Ưu đãi mùa du lịch', NULL, NULL),
(14, '206', 'The Opulent Oasis', 2, 4000000.00, 'Available', 9.3, 2210, 'Ocean View, Vung Tau', NULL, NULL, NULL),
(15, '207', 'The Majestic Pearl', 2, 4500000.00, 'Available', 9.6, 2800, 'Ocean View, Vung Tau', NULL, NULL, NULL),
(16, '208', 'Elysian Fields Resort', 2, 4500000.00, 'Booked', 9.2, 2055, 'Beachfront, Da Nang', NULL, NULL, NULL),
(17, '301', 'The Sovereign Suite', 3, 8000000.00, 'Available', 9.7, 890, 'Capital View, Hanoi', 'Ưu đãi trong thời gian có hạn', NULL, NULL),
(18, '302', 'Imperial Gardens Hotel', 3, 8000000.00, 'Available', 9.5, 750, 'Capital View, Hanoi', NULL, NULL, NULL),
(19, '303', 'The Zenith Tower', 3, 9000000.00, 'Available', 9.8, 1020, 'Capital View, Hanoi', 'Ưu đãi cuối tuần', NULL, NULL),
(20, '304', 'The Apex Presidential', 3, 9000000.00, 'Available', 9.6, 810, 'Capital View, Hanoi', NULL, NULL, NULL),
(21, '305', 'The Monarch Villa', 3, 10500000.00, 'Available', 9.9, 1150, 'Luxury District, Ho Chi Minh', NULL, NULL, NULL),
(22, '306', 'The Citadel Grand', 3, 10500000.00, 'Available', 9.7, 920, 'Luxury District, Ho Chi Minh', 'Ưu đãi trong thời gian có hạn', NULL, NULL),
(23, '307', 'The Paramount Estate', 3, 12000000.00, 'Available', 9.8, 1080, 'Luxury District, Ho Chi Minh', NULL, NULL, NULL),
(24, '308', 'The Pinnacle Palace', 3, 12000000.00, 'Available', 9.6, 850, 'Luxury District, Ho Chi Minh', NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
