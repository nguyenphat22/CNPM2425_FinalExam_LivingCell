-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 11, 2025 lúc 04:02 AM
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
-- Cơ sở dữ liệu: `qlrlktdh`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_admin`
--

CREATE TABLE `bang_admin` (
  `MaAdmin` varchar(20) NOT NULL,
  `MaTK` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_ctcthssv`
--

CREATE TABLE `bang_ctcthssv` (
  `MaCTCT` varchar(20) NOT NULL,
  `TenPhong` varchar(50) NOT NULL,
  `NguoiQL` varchar(50) NOT NULL,
  `MaTK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_danhhieu`
--

CREATE TABLE `bang_danhhieu` (
  `MaDH` int(11) NOT NULL,
  `TenDH` varchar(100) NOT NULL,
  `DieuKienGPA` decimal(3,2) NOT NULL,
  `DieuKienDRL` smallint(6) NOT NULL,
  `DieuKienNTN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_diemhoctap`
--

CREATE TABLE `bang_diemhoctap` (
  `MaSV` varchar(20) NOT NULL,
  `HocKy` tinyint(4) NOT NULL,
  `NamHoc` varchar(9) NOT NULL,
  `DiemHe4` decimal(3,2) NOT NULL,
  `XepLoai` varchar(50) DEFAULT NULL,
  `MaPKT` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_diemrenluyen`
--

CREATE TABLE `bang_diemrenluyen` (
  `MaSV` varchar(20) NOT NULL,
  `HocKy` tinyint(4) NOT NULL,
  `NamHoc` varchar(9) NOT NULL,
  `DiemRL` smallint(6) NOT NULL,
  `XepLoai` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_doantruong`
--

CREATE TABLE `bang_doantruong` (
  `MaDT` varchar(20) NOT NULL,
  `TenDT` varchar(50) NOT NULL,
  `NguoiQL` varchar(50) NOT NULL,
  `MaTK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_khaothi`
--

CREATE TABLE `bang_khaothi` (
  `MaPKT` varchar(20) NOT NULL,
  `TenPhong` varchar(50) NOT NULL,
  `NguoiQL` varchar(50) NOT NULL,
  `MaTK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_ngaytinhnguyen`
--

CREATE TABLE `bang_ngaytinhnguyen` (
  `MaNTN` int(11) NOT NULL,
  `MaSV` varchar(20) NOT NULL,
  `NgayThamGia` date NOT NULL,
  `TenHoatDong` varchar(200) NOT NULL,
  `SoNgayTN` int(11) NOT NULL,
  `TrangThaiDuyet` enum('ChuaDuyet','DaDuyet','TuChoi') DEFAULT 'ChuaDuyet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_sinhvien`
--

CREATE TABLE `bang_sinhvien` (
  `MaSV` varchar(20) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `NgaySinh` date NOT NULL,
  `Khoa` varchar(50) DEFAULT NULL,
  `Lop` varchar(50) DEFAULT NULL,
  `MaTK` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_sinhvien_danhhieu`
--

CREATE TABLE `bang_sinhvien_danhhieu` (
  `MaSV` varchar(20) NOT NULL,
  `MaDH` int(11) NOT NULL,
  `HocKy` tinyint(4) NOT NULL,
  `NamHoc` varchar(9) NOT NULL,
  `SoQuyetDinh` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bang_taikhoan`
--

CREATE TABLE `bang_taikhoan` (
  `MaTK` int(11) NOT NULL,
  `TenDangNhap` varchar(50) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `VaiTro` enum('Admin','SinhVien','KhaoThi','CTCTHSSV','DoanTruong') NOT NULL,
  `TrangThai` enum('Active','Inactive','Locked') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('G593ugT3lHnAbKpq19RAsJ85VzOvuxAeLrM6RQVu', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia3JUR3BCUFR2Y0dBeHFoWUdMVDBmTlBWWWp1b1Zibk91eFdsVEVMaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760147858);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bang_admin`
--
ALTER TABLE `bang_admin`
  ADD PRIMARY KEY (`MaAdmin`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_ctcthssv`
--
ALTER TABLE `bang_ctcthssv`
  ADD PRIMARY KEY (`MaCTCT`),
  ADD UNIQUE KEY `TenPhong` (`TenPhong`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_danhhieu`
--
ALTER TABLE `bang_danhhieu`
  ADD PRIMARY KEY (`MaDH`),
  ADD UNIQUE KEY `TenDH` (`TenDH`);

--
-- Chỉ mục cho bảng `bang_diemhoctap`
--
ALTER TABLE `bang_diemhoctap`
  ADD PRIMARY KEY (`MaSV`,`NamHoc`,`HocKy`),
  ADD KEY `idx_DHT_MaSV` (`MaSV`),
  ADD KEY `idx_DHT_MaPKT` (`MaPKT`);

--
-- Chỉ mục cho bảng `bang_diemrenluyen`
--
ALTER TABLE `bang_diemrenluyen`
  ADD PRIMARY KEY (`MaSV`,`NamHoc`,`HocKy`),
  ADD KEY `idx_DRL_MaSV` (`MaSV`);

--
-- Chỉ mục cho bảng `bang_doantruong`
--
ALTER TABLE `bang_doantruong`
  ADD PRIMARY KEY (`MaDT`),
  ADD UNIQUE KEY `TenDT` (`TenDT`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_khaothi`
--
ALTER TABLE `bang_khaothi`
  ADD PRIMARY KEY (`MaPKT`),
  ADD UNIQUE KEY `TenPhong` (`TenPhong`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_ngaytinhnguyen`
--
ALTER TABLE `bang_ngaytinhnguyen`
  ADD PRIMARY KEY (`MaNTN`),
  ADD KEY `idx_NTN_MaSV` (`MaSV`);

--
-- Chỉ mục cho bảng `bang_sinhvien`
--
ALTER TABLE `bang_sinhvien`
  ADD PRIMARY KEY (`MaSV`),
  ADD UNIQUE KEY `MaTK` (`MaTK`);

--
-- Chỉ mục cho bảng `bang_sinhvien_danhhieu`
--
ALTER TABLE `bang_sinhvien_danhhieu`
  ADD PRIMARY KEY (`MaSV`,`MaDH`,`NamHoc`,`HocKy`),
  ADD KEY `MaDH` (`MaDH`);

--
-- Chỉ mục cho bảng `bang_taikhoan`
--
ALTER TABLE `bang_taikhoan`
  ADD PRIMARY KEY (`MaTK`),
  ADD UNIQUE KEY `TenDangNhap` (`TenDangNhap`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bang_danhhieu`
--
ALTER TABLE `bang_danhhieu`
  MODIFY `MaDH` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bang_ngaytinhnguyen`
--
ALTER TABLE `bang_ngaytinhnguyen`
  MODIFY `MaNTN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bang_taikhoan`
--
ALTER TABLE `bang_taikhoan`
  MODIFY `MaTK` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bang_admin`
--
ALTER TABLE `bang_admin`
  ADD CONSTRAINT `bang_admin_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_ctcthssv`
--
ALTER TABLE `bang_ctcthssv`
  ADD CONSTRAINT `bang_ctcthssv_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_diemhoctap`
--
ALTER TABLE `bang_diemhoctap`
  ADD CONSTRAINT `bang_diemhoctap_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `bang_sinhvien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bang_diemhoctap_ibfk_2` FOREIGN KEY (`MaPKT`) REFERENCES `bang_khaothi` (`MaPKT`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_diemrenluyen`
--
ALTER TABLE `bang_diemrenluyen`
  ADD CONSTRAINT `bang_diemrenluyen_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `bang_sinhvien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_doantruong`
--
ALTER TABLE `bang_doantruong`
  ADD CONSTRAINT `bang_doantruong_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_khaothi`
--
ALTER TABLE `bang_khaothi`
  ADD CONSTRAINT `bang_khaothi_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_ngaytinhnguyen`
--
ALTER TABLE `bang_ngaytinhnguyen`
  ADD CONSTRAINT `bang_ngaytinhnguyen_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `bang_sinhvien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_sinhvien`
--
ALTER TABLE `bang_sinhvien`
  ADD CONSTRAINT `bang_sinhvien_ibfk_1` FOREIGN KEY (`MaTK`) REFERENCES `bang_taikhoan` (`MaTK`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `bang_sinhvien_danhhieu`
--
ALTER TABLE `bang_sinhvien_danhhieu`
  ADD CONSTRAINT `bang_sinhvien_danhhieu_ibfk_1` FOREIGN KEY (`MaSV`) REFERENCES `bang_sinhvien` (`MaSV`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bang_sinhvien_danhhieu_ibfk_2` FOREIGN KEY (`MaDH`) REFERENCES `bang_danhhieu` (`MaDH`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
