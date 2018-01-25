-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2017 at 09:33 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_vtgram_pro`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `data` text,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_accounts`
--

DROP TABLE IF EXISTS `instagram_accounts`;
CREATE TABLE `instagram_accounts` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `fid` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `checkpoint` int(11) DEFAULT '0',
  `status` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_activity`
--

DROP TABLE IF EXISTS `instagram_activity`;
CREATE TABLE `instagram_activity` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `data` longtext,
  `status` int(11) DEFAULT '1',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_history`
--

DROP TABLE IF EXISTS `instagram_history`;
CREATE TABLE `instagram_history` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `pk` varchar(255) DEFAULT NULL,
  `data` text,
  `created` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_schedules`
--

DROP TABLE IF EXISTS `instagram_schedules`;
CREATE TABLE `instagram_schedules` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `group_id` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `group_type` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `privacy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `speed` int(11) DEFAULT '1',
  `name` varchar(255) DEFAULT NULL,
  `message` text,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `description` text,
  `url` text CHARACTER SET utf8,
  `image` text CHARACTER SET utf8,
  `caption` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `comment` text,
  `filter` longtext,
  `time_post` datetime DEFAULT NULL,
  `time_post_show` datetime DEFAULT NULL,
  `delete_post` int(1) DEFAULT '0',
  `deplay` int(11) DEFAULT NULL,
  `repeat_post` int(1) DEFAULT '0',
  `repeat_time` int(11) DEFAULT NULL,
  `repeat_end` date DEFAULT NULL,
  `result` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `message_error` text CHARACTER SET utf8,
  `status` int(1) DEFAULT '1',
  `changed` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

DROP TABLE IF EXISTS `package`;
CREATE TABLE `package` (
  `id` int(11) NOT NULL,
  `type` int(1) DEFAULT '2',
  `name` varchar(255) DEFAULT NULL,
  `price` varchar(11) DEFAULT NULL,
  `day` int(11) DEFAULT NULL,
  `permission` text,
  `default_package` int(1) DEFAULT '0',
  `orders` int(11) DEFAULT '0',
  `status` int(1) DEFAULT '1',
  `changed` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`id`, `type`, `name`, `price`, `day`, `permission`, `default_package`, `orders`, `status`, `changed`, `created`) VALUES
(1, 0, 'Free', '0', 6, '{"maximum_account":1,"post":1,"message":1,"activity":1,"search":1,"download":1}', 0, 0, 1, '2017-06-09 12:34:36', NULL),
(2, 2, 'Basic', '20', 30, '{"maximum_account":1,"post":1,"message":1,"activity":1,"search":1,"download":1}', 0, 1000, 1, '2017-07-06 13:29:54', NULL),
(3, 2, 'Standard', '50', 60, '{"maximum_account":3,"post":1,"message":1,"activity":1,"search":1,"download":1}', 1, 2000, 1, '2017-07-06 13:29:43', '2017-06-15 17:38:21'),
(4, 2, 'Premium', '120', 365, '{"maximum_account":5,"post":1,"message":1,"activity":1,"search":1,"download":1}', 0, 3000, 1, '2017-07-06 13:29:33', '2017-07-06 11:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `pagseguro_email` varchar(255) DEFAULT NULL,
  `pagseguro_token` varchar(255) DEFAULT NULL,
  `sandbox` int(1) DEFAULT '0',
  `currency` varchar(32) DEFAULT NULL,
  `symbol` varchar(255) DEFAULT '$'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `paypal_email`, `pagseguro_email`, `pagseguro_token`, `sandbox`, `currency`, `symbol`) VALUES
(1, 'tienpham1606@gmail.com', NULL, NULL, 0, 'USD', '$');

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

DROP TABLE IF EXISTS `payment_history`;
CREATE TABLE `payment_history` (
  `id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `invoice` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `business` varchar(255) DEFAULT NULL,
  `receiver_email` varchar(255) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_number` int(1) DEFAULT NULL,
  `address_street` varchar(255) DEFAULT NULL,
  `address_city` varchar(255) DEFAULT NULL,
  `address_country` varchar(255) DEFAULT NULL,
  `mc_gross` float DEFAULT NULL,
  `feeAmount` float DEFAULT NULL,
  `netAmount` float DEFAULT NULL,
  `mc_currency` varchar(255) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `full_data` text,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `save`
--

DROP TABLE IF EXISTS `save`;
CREATE TABLE `save` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `message` text,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `caption` varchar(255) DEFAULT NULL,
  `url` text,
  `image` text,
  `status` int(1) DEFAULT '1',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `website_title` text,
  `website_description` text,
  `website_keyword` text,
  `logo` varchar(255) DEFAULT NULL,
  `theme_color` varchar(255) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `upload_max_size` int(11) DEFAULT '5',
  `register` int(1) DEFAULT '1',
  `auto_active_user` int(1) DEFAULT '1',
  `default_language` varchar(255) DEFAULT NULL,
  `schedule_default` text,
  `default_deplay` int(11) DEFAULT NULL,
  `default_deplay_post_now` int(11) DEFAULT NULL,
  `minimum_deplay` int(11) DEFAULT NULL,
  `minimum_deplay_post_now` int(11) NOT NULL,
  `purchase_code` varchar(255) DEFAULT NULL,
  `facebook_id` varchar(255) DEFAULT NULL,
  `facebook_secret` varchar(255) DEFAULT NULL,
  `google_api_key` text,
  `google_id` varchar(255) DEFAULT NULL,
  `google_secret` varchar(255) DEFAULT NULL,
  `twitter_id` varchar(255) DEFAULT NULL,
  `twitter_secret` varchar(255) DEFAULT NULL,
  `facebook_page` varchar(255) DEFAULT NULL,
  `twitter_page` varchar(255) DEFAULT NULL,
  `pinterest_page` varchar(255) DEFAULT NULL,
  `instagram_page` varchar(255) DEFAULT NULL,
  `mail_type` int(1) DEFAULT '1',
  `mail_from_name` text,
  `mail_from_email` text,
  `mail_smtp_host` text,
  `mail_smtp_user` text,
  `mail_smtp_pass` text,
  `mail_smtp_port` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `website_title`, `website_description`, `website_keyword`, `logo`, `theme_color`, `timezone`, `upload_max_size`, `register`, `auto_active_user`, `default_language`, `schedule_default`, `default_deplay`, `default_deplay_post_now`, `minimum_deplay`, `minimum_deplay_post_now`, `purchase_code`, `facebook_id`, `facebook_secret`, `google_api_key`, `google_id`, `google_secret`, `twitter_id`, `twitter_secret`, `facebook_page`, `twitter_page`, `pinterest_page`, `instagram_page`, `mail_type`, `mail_from_name`, `mail_from_email`, `mail_smtp_host`, `mail_smtp_user`, `mail_smtp_pass`, `mail_smtp_port`) VALUES
