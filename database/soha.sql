-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 20, 2023 at 09:56 AM
-- Server version: 5.7.24
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `soha`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `bank_name`, `account_name`, `account_number`, `branch`, `status`, `created_by`, `modified_by`, `created_at`, `updated_at`) VALUES
(1, 'ব্যাংক এশিয়া (সি.সি)', 'মেসার্স সোহা অটো রাইস মিল', '07633000165', 'দিনাজপুর', '1', 'Super Admin', 'Super Admin', '2023-03-19 04:43:29', '2023-03-19 04:49:15'),
(2, 'ব্যাংক এশিয়া', 'মেসার্স সোহা অটো রাইস মিল', '07633000141', 'দিনাজপুর', '1', 'Super Admin', NULL, '2023-03-19 04:43:56', '2023-03-19 04:43:56'),
(3, 'ব্যাংক এশিয়া - লিজ ফিনেন্স', 'মেসার্স সোহা অটো রাইস মিল', '07635000009', 'দিনাজপুর', '1', 'Super Admin', NULL, '2023-03-19 04:44:51', '2023-03-19 04:44:51'),
(4, 'ব্যাংক এশিয়া - নির্মান লোন', 'মেসার্স সোহা অটো রাইস মিল ইউনিট-2', '07635000088', 'দিনাজপুর', '1', 'Super Admin', NULL, '2023-03-19 04:46:33', '2023-03-19 04:46:33'),
(5, 'ব্যাংক এশিয়া - মেশিনারী লোন', 'মেসার্স সোহা অটো রাইস মিল ইউনিট-2', '07635000089', 'দিনাজপুর', '1', 'Super Admin', NULL, '2023-03-19 04:47:12', '2023-03-19 04:47:12'),
(6, 'ঢাকা ব্যাংক লিঃ (সি.সি)', 'মেসার্স ভাই ভাই অটো রাইস মিল', '4371850000774', 'দিনাজপুর', '1', 'Super Admin', NULL, '2023-03-19 04:48:14', '2023-03-19 04:48:14'),
(7, 'ঢাকা ব্যাংক লিঃ', 'মেসার্স ভাই ভাই অটো রাইস মিল', '4371000003857', 'দিনাজপুর', '1', 'Super Admin', NULL, '2023-03-19 04:48:49', '2023-03-19 04:48:49');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Active , 2 = InActive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `status`, `created_by`, `modified_by`, `created_at`, `updated_at`) VALUES
(1, 'Raw', '1', 'Super Admin', NULL, '2023-03-01 05:18:29', '2023-03-01 05:18:29'),
(2, 'Finish', '1', 'Super Admin', NULL, '2023-03-06 06:27:10', '2023-03-06 06:27:10'),
(3, 'Bag', '1', 'Super Admin', NULL, '2023-03-06 06:48:44', '2023-03-06 06:48:44'),
(4, 'By Product', '1', 'Super Admin', NULL, '2023-03-09 04:59:03', '2023-03-09 04:59:03');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_heads`
--

CREATE TABLE `chart_of_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `master_head` enum('1','2','3','4','5','6','7','8') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Current Asset , 2 = Non-Current Asset , 3 = Current Liabilities , 4 = Non-Current Liabilities , 5 = Income , 6 = Cost Of Goods Sold, 7 = Expenses , 8 = Equity',
  `type` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT ' 1 = Head , 2 = Sub Head , 3 = Child Head',
  `head_id` bigint(20) DEFAULT NULL,
  `sub_head_id` bigint(20) DEFAULT NULL,
  `party_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tenant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `labor_head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mobile_bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mill_id` bigint(20) UNSIGNED DEFAULT NULL,
  `truck_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `classification` enum('1','2','3','4','5','6','7','8','9') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Default , 2 = Party , 3 = Labor Head , 4 = Expense , 5 = Bank , 6 = Mobile Bank , 7 = Mill , 8 = Transport , 9 = Tenant',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_heads`
--

