-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 04, 2025 at 02:44 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vrdemo`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` json NOT NULL,
  `background_music` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort` int DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `background_music`, `sort`, `status`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, '{\"en\": \"Overview\", \"vi\": \"Toàn Cảnh\"}', NULL, 1, 1, 8, '2025-03-21 02:41:53', '2025-03-26 20:42:55'),
(2, '{\"en\": \"Trung Do Temple\", \"vi\": \"Đền Trung Đô\"}', 'background_music/iUBA1KP0NP3O9uyKWRdkj5NHaCV7ur06kanUJgB5.mp3', 2, 1, 8, '2025-03-21 02:48:43', '2025-03-27 00:16:03'),
(8, '{\"en\": \"Scenic Spots\", \"vi\": \"Danh Lam Thắng Cảnh\"}', NULL, 1, 1, NULL, '2025-03-26 20:42:32', '2025-03-26 20:42:32');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotlinks`
--

CREATE TABLE `hotlinks` (
  `id` bigint UNSIGNED NOT NULL,
  `type` int NOT NULL,
  `location_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_to_location_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `yaw` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pitch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotlinks`
--

INSERT INTO `hotlinks` (`id`, `type`, `location_id`, `link_to_location_id`, `yaw`, `pitch`, `created_at`, `updated_at`) VALUES
(50, 1, '20', '21', '1.61', '-84.66', '2025-03-19 01:34:38', '2025-03-19 01:34:38'),
(51, 1, '20', '22', '358.79', '-63.92', '2025-03-19 02:58:08', '2025-03-19 02:58:08'),
(53, 2, '22', '21', '182.71', '-12.13', '2025-03-24 18:56:28', '2025-03-24 18:56:28'),
(54, 2, '22', '23', '359.11', '-14.52', '2025-03-24 18:56:41', '2025-03-24 18:56:41'),
(55, 2, '21', '22', '357.08', '-11.50', '2025-03-26 20:34:46', '2025-03-26 20:34:46'),
(58, 5, '20', '23', '359.71', '-36.64', '2025-03-27 03:10:16', '2025-03-27 03:10:16'),
(59, 2, '23', '22', '102.72', '-28.25', '2025-03-27 03:16:19', '2025-03-27 03:16:19'),
(60, 3, '21', '20', '357.19', '57.34', '2025-03-27 03:16:45', '2025-03-27 03:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `hotlinks_special`
--

CREATE TABLE `hotlinks_special` (
  `id` bigint UNSIGNED NOT NULL,
  `type` int NOT NULL,
  `location_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `yaw` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pitch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info_content` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotlinks_special`
--

INSERT INTO `hotlinks_special` (`id`, `type`, `location_id`, `yaw`, `pitch`, `video_link`, `info_content`, `created_at`, `updated_at`) VALUES
(4, 7, '20', '359.67', '-49.83', NULL, '{\"en\": \"<p>Bro Im too lazy to translate these word :v</p>\", \"vi\": \"<p>Đ&acirc;y l&agrave; info về Đền Trung Đ&ocirc;</p>\"}', '2025-03-28 01:00:00', '2025-03-28 01:00:00'),
(5, 6, '20', '350.36', '-42.98', '3rSITeylBIA?si=DPRfsGG5FJ9_LWvi', NULL, '2025-03-28 01:02:12', '2025-03-28 01:02:12'),
(6, 6, '20', '10.06', '-43.67', 'pFRmdb3qc7M?si=74riHcAJgnnsRZEY', NULL, '2025-03-28 01:27:35', '2025-03-28 01:27:35'),
(7, 6, '22', '359.55', '3.20', 'Xh3JN3SaWqQ?si=-a4dBOdL1WGQqQBW', NULL, '2025-03-28 01:29:48', '2025-03-28 01:29:48');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` json NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paronama_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `next_location_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `sun` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort` int DEFAULT NULL,
  `eyes` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` json DEFAULT NULL,
  `voice_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `yaw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pitch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `slug`, `paronama_id`, `next_location_id`, `status`, `sun`, `voice`, `sort`, `eyes`, `created_at`, `updated_at`, `description`, `voice_en`, `category_id`, `yaw`, `pitch`) VALUES
(20, '{\"en\": \"Overview\", \"vi\": \"Toàn cảnh\"}', 'toan-canh', '8', '22', 1, '15', 'voices/BXMfiYsAghhsDySWMhIK7UXWL4gF4dm0cE43IvZK.mp3', 0, '{\"yaw\": 6.283010774254387, \"pitch\": -1.1430161271310864}', '2025-03-19 01:08:59', '2025-04-03 19:02:19', '{\"en\": \"<p>This is a Overview description</p>\", \"vi\": \"<p>Đ&acirc;y l&agrave; Description to&agrave;n cảnh a</p>\"}', 'voices/BlOyyJ8GCpP3txN6yyxJT2Txyk2w1eqNJEBi6C0J.mp3', 1, '21.57918654290975', '105.82580714584665'),
(21, '{\"en\": \"Temple Gate\", \"vi\": \"Cổng đền\"}', 'cong-den', '10', '22', 1, '14', NULL, 1, '{\"yaw\": 6.258437781182984, \"pitch\": 0.08902621034418479}', '2025-03-19 01:15:14', '2025-04-03 19:03:48', '{\"en\": \"<p>This is the description of the Temple Gate</p>\", \"vi\": \"<p>Đ&acirc;y l&agrave; m&ocirc; tả Cổng đền</p>\"}', NULL, 2, '21.54859174022242', '105.77886625636175'),
(22, '{\"en\": \"Outside the Temple\", \"vi\": \"Ngoài đền\"}', 'ngoai-den', '9', '23', 1, '16', NULL, 2, '{\"yaw\": 0.01422570050121472, \"pitch\": 0.012922603608477525}', '2025-03-19 02:05:00', '2025-04-03 19:03:58', '{\"en\": \"<p>This is a description of Outside the Temple</p>\", \"vi\": \"<p>Đ&acirc;y l&agrave; m&ocirc; tả Ngo&agrave;i đền</p>\"}', NULL, 2, NULL, NULL),
(23, '{\"en\": \"Inside temple\", \"vi\": \"Trong đền\"}', 'trong-den', '7', '25', 1, NULL, 'voices/GHr6YnuKSchNaWsjkcZobqYmT0qKEpR6n7BxzyE2.mp3', 3, '{\"yaw\": 6.2631875680840405, \"pitch\": -0.03315750090826608}', '2025-03-20 23:54:22', '2025-04-03 19:04:12', '{\"en\": \"<p>Inside the Trung Do Temple</p>\", \"vi\": \"<p>Trong đền Trung Đ&ocirc;</p>\"}', 'voices/ezBWmevVTfvLjUaWjojMVmYwbRAjE2VNGE7ILGaw.mp3', 2, NULL, NULL),
(25, '{\"en\": \"Lao Cai\", \"vi\": \"Lào cai\"}', 'lao-cai', '12', '20', 1, NULL, 'voices/Bg7cbP7ixVF25HinC64GJ6oXVt7MqXWjYBvZnWGe.mp3', 25, NULL, '2025-04-02 01:08:37', '2025-04-03 19:11:32', '{\"en\": null, \"vi\": null}', 'voices/7CsQGkDmnZs2tca3BzMFg6WY8GebBvRGaRlvgzDy.mp3', 2, '21.582759166748176', '105.82042126978475');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2024_12_28_024519_create_paronamas_table', 2),
(18, '2025_01_02_004826_create_locations_table', 3),
(19, '2025_01_02_010201_create_suns_table', 3),
(21, '2025_01_02_023709_create_hotlinks_table', 4),
(23, '2025_03_12_081356_add_data_field_to_locations_table', 5),
(25, '2025_03_19_065336_update_locations_add_description_voice_en_change_name', 6),
(27, '2025_03_21_065214_create_categories_table', 7),
(28, '2025_03_22_010326_add_category_id_to_locations_table', 8),
(30, '2025_03_24_030416_create_settings_table', 9),
(31, '2025_03_24_035819_remove_entry_from_locations_table', 10),
(33, '2025_03_24_064011_add_column_to_settings_table', 11),
(34, '2025_03_26_090244_add_voice_reader_avater_to_settings_table', 12),
(35, '2025_03_27_065829_add_background_music_to_categories_table', 13),
(36, '2025_03_28_012127_create_hotlinks_special_table', 14),
(37, '2025_04_02_031426_add_is_yaw_pitch_to_table_settings', 15),
(38, '2025_04_02_034203_add_yaw_and_pitch_to_locations_table', 16),
(39, '2025_04_04_015704_remove_previous_location_id_from_locations_table', 17);

-- --------------------------------------------------------

--
-- Table structure for table `paronamas`
--

CREATE TABLE `paronamas` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paronamas`
--

INSERT INTO `paronamas` (`id`, `name`, `slug`, `image`, `created_at`, `updated_at`) VALUES
(7, 'Trong đền', 'trong-den', '360/trong-den/trong-den.jpg', '2024-12-30 02:32:50', '2024-12-30 02:32:50'),
(8, 'Toàn cảnh', 'toan-canh', '360/toan-canh/toan-canh.jpg', '2024-12-30 02:46:46', '2024-12-30 02:46:46'),
(9, 'Ngoài đền', 'ngoai-den', '360/ngoai-den/ngoai-den.jpg', '2024-12-30 02:48:17', '2024-12-30 02:48:17'),
(10, 'Cổng đền', 'cong-den', '360/cong-den/cong-den.jpg', '2024-12-30 02:48:50', '2024-12-30 02:48:50'),
(12, 'Lào cai', 'lao-cai', '360/lao-cai/lao-cai.JPG', '2025-04-02 01:06:17', '2025-04-02 01:06:17'),
(13, 'Lào cai 2', 'lao-cai-2', '360/lao-cai-2/lao-cai-2.JPG', '2025-04-02 01:06:58', '2025-04-02 01:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `background_music` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_site_verification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bg_starter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logoMain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voice_reader_avater` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `yaw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pitch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `logo`, `background_music`, `created_at`, `updated_at`, `description`, `keywords`, `author`, `google_site_verification`, `bg_starter`, `logoMain`, `voice_reader_avater`, `yaw`, `pitch`) VALUES
(1, 'VR Thái Nguyên', 'logos/q8oURpz2wgF7gexUGD2Gn3eWqp3PM8pRA4aCz3RN.ico', 'music/QNE9PeBa0VuhSDEaYLQyCkfOhhF2FwImOIglfQHk.mp3', '2025-03-23 20:26:20', '2025-04-01 20:31:11', 'VR Thái Nguyên mang đến trải nghiệm du lịch ảo 360° chân thực, giúp bạn khám phá các danh lam thắng cảnh, di tích lịch sử và văn hóa đặc sắc của Thái Nguyên ngay trên thiết bị của mình.', 'VR Thái Nguyên, du lịch ảo Thái Nguyên, 360 VR Thái Nguyên, khám phá Thái Nguyên, danh lam thắng cảnh Thái Nguyên, di tích lịch sử Thái Nguyên, văn hóa Thái Nguyên, du lịch thực tế ảo', 'Kennatech', NULL, 'bg_starters/AYuACTkYlhohDK8thyNDEgQqvTZPjNuiq26Puc8H.jpg', 'logos/6McgUG9BeIOhh0EQxkADHH5ocVf0hXBJbJJrgVJb.webp', 'voice_reader_avater/zpuK2Gv7TKqxDSDhzRQ10O5nmQuuJq64MAgdHr3F.png', '21.58698408578731', '105.81643247148486');

-- --------------------------------------------------------

--
-- Table structure for table `suns`
--

CREATE TABLE `suns` (
  `id` bigint UNSIGNED NOT NULL,
  `yaw` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pitch` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suns`
--

INSERT INTO `suns` (`id`, `yaw`, `pitch`, `created_at`, `updated_at`) VALUES
(14, '285.76', '32.77', '2025-03-19 01:15:38', '2025-03-19 01:15:38'),
(15, '273.82', '32.20', '2025-03-19 01:15:51', '2025-03-19 01:15:51'),
(16, '257.57', '46.56', '2025-03-19 03:13:06', '2025-03-19 03:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Hoang Nhat Minh', 'admin@admin.com', NULL, '$2y$10$KIckZ.J1PayRc23aqNv2oumn85qzmHT7nRMH4u23wR4pfrYWyJsgO', NULL, '2024-12-27 19:00:25', '2024-12-27 19:00:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hotlinks`
--
ALTER TABLE `hotlinks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotlinks_special`
--
ALTER TABLE `hotlinks_special`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paronamas`
--
ALTER TABLE `paronamas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suns`
--
ALTER TABLE `suns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotlinks`
--
ALTER TABLE `hotlinks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `hotlinks_special`
--
ALTER TABLE `hotlinks_special`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `paronamas`
--
ALTER TABLE `paronamas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suns`
--
ALTER TABLE `suns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
