-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-26 18:43:14
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
-- 表的结构 `candy_company`
--

CREATE TABLE `candy_company` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `official_website` varchar(32) NOT NULL DEFAULT '' COMMENT '官方网站',
  `wechat` varchar(24) NOT NULL DEFAULT '' COMMENT '微信',
  `wechat_public` varchar(128) NOT NULL DEFAULT '' COMMENT '微信公众号二维码图片',
  `telegram_cn` varchar(24) NOT NULL DEFAULT '' COMMENT 'Telegram中文',
  `telegram_en` varchar(24) NOT NULL DEFAULT '' COMMENT 'Telegram英文',
  `twitter` varchar(24) NOT NULL DEFAULT '' COMMENT '推特',
  `weibo` varchar(24) NOT NULL DEFAULT '' COMMENT '微博',
  `about_us` varchar(512) NOT NULL DEFAULT '' COMMENT '关于我们',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `candy_company`
--

INSERT INTO `candy_company` (`id`, `official_website`, `wechat`, `wechat_public`, `telegram_cn`, `telegram_en`, `twitter`, `weibo`, `about_us`, `created_at`, `updated_at`) VALUES
(1, 'https://www.thinkbitpro.com', 'ThinkBitPro资讯', 'https://static.m960.cn/%E4%BA%8C%E7%BB%B4%E7%A0%81%402x.png', 'ThinkBit Pro(中文群)', 'ThinkBit Pro（English）', 'ThinkBitPro', 'ThinkBitPro资讯', 'ThinkBit作为下一代交易所，将为用户提供高流动性的交易环境、全资产冷储存、多种订单类型、高性能 API 。ThinkBit使用自研的“粒子对撞”交易撮合引擎，处理订单速度达500万订单/秒/交易。ThinkBit 还采用 “绝对零度” 100%冷钱包技术，保护用户资产的安全。\r\n\r\nThinkBit WaterDrop 是一个源自全球的订单簿平台，同时也是主经纪商。这将会为其他交易所提供高流动性，帮助小型交易所拥有健康流动性及更好的交易环境。交易所云服务让区域性分站做到快速自建交易所，在保障高品质产品体验的前提下，有极大的空间做好用户服务。ThinkBit WaterDrop 全球订单簿基于ThinkBit撮合引擎、智能订单路由、绝对零度冷储存系统，打造出一个全球联合订单簿，为交易者及机构提供高流动性及极佳深度的交易体验。', '2018-07-25 06:53:23', '2018-07-26 09:21:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candy_company`
--
ALTER TABLE `candy_company`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `candy_company`
--
ALTER TABLE `candy_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID', AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
