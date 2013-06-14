/*
MySQL Data Transfer
Source Host: 192.168.0.96
Source Database: cmshead
Target Host: 192.168.0.96
Target Database: cmshead
Date: 2012/11/1 16:15:59
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for ch_access
-- ----------------------------
DROP TABLE IF EXISTS `ch_access`;
CREATE TABLE `ch_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `module` varchar(50) NOT NULL DEFAULT '',
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_article
-- ----------------------------
DROP TABLE IF EXISTS `ch_article`;
CREATE TABLE `ch_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tid` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `img` varchar(50) NOT NULL,
  `content` longtext NOT NULL,
  `add_time` int(10) NOT NULL,
  `update_time` int(10) NOT NULL,
  `adder_id` int(10) NOT NULL,
  `sort` smallint(5) NOT NULL,
  `apv` smallint(5) NOT NULL,
  `rewrite` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `template` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_category
-- ----------------------------
DROP TABLE IF EXISTS `ch_category`;
CREATE TABLE `ch_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `sort` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `keywords` varchar(150) NOT NULL,
  `description` varchar(200) NOT NULL,
  `module` varchar(30) NOT NULL,
  `rewrite` varchar(100) NOT NULL,
  `template` varchar(50) NOT NULL DEFAULT '',
  `newstemplate` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_diary
-- ----------------------------
DROP TABLE IF EXISTS `ch_diary`;
CREATE TABLE `ch_diary` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weather` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `adder_id` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_group
-- ----------------------------
DROP TABLE IF EXISTS `ch_group`;
CREATE TABLE `ch_group` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `title` varchar(50) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_link
-- ----------------------------
DROP TABLE IF EXISTS `ch_link`;
CREATE TABLE `ch_link` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  `intro` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_message
-- ----------------------------
DROP TABLE IF EXISTS `ch_message`;
CREATE TABLE `ch_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `aid` int(10) NOT NULL,
  `adder_name` varchar(50) NOT NULL,
  `adder_email` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1:文章评论,2:留言',
  `adder_id` int(10) NOT NULL,
  `content` varchar(200) NOT NULL,
  `add_time` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_music
-- ----------------------------
DROP TABLE IF EXISTS `ch_music`;
CREATE TABLE `ch_music` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `author` varchar(50) NOT NULL,
  `url` varchar(150) NOT NULL,
  `sort` int(10) NOT NULL,
  `tid` int(10) NOT NULL,
  `adder_id` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_node
-- ----------------------------
DROP TABLE IF EXISTS `ch_node`;
CREATE TABLE `ch_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_photo
-- ----------------------------
DROP TABLE IF EXISTS `ch_photo`;
CREATE TABLE `ch_photo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `intro` varchar(200) NOT NULL,
  `img` varchar(150) NOT NULL,
  `link` varchar(150) NOT NULL,
  `tid` int(10) NOT NULL,
  `sort` int(10) NOT NULL,
  `adder_id` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_role
-- ----------------------------
DROP TABLE IF EXISTS `ch_role`;
CREATE TABLE `ch_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(5) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `ename` varchar(5) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `ename` (`ename`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=264 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_role_user
-- ----------------------------
DROP TABLE IF EXISTS `ch_role_user`;
CREATE TABLE `ch_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_router
-- ----------------------------
DROP TABLE IF EXISTS `ch_router`;
CREATE TABLE `ch_router` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rewrite` varchar(100) NOT NULL,
  `url` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_user
-- ----------------------------
DROP TABLE IF EXISTS `ch_user`;
CREATE TABLE `ch_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `bind_account` varchar(50) NOT NULL,
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '',
  `login_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `verify` char(32) NOT NULL DEFAULT '',
  `email` varchar(150) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `type_id` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM AUTO_INCREMENT=1769 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_video
-- ----------------------------
DROP TABLE IF EXISTS `ch_video`;
CREATE TABLE `ch_video` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `img` varchar(50) NOT NULL,
  `url` varchar(150) NOT NULL,
  `rewrite` varchar(50) NOT NULL,
  `sort` int(10) NOT NULL,
  `tid` int(10) NOT NULL,
  `adder_id` int(10) NOT NULL,
  `add_time` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `ch_access` VALUES ('1', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('1', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('1', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('1', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('1', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('1', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('148', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('148', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('148', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('148', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('148', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('148', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('148', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('148', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('148', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('148', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('148', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('148', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('148', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('149', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('149', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('149', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('149', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('149', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('149', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('149', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('149', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('149', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('149', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('149', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('149', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('149', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('150', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('150', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('150', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('150', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('150', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('150', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('150', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('150', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('150', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('150', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('150', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('150', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('150', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('151', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('151', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('151', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('151', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('151', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('151', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('151', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('151', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('151', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('151', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('151', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('151', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('151', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('152', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('152', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('152', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('152', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('152', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('152', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('152', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('152', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('152', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('152', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('152', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('152', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('152', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('153', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('153', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('153', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('153', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('153', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('153', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('153', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('153', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('153', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('153', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('153', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('153', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('153', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('155', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('155', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('155', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('155', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('155', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('155', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('155', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('155', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('155', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('155', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('155', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('155', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('155', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('156', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('156', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('156', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('156', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('156', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('156', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('156', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('156', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('156', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('156', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('156', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('156', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('156', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('157', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('157', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('157', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('157', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('157', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('157', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('157', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('157', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('157', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('157', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('157', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('157', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('157', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('158', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('158', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('158', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('158', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('158', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('158', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('158', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('158', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('158', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('158', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('158', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('158', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('158', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('159', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('159', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('159', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('159', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('159', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('159', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('159', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('159', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('159', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('159', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('159', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('159', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('159', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('160', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('160', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('160', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('160', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('160', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('160', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('160', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('160', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('160', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('160', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('160', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('160', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('160', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('161', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('161', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('161', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('161', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('161', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('161', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('161', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('161', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('161', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('161', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('161', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('161', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('161', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('162', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('162', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('162', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('162', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('162', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('162', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('162', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('162', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('162', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('162', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('162', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('162', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('162', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('163', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('163', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('163', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('163', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('163', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('163', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('163', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('163', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('163', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('163', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('163', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('163', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('163', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('164', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('164', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('164', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('164', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('164', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('164', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('164', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('164', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('164', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('164', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('164', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('164', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('164', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('165', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('165', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('165', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('165', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('165', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('165', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('165', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('165', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('165', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('165', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('165', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('165', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('165', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('166', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('166', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('166', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('166', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('166', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('166', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('166', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('166', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('166', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('166', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('166', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('166', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('166', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('167', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('167', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('167', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('167', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('167', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('167', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('167', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('167', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('167', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('167', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('167', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('167', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('167', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('168', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('168', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('168', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('168', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('168', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('168', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('168', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('168', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('168', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('168', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('168', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('168', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('168', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('169', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('169', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('169', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('169', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('169', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('169', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('169', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('169', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('169', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('169', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('169', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('169', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('169', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('170', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('170', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('170', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('170', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('170', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('170', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('170', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('170', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('170', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('170', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('170', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('170', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('170', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('171', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('171', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('171', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('171', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('171', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('171', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('171', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('171', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('171', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('171', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('171', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('171', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('171', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('172', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('172', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('172', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('172', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('172', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('172', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('172', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('172', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('172', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('172', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('172', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('172', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('172', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('174', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('174', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('174', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('174', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('174', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('174', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('174', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('174', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('174', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('174', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('174', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('174', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('174', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('175', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('175', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('175', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('175', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('175', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('175', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('175', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('175', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('175', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('175', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('175', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('175', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('175', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('176', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('176', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('176', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('176', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('176', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('176', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('176', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('176', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('176', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('176', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('176', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('176', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('176', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('177', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('177', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('177', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('177', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('177', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('177', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('177', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('177', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('177', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('177', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('177', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('177', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('177', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('178', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('178', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('178', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('178', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('178', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('178', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('178', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('178', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('178', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('178', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('178', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('178', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('178', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('179', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('179', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('179', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('179', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('179', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('179', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('179', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('179', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('179', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('179', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('179', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('179', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('179', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('180', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('180', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('180', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('180', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('180', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('180', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('180', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('180', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('180', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('180', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('180', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('180', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('180', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('181', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('181', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('181', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('181', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('181', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('181', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('181', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('181', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('181', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('181', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('181', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('181', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('181', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('182', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('182', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('182', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('182', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('182', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('182', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('182', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('182', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('182', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('182', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('182', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('182', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('182', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('183', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('183', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('183', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('183', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('183', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('183', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('183', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('183', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('183', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('183', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('183', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('183', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('183', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('184', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('184', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('184', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('184', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('184', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('184', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('184', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('184', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('184', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('184', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('184', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('184', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('184', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('185', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('185', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('185', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('185', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('185', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('185', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('185', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('185', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('185', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('185', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('185', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('185', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('185', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('186', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('186', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('186', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('186', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('186', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('186', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('186', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('186', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('186', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('186', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('186', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('186', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('186', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('187', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('187', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('187', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('187', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('187', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('187', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('187', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('187', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('187', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('187', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('187', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('187', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('187', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('188', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('188', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('188', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('188', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('188', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('188', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('188', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('188', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('188', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('188', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('188', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('188', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('188', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('189', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('189', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('189', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('189', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('189', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('189', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('189', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('189', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('189', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('189', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('189', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('189', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('189', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('190', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('190', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('190', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('190', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('190', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('190', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('190', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('190', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('190', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('190', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('190', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('190', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('190', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('191', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('191', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('191', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('191', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('191', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('191', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('191', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('191', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('191', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('191', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('191', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('191', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('191', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('192', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('192', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('192', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('192', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('192', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('192', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('192', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('192', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('192', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('192', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('192', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('192', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('192', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('193', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('193', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('193', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('193', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('193', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('193', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('193', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('193', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('193', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('193', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('193', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('193', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('193', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('194', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('194', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('194', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('194', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('194', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('194', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('194', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('194', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('194', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('194', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('194', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('194', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('194', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('195', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('195', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('195', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('195', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('195', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('195', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('195', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('195', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('195', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('195', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('195', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('195', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('195', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('196', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('196', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('196', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('196', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('196', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('196', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('196', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('196', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('196', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('196', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('196', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('196', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('196', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('197', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('197', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('197', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('197', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('197', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('197', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('197', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('197', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('197', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('197', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('197', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('197', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('197', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('198', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('198', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('198', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('198', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('198', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('198', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('198', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('198', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('198', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('198', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('198', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('198', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('198', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('199', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('199', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('199', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('199', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('199', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('199', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('199', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('199', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('199', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('199', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('199', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('199', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('199', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('200', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('200', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('200', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('200', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('200', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('200', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('200', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('200', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('200', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('200', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('200', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('200', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('200', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('201', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('201', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('201', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('201', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('201', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('201', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('201', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('201', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('201', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('201', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('201', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('201', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('201', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('202', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('202', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('202', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('202', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('202', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('202', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('202', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('202', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('202', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('202', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('202', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('202', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('202', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('203', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('203', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('203', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('203', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('203', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('203', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('203', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('203', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('203', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('203', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('203', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('203', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('203', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('204', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('204', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('204', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('204', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('204', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('204', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('204', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('204', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('204', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('204', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('204', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('204', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('204', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('205', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('205', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('205', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('205', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('205', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('205', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('205', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('205', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('205', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('205', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('205', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('205', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('205', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('206', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('206', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('206', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('206', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('206', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('206', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('206', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('206', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('206', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('206', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('206', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('206', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('206', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('207', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('207', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('207', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('207', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('207', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('207', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('207', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('207', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('207', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('207', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('207', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('207', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('207', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('208', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('208', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('208', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('208', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('208', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('208', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('208', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('208', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('208', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('208', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('208', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('208', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('208', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('210', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('210', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('210', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('210', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('210', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('210', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('210', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('210', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('210', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('210', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('210', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('210', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('210', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('211', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('211', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('211', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('211', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('211', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('211', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('211', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('211', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('211', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('211', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('211', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('211', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('211', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('212', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('212', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('212', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('212', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('212', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('212', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('212', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('212', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('212', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('212', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('212', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('212', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('212', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('213', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('213', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('213', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('213', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('213', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('213', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('213', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('213', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('213', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('213', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('213', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('213', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('213', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('214', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('214', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('214', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('214', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('214', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('214', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('214', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('214', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('214', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('214', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('214', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('214', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('214', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('215', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('215', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('215', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('215', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('215', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('215', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('215', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('215', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('215', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('215', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('215', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('215', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('215', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('216', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('216', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('216', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('216', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('216', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('216', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('216', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('216', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('216', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('216', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('216', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('216', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('216', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('217', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('217', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('217', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('217', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('217', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('217', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('217', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('217', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('217', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('217', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('217', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('217', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('217', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('218', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('218', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('218', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('218', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('218', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('218', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('218', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('218', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('218', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('218', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('218', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('218', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('218', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('221', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('221', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('221', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('221', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('221', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('221', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('221', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('221', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('221', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('221', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('221', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('221', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('221', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('222', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('222', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('222', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('222', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('222', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('222', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('222', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('222', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('222', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('222', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('222', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('222', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('222', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('223', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('223', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('223', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('223', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('223', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('223', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('223', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('223', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('223', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('223', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('223', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('223', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('223', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('224', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('224', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('224', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('224', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('224', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('224', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('224', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('224', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('224', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('224', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('224', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('224', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('224', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('226', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('226', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('226', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('226', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('226', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('226', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('226', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('226', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('226', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('226', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('226', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('226', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('226', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('227', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('227', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('227', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('227', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('227', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('227', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('227', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('227', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('227', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('227', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('227', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('227', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('227', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('228', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('228', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('228', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('228', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('228', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('228', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('228', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('228', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('228', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('228', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('228', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('228', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('228', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('229', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('229', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('229', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('229', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('229', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('229', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('229', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('229', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('229', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('229', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('229', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('229', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('229', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('230', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('230', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('230', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('230', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('230', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('230', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('230', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('230', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('230', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('230', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('230', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('230', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('230', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('233', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('233', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('233', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('233', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('233', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('233', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('233', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('233', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('233', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('233', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('233', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('233', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('233', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('234', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('234', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('234', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('234', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('234', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('234', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('234', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('234', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('234', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('234', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('234', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('234', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('234', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('236', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('236', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('236', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('236', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('236', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('236', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('236', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('236', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('236', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('236', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('236', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('236', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('236', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('237', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('237', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('237', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('237', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('237', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('237', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('237', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('237', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('237', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('237', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('237', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('237', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('237', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('238', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('238', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('238', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('238', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('238', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('238', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('238', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('238', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('238', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('238', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('238', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('238', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('238', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('239', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('239', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('239', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('239', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('239', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('239', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('239', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('239', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('239', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('239', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('239', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('239', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('239', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('240', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('240', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('240', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('240', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('240', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('240', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('240', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('240', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('240', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('240', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('240', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('240', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('240', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('248', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('248', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('248', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('248', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('248', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('248', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('248', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('248', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('248', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('248', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('248', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('248', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('248', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('250', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('250', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('250', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('250', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('250', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('250', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('250', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('250', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('250', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('250', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('250', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('250', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('250', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('251', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('251', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('251', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('251', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('251', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('251', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('251', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('251', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('251', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('251', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('251', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('251', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('251', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('252', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('252', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('252', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('252', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('252', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('252', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('252', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('252', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('252', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('252', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('252', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('252', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('252', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('253', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('253', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('253', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('253', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('253', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('253', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('253', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('253', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('253', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('253', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('253', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('253', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('253', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('255', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('255', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('255', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('255', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('255', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('255', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('255', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('255', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('255', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('255', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('255', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('255', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('255', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('256', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('256', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('256', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('256', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('256', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('256', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('256', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('256', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('256', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('256', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('256', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('256', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('256', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('258', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('258', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('258', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('258', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('258', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('258', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('258', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('258', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('258', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('258', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('258', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('258', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('258', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('259', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('259', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('259', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('259', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('259', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('259', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('259', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('259', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('259', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('259', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('259', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('259', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('259', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('260', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('260', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('260', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('260', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('260', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('260', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('260', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('260', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('260', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('260', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('260', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('260', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('260', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('261', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('261', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('261', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('261', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('261', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('261', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('261', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('261', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('261', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('261', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('261', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('261', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('261', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('262', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('262', '5', '2', '1', '');
INSERT INTO `ch_access` VALUES ('262', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('262', '30', '2', '1', '');
INSERT INTO `ch_access` VALUES ('262', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('262', '8', '3', '5', '');
INSERT INTO `ch_access` VALUES ('262', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('262', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('262', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('262', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('262', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('262', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('262', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_access` VALUES ('0', '0', '0', '0', '');
INSERT INTO `ch_article` VALUES ('1', '2', '朝鲜第一夫人被曝用迪奥包 价值1600美元(图)', '朝鲜', '东方网8月9日消息：据新闻晚报报道，朝鲜第一夫人李雪主被人拍摄到使用法国迪奥名牌手袋。目前尚未清楚该手袋是否真货，据韩国 《中央日报》8日报道，若是真货，在首尔将价值180万韩元（约合1600美元，10166元人民币）。', '509216774f526.jpg', '<p align=\"center\"><img alt=\"朝鲜第一夫人被曝用迪奥包 价值1600美元(图)\" src=\"http://img1.gtimg.com/news/pics/hv1/136/5/1110/72179161.jpg\" /></p><p class=\"pictext\" align=\"center\"><span style=\"FONT-SIZE: 12px\">李雪主右手边的手袋被指出自迪奥111</span></p><p style=\"TEXT-INDENT: 2em\">东方网8月9日消息：据新闻晚报报道，朝鲜第一夫人李雪主被人拍摄到使用法国迪奥名牌手袋。目前尚未清楚该手袋是否真货，据韩国 《中央日报》8日报道，若是真货，在首尔将价值180万韩元（约合1600美元，10166元人民币）。</p><p style=\"TEXT-INDENT: 2em\">李雪主日前陪丈夫、朝鲜领导人金正恩视察部队。朝鲜官方电视台和官方新闻社刊登的照片显示，当时金正恩夫妇在户外同坐，欣赏朝鲜人民军的艺文表演。</p><p style=\"TEXT-INDENT: 2em\">当天李雪主以一袭黑衣白裙套装亮相，身边有个小巧的手提袋，被人眼尖地看到包上面有迪奥的金属制“D”字标志。她的亮丽装扮在金正恩一贯的黑色中山装穿着及军方官员的绿色军装中，显得格外突出。</p><p style=\"TEXT-INDENT: 2em\">韩国媒体指出，李雪主腹部隆起，很像是怀孕的迹象。韩国情报部门此前表示，金正恩与李雪主已经有一个孩子。</p>', '1344522626', '1351751273', '1', '0', '25', '', '1', '{tplroot}Article/view_test.html');
INSERT INTO `ch_article` VALUES ('2', '3', '上海浦东机场两客机发生碰擦 两机机翼受损(图)', '上海浦东机场', '上海浦东机场两客机发生碰擦 两机机翼受损(图)', '5092165847a23.jpg', '<div style=\"POSITION: relative\" id=\"Cnt-Main-Article-QQ\" bosszone=\"content\"><p align=\"center\"></p><p></p><p style=\"TEXT-ALIGN: center\"><img alt=\"伤害浦东机场两客机发生碰擦 两机机翼受损(图)\" src=\"http://img1.gtimg.com/news/pics/hv1/173/70/1110/72195773.jpg\" /></p><p style=\"TEXT-ALIGN: center\">亲历此事的网友方小姐告诉记者，其所乘坐的东航飞机水平翼处受损。网友 方小姐 拍摄</p><p style=\"TEXT-INDENT: 2em\">【<span class=\"infoMblog\"><a class=\"a-tips-Article-QQ\" href=\"http://t.qq.com/xinmincn#pref=qqcom.keyword\" rel=\"xinmincn\" target=\"_blank\" reltitle=\"新民网\">新民网</a></span>·独家报道】今天16时30分左右，浦东国际机场发生两架飞机碰擦事故。据悉，马来西亚航空的MH389次航班与东方航空MU583次航班碰擦，马航客机机翼与东航客机水平翼均受损。</p><p style=\"TEXT-INDENT: 2em\">网友方小姐及网友@小眼大陆在微博上第一时间发布了事故信息。据方小姐告诉记者，事发时她觉得飞机震动了一下，随后乘客被通知飞机发生碰撞，并被要求下飞机。</p><p style=\"TEXT-INDENT: 2em\">据@上海发布消息，今天16时28分左右，东方航空MU583航班和马来西亚航空MH389航班在浦东机场F9道口发生刮蹭。该事件没有造成人员受伤，对浦东机场运行没有造成影响。目前，民航华东局已对此事件开展调查。</p><p style=\"TEXT-INDENT: 2em\">记者查询获悉，东方航空MU583航班原定是从浦东机场T1航站楼飞往洛杉矶机场，计划起飞时间是今天15时，预计飞机时间是12小时5分钟。而记者查询马来西亚航空MH389航班的相关信息显示，该航班计划起飞时间为15时35分，原定因从浦东机场T2航站楼起飞。目前 ，相关航班状态均为延误。</p></div>', '1344523056', '1351751244', '1', '0', '6', 'abc', '1', '');
INSERT INTO `ch_article` VALUES ('8', '2,3', '国内新闻测试', '', '', '', '<p>喝多了和反垄断</p><p>发达发货了大黄蜂了大灰狼</p>', '1346123608', '1351752011', '1', '0', '0', '', '1', '{tplroot}Article/view_test.html');
INSERT INTO `ch_article` VALUES ('9', '5,6', '111111111', '', '', '', '1111111111111111111', '1346134619', '1346134619', '1', '0', '1', '', '1', '');
INSERT INTO `ch_article` VALUES ('10', '2,3', '22222222222222', '', '', '', '<img src=\"/Public/Upload/article/day_120828/201208281536203228.bmp\" alt=\"\" />&nbsp;<img src=\"/Public/Upload/article/day_120828/201208281536201658.bmp\" alt=\"\" />', '1346134963', '1346139365', '1', '0', '0', '', '1', '');
INSERT INTO `ch_article` VALUES ('11', '2', '3333333333', '', '', '', '333333333333333333333333', '1351755222', '1351755222', '1', '0', '0', '', '1', '');
INSERT INTO `ch_article` VALUES ('12', '2', '44444444444444', '', '', '', '44444444444444444444444444444444', '1351755236', '1351755236', '1', '0', '0', '', '1', '');
INSERT INTO `ch_article` VALUES ('13', '2,3,51', '文章用的是view2.php这个模板', '', '', '', '5555555555555555555555555555555555555555555555', '1351755246', '1351755467', '1', '0', '0', '', '1', '{tplroot}Article/view2.php');
INSERT INTO `ch_article` VALUES ('14', '3,2,51', '一篇文章属于多个栏目测试', '', '', '', '66666666666666666666666666666666', '1351755278', '1351755496', '1', '0', '1', '', '1', '');
INSERT INTO `ch_article` VALUES ('15', '2,3,5,6,51', 'CMSHeadV1.0_20121101版本说明', '', '', '', '<p>&nbsp;&nbsp;&nbsp; 朋友们好，<strong>这个版本是微型CMS的最终版</strong>。修复了所有问题！本来我还做了一个理论网苑，是内部使用的，所以给你们网址也没用，要登录才能使用！是公务员学习考核平台，可以学习视频和文本课程，可答题加分，视频自动记忆上次学习的进度等功能，现在武进区政府的所有部门都在使用的，性能也不错，反应良好！暂时就不放出来了。有需要的朋友可以联系我索取。严格说来，目前这个微型1.0版本，还不能称其为CMS，只能说是一个框架之上的简易系统吧，因为要改前台模板的话，可能就需要碰控制器，需要改PHP代码，不过程序员好用用的。而CMS的目标是不懂PHP的人也能快速而随意的做出自己想要的网站。所以，这后面我将按帝国、DEDE等CMS的规模来完善这套CMS，理念很重要，请大家给我一点时间吧。可能时间有点长哦。不过我一旦有比较可用的版本以后，我会马上放出来，大家可以@还是这个味。我的新浪微博，或者加入CMSHead新浪微群，还有谷歌代码分享也可以查看：<a href=\"http://code.google.com/p/cmshead/\">http://code.google.com/p/cmshead/</a>，将有最新动态发出，有任何问题欢迎加我QQ 782039296，给我Email <a href=\"mailto:782039296@126.com\">782039296@126.com</a> ，谢谢你们支持我。相信以后CMSHead一定会超过帝国CMS。</p>', '1351756716', '1351757493', '1', '0', '1', '', '1', '');
INSERT INTO `ch_category` VALUES ('1', '0', '新闻', '0', '1', '', '', 'Article', '', '', '');
INSERT INTO `ch_category` VALUES ('2', '1', '国内', '1', '1', '国内', '国内新闻是新闻下级栏目', 'Article', '', '{tplroot}Article/index2.php', '{tplroot}Article/view2.php');
INSERT INTO `ch_category` VALUES ('3', '1', '国际', '2', '1', '', '', 'Article', '', '', '');
INSERT INTO `ch_category` VALUES ('4', '0', '汽车资讯', '0', '1', '', '', 'Article', '', '', '');
INSERT INTO `ch_category` VALUES ('5', '4', '全国车讯', '0', '1', '', '', 'Article', '', '', '');
INSERT INTO `ch_category` VALUES ('6', '4', '常州车讯', '0', '1', '', '', 'Article', '', '', '');
INSERT INTO `ch_category` VALUES ('7', '0', '图库', '0', '1', '', '', 'Photo', '', '', '');
INSERT INTO `ch_category` VALUES ('8', '7', '中国车模', '0', '1', '', '', 'Photo', '', '', '');
INSERT INTO `ch_category` VALUES ('9', '7', '韩国车模', '0', '1', '', '', 'Photo', '', '', '');
INSERT INTO `ch_category` VALUES ('10', '0', '视频', '0', '1', '', '', 'Video', '', '', '');
INSERT INTO `ch_category` VALUES ('11', '10', '汽车试驾', '0', '1', '', '', 'Video', '', '', '');
INSERT INTO `ch_category` VALUES ('12', '10', '宣传视频', '0', '1', '', '', 'Video', '', '', '');
INSERT INTO `ch_category` VALUES ('13', '14', '伤感音乐', '0', '1', '', '', 'Music', '', '', '');
INSERT INTO `ch_category` VALUES ('14', '0', '音乐', '0', '1', '', '', 'Music', '', '', '');
INSERT INTO `ch_category` VALUES ('51', '0', '测试栏目', '0', '1', '', '', 'Article', '', '', '');
INSERT INTO `ch_diary` VALUES ('1', '晴朗', '心情不错，试试看吧。', '1', '1346031936', '1');
INSERT INTO `ch_group` VALUES ('1', 'Sys', '系统设置', '0', '0', '1', '0', '0');
INSERT INTO `ch_group` VALUES ('2', 'App', '应用中心', '0', '0', '1', '0', '0');
INSERT INTO `ch_link` VALUES ('1', 'CMSHAED', 'http://www.cmshead.com', 'cmshead官方网站', '1', '1');
INSERT INTO `ch_link` VALUES ('2', '工作IT网', 'http://www.gongzuoit.com', '找IT工作上工作IT网', '0', '1');
INSERT INTO `ch_message` VALUES ('1', '0', '1', '阿文', 'awen8180@126.com', '1', '0', '牛逼，再穷不能穷政府啊。猿类！', '1344523278', '192.168.0.96', '1');
INSERT INTO `ch_message` VALUES ('2', '1', '1', '', 'awen8180@126.com', '0', '1', '独才国家都是这样。', '1344524642', '', '1');
INSERT INTO `ch_music` VALUES ('1', '口袋的天空', '张韶涵', 'http://zhangmenshiting2.baidu.com/data2/music/926195/926195.mp3?xcode=8a950cd4d28917c6553396c56deab3f1&mid=0.39602446811847', '0', '13', '1', '1344518188', '1');
INSERT INTO `ch_music` VALUES ('2', '隐形的翅膀', '张韶涵', 'http://zhangmenshiting2.baidu.com/data2/music/1501662/1501662.mp3', '0', '13', '1', '1344518360', '1');
INSERT INTO `ch_node` VALUES ('1', 'Admin', '后台管理', '1', '', '0', '0', '1', '0', '0');
INSERT INTO `ch_node` VALUES ('2', 'Node', '节点管理', '1', '', '0', '1', '2', '0', '1');
INSERT INTO `ch_node` VALUES ('3', 'User', '用户管理', '1', '', '0', '1', '2', '0', '1');
INSERT INTO `ch_node` VALUES ('4', 'Role', '群组管理', '1', '', '0', '1', '2', '0', '1');
INSERT INTO `ch_node` VALUES ('5', 'Public', '公共模块', '1', '', '0', '1', '2', '0', '0');
INSERT INTO `ch_node` VALUES ('6', 'Index', '默认模块', '1', '', '0', '1', '2', '0', '0');
INSERT INTO `ch_node` VALUES ('7', 'index', '后台首页', '1', '', '0', '6', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('8', 'index', '列表', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('9', 'add', '添加', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('10', 'foreverdelete', '删除', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('11', 'edit', '修改', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('12', 'insert', '写入', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('13', 'forbid', '禁用', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('14', 'update', '更新', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('15', 'resume', '恢复', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('16', 'Category', '分类管理', '1', '', '2', '1', '2', '0', '2');
INSERT INTO `ch_node` VALUES ('17', 'Article', '文章管理', '1', '', '1', '1', '2', '0', '2');
INSERT INTO `ch_node` VALUES ('26', 'Music', '音乐管理', '1', '', '5', '1', '2', '0', '2');
INSERT INTO `ch_node` VALUES ('27', 'Video', '视频管理', '1', '', '4', '1', '2', '0', '2');
INSERT INTO `ch_node` VALUES ('28', 'Photo', '图片管理', '1', '', '3', '1', '2', '0', '2');
INSERT INTO `ch_node` VALUES ('29', 'Link', '链接管理', '1', '', '8', '1', '2', '0', '2');
INSERT INTO `ch_node` VALUES ('30', 'Diary', '日记管理', '1', '', '7', '1', '2', '0', '2');
INSERT INTO `ch_node` VALUES ('31', 'Message', '留言评论', '1', '', '6', '1', '2', '0', '2');
INSERT INTO `ch_node` VALUES ('32', 'System', '系统功能', '1', '', '0', '1', '2', '0', '1');
INSERT INTO `ch_node` VALUES ('33', 'Router', '路由列表', '1', '', '30', '1', '2', '0', '2');
INSERT INTO `ch_node` VALUES ('34', 'File', '文件管理', '1', '', '0', '1', '2', '0', '1');
INSERT INTO `ch_photo` VALUES ('1', '北影小仙女赵思诺', '', '5023c2d279beb.jpg', '', '8', '0', '0', '1344520702', '1');
INSERT INTO `ch_role` VALUES ('1', '理论网苑', '0', '1', '', '', '1347269667', '0');
INSERT INTO `ch_role` VALUES ('183', '牛塘镇', '0', '1', '', '', '1347269667', '0');
INSERT INTO `ch_role_user` VALUES ('1', '1095');
INSERT INTO `ch_role_user` VALUES ('183', '1391');
INSERT INTO `ch_role_user` VALUES ('183', '1326');
INSERT INTO `ch_role_user` VALUES ('242', '1095');
INSERT INTO `ch_role_user` VALUES ('245', '1103');
INSERT INTO `ch_role_user` VALUES ('183', '1325');
INSERT INTO `ch_role_user` VALUES ('183', '858');
INSERT INTO `ch_role_user` VALUES ('1', '1103');
INSERT INTO `ch_role_user` VALUES ('183', '856');
INSERT INTO `ch_role_user` VALUES ('3', '1768');
INSERT INTO `ch_role_user` VALUES ('3', '1769');
INSERT INTO `ch_role_user` VALUES ('263', '1769');
INSERT INTO `ch_role_user` VALUES ('3', '1770');
INSERT INTO `ch_role_user` VALUES ('3', '1768');
INSERT INTO `ch_router` VALUES ('9', 'abc', 'article/view/id/2');
INSERT INTO `ch_user` VALUES ('1', 'admin', '超级管理员', '21232f297a57a5a743894a0e4a801fc3', '', '1351756574', '192.168.0.96', '219', '', 'admin@126.com', '', '1347269667', '0', '1', '0', '');
INSERT INTO `ch_user` VALUES ('1391', 'ntz2', 'ntz2', '81dc9bdb52d04dc20036dbd8313ed055', '', '0', '', '0', '', 'ntz2@126.com', '', '1347269668', '0', '1', '0', '');
INSERT INTO `ch_user` VALUES ('1326', 'ntz5', 'ntz5', '81dc9bdb52d04dc20036dbd8313ed055', '', '0', '', '0', '', 'ntz5@126.com', '', '1347269668', '0', '1', '0', '');
INSERT INTO `ch_user` VALUES ('1325', 'ntz4', 'ntz4', '81dc9bdb52d04dc20036dbd8313ed055', '', '0', '', '0', '', 'ntz4@126.com', '', '1347269668', '0', '1', '0', '');
INSERT INTO `ch_user` VALUES ('1103', 'rmccbx1', 'rmccbx1', '81dc9bdb52d04dc20036dbd8313ed055', '', '0', '', '0', '', 'rmccbx1@126.com', '', '1347269667', '0', '1', '0', '');
INSERT INTO `ch_user` VALUES ('1095', 'gsyh2', 'gsyh2', '81dc9bdb52d04dc20036dbd8313ed055', '', '0', '', '0', '', 'gsyh2@126.com', '', '1347269667', '0', '1', '0', '');
INSERT INTO `ch_user` VALUES ('858', 'ntz3', 'ntz3', '81dc9bdb52d04dc20036dbd8313ed055', '', '0', '', '0', '', 'ntz3@126.com', '', '1347269667', '0', '1', '0', '');
INSERT INTO `ch_user` VALUES ('856', 'ntz1', 'ntz1', '81dc9bdb52d04dc20036dbd8313ed055', '', '1348793804', '218.93.116.12', '2', '', 'ntz1@126.com', '', '1347269667', '0', '1', '0', '');
