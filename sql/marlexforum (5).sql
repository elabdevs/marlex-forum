-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 03 Eyl 2025, 17:34:46
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `marlexforum`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `action_time`) VALUES
(1, 10, 'reality1111 Adlı kullanıcı giriş yaptı.', '2025-08-29 18:08:15'),
(2, 10, 'reality1111 Adlı kullanıcı giriş yaptı.', '2025-08-29 18:08:19'),
(3, 10, 'reality1111 Adlı kullanıcı giriş yaptı.', '2025-08-30 08:48:46'),
(4, 10, 'reality1111 Adlı kullanıcı giriş yaptı.', '2025-09-02 12:35:48'),
(5, 10, 'reality1111 Adlı kullanıcı giriş yaptı.', '2025-09-03 09:35:31');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `created_at`, `expires_at`) VALUES
(1, 'Test Duyurusu', 'Test İçeriği', '2025-08-29 18:20:04', '2025-09-05 18:19:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `banned_words`
--

CREATE TABLE `banned_words` (
  `id` int(11) NOT NULL,
  `pattern` varchar(255) NOT NULL,
  `is_regex` tinyint(1) DEFAULT 0,
  `replacement` varchar(255) DEFAULT NULL,
  `severity` enum('low','medium','high') DEFAULT 'medium',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bans`
--

CREATE TABLE `bans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `banned_by` int(11) NOT NULL,
  `banned_at` datetime DEFAULT current_timestamp(),
  `ban_expiration` timestamp NULL DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`) VALUES
(7, 'Genel Konular', 'genel-konular', 'Bu kategoride genel konular bulunmaktadır.');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `direct_messages`
--

CREATE TABLE `direct_messages` (
  `id` int(11) NOT NULL,
  `msg_from` int(11) NOT NULL,
  `msg_to` int(11) NOT NULL,
  `content` text NOT NULL,
  `sended_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `topic_id`, `is_active`, `added_at`) VALUES
(25, 10, 75, 0, '2025-09-03 11:51:30');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ip_whitelist`
--

CREATE TABLE `ip_whitelist` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `added_by_user_id` int(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `reply_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `liked_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `reply_id`, `is_active`, `liked_at`) VALUES
(68, 10, 75, NULL, 1, '2025-09-03 13:50:08'),
(69, 10, 77, NULL, 1, '2025-09-03 11:51:15'),
(70, 10, NULL, 31, 1, '2025-09-03 13:49:58'),
(71, 10, NULL, 32, 1, '2025-09-03 14:01:43');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `live_presence`
--

CREATE TABLE `live_presence` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(64) NOT NULL,
  `tab_id` char(36) NOT NULL,
  `current_url` varchar(1024) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip` varbinary(16) DEFAULT NULL,
  `last_active` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `live_presence`
--

INSERT INTO `live_presence` (`id`, `user_id`, `session_id`, `tab_id`, `current_url`, `topic_id`, `user_agent`, `ip`, `last_active`) VALUES
(63, 10, '6c94385a86t57f4uiad41c1ju4', '/topics/test-konusu-896239', '/topics/test-konusu-896239', 75, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 0x3a3a31, '2025-09-03 13:41:14');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `message`, `is_read`, `created_at`) VALUES
(172, 10, 'like', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Beğendi.', 0, '2025-09-03 11:33:52'),
(173, 10, 'like', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Beğendi.', 0, '2025-09-03 11:34:00'),
(174, 10, 'favorite', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Favorilerine Ekledi.', 0, '2025-09-03 11:38:08'),
(175, 10, 'like', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Favorilerine Ekledi.', 0, '2025-09-03 11:38:13'),
(176, 10, 'like', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Favorilerine Ekledi.', 0, '2025-09-03 11:43:12'),
(177, 10, 'favorite', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Favorilerine Ekledi.', 0, '2025-09-03 11:51:30'),
(178, 10, 'like', 'reality1111 &#039;Test Konusu&#039; konunuzu favorilerine ekledi.', 0, '2025-09-03 11:57:24'),
(179, 10, 'like', 'reality1111 &#039;Test Konusu&#039; konunuzu favorilerine ekledi.', 0, '2025-09-03 11:57:27'),
(180, 10, 'like', 'reality1111 &#039;Test Konusu&#039; konunuzu favorilerine ekledi.', 0, '2025-09-03 11:58:37'),
(181, 10, 'like', 'reality1111 &#039;Test Konusu&#039; konunuzu favorilerine ekledi.', 0, '2025-09-03 12:19:01'),
(182, 10, 'like', 'reality1111 &#039;Test Konusu&#039; konunuzu favorilerine ekledi.', 0, '2025-09-03 12:19:04'),
(183, 10, 'like', 'reality1111 &#039;Test Konusu&#039; konunuzu favorilerine ekledi.', 0, '2025-09-03 12:21:51'),
(184, 10, 'like', 'reality1111 &#039;Test Konusu&#039; konunuzu favorilerine ekledi.', 0, '2025-09-03 12:22:14'),
(185, 10, 'like', 'reality1111 &#039;Test Konusu&#039; konunuzu favorilerine ekledi.', 0, '2025-09-03 12:22:16'),
(186, 10, 'like', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Favorilerine Ekledi.', 0, '2025-09-03 12:22:46'),
(187, 10, 'like', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Favorilerine Ekledi.', 0, '2025-09-03 12:23:42'),
(188, 10, 'like', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Favorilerine Ekledi.', 0, '2025-09-03 12:23:44'),
(189, 10, 'like', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Beğendi.', 0, '2025-09-03 12:24:05'),
(190, 10, 'like', 'reality1111 Adlı Kullanıcı, 31 Kimlik Numaralı Yanıtınızı Beğendi.', 0, '2025-09-03 13:49:58'),
(191, 10, 'like', 'reality1111 Adlı Kullanıcı, 32 Kimlik Numaralı Yanıtınızı Beğendi.', 0, '2025-09-03 13:50:02'),
(192, 10, 'like', 'reality1111 Adlı Kullanıcı, Test Konusu Adlı Konunuzu Beğendi.', 0, '2025-09-03 13:50:08'),
(193, 10, 'like', 'reality1111 Adlı Kullanıcı, 32 Kimlik Numaralı Yanıtınızı Beğendi.', 0, '2025-09-03 13:58:54'),
(194, 10, 'like', 'reality1111 Adlı Kullanıcı, 32 Kimlik Numaralı Yanıtınızı Beğendi.', 0, '2025-09-03 13:59:03');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `posts`
--

INSERT INTO `posts` (`id`, `topic_id`, `user_id`, `content`, `created_at`, `updated_at`, `is_active`) VALUES
(31, 75, 10, 'Vcb1hYYbvTNCFagZrIctWJ97e4ABFFejeaGj5dxtOX0=', '2025-09-03 13:30:47', '2025-09-03 13:30:47', 1),
(32, 75, 10, 'l8tqrPUQ6KSC8AzePE8zTzKTalr+yFGGVIU4zoZfMF4eYUgw2W44M+D3eKwP13dw', '2025-09-03 13:41:11', '2025-09-03 13:41:11', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rate_limiter_logs`
--

CREATE TABLE `rate_limiter_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rate_limiter_settings`
--

CREATE TABLE `rate_limiter_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `rate_limiter_settings`
--

INSERT INTO `rate_limiter_settings` (`id`, `setting_name`, `value`, `description`) VALUES
(1, 'enabled', '0', 'Rate limiter aktif/pasif, 1=aktif, 0=pasif'),
(2, 'max_requests', '1', 'Bir IP için izin verilen maksimum istek sayısı'),
(3, 'time_window', '10', 'İstek sayısının sayılacağı süre, saniye cinsinden'),
(4, 'cooldown_period', '30', 'Limit aşıldığında IP bekleyeceği süre, saniye cinsinden'),
(5, 'whitelisted_ips', '', 'İstek limiti uygulanmayacak IP adresleri, virgülle ayrılmış'),
(6, 'storage_type', 'db', 'Depolama tipi: file veya db'),
(7, 'storage_path', 'rate_limit_storage', 'File depolama dizini'),
(8, 'custom_429_page', '0', 'Özel 429 rate limit sayfası 0 = default');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expire` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `token_hash`, `expire`, `created_at`) VALUES
(24, 10, '2d0efd7e9be16ac5ea5293194cbedc638a8629c9e820b21460e2158cd9f7b5ac', '2025-10-03 09:35:31', '2025-09-03 06:35:31');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reported_by` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `reported_at` datetime DEFAULT current_timestamp(),
  `status` enum('pending','reviewed','resolved') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `reports`
--

INSERT INTO `reports` (`id`, `reported_by`, `post_id`, `topic_id`, `reported_at`, `status`) VALUES
(11, 10, 32, 75, '2025-09-03 13:45:20', 'pending'),
(17, 10, 31, 75, '2025-09-03 13:49:04', 'pending');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `seo_settings`
--

CREATE TABLE `seo_settings` (
  `id` int(11) NOT NULL,
  `page` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `schema_type` varchar(100) DEFAULT 'WebPage',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `seo_settings`
--

INSERT INTO `seo_settings` (`id`, `page`, `title`, `description`, `canonical_url`, `og_image`, `schema_type`, `created_at`, `updated_at`) VALUES
(1, 'dashboard', 'Anasayfa', '{siteTitle} kullanıcı panelinde hesap ayarlarını, istatistiklerini ve bildirimlerini kolayca yönet.', '{fullSiteURL}', '{fullSiteURL}/assets/images/test-og.png', 'WebPage', '2025-08-22 14:01:19', '2025-08-22 14:28:29'),
(2, 'topics', 'Konular', '{siteTitle} sitesinde konuları rahatlıkla görüntüleyebilirsin.', '{fullSiteURL}/topics', '{fullSiteURL}/assets/images/test-og.png', 'WebPage', '2025-08-22 14:01:19', '2025-08-22 14:28:29');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `variable` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `settings`
--

INSERT INTO `settings` (`id`, `variable`, `value`) VALUES
(1, 'siteInfo', '{\"data\":{\"siteName\":\"Marlex Forum\",\"defaultSiteDescription\":\"Marlex Forum ama modern bir forum, sıvı cam tasarımıyla :)\",\"defaultSiteTitle\":\"\"}}'),
(2, 'defaultSiteDescription', 'Marlex Forum ama modern bir forum, sıvı cam tasarımıyla :)'),
(3, 'chat_active', 'false'),
(4, 'captchaExpiration', '60'),
(6, 'viewTopicIsLoggedCheck', 'false'),
(7, 'dashboardAnnounceLimit', '5'),
(8, 'registirationActive', 'true'),
(9, 'forceEmailVerification', 'false'),
(10, 'postsRequireAccept', 'false'),
(11, 'maxPostLength', '5000'),
(12, 'bannedWordsTable', 'banned_words'),
(13, 'smtpServer', ''),
(14, 'smtpPort', '587'),
(15, 'sendWelcomeMail', 'true'),
(16, 'sendNotificationMails', 'true'),
(17, 'sessionExpiration', '60'),
(18, 'require2FA', 'false'),
(19, 'logAdminActions', 'true'),
(20, 'minPasswordLength', '8'),
(21, 'minUsernameLength', '6'),
(22, 'defaultUserRole', ''),
(23, 'allowModeratorBans', 'true'),
(24, 'usersRemoveOwnPosts', 'false'),
(25, 'darkModeToggle', '0'),
(26, 'customCssTable', 'custom_css'),
(27, 'instantMailNotifications', 'false'),
(28, 'dailySummaryMail', 'false'),
(29, 'googleAnalyticsId', ''),
(30, 'maintenceMode', 'false'),
(31, 'allowApiUsage', 'false'),
(32, 'welcomeMessage', 'Marlex Forum\'a Hoşgeldiniz'),
(33, 'forceUploadProcilePicture', 'false'),
(34, 'allowChangeUsername', 'false'),
(35, 'requireCaptchaRegistiration', 'false'),
(36, 'maxPostTitleLength', '120'),
(37, 'maxImagePerPost', '5'),
(38, 'maxTagPerPosts', '8'),
(39, 'maxPostsPerMinute', '10'),
(40, 'captchaType', '1'),
(41, 'enableRankSystem', 'false'),
(42, 'ranksTable', 'user_roles'),
(43, 'automaticallyAssignBadges', 'false'),
(44, 'customPagesTable', 'custom_pages'),
(45, 'adsSnippet', ''),
(46, 'sponsoredTopicsTable', 'sponsored_topics'),
(47, 'activatePremiumAccountSystem', 'false'),
(48, 'kvkkText', ''),
(49, 'cookiePolicy', ''),
(50, 'adminLogsTable', 'admin_logs'),
(51, 'moderationLogsTable', 'moderation_logs'),
(52, 'sessionLogsTable', 'session_logs'),
(53, 'defaultLanguage', 'TR'),
(54, 'translateTable', 'translations'),
(55, 'activatePWA', 'false'),
(56, 'allowInstantMobileNotificatons', 'false'),
(100, 'pageDataTable', 'page_data'),
(101, 'recaptchaSiteKey', '6LettK8rAAAAACk38P-IpQSHqveI4Sf5kmiUDkev'),
(102, 'recaptchaSecretKey', '6LettK8rAAAAAMH_zNm_ofPBUkS7GLUlfqnp7gKm');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `site_variables`
--

CREATE TABLE `site_variables` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `site_variables`
--

INSERT INTO `site_variables` (`id`, `name`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'fullSiteURL', 'http://localhost', 'Varsayılan site urlsi', '2025-08-23 08:13:57', '2025-08-23 08:19:33'),
(2, 'siteTitle', 'Marlex Forum', 'Site adı', '2025-08-23 08:13:57', '2025-08-23 08:32:16'),
(3, 'email', 'info@marlexforum.net', 'Site iletişim maili', '2025-08-26 10:17:58', '2025-08-26 10:18:00'),
(8, 'instagram', 'marlexforum', 'Instagram adresi', '2025-08-26 10:17:58', '2025-08-26 10:18:00'),
(9, 'twitter', 'marlexforum', 'X (Twitter) adresi', '2025-08-26 10:17:58', '2025-08-26 10:18:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `subscriptions`
--

CREATE TABLE `subscriptions` (
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subscribed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(10, 'api'),
(11, 'forum'),
(8, 'Lorem İpsum'),
(9, 'php');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(50) NOT NULL,
  `meta_title` varchar(90) NOT NULL,
  `meta_description` varchar(180) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `is_removed` int(11) NOT NULL,
  `is_pinned` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `topics`
--

INSERT INTO `topics` (`id`, `title`, `description`, `content`, `slug`, `meta_title`, `meta_description`, `user_id`, `category_id`, `views`, `created_at`, `updated_at`, `is_active`, `is_removed`, `is_pinned`) VALUES
(75, 'Test Konusu', 'Test açıklaması', 'Mrw1yBiJm+B2gumAAw91KW16B5RpudyNDCWgkNplPB3/dqvncoYkqg3vLXZsahQ0ei8e/iUSf+HCA9Lcs79f+yr4aRzK6dljU5zRdh7rxlqftA55pb1zcFfM/WQJZ0cg1s7Rz/Rvte58n4y4vQeEF1FvgNIxtJXGwIfN5qVy+gjfdcGTZ2fpIFCstwxbbE4w4uALQXC9TQnFJ0vBEySuEt02oDeJOsM82W05oKgLaz9qCY4t0dFASd3pR2ZMdAznuVH1Dq9KnXRyN2I/SzL11yIrJ3ocbwrHmUTNQzrTSkQ2Gyn6yfFGh1Z/kr4cUIOSrzO0gQejIXRfxz+qKpG5LwXk55PkenwSjkw/wB4n1hsZBl6+X2OpJ/bzQqPZmI90SXSGj9G+S7kP3GyUqw4hY5EBhjOGdfrsObjKedFoFVwI6QuF0UR2EcBZqOBYv+1mNAUxunHdV/U3C5gZhKMg1viNFFUTvGpeG5crQQWOOjUHLr3uwRbCLUxDhnvyhGmdUuCYxfwuRN7hURZe9iNFRoKJ7yScXufTKtg0nD0CbEmk9U3Lh9mreyIofmZLvCgPHnnEyYDAcyIu2DRFZUPFowMxC0IXYXApkUtq1GCkjtQa/5Fk4ru8HXGjJ7g5QqHuvAvpXpDr8epoPfzzAxXT1irzU3ZlSZ7/9ASeRgdlMu6rbDUMSn3yX127dBf5x2XBj43zY7b0l5vRDMeznIlVRL/ey3Mf42i/ESGMNxBUO1LMs/anON/qFf4hGVicYmvHiZsUmZyjEfYoEuhXP1PZEV+vN/+Tj+ebf2HGtpkvFlQtzEJTY1qUsoubLhadGk8bO4YWO79Od8d1nBZqqKmNr8b+kyi382qD3hovKWvutN5jSLsjFWKzWEgZ8Az6j+MiYfcQb2d0nRNhI7zvZdqQND01avEbfQXLdBuC2d8tE6LnmL/LoLflO4dUnTB9I1mX6yW99Sc/B85077j2KEiBM+ignxZhMsm5LDQcVnZRTjw8GeUAaZSDVrwh0rX/5Zdq5nrHNxAN0m6BsbGIWd+J9dBO/OKi/IL6X82x/e+IWLhoGJhS4MbRPKMVHaMl1NKpIdv7rGnv1OaTxpjwPntwK7M9XPQF7d0tK5AX0y9Cp2LmH1zF+ZPioZeM9HNeyKuSQAVG0OjZ6DuDjodq1s5Ay6UujmSwjt+OGrvEJbDb7XnZ6mwAU5Whbe+dsSCxpVhUV0CN3G5wRmzu9TEI3eirO85UHNRsFfvp6LkBcLcdsYSizwD00eV83HDMD7rXOfMMu0PdFFuggOOzeCrnkX9BKbHfefB1aVP47FtTO7AxpaT9LKDCdoW5xQyLnETkEXm63m0UzpRv0u5FbzmC2swDzObBZ/74PezXWMpqcvH5wHaN+kJiZq+ducSQwt6ZfOwW/ga9/r5OGDPhdo3D+iq6OyQuveuLfa8LoQxxlFWBl33gFuX8kHTTAk/ZFMQNuWtCkBC5RvwgbOqi7VMjiM1foR3N1uEULrGzmLhLyDjuOtbnSK8vvLlvqZWxCNsKJO8z5ScriiJMC5Qpy6R8japrk9StmssB/uwTXsiNVMxV1COPKIVA+yKgWhb6xwWocs906UiLN2aVeup4fT65qBXKmzU/CPrdXX9GH96rW+9lH4dKBkYBY7XzexIetgO4rMEBSVz2AuF9Oo6owxvqA4haWVD2a+6ADA3H4U2rrIpm8aQMhDK8JFiszx+RCNzuypfZHk0ujiGfp+BczIiKWJelqH3KCs10eB/ghvKxxB7+t13CCHwLs2DpRHjo6kgLTTE4OvjBXGbPOELQsjm+ahU/3DLuXBw4e7t8wtVyIIZ3B3OSxWy2AwGsumbHV7T6m2nR+V0CdnyP+DRuDdMEp8AK2hu/iWjcuGT7Czpt1tCQ646TjtbQ+AMi/YxoKqdXbs0LQ8Ly4SjEn3YK7WImF2HAVAlO2qGIN8b526ktV83IvWQ=', 'test-konusu-896239', '', '', 10, 7, 1, '2025-09-03 09:40:50', '2025-09-03 09:40:52', 1, 0, 0),
(76, 'PHP ile Forum API Geliştirme', 'Bu konu forum sitem için API sistemi üzerine olacak.', 'WtKSvcX5MAvjHbcLk/7SJVz4CMwyt70HsbmiJdHQBWJ9Dg4S5qDteyplCr3Du7TJF0wyL8TlzKzFb8e+b8jTEGxwC37QERY+Lq9cOf4pjUrpOVBiPe5GcQ1itzcP18+uzKoltjMQ7iEelfY/DvqZUA==', 'php-ile-forum-api-gelistirme-139278', '', '', 10, 7, 0, '2025-09-03 10:58:54', '2025-09-03 11:28:02', 1, 1, 0),
(77, 'PHP ile Forum API Geliştirme', 'Bu konu forum sitem için API sistemi üzerine olacak.', 'HsyDts4uKtqrIkTzqx5fViX6ascGnCywJWxnGsMeJ/nemWxoceCv0kkAFn8RVAdUP7fIRUAzD7Oi5jiWtsZl6tnb9EhyWJFVWNlraNbtiCw7kQL+DUG8jsr8eymuZuVU5zulDeKmuq+KvSAZCewCCg==', 'php-ile-forum-api-gelistirme-788289', '', '', 10, 7, 1, '2025-09-03 11:03:22', '2025-09-03 11:15:11', 1, 1, 0),
(78, 'Lipsum 2', 'Test konusudur.', '0jcsejCTXG/fyInw+OhpNaLBktyLKYZpFzZrJaNW6LBxCw9aKO1rypN16tUWCR4YsVdk2t1e+qQxGtHKpMftvUHFWTuuh0plHuqd1M+cWWrizxYTNpClPb7h1sBcM20sXAwOQqu7hxJj0NUD5SXVdqsFMi95+BeZGOI/L2WccUM=', 'lipsum-2-902824', '', '', 10, 7, 1, '2025-09-03 12:32:55', '2025-09-03 12:32:56', 1, 0, 0),
(79, 'Testssssss', '', 'LfsDvDDJGT2nZjVYhwVOaRxVelmsvS4EykaF9plqgqE=', 'testssssss-404140', '', '', 10, 7, 0, '2025-09-03 14:53:18', '2025-09-03 14:53:18', 1, 0, 0),
(80, 'Testssssss', '', 'KBjx7s5/tX07t4ucm1YxMlHFjnIDER22GXyN9joIyWE=', 'testssssss-712446', '', '', 10, 7, 0, '2025-09-03 14:53:28', '2025-09-03 14:53:28', 1, 0, 0),
(81, 'Testssssss', '', 'kSGsjnBG0Aq/FOTjClhNRMlF3pSfc46788REgokvv3Q=', 'testssssss-713817', '', '', 10, 7, 0, '2025-09-03 14:53:30', '2025-09-03 14:53:30', 1, 0, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `topic_tags`
--

CREATE TABLE `topic_tags` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `topic_tags`
--

INSERT INTO `topic_tags` (`id`, `topic_id`, `tag_id`) VALUES
(13, 75, 8),
(14, 77, 9),
(15, 77, 10),
(16, 77, 11),
(17, 78, 8),
(18, 79, 9),
(19, 79, 10),
(20, 79, 11),
(21, 80, 9),
(22, 80, 10),
(23, 80, 11),
(24, 81, 9),
(25, 81, 10),
(26, 81, 11);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `displayName` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_admin` tinyint(1) DEFAULT 0,
  `last_ip` varchar(45) DEFAULT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `userRole` int(11) NOT NULL DEFAULT 1,
  `userPoints` int(11) NOT NULL,
  `sessionId` varchar(255) NOT NULL,
  `last_password_change` datetime DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `profile_views` int(11) DEFAULT 0,
  `login_attempts` int(11) DEFAULT 0,
  `account_locked_until` datetime DEFAULT NULL,
  `last_password_reset_at` datetime DEFAULT NULL,
  `last_activity_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_afk` int(11) NOT NULL,
  `last_topic_activity` varchar(255) NOT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `avatar_updated_at` datetime DEFAULT NULL,
  `delete_request_at` timestamp NULL DEFAULT NULL,
  `apiAuthKey` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `displayName`, `email`, `password_hash`, `created_at`, `updated_at`, `last_login`, `profile_picture`, `bio`, `location`, `website`, `is_active`, `is_admin`, `last_ip`, `preferences`, `userRole`, `userPoints`, `sessionId`, `last_password_change`, `email_verified_at`, `activation_code`, `profile_views`, `login_attempts`, `account_locked_until`, `last_password_reset_at`, `last_activity_time`, `is_afk`, `last_topic_activity`, `avatar_path`, `avatar_updated_at`, `delete_request_at`, `apiAuthKey`) VALUES
(10, 'reality1111', 'Reality', 'reality1111@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$VUx2djVtNGwxSDd5cDZkUw$i30hvnGBOqCb7lfu1pLsLdG788X0GJI/qMLR40yIRK8', '2024-08-24 07:59:56', '2025-09-03 15:21:25', '2025-09-03 09:35:31', NULL, 'Test biyografisidir.', 'Türkiye', 'https://marlexforum.net', 1, 1, '::1', NULL, 30, 46, '6c94385a86t57f4uiad41c1ju4', NULL, NULL, NULL, 0, 0, NULL, NULL, '2025-09-03 12:21:25', 0, '69', '/public/uploads/avatars/10/94c24ca15def5116bef65ac182e16631e5e6.jpg', '2025-08-29 10:46:43', NULL, '2e99c4ce4f19ffd125d05a96f289da10a428b1ac92726e97904ae3466df63d72');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_devices`
--

CREATE TABLE `user_devices` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `device_identifier` varchar(255) NOT NULL,
  `user_agent` text NOT NULL,
  `last_login` datetime NOT NULL,
  `remember_me` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_profile_views`
--

CREATE TABLE `user_profile_views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_user_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `custom_css` text NOT NULL,
  `permission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `user_roles`
--

INSERT INTO `user_roles` (`id`, `role_name`, `custom_css`, `permission`) VALUES
(1, 'Kullanıcı', 'background: #566573;', 7),
(6, 'Yeni Üye', 'background: #5d6d7e;', 7),
(7, 'Çaylak', 'background: #6c3483;', 7),
(8, 'Acemi', 'background: #7d3c98;', 7),
(9, 'Üye', 'background: #34495e;', 7),
(10, 'Kıdemli Üye', 'background: #95a5a6;', 6),
(11, 'Deneyimli Üye', 'background: #7f8c8d;', 6),
(12, 'Takipçi', 'background: #2c3e50;', 6),
(13, 'Katılımcı', 'background: #0e6251;', 6),
(14, 'Yardımsever', 'background: #117a65;', 6),
(15, 'Paylaşımcı', 'background: #1f618d;', 6),
(16, 'Aktif Üye', 'background: #3498db;', 5),
(17, 'Süper Üye', 'background: #2e86c1;', 5),
(18, 'Uzman', 'background: #20c997;', 5),
(19, 'Usta', 'background: #1abc9c;', 5),
(20, 'Tecrübeli', 'background: #16a085;', 5),
(21, 'Elit Üye', 'background: #27ae60;', 5),
(22, 'Efsane Üye', 'background: #2ecc71;', 5),
(23, 'Veteran', 'background: #d35400;', 4),
(24, 'Destekçi', 'background: #f39c12; color: #000;', 4),
(25, 'Sponsor', 'background: #f1c40f; color: #000;', 4),
(26, 'Gözetmen', 'background: #6c5ce7;', 3),
(27, 'Moderatör', 'background: #9b59b6;', 3),
(28, 'Kıdemli Mod', 'background: #8e44ad;', 3),
(29, 'Admin Yardımcısı', 'background: #c0392b;', 2),
(30, 'Admin / Kurucu', 'background: #e74c3c;', 1),
(31, 'Banned', 'background: #95a5a6;', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_role_assignments`
--

CREATE TABLE `user_role_assignments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `assigned_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `user_role_assignments`
--

INSERT INTO `user_role_assignments` (`id`, `user_id`, `role_id`, `assigned_at`) VALUES
(1, 10, 30, '2025-08-26 18:49:03'),
(2, 10, 29, '2025-08-26 18:49:03');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_views`
--

CREATE TABLE `user_views` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `topic_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `user_views`
--

INSERT INTO `user_views` (`id`, `user_id`, `session_id`, `topic_id`, `viewed_at`) VALUES
(80, 10, NULL, 75, '2025-09-03 06:40:52'),
(81, 10, NULL, 77, '2025-09-03 08:03:30'),
(82, 10, NULL, 78, '2025-09-03 09:32:56');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Tablo için indeksler `banned_words`
--
ALTER TABLE `banned_words`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `bans`
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniquserid` (`user_id`),
  ADD KEY `banned_by` (`banned_by`);

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Tablo için indeksler `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `direct_messages`
--
ALTER TABLE `direct_messages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `favorites_ibfk_1` (`user_id`);

--
-- Tablo için indeksler `ip_whitelist`
--
ALTER TABLE `ip_whitelist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_ip` (`ip_address`),
  ADD KEY `added_by_user_id` (`added_by_user_id`);

--
-- Tablo için indeksler `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `live_presence`
--
ALTER TABLE `live_presence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_sess_tab` (`session_id`,`tab_id`),
  ADD KEY `idx_topic_active` (`topic_id`,`last_active`),
  ADD KEY `idx_url_active` (`current_url`(255),`last_active`);

--
-- Tablo için indeksler `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Tablo için indeksler `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `rate_limiter_logs`
--
ALTER TABLE `rate_limiter_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip_timestamp` (`ip_address`,`timestamp`);

--
-- Tablo için indeksler `rate_limiter_settings`
--
ALTER TABLE `rate_limiter_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Tablo için indeksler `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reported_by` (`reported_by`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Tablo için indeksler `seo_settings`
--
ALTER TABLE `seo_settings`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `site_variables`
--
ALTER TABLE `site_variables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Tablo için indeksler `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`user_id`,`topic_id`,`category_id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Tablo için indeksler `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Tablo için indeksler `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniqslug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Tablo için indeksler `topic_tags`
--
ALTER TABLE `topic_tags`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `userRole` (`id`),
  ADD KEY `role` (`userRole`);

--
-- Tablo için indeksler `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `user_profile_views`
--
ALTER TABLE `user_profile_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ibfk2` (`viewed_user_id`),
  ADD KEY `ibfk` (`user_id`) USING BTREE;

--
-- Tablo için indeksler `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Tablo için indeksler `user_role_assignments`
--
ALTER TABLE `user_role_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `userid` (`user_id`);

--
-- Tablo için indeksler `user_views`
--
ALTER TABLE `user_views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_view` (`user_id`,`topic_id`,`session_id`) USING BTREE,
  ADD KEY `topics` (`topic_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `banned_words`
--
ALTER TABLE `banned_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `bans`
--
ALTER TABLE `bans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Tablo için AUTO_INCREMENT değeri `direct_messages`
--
ALTER TABLE `direct_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- Tablo için AUTO_INCREMENT değeri `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `ip_whitelist`
--
ALTER TABLE `ip_whitelist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- Tablo için AUTO_INCREMENT değeri `live_presence`
--
ALTER TABLE `live_presence`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Tablo için AUTO_INCREMENT değeri `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- Tablo için AUTO_INCREMENT değeri `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Tablo için AUTO_INCREMENT değeri `rate_limiter_logs`
--
ALTER TABLE `rate_limiter_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- Tablo için AUTO_INCREMENT değeri `rate_limiter_settings`
--
ALTER TABLE `rate_limiter_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Tablo için AUTO_INCREMENT değeri `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Tablo için AUTO_INCREMENT değeri `seo_settings`
--
ALTER TABLE `seo_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- Tablo için AUTO_INCREMENT değeri `site_variables`
--
ALTER TABLE `site_variables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- Tablo için AUTO_INCREMENT değeri `topic_tags`
--
ALTER TABLE `topic_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Tablo için AUTO_INCREMENT değeri `user_profile_views`
--
ALTER TABLE `user_profile_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Tablo için AUTO_INCREMENT değeri `user_role_assignments`
--
ALTER TABLE `user_role_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Tablo için AUTO_INCREMENT değeri `user_views`
--
ALTER TABLE `user_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Tablo kısıtlamaları `bans`
--
ALTER TABLE `bans`
  ADD CONSTRAINT `bans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bans_ibfk_2` FOREIGN KEY (`banned_by`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`);

--
-- Tablo kısıtlamaları `ip_whitelist`
--
ALTER TABLE `ip_whitelist`
  ADD CONSTRAINT `user` FOREIGN KEY (`added_by_user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `reports_ibfk_3` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`);

--
-- Tablo kısıtlamaları `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  ADD CONSTRAINT `subscriptions_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Tablo kısıtlamaları `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Tablo kısıtlamaları `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `role` FOREIGN KEY (`userRole`) REFERENCES `user_roles` (`id`);

--
-- Tablo kısıtlamaları `user_devices`
--
ALTER TABLE `user_devices`
  ADD CONSTRAINT `user_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `user_profile_views`
--
ALTER TABLE `user_profile_views`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_viewed_user_id` FOREIGN KEY (`viewed_user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `user_role_assignments`
--
ALTER TABLE `user_role_assignments`
  ADD CONSTRAINT `user_role_assignments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_role_assignments_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`);

--
-- Tablo kısıtlamaları `user_views`
--
ALTER TABLE `user_views`
  ADD CONSTRAINT `topics` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
