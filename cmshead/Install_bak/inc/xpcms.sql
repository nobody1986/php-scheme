CREATE TABLE IF NOT EXISTS `xp_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `xp_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tid` int(6) NOT NULL,
  `title` varchar(120) NOT NULL,
  `keywords` varchar(120) NOT NULL,
  `description` varchar(200) NOT NULL,
  `img` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `add_time` int(15) NOT NULL,
  `update_time` int(15) NOT NULL,
  `adder_id` int(10) NOT NULL,
  `sort` int(15) NOT NULL,
  `apv` int(10) NOT NULL,
  `rewrite` varchar(200) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;


CREATE TABLE IF NOT EXISTS `xp_category` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `pid` int(6) NOT NULL,
  `title` varchar(60) NOT NULL,
  `sort` int(12) NOT NULL,
  `status` int(1) NOT NULL,
  `keywords` varchar(120) NOT NULL,
  `description` varchar(200) NOT NULL,
  `module` varchar(30) NOT NULL,
  `rewrite` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;


CREATE TABLE IF NOT EXISTS `xp_diary` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weather` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `adder_id` int(10) NOT NULL,
  `add_time` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;


CREATE TABLE IF NOT EXISTS `xp_group` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `title` varchar(50) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


INSERT INTO `xp_group` (`id`, `name`, `title`, `create_time`, `update_time`, `status`, `sort`, `show`) VALUES
(1, 'Sys', '系统设置', 0, 0, 1, 0, 0),
(2, 'App', '应用中心', 0, 0, 1, 0, 0);


CREATE TABLE IF NOT EXISTS `xp_link` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  `intro` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


INSERT INTO `xp_link` (`id`, `title`, `url`, `intro`, `sort`, `status`) VALUES
(1, 'CMSHEAD 官网', 'http://www.cmshead.com', 'CMSHEAD官方网站', 1, 1),
(2, '工作IT网', 'http://www.gongzuoit.com', '找IT工作上工作IT网', 0, 1);


CREATE TABLE IF NOT EXISTS `xp_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `aid` int(10) NOT NULL,
  `adder_name` varchar(200) NOT NULL,
  `adder_email` varchar(200) NOT NULL,
  `type` int(1) NOT NULL COMMENT '1:文章评论,2:留言',
  `adder_id` int(10) NOT NULL,
  `content` varchar(200) NOT NULL,
  `add_time` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;


CREATE TABLE IF NOT EXISTS `xp_music` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `author` varchar(60) NOT NULL,
  `url` varchar(200) NOT NULL,
  `sort` int(12) NOT NULL,
  `tid` int(10) NOT NULL,
  `adder_id` int(10) NOT NULL,
  `add_time` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;


CREATE TABLE IF NOT EXISTS `xp_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;


INSERT INTO `xp_node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`, `type`, `group_id`) VALUES
(1, 'Admin', '后台管理', 1, NULL, NULL, 0, 1, 0, 0),
(2, 'Node', '节点管理', 1, NULL, NULL, 1, 2, 0, 1),
(3, 'User', '用户管理', 1, '', NULL, 1, 2, 0, 1),
(4, 'Role', '群组管理', 1, '', NULL, 1, 2, 0, 1),
(5, 'Public', '公共模块', 1, '', NULL, 1, 2, 0, 0),
(6, 'Index', '默认模块', 1, '', NULL, 1, 2, 0, 0),
(7, 'index', '后台首页', 1, '', NULL, 6, 3, 0, 0),
(8, 'index', '列表', 1, '', NULL, 2, 3, 0, 0),
(9, 'add', '添加', 1, '', NULL, 2, 3, 0, 0),
(10, 'foreverdelete', '删除', 1, '', NULL, 2, 3, 0, 0),
(11, 'edit', '修改', 1, '', NULL, 2, 3, 0, 0),
(12, 'insert', '写入', 1, '', NULL, 2, 3, 0, 0),
(13, 'forbid', '禁用', 1, '', NULL, 2, 3, 0, 0),
(14, 'update', '更新', 1, '', NULL, 2, 3, 0, 0),
(15, 'resume', '恢复', 1, '', NULL, 2, 3, 0, 0),
(16, 'Category', '分类管理', 1, '', NULL, 1, 2, 0, 2),
(17, 'Article', '文章管理', 1, '', NULL, 1, 2, 0, 2),
(18, 'index', '列表', 1, '', NULL, 16, 3, 0, 0),
(19, 'add', '新增', 1, '', NULL, 16, 3, 0, 0),
(20, 'foreverdelete', '删除', 1, '', NULL, 16, 3, 0, 0),
(21, 'edit', '编辑', 1, '', NULL, 16, 3, 0, 0),
(22, 'insert', '写入', 1, '', NULL, 16, 3, 0, 0),
(23, 'forbid', '禁用', 1, '', NULL, 16, 3, 0, 0),
(24, 'update', '更新', 1, '', NULL, 16, 3, 0, 0),
(25, 'resume', '恢复', 1, '', NULL, 16, 3, 0, 0),
(26, 'Music', '音乐管理', 1, '', 0, 1, 2, 0, 2),
(27, 'Video', '视频管理', 1, '', 0, 1, 2, 0, 2),
(28, 'Photo', '图片管理', 1, '', 0, 1, 2, 0, 2),
(29, 'Link', '链接管理', 1, '', 0, 1, 2, 0, 2),
(30, 'Diary', '日记管理', 1, '', 0, 1, 2, 0, 2),
(31, 'Message', '留言评论', 1, '', 0, 1, 2, 0, 2),
(32, 'System', '系统功能', 1, '', 0, 1, 2, 0, 1),
(33, 'Router', '路由列表', 1, '', 0, 1, 2, 0, 2),
(34, 'File', '文件管理', 1, '', 0, 1, 2, 0, 1);


CREATE TABLE IF NOT EXISTS `xp_photo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `intro` varchar(200) NOT NULL,
  `img` varchar(200) NOT NULL,
  `link` varchar(200) NOT NULL,
  `tid` int(10) NOT NULL,
  `sort` int(12) NOT NULL,
  `adder_id` int(10) NOT NULL,
  `add_time` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;


CREATE TABLE IF NOT EXISTS `xp_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `ename` varchar(5) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `ename` (`ename`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `xp_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `xp_router` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rewrite` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

CREATE TABLE IF NOT EXISTS `xp_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `bind_account` varchar(50) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `login_count` mediumint(8) unsigned DEFAULT '0',
  `verify` varchar(32) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `type_id` tinyint(2) unsigned DEFAULT '0',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


CREATE TABLE IF NOT EXISTS `xp_video` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `img` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `rewrite` varchar(200) NOT NULL,
  `sort` int(12) NOT NULL,
  `tid` int(10) NOT NULL,
  `adder_id` int(10) NOT NULL,
  `add_time` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;