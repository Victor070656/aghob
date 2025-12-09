-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 25, 2025 at 03:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aghob`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `action`, `table_name`, `record_id`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'update', 'pastors', 1, 'Updated pastor: Rev. John Smith', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 13:28:57'),
(2, 1, 'update', 'pastors', 1, 'Updated pastor: Rev. Emmanuel Ibe', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 14:04:12'),
(3, 1, 'update', 'pastors', 2, 'Updated pastor: Rev. Emmanuel Shadrach', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 14:04:46'),
(4, 1, 'update', 'pastors', 3, 'Updated pastor: Rev. Enoch Afanya', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 14:05:08'),
(5, 1, 'delete', 'ministries', 6, 'Deleted ministry: Outreach Ministry', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 14:57:54'),
(6, 1, 'delete', 'ministries', 1, 'Deleted ministry: Praise & Worship', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 14:58:05'),
(7, 1, 'create', 'ministries', 7, 'Added ministry: Amanda Stanton', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 14:58:52'),
(8, 1, 'update', 'ministries', 7, 'Updated ministry: Amanda Stanton', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 14:59:10'),
(9, 1, 'delete', 'ministries', 7, 'Deleted ministry: Amanda Stanton', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 15:00:11'),
(10, 1, 'create', 'sermons', 1, 'Added sermon: Est ab voluptates mo', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 15:04:14'),
(11, 1, 'create', 'events', 1, 'Added event: Voluptatibus a sunt', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 15:07:29'),
(12, 1, 'update', 'events', 1, 'Updated event: Voluptatibus a sunt', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 15:08:19'),
(13, 1, 'update', 'site_settings', 1, 'Updated site settings', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 15:18:08'),
(14, 1, 'login', 'admins', 1, 'Admin logged in', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-24 20:39:52'),
(15, 1, 'login', 'admins', 1, 'Admin logged in', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-25 11:39:30'),
(16, 1, 'update', 'site_settings', 1, 'Updated site settings', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-25 11:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'Administrator', 'admin@aghob.org', NULL, 'admin', 1, '2025-10-25 12:39:30', '2025-10-24 08:43:41', '2025-10-25 11:39:30');

-- --------------------------------------------------------

--
-- Table structure for table `contact_submissions`
--

CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','responded','archived') DEFAULT 'new',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_submissions`
--

INSERT INTO `contact_submissions` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'Keefe Lowery', 'hesovufih@example.com', '+1 (814) 666-9485', 'Maiores ea fuga Et', 'Amet labore at mole', 'new', '::1', '2025-10-25 12:55:01', '2025-10-25 12:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `registration_required` tinyint(1) DEFAULT 0,
  `registration_link` varchar(500) DEFAULT NULL,
  `max_attendees` int(11) DEFAULT NULL,
  `event_type` enum('conference','seminar','workshop','outreach','celebration','training','youth','worship','prayer','other') DEFAULT 'other',
  `cost` varchar(100) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `slug`, `description`, `event_date`, `event_time`, `end_date`, `end_time`, `location`, `address`, `featured_image`, `contact_person`, `contact_phone`, `contact_email`, `registration_required`, `registration_link`, `max_attendees`, `event_type`, `cost`, `is_featured`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Voluptatibus a sunt', 'Aliquid obcaecati ut', 'Deleniti magna elige', '2025-11-20', '10:52:00', NULL, NULL, 'Oakland', '363 Fabien Lane', NULL, 'Ex commodi aut obcae', '+1 (196) 976-5361', 'nisy@example.com', 0, 'https://www.gubasov.com.au', NULL, 'worship', NULL, 1, 1, 1, '2025-10-24 15:07:29', '2025-10-24 15:08:19');

-- --------------------------------------------------------

--
-- Table structure for table `ministries`
--

CREATE TABLE `ministries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `leader_name` varchar(255) DEFAULT NULL,
  `leader_phone` varchar(20) DEFAULT NULL,
  `leader_email` varchar(255) DEFAULT NULL,
  `meeting_day` varchar(50) DEFAULT NULL,
  `meeting_time` varchar(50) DEFAULT NULL,
  `meeting_location` varchar(255) DEFAULT NULL,
  `activities` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ministries`
--