INSERT INTO `chart_of_heads` (`id`, `master_head`, `type`, `head_id`, `sub_head_id`, `party_id`, `tenant_id`, `labor_head_id`, `bank_id`, `mobile_bank_id`, `mill_id`, `truck_id`, `expense_item_id`, `name`, `classification`, `created_at`, `updated_at`) VALUES
(1, '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Account Receivable', '1', '2023-03-07 00:53:13', '2023-03-14 05:54:14'),
(2, '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cash & Bank Balance', '1', '2023-03-07 00:53:45', '2023-03-14 05:53:03'),
(3, '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Advance, Deposit & Pre-payments', '1', '2023-03-07 00:54:08', '2023-03-14 05:53:32'),
(4, '2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Transportation', '1', '2023-03-07 00:54:47', '2023-03-07 00:54:47'),
(5, '2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Mill Building', '1', '2023-03-07 00:54:59', '2023-03-07 00:54:59'),
(6, '2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Land', '1', '2023-03-07 00:55:13', '2023-03-07 00:55:13'),
(7, '2', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Machineries', '1', '2023-03-07 00:55:27', '2023-03-07 00:55:27'),
(8, '3', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Payable', '1', '2023-03-07 00:56:50', '2023-03-07 00:56:50'),
(9, '3', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Accured Expense', '1', '2023-03-07 00:57:07', '2023-03-07 00:57:07'),
(10, '3', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Current Portion Of Long Term Loan', '1', '2023-03-07 00:57:23', '2023-03-07 00:57:23'),
(11, '3', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Bank Overdraft', '1', '2023-03-07 01:13:52', '2023-03-07 01:13:52'),
(12, '3', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Probition', '1', '2023-03-07 01:14:04', '2023-03-07 01:14:04'),
(13, '3', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Short Term Loan', '1', '2023-03-07 01:14:18', '2023-03-07 01:14:18'),
(14, '4', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Long Term Loan', '1', '2023-03-07 01:14:39', '2023-03-07 01:14:39'),
(15, '5', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Income From Mill', '1', '2023-03-07 01:15:29', '2023-03-07 01:15:29'),
(16, '5', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Income From Transport', '1', '2023-03-07 01:16:26', '2023-03-07 01:16:26'),
(17, '5', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sales Revenue', '1', '2023-03-07 01:16:41', '2023-03-14 06:03:03'),
(18, '6', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Purchase', '1', '2023-03-07 01:16:51', '2023-03-07 01:16:51'),
(19, '7', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Production Expense', '1', '2023-03-07 01:17:02', '2023-03-07 01:17:02'),
(20, '7', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Other Expense', '1', '2023-03-07 01:17:13', '2023-03-07 01:17:13'),
(21, '1', '2', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Party', '1', '2023-03-07 01:19:21', '2023-03-07 01:19:21'),
(22, '1', '2', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Walking Party', '1', '2023-03-07 01:19:43', '2023-03-07 01:19:43'),
(23, '1', '2', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tenant', '1', '2023-03-07 01:20:03', '2023-03-07 01:20:03'),
(24, '1', '2', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cash In Hand', '1', '2023-03-07 01:20:40', '2023-03-07 01:20:40'),
(25, '1', '2', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cash At Bank', '1', '2023-03-07 01:20:55', '2023-03-07 01:20:55'),
(26, '1', '2', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Cash At Mobile', '1', '2023-03-07 01:21:07', '2023-03-07 01:21:07'),
(27, '3', '2', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Party', '1', '2023-03-07 01:21:59', '2023-03-07 01:21:59'),
(28, '3', '2', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Walking Party', '1', '2023-03-07 01:22:16', '2023-03-07 01:22:16'),
(29, '3', '2', 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Labor', '1', '2023-03-07 01:22:30', '2023-03-07 01:22:30'),
(56, '5', '2', 15, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'সোহা অটো ইউনিট-1(তরিমপুর, পুলহাট, দিনাজপুর)', '7', '2023-03-19 03:02:13', '2023-03-19 03:02:13'),
(57, '5', '2', 15, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, 'সোহা অটো ইউনিট-2(তরিমপুর, পুলহাট, দিনাজপুর)', '7', '2023-03-19 03:02:31', '2023-03-19 03:02:31'),
(58, '5', '2', 15, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, 'ভাই ভাই অটো রাইস মিল(আউলিয়াপুর, পুলহাট, সদর, দিনাজপুর।)', '7', '2023-03-19 03:02:48', '2023-03-19 03:02:48'),
(59, '1', '3', 1, 21, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'রায়হান ট্রেডার্স', '2', NULL, NULL),
(60, '3', '3', 8, 27, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'রায়হান ট্রেডার্স', '2', NULL, NULL),
(61, '1', '3', 1, 21, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সায়মা ট্রেডার্স', '2', NULL, NULL),
(62, '3', '3', 8, 27, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সায়মা ট্রেডার্স', '2', NULL, NULL),
(63, '1', '3', 1, 21, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'পিংকি ট্রেডার্স', '2', NULL, NULL),
(64, '3', '3', 8, 27, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'পিংকি ট্রেডার্স', '2', NULL, NULL),
(65, '1', '3', 1, 21, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'রানা ট্রেডর্স', '2', NULL, NULL),
(66, '3', '3', 8, 27, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'রানা ট্রেডর্স', '2', NULL, NULL),
(67, '1', '3', 1, 21, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'লাবন্য ট্রেডার্স', '2', NULL, NULL),
(68, '3', '3', 8, 27, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'লাবন্য ট্রেডার্স', '2', NULL, NULL),
(69, '1', '3', 1, 21, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'করিম কাদের ট্রেডার্স', '2', NULL, NULL),
(70, '3', '3', 8, 27, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'করিম কাদের ট্রেডার্স', '2', NULL, NULL),
(71, '1', '3', 1, 21, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'নূরী ট্রেডার্স', '2', NULL, NULL),
(72, '3', '3', 8, 27, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'নূরী ট্রেডার্স', '2', NULL, NULL),
(73, '1', '3', 1, 21, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সাঈদ ট্রেডার্স', '2', NULL, NULL),
(74, '3', '3', 8, 27, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সাঈদ ট্রেডার্স', '2', NULL, NULL),
(75, '1', '3', 1, 21, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'মোশারফ ট্রেডার্স', '2', NULL, NULL),
(76, '3', '3', 8, 27, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'মোশারফ ট্রেডার্স', '2', NULL, NULL),
(77, '1', '3', 1, 21, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'লাকী এন্টারপ্রাইজ', '2', NULL, NULL),
(78, '3', '3', 8, 27, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'লাকী এন্টারপ্রাইজ', '2', NULL, NULL),
(79, '1', '3', 1, 21, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'জাকির ট্রেডার্স', '2', NULL, NULL),
(80, '3', '3', 8, 27, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'জাকির ট্রেডার্স', '2', NULL, NULL),
(81, '1', '3', 1, 21, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সিয়াম ট্রেডার্স', '2', NULL, NULL),
(82, '3', '3', 8, 27, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সিয়াম ট্রেডার্স', '2', NULL, NULL),
(83, '1', '3', 1, 21, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'নুরজাহান ট্রেডার্স', '2', NULL, NULL),
(84, '3', '3', 8, 27, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'নুরজাহান ট্রেডার্স', '2', NULL, NULL),
(85, '1', '3', 2, 25, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'মেসার্স সোহা অটো রাইস মিল(07633000165)', '5', '2023-03-19 04:43:29', '2023-03-19 04:43:29'),
(86, '1', '3', 2, 25, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, 'মেসার্স সোহা অটো রাইস মিল(07633000141)', '5', '2023-03-19 04:43:56', '2023-03-19 04:43:56'),
(87, '1', '3', 2, 25, NULL, NULL, NULL, 3, NULL, NULL, NULL, NULL, 'মেসার্স সোহা অটো রাইস মিল(07635000009)', '5', '2023-03-19 04:44:51', '2023-03-19 04:44:51'),
(88, '1', '3', 2, 25, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, 'মেসার্স সোহা অটো রাইস মিল ইউনিট-2(07635000088)', '5', '2023-03-19 04:46:33', '2023-03-19 04:46:33'),
(89, '1', '3', 2, 25, NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, 'মেসার্স সোহা অটো রাইস মিল ইউনিট-2(07635000089)', '5', '2023-03-19 04:47:12', '2023-03-19 04:47:12'),
(90, '1', '3', 2, 25, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, 'মেসার্স ভাই ভাই অটো রাইস মিল(4371850000774)', '5', '2023-03-19 04:48:14', '2023-03-19 04:48:14'),
(91, '1', '3', 2, 25, NULL, NULL, NULL, 7, NULL, NULL, NULL, NULL, 'মেসার্স ভাই ভাই অটো রাইস মিল(4371000003857)', '5', '2023-03-19 04:48:49', '2023-03-19 04:48:49'),
(92, '7', '2', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 'অফিস খরচ(Other Expense)', '7', '2023-03-19 04:50:09', '2023-03-19 04:50:09'),
(93, '7', '2', 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 'সর্দার লেবার বিল(Production Expense)', '7', '2023-03-19 04:50:28', '2023-03-19 04:50:28'),
(94, '7', '2', 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 'ধান নেটিং লেবার বিল(Production Expense)', '7', '2023-03-19 04:50:46', '2023-03-19 04:50:46'),
(95, '7', '2', 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 'মিল মেশিনারী(Production Expense)', '7', '2023-03-19 04:51:30', '2023-03-19 04:51:30'),
(96, '7', '2', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 'মোবাইল খরচ(Other Expense)', '7', '2023-03-19 04:51:47', '2023-03-19 04:51:47'),
(97, '7', '2', 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 'বস্তা রিপু খরচ(Production Expense)', '7', '2023-03-19 04:52:06', '2023-03-19 04:52:06'),
(98, '7', '2', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 'বিবিধ খরচ(Other Expense)', '7', '2023-03-19 04:52:22', '2023-03-19 04:52:22'),
(99, '7', '2', 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 'মহিলা লেবার বিল(Production Expense)', '7', '2023-03-19 04:52:43', '2023-03-19 04:52:43'),
(100, '7', '2', 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 9, 'কর্মচারী বেতন খরচ(Production Expense)', '7', '2023-03-19 04:53:03', '2023-03-19 04:53:03'),
(101, '7', '2', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, 'ট্রাক ভাড়া খরচ(Other Expense)', '7', '2023-03-19 04:53:23', '2023-03-19 04:53:23'),
(102, '7', '2', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 11, 'মটরসাইকেল খরচ/যাতায়াত খরচ(Other Expense)', '7', '2023-03-19 04:53:59', '2023-03-19 04:53:59'),
(103, '7', '2', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 'ব্যাংক ইন্টারেষ্ট খরচ(Other Expense)', '7', '2023-03-19 04:54:19', '2023-03-19 04:54:19'),
(104, '7', '2', 20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 'কর ফি খরচ(Other Expense)', '7', '2023-03-19 04:54:37', '2023-03-19 04:54:37'),
(105, '7', '2', 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 14, 'বিদ্যূৎ বিল খরচ(Production Expense)', '7', '2023-03-19 04:54:55', '2023-03-19 04:54:55'),
(106, '1', '3', 1, 21, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সোহা ফুড ইন্ডাষ্ট্রিজ', '2', NULL, NULL),
(107, '3', '3', 8, 27, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সোহা ফুড ইন্ডাষ্ট্রিজ', '2', NULL, NULL),
(108, '1', '3', 1, 21, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ভাই ভাই অটো রাইস মিল', '2', NULL, NULL),
(109, '3', '3', 8, 27, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ভাই ভাই অটো রাইস মিল', '2', NULL, NULL),
(110, '1', '3', 1, 21, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'দিনাজপুর ইন্ডাষ্ট্রিজ', '2', NULL, NULL),
(111, '3', '3', 8, 27, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'দিনাজপুর ইন্ডাষ্ট্রিজ', '2', NULL, NULL),
(112, '1', '3', 1, 21, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'আল-তুবা গ্লোবাল লিমিটেড', '2', NULL, NULL),
(113, '3', '3', 8, 27, 17, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'আল-তুবা গ্লোবাল লিমিটেড', '2', NULL, NULL),
(114, '1', '3', 1, 21, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সোহা পরিবহন আয় ব্যায়', '2', NULL, NULL),
(115, '3', '3', 8, 27, 18, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'সোহা পরিবহন আয় ব্যায়', '2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `distributions`
--

CREATE TABLE `distributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `distribution_status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT ' 1 = Approved , 2 = Pending',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `distribution_products`
--

CREATE TABLE `distribution_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `distribution_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT '0',
  `scale` double DEFAULT '0',
  `dis_qty` double DEFAULT '0',
  `date` date DEFAULT '0000-00-00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_items`
--

CREATE TABLE `expense_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `expense_type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 = Production Expense,2 = Other Expense',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_items`
--

INSERT INTO `expense_items` (`id`, `name`, `status`, `expense_type`, `created_by`, `modified_by`, `created_at`, `updated_at`) VALUES
(1, 'অফিস খরচ', '1', '2', 'Super Admin', NULL, '2023-03-19 04:50:09', '2023-03-19 04:50:09'),
(2, 'সর্দার লেবার বিল', '1', '1', 'Super Admin', NULL, '2023-03-19 04:50:28', '2023-03-19 04:50:28'),
(3, 'ধান নেটিং লেবার বিল', '1', '1', 'Super Admin', NULL, '2023-03-19 04:50:46', '2023-03-19 04:50:46'),
(4, 'মিল মেশিনারী', '1', '1', 'Super Admin', NULL, '2023-03-19 04:51:30', '2023-03-19 04:51:30'),
(5, 'মোবাইল খরচ', '1', '2', 'Super Admin', NULL, '2023-03-19 04:51:47', '2023-03-19 04:51:47'),
(6, 'বস্তা রিপু খরচ', '1', '1', 'Super Admin', NULL, '2023-03-19 04:52:06', '2023-03-19 04:52:06'),
(7, 'বিবিধ খরচ', '1', '2', 'Super Admin', NULL, '2023-03-19 04:52:22', '2023-03-19 04:52:22'),
(8, 'মহিলা লেবার বিল', '1', '1', 'Super Admin', NULL, '2023-03-19 04:52:43', '2023-03-19 04:52:43'),
(9, 'কর্মচারী বেতন খরচ', '1', '1', 'Super Admin', NULL, '2023-03-19 04:53:03', '2023-03-19 04:53:03'),
(10, 'ট্রাক ভাড়া খরচ', '1', '2', 'Super Admin', NULL, '2023-03-19 04:53:23', '2023-03-19 04:53:23'),
(11, 'মটরসাইকেল খরচ/যাতায়াত খরচ', '1', '2', 'Super Admin', NULL, '2023-03-19 04:53:51', '2023-03-19 04:53:51'),
(12, 'ব্যাংক ইন্টারেষ্ট খরচ', '1', '2', 'Super Admin', NULL, '2023-03-19 04:54:19', '2023-03-19 04:54:19'),
(13, 'কর ফি খরচ', '1', '2', 'Super Admin', NULL, '2023-03-19 04:54:37', '2023-03-19 04:54:37'),
(14, 'বিদ্যূৎ বিল খরচ', '1', '1', 'Super Admin', NULL, '2023-03-19 04:54:55', '2023-03-19 04:54:55');

-- --------------------------------------------------------

--
-- Table structure for table `labor_bills`
--

CREATE TABLE `labor_bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `labor_head_id` bigint(20) UNSIGNED NOT NULL,
  `labor_bill_rate_id` bigint(20) UNSIGNED NOT NULL,
  `rate` double DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `status` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT ' 1 = Approve , 2 = Reject , 3 = Pending',
  `narration` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labor_bill_rates`
--

CREATE TABLE `labor_bill_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labor_heads`
--

CREATE TABLE `labor_heads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous_balance` varchar(260) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `loan_categories_id` bigint(20) UNSIGNED NOT NULL,
  `bank_id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loan_amount` bigint(20) DEFAULT NULL,
  `loan_percentage_percent` bigint(20) DEFAULT NULL,
  `loan_percentage_tk` bigint(20) DEFAULT NULL,
  `processing_charge` bigint(20) DEFAULT NULL,
  `misc_charge` bigint(20) DEFAULT NULL,
  `apply_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` bigint(20) DEFAULT NULL,
  `total_installment_month` bigint(20) DEFAULT NULL,
  `monthly_installment` bigint(20) DEFAULT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `start_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '1 = active , 2 = in-active',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_categories`
--

CREATE TABLE `loan_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = active , 2 = in-active',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deletable` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '1=No, 2=Yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `menu_name`, `deletable`, `created_at`, `updated_at`) VALUES
(1, 'Backend', '1', '2021-03-26 23:49:24', '2021-03-26 23:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2022_03_03_075918_create_labor_heads_table', 1),
(24, '2022_08_11_054557_create_loan_categories_table', 9),
(29, '2022_08_11_112655_create_loans_table', 10),
(43, '2022_10_27_085855_create_categories_table', 18),
(46, '2021_03_28_032515_create_materials_table', 21),
(117, '2021_03_29_092922_create_products_table', 34),
(129, '2023_01_16_121601_create_tenants_table', 35),
(139, '2022_04_27_190602_create_mills_table', 40),
(155, '2023_01_18_081602_create_tenant_receive_products_table', 44),
(156, '2023_01_18_083122_create_tenant_receive_product_lists_table', 44),
(157, '2023_01_18_085255_create_tenant_return_products_table', 44),
(158, '2023_01_18_085314_create_tenant_return_product_lists_table', 44),
(159, '2023_01_18_085910_create_tenant_delivery_products_table', 44),
(160, '2023_01_18_085929_create_tenant_delivery_product_lists_table', 44),
(161, '2023_01_18_125919_create_tenant_warehouse_products_table', 44),
(169, '2022_12_28_061223_create_purchase_products_table', 45),
(170, '2022_12_28_061250_create_purchase_product_receives_table', 45),
(171, '2022_12_28_061313_create_purchase_product_returns_table', 45),
(172, '2023_01_23_114206_create_warehouse_product_table', 46),
(174, '2021_03_30_115349_create_sale_products_table', 47),
(175, '2022_09_08_094413_create_sale_product_deliveries_table', 47),
(176, '2022_09_12_105008_create_sale_product_returns_table', 47),
(177, '2023_01_12_065405_create_distributions_table', 48),
(178, '2023_01_12_065455_create_distribution_products_table', 48),
(179, '2022_09_13_094159_create_stock_transfers_table', 49),
(180, '2022_09_13_111301_create_stock_transfer_warehouse_products_table', 49),
(228, '2023_02_09_090406_create_tenant_productions_table', 52),
(229, '2023_02_09_090654_create_tenant_production_raw_products_table', 52),
(230, '2023_02_09_090728_create_tenant_production_expenses_table', 52),
(231, '2023_02_09_090756_create_tenant_production_products_table', 52),
(232, '2023_02_09_090831_create_tenant_production_deliveries_table', 52),
(236, '2023_02_09_090955_create_tenant_production_merge_products_table', 55),
(237, '2023_02_09_090918_create_tenant_production_delivery_products_table', 56),
(242, '2021_03_27_111946_create_transactions_table', 60),
(243, '2022_12_28_070541_create_parties_table', 61),
(244, '2022_12_14_070154_create_chart_of_heads_table', 62),
(245, '2022_11_03_102833_create_purchases_table', 63),
(246, '2021_03_30_115339_create_sales_table', 64),
(247, '2023_03_07_054045_add_column_chart_of_head_table', 65),
(248, '2023_03_07_085027_add_column_chart_of_head_table', 66),
(249, '2023_03_07_085957_add_column_chart_of_head_table', 67),
(250, '2023_01_18_132705_create_productions_table', 68),
(251, '2023_01_30_091304_create_production_expenses_table', 68),
(252, '2023_01_30_091324_create_production_products_table', 68),
(253, '2023_01_30_091423_create_production_sales_table', 68),
(254, '2023_01_30_091440_create_production_sale_products_table', 68),
(255, '2023_01_30_093222_create_production_raw_products_table', 68),
(256, '2023_03_09_084818_add_column_chart_of_head_table', 69),
(257, '2023_03_12_082501_create_labor_bill_rates_table', 70),
(259, '2023_03_12_084133_create_labor_bills_table', 71),
(260, '2021_03_30_090507_create_trucks_table', 72),
(262, '2021_03_30_120852_create_transport_details_table', 72),
(263, '2023_03_13_100331_add_column_chart_of_head_table', 73),
(264, '2021_03_30_105707_create_transports_table', 74),
(265, '2023_03_15_054624_add_column_truck_table', 75),
(266, '2023_03_15_055831_add_column_mill_table', 76),
(267, '2023_03_20_052432_create_opening_stocks_table', 77);

-- --------------------------------------------------------

--
-- Table structure for table `mills`
--

CREATE TABLE `mills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_price` double DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Active , 2 = InActive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mills`
--

INSERT INTO `mills` (`id`, `name`, `address`, `asset_price`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'সোহা অটো ইউনিট-1', 'তরিমপুর, পুলহাট, দিনাজপুর', 0, '1', 'Super Admin', '2023-03-19 03:02:13', '2023-03-19 03:02:13'),
(2, 'সোহা অটো ইউনিট-2', 'তরিমপুর, পুলহাট, দিনাজপুর', 0, '1', 'Super Admin', '2023-03-19 03:02:31', '2023-03-19 03:02:31'),
(3, 'ভাই ভাই অটো রাইস মিল', 'আউলিয়াপুর, পুলহাট, সদর, দিনাজপুর।', 0, '1', 'Super Admin', '2023-03-19 03:02:48', '2023-03-19 03:02:48');

-- --------------------------------------------------------

--
-- Table structure for table `mobile_banks`
--

CREATE TABLE `mobile_banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=divider,2=module',
  `module_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `divider_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `target` enum('_self','_blank') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `menu_id`, `type`, `module_name`, `divider_title`, `icon_class`, `url`, `order`, `parent_id`, `target`, `created_at`, `updated_at`) VALUES
(1, 1, '2', 'Dashboard', NULL, 'fas fa-tachometer-alt', '/', 1, NULL, NULL, NULL, '2023-02-26 03:51:21'),
(2, 1, '1', NULL, 'Menus', NULL, NULL, 2, NULL, NULL, NULL, '2023-02-16 02:41:39'),
(3, 1, '1', NULL, 'Access Control', NULL, NULL, 21, NULL, NULL, NULL, '2023-03-06 23:15:17'),
(4, 1, '2', 'User', NULL, 'fas fa-users', 'user', 22, NULL, NULL, NULL, '2023-03-06 23:15:17'),
(5, 1, '2', 'Role', NULL, 'fas fa-user-tag', 'role', 23, NULL, NULL, NULL, '2023-03-06 23:15:17'),
(6, 1, '1', NULL, 'System', NULL, NULL, 24, NULL, NULL, NULL, '2023-03-06 23:15:17'),
(7, 1, '2', 'Setting', NULL, 'fas fa-cogs', NULL, 25, NULL, NULL, NULL, '2023-03-06 23:15:17'),
(8, 1, '2', 'Menu', NULL, 'fas fa-th-list', 'menu', 26, NULL, NULL, NULL, '2023-03-06 23:15:17'),
(9, 1, '2', 'Permission', NULL, 'fas fa-tasks', 'menu/module/permission', 27, NULL, NULL, NULL, '2023-03-06 23:15:17'),
(10, 1, '2', 'General Setting', NULL, NULL, 'setting', 1, 7, NULL, '2021-03-27 00:01:48', '2021-03-27 00:06:29'),
(11, 1, '2', 'Company', NULL, NULL, 'warehouse', 2, 7, NULL, '2021-03-27 00:07:10', '2022-12-26 03:29:46'),
(13, 1, '2', 'Unit', NULL, NULL, 'unit', 3, 7, NULL, '2021-03-27 00:07:54', '2023-03-14 21:23:30'),
(16, 1, '2', 'Purchase', NULL, 'fas fa-cart-arrow-down', NULL, 5, NULL, NULL, '2021-03-27 00:15:31', '2023-02-26 03:51:21'),
(17, 1, '2', 'Add Purchase', NULL, NULL, 'purchase/add', 1, 16, NULL, '2021-03-27 00:16:04', '2021-04-24 09:15:53'),
(18, 1, '2', 'Manage Purchase', NULL, NULL, 'purchase', 2, 16, NULL, '2021-03-27 00:16:22', '2021-03-27 00:16:27'),
(19, 1, '2', 'Product', NULL, 'fab fa-product-hunt', 'product', 4, NULL, NULL, '2021-03-27 00:23:19', '2023-02-26 03:51:21'),
(22, 1, '2', 'Category', NULL, 'fas fa-toolbox', 'category', 3, NULL, NULL, '2021-03-27 00:24:12', '2023-02-26 03:51:21'),
(23, 1, '2', 'Production', NULL, 'fas fa-industry', NULL, 7, NULL, NULL, '2021-03-27 00:26:25', '2023-02-26 03:51:21'),
(28, 1, '2', 'Product Stock', NULL, NULL, 'product-stock', 4, 200, NULL, '2021-03-27 00:37:24', '2023-03-19 23:44:54'),
(29, 1, '2', 'Sale', NULL, 'fab fa-opencart', NULL, 6, NULL, NULL, '2021-03-27 00:43:50', '2023-02-26 03:51:21'),
(30, 1, '2', 'Add Sale', NULL, NULL, 'sale/add', 1, 29, NULL, '2021-03-27 00:44:02', '2021-03-27 00:44:18'),
(31, 1, '2', 'Manage Sale', NULL, NULL, 'sale', 2, 29, NULL, '2021-03-27 00:44:15', '2021-03-27 00:44:24'),
(42, 1, '2', 'Accounts', NULL, 'far fa-money-bill-alt', NULL, 13, NULL, NULL, '2021-03-27 00:52:27', '2023-03-06 23:15:17'),
(45, 1, '2', 'Payment', NULL, NULL, 'supplier-payment', 1, 237, NULL, '2021-03-27 00:53:43', '2022-12-13 07:16:38'),
(46, 1, '2', 'Collection', NULL, NULL, 'customer-receive', 2, 237, NULL, '2021-03-27 00:54:01', '2022-12-13 07:16:54'),
(62, 1, '2', 'Bank', NULL, 'fas fa-university', NULL, 4, 42, NULL, '2021-03-27 01:04:11', '2023-03-06 23:15:17'),
(63, 1, '2', 'Manage Bank', NULL, NULL, 'bank', 1, 62, NULL, '2021-03-27 01:04:36', '2021-03-27 01:05:19'),
(66, 1, '2', 'Mobile Bank', NULL, 'fas fa-mobile-alt', NULL, 5, 42, NULL, '2021-03-27 01:05:51', '2023-03-06 23:15:17'),
(67, 1, '2', 'Manage Mobile Bank', NULL, NULL, 'mobile-bank', 1, 66, NULL, '2021-03-27 01:06:09', '2021-03-27 01:07:00'),
(70, 1, '2', 'Report', NULL, 'fas fa-file-signature', NULL, 15, NULL, NULL, '2021-03-27 01:08:11', '2023-03-06 23:15:17'),
(78, 1, '2', 'Distribution', NULL, 'fas fa-tools', NULL, 11, NULL, NULL, '2021-03-27 01:16:19', '2023-03-06 23:15:17'),
(89, 1, '2', 'Manage Labor Bill', NULL, NULL, 'labor-bill', 3, 171, NULL, '2021-03-27 03:08:31', '2023-03-12 00:59:21'),
(90, 1, '2', 'Manage Labor Bill Rate', NULL, NULL, 'labor-bill-rate', 2, 171, NULL, '2021-03-27 03:45:27', '2023-03-12 00:59:09'),
(98, 1, '2', 'Expense Item', NULL, 'fas fa-money-check-alt', NULL, 10, NULL, NULL, '2021-04-03 06:03:39', '2023-03-06 23:15:28'),
(99, 1, '2', 'Manage Expense Item', NULL, NULL, 'expense-item', 1, 98, NULL, '2021-04-03 06:04:22', '2021-04-03 06:04:26'),
(100, 1, '2', 'Expense', NULL, NULL, 'expense', 3, 237, NULL, '2021-04-03 06:04:44', '2023-03-06 22:55:37'),
(134, 1, '2', 'HRM', NULL, 'fas fa-users', NULL, 14, NULL, NULL, '2021-04-03 16:54:02', '2023-03-06 23:15:17'),
(161, 1, '2', 'Loan', NULL, 'far fa-money-bill-alt', NULL, 3, 42, NULL, '2021-07-24 06:00:12', '2023-02-28 04:20:48'),
(171, 1, '2', 'Labor', NULL, 'fas fa-user', NULL, 20, NULL, NULL, '2022-03-03 00:46:27', '2023-03-06 23:15:17'),
(172, 1, '2', 'Manage Labor', NULL, NULL, 'labor-head', 1, 171, '_self', '2022-03-03 00:47:47', '2022-08-09 23:39:32'),
(191, 1, '2', 'Voucher', NULL, NULL, 'voucher', 4, 237, '_self', '2022-04-12 01:12:14', '2023-03-06 22:55:29'),
(193, 1, '2', 'Transport', NULL, 'fas fa-truck-moving', NULL, 9, NULL, NULL, '2022-04-12 02:49:53', '2023-02-26 03:51:21'),
(194, 1, '2', 'Truck', NULL, NULL, 'truck', 1, 193, '_self', '2022-04-12 02:51:08', '2022-04-12 02:51:17'),
(198, 1, '2', 'Manage Transport', NULL, NULL, 'transport', 2, 193, '_self', '2022-04-12 02:53:56', '2023-03-14 21:23:30'),
(200, 1, '2', 'Stock', NULL, 'fas fa-boxes', NULL, 8, NULL, '_self', '2022-05-16 22:49:38', '2023-02-26 03:51:21'),
(202, 1, '2', 'Manage Loan Categories', NULL, NULL, 'loan-categories', 1, 161, '_self', '2022-08-10 23:57:16', '2022-08-10 23:58:01'),
(203, 1, '2', 'Manage Loan', NULL, NULL, 'loan', 2, 161, '_self', '2022-08-11 05:22:27', '2022-08-11 05:22:45'),
(206, 1, '2', 'Manage Loan Installment', NULL, NULL, 'loan-installment', 3, 161, '_self', '2022-08-25 03:18:16', '2022-08-25 03:18:30'),
(207, 1, '2', 'Loan Ledger', NULL, NULL, 'loan-ledger', 4, 161, '_self', '2022-08-25 05:19:08', '2022-08-25 05:19:24'),
(208, 1, '1', NULL, 'Profile', NULL, NULL, 16, NULL, NULL, '2022-09-04 23:23:50', '2023-03-06 23:15:17'),
(209, 1, '1', NULL, 'Accounts & HRM & Report', NULL, NULL, 12, NULL, NULL, '2022-09-04 23:32:11', '2023-03-06 23:15:17'),
(210, 1, '2', 'Stock Transfer', NULL, NULL, 'stock-transfer', 2, 200, '_self', '2022-09-13 03:37:27', '2023-03-19 23:44:54'),
(229, 1, '2', 'Product Alert', NULL, NULL, 'product-alert', 3, 200, '_self', '2022-11-03 06:14:58', '2023-03-19 23:44:54'),
(231, 1, '2', 'Chart Of Head', NULL, NULL, NULL, 1, 42, '_self', '2022-12-13 03:25:41', '2022-12-14 03:00:14'),
(233, 1, '2', 'Manage Head', NULL, NULL, 'head', 1, 231, '_self', '2022-12-13 03:27:11', '2022-12-21 02:48:54'),
(234, 1, '2', 'Manage Sub Head', NULL, NULL, 'sub-head', 2, 231, '_self', '2022-12-13 03:28:09', '2022-12-21 02:48:54'),
(235, 1, '2', 'Manage Child Head', NULL, NULL, 'child-head', 3, 231, '_self', '2022-12-13 03:28:38', '2022-12-21 02:48:54'),
(236, 1, '2', 'Chart', NULL, NULL, 'chart-of-head', 4, 231, '_self', '2022-12-13 05:45:45', '2022-12-21 02:48:54'),
(237, 1, '2', 'Transaction', NULL, NULL, NULL, 2, 42, '_self', '2022-12-13 07:16:03', '2022-12-14 03:00:14'),
(238, 1, '2', 'Tenant', NULL, 'fas fa-user-check', NULL, 19, NULL, NULL, '2022-12-26 03:56:37', '2023-03-06 23:15:17'),
(239, 1, '2', 'Party', NULL, 'fas fa-user-tie', NULL, 18, NULL, NULL, '2022-12-28 00:49:30', '2023-03-06 23:15:17'),
(240, 1, '2', 'Manage Party', NULL, NULL, 'party', 1, 239, '_self', '2022-12-28 00:49:49', '2022-12-28 00:49:52'),
(241, 1, '2', 'Party Ledger', NULL, NULL, 'party-ledger', 2, 239, '_self', '2022-12-28 00:50:17', '2022-12-28 00:50:23'),
(242, 1, '2', 'Manage Distribution', NULL, NULL, 'distribution', 1, 78, '_self', '2023-01-05 07:06:54', '2023-01-12 00:46:18'),
(243, 1, '2', 'Manage Tenant', NULL, NULL, 'tenant', 1, 238, '_self', '2023-01-15 23:36:26', '2023-01-16 00:20:25'),
(244, 1, '2', 'Tenant Receive', NULL, NULL, 'tenant-receive-product', 2, 238, '_self', '2023-01-16 00:17:06', '2023-01-18 04:08:46'),
(245, 1, '2', 'Tenant Return', NULL, NULL, 'tenant-return-product', 3, 238, '_self', '2023-01-16 00:18:34', '2023-01-18 04:08:37'),
(246, 1, '2', 'Tenant Delivery', NULL, NULL, 'tenant-delivery-product', 5, 238, '_self', '2023-01-16 00:19:22', '2023-01-18 04:08:29'),
(247, 1, '2', 'Tenant Stock', NULL, NULL, 'tenant-stock', 6, 238, '_self', '2023-01-16 00:41:27', '2023-01-17 23:11:23'),
(248, 1, '2', 'Tenant Production', NULL, NULL, 'tenant-production', 4, 238, '_self', '2023-01-16 00:42:35', '2023-01-16 00:44:13'),
(249, 1, '2', 'Manage Production', NULL, NULL, 'production', 1, 23, '_self', '2023-01-16 03:00:26', '2023-01-16 03:00:37'),
(250, 1, '2', 'Mill', NULL, 'fas fa-clipboard', NULL, 17, NULL, NULL, '2023-01-18 07:35:48', '2023-03-06 23:15:17'),
(251, 1, '2', 'Manage Mill', NULL, NULL, 'mill', 1, 250, '_self', '2023-01-18 07:36:37', '2023-01-18 07:36:40'),
(252, 1, '2', 'Tenant Quotation', NULL, NULL, 'tenant-quotation', 7, 238, '_self', '2023-02-11 23:20:19', '2023-02-11 23:23:07'),
(253, 1, '2', 'Ledger', NULL, NULL, 'ledger', 6, 42, '_self', '2023-03-06 06:08:23', '2023-03-06 23:15:17'),
(254, 1, '2', 'Balance Sheet', NULL, NULL, 'balance-sheet', 1, 70, '_self', '2023-03-14 21:23:14', '2023-03-14 21:23:30'),
(255, 1, '2', 'Income Statement', NULL, NULL, 'income-statement', 2, 70, '_self', '2023-03-14 21:23:50', '2023-03-14 21:24:06'),
(256, 1, '2', 'Product Ledger', NULL, NULL, 'product-ledger', 1, 200, '_self', '2023-03-19 23:44:47', '2023-03-19 23:44:54');

-- --------------------------------------------------------

--
-- Table structure for table `module_role`
--

CREATE TABLE `module_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `module_role`
--

INSERT INTO `module_role` (`id`, `module_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(2, 2, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(4, 16, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(5, 17, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(6, 18, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(9, 22, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(12, 23, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(16, 28, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(17, 29, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(18, 30, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(19, 31, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(26, 38, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(27, 39, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(28, 40, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(29, 41, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(61, 98, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(62, 99, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(63, 100, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(64, 101, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(65, 62, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(66, 63, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(67, 64, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(68, 65, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(69, 66, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(70, 67, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(71, 68, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(72, 69, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(73, 70, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(74, 71, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(75, 88, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(76, 92, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(77, 93, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(78, 72, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(79, 94, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(80, 73, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(81, 74, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(82, 75, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(83, 76, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(84, 95, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(85, 96, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(86, 97, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(87, 77, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(88, 3, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(89, 4, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(90, 5, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(91, 6, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(92, 7, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(93, 10, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(94, 11, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(95, 12, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(96, 89, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(97, 90, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(98, 13, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(104, 123, 2, '2021-04-08 18:35:36', '2021-04-08 18:35:36'),
(120, 132, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(125, 134, 2, '2021-05-04 13:08:17', '2021-05-04 13:08:17'),
(126, 135, 2, '2021-05-04 13:08:17', '2021-05-04 13:08:17'),
(127, 136, 2, '2021-05-04 13:08:17', '2021-05-04 13:08:17'),
(128, 137, 2, '2021-05-04 13:08:17', '2021-05-04 13:08:17'),
(129, 138, 2, '2021-05-04 13:08:17', '2021-05-04 13:08:17'),
(130, 139, 2, '2021-05-04 13:08:17', '2021-05-04 13:08:17'),
(131, 140, 2, '2021-05-04 13:08:17', '2021-05-04 13:08:17'),
(132, 155, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(133, 141, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(134, 142, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(135, 143, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(136, 150, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(137, 151, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(138, 152, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(139, 153, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(140, 154, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(141, 144, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(142, 145, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(143, 146, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(144, 147, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(145, 148, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(288, 8, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(289, 9, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(292, 200, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(293, 42, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(294, 45, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(295, 46, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(296, 191, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(297, 52, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(298, 201, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(299, 43, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(300, 44, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(301, 188, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(302, 161, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(303, 202, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(304, 203, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(305, 206, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(306, 207, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(307, 53, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(308, 54, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(309, 55, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(310, 56, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(311, 57, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(312, 58, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(313, 59, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(314, 60, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(315, 61, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(316, 133, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `opening_stocks`
--

CREATE TABLE `opening_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `scale` double NOT NULL,
  `qty` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE `parties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `previous_balance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance_type` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '3' COMMENT '1 = Receivable , 2 = Payable , 3 = No Balance',
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`id`, `name`, `company_name`, `mobile`, `address`, `previous_balance`, `balance_type`, `status`, `created_by`, `modified_by`, `created_at`, `updated_at`) VALUES
(1, 'রায়হান ট্রেডার্স', NULL, '01764880209', 'জংলীপীর, বোচাগঞ্জ', '2111821', '2', '1', 'Super Admin', NULL, '2023-03-19 04:20:10', '2023-03-19 04:20:10'),
(2, 'সায়মা ট্রেডার্স', NULL, '01718157846', 'কাহারোল, দিনাজপুর', '1644765', '2', '1', 'Super Admin', NULL, '2023-03-19 04:21:05', '2023-03-19 04:21:05'),
(3, 'পিংকি ট্রেডার্স', NULL, '01713709051', 'কবিরাজহাট, দিনাজপুর', '3362757', '2', '1', 'Super Admin', NULL, '2023-03-19 04:21:48', '2023-03-19 04:21:48'),
(4, 'রানা ট্রেডর্স', NULL, '01738344933', 'কামদেবপুর, বিরল', '1164182', '2', '1', 'Super Admin', NULL, '2023-03-19 04:23:00', '2023-03-19 04:23:00'),
(5, 'লাবন্য ট্রেডার্স', NULL, '01734868717', 'সুখদবপুর, বোচাগঞ্জ', '289714', '2', '1', 'Super Admin', NULL, '2023-03-19 04:23:54', '2023-03-19 04:23:54'),
(6, 'করিম কাদের ট্রেডার্স', NULL, '017355662189', 'কাঠালডাঙ্গি, ঠাকুরগাও', '1439257', '2', '1', 'Super Admin', NULL, '2023-03-19 04:25:01', '2023-03-19 04:25:01'),
(7, 'নূরী ট্রেডার্স', NULL, '01736063824', 'বিরগঞ্জ, দিনাজপুর', '1329294', '2', '1', 'Super Admin', NULL, '2023-03-19 04:25:55', '2023-03-19 04:25:55'),
(8, 'সাঈদ ট্রেডার্স', NULL, '01712645165', 'আমবাড়ী, চিরিরবন্দর', '105305', '1', '1', 'Super Admin', NULL, '2023-03-19 04:26:44', '2023-03-19 04:26:44'),
(9, 'মোশারফ ট্রেডার্স', NULL, '01712509973', 'বিন্নাকুড়ী, চিরিরবন্দর', '2437816', '2', '1', 'Super Admin', NULL, '2023-03-19 04:27:33', '2023-03-19 04:27:33'),
(10, 'লাকী এন্টারপ্রাইজ', NULL, '01797731772', 'রুইয়া, ঠাকুরগাও', '61944', '2', '1', 'Super Admin', NULL, '2023-03-19 04:30:09', '2023-03-19 04:30:09'),
(11, 'জাকির ট্রেডার্স', NULL, '01722648920', 'ডিমলা, নীলফামারী', '2221887', '2', '1', 'Super Admin', NULL, '2023-03-19 04:33:26', '2023-03-19 04:33:26'),
(12, 'সিয়াম ট্রেডার্স', NULL, '017', 'নেকমরদ, ঠাকুরগাও', '839111', '2', '1', 'Super Admin', NULL, '2023-03-19 04:35:01', '2023-03-19 04:35:01'),
(13, 'নুরজাহান ট্রেডার্স', NULL, '01', 'বৈরচুনা, সেতাবগঞ্জ', '1659818', '2', '1', 'Super Admin', NULL, '2023-03-19 04:36:42', '2023-03-19 04:36:42'),
(14, 'সোহা ফুড ইন্ডাষ্ট্রিজ', NULL, '01896140840', 'তরিমপুর (লালদিঘি), পুলহাট, সদর, দিনাজপুর', '11568622', '1', '1', 'Super Admin', NULL, '2023-03-19 05:01:16', '2023-03-19 05:01:16'),
(15, 'ভাই ভাই অটো রাইস মিল', NULL, '01896140825', 'আউলিয়াপুর, সদর, দিনাজপুর', '95101082', '1', '1', 'Super Admin', NULL, '2023-03-19 05:02:16', '2023-03-19 05:02:16'),
(16, 'দিনাজপুর ইন্ডাষ্ট্রিজ', NULL, '01716300600', 'মাঝিপাড়া, দিনাজপুর', '174708518', '1', '1', 'Super Admin', NULL, '2023-03-19 05:03:16', '2023-03-19 05:03:16'),
(17, 'আল-তুবা গ্লোবাল লিমিটেড', NULL, '01888386012', 'উত্তরা, ঢাকা', '2850900', '1', '1', 'Super Admin', NULL, '2023-03-19 05:04:42', '2023-03-19 05:04:42'),
(18, 'সোহা পরিবহন আয় ব্যায়', NULL, '01896140810', NULL, '8972456', '1', '1', 'Super Admin', NULL, '2023-03-19 05:05:52', '2023-03-19 05:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `module_id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dashboard Access', 'dashboard-access', '2021-03-26 23:49:24', NULL),
(2, 4, 'User Access', 'user-access', '2021-03-26 23:49:24', NULL),
(3, 4, 'User Add', 'user-add', '2021-03-26 23:49:24', NULL),
(4, 4, 'User Edit', 'user-edit', '2021-03-26 23:49:24', NULL),
(5, 4, 'User View', 'user-view', '2021-03-26 23:49:24', NULL),
(6, 4, 'User Delete', 'user-delete', '2021-03-26 23:49:24', NULL),
(7, 4, 'User Bulk Delete', 'user-bulk-delete', '2021-03-26 23:49:24', NULL),
(8, 4, 'User Report', 'user-report', '2021-03-26 23:49:24', NULL),
(9, 5, 'Role Access', 'role-access', '2021-03-26 23:49:24', NULL),
(10, 5, 'Role Add', 'role-add', '2021-03-26 23:49:24', NULL),
(11, 5, 'Role Edit', 'role-edit', '2021-03-26 23:49:24', NULL),
(12, 5, 'Role View', 'role-view', '2021-03-26 23:49:24', NULL),
(13, 5, 'Role Delete', 'role-delete', '2021-03-26 23:49:24', NULL),
(14, 5, 'Role Bulk Delete', 'role-bulk-delete', '2021-03-26 23:49:24', NULL),
(15, 5, 'Role Report', 'role-report', '2021-03-26 23:49:24', NULL),
(17, 8, 'Menu Access', 'menu-access', '2021-03-26 23:49:24', NULL),
(18, 8, 'Menu Add', 'menu-add', '2021-03-26 23:49:24', NULL),
(19, 8, 'Menu Edit', 'menu-edit', '2021-03-26 23:49:24', NULL),
(20, 8, 'Menu Delete', 'menu-delete', '2021-03-26 23:49:24', NULL),
(21, 8, 'Menu Bulk Delete', 'menu-bulk-delete', '2021-03-26 23:49:24', NULL),
(22, 8, 'Menu Report', 'menu-report', '2021-03-26 23:49:24', NULL),
(23, 8, 'Menu Builder Access', 'menu-builder-access', '2021-03-26 23:49:24', NULL),
(24, 8, 'Menu Module Add', 'menu-module-add', '2021-03-26 23:49:24', NULL),
(25, 8, 'Menu Module Edit', 'menu-module-edit', '2021-03-26 23:49:24', NULL),
(26, 8, 'Menu Module Delete', 'menu-module-delete', '2021-03-26 23:49:24', NULL),
(27, 9, 'Permission Access', 'permission-access', '2021-03-26 23:49:24', NULL),
(28, 9, 'Permission Add', 'permission-add', '2021-03-26 23:49:24', NULL),
(29, 9, 'Permission Edit', 'permission-edit', '2021-03-26 23:49:24', NULL),
(30, 9, 'Permission Delete', 'permission-delete', '2021-03-26 23:49:24', NULL),
(31, 9, 'Permission Bulk Delete', 'permission-bulk-delete', '2021-03-26 23:49:24', NULL),
(32, 9, 'Permission Report', 'permission-report', '2021-03-26 23:49:24', NULL),
(33, 11, 'Warehouse Access', 'warehouse-access', '2021-03-27 02:06:16', NULL),
(34, 11, 'Warehouse Add', 'warehouse-add', '2021-03-27 02:06:16', NULL),
(35, 11, 'Warehouse Edit', 'warehouse-edit', '2021-03-27 02:06:16', NULL),
(36, 11, 'Warehouse Delete', 'warehouse-delete', '2021-03-27 02:06:16', NULL),
(37, 11, 'Warehouse Bulk Delete', 'warehouse-bulk-delete', '2021-03-27 02:06:16', NULL),
(38, 11, 'Warehouse Report', 'warehouse-report', '2021-03-27 02:06:16', NULL),
(39, 10, 'General Setting Access', 'general-setting-access', '2021-03-27 02:07:15', NULL),
(46, 13, 'Unit Access', 'unit-access', '2021-03-27 02:10:02', NULL),
(47, 13, 'Unit Add', 'unit-add', '2021-03-27 02:10:02', NULL),
(48, 13, 'Unit Edit', 'unit-edit', '2021-03-27 02:10:02', NULL),
(49, 13, 'Unit Delete', 'unit-delete', '2021-03-27 02:10:02', NULL),
(50, 13, 'Unit Bulk Delete', 'unit-bulk-delete', '2021-03-27 02:10:02', NULL),
(51, 13, 'Unit Report', 'unit-report', '2021-03-27 02:10:02', NULL),
(65, 18, 'Purchase Access', 'purchase-access', '2021-03-27 02:22:49', NULL),
(66, 18, 'Purchase Add', 'purchase-add', '2021-03-27 02:22:49', NULL),
(67, 18, 'Purchase Edit', 'purchase-edit', '2021-03-27 02:22:49', NULL),
(68, 18, 'Purchase View', 'purchase-view', '2021-03-27 02:22:49', NULL),
(69, 18, 'Purchase Delete', 'purchase-delete', '2021-03-27 02:22:49', NULL),
(70, 18, 'Purchase Bulk Delete', 'purchase-bulk-delete', '2021-03-27 02:22:49', NULL),
(71, 18, 'Purchase Report', 'purchase-report', '2021-03-27 02:22:49', NULL),
(88, 28, 'Finished Goods Stock Access', 'finished-goods-stock-access', '2021-03-27 02:28:06', NULL),
(89, 31, 'Sale Access', 'sale-access', '2021-03-27 02:30:26', NULL),
(90, 31, 'Sale Add', 'sale-add', '2021-03-27 02:30:26', NULL),
(91, 31, 'Sale Edit', 'sale-edit', '2021-03-27 02:30:26', NULL),
(92, 31, 'Sale View', 'sale-view', '2021-03-27 02:30:26', NULL),
(93, 31, 'Sale Delete', 'sale-delete', '2021-03-27 02:30:26', NULL),
(94, 31, 'Sale Bulk Delete', 'sale-bulk-delete', '2021-03-27 02:30:26', NULL),
(95, 31, 'Sale Report', 'sale-report', '2021-03-27 02:30:26', NULL),
(181, 63, 'Bank Access', 'bank-access', '2021-03-27 02:54:57', NULL),
(182, 63, 'Bank Add', 'bank-add', '2021-03-27 02:54:57', NULL),
(183, 63, 'Bank Edit', 'bank-edit', '2021-03-27 02:54:57', NULL),
(184, 63, 'Bank Delete', 'bank-delete', '2021-03-27 02:54:57', NULL),
(185, 63, 'Bank Report', 'bank-report', '2021-03-27 02:54:57', NULL),
(188, 67, 'Mobile Bank Access', 'mobile-bank-access', '2021-03-27 03:02:36', NULL),
(189, 67, 'Mobile Bank Add', 'mobile-bank-add', '2021-03-27 03:02:36', NULL),
(190, 67, 'Mobile Bank Edit', 'mobile-bank-edit', '2021-03-27 03:02:36', NULL),
(191, 67, 'Mobile Bank Delete', 'mobile-bank-delete', '2021-03-27 03:02:36', NULL),
(192, 67, 'Mobile Bank Bulk Delete', 'mobile-bank-bulk-delete', '2021-03-27 03:02:36', NULL),
(193, 67, 'Mobile Bank Report', 'mobile-bank-report', '2021-03-27 03:02:36', NULL),
(204, 89, 'Labor Bill Access', 'labor-bill-access', '2021-03-27 03:17:07', NULL),
(205, 89, 'Labor Bill Add', 'labor-bill-add', '2021-03-27 03:17:07', NULL),
(206, 89, 'Labor Bill Edit', 'labor-bill-edit', '2021-03-27 03:17:07', NULL),
(207, 89, 'Labor Bill Delete', 'labor-bill-delete', '2021-03-27 03:17:07', NULL),
(208, 89, 'Labor Bill Bulk Delete', 'labor-bill-bulk-delete', '2021-03-27 03:17:07', NULL),
(209, 89, 'Labor Bill Report', 'labor-bill-report', '2021-03-27 03:17:07', NULL),
(210, 90, 'Labor Bill Rate Access', 'labor-bill-rate-access', '2021-03-27 03:46:35', NULL),
(211, 90, 'Labor Bill Rate Add', 'labor-bill-rate-add', '2021-03-27 03:46:35', NULL),
(212, 90, 'Labor Bill Rate Edit', 'labor-bill-rate-edit', '2021-03-27 03:46:35', NULL),
(213, 90, 'Labor Bill Rate Delete', 'labor-bill-rate-delete', '2021-03-27 03:46:35', NULL),
(214, 90, 'Labor Bill Rate Bulk Delete', 'labor-bill-rate-bulk-delete', '2021-03-27 03:46:35', NULL),
(215, 90, 'Labor Bill Rate Report', 'labor-bill-rate-report', '2021-03-27 03:46:35', NULL),
(216, 18, 'Purchase Payment Add', 'purchase-payment-add', '2021-03-28 00:28:57', NULL),
(217, 18, 'Purchase Payment Edit', 'purchase-payment-edit', '2021-03-28 00:28:57', NULL),
(218, 18, 'Purchase Payment View', 'purchase-payment-view', '2021-03-28 00:28:57', NULL),
(219, 18, 'Purchase Payment Delete', 'purchase-payment-delete', '2021-03-28 00:28:57', NULL),
(220, 18, 'Purchase Approve', 'purchase-approve', '2021-03-28 08:24:45', NULL),
(222, 31, 'Sale Approve', 'sale-approve', '2021-03-28 08:25:17', NULL),
(232, 22, 'Category Access', 'category-access', '2021-03-29 03:03:24', NULL),
(233, 22, 'Category Add', 'category-add', '2021-03-29 03:03:24', NULL),
(234, 22, 'Category Edit', 'category-edit', '2021-03-29 03:03:24', NULL),
(235, 22, 'Category Delete', 'category-delete', '2021-03-29 03:03:24', NULL),
(236, 22, 'Category Bulk Delete', 'category-bulk-delete', '2021-03-29 03:03:24', NULL),
(237, 22, 'Category Report', 'category-report', '2021-03-29 03:03:24', NULL),
(252, 99, 'Expense Item Access', 'expense-item-access', '2021-04-03 06:07:26', NULL),
(253, 99, 'Expense Item Add', 'expense-item-add', '2021-04-03 06:07:26', NULL),
(254, 99, 'Expense Item Edit', 'expense-item-edit', '2021-04-03 06:07:26', NULL),
(255, 99, 'Expense Item Delete', 'expense-item-delete', '2021-04-03 06:07:26', NULL),
(256, 99, 'Expense Item Bulk Delete', 'expense-item-bulk-delete', '2021-04-03 06:07:26', NULL),
(257, 99, 'Expense Item Report', 'expense-item-report', '2021-04-03 06:07:26', NULL),
(258, 100, 'Expense Access', 'expense-access', '2021-04-03 06:08:10', NULL),
(259, 100, 'Expense Add', 'expense-add', '2021-04-03 06:08:10', NULL),
(260, 100, 'Expense Edit', 'expense-edit', '2021-04-03 06:08:10', NULL),
(261, 100, 'Expense Delete', 'expense-delete', '2021-04-03 06:08:10', NULL),
(262, 100, 'Expense Bulk Delete', 'expense-bulk-delete', '2021-04-03 06:08:10', NULL),
(263, 100, 'Expense Report', 'expense-report', '2021-04-03 06:08:10', NULL),
(379, 100, 'Expense Approve', 'expense-approve', '2021-04-22 11:11:09', NULL),
(524, 172, 'Labor Head Access', 'labor-head-access', '2022-03-03 00:50:47', NULL),
(525, 172, 'Labor Head Add', 'labor-head-add', '2022-03-03 00:50:47', NULL),
(526, 172, 'Labor Head Edit', 'labor-head-edit', '2022-03-03 00:50:47', NULL),
(527, 172, 'Labor Head Delete', 'labor-head-delete', '2022-03-03 00:50:47', NULL),
(528, 172, 'Labor Head Bulk Delete', 'labor-head-bulk-delete', '2022-03-03 00:50:47', NULL),
(557, 18, 'Purchase Invoice', 'purchase-invoice', '2022-03-26 22:47:47', NULL),
(558, 18, 'Received Invoice', 'received-invoice', '2022-03-26 22:47:47', NULL),
(559, 18, 'Gate Pass', 'gate-pass', '2022-03-26 22:47:47', NULL),
(560, 18, 'Purchase Received', 'purchase-received', '2022-03-27 00:05:17', NULL),
(572, 45, 'Supplier Payment Access', 'supplier-payment-access', '2022-04-10 23:56:06', NULL),
(573, 45, 'Supplier Payment Add', 'supplier-payment-add', '2022-04-10 23:56:06', NULL),
(574, 45, 'Supplier Payment Edit', 'supplier-payment-edit', '2022-04-10 23:56:06', NULL),
(575, 45, 'Supplier Payment View', 'supplier-payment-view', '2022-04-10 23:56:06', NULL),
(576, 45, 'Supplier Payment Delete', 'supplier-payment-delete', '2022-04-10 23:56:06', NULL),
(577, 46, 'Customer Receive Access', 'customer-receive-access', '2022-04-10 23:56:44', NULL),
(578, 46, 'Customer Receive Add', 'customer-receive-add', '2022-04-10 23:56:44', NULL),
(579, 46, 'Customer Receive Edit', 'customer-receive-edit', '2022-04-10 23:56:44', NULL),
(580, 46, 'Customer Receive View', 'customer-receive-view', '2022-04-10 23:56:44', NULL),
(581, 46, 'Customer Receive Delete', 'customer-receive-delete', '2022-04-10 23:56:44', NULL),
(582, 46, 'Customer Receive Details', 'customer-receive-details', '2022-04-11 01:38:16', NULL),
(583, 45, 'Supplier Payment Details', 'supplier-payment-details', '2022-04-11 01:57:11', NULL),
(601, 191, 'Voucher Access', 'voucher-access', '2022-04-12 02:32:26', '2022-08-21 22:07:21'),
(602, 191, 'Voucher Add', 'voucher-add', '2022-04-12 02:32:26', '2022-08-21 22:07:10'),
(603, 191, 'Voucher View', 'voucher-view', '2022-04-12 02:32:26', '2022-08-21 22:06:59'),
(604, 191, 'Voucher Delete', 'voucher-delete', '2022-04-12 02:32:26', '2022-08-21 22:06:45'),
(605, 194, 'Truck Report', 'truck-report', '2022-04-12 02:58:37', NULL),
(606, 194, 'Truck Bulk Delete', 'truck-bulk-delete', '2022-04-12 02:58:37', NULL),
(607, 194, 'Truck Delete', 'truck-delete', '2022-04-12 02:58:37', NULL),
(608, 194, 'Truck Edit', 'truck-edit', '2022-04-12 02:58:37', NULL),
(609, 194, 'Truck Add', 'truck-add', '2022-04-12 02:58:37', NULL),
(610, 194, 'Truck Access', 'truck-access', '2022-04-12 02:58:37', NULL),
(623, 198, 'Transport Change Status', 'transport-change-status', '2022-04-12 03:04:43', '2023-03-13 23:34:58'),
(624, 198, 'Transport Report', 'transport-report', '2022-04-12 03:04:43', NULL),
(625, 198, 'Transport Bulk Delete', 'transport-bulk-delete', '2022-04-12 03:04:43', NULL),
(626, 198, 'Transport Delete', 'transport-delete', '2022-04-12 03:04:43', NULL),
(627, 198, 'Transport View', 'transport-view', '2022-04-12 03:04:43', NULL),
(628, 198, 'Transport Edit', 'transport-edit', '2022-04-12 03:04:43', NULL),
(629, 198, 'Transport Add', 'transport-add', '2022-04-12 03:04:43', NULL),
(630, 198, 'Transport Access', 'transport-access', '2022-04-12 03:04:43', NULL),
(632, 202, 'Loan Categories Access', 'loan-categories-access', '2022-08-11 03:15:34', NULL),
(633, 202, 'Loan Categories Store Update', 'loan-categories-store-update', '2022-08-11 03:15:34', NULL),
(634, 202, 'Loan Categories Edit', 'loan-categories-edit', '2022-08-11 03:15:34', NULL),
(635, 202, 'Loan Categories Delete', 'loan-categories-delete', '2022-08-11 03:15:34', NULL),
(636, 203, 'Loan Access', 'loan-access', '2022-08-14 01:43:45', NULL),
(637, 203, 'Loan Add', 'loan-add', '2022-08-14 01:43:45', NULL),
(638, 203, 'Loan Edit', 'loan-edit', '2022-08-14 01:43:45', NULL),
(639, 203, 'Loan Delete', 'loan-delete', '2022-08-14 01:43:45', NULL),
(640, 203, 'Loan View', 'loan-view', '2022-08-14 01:43:45', NULL),
(641, 203, 'Loan Status Change', 'loan-status-change', '2022-08-14 01:43:45', NULL),
(644, 207, 'Loan Ledger Access', 'loan-ledger-access', '2022-08-28 02:42:34', NULL),
(645, 206, 'Loan Installment Access', 'loan-installment-access', '2022-08-28 03:04:36', NULL),
(646, 206, 'Loan Installment Add', 'loan-installment-add', '2022-08-28 03:04:36', NULL),
(647, 206, 'Loan Installment Edit', 'loan-installment-edit', '2022-08-28 03:04:36', NULL),
(648, 206, 'Loan Installment Delete', 'loan-installment-delete', '2022-08-28 03:04:36', NULL),
(649, 206, 'Loan Installment Status Change', 'loan-installment-status-change', '2022-08-28 03:04:36', NULL),
(650, 31, 'Sale Delivery', 'sale-delivery', '2022-09-08 02:49:42', NULL),
(651, 31, 'Sale Delivery Invoice', 'sale-delivery-invoice', '2022-09-08 02:49:42', NULL),
(652, 31, 'Sale Return', 'sale-return', '2022-09-12 03:12:34', NULL),
(653, 31, 'Sale Return Invoice', 'sale-return-invoice', '2022-09-12 06:05:16', NULL),
(654, 210, 'Stock Transfer Access', 'stock-transfer-access', '2022-09-13 05:03:42', '2022-09-13 05:04:33'),
(655, 210, 'Stock Transfer Add', 'stock-transfer-add', '2022-09-13 05:03:42', NULL),
(656, 210, 'Stock Transfer Change Status', 'stock-transfer-change-status', '2022-09-13 05:03:42', NULL),
(657, 210, 'Stock Transfer Delete', 'stock-transfer-delete', '2022-09-13 05:03:42', NULL),
(667, 19, 'Product Access', 'product-access', '2022-10-27 00:19:08', NULL),
(668, 19, 'Product Add', 'product-add', '2022-10-27 00:19:08', NULL),
(669, 19, 'Product Edit', 'product-edit', '2022-10-27 00:19:08', NULL),
(670, 19, 'Product Status Change', 'product-status-change', '2022-10-27 00:19:08', NULL),
(671, 19, 'Product Delete', 'product-delete', '2022-10-27 00:19:08', NULL),
(672, 19, 'Product View', 'product-view', '2022-10-31 07:34:36', NULL),
(673, 210, 'Stock Transfer Edit', 'stock-transfer-edit', '2022-11-02 23:53:50', NULL),
(674, 210, 'Stock Transfer View', 'stock-transfer-view', '2022-11-02 23:53:50', NULL),
(682, 229, 'Product Alert Access', 'product-alert-access', '2022-11-07 04:42:12', NULL),
(685, 18, 'Purchase Status Change', 'purchase-status-change', '2022-11-14 02:13:08', NULL),
(686, 18, 'Purchase Receive', 'purchase-receive', '2022-11-14 04:13:44', NULL),
(687, 18, 'Purchase Receive Details', 'purchase-receive-details', '2022-11-15 00:08:26', NULL),
(688, 18, 'Purchase Return', 'purchase-return', '2022-11-15 00:48:18', NULL),
(689, 18, 'Purchase Return Details', 'purchase-return-details', '2022-11-15 00:48:18', NULL),
(691, 233, 'Head Access', 'head-access', '2022-12-15 03:45:53', NULL),
(692, 233, 'Head Add', 'head-add', '2022-12-15 03:45:53', NULL),
(693, 233, 'Head Edit', 'head-edit', '2022-12-15 03:45:53', NULL),
(694, 233, 'Head Delete', 'head-delete', '2022-12-15 03:45:53', NULL),
(695, 234, 'Sub Head Access', 'sub-head-access', '2022-12-15 03:46:33', NULL),
(696, 234, 'Sub Head Add', 'sub-head-add', '2022-12-15 03:46:33', NULL),
(697, 234, 'Sub Head Edit', 'sub-head-edit', '2022-12-15 03:46:33', NULL),
(698, 234, 'Sub Head Delete', 'sub-head-delete', '2022-12-15 03:46:33', NULL),
(699, 235, 'Child Head Access', 'child-head-access', '2022-12-15 03:47:13', NULL),
(700, 235, 'Child Head Add', 'child-head-add', '2022-12-15 03:47:13', NULL),
(701, 235, 'Child Head Edit', 'child-head-edit', '2022-12-15 03:47:13', NULL),
(702, 235, 'Child Head Delete', 'child-head-delete', '2022-12-15 03:47:13', NULL),
(703, 236, 'Chart Access', 'chart-access', '2022-12-15 03:47:31', NULL),
(704, 240, 'Party Access', 'party-access', '2022-12-28 01:17:37', NULL),
(705, 240, 'Party Add', 'party-add', '2022-12-28 01:17:37', NULL),
(706, 240, 'Party Edit', 'party-edit', '2022-12-28 01:17:37', NULL),
(707, 240, 'Party Delete', 'party-delete', '2022-12-28 01:17:37', NULL),
(708, 240, 'Party View', 'party-view', '2022-12-28 01:22:24', NULL),
(709, 242, 'Distribution Access', 'distribution-access', '2023-01-12 02:52:11', NULL),
(710, 242, 'Distribution Add', 'distribution-add', '2023-01-12 02:52:11', NULL),
(711, 242, 'Distribution Edit', 'distribution-edit', '2023-01-12 02:52:11', NULL),
(712, 242, 'Distribution Update', 'distribution-update', '2023-01-12 02:52:11', NULL),
(713, 242, 'Distribution Show', 'distribution-show', '2023-01-12 02:52:11', NULL),
(714, 242, 'Distribution Delete', 'distribution-delete', '2023-01-12 02:52:11', NULL),
(715, 242, 'Distribution Status Change', 'distribution-status-change', '2023-01-12 02:52:11', NULL),
(716, 242, 'Distribution View', 'distribution-view', '2023-01-15 10:38:44', NULL),
(717, 243, 'Tenant Access', 'tenant-access', '2023-01-16 06:44:35', NULL),
(718, 243, 'Tenant Add', 'tenant-add', '2023-01-16 06:44:35', NULL),
(719, 243, 'Tenant Edit', 'tenant-edit', '2023-01-16 06:44:35', NULL),
(720, 243, 'Tenant Delete', 'tenant-delete', '2023-01-16 06:44:35', NULL),
(721, 243, 'Tenant Change Status', 'tenant-change-status', '2023-01-16 06:44:35', NULL),
(722, 244, 'Tenant Receive Access', 'tenant-receive-access', '2023-01-18 04:19:48', NULL),
(723, 244, 'Tenant Receive Add', 'tenant-receive-add', '2023-01-18 04:19:48', NULL),
(724, 244, 'Tenant Receive Edit', 'tenant-receive-edit', '2023-01-18 04:19:48', NULL),
(725, 244, 'Tenant Receive Show', 'tenant-receive-show', '2023-01-18 04:19:48', NULL),
(726, 244, 'Tenant Receive Delete', 'tenant-receive-delete', '2023-01-18 04:19:48', NULL),
(727, 244, 'Tenant Receive Status Change', 'tenant-receive-status-change', '2023-01-18 04:19:48', NULL),
(728, 245, 'Tenant Return Access', 'tenant-return-access', '2023-01-18 04:21:03', NULL),
(729, 245, 'Tenant Return Add', 'tenant-return-add', '2023-01-18 04:21:03', NULL),
(730, 245, 'Tenant Return Edit', 'tenant-return-edit', '2023-01-18 04:21:03', NULL),
(731, 245, 'Tenant Return Show', 'tenant-return-show', '2023-01-18 04:21:03', NULL),
(732, 245, 'Tenant Return Delete', 'tenant-return-delete', '2023-01-18 04:21:03', NULL),
(733, 245, 'Tenant Return Status Change', 'tenant-return-status-change', '2023-01-18 04:21:03', NULL),
(734, 246, 'Tenant Delivery Access', 'tenant-delivery-access', '2023-01-18 04:22:35', NULL),
(735, 246, 'Tenant Delivery Add', 'tenant-delivery-add', '2023-01-18 04:22:35', NULL),
(736, 246, 'Tenant Delivery Edit', 'tenant-delivery-edit', '2023-01-18 04:22:35', NULL),
(737, 246, 'Tenant Delivery Show', 'tenant-delivery-show', '2023-01-18 04:22:35', NULL),
(738, 246, 'Tenant Delivery Delete', 'tenant-delivery-delete', '2023-01-18 04:22:35', NULL),
(739, 246, 'Tenant Delivery Status Change', 'tenant-delivery-status-change', '2023-01-18 04:22:35', NULL),
(740, 247, 'Tenant Stock Access', 'tenant-stock-access', '2023-01-18 04:23:08', NULL),
(741, 251, 'Mill Access', 'mill-access', '2023-01-21 10:15:48', NULL),
(742, 251, 'Mill Add', 'mill-add', '2023-01-21 10:15:48', NULL),
(743, 251, 'Mill Edit', 'mill-edit', '2023-01-21 10:15:48', NULL),
(744, 251, 'Mill Delete', 'mill-delete', '2023-01-21 10:15:48', NULL),
(745, 249, 'Production Add', 'production-add', '2023-01-30 05:18:03', NULL),
(746, 249, 'Production Access', 'production-access', '2023-01-30 05:18:03', NULL),
(747, 249, 'Production Edit', 'production-edit', '2023-01-30 05:18:03', NULL),
(748, 249, 'Production Status Change', 'production-status-change', '2023-01-30 05:18:03', NULL),
(749, 249, 'Production Delete', 'production-delete', '2023-01-30 05:18:03', NULL),
(750, 249, 'Production Show', 'production-show', '2023-01-30 05:18:03', '2023-01-30 23:52:14'),
(751, 249, 'Production Report', 'production-report', '2023-01-30 05:18:03', NULL),
(752, 249, 'Production Summary', 'production-summary', '2023-01-30 05:18:03', NULL),
(753, 249, 'Production Sale Add', 'production-sale-add', '2023-01-30 05:18:03', '2023-01-31 23:27:12'),
(754, 249, 'Production Sale Details', 'production-sale-details', '2023-01-30 05:18:03', NULL),
(756, 249, 'Production', 'production', '2023-01-31 04:38:02', NULL),
(758, 249, 'Production Product Add', 'production-product-add', '2023-01-31 23:31:33', '2023-01-31 23:57:34'),
(759, 249, 'Production Product Details', 'production-product-details', '2023-01-31 23:31:33', '2023-01-31 23:57:54'),
(760, 249, 'Production Complete', 'production-complete', '2023-02-05 05:04:54', NULL),
(761, 248, 'Tenant Production Access', 'tenant-production-access', '2023-02-12 00:37:21', NULL),
(762, 248, 'Tenant Production Add', 'tenant-production-add', '2023-02-12 00:37:21', NULL),
(763, 248, 'Tenant Production Show', 'tenant-production-show', '2023-02-12 00:37:21', NULL),
(764, 248, 'Tenant Production Edit', 'tenant-production-edit', '2023-02-12 00:37:21', NULL),
(765, 248, 'Tenant Production Update', 'tenant-production-update', '2023-02-12 00:37:21', NULL),
(766, 248, 'Tenant Production Status Change', 'tenant-production-status-change', '2023-02-12 00:37:21', '2023-02-16 05:26:36'),
(767, 248, 'Tenant Production Product', 'tenant-production-product', '2023-02-12 00:37:21', NULL),
(768, 248, 'Tenant Production Complete', 'tenant-production-complete', '2023-02-12 00:37:21', NULL),
(769, 248, 'Tenant Production Report Details', 'tenant-production-report-details', '2023-02-12 00:37:21', NULL),
(770, 248, 'Tenant Production Summary', 'tenant-production-summary', '2023-02-12 00:37:21', NULL),
(771, 248, 'Tenant Production Delete', 'tenant-production-delete', '2023-02-12 00:37:21', NULL),
(772, 248, 'Tenant Production Delivery Add', 'tenant-production-delivery-add', '2023-02-12 23:49:36', NULL),
(773, 248, 'Tenant Production Delivery Details', 'tenant-production-delivery-details', '2023-02-12 23:49:36', NULL),
(774, 248, 'Tenant Production Product Add', 'tenant-production-product-add', '2023-02-13 22:26:18', '2023-02-13 22:26:54'),
(775, 248, 'Tenant Production Product Details', 'tenant-production-product-details', '2023-02-13 22:26:18', '2023-02-13 22:27:09'),
(776, 191, 'Voucher Edit', 'voucher-edit', '2023-03-05 00:03:53', NULL),
(777, 191, 'Voucher Status Change', 'voucher-status-change', '2023-03-05 00:03:53', NULL),
(778, 191, 'Voucher Update', 'voucher-update', '2023-03-05 00:31:42', NULL),
(779, 45, 'Supplier Payment Status Change', 'supplier-payment-status-change', '2023-03-06 00:32:39', NULL),
(780, 46, 'Customer Receive Status Change', 'customer-receive-status-change', '2023-03-06 04:08:47', NULL),
(781, 100, 'Expense Status Change', 'expense-status-change', '2023-03-07 03:56:46', NULL),
(782, 100, 'Expense Details', 'expense-details', '2023-03-07 03:56:46', NULL),
(783, 241, 'Party Ledger Access', 'party-ledger-access', '2023-03-07 04:49:19', NULL),
(784, 253, 'Ledger Access', 'ledger-access', '2023-03-07 06:37:39', NULL),
(785, 89, 'Labor Bill Show', 'labor-bill-show', '2023-03-12 04:12:33', NULL),
(786, 89, 'Labor Bill Status Change', 'labor-bill-status-change', '2023-03-12 04:12:33', NULL),
(787, 254, 'Balance Sheet', 'balance-sheet', '2023-03-15 02:39:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(9, 65, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(10, 66, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(11, 67, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(12, 68, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(13, 69, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(14, 70, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(15, 71, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(16, 216, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(17, 217, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(18, 218, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(19, 219, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(20, 220, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(22, 232, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(23, 233, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(24, 234, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(25, 235, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(26, 236, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(27, 237, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(44, 88, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(45, 89, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(46, 90, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(47, 91, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(48, 92, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(49, 93, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(50, 94, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(51, 95, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(52, 222, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(69, 112, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(70, 113, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(71, 114, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(72, 115, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(73, 116, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(74, 117, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(75, 118, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(76, 119, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(77, 120, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(78, 121, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(79, 122, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(80, 123, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(81, 124, 2, '2021-04-03 20:57:49', '2021-04-03 20:57:49'),
(155, 252, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(156, 253, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(157, 254, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(158, 255, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(159, 256, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(160, 257, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(161, 258, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(162, 259, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(163, 260, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(164, 261, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(165, 262, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(166, 263, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(167, 264, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(168, 181, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(169, 182, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(170, 183, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(171, 184, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(172, 185, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(173, 186, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(174, 187, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(175, 188, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(176, 189, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(177, 190, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(178, 191, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(179, 192, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(180, 193, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(181, 194, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(182, 195, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(183, 196, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(184, 197, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(185, 246, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(186, 247, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(187, 198, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(188, 248, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(189, 199, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(190, 200, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(191, 201, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(192, 202, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(193, 249, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(194, 250, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(195, 251, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(196, 203, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(197, 2, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(198, 3, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(199, 4, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(200, 5, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(201, 6, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(202, 7, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(203, 8, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(204, 9, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(205, 10, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(206, 11, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(207, 12, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(208, 13, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(209, 14, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(210, 15, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(211, 39, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(212, 33, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(213, 34, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(214, 35, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(215, 36, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(216, 37, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(217, 38, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(218, 40, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(219, 41, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(220, 42, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(221, 43, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(222, 44, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(223, 45, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(224, 204, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(225, 205, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(226, 206, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(227, 207, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(228, 208, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(229, 209, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(230, 210, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(231, 211, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(232, 212, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(233, 213, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(234, 214, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(235, 215, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(236, 46, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(237, 47, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(238, 48, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(239, 49, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(240, 50, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(241, 51, 2, '2021-04-03 20:57:50', '2021-04-03 20:57:50'),
(257, 343, 2, '2021-04-08 18:35:36', '2021-04-08 18:35:36'),
(258, 344, 2, '2021-04-08 18:35:36', '2021-04-08 18:35:36'),
(259, 345, 2, '2021-04-08 18:35:36', '2021-04-08 18:35:36'),
(260, 346, 2, '2021-04-08 18:35:36', '2021-04-08 18:35:36'),
(261, 347, 2, '2021-04-08 18:35:36', '2021-04-08 18:35:36'),
(262, 348, 2, '2021-04-08 18:35:36', '2021-04-08 18:35:36'),
(297, 359, 2, '2021-04-19 02:00:16', '2021-04-19 02:00:16'),
(318, 381, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(319, 382, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(320, 383, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(321, 384, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(322, 385, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(323, 386, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(324, 387, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(325, 388, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(327, 379, 2, '2021-04-24 09:18:52', '2021-04-24 09:18:52'),
(341, 389, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(342, 390, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(343, 391, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(344, 392, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(345, 393, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(346, 394, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(347, 395, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(348, 396, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(349, 397, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(350, 398, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(351, 399, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(352, 400, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(353, 401, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(354, 402, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(355, 403, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(356, 404, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(357, 405, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(358, 406, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(359, 407, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(360, 408, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(361, 409, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(362, 410, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(363, 411, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(364, 412, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(365, 413, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(366, 467, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(367, 468, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(368, 469, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(369, 470, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(370, 471, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(371, 472, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(372, 473, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(373, 474, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(374, 475, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(375, 476, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(376, 477, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(377, 478, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(378, 479, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(379, 414, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(380, 415, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(381, 416, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(382, 417, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(383, 418, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(384, 419, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(385, 420, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(386, 421, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(387, 422, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(388, 423, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(389, 424, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(390, 425, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(391, 426, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(392, 427, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(393, 428, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(394, 429, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(395, 430, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(396, 431, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(397, 432, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(398, 433, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(399, 434, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(400, 435, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(401, 436, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(402, 437, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(403, 438, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(404, 439, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(405, 440, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(406, 441, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(407, 442, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(408, 443, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(409, 444, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(410, 445, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(411, 446, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(412, 447, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(413, 448, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(414, 449, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(415, 450, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(416, 451, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(417, 452, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(418, 453, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(419, 454, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(420, 455, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(421, 456, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(422, 457, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(423, 458, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(424, 459, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(425, 460, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(426, 461, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(427, 462, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(428, 463, 2, '2021-05-04 13:08:18', '2021-05-04 13:08:18'),
(768, 17, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(769, 18, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(770, 19, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(771, 20, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(772, 21, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(773, 22, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(774, 23, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(775, 24, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(776, 25, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(777, 26, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(778, 27, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(779, 28, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(780, 29, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(781, 30, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(782, 31, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(783, 32, 2, '2021-07-31 16:43:39', '2021-07-31 16:43:39'),
(794, 572, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(795, 573, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(796, 574, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(797, 575, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(798, 576, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(799, 583, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(800, 577, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(801, 578, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(802, 579, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(803, 580, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(804, 581, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(805, 582, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(806, 601, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(807, 602, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(808, 603, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(809, 604, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(810, 168, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(811, 169, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(812, 170, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(813, 171, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(814, 172, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(815, 159, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(816, 495, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(817, 496, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(818, 497, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(819, 160, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(820, 588, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(821, 589, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(822, 590, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(823, 591, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(824, 592, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(825, 632, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(826, 633, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(827, 634, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(828, 635, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(829, 636, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(830, 637, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(831, 638, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(832, 639, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(833, 640, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(834, 641, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(835, 645, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(836, 646, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(837, 647, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(838, 648, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(839, 649, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(840, 644, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(841, 173, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(842, 174, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(843, 175, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(844, 176, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(845, 177, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(846, 178, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(847, 179, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(848, 180, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09'),
(849, 380, 2, '2022-09-11 23:03:09', '2022-09-11 23:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `productions`
--

CREATE TABLE `productions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `production_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mill_id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_raw_scale` double DEFAULT NULL,
  `total_raw_amount` double DEFAULT NULL,
  `total_use_product_qty` double DEFAULT NULL,
  `total_use_product_amount` double DEFAULT NULL,
  `total_milling` double DEFAULT NULL,
  `total_expense` double DEFAULT NULL,
  `total_sale_scale` double DEFAULT NULL,
  `total_sale_amount` double DEFAULT NULL,
  `total_stock_scale` double DEFAULT NULL,
  `total_stock_amount` double DEFAULT NULL,
  `per_unit_scale_cost` double DEFAULT NULL,
  `production_status` enum('1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Pending , 2 = Cancel , 3 = Processing , 4 = Finished',
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_expenses`
--

CREATE TABLE `production_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `expense_item_id` bigint(20) UNSIGNED NOT NULL,
  `expense_cost` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_products`
--

CREATE TABLE `production_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT NULL,
  `scale` double DEFAULT NULL,
  `production_qty` double DEFAULT NULL,
  `price` double DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `use_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `use_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `use_qty` double DEFAULT NULL,
  `use_price` double DEFAULT NULL,
  `use_sub_total` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_raw_products`
--

CREATE TABLE `production_raw_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` double DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `use_qty` double DEFAULT NULL,
  `rest_qty` double DEFAULT NULL,
  `scale` double DEFAULT NULL,
  `use_scale` double DEFAULT NULL,
  `rest_scale` double DEFAULT NULL,
  `pro_qty` double DEFAULT NULL,
  `use_pro_qty` double DEFAULT NULL,
  `rest_pro_qty` double DEFAULT NULL,
  `milling` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_sales`
--

CREATE TABLE `production_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT ' 1 = General , 2 = Walking',
  `party_id` bigint(20) UNSIGNED DEFAULT NULL,
  `party_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_sale_qty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_sale_scale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_sale_sub_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `previous_due` double DEFAULT '0',
  `net_total` double DEFAULT '0',
  `paid_amount` double DEFAULT '0',
  `due_amount` double DEFAULT '0',
  `payment_status` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=Paid,2=Partial,3=Due',
  `payment_method` enum('1','2','3') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1=Cash,2=Bank Deposit,3=Mobile',
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_sale_products`
--

CREATE TABLE `production_sale_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT NULL,
  `scale` double DEFAULT NULL,
  `sel_qty` double DEFAULT NULL,
  `price` double DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `use_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `use_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `use_qty` double DEFAULT NULL,
  `use_price` double DEFAULT NULL,
  `use_sub_total` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `sale_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `alert_qty` double DEFAULT NULL,
  `opening_stock` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '1=Yes, 2=No',
  `opening_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_stock_qty` double DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `image`, `product_name`, `product_code`, `category_id`, `unit_id`, `purchase_price`, `sale_price`, `alert_qty`, `opening_stock`, `opening_warehouse_id`, `opening_stock_qty`, `status`, `created_by`, `modified_by`, `created_at`, `updated_at`) VALUES
(1, NULL, '29 ধান - 75', '35404391', 1, 25, '2600', '2650', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:04:29', '2023-03-19 03:04:29'),
(2, NULL, '17 ধান- 75', '08695099', 1, 25, '2550', '2560', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:05:07', '2023-03-19 03:05:07'),
(3, NULL, '51 ধান - 75', '10241032', 1, 25, '2350', '2360', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:05:41', '2023-03-19 03:05:41'),
(4, NULL, 'গুটি ধান - 75', '37028499', 1, 25, '2250', '2260', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:06:38', '2023-03-19 03:06:38'),
(5, NULL, 'মিনিকেট চাউল - 50', '26817609', 2, 31, '2800', '2900', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:07:28', '2023-03-19 03:07:28'),
(6, NULL, 'মিনিকেট চাউল - 25', '20631561', 2, 28, '1400', '1450', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:07:50', '2023-03-19 03:07:50'),
(7, NULL, 'মিনিকেট চাউল - 84', '29418975', 2, 36, '4300', '4350', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:09:30', '2023-03-19 03:09:30'),
(8, NULL, 'সুমন নাজির চাউল - 50', '38457904', 2, 31, '2250', '2300', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:10:09', '2023-03-19 03:10:09'),
(9, NULL, 'সুমন নাজির চাউল - 25', '31770093', 2, 28, '1125', '1150', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:10:35', '2023-03-19 03:10:35'),
(10, NULL, 'সুমন সিদ্ধ চাউল- 50', '91368493', 2, 31, '2250', '2300', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:11:13', '2023-03-19 03:11:13'),
(11, NULL, 'সুমন ‍সিদ্ধ চাউল - 25', '29307578', 2, 28, '1125', '1150', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:11:42', '2023-03-19 03:11:42'),
(12, NULL, '29 নাজির চাউল -50', '71303729', 2, 31, '2600', '2650', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:12:12', '2023-03-19 03:12:12'),
(13, NULL, '29 নাজির চাউল -25', '16257152', 2, 28, '1300', '1325', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:12:44', '2023-03-19 03:12:44'),
(14, NULL, '28 সিদ্ধ চাউল - 50', '08571193', 2, 31, '2750', '2800', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:13:16', '2023-03-19 03:13:16'),
(15, NULL, '28 সিদ্ধ চাউল - 25', '14986870', 2, 28, '1375', '1400', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:13:49', '2023-03-19 03:13:49'),
(16, NULL, 'গুটি সিদ্ধ চাউল - 50', '95501347', 2, 31, '2050', '2100', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:14:40', '2023-03-19 03:14:40'),
(17, NULL, 'গুটি সিদ্ধ চাউল - 25', '26881094', 2, 28, '1025', '1050', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:15:07', '2023-03-19 03:15:07'),
(18, NULL, 'গুটি নাজির চাউল- 50', '99510073', 2, 31, '2050', '2100', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:15:31', '2023-03-19 03:15:31'),
(19, NULL, 'গুটি নাজির চাউল- 25', '79116349', 2, 28, '1025', '1100', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:15:56', '2023-03-19 03:15:56'),
(20, NULL, 'বাসমতি চাউল - 84', '24504975', 2, 36, '4700', '4750', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:16:31', '2023-03-19 03:16:31'),
(21, NULL, 'বাসমতি চাউল - 50', '76285063', 2, 28, '2900', '3100', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:17:24', '2023-03-19 03:17:24'),
(22, NULL, 'বাসমতি চাউল - 25', '57147304', 2, 28, '1450', '1550', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:17:59', '2023-03-19 03:17:59'),
(23, NULL, 'সম্পা ষ্টিম নাজির - 25', '67503317', 2, 28, '1600', '1650', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:19:35', '2023-03-19 03:19:35'),
(24, NULL, '16 মুড়ির চাউল - 50', '75113370', 2, 31, '2800', '2850', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:20:57', '2023-03-19 03:20:57'),
(25, NULL, 'বিনা 7 মুড়ির চাউল - 50', '34609392', 2, 31, '2300', '2400', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:21:51', '2023-03-19 03:21:51'),
(26, NULL, 'সম্পা সিদ্ধ চাউল - 60', '08171823', 2, 32, '2600', '2650', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:22:38', '2023-03-19 03:22:38'),
(27, NULL, 'সম্পা সিদ্ধ চাউল - 25', '89463204', 2, 28, '1300', '1350', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:22:58', '2023-03-19 03:22:58'),
(28, NULL, '16 ধান - 75', '29416212', 1, 25, '2450', '2500', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:24:02', '2023-03-19 03:24:02'),
(29, NULL, 'ধানীগোল্ড ধান - 75', '14522036', 1, 25, '2400', '2500', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:24:37', '2023-03-19 03:24:37'),
(30, NULL, 'হাইব্রিড ধান - 75', '07183540', 1, 25, '2000', '2050', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:30:20', '2023-03-19 03:30:20'),
(31, NULL, 'সিদ্ধ ব্রান - 50', '51693843', 4, 31, '1900', '2000', NULL, '2', NULL, NULL, '1', 'Super Admin', 'Super Admin', '2023-03-19 03:30:54', '2023-03-19 03:31:26'),
(32, NULL, 'সিলকী ব্রান - 55', '06810364', 4, 35, '1700', '1750', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:31:15', '2023-03-19 03:31:15'),
(33, NULL, 'সোডার খুদ - 50', '18799350', 4, 31, '1700', '1800', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:31:54', '2023-03-19 03:31:54'),
(34, NULL, 'শিপ্টার খুদ - 50', '49908731', 4, 31, '1700', '1800', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:32:12', '2023-03-19 03:32:12'),
(35, NULL, 'ডায়মন্ড খুদ - 50', '41025310', 4, 31, '1800', '1900', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:32:35', '2023-03-19 03:32:35'),
(36, NULL, 'লাল চাউল সিদ্ধ - 50', '56143485', 4, 31, '1800', '1900', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:33:14', '2023-03-19 03:33:14'),
(37, NULL, 'লাল চাউল নাজির - 50', '15002354', 4, 31, '1800', '1900', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:33:43', '2023-03-19 03:33:43'),
(38, NULL, 'পাতান খুদ- 50', '78934158', 4, 31, '1600', '1700', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:34:45', '2023-03-19 03:34:45'),
(39, NULL, 'রি-সোডার চাউল - 50', '01660749', 4, 31, '1700', '1800', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:35:11', '2023-03-19 03:35:11'),
(40, NULL, 'চিনিগুড়া আতব চাউল - 1 কেজি', '29060202', 2, 29, '120', '130', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:36:03', '2023-03-19 03:36:03'),
(41, NULL, 'চিনিগুড়া আতব চাউল', '47401823', 2, 29, '110', '115', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:44:45', '2023-03-19 03:44:45'),
(42, NULL, 'সম্পা আতব চাউল', '29510056', 2, 29, '60', '65', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:46:54', '2023-03-19 03:46:54'),
(43, NULL, '49 আতব চাউল', '94259506', 2, 29, '45', '50', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:47:25', '2023-03-19 03:47:25'),
(44, NULL, 'সুমন আতব চাউল', '17027966', 2, 29, '45', '46', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:48:13', '2023-03-19 03:48:13'),
(45, NULL, '90 জিরা আতব চাউল', '99576015', 2, 29, '90', '95', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:48:43', '2023-03-19 03:48:43'),
(46, NULL, 'আতব ব্রান - 55', '25182369', 4, 35, '1800', '1850', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:49:51', '2023-03-19 03:49:51'),
(47, NULL, 'আতব ভালো খুদ - 50', '02023848', 4, 31, '2000', '2200', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:50:17', '2023-03-19 03:50:17'),
(48, NULL, 'আতব সটার খুদ - 50', '80991908', 4, 31, '1800', '1900', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:50:47', '2023-03-19 03:50:47'),
(49, NULL, 'আতব লাল চাউল - 50', '18097189', 4, 31, '2200', '2400', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:51:17', '2023-03-19 03:51:17'),
(50, NULL, '34 জিরা ধান - 75', '95102143', 1, 25, '5500', '6000', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:52:04', '2023-03-19 03:52:04'),
(51, NULL, '90 জিরা ধান - 75', '39019192', 1, 25, '4200', '4500', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:52:26', '2023-03-19 03:52:26'),
(52, NULL, 'নাজির চট বস্তা - 50', '13780532', 3, 33, '35', '35', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:56:51', '2023-03-19 03:56:51'),
(53, NULL, 'নাজির চট বস্তা - 25', '42696300', 3, 33, '25', '25', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:57:11', '2023-03-19 03:57:11'),
(54, NULL, 'নাজির প্লাষ্টিক মোরগ - 50', '78011243', 3, 33, '20', '20', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:58:01', '2023-03-19 03:58:01'),
(55, NULL, 'নাজির প্লাষ্টিক মোরগ - 25', '97630629', 3, 33, '15', '15', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 03:58:28', '2023-03-19 03:58:28'),
(56, NULL, 'গরুগাড়ী নাজির প্লাষ্টিক -50', '45747196', 3, 33, '20', '20', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 04:00:10', '2023-03-19 04:00:10'),
(57, NULL, 'গরুগাড়ী নাজির প্লাষ্টিক -25', '70342599', 3, 33, '15', '15', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 04:00:36', '2023-03-19 04:00:36'),
(58, NULL, 'চট বস্তা - 50', '38497692', 3, 33, '35', '35', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 04:01:12', '2023-03-19 04:01:12'),
(59, NULL, 'চট বস্তা - 25', '36798261', 3, 33, '25', '25', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 04:01:31', '2023-03-19 04:01:31'),
(60, NULL, 'চিনিগুড়া প্লাষ্টিক বস্তা - 25', '70132816', 3, 33, '25', '25', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 04:02:03', '2023-03-19 04:02:03'),
(61, NULL, 'বাসমতি চট বস্তা -25', '31325442', 3, 33, '35', '35', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 04:02:34', '2023-03-19 04:02:34'),
(62, NULL, 'খুদ প্লাষ্টিক বস্তা', '48217142', 3, 33, '13', '13', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 04:03:11', '2023-03-19 04:03:11'),
(63, NULL, 'ব্রানের বস্তা', '19522618', 3, 33, '15', '15', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 04:03:29', '2023-03-19 04:03:29'),
(64, NULL, 'সাদা প্লাষ্টিক বস্তা', '96511432', 3, 33, '13', '13', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 04:03:52', '2023-03-19 04:03:52'),
(65, NULL, 'সুমন ধান - 75', '57910971', 1, 25, '2300', '2350', NULL, '2', NULL, NULL, '1', 'Super Admin', NULL, '2023-03-19 05:10:29', '2023-03-19 05:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT ' 1 = General , 2 = Walking',
  `party_id` bigint(20) UNSIGNED DEFAULT NULL,
  `party_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_status` enum('1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT ' 1 = Ordered , 2 = Pending , 3 = Rejected , 4 = Approved',
  `document` longtext COLLATE utf8mb4_unicode_ci,
  `discount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_purchase_qty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_receive_qty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_return_qty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_purchase_sub_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_receive_sub_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_return_sub_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `previous_due` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `net_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `paid_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `due_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `payment_status` enum('1','2','3') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1=Paid,2=Partial,3=Due',
  `payment_method` enum('1','2','3') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1=Cash,2=Bank Deposit,3=Mobile',
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT '0',
  `scale` double DEFAULT '0',
  `rec_qty` double DEFAULT NULL,
  `price` double DEFAULT '0',
  `sub_total` double DEFAULT '0',
  `receive_scale` double DEFAULT '0',
  `receive_qty` double DEFAULT '0',
  `return_scale` double DEFAULT '0',
  `return_qty` double DEFAULT '0',
  `purchase_date` date DEFAULT '0000-00-00',
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_product_receives`
--

CREATE TABLE `purchase_product_receives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `scale` double DEFAULT '0',
  `qty` double DEFAULT '0',
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_product_returns`
--

CREATE TABLE `purchase_product_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `scale` double DEFAULT '0',
  `qty` double DEFAULT '0',
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deletable` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '1=No, 2=Yes',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `deletable`, `created_by`, `modified_by`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', '1', NULL, NULL, NULL, NULL),
(2, 'Admin', '1', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT ' 1 = General , 2 = Walking',
  `party_id` bigint(20) UNSIGNED DEFAULT NULL,
  `party_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT ' 1 = Regular , 2 = Pre Order',
  `sale_status` enum('1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT ' 1 = Ordered , 2 = Pending , 3 = Rejected , 4 = Approved',
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_sale_qty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_delivery_qty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `total_return_qty` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_sale_sub_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_delivery_sub_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `total_return_sub_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `previous_due` double DEFAULT '0',
  `net_total` double DEFAULT '0',
  `paid_amount` double DEFAULT '0',
  `due_amount` double DEFAULT '0',
  `payment_status` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=Paid,2=Partial,3=Due',
  `payment_method` enum('1','2','3') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1=Cash,2=Bank Deposit,3=Mobile',
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_products`
--

CREATE TABLE `sale_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT '0',
  `scale` double DEFAULT '0',
  `sel_qty` double DEFAULT '0',
  `price` double DEFAULT '0',
  `sub_total` double DEFAULT '0',
  `delivery_scale` double DEFAULT '0',
  `delivery_qty` double DEFAULT '0',
  `return_scale` double DEFAULT '0',
  `return_qty` double DEFAULT '0',
  `date` date DEFAULT NULL,
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_deliveries`
--

CREATE TABLE `sale_product_deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` double DEFAULT '0',
  `scale` double DEFAULT '0',
  `qty` double DEFAULT '0',
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_product_returns`
--

CREATE TABLE `sale_product_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` double DEFAULT '0',
  `scale` double DEFAULT '0',
  `qty` double DEFAULT '0',
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fMZNWU47r7lOS2brv8HBm7IIPfCPiuVKMnN98vk3', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoidndiUkZnNTFEZTQ2QWRFZTRmOHc4bHFrS2JaQmN6TW1Ca2ltcUdLbiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI0OiJodHRwOi8vc29oYS50ZXN0L3Byb2R1Y3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTU6InVzZXJfcGVybWlzc2lvbiI7YToyNzk6e2k6MDtzOjEzOiJiYWxhbmNlLXNoZWV0IjtpOjE7czoxMToiYmFuay1hY2Nlc3MiO2k6MjtzOjg6ImJhbmstYWRkIjtpOjM7czoxMToiYmFuay1kZWxldGUiO2k6NDtzOjk6ImJhbmstZWRpdCI7aTo1O3M6MTE6ImJhbmstcmVwb3J0IjtpOjY7czoxNToiY2F0ZWdvcnktYWNjZXNzIjtpOjc7czoxMjoiY2F0ZWdvcnktYWRkIjtpOjg7czoyMDoiY2F0ZWdvcnktYnVsay1kZWxldGUiO2k6OTtzOjE1OiJjYXRlZ29yeS1kZWxldGUiO2k6MTA7czoxMzoiY2F0ZWdvcnktZWRpdCI7aToxMTtzOjE1OiJjYXRlZ29yeS1yZXBvcnQiO2k6MTI7czoxMjoiY2hhcnQtYWNjZXNzIjtpOjEzO3M6MTc6ImNoaWxkLWhlYWQtYWNjZXNzIjtpOjE0O3M6MTQ6ImNoaWxkLWhlYWQtYWRkIjtpOjE1O3M6MTc6ImNoaWxkLWhlYWQtZGVsZXRlIjtpOjE2O3M6MTU6ImNoaWxkLWhlYWQtZWRpdCI7aToxNztzOjIzOiJjdXN0b21lci1yZWNlaXZlLWFjY2VzcyI7aToxODtzOjIwOiJjdXN0b21lci1yZWNlaXZlLWFkZCI7aToxOTtzOjIzOiJjdXN0b21lci1yZWNlaXZlLWRlbGV0ZSI7aToyMDtzOjI0OiJjdXN0b21lci1yZWNlaXZlLWRldGFpbHMiO2k6MjE7czoyMToiY3VzdG9tZXItcmVjZWl2ZS1lZGl0IjtpOjIyO3M6MzA6ImN1c3RvbWVyLXJlY2VpdmUtc3RhdHVzLWNoYW5nZSI7aToyMztzOjIxOiJjdXN0b21lci1yZWNlaXZlLXZpZXciO2k6MjQ7czoxNjoiZGFzaGJvYXJkLWFjY2VzcyI7aToyNTtzOjE5OiJkaXN0cmlidXRpb24tYWNjZXNzIjtpOjI2O3M6MTY6ImRpc3RyaWJ1dGlvbi1hZGQiO2k6Mjc7czoxOToiZGlzdHJpYnV0aW9uLWRlbGV0ZSI7aToyODtzOjE3OiJkaXN0cmlidXRpb24tZWRpdCI7aToyOTtzOjE3OiJkaXN0cmlidXRpb24tc2hvdyI7aTozMDtzOjI2OiJkaXN0cmlidXRpb24tc3RhdHVzLWNoYW5nZSI7aTozMTtzOjE5OiJkaXN0cmlidXRpb24tdXBkYXRlIjtpOjMyO3M6MTc6ImRpc3RyaWJ1dGlvbi12aWV3IjtpOjMzO3M6MTQ6ImV4cGVuc2UtYWNjZXNzIjtpOjM0O3M6MTE6ImV4cGVuc2UtYWRkIjtpOjM1O3M6MTU6ImV4cGVuc2UtYXBwcm92ZSI7aTozNjtzOjE5OiJleHBlbnNlLWJ1bGstZGVsZXRlIjtpOjM3O3M6MTQ6ImV4cGVuc2UtZGVsZXRlIjtpOjM4O3M6MTU6ImV4cGVuc2UtZGV0YWlscyI7aTozOTtzOjEyOiJleHBlbnNlLWVkaXQiO2k6NDA7czoxOToiZXhwZW5zZS1pdGVtLWFjY2VzcyI7aTo0MTtzOjE2OiJleHBlbnNlLWl0ZW0tYWRkIjtpOjQyO3M6MjQ6ImV4cGVuc2UtaXRlbS1idWxrLWRlbGV0ZSI7aTo0MztzOjE5OiJleHBlbnNlLWl0ZW0tZGVsZXRlIjtpOjQ0O3M6MTc6ImV4cGVuc2UtaXRlbS1lZGl0IjtpOjQ1O3M6MTk6ImV4cGVuc2UtaXRlbS1yZXBvcnQiO2k6NDY7czoxNDoiZXhwZW5zZS1yZXBvcnQiO2k6NDc7czoyMToiZXhwZW5zZS1zdGF0dXMtY2hhbmdlIjtpOjQ4O3M6Mjc6ImZpbmlzaGVkLWdvb2RzLXN0b2NrLWFjY2VzcyI7aTo0OTtzOjk6ImdhdGUtcGFzcyI7aTo1MDtzOjIyOiJnZW5lcmFsLXNldHRpbmctYWNjZXNzIjtpOjUxO3M6MTE6ImhlYWQtYWNjZXNzIjtpOjUyO3M6ODoiaGVhZC1hZGQiO2k6NTM7czoxMToiaGVhZC1kZWxldGUiO2k6NTQ7czo5OiJoZWFkLWVkaXQiO2k6NTU7czoxNzoibGFib3ItYmlsbC1hY2Nlc3MiO2k6NTY7czoxNDoibGFib3ItYmlsbC1hZGQiO2k6NTc7czoyMjoibGFib3ItYmlsbC1idWxrLWRlbGV0ZSI7aTo1ODtzOjE3OiJsYWJvci1iaWxsLWRlbGV0ZSI7aTo1OTtzOjE1OiJsYWJvci1iaWxsLWVkaXQiO2k6NjA7czoyMjoibGFib3ItYmlsbC1yYXRlLWFjY2VzcyI7aTo2MTtzOjE5OiJsYWJvci1iaWxsLXJhdGUtYWRkIjtpOjYyO3M6Mjc6ImxhYm9yLWJpbGwtcmF0ZS1idWxrLWRlbGV0ZSI7aTo2MztzOjIyOiJsYWJvci1iaWxsLXJhdGUtZGVsZXRlIjtpOjY0O3M6MjA6ImxhYm9yLWJpbGwtcmF0ZS1lZGl0IjtpOjY1O3M6MjI6ImxhYm9yLWJpbGwtcmF0ZS1yZXBvcnQiO2k6NjY7czoxNzoibGFib3ItYmlsbC1yZXBvcnQiO2k6Njc7czoxNToibGFib3ItYmlsbC1zaG93IjtpOjY4O3M6MjQ6ImxhYm9yLWJpbGwtc3RhdHVzLWNoYW5nZSI7aTo2OTtzOjE3OiJsYWJvci1oZWFkLWFjY2VzcyI7aTo3MDtzOjE0OiJsYWJvci1oZWFkLWFkZCI7aTo3MTtzOjIyOiJsYWJvci1oZWFkLWJ1bGstZGVsZXRlIjtpOjcyO3M6MTc6ImxhYm9yLWhlYWQtZGVsZXRlIjtpOjczO3M6MTU6ImxhYm9yLWhlYWQtZWRpdCI7aTo3NDtzOjEzOiJsZWRnZXItYWNjZXNzIjtpOjc1O3M6MTE6ImxvYW4tYWNjZXNzIjtpOjc2O3M6ODoibG9hbi1hZGQiO2k6Nzc7czoyMjoibG9hbi1jYXRlZ29yaWVzLWFjY2VzcyI7aTo3ODtzOjIyOiJsb2FuLWNhdGVnb3JpZXMtZGVsZXRlIjtpOjc5O3M6MjA6ImxvYW4tY2F0ZWdvcmllcy1lZGl0IjtpOjgwO3M6Mjg6ImxvYW4tY2F0ZWdvcmllcy1zdG9yZS11cGRhdGUiO2k6ODE7czoxMToibG9hbi1kZWxldGUiO2k6ODI7czo5OiJsb2FuLWVkaXQiO2k6ODM7czoyMzoibG9hbi1pbnN0YWxsbWVudC1hY2Nlc3MiO2k6ODQ7czoyMDoibG9hbi1pbnN0YWxsbWVudC1hZGQiO2k6ODU7czoyMzoibG9hbi1pbnN0YWxsbWVudC1kZWxldGUiO2k6ODY7czoyMToibG9hbi1pbnN0YWxsbWVudC1lZGl0IjtpOjg3O3M6MzA6ImxvYW4taW5zdGFsbG1lbnQtc3RhdHVzLWNoYW5nZSI7aTo4ODtzOjE4OiJsb2FuLWxlZGdlci1hY2Nlc3MiO2k6ODk7czoxODoibG9hbi1zdGF0dXMtY2hhbmdlIjtpOjkwO3M6OToibG9hbi12aWV3IjtpOjkxO3M6MTE6Im1lbnUtYWNjZXNzIjtpOjkyO3M6ODoibWVudS1hZGQiO2k6OTM7czoxOToibWVudS1idWlsZGVyLWFjY2VzcyI7aTo5NDtzOjE2OiJtZW51LWJ1bGstZGVsZXRlIjtpOjk1O3M6MTE6Im1lbnUtZGVsZXRlIjtpOjk2O3M6OToibWVudS1lZGl0IjtpOjk3O3M6MTU6Im1lbnUtbW9kdWxlLWFkZCI7aTo5ODtzOjE4OiJtZW51LW1vZHVsZS1kZWxldGUiO2k6OTk7czoxNjoibWVudS1tb2R1bGUtZWRpdCI7aToxMDA7czoxMToibWVudS1yZXBvcnQiO2k6MTAxO3M6MTE6Im1pbGwtYWNjZXNzIjtpOjEwMjtzOjg6Im1pbGwtYWRkIjtpOjEwMztzOjExOiJtaWxsLWRlbGV0ZSI7aToxMDQ7czo5OiJtaWxsLWVkaXQiO2k6MTA1O3M6MTg6Im1vYmlsZS1iYW5rLWFjY2VzcyI7aToxMDY7czoxNToibW9iaWxlLWJhbmstYWRkIjtpOjEwNztzOjIzOiJtb2JpbGUtYmFuay1idWxrLWRlbGV0ZSI7aToxMDg7czoxODoibW9iaWxlLWJhbmstZGVsZXRlIjtpOjEwOTtzOjE2OiJtb2JpbGUtYmFuay1lZGl0IjtpOjExMDtzOjE4OiJtb2JpbGUtYmFuay1yZXBvcnQiO2k6MTExO3M6MTI6InBhcnR5LWFjY2VzcyI7aToxMTI7czo5OiJwYXJ0eS1hZGQiO2k6MTEzO3M6MTI6InBhcnR5LWRlbGV0ZSI7aToxMTQ7czoxMDoicGFydHktZWRpdCI7aToxMTU7czoxOToicGFydHktbGVkZ2VyLWFjY2VzcyI7aToxMTY7czoxMDoicGFydHktdmlldyI7aToxMTc7czoxNzoicGVybWlzc2lvbi1hY2Nlc3MiO2k6MTE4O3M6MTQ6InBlcm1pc3Npb24tYWRkIjtpOjExOTtzOjIyOiJwZXJtaXNzaW9uLWJ1bGstZGVsZXRlIjtpOjEyMDtzOjE3OiJwZXJtaXNzaW9uLWRlbGV0ZSI7aToxMjE7czoxNToicGVybWlzc2lvbi1lZGl0IjtpOjEyMjtzOjE3OiJwZXJtaXNzaW9uLXJlcG9ydCI7aToxMjM7czoxNDoicHJvZHVjdC1hY2Nlc3MiO2k6MTI0O3M6MTE6InByb2R1Y3QtYWRkIjtpOjEyNTtzOjIwOiJwcm9kdWN0LWFsZXJ0LWFjY2VzcyI7aToxMjY7czoxNDoicHJvZHVjdC1kZWxldGUiO2k6MTI3O3M6MTI6InByb2R1Y3QtZWRpdCI7aToxMjg7czoyMToicHJvZHVjdC1zdGF0dXMtY2hhbmdlIjtpOjEyOTtzOjEyOiJwcm9kdWN0LXZpZXciO2k6MTMwO3M6MTA6InByb2R1Y3Rpb24iO2k6MTMxO3M6MTc6InByb2R1Y3Rpb24tYWNjZXNzIjtpOjEzMjtzOjE0OiJwcm9kdWN0aW9uLWFkZCI7aToxMzM7czoxOToicHJvZHVjdGlvbi1jb21wbGV0ZSI7aToxMzQ7czoxNzoicHJvZHVjdGlvbi1kZWxldGUiO2k6MTM1O3M6MTU6InByb2R1Y3Rpb24tZWRpdCI7aToxMzY7czoyMjoicHJvZHVjdGlvbi1wcm9kdWN0LWFkZCI7aToxMzc7czoyNjoicHJvZHVjdGlvbi1wcm9kdWN0LWRldGFpbHMiO2k6MTM4O3M6MTc6InByb2R1Y3Rpb24tcmVwb3J0IjtpOjEzOTtzOjE5OiJwcm9kdWN0aW9uLXNhbGUtYWRkIjtpOjE0MDtzOjIzOiJwcm9kdWN0aW9uLXNhbGUtZGV0YWlscyI7aToxNDE7czoxNToicHJvZHVjdGlvbi1zaG93IjtpOjE0MjtzOjI0OiJwcm9kdWN0aW9uLXN0YXR1cy1jaGFuZ2UiO2k6MTQzO3M6MTg6InByb2R1Y3Rpb24tc3VtbWFyeSI7aToxNDQ7czoxNToicHVyY2hhc2UtYWNjZXNzIjtpOjE0NTtzOjEyOiJwdXJjaGFzZS1hZGQiO2k6MTQ2O3M6MTY6InB1cmNoYXNlLWFwcHJvdmUiO2k6MTQ3O3M6MjA6InB1cmNoYXNlLWJ1bGstZGVsZXRlIjtpOjE0ODtzOjE1OiJwdXJjaGFzZS1kZWxldGUiO2k6MTQ5O3M6MTM6InB1cmNoYXNlLWVkaXQiO2k6MTUwO3M6MTY6InB1cmNoYXNlLWludm9pY2UiO2k6MTUxO3M6MjA6InB1cmNoYXNlLXBheW1lbnQtYWRkIjtpOjE1MjtzOjIzOiJwdXJjaGFzZS1wYXltZW50LWRlbGV0ZSI7aToxNTM7czoyMToicHVyY2hhc2UtcGF5bWVudC1lZGl0IjtpOjE1NDtzOjIxOiJwdXJjaGFzZS1wYXltZW50LXZpZXciO2k6MTU1O3M6MTY6InB1cmNoYXNlLXJlY2VpdmUiO2k6MTU2O3M6MjQ6InB1cmNoYXNlLXJlY2VpdmUtZGV0YWlscyI7aToxNTc7czoxNzoicHVyY2hhc2UtcmVjZWl2ZWQiO2k6MTU4O3M6MTU6InB1cmNoYXNlLXJlcG9ydCI7aToxNTk7czoxNToicHVyY2hhc2UtcmV0dXJuIjtpOjE2MDtzOjIzOiJwdXJjaGFzZS1yZXR1cm4tZGV0YWlscyI7aToxNjE7czoyMjoicHVyY2hhc2Utc3RhdHVzLWNoYW5nZSI7aToxNjI7czoxMzoicHVyY2hhc2UtdmlldyI7aToxNjM7czoxNjoicmVjZWl2ZWQtaW52b2ljZSI7aToxNjQ7czoxMToicm9sZS1hY2Nlc3MiO2k6MTY1O3M6ODoicm9sZS1hZGQiO2k6MTY2O3M6MTY6InJvbGUtYnVsay1kZWxldGUiO2k6MTY3O3M6MTE6InJvbGUtZGVsZXRlIjtpOjE2ODtzOjk6InJvbGUtZWRpdCI7aToxNjk7czoxMToicm9sZS1yZXBvcnQiO2k6MTcwO3M6OToicm9sZS12aWV3IjtpOjE3MTtzOjExOiJzYWxlLWFjY2VzcyI7aToxNzI7czo4OiJzYWxlLWFkZCI7aToxNzM7czoxMjoic2FsZS1hcHByb3ZlIjtpOjE3NDtzOjE2OiJzYWxlLWJ1bGstZGVsZXRlIjtpOjE3NTtzOjExOiJzYWxlLWRlbGV0ZSI7aToxNzY7czoxMzoic2FsZS1kZWxpdmVyeSI7aToxNzc7czoyMToic2FsZS1kZWxpdmVyeS1pbnZvaWNlIjtpOjE3ODtzOjk6InNhbGUtZWRpdCI7aToxNzk7czoxMToic2FsZS1yZXBvcnQiO2k6MTgwO3M6MTE6InNhbGUtcmV0dXJuIjtpOjE4MTtzOjE5OiJzYWxlLXJldHVybi1pbnZvaWNlIjtpOjE4MjtzOjk6InNhbGUtdmlldyI7aToxODM7czoyMToic3RvY2stdHJhbnNmZXItYWNjZXNzIjtpOjE4NDtzOjE4OiJzdG9jay10cmFuc2Zlci1hZGQiO2k6MTg1O3M6Mjg6InN0b2NrLXRyYW5zZmVyLWNoYW5nZS1zdGF0dXMiO2k6MTg2O3M6MjE6InN0b2NrLXRyYW5zZmVyLWRlbGV0ZSI7aToxODc7czoxOToic3RvY2stdHJhbnNmZXItZWRpdCI7aToxODg7czoxOToic3RvY2stdHJhbnNmZXItdmlldyI7aToxODk7czoxNToic3ViLWhlYWQtYWNjZXNzIjtpOjE5MDtzOjEyOiJzdWItaGVhZC1hZGQiO2k6MTkxO3M6MTU6InN1Yi1oZWFkLWRlbGV0ZSI7aToxOTI7czoxMzoic3ViLWhlYWQtZWRpdCI7aToxOTM7czoyMzoic3VwcGxpZXItcGF5bWVudC1hY2Nlc3MiO2k6MTk0O3M6MjA6InN1cHBsaWVyLXBheW1lbnQtYWRkIjtpOjE5NTtzOjIzOiJzdXBwbGllci1wYXltZW50LWRlbGV0ZSI7aToxOTY7czoyNDoic3VwcGxpZXItcGF5bWVudC1kZXRhaWxzIjtpOjE5NztzOjIxOiJzdXBwbGllci1wYXltZW50LWVkaXQiO2k6MTk4O3M6MzA6InN1cHBsaWVyLXBheW1lbnQtc3RhdHVzLWNoYW5nZSI7aToxOTk7czoyMToic3VwcGxpZXItcGF5bWVudC12aWV3IjtpOjIwMDtzOjEzOiJ0ZW5hbnQtYWNjZXNzIjtpOjIwMTtzOjEwOiJ0ZW5hbnQtYWRkIjtpOjIwMjtzOjIwOiJ0ZW5hbnQtY2hhbmdlLXN0YXR1cyI7aToyMDM7czoxMzoidGVuYW50LWRlbGV0ZSI7aToyMDQ7czoyMjoidGVuYW50LWRlbGl2ZXJ5LWFjY2VzcyI7aToyMDU7czoxOToidGVuYW50LWRlbGl2ZXJ5LWFkZCI7aToyMDY7czoyMjoidGVuYW50LWRlbGl2ZXJ5LWRlbGV0ZSI7aToyMDc7czoyMDoidGVuYW50LWRlbGl2ZXJ5LWVkaXQiO2k6MjA4O3M6MjA6InRlbmFudC1kZWxpdmVyeS1zaG93IjtpOjIwOTtzOjI5OiJ0ZW5hbnQtZGVsaXZlcnktc3RhdHVzLWNoYW5nZSI7aToyMTA7czoxMToidGVuYW50LWVkaXQiO2k6MjExO3M6MjQ6InRlbmFudC1wcm9kdWN0aW9uLWFjY2VzcyI7aToyMTI7czoyMToidGVuYW50LXByb2R1Y3Rpb24tYWRkIjtpOjIxMztzOjI2OiJ0ZW5hbnQtcHJvZHVjdGlvbi1jb21wbGV0ZSI7aToyMTQ7czoyNDoidGVuYW50LXByb2R1Y3Rpb24tZGVsZXRlIjtpOjIxNTtzOjMwOiJ0ZW5hbnQtcHJvZHVjdGlvbi1kZWxpdmVyeS1hZGQiO2k6MjE2O3M6MzQ6InRlbmFudC1wcm9kdWN0aW9uLWRlbGl2ZXJ5LWRldGFpbHMiO2k6MjE3O3M6MjI6InRlbmFudC1wcm9kdWN0aW9uLWVkaXQiO2k6MjE4O3M6MjU6InRlbmFudC1wcm9kdWN0aW9uLXByb2R1Y3QiO2k6MjE5O3M6Mjk6InRlbmFudC1wcm9kdWN0aW9uLXByb2R1Y3QtYWRkIjtpOjIyMDtzOjMzOiJ0ZW5hbnQtcHJvZHVjdGlvbi1wcm9kdWN0LWRldGFpbHMiO2k6MjIxO3M6MzI6InRlbmFudC1wcm9kdWN0aW9uLXJlcG9ydC1kZXRhaWxzIjtpOjIyMjtzOjIyOiJ0ZW5hbnQtcHJvZHVjdGlvbi1zaG93IjtpOjIyMztzOjMxOiJ0ZW5hbnQtcHJvZHVjdGlvbi1zdGF0dXMtY2hhbmdlIjtpOjIyNDtzOjI1OiJ0ZW5hbnQtcHJvZHVjdGlvbi1zdW1tYXJ5IjtpOjIyNTtzOjI0OiJ0ZW5hbnQtcHJvZHVjdGlvbi11cGRhdGUiO2k6MjI2O3M6MjE6InRlbmFudC1yZWNlaXZlLWFjY2VzcyI7aToyMjc7czoxODoidGVuYW50LXJlY2VpdmUtYWRkIjtpOjIyODtzOjIxOiJ0ZW5hbnQtcmVjZWl2ZS1kZWxldGUiO2k6MjI5O3M6MTk6InRlbmFudC1yZWNlaXZlLWVkaXQiO2k6MjMwO3M6MTk6InRlbmFudC1yZWNlaXZlLXNob3ciO2k6MjMxO3M6Mjg6InRlbmFudC1yZWNlaXZlLXN0YXR1cy1jaGFuZ2UiO2k6MjMyO3M6MjA6InRlbmFudC1yZXR1cm4tYWNjZXNzIjtpOjIzMztzOjE3OiJ0ZW5hbnQtcmV0dXJuLWFkZCI7aToyMzQ7czoyMDoidGVuYW50LXJldHVybi1kZWxldGUiO2k6MjM1O3M6MTg6InRlbmFudC1yZXR1cm4tZWRpdCI7aToyMzY7czoxODoidGVuYW50LXJldHVybi1zaG93IjtpOjIzNztzOjI3OiJ0ZW5hbnQtcmV0dXJuLXN0YXR1cy1jaGFuZ2UiO2k6MjM4O3M6MTk6InRlbmFudC1zdG9jay1hY2Nlc3MiO2k6MjM5O3M6MTY6InRyYW5zcG9ydC1hY2Nlc3MiO2k6MjQwO3M6MTM6InRyYW5zcG9ydC1hZGQiO2k6MjQxO3M6MjE6InRyYW5zcG9ydC1idWxrLWRlbGV0ZSI7aToyNDI7czoyMzoidHJhbnNwb3J0LWNoYW5nZS1zdGF0dXMiO2k6MjQzO3M6MTY6InRyYW5zcG9ydC1kZWxldGUiO2k6MjQ0O3M6MTQ6InRyYW5zcG9ydC1lZGl0IjtpOjI0NTtzOjE2OiJ0cmFuc3BvcnQtcmVwb3J0IjtpOjI0NjtzOjE0OiJ0cmFuc3BvcnQtdmlldyI7aToyNDc7czoxMjoidHJ1Y2stYWNjZXNzIjtpOjI0ODtzOjk6InRydWNrLWFkZCI7aToyNDk7czoxNzoidHJ1Y2stYnVsay1kZWxldGUiO2k6MjUwO3M6MTI6InRydWNrLWRlbGV0ZSI7aToyNTE7czoxMDoidHJ1Y2stZWRpdCI7aToyNTI7czoxMjoidHJ1Y2stcmVwb3J0IjtpOjI1MztzOjExOiJ1bml0LWFjY2VzcyI7aToyNTQ7czo4OiJ1bml0LWFkZCI7aToyNTU7czoxNjoidW5pdC1idWxrLWRlbGV0ZSI7aToyNTY7czoxMToidW5pdC1kZWxldGUiO2k6MjU3O3M6OToidW5pdC1lZGl0IjtpOjI1ODtzOjExOiJ1bml0LXJlcG9ydCI7aToyNTk7czoxMToidXNlci1hY2Nlc3MiO2k6MjYwO3M6ODoidXNlci1hZGQiO2k6MjYxO3M6MTY6InVzZXItYnVsay1kZWxldGUiO2k6MjYyO3M6MTE6InVzZXItZGVsZXRlIjtpOjI2MztzOjk6InVzZXItZWRpdCI7aToyNjQ7czoxMToidXNlci1yZXBvcnQiO2k6MjY1O3M6OToidXNlci12aWV3IjtpOjI2NjtzOjE0OiJ2b3VjaGVyLWFjY2VzcyI7aToyNjc7czoxMToidm91Y2hlci1hZGQiO2k6MjY4O3M6MTQ6InZvdWNoZXItZGVsZXRlIjtpOjI2OTtzOjEyOiJ2b3VjaGVyLWVkaXQiO2k6MjcwO3M6MjE6InZvdWNoZXItc3RhdHVzLWNoYW5nZSI7aToyNzE7czoxNDoidm91Y2hlci11cGRhdGUiO2k6MjcyO3M6MTI6InZvdWNoZXItdmlldyI7aToyNzM7czoxNjoid2FyZWhvdXNlLWFjY2VzcyI7aToyNzQ7czoxMzoid2FyZWhvdXNlLWFkZCI7aToyNzU7czoyMToid2FyZWhvdXNlLWJ1bGstZGVsZXRlIjtpOjI3NjtzOjE2OiJ3YXJlaG91c2UtZGVsZXRlIjtpOjI3NztzOjE0OiJ3YXJlaG91c2UtZWRpdCI7aToyNzg7czoxNjoid2FyZWhvdXNlLXJlcG9ydCI7fXM6OToidXNlcl9tZW51IjtPOjI2OiJUeXBpQ01TXE5lc3RhYmxlQ29sbGVjdGlvbiI6ODp7czo4OiIAKgB0b3RhbCI7aToyNztzOjE1OiIAKgBwYXJlbnRDb2x1bW4iO3M6OToicGFyZW50X2lkIjtzOjMzOiIAKgByZW1vdmVJdGVtc1dpdGhNaXNzaW5nQW5jZXN0b3IiO2I6MTtzOjE0OiIAKgBpbmRlbnRDaGFycyI7czo4OiLCoMKgwqDCoCI7czoxNToiACoAY2hpbGRyZW5OYW1lIjtzOjU6Iml0ZW1zIjtz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dGgiO2E6MDp7fXM6MTI6IgAqAHdpdGhDb3VudCI7YTowOnt9czoxOToicHJldmVudHNMYXp5TG9hZGluZyI7YjowO3M6MTA6IgAqAHBlclBhZ2UiO2k6MTU7czo2OiJleGlzdHMiO2I6MTtzOjE4OiJ3YXNSZWNlbnRseUNyZWF0ZWQiO2I6MDtzOjI4OiIAKgBlc2NhcGVXaGVuQ2FzdGluZ1RvU3RyaW5nIjtiOjA7czoxMzoiACoAYXR0cmlidXRlcyI7YToxMjp7czoyOiJpZCI7aToyNDI7czo3OiJtZW51X2lkIjtpOjE7czo0OiJ0eXBlIjtzOjE6IjIiO3M6MTE6Im1vZHVsZV9uYW1lIjtzOjE5OiJNYW5hZ2UgRGlzdHJpYnV0aW9uIjtzOjEzOiJkaXZpZGVyX3RpdGxlIjtOO3M6MTA6Imljb25fY2xhc3MiO047czozOiJ1cmwiO3M6MTI6ImRpc3RyaWJ1dGlvbiI7czo1OiJvcmRlciI7aToxO3M6OToicGFyZW50X2lkIjtpOjc4O3M6NjoidGFyZ2V0IjtzOjU6Il9zZWxmIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDIzLTAxLTA1IDEzOjA2OjU0IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDIzLTAxLTEyIDA2OjQ2OjE4Ijt9czoxMToiACoAb3JpZ2luYWwiO2E6MTI6e3M6MjoiaWQiO2k6MjQyO3M6NzoibWVudV9pZCI7aToxO3M6NDoidHlwZSI7czoxOiIyIjtzOjExOiJtb2R1bGVfbmFtZSI7czoxOToiTWFuYWdlIERpc3RyaWJ1dGlvbiI7czoxMzoiZGl2aWRlcl90aXRsZSI7TjtzOjEwOiJpY29uX2NsYXNzIjtOO3M6MzoidXJsIjtzOjEyOiJkaXN0cmlidXRpb24iO3M6NToib3JkZXIiO2k6MTtzOjk6InBhcmVudF9pZCI7aTo3ODtzOjY6InRhcmdldCI7czo1OiJfc2VsZiI7czoxMDoiY3JlYXRlZF9hdCI7czoxOToiMjAyMy0wMS0wNSAxMzowNjo1NCI7czoxMDoidXBkYXRlZF9hdCI7czoxOToiMjAyMy0wMS0xMiAwNjo0NjoxOCI7fXM6MTA6IgAqAGNoYW5nZXMiO2E6MDp7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AGtleVR5cGUiO3M6MzoiaW50IjtzOjEyOiJpbmNyZW1lbnRpbmciO2I6MTtzOjc6IgAqAHdpdGgiO2E6MDp7fXM6MTI6IgAqAHdpdGhDb3VudCI7YTowOnt9czoxOToicHJldmVudHNMYXp5TG9hZGluZyI7YjowO3M6MTA6IgAqAHBlclBhZ2UiO2k6MTU7czo2OiJleGlzdHMiO2I6MTtzOjE4OiJ3YXNSZWNlbnRseUNyZWF0ZWQiO2I6MDtzOjI4OiIAKgBlc2NhcGVXaGVuQ2FzdGluZ1RvU3RyaW5nIjtiOjA7czoxMzoiACoAYXR0cmlidXRlcyI7YToxMjp7czoyOiJpZCI7aToyNDA7czo3OiJtZW51X2lkIjtpOjE7czo0OiJ0eXBlIjtzOjE6IjIiO3M6MTE6Im1vZHVsZV9uYW1lIjtzOjEyOiJNYW5hZ2UgUGFydHkiO3M6MTM6ImRpdmlkZXJfdGl0bGUiO047czoxMDoiaWNvbl9jbGFzcyI7TjtzOjM6InVybCI7czo1OiJwYXJ0eSI7czo1OiJvcmRlciI7aToxO3M6OToicGFyZW50X2lkIjtpOjIzOTtzOjY6InRhcmdldCI7czo1OiJfc2VsZiI7czoxMDoiY3JlYXRlZF9hdCI7czoxOToiMjAyMi0xMi0yOCAwNjo0OTo0OSI7czoxMDoidXBkYXRlZF9hdCI7czoxOToiMjAyMi0xMi0yOCAwNjo0OTo1MiI7fXM6MTE6IgAqAG9yaWdpbmFsIjthOjEyOntzOjI6ImlkIjtpOjI0MDtzOjc6Im1lbnVfaWQiO2k6MTtzOjQ6InR5cGUiO3M6MToiMiI7czoxMToibW9kdWxlX25hbWUiO3M6MTI6Ik1hbmFnZSBQYXJ0eSI7czoxMzoiZGl2aWRlcl90aXRsZSI7TjtzOjEwOiJpY29uX2NsYXNzIjtOO3M6MzoidXJsIjtzOjU6InBhcnR5IjtzOjU6Im9yZGVyIjtpOjE7czo5OiJwYXJlbnRfaWQiO2k6MjM5O3M6NjoidGFyZ2V0IjtzOjU6Il9zZWxmIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDIyLTEyLTI4IDA2OjQ5OjQ5IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDIyLTEyLTI4IDA2OjQ5OjUy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e31zOjE5OiJwcmV2ZW50c0xhenlMb2FkaW5nIjtiOjA7czoxMDoiACoAcGVyUGFnZSI7aToxNTtzOjY6ImV4aXN0cyI7YjoxO3M6MTg6Indhc1JlY2VudGx5Q3JlYXRlZCI7YjowO3M6Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDtzOjEzOiIAKgBhdHRyaWJ1dGVzIjthOjEyOntzOjI6ImlkIjtpOjI0NTtzOjc6Im1lbnVfaWQiO2k6MTtzOjQ6InR5cGUiO3M6MToiMiI7czoxMToibW9kdWxlX25hbWUiO3M6MTM6IlRlbmFudCBSZXR1cm4iO3M6MTM6ImRpdmlkZXJfdGl0bGUiO047czoxMDoiaWNvbl9jbGFzcyI7TjtzOjM6InVybCI7czoyMToidGVuYW50LXJldHVybi1wcm9kdWN0IjtzOjU6Im9yZGVyIjtpOjM7czo5OiJwYXJlbnRfaWQiO2k6MjM4O3M6NjoidGFyZ2V0IjtzOjU6Il9zZWxmIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDIzLTAxLTE2IDA2OjE4OjM0IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDIzLTAxLTE4IDEwOjA4OjM3Ijt9czoxMToiACoAb3JpZ2luYWwiO2E6MTI6e3M6MjoiaWQiO2k6MjQ1O3M6NzoibWVudV9pZCI7aToxO3M6NDoidHlwZSI7czoxOiIyIjtzOjExOiJtb2R1bGVfbmFtZSI7czoxMzoiVGVuYW50IFJldHVybiI7czoxMzoiZGl2aWRlcl90aXRsZSI7TjtzOjEwOiJpY29uX2NsYXNzIjtOO3M6MzoidXJsIjtzOjIxOiJ0ZW5hbnQtcmV0dXJuLXByb2R1Y3QiO3M6NToib3JkZXIiO2k6MztzOjk6InBhcmVudF9pZCI7aToyMzg7czo2OiJ0YXJnZXQiO3M6NToiX3NlbGYiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjMtMDEtMTYgMDY6MTg6MzQiO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjMtMDEtMTggMTA6MDg6MzciO31zOjEwOiIAKgBjaGFuZ2VzIjthOjA6e31zOjg6IgAqAGNhc3RzIjthOjA6e31zOjE3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', 1679303400);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'title', 'Soha', NULL, NULL),
(2, 'email', 'iar@b2gsoft.com', NULL, NULL),
(3, 'contact_no', '+8801868890689', NULL, NULL),
(4, 'address', '1st Floor, House#44, Road#8/A, Nikunja-1, Dhaka', NULL, NULL),
(5, 'logo', 'WhatsApp-Image-2023-03-15-at-4.25.29-PM-938266.png', NULL, NULL),
(6, 'favicon', 'WhatsApp-Image-2023-03-15-at-4.25.29-PM-251260.png', NULL, NULL),
(7, 'currency_code', 'BDT', NULL, NULL),
(8, 'currency_symbol', 'Tk', NULL, NULL),
(9, 'currency_position', '2', NULL, NULL),
(10, 'invoice_prefix', 'INV-', NULL, NULL),
(11, 'invoice_number', '1001', NULL, NULL),
(12, 'timezone', 'Asia/Dhaka', NULL, NULL),
(13, 'date_format', 'd-M-Y', NULL, NULL),
(14, 'mail_mailer', 'smtp', NULL, NULL),
(15, 'mail_host', '', NULL, NULL),
(16, 'mail_port', '', NULL, NULL),
(17, 'mail_username', '', NULL, NULL),
(18, 'mail_password', '', NULL, NULL),
(19, 'mail_encryption', '', NULL, NULL),
(20, 'mail_from_name', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `receive_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT ' 1 = Active , 2 = InActive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_warehouse_products`
--

CREATE TABLE `stock_transfer_warehouse_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_transfer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `scale` double NOT NULL,
  `qty` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_delivery_products`
--

CREATE TABLE `tenant_delivery_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT ' 1 = Active , 2 = InActive',
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_delivery_product_lists`
--

CREATE TABLE `tenant_delivery_product_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_delivery_product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT '0',
  `scale` double DEFAULT '0',
  `del_qty` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_productions`
--

CREATE TABLE `tenant_productions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `mill_id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_raw_scale` double DEFAULT NULL,
  `total_merge_scale` double DEFAULT NULL,
  `total_use_product_qty` double DEFAULT NULL,
  `total_milling` double DEFAULT NULL,
  `total_expense` double DEFAULT NULL,
  `total_delivery_scale` double DEFAULT NULL,
  `total_stock_scale` double DEFAULT NULL,
  `production_status` enum('1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Pending , 2 = Cancel , 3 = Processing , 4 = Finished',
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_production_deliveries`
--

CREATE TABLE `tenant_production_deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_production_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `total_delivery_qty` double DEFAULT NULL,
  `total_delivery_scale` double DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_production_delivery_products`
--

CREATE TABLE `tenant_production_delivery_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `t_p_d_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT NULL,
  `scale` double DEFAULT NULL,
  `del_qty` double DEFAULT NULL,
  `use_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `use_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `use_qty` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_production_expenses`
--

CREATE TABLE `tenant_production_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_production_id` bigint(20) UNSIGNED NOT NULL,
  `expense_item_id` bigint(20) UNSIGNED NOT NULL,
  `expense_cost` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_production_merge_products`
--

CREATE TABLE `tenant_production_merge_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_production_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` double DEFAULT NULL,
  `qty` double DEFAULT NULL,
  `scale` double DEFAULT NULL,
  `mer_qty` double DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `milling` double DEFAULT NULL,
  `type` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Milling , 2 = Delivery , 3 = Stock',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_production_products`
--

CREATE TABLE `tenant_production_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date DEFAULT NULL,
  `tenant_production_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT NULL,
  `scale` double DEFAULT NULL,
  `production_qty` double DEFAULT NULL,
  `use_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `use_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `use_qty` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_production_raw_products`
--

CREATE TABLE `tenant_production_raw_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_production_id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT NULL,
  `use_qty` double DEFAULT NULL,
  `scale` double DEFAULT NULL,
  `use_scale` double DEFAULT NULL,
  `pro_qty` double DEFAULT NULL,
  `use_pro_qty` double DEFAULT NULL,
  `milling` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_receive_products`
--

CREATE TABLE `tenant_receive_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT ' 1 = Active , 2 = InActive',
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_receive_product_lists`
--

CREATE TABLE `tenant_receive_product_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_receive_product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT '0',
  `scale` double DEFAULT '0',
  `rec_qty` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_return_products`
--

CREATE TABLE `tenant_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '1 = Active , 2 = InActive',
  `note` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_return_product_lists`
--

CREATE TABLE `tenant_return_product_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_return_product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double DEFAULT '0',
  `scale` double DEFAULT '0',
  `ret_qty` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_warehouse_products`
--

CREATE TABLE `tenant_warehouse_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` double NOT NULL,
  `scale` double DEFAULT '0',
  `tenant_product_type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Raw , 2 = Finish',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chart_of_head_id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `narration` longtext COLLATE utf8mb4_unicode_ci,
  `debit` double DEFAULT NULL,
  `credit` double DEFAULT NULL,
  `status` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT ' 1 = Approve , 2 = Reject , 3 = Pending',
  `is_opening` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '1=Yes, 2=No',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `chart_of_head_id`, `date`, `voucher_no`, `voucher_type`, `narration`, `debit`, `credit`, `status`, `is_opening`, `created_by`, `modified_by`, `created_at`, `updated_at`) VALUES
(1, 56, '2023-03-19', 'MILL-ASSET-PRICE-1679216533308', 'MILL-ASSET-PRICE', 'Mill Asset Price', 0, 0, '1', '1', 'Super Admin', NULL, '2023-03-19 03:02:13', '2023-03-19 03:02:13'),
(2, 57, '2023-03-19', 'MILL-ASSET-PRICE-1679216551020', 'MILL-ASSET-PRICE', 'Mill Asset Price', 0, 0, '1', '1', 'Super Admin', NULL, '2023-03-19 03:02:31', '2023-03-19 03:02:31'),
(3, 58, '2023-03-19', 'MILL-ASSET-PRICE-1679216568605', 'MILL-ASSET-PRICE', 'Mill Asset Price', 0, 0, '1', '1', 'Super Admin', NULL, '2023-03-19 03:02:48', '2023-03-19 03:02:48'),
(4, 60, '2023-03-19', 'OPENING-BALANCE-1679221210638', 'OPENING-BALANCE', 'রায়হান ট্রেডার্স Payable Opening Balance', 0, 2111821, '1', '1', 'Super Admin', NULL, '2023-03-19 04:20:10', '2023-03-19 04:20:10'),
(5, 62, '2023-03-19', 'OPENING-BALANCE-1679221265444', 'OPENING-BALANCE', 'সায়মা ট্রেডার্স Payable Opening Balance', 0, 1644765, '1', '1', 'Super Admin', NULL, '2023-03-19 04:21:05', '2023-03-19 04:21:05'),
(6, 64, '2023-03-19', 'OPENING-BALANCE-1679221308711', 'OPENING-BALANCE', 'পিংকি ট্রেডার্স Payable Opening Balance', 0, 3362757, '1', '1', 'Super Admin', NULL, '2023-03-19 04:21:48', '2023-03-19 04:21:48'),
(7, 66, '2023-03-19', 'OPENING-BALANCE-1679221380223', 'OPENING-BALANCE', 'রানা ট্রেডর্স Payable Opening Balance', 0, 1164182, '1', '1', 'Super Admin', NULL, '2023-03-19 04:23:00', '2023-03-19 04:23:00'),
(8, 68, '2023-03-19', 'OPENING-BALANCE-1679221434724', 'OPENING-BALANCE', 'লাবন্য ট্রেডার্স Payable Opening Balance', 0, 289714, '1', '1', 'Super Admin', NULL, '2023-03-19 04:23:54', '2023-03-19 04:23:54'),
(9, 70, '2023-03-19', 'OPENING-BALANCE-1679221501378', 'OPENING-BALANCE', 'করিম কাদের ট্রেডার্স Payable Opening Balance', 0, 1439257, '1', '1', 'Super Admin', NULL, '2023-03-19 04:25:01', '2023-03-19 04:25:01'),
(10, 72, '2023-03-19', 'OPENING-BALANCE-1679221555833', 'OPENING-BALANCE', 'নূরী ট্রেডার্স Payable Opening Balance', 0, 1329294, '1', '1', 'Super Admin', NULL, '2023-03-19 04:25:55', '2023-03-19 04:25:55'),
(11, 73, '2023-03-19', 'OPENING-BALANCE-1679221604360', 'OPENING-BALANCE', 'সাঈদ ট্রেডার্স Receivable Opening Balance', 105305, 0, '1', '1', 'Super Admin', NULL, '2023-03-19 04:26:44', '2023-03-19 04:26:44'),
(12, 76, '2023-03-19', 'OPENING-BALANCE-1679221653856', 'OPENING-BALANCE', 'মোশারফ ট্রেডার্স Payable Opening Balance', 0, 2437816, '1', '1', 'Super Admin', NULL, '2023-03-19 04:27:33', '2023-03-19 04:27:33'),
(13, 78, '2023-03-19', 'OPENING-BALANCE-1679221809181', 'OPENING-BALANCE', 'লাকী এন্টারপ্রাইজ Payable Opening Balance', 0, 61944, '1', '1', 'Super Admin', NULL, '2023-03-19 04:30:09', '2023-03-19 04:30:09'),
(14, 80, '2023-03-19', 'OPENING-BALANCE-1679222006203', 'OPENING-BALANCE', 'জাকির ট্রেডার্স Payable Opening Balance', 0, 2221887, '1', '1', 'Super Admin', NULL, '2023-03-19 04:33:26', '2023-03-19 04:33:26'),
(15, 82, '2023-03-19', 'OPENING-BALANCE-1679222101836', 'OPENING-BALANCE', 'সিয়াম ট্রেডার্স Payable Opening Balance', 0, 839111, '1', '1', 'Super Admin', NULL, '2023-03-19 04:35:01', '2023-03-19 04:35:01'),
(16, 84, '2023-03-19', 'OPENING-BALANCE-1679222202720', 'OPENING-BALANCE', 'নুরজাহান ট্রেডার্স Payable Opening Balance', 0, 1659818, '1', '1', 'Super Admin', NULL, '2023-03-19 04:36:42', '2023-03-19 04:36:42'),
(17, 106, '2023-03-19', 'OPENING-BALANCE-1679223676292', 'OPENING-BALANCE', 'সোহা ফুড ইন্ডাষ্ট্রিজ Receivable Opening Balance', 11568622, 0, '1', '1', 'Super Admin', NULL, '2023-03-19 05:01:16', '2023-03-19 05:01:16'),
(18, 108, '2023-03-19', 'OPENING-BALANCE-1679223736296', 'OPENING-BALANCE', 'ভাই ভাই অটো রাইস মিল Receivable Opening Balance', 95101082, 0, '1', '1', 'Super Admin', NULL, '2023-03-19 05:02:16', '2023-03-19 05:02:16'),
(19, 110, '2023-03-19', 'OPENING-BALANCE-1679223796592', 'OPENING-BALANCE', 'দিনাজপুর ইন্ডাষ্ট্রিজ Receivable Opening Balance', 174708518, 0, '1', '1', 'Super Admin', NULL, '2023-03-19 05:03:16', '2023-03-19 05:03:16'),
(20, 112, '2023-03-19', 'OPENING-BALANCE-1679223882583', 'OPENING-BALANCE', 'আল-তুবা গ্লোবাল লিমিটেড Receivable Opening Balance', 2850900, 0, '1', '1', 'Super Admin', NULL, '2023-03-19 05:04:42', '2023-03-19 05:04:42'),
(21, 114, '2023-03-19', 'OPENING-BALANCE-1679223952635', 'OPENING-BALANCE', 'সোহা পরিবহন আয় ব্যায় Receivable Opening Balance', 8972456, 0, '1', '1', 'Super Admin', NULL, '2023-03-19 05:05:52', '2023-03-19 05:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `transports`
--

CREATE TABLE `transports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `truck_id` bigint(20) UNSIGNED NOT NULL,
  `party_type` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT ' 1 = General , 2 = Walking',
  `party_id` bigint(20) UNSIGNED DEFAULT NULL,
  `party_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rent_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rent_amount` double DEFAULT NULL,
  `total_expense` double DEFAULT NULL,
  `income` double DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '3' COMMENT ' 1 = Approve , 2 = Reject , 3 = Pending',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transport_details`
--

CREATE TABLE `transport_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transport_id` bigint(20) UNSIGNED NOT NULL,
  `expense_item_id` bigint(20) UNSIGNED NOT NULL,
  `amount` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trucks`
--

CREATE TABLE `trucks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `truck_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_price` double DEFAULT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_unit` int(10) UNSIGNED DEFAULT NULL,
  `operator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '*',
  `operation_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '1',
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=active,2=inactive',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit_code`, `unit_name`, `base_unit`, `operator`, `operation_value`, `status`, `created_by`, `modified_by`, `created_at`, `updated_at`) VALUES
(1, 'kg', 'Kilogram', NULL, '*', '1', '1', 'Super Admin', 'Super Admin', '2021-03-27 03:27:57', '2022-04-10 01:07:04'),
(25, 'kg', '75', 1, '*', '1', '1', 'Super Admin', NULL, '2022-12-26 02:50:08', '2022-12-26 02:50:08'),
(27, 'kg', '40', 1, '*', '1', '1', 'Super Admin', NULL, '2022-12-26 02:50:40', '2022-12-26 02:50:40'),
(28, 'kg', '25', 1, '*', '1', '1', 'Super Admin', NULL, '2022-12-26 02:50:59', '2022-12-26 02:50:59'),
(29, 'kg', '1', 1, '*', '1', '1', 'Super Admin', NULL, '2022-12-26 02:55:10', '2022-12-26 02:55:10'),
(30, 'pcs', 'Pics', NULL, '*', '1', '1', 'Super Admin', NULL, '2022-12-26 03:00:10', '2022-12-26 03:00:10'),
(31, 'kg', '50', 1, '*', '1', '1', 'Super Admin', NULL, '2022-12-26 23:58:11', '2022-12-26 23:58:11'),
(32, 'kg', '60', 1, '*', '1', '1', 'Super Admin', NULL, '2023-01-16 05:29:35', '2023-01-16 05:29:35'),
(33, 'pcs', '1', 30, '*', '1', '1', 'Super Admin', NULL, '2023-02-01 06:52:03', '2023-02-01 06:52:03'),
(34, 'kg', '70', 1, '*', '1', '1', 'Super Admin', NULL, '2023-03-06 06:31:27', '2023-03-06 06:31:27'),
(35, 'kg', '55', 1, '*', '1', '1', 'Super Admin', NULL, '2023-03-11 00:27:14', '2023-03-11 00:27:14'),
(36, 'kg', '84', 1, '*', '1', '1', 'Super Admin', NULL, '2023-03-19 03:08:35', '2023-03-19 03:08:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1=Male,2=Female,3=Other',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `deletable` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '1=No, 2=Yes',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `username`, `email`, `phone`, `avatar`, `gender`, `password`, `status`, `deletable`, `created_by`, `modified_by`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Super Admin', 'SuperAdmin', 'superadmin@mail.com', '01521225987', 'Soha-Logo-433467.jpg', '1', '$2y$10$oyBQnkvC8WZEQffhpEEsyeZPPqXEHmpaErfi62fsfyAw.UMwIzS/m', '1', '1', 'Super Admin', NULL, 'tFZDVmW98HEUQYwieLNMBtbbv82bKYdtFkWSd5gwmJLChzoWQtR6GySToMuZ', '2021-03-26 23:49:24', '2023-03-19 05:11:19'),
(2, 2, 'Admin', 'Admin', 'admin@mail.com', '01711154960', 'B041BD6A-9488-4A61-B7BF-FDBF1B12613B-672700-543843.jpeg', '1', '$2y$10$yXReCHuaUY2.bshbsICFU.bFuyZn4ENXZhrYnQVmMGPyHzqEK77n.', '1', '1', 'Admin', 'Super Admin', 'zCHzFg9B2YAfI95sHl2UIx3Wrq5nFt3HkOAddlpQ7Ed4Eg8GtaDriO0KcZLH', '2021-03-26 23:49:24', '2022-09-12 04:54:30');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 2=Inactive',
  `deletable` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '1=No, 2=Yes',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `phone`, `email`, `address`, `status`, `deletable`, `created_by`, `modified_by`, `created_at`, `updated_at`) VALUES
(1, 'ভাই ভাই অটো', '01986348224', 'soha@gmail.com', 'dinajpur', '1', '1', 'Admin', 'Super Admin', '2021-04-11 11:18:33', '2023-03-11 00:22:23'),
(2, 'সোহা অটো ইউনিট-2', '01986348224', 'soha@gmail.com', 'dinajpur', '1', '1', 'Admin', 'Super Admin', '2021-04-11 11:20:15', '2023-03-11 00:22:07'),
(10, 'সোহা অটো ইউনিট-1', '01986348224', 'soha@gmail.com', 'dinajpur', '1', '1', 'Super Admin', 'Super Admin', '2022-02-28 23:30:58', '2023-03-11 00:21:56'),
(11, 'Unit Four', '01986348229', NULL, NULL, '1', '1', 'Super Admin', NULL, '2023-02-12 04:30:15', '2023-02-12 04:30:15'),
(12, 'ক্রাসিং পার্টি', '01986348225', 'tenant@gmail.com', 'Fakir Hat', '1', '1', 'Super Admin', 'Super Admin', '2023-02-16 03:38:50', '2023-03-11 00:22:55');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_product`
--

CREATE TABLE `warehouse_product` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `scale` double DEFAULT '0',
  `qty` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `banks_account_number_unique` (`account_number`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD UNIQUE KEY `cache_key_unique` (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chart_of_heads`
--
ALTER TABLE `chart_of_heads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chart_of_heads_party_id_foreign` (`party_id`),
  ADD KEY `chart_of_heads_bank_id_foreign` (`bank_id`),
  ADD KEY `chart_of_heads_mobile_bank_id_foreign` (`mobile_bank_id`),
  ADD KEY `chart_of_heads_expense_item_id_foreign` (`expense_item_id`),
  ADD KEY `chart_of_heads_tenant_id_foreign` (`tenant_id`),
  ADD KEY `chart_of_heads_mill_id_foreign` (`mill_id`),
  ADD KEY `chart_of_heads_labor_head_id_foreign` (`labor_head_id`),
  ADD KEY `chart_of_heads_truck_id_foreign` (`truck_id`);

--
-- Indexes for table `distributions`
--
ALTER TABLE `distributions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `distribution_products`
--
ALTER TABLE `distribution_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `distribution_products_distribution_id_foreign` (`distribution_id`),
  ADD KEY `distribution_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `distribution_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_items_name_unique` (`name`);

--
-- Indexes for table `labor_bills`
--
ALTER TABLE `labor_bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `labor_bills_labor_head_id_foreign` (`labor_head_id`),
  ADD KEY `labor_bills_labor_bill_rate_id_foreign` (`labor_bill_rate_id`);

--
-- Indexes for table `labor_bill_rates`
--
ALTER TABLE `labor_bill_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `labor_bill_rates_name_unique` (`name`);

--
-- Indexes for table `labor_heads`
--
ALTER TABLE `labor_heads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `labor_heads_mobile_unique` (`mobile`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_loan_categories_id_foreign` (`loan_categories_id`),
  ADD KEY `loans_bank_id_foreign` (`bank_id`);

--
-- Indexes for table `loan_categories`
--
ALTER TABLE `loan_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menus_menu_name_unique` (`menu_name`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mills`
--
ALTER TABLE `mills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mobile_banks`
--
ALTER TABLE `mobile_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modules_menu_id_foreign` (`menu_id`);

--
-- Indexes for table `module_role`
--
ALTER TABLE `module_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_role_module_id_foreign` (`module_id`),
  ADD KEY `module_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `opening_stocks`
--
ALTER TABLE `opening_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opening_stocks_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `opening_stocks_product_id_foreign` (`product_id`);

--
-- Indexes for table `parties`
--
ALTER TABLE `parties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`),
  ADD KEY `permissions_module_id_foreign` (`module_id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_role_permission_id_foreign` (`permission_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productions_mill_id_foreign` (`mill_id`);

--
-- Indexes for table `production_expenses`
--
ALTER TABLE `production_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_expenses_production_id_foreign` (`production_id`),
  ADD KEY `production_expenses_expense_item_id_foreign` (`expense_item_id`);

--
-- Indexes for table `production_products`
--
ALTER TABLE `production_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_products_production_id_foreign` (`production_id`),
  ADD KEY `production_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `production_products_product_id_foreign` (`product_id`),
  ADD KEY `production_products_use_warehouse_id_foreign` (`use_warehouse_id`),
  ADD KEY `production_products_use_product_id_foreign` (`use_product_id`);

--
-- Indexes for table `production_raw_products`
--
ALTER TABLE `production_raw_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_raw_products_production_id_foreign` (`production_id`),
  ADD KEY `production_raw_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `production_raw_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `production_sales`
--
ALTER TABLE `production_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_sales_production_id_foreign` (`production_id`),
  ADD KEY `production_sales_party_id_foreign` (`party_id`),
  ADD KEY `production_sales_account_id_foreign` (`account_id`);

--
-- Indexes for table `production_sale_products`
--
ALTER TABLE `production_sale_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_sale_products_production_sale_id_foreign` (`production_sale_id`),
  ADD KEY `production_sale_products_product_id_foreign` (`product_id`),
  ADD KEY `production_sale_products_use_warehouse_id_foreign` (`use_warehouse_id`),
  ADD KEY `production_sale_products_use_product_id_foreign` (`use_product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_product_code_unique` (`product_code`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_unit_id_foreign` (`unit_id`),
  ADD KEY `products_opening_warehouse_id_foreign` (`opening_warehouse_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_party_id_foreign` (`party_id`),
  ADD KEY `purchases_account_id_foreign` (`account_id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_products_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_product_receives`
--
ALTER TABLE `purchase_product_receives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_product_receives_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_product_receives_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_product_receives_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_product_returns`
--
ALTER TABLE `purchase_product_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_product_returns_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_product_returns_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_product_returns_product_id_foreign` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_role_name_unique` (`role_name`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_party_id_foreign` (`party_id`),
  ADD KEY `sales_account_id_foreign` (`account_id`);

--
-- Indexes for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_products_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sale_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `sale_product_deliveries`
--
ALTER TABLE `sale_product_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_product_deliveries_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_product_deliveries_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sale_product_deliveries_product_id_foreign` (`product_id`);

--
-- Indexes for table `sale_product_returns`
--
ALTER TABLE `sale_product_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_product_returns_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_product_returns_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sale_product_returns_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_name_unique` (`name`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfers_transfer_warehouse_id_foreign` (`transfer_warehouse_id`),
  ADD KEY `stock_transfers_receive_warehouse_id_foreign` (`receive_warehouse_id`);

--
-- Indexes for table `stock_transfer_warehouse_products`
--
ALTER TABLE `stock_transfer_warehouse_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfer_warehouse_products_stock_transfer_id_foreign` (`stock_transfer_id`),
  ADD KEY `stock_transfer_warehouse_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenants_mobile_unique` (`mobile`);

--
-- Indexes for table `tenant_delivery_products`
--
ALTER TABLE `tenant_delivery_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenant_delivery_products_invoice_no_unique` (`invoice_no`),
  ADD KEY `tenant_delivery_products_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `tenant_delivery_product_lists`
--
ALTER TABLE `tenant_delivery_product_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_delivery_product_lists_tenant_delivery_product_id_foreign` (`tenant_delivery_product_id`),
  ADD KEY `tenant_delivery_product_lists_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `tenant_delivery_product_lists_product_id_foreign` (`product_id`);

--
-- Indexes for table `tenant_productions`
--
ALTER TABLE `tenant_productions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenant_productions_invoice_no_unique` (`invoice_no`),
  ADD KEY `tenant_productions_tenant_id_foreign` (`tenant_id`),
  ADD KEY `tenant_productions_mill_id_foreign` (`mill_id`);

--
-- Indexes for table `tenant_production_deliveries`
--
ALTER TABLE `tenant_production_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenant_production_deliveries_invoice_no_unique` (`invoice_no`),
  ADD KEY `tenant_production_deliveries_tenant_production_id_foreign` (`tenant_production_id`);

--
-- Indexes for table `tenant_production_delivery_products`
--
ALTER TABLE `tenant_production_delivery_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_production_delivery_products_t_p_d_id_foreign` (`t_p_d_id`),
  ADD KEY `tenant_production_delivery_products_product_id_foreign` (`product_id`),
  ADD KEY `tenant_production_delivery_products_use_warehouse_id_foreign` (`use_warehouse_id`),
  ADD KEY `tenant_production_delivery_products_use_product_id_foreign` (`use_product_id`);

--
-- Indexes for table `tenant_production_expenses`
--
ALTER TABLE `tenant_production_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_production_expenses_tenant_production_id_foreign` (`tenant_production_id`),
  ADD KEY `tenant_production_expenses_expense_item_id_foreign` (`expense_item_id`);

--
-- Indexes for table `tenant_production_merge_products`
--
ALTER TABLE `tenant_production_merge_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_production_merge_products_tenant_production_id_foreign` (`tenant_production_id`),
  ADD KEY `tenant_production_merge_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `tenant_production_merge_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `tenant_production_products`
--
ALTER TABLE `tenant_production_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenant_production_products_invoice_no_unique` (`invoice_no`),
  ADD KEY `tenant_production_products_tenant_production_id_foreign` (`tenant_production_id`),
  ADD KEY `tenant_production_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `tenant_production_products_product_id_foreign` (`product_id`),
  ADD KEY `tenant_production_products_use_warehouse_id_foreign` (`use_warehouse_id`),
  ADD KEY `tenant_production_products_use_product_id_foreign` (`use_product_id`);

--
-- Indexes for table `tenant_production_raw_products`
--
ALTER TABLE `tenant_production_raw_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_production_raw_products_tenant_production_id_foreign` (`tenant_production_id`),
  ADD KEY `tenant_production_raw_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `tenant_production_raw_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `tenant_receive_products`
--
ALTER TABLE `tenant_receive_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenant_receive_products_invoice_no_unique` (`invoice_no`),
  ADD KEY `tenant_receive_products_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `tenant_receive_product_lists`
--
ALTER TABLE `tenant_receive_product_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_receive_product_lists_tenant_receive_product_id_foreign` (`tenant_receive_product_id`),
  ADD KEY `tenant_receive_product_lists_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `tenant_receive_product_lists_product_id_foreign` (`product_id`);

--
-- Indexes for table `tenant_return_products`
--
ALTER TABLE `tenant_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_return_products_tenant_id_foreign` (`tenant_id`);

--
-- Indexes for table `tenant_return_product_lists`
--
ALTER TABLE `tenant_return_product_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_return_product_lists_tenant_return_product_id_foreign` (`tenant_return_product_id`),
  ADD KEY `tenant_return_product_lists_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `tenant_return_product_lists_product_id_foreign` (`product_id`);

--
-- Indexes for table `tenant_warehouse_products`
--
ALTER TABLE `tenant_warehouse_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_warehouse_products_tenant_id_foreign` (`tenant_id`),
  ADD KEY `tenant_warehouse_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `tenant_warehouse_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_chart_of_head_id_foreign` (`chart_of_head_id`);

--
-- Indexes for table `transports`
--
ALTER TABLE `transports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transports_invoice_no_unique` (`invoice_no`),
  ADD KEY `transports_truck_id_foreign` (`truck_id`),
  ADD KEY `transports_party_id_foreign` (`party_id`);

--
-- Indexes for table `transport_details`
--
ALTER TABLE `transport_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transport_details_transport_id_foreign` (`transport_id`),
  ADD KEY `transport_details_expense_item_id_foreign` (`expense_item_id`);

--
-- Indexes for table `trucks`
--
ALTER TABLE `trucks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trucks_truck_no_unique` (`truck_no`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouses_name_unique` (`name`);

--
-- Indexes for table `warehouse_product`
--
ALTER TABLE `warehouse_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_product_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `warehouse_product_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chart_of_heads`
--
ALTER TABLE `chart_of_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `distributions`
--
ALTER TABLE `distributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `distribution_products`
--
ALTER TABLE `distribution_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `labor_bills`
--
ALTER TABLE `labor_bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labor_bill_rates`
--
ALTER TABLE `labor_bill_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labor_heads`
--
ALTER TABLE `labor_heads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_categories`
--
ALTER TABLE `loan_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT for table `mills`
--
ALTER TABLE `mills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mobile_banks`
--
ALTER TABLE `mobile_banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=257;

--
-- AUTO_INCREMENT for table `module_role`
--
ALTER TABLE `module_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT for table `opening_stocks`
--
ALTER TABLE `opening_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `parties`
--
ALTER TABLE `parties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=788;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=850;

--
-- AUTO_INCREMENT for table `productions`
--
ALTER TABLE `productions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_expenses`
--
ALTER TABLE `production_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_products`
--
ALTER TABLE `production_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_raw_products`
--
ALTER TABLE `production_raw_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_sales`
--
ALTER TABLE `production_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_sale_products`
--
ALTER TABLE `production_sale_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_product_receives`
--
ALTER TABLE `purchase_product_receives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_product_returns`
--
ALTER TABLE `purchase_product_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_products`
--
ALTER TABLE `sale_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_deliveries`
--
ALTER TABLE `sale_product_deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_product_returns`
--
ALTER TABLE `sale_product_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_warehouse_products`
--
ALTER TABLE `stock_transfer_warehouse_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_delivery_products`
--
ALTER TABLE `tenant_delivery_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_delivery_product_lists`
--
ALTER TABLE `tenant_delivery_product_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_productions`
--
ALTER TABLE `tenant_productions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_production_deliveries`
--
ALTER TABLE `tenant_production_deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_production_delivery_products`
--
ALTER TABLE `tenant_production_delivery_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_production_expenses`
--
ALTER TABLE `tenant_production_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_production_merge_products`
--
ALTER TABLE `tenant_production_merge_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_production_products`
--
ALTER TABLE `tenant_production_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_production_raw_products`
--
ALTER TABLE `tenant_production_raw_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_receive_products`
--
ALTER TABLE `tenant_receive_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_receive_product_lists`
--
ALTER TABLE `tenant_receive_product_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_return_products`
--
ALTER TABLE `tenant_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_return_product_lists`
--
ALTER TABLE `tenant_return_product_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_warehouse_products`
--
ALTER TABLE `tenant_warehouse_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transports`
--
ALTER TABLE `transports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transport_details`
--
ALTER TABLE `transport_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trucks`
--
ALTER TABLE `trucks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `warehouse_product`
--
ALTER TABLE `warehouse_product`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chart_of_heads`
--
ALTER TABLE `chart_of_heads`
  ADD CONSTRAINT `chart_of_heads_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`),
  ADD CONSTRAINT `chart_of_heads_expense_item_id_foreign` FOREIGN KEY (`expense_item_id`) REFERENCES `expense_items` (`id`),
  ADD CONSTRAINT `chart_of_heads_labor_head_id_foreign` FOREIGN KEY (`labor_head_id`) REFERENCES `labor_heads` (`id`),
  ADD CONSTRAINT `chart_of_heads_mill_id_foreign` FOREIGN KEY (`mill_id`) REFERENCES `mills` (`id`),
  ADD CONSTRAINT `chart_of_heads_mobile_bank_id_foreign` FOREIGN KEY (`mobile_bank_id`) REFERENCES `mobile_banks` (`id`),
  ADD CONSTRAINT `chart_of_heads_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`),
  ADD CONSTRAINT `chart_of_heads_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  ADD CONSTRAINT `chart_of_heads_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`);

--
-- Constraints for table `distribution_products`
--
ALTER TABLE `distribution_products`
  ADD CONSTRAINT `distribution_products_distribution_id_foreign` FOREIGN KEY (`distribution_id`) REFERENCES `distributions` (`id`),
  ADD CONSTRAINT `distribution_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `distribution_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `labor_bills`
--
ALTER TABLE `labor_bills`
  ADD CONSTRAINT `labor_bills_labor_bill_rate_id_foreign` FOREIGN KEY (`labor_bill_rate_id`) REFERENCES `labor_bill_rates` (`id`),
  ADD CONSTRAINT `labor_bills_labor_head_id_foreign` FOREIGN KEY (`labor_head_id`) REFERENCES `labor_heads` (`id`);

--
-- Constraints for table `opening_stocks`
--
ALTER TABLE `opening_stocks`
  ADD CONSTRAINT `opening_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `opening_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `productions`
--
ALTER TABLE `productions`
  ADD CONSTRAINT `productions_mill_id_foreign` FOREIGN KEY (`mill_id`) REFERENCES `mills` (`id`);

--
-- Constraints for table `production_expenses`
--
ALTER TABLE `production_expenses`
  ADD CONSTRAINT `production_expenses_expense_item_id_foreign` FOREIGN KEY (`expense_item_id`) REFERENCES `expense_items` (`id`),
  ADD CONSTRAINT `production_expenses_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`);

--
-- Constraints for table `production_products`
--
ALTER TABLE `production_products`
  ADD CONSTRAINT `production_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `production_products_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`),
  ADD CONSTRAINT `production_products_use_product_id_foreign` FOREIGN KEY (`use_product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `production_products_use_warehouse_id_foreign` FOREIGN KEY (`use_warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `production_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `production_raw_products`
--
ALTER TABLE `production_raw_products`
  ADD CONSTRAINT `production_raw_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `production_raw_products_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`),
  ADD CONSTRAINT `production_raw_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `production_sales`
--
ALTER TABLE `production_sales`
  ADD CONSTRAINT `production_sales_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_heads` (`id`),
  ADD CONSTRAINT `production_sales_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`),
  ADD CONSTRAINT `production_sales_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`);

--
-- Constraints for table `production_sale_products`
--
ALTER TABLE `production_sale_products`
  ADD CONSTRAINT `production_sale_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `production_sale_products_production_sale_id_foreign` FOREIGN KEY (`production_sale_id`) REFERENCES `production_sales` (`id`),
  ADD CONSTRAINT `production_sale_products_use_product_id_foreign` FOREIGN KEY (`use_product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `production_sale_products_use_warehouse_id_foreign` FOREIGN KEY (`use_warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_opening_warehouse_id_foreign` FOREIGN KEY (`opening_warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_heads` (`id`),
  ADD CONSTRAINT `purchases_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`);

--
-- Constraints for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD CONSTRAINT `purchase_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_products_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `purchase_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `purchase_product_receives`
--
ALTER TABLE `purchase_product_receives`
  ADD CONSTRAINT `purchase_product_receives_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_product_receives_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `purchase_product_receives_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `purchase_product_returns`
--
ALTER TABLE `purchase_product_returns`
  ADD CONSTRAINT `purchase_product_returns_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `purchase_product_returns_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `purchase_product_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `chart_of_heads` (`id`),
  ADD CONSTRAINT `sales_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`);

--
-- Constraints for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD CONSTRAINT `sale_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sale_products_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `sale_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `sale_product_deliveries`
--
ALTER TABLE `sale_product_deliveries`
  ADD CONSTRAINT `sale_product_deliveries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sale_product_deliveries_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `sale_product_deliveries_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `sale_product_returns`
--
ALTER TABLE `sale_product_returns`
  ADD CONSTRAINT `sale_product_returns_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sale_product_returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `sale_product_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `stock_transfers_receive_warehouse_id_foreign` FOREIGN KEY (`receive_warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `stock_transfers_transfer_warehouse_id_foreign` FOREIGN KEY (`transfer_warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `stock_transfer_warehouse_products`
--
ALTER TABLE `stock_transfer_warehouse_products`
  ADD CONSTRAINT `stock_transfer_warehouse_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_transfer_warehouse_products_stock_transfer_id_foreign` FOREIGN KEY (`stock_transfer_id`) REFERENCES `stock_transfers` (`id`);

--
-- Constraints for table `tenant_delivery_products`
--
ALTER TABLE `tenant_delivery_products`
  ADD CONSTRAINT `tenant_delivery_products_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`);

--
-- Constraints for table `tenant_delivery_product_lists`
--
ALTER TABLE `tenant_delivery_product_lists`
  ADD CONSTRAINT `tenant_delivery_product_lists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_delivery_product_lists_tenant_delivery_product_id_foreign` FOREIGN KEY (`tenant_delivery_product_id`) REFERENCES `tenant_delivery_products` (`id`),
  ADD CONSTRAINT `tenant_delivery_product_lists_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `tenant_productions`
--
ALTER TABLE `tenant_productions`
  ADD CONSTRAINT `tenant_productions_mill_id_foreign` FOREIGN KEY (`mill_id`) REFERENCES `mills` (`id`),
  ADD CONSTRAINT `tenant_productions_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`);

--
-- Constraints for table `tenant_production_deliveries`
--
ALTER TABLE `tenant_production_deliveries`
  ADD CONSTRAINT `tenant_production_deliveries_tenant_production_id_foreign` FOREIGN KEY (`tenant_production_id`) REFERENCES `tenant_productions` (`id`);

--
-- Constraints for table `tenant_production_delivery_products`
--
ALTER TABLE `tenant_production_delivery_products`
  ADD CONSTRAINT `tenant_production_delivery_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_production_delivery_products_t_p_d_id_foreign` FOREIGN KEY (`t_p_d_id`) REFERENCES `tenant_production_deliveries` (`id`),
  ADD CONSTRAINT `tenant_production_delivery_products_use_product_id_foreign` FOREIGN KEY (`use_product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_production_delivery_products_use_warehouse_id_foreign` FOREIGN KEY (`use_warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `tenant_production_expenses`
--
ALTER TABLE `tenant_production_expenses`
  ADD CONSTRAINT `tenant_production_expenses_expense_item_id_foreign` FOREIGN KEY (`expense_item_id`) REFERENCES `expense_items` (`id`),
  ADD CONSTRAINT `tenant_production_expenses_tenant_production_id_foreign` FOREIGN KEY (`tenant_production_id`) REFERENCES `tenant_productions` (`id`);

--
-- Constraints for table `tenant_production_merge_products`
--
ALTER TABLE `tenant_production_merge_products`
  ADD CONSTRAINT `tenant_production_merge_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_production_merge_products_tenant_production_id_foreign` FOREIGN KEY (`tenant_production_id`) REFERENCES `tenant_productions` (`id`),
  ADD CONSTRAINT `tenant_production_merge_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `tenant_production_products`
--
ALTER TABLE `tenant_production_products`
  ADD CONSTRAINT `tenant_production_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_production_products_tenant_production_id_foreign` FOREIGN KEY (`tenant_production_id`) REFERENCES `tenant_productions` (`id`),
  ADD CONSTRAINT `tenant_production_products_use_product_id_foreign` FOREIGN KEY (`use_product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_production_products_use_warehouse_id_foreign` FOREIGN KEY (`use_warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `tenant_production_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `tenant_production_raw_products`
--
ALTER TABLE `tenant_production_raw_products`
  ADD CONSTRAINT `tenant_production_raw_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_production_raw_products_tenant_production_id_foreign` FOREIGN KEY (`tenant_production_id`) REFERENCES `tenant_productions` (`id`),
  ADD CONSTRAINT `tenant_production_raw_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `tenant_receive_products`
--
ALTER TABLE `tenant_receive_products`
  ADD CONSTRAINT `tenant_receive_products_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`);

--
-- Constraints for table `tenant_receive_product_lists`
--
ALTER TABLE `tenant_receive_product_lists`
  ADD CONSTRAINT `tenant_receive_product_lists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_receive_product_lists_tenant_receive_product_id_foreign` FOREIGN KEY (`tenant_receive_product_id`) REFERENCES `tenant_receive_products` (`id`),
  ADD CONSTRAINT `tenant_receive_product_lists_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `tenant_return_products`
--
ALTER TABLE `tenant_return_products`
  ADD CONSTRAINT `tenant_return_products_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`);

--
-- Constraints for table `tenant_return_product_lists`
--
ALTER TABLE `tenant_return_product_lists`
  ADD CONSTRAINT `tenant_return_product_lists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_return_product_lists_tenant_return_product_id_foreign` FOREIGN KEY (`tenant_return_product_id`) REFERENCES `tenant_return_products` (`id`),
  ADD CONSTRAINT `tenant_return_product_lists_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `tenant_warehouse_products`
--
ALTER TABLE `tenant_warehouse_products`
  ADD CONSTRAINT `tenant_warehouse_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `tenant_warehouse_products_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  ADD CONSTRAINT `tenant_warehouse_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_chart_of_head_id_foreign` FOREIGN KEY (`chart_of_head_id`) REFERENCES `chart_of_heads` (`id`);

--
-- Constraints for table `transports`
--
ALTER TABLE `transports`
  ADD CONSTRAINT `transports_party_id_foreign` FOREIGN KEY (`party_id`) REFERENCES `parties` (`id`),
  ADD CONSTRAINT `transports_truck_id_foreign` FOREIGN KEY (`truck_id`) REFERENCES `trucks` (`id`);

--
-- Constraints for table `transport_details`
--
ALTER TABLE `transport_details`
  ADD CONSTRAINT `transport_details_expense_item_id_foreign` FOREIGN KEY (`expense_item_id`) REFERENCES `expense_items` (`id`),
  ADD CONSTRAINT `transport_details_transport_id_foreign` FOREIGN KEY (`transport_id`) REFERENCES `transports` (`id`);

--
-- Constraints for table `warehouse_product`
--
ALTER TABLE `warehouse_product`
  ADD CONSTRAINT `warehouse_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `warehouse_product_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
