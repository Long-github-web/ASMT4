-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 06, 2025 lúc 08:12 AM
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
-- Đang đổ dữ liệu cho bảng `roomimage`
--

INSERT INTO `roomimage` (`RoomImageID`, `RoomID`, `ImageURL`, `Caption`, `CreatedAt`) VALUES
(1, 1, 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(2, 1, 'https://images.unsplash.com/photo-1598605272254-16f0c0ecdfa5?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(3, 1, 'https://images.unsplash.com/photo-1540518614846-7eded433c457?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(4, 1, 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(5, 1, 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(6, 2, 'https://lh6.googleusercontent.com/proxy/uUS8tI0JebslCPfrWMnCGOXPbwnDxrwhrQ3oaTF9FQHfaX5kPPNFmxoj09geZVoULq0vCuzyrypAjoZoQHuKlW3g8lGyMbLDFI6MdsBTLIci', NULL, NULL),
(7, 2, 'https://ik.imagekit.io/tvlk/apr-asset/Ixf4aptF5N2Qdfmh4fGGYhTN274kJXuNMkUAzpL5HuD9jzSxIGG5kZNhhHY-p7nw/hotel/asset/20072937-9b61d3ed5a284bd2ed88c9a857a5f1e3.jpeg?_src=imagekit&tr=c-at_max,f-jpg,fo-auto,h-203,pr-true,q-80,w-300', NULL, NULL),
(8, 2, 'https://amorgoshotel.com/wp-content/uploads/2014/12/Amorgos-Standard-Room1-e1464286427430.jpg', NULL, NULL),
(9, 2, 'https://images.unsplash.com/photo-1562438668-bcf0ca6578f0?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(10, 2, 'https://res.cloudinary.com/maistra/image/upload/w_1920,c_lfill,g_auto,q_auto,dpr_auto/f_auto/v1700658053/Proprietes/Select/Zagreb/Hotel%20International/22.11.23/23074-09-18%20Hotel%20International%20Rooms/23074-09-18%20Hotel%20International%20Rooms%20Standard%20Single%20Use/Webres%202000px/23074-09-18_Hotel_International_Rooms_Classic_Queen_1_2000px_sivgq2.jpg', NULL, NULL),
(11, 3, 'https://www.hotelmalaysia.com.my/images/Standard%20Room/IMGL6303xxx.jpg', NULL, NULL),
(12, 3, 'https://www.residenthotels.com/wp-content/uploads/2022/06/The-Resident-Victoria-Double.jpg', NULL, NULL),
(13, 3, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(14, 3, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(15, 3, 'https://ezcloud.vn/wp-content/uploads/2023/03/khong-gian-sach-se-phong-standard.webp', NULL, NULL),
(16, 4, 'https://d2e5ushqwiltxm.cloudfront.net/wp-content/uploads/sites/38/2024/10/30043743/1.Standard-Room-scaled.jpg', NULL, NULL),
(17, 4, 'https://www.crownmelbourne.com.au/getmedia/bf3641e5-1dc0-4015-8c0d-17954f5a4325/120422-Melbourne-Hotels-Promenade-Rooms-Standard-King-1-1800x1200.jpg', NULL, NULL),
(18, 4, 'https://www.kabayanhotel.com.ph/wp-content/uploads/2016/02/kabayan-standardroom-01.jpg', NULL, NULL),
(19, 4, 'https://www.themidlandhotel.co.uk/image/fit/593x400/cms/midland/images/new/themidland_bedroom_2023v2.jpg', NULL, NULL),
(20, 4, 'https://thumbs.dreamstime.com/b/hotel-rooms-8146308.jpg', NULL, NULL),
(21, 5, 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(22, 5, 'https://images.mirai.com/INFOROOMS/15708139/a4p6Q32ap2ILETsYJ8FR/a4p6Q32ap2ILETsYJ8FR_large.jpg', NULL, NULL),
(23, 5, 'https://www.alexandra.no/media/198169/_bd_0671-1.jpg?w=800&h=560&mode=crop&anchor=middlecenter', NULL, NULL),
(24, 5, 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(25, 5, 'https://www.apexhotels.co.uk/media/krllyiws/acg-rm-standard-double-desk-tv-dsc08313.jpg?rmode=crop&width=1000&height=666', NULL, NULL),
(26, 6, 'https://www.nordichotels.eu/wp-content/uploads/2024/03/Standard-Double_street-view_V_Tuul-min.jpg', NULL, NULL),
(27, 6, 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(28, 6, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/232558092.jpg?k=350926adf13ef12549ccaaa930512b4607f9ea9dc4a9e4d5fa2ac52d1362b0f6&o=&hp=1', NULL, NULL),
(29, 6, 'https://images.unsplash.com/photo-1590073242678-70ee3fc28e8e?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(30, 6, 'https://images.mirai.com/INFOROOMS/15708139/yPG2XfNFgFwOJDc6FK8G/yPG2XfNFgFwOJDc6FK8G_large.jpg', NULL, NULL),
(31, 7, 'https://image-tc.galaxy.tf/wijpeg-4lj3sj0zemqdwwp9ah2e1rwzt/standard-plus-room-at-the-magnolia-hotel_wide.jpg?rotate=0&crop=0%2C1%2C1800%2C1012', NULL, NULL),
(32, 7, 'https://media.istockphoto.com/id/1366246794/photo/standard-twin-room-in-hotel.jpg?s=612x612&w=0&k=20&c=FWGs33viYJKetViJFnd2wzkWNMBpB-6l88Tds7-HVnE=', NULL, NULL),
(33, 7, 'https://www.hotelactual.com/wp2017/wp-content/uploads/Habitacion-low-cost-hotel-barcelona-01.jpg', NULL, NULL),
(34, 7, 'https://res.cloudinary.com/maistra/image/upload/w_1920,c_lfill,g_auto,q_auto,dpr_auto/f_auto/v1700658049/Proprietes/Select/Zagreb/Hotel%20International/22.11.23/23074-09-18%20Hotel%20International%20Rooms/23074-09-18%20Hotel%20International%20Rooms%20Standard%20Single%20Use/Webres%202000px/23074-09-18_Hotel_International_Rooms_Classic_Queen_2_2000px_wuepx7.jpg', NULL, NULL),
(35, 7, 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(36, 8, 'https://www.dobedanhotels.com/media/mmydofg2/superior-standard-room-1-gallery.jpg', NULL, NULL),
(37, 8, 'https://www.dobedanhotels.com/media/00hmajaa/superior-duplex-family-room-3-gallery.jpg', NULL, NULL),
(38, 8, 'https://lh6.googleusercontent.com/proxy/uUS8tI0JebslCPfrWMnCGOXPbwnDxrwhrQ3oaTF9FQHfaX5kPPNFmxoj09geZVoULq0vCuzyrypAjoZoQHuKlW3g8lGyMbLDFI6MdsBTLIci', NULL, NULL),
(39, 8, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(40, 8, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(41, 9, 'https://images.unsplash.com/photo-1568495248636-6432b97bd949?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(42, 9, 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(43, 9, 'https://images.unsplash.com/photo-1582719508461-905c673771fd?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(44, 9, 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(45, 9, 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(46, 10, 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(47, 10, 'https://vinapad.com/wp-content/uploads/2019/03/phong-deluxe-1.jpg', NULL, NULL),
(48, 10, 'https://ezcloud.vn/wp-content/uploads/2023/03/phong-deluxe-la-gi.webp', NULL, NULL),
(49, 10, 'https://d2e5ushqwiltxm.cloudfront.net/wp-content/uploads/sites/92/2024/02/23093302/SBV_1482-1-1500x1000.jpg', NULL, NULL),
(50, 10, 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(51, 11, 'https://media.licdn.com/dms/image/v2/C4D12AQGOVrqEZEz4bQ/article-cover_image-shrink_600_2000/article-cover_image-shrink_600_2000/0/1520176983516?e=2147483647&v=beta&t=WPWiygU0dRpnTkRWJSkLkDwF8uyca-8rePA-F70qy0E', NULL, NULL),
(52, 11, 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(53, 11, 'https://assets.simplotel.com/simplotel/image/upload/x_0,y_66,w_2256,h_1268,r_0,c_crop,q_80,fl_progressive/w_900,f_auto,c_fit/the-residency-towers-coimbatore/Executive_Deluxe_Room_-_King_Bedroom_(in-out)?1754092800253', NULL, NULL),
(54, 11, 'https://images.unsplash.com/photo-1590073242678-70ee3fc28e8e?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(55, 11, 'https://www.subicbaytravelershotel.com/wp-content/uploads/2020/06/20190603_170950-scaled.jpg', NULL, NULL),
(56, 12, 'https://peridotgrandhotel.com/wp-content/uploads/2022/07/Premier-Deluxe-Twin-2000.jpg', NULL, NULL),
(57, 12, 'https://www.solanohotels.com/wp-content/uploads/2021/06/solano-deluxe-gallery-2.jpg', NULL, NULL),
(58, 12, 'https://www.thereveriesaigon.com/wp-content/uploads/2021/11/The-Grand-Deluxe-360-1920x1080-1.jpg', NULL, NULL),
(59, 12, 'https://reynahotelhanoi.com/files/images/Room/deluxe/dld3.jpg', NULL, NULL),
(60, 12, 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(61, 13, 'https://image-tc.galaxy.tf/wijpeg-5g06rln76xdqyv8hiy50wuzcw/sdq-7288cropped_standard.jpg?crop=0%2C10%2C1800%2C1350', NULL, NULL),
(62, 13, 'https://image-tc.galaxy.tf/wijpeg-kmklc3s8mky9qa3103a0y789/deluxe.jpg', NULL, NULL),
(63, 13, 'https://image-tc.galaxy.tf/wijpeg-6255xo0kyr8ijg62a8ybxyjqt/deluxe-king-suite5_standard.jpg?crop=107%2C0%2C1707%2C1280', NULL, NULL),
(64, 13, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(65, 13, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(66, 14, 'https://lamejorhotel.com/wp-content/uploads/2023/04/5O4A8418.jpg', NULL, NULL),
(67, 14, 'https://homesweb.staah.net/imagelibrary/1639532645_3502_Deluxe_Room_(19).jpg', NULL, NULL),
(68, 14, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(69, 14, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(70, 14, 'https://homesweb.staah.net/imagelibrary/1639532645_3502_Deluxe_Room_(19).jpg', NULL, NULL),
(71, 15, 'https://www.dorsetthotels.com/images/dorsett-melbourne/stay/deluxe-room/deluxe-king-banner/discoveryroombedroomday05-S360-mobile.webp', NULL, NULL),
(72, 15, 'https://www.tiranthotel.com/UploadFile/-MLL7331-HDR-resize-11122024105240.jpg', NULL, NULL),
(73, 15, 'https://d2img7eurva700.cloudfront.net/cache/img/hotel-le-soleia-chambre-deluxe-247010-1920-1080-landscape.jpg?q=1742826658', NULL, NULL),
(74, 15, 'https://thejohnstownestate.com/wp-content/uploads/2020/10/Deluxe-King-bedroom-1_1200X800.jpg', NULL, NULL),
(75, 15, 'https://d2img7eurva700.cloudfront.net/cache/img/hotel-le-soleia-chambre-deluxe-247010-1920-1080-landscape.jpg?q=1742826658', NULL, NULL),
(76, 16, 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(77, 16, 'https://vinapad.com/wp-content/uploads/2019/03/phong-deluxe-2.jpg', NULL, NULL),
(78, 16, 'https://assets.simplotel.com/simplotel/image/upload/w_5000,h_3332/x_0,y_405,w_4999,h_2816,r_0,c_crop,q_80,fl_progressive/w_900,f_auto,c_fit/kenilworth-resort-spa-goa/Superior_Deluxe_Room_Twin_-_Luxury_Rooms_in_Salcete_at_Kenilworth_Resort_and_Spa?1754265600192', NULL, NULL),
(79, 16, 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(80, 16, 'https://lasiestahotels.com/saigoncentral/wp-content/uploads/2024/04/907-7456_Deluxe-Room_0511-4-200095.jpg', NULL, NULL),
(81, 17, 'https://images.unsplash.com/photo-1594563703937-fdc640497dcd?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(82, 17, 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(83, 17, 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(84, 17, 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(85, 17, 'https://www.grandvistahanoi.com/wp-content/uploads/2019/08/Phong-ngu-1.9-1100x733.jpg', NULL, NULL),
(86, 18, 'https://images.unsplash.com/photo-1562438668-bcf0ca6578f0?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(87, 18, 'https://images.unsplash.com/photo-1631049552240-59c37f38802b?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(88, 18, 'https://hotelmiramar.in/images/product/deluxe/d1.jpg', NULL, NULL),
(89, 18, 'https://www.millenniumhotels.com/mhb-media/regions/asia/siingapore/mhotelsingaporecitycentre/rooms/images/m-hotel-singapore-deluxe-room_w1000.jpg?w=1000&rev=3dd499d5b53a4cdcad5dda5596d0c78e&hash=9558DC0D6509AC5AA82CF381D8C43239', NULL, NULL),
(90, 18, 'https://www.royalgardenhotel.co.uk/_novaimg/4907700-1578207_256_0_4338_3199_800_590.jpg', NULL, NULL),
(91, 19, 'https://ezcloud.vn/wp-content/uploads/2023/03/can-phong-deluxe.webp', NULL, NULL),
(92, 19, 'https://image-tc.galaxy.tf/wijpeg-z0ekwkcl4ukx08e326fdbn17/deluxe-premium-i.jpg', NULL, NULL),
(93, 19, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(94, 19, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(95, 19, 'https://cdn.prod.website-files.com/6448000098cde53609c9851c/688cc16a10daf5db15f939b6_boutique-hotel-paris-deluxe-room.jpg', NULL, NULL),
(96, 20, 'https://djmzubtjl6upi.cloudfront.net/wp-content/uploads/sites/3/2017/12/Deluxe-Double-Guestroom2.jpg', NULL, NULL),
(97, 20, 'https://victoriahotel.co.uk/sites/default/files/styles/940x690/public/2022-09/bc-victoria-accom_victoria4.jpg?h=b2d9f031&itok=8AON9o-Z', NULL, NULL),
(98, 20, 'https://www.landmarklondon.co.uk/wp-content/uploads/2019/05/Deluxe-Room-1800x1200-1800x1200.jpg', NULL, NULL),
(99, 20, 'https://img.lottehotel.com/cms/asset/2025/01/31/2303/180921-2-2000-roo-LTHA.webp', NULL, NULL),
(100, 20, 'https://image-tc.galaxy.tf/wijpeg-5w28iee9g813o0w56j74reemf/luxury-hanoi-accommodation-with-exceptional-services.jpg?width=1920', NULL, NULL),
(101, 21, 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(102, 21, 'https://en-m.prestigedeluxeaquapark.com/images/hotel_images/c-fakepath-3.double-deluxe-room-prestige-deluxe-hotel-aquapark-club-.jpg', NULL, NULL),
(103, 21, 'https://res.cloudinary.com/maistra/image/upload/h_574,w_1044,c_fill,g_auto,q_auto,dpr_auto/f_auto/v1700658044/Proprietes/Select/Zagreb/Hotel%20International/22.11.23/23074-09-18%20Hotel%20International%20Rooms/23074-09-18%20Hotel%20International%20Rooms%20Deluxe%20Room/Webres%202000px/23074-09-18_Hotel_International_Rooms_Deluxe_Room_1_2000px_twaxeh.jpg', NULL, NULL),
(104, 21, 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(105, 21, 'https://mikazuki.com.vn/vnt_upload/product/10_2023/DSC07587.jpg', NULL, NULL),
(106, 22, 'https://image-tc.galaxy.tf/wijpeg-a8w5371789v9v2c3yhrk5u4oh/artboard-22-100.jpg', NULL, NULL),
(107, 22, 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(108, 22, 'https://d2mo2a5fvrklap.cloudfront.net/app/uploads/sites/9/2022/08/19122251/executivesuite-hero-desktop.jpg', NULL, NULL),
(109, 22, 'https://images.unsplash.com/photo-1590073242678-70ee3fc28e8e?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(110, 22, 'https://pistachiohotel.com/UploadFile/Gallery/Rooms/Executive-Suite/Executive-Suite-1.jpg', NULL, NULL),
(111, 23, 'https://img.lottehotel.com/cms/asset/2025/01/31/2302/180921-1-2000-roo-LTHA.webp', NULL, NULL),
(112, 23, 'https://www.hilton.com/im/en/MNLMBCI/9317784/deluxe-pool-view.jpg?impolicy=crop&cw=5000&ch=2992&gravity=NorthWest&xposition=0&yposition=172&rw=528&rh=316', NULL, NULL),
(113, 23, 'https://cache.marriott.com/content/dam/marriott-renditions/DXBJW/dxbjw-room-0026-hor-wide.jpg?output-quality=70&interpolation=progressive-bilinear&downsize=540px:*', NULL, NULL),
(114, 23, 'https://www.tiranthotel.com/UploadFile/DSC06520-resize-11122024105658.jpg', NULL, NULL),
(115, 23, 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(116, 24, 'https://images.rosewoodhotels.com/is/image/rwhg/rwgzu-executive-suite-living-room-dusk', NULL, NULL),
(117, 24, 'https://thehotelandrea.com/wp-content/uploads/2022/06/Executive-Suite-Room-min-1.jpg', NULL, NULL),
(118, 24, 'https://www.peninsula.com/-/media/images/manila/new/rooms/new-executive-suite/executive-suite-king-bedroom-1074-2_c.jpg', NULL, NULL),
(119, 24, 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1920&q=80', NULL, NULL),
(120, 24, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1920&q=80', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
