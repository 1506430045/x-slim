-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-24 15:33:33
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
-- 表的结构 `candy_asset`
--

CREATE TABLE `candy_asset` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '货币ID',
  `currency_number` decimal(36,18) NOT NULL DEFAULT '0.000000000000000000' COMMENT 'TB持有数量',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户资产表';

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
(1, 'TB', 'https://static.feixiaohao.com/coin/eced1e28da4f16e117f471b08ad6e_mid.png', '比特币（BitCoin）的概念最初由中本聪在2009年提出，根据中本聪的思路设计发布的开源软件以及建构其上的P2P网络。比特币是一种P2P形式的数字货币。点对点的传输意味着一个去中心化的支付系统。\r\n与大多数货币不同，比特币不依靠特定货币机构发行，它依据特定算法，通过大量的计算产生，比特币经济使用整个P2P网络中众多节点构成的分布式数据库来确认并记录所有的交易行为，并使用密码学的设计来确保货币流通各个环节安全性。P2P的去中心化特性与算法本身可以确保无法通过大量制造比特币来人为操控币值。基于密码学的设计可以使比特币只能被真实的拥有者转移或支付。这同样确保了货币所有权与流通交易的匿名性。比特币与其他虚拟货币最大的不同，是其总数量非常有限，具有极强的稀缺性。该货币系统曾在4年内只有不超过1050万个，之后的总数量将被永久限制在2100万个。 ', '2018-07-19 12:02:07', '2018-07-20 04:07:33');

-- --------------------------------------------------------

--
-- 表的结构 `candy_invite`
--

CREATE TABLE `candy_invite` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `inviter` int(11) NOT NULL DEFAULT '0' COMMENT '邀请人',
  `invitee` int(11) NOT NULL DEFAULT '0' COMMENT '被邀请人',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '货币ID',
  `currency_name` varchar(16) NOT NULL DEFAULT '' COMMENT '货币名称',
  `currency_number` decimal(36,18) NOT NULL DEFAULT '0.000000000000000000' COMMENT '货币数量',
  `invite_status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '邀请状态 0待被邀请人绑定手机 1被邀请人已绑定手机',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `candy_mining`
--

CREATE TABLE `candy_mining` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `mining_status` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '挖矿状态1待领取 2已领取',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '货币ID',
  `currency_name` varchar(16) NOT NULL DEFAULT '' COMMENT '货币名称',
  `currency_number` decimal(36,18) NOT NULL DEFAULT '0.000000000000000000' COMMENT '货币数量',
  `effective_time` int(11) UNSIGNED NOT NULL COMMENT '生效时间',
  `dead_time` int(11) UNSIGNED NOT NULL COMMENT '过期时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='挖矿记录表';

-- --------------------------------------------------------

--
-- 表的结构 `candy_reward`
--

CREATE TABLE `candy_reward` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `reward_type` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '奖励类型1任务奖励 2挖矿奖励3邀请奖励',
  `foreign_id` int(11) UNSIGNED NOT NULL COMMENT '任务ID或挖矿ID或邀请ID',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '货币ID',
  `currency_name` varchar(16) NOT NULL COMMENT '货币名称',
  `currency_number` decimal(36,18) NOT NULL DEFAULT '0.000000000000000000' COMMENT '货币数量',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `candy_task`
--

CREATE TABLE `candy_task` (
  `id` int(11) NOT NULL COMMENT '任务ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `conf_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务ID candy_task_conf表ID',
  `task_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '任务类型 冗余candy_task表task_type',
  `task_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '任务状态1待派发 2已派发 3已开始 4已完成',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '货币ID',
  `currency_name` varchar(16) NOT NULL DEFAULT '' COMMENT '奖励币种',
  `currency_number` decimal(36,18) NOT NULL DEFAULT '0.000000000000000000' COMMENT '奖励数量',
  `created_date` varchar(10) NOT NULL DEFAULT '0000-00-00' COMMENT '创建日期',
  `start_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '任务开始时间',
  `finish_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '任务完成时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '任务创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='任务详细表';

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
(1, '签到', '每日签到任务，奖励10TB糖果', 1, 1, 1, 'TB', '10.000000000000000000', '2018-07-19 04:31:31', '2018-07-23 16:24:18'),
(2, '连续7天签到', '连续7天签到可以另外获取20TB糖果，若中间间断则下次登录从第一天算起', 1, 2, 1, 'TB', '20.000000000000000000', '2018-07-19 04:34:13', '2018-07-23 16:24:23'),
(3, '绑定手机', '绑定手机号码，奖励10TB糖果', 1, 3, 1, 'TB', '10.000000000000000000', '2018-07-19 04:35:44', '2018-07-23 16:24:25'),
(4, '邀请用户', '邀请用户进小程序，奖励10TB', 1, 4, 1, 'TB', '20.000000000000000000', '2018-07-19 04:35:44', '2018-07-23 16:24:27');

-- --------------------------------------------------------

--
-- 表的结构 `candy_user`
--

CREATE TABLE `candy_user` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `openid` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信openid',
  `nickname` varchar(12) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `gender` tinyint(2) NOT NULL DEFAULT '0' COMMENT '微信性别0未知1男2女',
  `language` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信语言',
  `city` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信城市',
  `province` varchar(12) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信省份',
  `country` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信国家',
  `avatar_url` varchar(168) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '微信头像',
  `invite_code` varchar(8) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '邀请码',
  `inviter` int(11) NOT NULL DEFAULT '0' COMMENT '邀请人',
  `phone` varchar(64) NOT NULL DEFAULT '' COMMENT '电话密文',
  `mining_power` int(11) NOT NULL DEFAULT '0' COMMENT '挖矿算力',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户信息表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candy_asset`
--
ALTER TABLE `candy_asset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candy_currency`
--
ALTER TABLE `candy_currency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `candy_invite`
--
ALTER TABLE `candy_invite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candy_mining`
--
ALTER TABLE `candy_mining`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candy_reward`
--
ALTER TABLE `candy_reward`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candy_task`
--
ALTER TABLE `candy_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candy_task_conf`
--
ALTER TABLE `candy_task_conf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candy_user`
--
ALTER TABLE `candy_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_openid` (`openid`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `candy_asset`
--
ALTER TABLE `candy_asset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID';

--
-- 使用表AUTO_INCREMENT `candy_currency`
--
ALTER TABLE `candy_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `candy_invite`
--
ALTER TABLE `candy_invite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID';

--
-- 使用表AUTO_INCREMENT `candy_mining`
--
ALTER TABLE `candy_mining`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID';

--
-- 使用表AUTO_INCREMENT `candy_reward`
--
ALTER TABLE `candy_reward`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID';

--
-- 使用表AUTO_INCREMENT `candy_task`
--
ALTER TABLE `candy_task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '任务ID';

--
-- 使用表AUTO_INCREMENT `candy_task_conf`
--
ALTER TABLE `candy_task_conf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID', AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `candy_user`
--
ALTER TABLE `candy_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
