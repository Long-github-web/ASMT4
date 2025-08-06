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
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Email`, `Fullname`, `Role`, `Phonenumber`, `CreatedAt`, `UpdatedAt`, `Feedback`, `remember_token_selector`, `remember_token_validator_hash`) VALUES
(2, 'customer1', '$2y$10$aj9DIsd8jv/cN1pv.QMjmuswCI1dPKBjksRQeoCfdhpxa4YsIEAhi', 'customer@gmail.com', NULL, 1, NULL, '2025-08-05 14:16:12', '2025-08-05 14:16:12', NULL, 'ff55518cb823bdf3f1c0227a77c4f90b', '$2y$10$xPRMM.YdXEoFzX4yMDnU8uN5wj1cQEarLXIOPUW76tBWtHNBcnDvC'),
(4, 'admin1', '$2y$10$54YHCMVCFp4JYM5rPex/jen/W7egOYS.EpPpOTE7NdWZ3CePwAlva', 'admin@gmail.com', NULL, 2, NULL, '2025-08-05 14:41:42', '2025-08-05 14:41:42', NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
