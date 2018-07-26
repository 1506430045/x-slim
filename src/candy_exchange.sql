-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-26 16:14:54
-- 服务器版本： 5.7.22
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `candy`
--

-- --------------------------------------------------------

--
-- 表的结构 `candy_exchange`
--

CREATE TABLE `candy_exchange` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `pair` varchar(24) NOT NULL DEFAULT '' COMMENT '交易对',
  `currency_id` int(11) DEFAULT '0' COMMENT '货币ID',
  `to_currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '兑换货币ID',
  `exchange_rate` decimal(36,18) NOT NULL DEFAULT '0.000000000000000000' COMMENT '汇率',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `candy_exchange`
--

INSERT INTO `candy_exchange` (`id`, `pair`, `currency_id`, `to_currency_id`, `exchange_rate`, `created_at`, `updated_at`) VALUES
(1, 'tb-tb', 1, 1, '1.000000000000000000', '2018-07-26 06:53:29', '2018-07-26 06:53:29'),
(2, 'ade-tb', 2, 1, '0.001000000000000000', '2018-07-26 06:53:29', '2018-07-26 06:54:06'),
(3, 'ccc-tb', 3, 1, '0.100000000000000000', '2018-07-26 06:53:29', '2018-07-26 06:54:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candy_exchange`
--
ALTER TABLE `candy_exchange`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `candy_exchange`
--
ALTER TABLE `candy_exchange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID', AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
