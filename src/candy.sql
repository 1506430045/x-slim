-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-07-25 16:38:58
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
  `currency_name` varchar(16) NOT NULL DEFAULT '' COMMENT '货币名称',
  `currency_number` decimal(36,18) NOT NULL DEFAULT '0.000000000000000000' COMMENT 'TB持有数量',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户资产表';

-- --------------------------------------------------------

--
-- 表的结构 `candy_company`
--

CREATE TABLE `candy_company` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `official_website` varchar(24) NOT NULL DEFAULT '' COMMENT '官方网站',
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
(1, 'https://thinkbitpro.com/', 'ThinkBitPro_2018', '', 'ThinkBitProCN', 'ThinkBitProEnglish', 'ThinkBitPro', 'thinkbitexchange', '信币是下一代的数字资产交易所，为用户提供高流动性交易环境、全资产冷储存、多种订单类型、高性能', '2018-07-25 06:53:23', '2018-07-25 06:53:23');

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
(1, 'TB', 'https://static.feixiaohao.com/coin/eced1e28da4f16e117f471b08ad6e_mid.png', 'TB是基于ERC-20协议发行的币，是ThinkBit信币交易所发行的平台币，极具独创性，总量210亿，永不增发。TB可以用作 ThinkBit服务的费用支付，包括支付费用、保证金费用支付，以及其他费用。\r\n官方网站：https://thinkbit.com', '2018-07-19 12:02:07', '2018-07-24 12:30:25'),
(2, 'ADE', 'https://static.feixiaohao.com/coin/eced1e28da4f16e117f471b08ad6e_mid.png', 'ADE是建立在DMChain上的币，DMChain建立在Cardano（ADA）之上，利用智能合约和数据透明度，为广告业者提供了一种解决方案，无论广告主的规模如何，均可确保交易的安全性、可验证性、可追溯性和易用性。此外，它还消除了广告行业的不信任性和不确定性，并为广告主提供了更明确的市场目标，以及可跟踪和可量化的广告投放结果。\r\n官方网站：https://dmchain.io\r\n', '2018-07-19 12:02:07', '2018-07-24 12:31:24'),
(3, 'CCC', 'https://static.feixiaohao.com/coin/eced1e28da4f16e117f471b08ad6e_mid.png', '控银天下（Coindom）是一款区块链投资者的投顾工具，提供深度行情指标，多交易所账户管理、一键搬砖以及资讯、社区等区块链投资相关服务，通过统一极致的操作体验，让小白投资者能够快速上手区块链资产的投资与管理。\r\n官方网站：https://www.coindom.com', '2018-07-19 12:02:07', '2018-07-24 12:30:25');

-- --------------------------------------------------------

--
-- 表的结构 `candy_goods`
--

CREATE TABLE `candy_goods` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `goods_name` varchar(32) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_img` varchar(128) NOT NULL DEFAULT '' COMMENT '商品图片',
  `stock` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '商品库存',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '货币ID',
  `currency_name` varchar(16) NOT NULL DEFAULT '' COMMENT '货币名称',
  `currency_number` decimal(36,18) NOT NULL DEFAULT '0.000000000000000000' COMMENT '货币数量',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `candy_goods`
--

INSERT INTO `candy_goods` (`id`, `goods_name`, `goods_img`, `stock`, `currency_id`, `currency_name`, `currency_number`, `created_at`, `updated_at`) VALUES
(1, 'iPhone X 5.8 英寸 64GB', 'http://img.pconline.com.cn/images/product/1048/1048848/z-5_sn8.jpg', 100, 1, 'TB', '150000.000000000000000000', '2018-07-25 06:31:16', '2018-07-25 06:31:16'),
(2, 'iPad Pro 10.5 英寸 64G', 'http://img.pconline.com.cn/images/product/6145/614547/1.jpg', 100, 1, 'TB', '110000.000000000000000000', '2018-07-25 06:31:16', '2018-07-25 06:31:16');

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
  `reward_description` varchar(16) NOT NULL DEFAULT '' COMMENT '奖励描述',
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
  `task_status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '任务状态0未完成1已完成',
  `currency_id` int(11) NOT NULL DEFAULT '0' COMMENT '货币ID',
  `currency_name` varchar(16) NOT NULL DEFAULT '' COMMENT '奖励币种',
  `currency_number` decimal(36,18) NOT NULL DEFAULT '0.000000000000000000' COMMENT '奖励数量',
  `created_date` varchar(10) NOT NULL DEFAULT '0000-00-00' COMMENT '创建日期',
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
(1, '每日登录', '每日签到任务，奖励5TB糖果', 1, 1, 1, 'TB', '5.000000000000000000', '2018-07-19 04:31:31', '2018-07-24 12:52:41'),
(2, '七日登录', '每周内连续7天签到可以另外获取15TB糖果', 1, 2, 1, 'TB', '15.000000000000000000', '2018-07-19 04:34:13', '2018-07-24 12:53:09'),
(3, '绑定手机', '绑定手机号码，奖励50TB糖果', 1, 3, 1, 'TB', '50.000000000000000000', '2018-07-19 04:35:44', '2018-07-24 12:53:15'),
(4, '邀请好友', '邀请用户进小程序，奖励100TB糖果', 1, 4, 1, 'TB', '100.000000000000000000', '2018-07-19 04:35:44', '2018-07-24 12:53:24');

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
-- Indexes for table `candy_company`
--
ALTER TABLE `candy_company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candy_currency`
--
ALTER TABLE `candy_currency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `candy_goods`
--
ALTER TABLE `candy_goods`
  ADD PRIMARY KEY (`id`);

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
-- 使用表AUTO_INCREMENT `candy_company`
--
ALTER TABLE `candy_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `candy_currency`
--
ALTER TABLE `candy_currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `candy_goods`
--
ALTER TABLE `candy_goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID', AUTO_INCREMENT=3;

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
