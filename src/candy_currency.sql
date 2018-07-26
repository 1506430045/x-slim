-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-26 18:43:23
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
-- 表的结构 `candy_currency`
--

CREATE TABLE `candy_currency` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `currency_name` varchar(16) NOT NULL DEFAULT '' COMMENT '货币名称',
  `currency_icon` varchar(128) NOT NULL DEFAULT '' COMMENT '货币图标',
  `currency_description` varchar(512) NOT NULL DEFAULT '' COMMENT '货币描述',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `candy_currency`
--

INSERT INTO `candy_currency` (`id`, `currency_name`, `currency_icon`, `currency_description`, `created_at`, `updated_at`) VALUES
(1, 'TB', 'https://static.m960.cn/TB%402x.png', 'TB是基于ERC-20协议发行的币，是ThinkBit信币交易所发行的平台币，极具独创性，总量210亿，永不增发。TB可以用作 ThinkBit服务的费用支付，包括支付费用、保证金费用支付，以及其他费用。\r\n官方网站：https://thinkbit.com', '2018-07-19 12:02:07', '2018-07-26 09:19:14'),
(2, 'ADE', 'https://static.m960.cn/ADE%402x.png', 'ADE是建立在DMChain上的币，DMChain建立在Cardano（ADA）之上，利用智能合约和数据透明度，为广告业者提供了一种解决方案，无论广告主的规模如何，均可确保交易的安全性、可验证性、可追溯性和易用性。此外，它还消除了广告行业的不信任性和不确定性，并为广告主提供了更明确的市场目标，以及可跟踪和可量化的广告投放结果。\r\n官方网站：https://dmchain.io\r\n', '2018-07-19 12:02:07', '2018-07-26 09:18:46'),
(3, 'CCC', 'https://static.m960.cn/CCC%402x.png', '控银天下（Coindom）是一款区块链投资者的投顾工具，提供深度行情指标，多交易所账户管理、一键搬砖以及资讯、社区等区块链投资相关服务，通过统一极致的操作体验，让小白投资者能够快速上手区块链资产的投资与管理。\r\n官方网站：https://www.coindom.com', '2018-07-19 12:02:07', '2018-07-26 09:19:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candy_currency`
--
ALTER TABLE `candy_currency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `candy_currency`
--
ALTER TABLE `candy_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID', AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
