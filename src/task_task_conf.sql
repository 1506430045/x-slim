-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-28 20:37:03
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
-- 表的结构 `candy_task_conf`
--

CREATE TABLE `candy_task_conf` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `task_name` varchar(12) NOT NULL DEFAULT '' COMMENT '任务名称',
  `task_description` varchar(512) NOT NULL DEFAULT '' COMMENT '任务描述',
  `task_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '任务类型 1基本任务 2高级任务',
  `task_cycle` tinyint(2) NOT NULL DEFAULT '0' COMMENT '任务周期 1每日 2每周 3一次性 4无限制',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '货币种类',
  `currency_name` varchar(16) NOT NULL DEFAULT '' COMMENT '奖励币种',
  `currency_number` decimal(36,18) DEFAULT '0.000000000000000000' COMMENT '奖励数量',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建日期',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='基础任务表';

--
-- 转存表中的数据 `candy_task_conf`
--

INSERT INTO `candy_task_conf` (`id`, `task_name`, `task_description`, `task_type`, `task_cycle`, `currency_id`, `currency_name`, `currency_number`, `created_at`, `updated_at`) VALUES
(1, '每日登录', '每日签到任务，奖励5TB糖果', 1, 1, 1, 'TB', '5.000000000000000000', '2018-07-19 04:31:31', '2018-07-24 12:52:41'),
(2, '七日登录', '每周内连续7天签到可以另外获取15TB糖果', 1, 2, 1, 'TB', '15.000000000000000000', '2018-07-19 04:34:13', '2018-07-24 12:53:09'),
(3, '绑定手机', '绑定手机号码，奖励50TB糖果', 1, 3, 1, 'TB', '50.000000000000000000', '2018-07-19 04:35:44', '2018-07-24 12:53:15'),
(4, '邀请好友', '邀请用户进小程序，奖励100TB糖果', 1, 4, 1, 'TB', '100.000000000000000000', '2018-07-19 04:35:44', '2018-07-24 12:53:24'),
(5, '实名认证', '在官网进行实名认证', 2, 3, 1, 'TB', '100.000000000000000000', '2018-07-19 04:35:44', '2018-07-28 07:36:22'),
(6, '充币', '在官网进行充值', 2, 3, 1, 'TB', '100.000000000000000000', '2018-07-19 04:35:44', '2018-07-28 07:36:22'),
(7, '交易', '在官网进行交易', 2, 3, 1, 'TB', '100.000000000000000000', '2018-07-19 04:35:44', '2018-07-28 07:36:22'),
(8, '下载APP', '下载APP', 2, 3, 1, 'TB', '100.000000000000000000', '2018-07-19 04:35:44', '2018-07-28 07:36:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candy_task_conf`
--
ALTER TABLE `candy_task_conf`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `candy_task_conf`
--
ALTER TABLE `candy_task_conf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID', AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