(1, 'GramEasy - Instagram Automatic Tool', 'The ultimate way to help your marketing effectiveness on Instagram today', 'The ultimate way to help your marketing effectiveness on Instagram today', 'assets/images/logo.png', 'blue-grey', 'admin_timezone', 30, 1, 1, 'en', '{"todo":"{\\"like\\":1,\\"comment\\":1,\\"follow\\":0,\\"followback\\":0,\\"unfollow\\":0,\\"repost\\":0,\\"deletemedia\\":1}","target":"{\\"tag\\":1,\\"location\\":1,\\"username\\":1,\\"timeline\\":1,\\"popular\\":1,\\"your_feed\\":1,\\"explore_tab\\":1}","tags":"[\\"author\\",\\"vacation\\",\\"instaart\\",\\"nature\\",\\"tasty\\",\\"masterpiece\\",\\"creative\\",\\"bestoftheday\\",\\"pretty\\",\\"siblings\\",\\"clouds\\",\\"page\\",\\"throwbackthursday\\",\\"cuddle\\",\\"instafollow\\",\\"lovely\\",\\"shoutout\\",\\"cute\\",\\"draw\\"]","locations":"null","comments":"[\\" Made my day\\",\\"Totally rocks!\\",\\"Very nice\\",\\"Very sweet :)\\",\\"This is great\\",\\"So cool\\",\\"Fascinating one\\",\\"Neat-o!\\",\\"Gorgeous! Love it!\\",\\"The cutest :grinning:\\",\\"Breathtaking one\\",\\"This is awesome :)\\",\\"Outstanding one!\\",\\"Whoopee!\\",\\"My Goodness!\\",\\"This is awesome!\\"]","messages":"null","slow":"{\\"repost\\":1,\\"like\\":20,\\"comment\\":6,\\"deletemedia\\":10,\\"follow\\":15,\\"followback\\":15,\\"unfollow\\":15,\\"delay\\":6}","medium":"{\\"repost\\":2,\\"like\\":30,\\"comment\\":8,\\"deletemedia\\":20,\\"follow\\":20,\\"followback\\":20,\\"unfollow\\":20,\\"delay\\":4}","fast":"{\\"repost\\":3,\\"like\\":40,\\"comment\\":10,\\"deletemedia\\":30,\\"follow\\":25,\\"followback\\":25,\\"unfollow\\":25,\\"delay\\":2}","speed":"1","filter":"{\\"media_age\\":\\"\\",\\"media_type\\":\\"\\",\\"min_likes\\":0,\\"max_likes\\":0,\\"min_comments\\":0,\\"max_comments\\":0,\\"user_relation\\":\\"\\",\\"user_profile\\":\\"\\",\\"min_followers\\":0,\\"max_followers\\":0,\\"min_followings\\":0,\\"max_followings\\":0,\\"gender\\":\\"\\"}"}', 5, 120, 1, 10, 'ITEM-PURCHASE-CODE', '', '', 'admin_google_api_key', '', '', '', '', '', '', '', '', 1, '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_management`
--

DROP TABLE IF EXISTS `user_management`;
CREATE TABLE `user_management` (
  `id` int(11) NOT NULL,
  `admin` int(1) DEFAULT '0',
  `type` varchar(255) DEFAULT NULL,
  `pid` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `history_id` text,
  `timezone` varchar(255) DEFAULT NULL,
  `reset_key` text,
  `status` int(1) DEFAULT '1',
  `changed` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_management`
--

INSERT INTO `user_management` (`id`, `admin`, `type`, `pid`, `fullname`, `email`, `password`, `package_id`, `expiration_date`, `history_id`, `timezone`, `reset_key`, `status`, `changed`, `created`) VALUES
(1, 1, 'direct', NULL, 'admin_fullname', 'admin_email', 'admin_password', 1, '2025-12-25', NULL, 'admin_timezone', 'bac5222837b6b27f8daf7acd4f828110', 1, '2016-10-06 22:11:10', '2016-09-30 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_accounts`
--
ALTER TABLE `instagram_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_activity`
--
ALTER TABLE `instagram_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_history`
--
ALTER TABLE `instagram_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_schedules`
--
ALTER TABLE `instagram_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `save`
--
ALTER TABLE `save`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_management`
--
ALTER TABLE `user_management`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instagram_accounts`
--
ALTER TABLE `instagram_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instagram_activity`
--
ALTER TABLE `instagram_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instagram_history`
--
ALTER TABLE `instagram_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instagram_schedules`
--
ALTER TABLE `instagram_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `save`
--
ALTER TABLE `save`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_management`
--
ALTER TABLE `user_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