INSERT INTO `ministries` (`id`, `name`, `slug`, `description`, `leader_name`, `leader_phone`, `leader_email`, `meeting_day`, `meeting_time`, `meeting_location`, `activities`, `image`, `is_active`, `display_order`, `created_at`, `updated_at`) VALUES
(2, 'Children Ministry', 'children-ministry', 'Nurturing the faith of our children through Bible-based teaching and fun activities.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, '2025-10-24 08:43:41', '2025-10-24 08:43:41'),
(3, 'Youth Ministry', 'youth-ministry', 'Empowering young people to live for Christ and make a difference in their world.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 3, '2025-10-24 08:43:41', '2025-10-24 08:43:41'),
(4, 'Women Ministry', 'women-ministry', 'Building community and faith among women of all ages through fellowship and service.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, '2025-10-24 08:43:41', '2025-10-24 08:43:41'),
(5, 'Men Ministry', 'men-ministry', 'Strengthening men in their faith and leadership through brotherhood and accountability.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, '2025-10-24 08:43:41', '2025-10-24 08:43:41');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','unsubscribed') DEFAULT 'active',
  `unsubscribe_token` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pastors`
--

CREATE TABLE `pastors` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `position` enum('senior_pastor','assistant_pastor','children_pastor') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `credentials` varchar(255) DEFAULT NULL,
  `ordination_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pastors`
--

INSERT INTO `pastors` (`id`, `full_name`, `position`, `email`, `phone`, `photo`, `bio`, `credentials`, `ordination_date`, `is_active`, `display_order`) VALUES
(1, 'Rev. Emmanuel Ibe', 'senior_pastor', 'senior.pastor@aghob.org', '', NULL, '', '', NULL, 1, 1),
(2, 'Rev. Emmanuel Shadrach', 'assistant_pastor', 'assistant.pastor@aghob.org', '', NULL, '', '', NULL, 1, 2),
(3, 'Rev. Enoch Afanya', 'children_pastor', 'children.pastor@aghob.org', '', NULL, '', '', NULL, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `prayer_requests`
--

CREATE TABLE `prayer_requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `prayer_request` text NOT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `status` enum('pending','praying','answered') DEFAULT 'pending',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prayer_requests`
--

INSERT INTO `prayer_requests` (`id`, `full_name`, `email`, `phone`, `prayer_request`, `is_anonymous`, `status`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'Karly Boyd', 'fadaja@example.com', '+1 (216) 529-3269', 'Veritatis cupidatat', 0, 'pending', '::1', '2025-10-25 12:55:16', '2025-10-25 12:55:16');

-- --------------------------------------------------------

--
-- Table structure for table `sermons`
--

CREATE TABLE `sermons` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `speaker` varchar(255) NOT NULL,
  `sermon_date` date NOT NULL,
  `scripture_reference` varchar(255) DEFAULT NULL,
  `series_name` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL COMMENT 'Duration in minutes',
  `audio_file` varchar(255) DEFAULT NULL,
  `audio_size` bigint(20) DEFAULT NULL COMMENT 'File size in bytes',
  `video_url` varchar(500) DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `pdf_size` bigint(20) DEFAULT NULL COMMENT 'File size in bytes',
  `thumbnail` varchar(255) DEFAULT NULL,
  `transcript` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `view_count` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sermons`
--

INSERT INTO `sermons` (`id`, `title`, `slug`, `description`, `speaker`, `sermon_date`, `scripture_reference`, `series_name`, `category`, `duration`, `audio_file`, `audio_size`, `video_url`, `pdf_file`, `pdf_size`, `thumbnail`, `transcript`, `notes`, `is_featured`, `is_active`, `view_count`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Est ab voluptates mo', 'Veritatis et soluta', 'Nobis iste tempora v', 'Ipsum at ut dolor q', '2010-12-27', 'Exercitation harum m', 'Madonna Ross', 'Est libero quisquam', 92, NULL, NULL, 'https://www.youtube.com/watch?v=TbB7hSBVKDM', NULL, NULL, NULL, NULL, NULL, 1, 1, 0, 1, '2025-10-24 15:04:14', '2025-10-24 15:04:14');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) DEFAULT 'Assemblies of God Church House of Bread',
  `site_tagline` varchar(255) DEFAULT 'Building Faith, Transforming Lives',
  `site_email` varchar(255) DEFAULT NULL,
  `site_phone` varchar(20) DEFAULT NULL,
  `site_address` text DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `whatsapp_url` varchar(255) DEFAULT NULL,
  `live_stream_url` varchar(500) DEFAULT NULL,
  `service_times` varchar(255) DEFAULT NULL,
  `service_location` varchar(255) DEFAULT NULL,
  `about_text` text DEFAULT NULL,
  `mission_statement` text DEFAULT NULL,
  `vision_statement` text DEFAULT NULL,
  `welcome_message` text DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_address` text DEFAULT NULL,
  `map_embed_code` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `hero_title` varchar(255) DEFAULT NULL,
  `hero_subtitle` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_tagline`, `site_email`, `site_phone`, `site_address`, `facebook_url`, `instagram_url`, `youtube_url`, `twitter_url`, `linkedin_url`, `whatsapp_url`, `live_stream_url`, `service_times`, `service_location`, `about_text`, `mission_statement`, `vision_statement`, `welcome_message`, `contact_email`, `contact_phone`, `contact_address`, `map_embed_code`, `meta_description`, `meta_keywords`, `logo`, `favicon`, `hero_image`, `hero_title`, `hero_subtitle`) VALUES
(1, 'Assemblies of God Church House of Bread', 'Building Faith, Transforming Lives', '', '', '', '', '', '', '', '', NULL, '', NULL, NULL, 'As part of the Assemblies of God fellowship, we are united in our mission to advance God\'s kingdom through biblical evangelism, Spirit-led discipleship, anointed worship, and compassionate service to our communities.', '', '', NULL, NULL, NULL, NULL, NULL, 'Welcome to Assemblies of God Church House of Bread - Building Faith, Transforming Lives in our community.', 'church,AGC,House of Bread,worship,community,faith', 'uploads/settings/1761319088_68fb98b0df8e2.png', 'uploads/settings/1761319088_68fb98b0df91b.png', NULL, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `action` (`action`),
  ADD KEY `table_name` (`table_name`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- Indexes for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `event_date` (`event_date`),
  ADD KEY `event_type` (`event_type`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `is_active` (`is_active`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `ministries`
--
ALTER TABLE `ministries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `is_active` (`is_active`),
  ADD KEY `display_order` (`display_order`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `pastors`
--
ALTER TABLE `pastors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prayer_requests`
--
ALTER TABLE `prayer_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `sermons`
--
ALTER TABLE `sermons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `sermon_date` (`sermon_date`),
  ADD KEY `speaker` (`speaker`),
  ADD KEY `category` (`category`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `is_active` (`is_active`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_submissions`
--
ALTER TABLE `contact_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ministries`
--
ALTER TABLE `ministries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pastors`
--
ALTER TABLE `pastors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prayer_requests`
--
ALTER TABLE `prayer_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sermons`
--
ALTER TABLE `sermons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sermons`
--
ALTER TABLE `sermons`
  ADD CONSTRAINT `sermons_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
