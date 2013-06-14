/*
MySQL Data Transfer
Source Host: localhost
Source Database: cmshead
Target Host: localhost
Target Database: cmshead
Date: 2013/5/27 17:31:11
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for ch_access
-- ----------------------------
DROP TABLE IF EXISTS `ch_access`;
CREATE TABLE `ch_access` (
  `role_id` int(10) unsigned NOT NULL,
  `node_id` int(10) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` int(10) NOT NULL,
  `module` varchar(50) NOT NULL DEFAULT '',
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_article
-- ----------------------------
DROP TABLE IF EXISTS `ch_article`;
CREATE TABLE `ch_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `tid` varchar(50) NOT NULL COMMENT '所属分类',
  `title` varchar(100) NOT NULL COMMENT '文章标题',
  `isb` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加粗',
  `isi` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否斜体',
  `tcolor` char(10) DEFAULT NULL COMMENT '标题颜色',
  `keywords` varchar(100) DEFAULT NULL COMMENT '关键字',
  `description` varchar(200) DEFAULT NULL COMMENT '描述',
  `img` varchar(100) DEFAULT NULL COMMENT '预览图片',
  `content` longtext NOT NULL COMMENT '内容',
  `add_time` int(10) NOT NULL COMMENT '录入时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  `adder_id` int(10) NOT NULL COMMENT '录入人',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序值',
  `apv` smallint(5) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `rewrite` varchar(50) DEFAULT NULL COMMENT 'URL重写值',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1:启用,0:禁用',
  `template` varchar(50) DEFAULT NULL COMMENT '使用模板',
  `attrtj` varchar(30) DEFAULT NULL COMMENT '推荐属性',
  `attrtt` varchar(30) DEFAULT NULL COMMENT '头条属性',
  `outurl` varchar(150) DEFAULT NULL COMMENT '外部网址',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_attribute
-- ----------------------------
DROP TABLE IF EXISTS `ch_attribute`;
CREATE TABLE `ch_attribute` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `attrtype` varchar(30) NOT NULL COMMENT '属性分类',
  `attrname` varchar(50) NOT NULL COMMENT '属性名称',
  `attrvalue` varchar(50) NOT NULL COMMENT '属性值',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序值',
  `status` varchar(1) NOT NULL DEFAULT '1' COMMENT '状态 1:启用,0:禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_category
-- ----------------------------
DROP TABLE IF EXISTS `ch_category`;
CREATE TABLE `ch_category` (
  `classid` int(10) NOT NULL AUTO_INCREMENT COMMENT '栏目id',
  `classpid` int(10) NOT NULL DEFAULT '0' COMMENT '栏目父id',
  `classpids` varchar(100) NOT NULL COMMENT '栏目父ids',
  `classchild` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有下级',
  `classchildids` varchar(2000) NOT NULL COMMENT '栏目下级ids',
  `classarrchildids` mediumtext NOT NULL COMMENT '栏目下级对象',
  `classtitle` varchar(100) NOT NULL COMMENT '栏目标题',
  `classsort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `classstatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `classkeywords` varchar(150) NOT NULL COMMENT '关键字',
  `classdescription` varchar(200) NOT NULL COMMENT '描述',
  `classmodule` varchar(30) NOT NULL COMMENT '所属模型',
  `classrewrite` varchar(100) NOT NULL COMMENT 'URL重写值',
  `classtemplate` varchar(50) NOT NULL COMMENT '栏目模版',
  `newstemplate` varchar(50) NOT NULL COMMENT '文章模版',
  `classimg` varchar(100) DEFAULT NULL COMMENT '栏目预览图',
  `classapv` int(10) NOT NULL DEFAULT '0' COMMENT '栏目浏览量',
  `classdomain` varchar(100) DEFAULT NULL COMMENT '栏目二级域名',
  `classouturl` varchar(150) DEFAULT NULL COMMENT '栏目外部网址',
  `classmenushow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '前台菜单中显示',
  PRIMARY KEY (`classid`),
  KEY `pid` (`classpid`)
) ENGINE=MyISAM AUTO_INCREMENT=282 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_category_map
-- ----------------------------
DROP TABLE IF EXISTS `ch_category_map`;
CREATE TABLE `ch_category_map` (
  `mapid` int(10) NOT NULL AUTO_INCREMENT COMMENT '一条信息属于多个栏目的映射关系表',
  `mapissource` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否源头',
  `mapinfoid` int(10) NOT NULL COMMENT '信息ID',
  `mapclassid` int(10) NOT NULL COMMENT '所属栏目',
  `mapclassmodule` varchar(30) NOT NULL COMMENT '所属模型',
  PRIMARY KEY (`mapid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_download
-- ----------------------------
DROP TABLE IF EXISTS `ch_download`;
CREATE TABLE `ch_download` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `tid` varchar(50) NOT NULL COMMENT '所属分类',
  `title` varchar(100) NOT NULL COMMENT '软件标题',
  `rjbb` varchar(100) NOT NULL COMMENT '软件版本',
  `isb` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加粗',
  `isi` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否斜体',
  `tcolor` char(10) DEFAULT NULL COMMENT '标题颜色',
  `keywords` varchar(100) DEFAULT NULL COMMENT '关键字',
  `description` varchar(200) DEFAULT NULL COMMENT '描述',
  `img` varchar(100) NOT NULL COMMENT '预览图片',
  `content` longtext NOT NULL COMMENT '内容',
  `add_time` int(10) NOT NULL COMMENT '录入时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  `adder_id` int(10) NOT NULL COMMENT '录入人',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序值',
  `apv` smallint(5) DEFAULT '0' COMMENT '浏览量',
  `rewrite` varchar(50) NOT NULL COMMENT 'URL重写值',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 1:启用,0:禁用',
  `template` varchar(50) DEFAULT NULL COMMENT '使用模板',
  `attrtj` varchar(30) DEFAULT NULL COMMENT '推荐属性',
  `attrtt` varchar(30) DEFAULT NULL COMMENT '头条属性',
  `outurl` varchar(150) DEFAULT NULL COMMENT '外部网址',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_key
-- ----------------------------
DROP TABLE IF EXISTS `ch_key`;
CREATE TABLE `ch_key` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `keyname` varchar(50) NOT NULL COMMENT '关键字名称',
  `url` varchar(100) NOT NULL COMMENT '关键字链接地址',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_message
-- ----------------------------
DROP TABLE IF EXISTS `ch_message`;
CREATE TABLE `ch_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0',
  `adder_name` varchar(50) NOT NULL,
  `adder_email` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1:文章评论,2:留言',
  `adder_id` int(10) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `add_time` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `modelname` varchar(20) NOT NULL DEFAULT '' COMMENT '模型名称',
  `modekeyvalue` varchar(20) NOT NULL DEFAULT '' COMMENT '模型主键值',
  `fromtitle` varchar(100) NOT NULL DEFAULT '' COMMENT '来源标题',
  `fromurl` varchar(150) NOT NULL DEFAULT '' COMMENT '来源url',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_model
-- ----------------------------
DROP TABLE IF EXISTS `ch_model`;
CREATE TABLE `ch_model` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '模型表',
  `ename` varchar(20) NOT NULL DEFAULT '' COMMENT '模块名称',
  `cname` varchar(50) NOT NULL DEFAULT '' COMMENT '显示名称',
  `notes` text NOT NULL COMMENT '应用描述',
  `menugroup` tinyint(2) NOT NULL DEFAULT '2' COMMENT '属于大菜单',
  `sort` tinyint(3) NOT NULL DEFAULT '1' COMMENT '排序',
  `author` varchar(30) NOT NULL DEFAULT '' COMMENT '开发作者',
  `version` varchar(15) NOT NULL DEFAULT '' COMMENT '版本',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ename` (`ename`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_model_children
-- ----------------------------
DROP TABLE IF EXISTS `ch_model_children`;
CREATE TABLE `ch_model_children` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '模型子菜单表',
  `ename` varchar(20) NOT NULL DEFAULT '' COMMENT '模型名称',
  `childmenus` text NOT NULL,
  `childtables` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ename` (`ename`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_model_fieldnotes
-- ----------------------------
DROP TABLE IF EXISTS `ch_model_fieldnotes`;
CREATE TABLE `ch_model_fieldnotes` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '模型字段附加信息',
  `ename` varchar(20) NOT NULL DEFAULT '',
  `fieldnotes` mediumtext NOT NULL,
  `fieldsmap` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_music
-- ----------------------------
DROP TABLE IF EXISTS `ch_music`;
CREATE TABLE `ch_music` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(100) NOT NULL COMMENT '音乐名',
  `author` varchar(50) DEFAULT NULL COMMENT '作者',
  `file` varchar(100) NOT NULL COMMENT '上传音乐',
  `sort` int(10) NOT NULL COMMENT '排序值 0',
  `tid` int(10) NOT NULL COMMENT '所属分类 classid',
  `adder_id` int(10) NOT NULL COMMENT '录入人 userid',
  `add_time` int(10) NOT NULL COMMENT '录入时间 time()',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 1:启用,0:禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_node
-- ----------------------------
DROP TABLE IF EXISTS `ch_node`;
CREATE TABLE `ch_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_photo
-- ----------------------------
DROP TABLE IF EXISTS `ch_photo`;
CREATE TABLE `ch_photo` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(100) NOT NULL COMMENT '图片名称',
  `isb` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加粗',
  `isi` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否斜体',
  `tcolor` char(10) DEFAULT NULL COMMENT '标题颜色',
  `intro` varchar(200) DEFAULT NULL COMMENT '图片简介',
  `img` varchar(100) NOT NULL COMMENT '图片上传',
  `link` varchar(150) DEFAULT NULL COMMENT '链接地址',
  `tid` int(10) NOT NULL COMMENT '所属分类 classid',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序值',
  `adder_id` int(10) NOT NULL COMMENT '录入人 userid',
  `add_time` int(10) NOT NULL COMMENT '添加时间 time()',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0:禁用,1:启用',
  `attrtj` varchar(30) DEFAULT NULL COMMENT '推荐属性',
  `attrtt` varchar(30) DEFAULT NULL COMMENT '头条属性',
  `outurl` varchar(150) DEFAULT NULL COMMENT '外部网址',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_role
-- ----------------------------
DROP TABLE IF EXISTS `ch_role`;
CREATE TABLE `ch_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
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
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` char(32) NOT NULL DEFAULT '0',
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `rewrite` (`rewrite`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM AUTO_INCREMENT=257 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_test
-- ----------------------------
DROP TABLE IF EXISTS `ch_test`;
CREATE TABLE `ch_test` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `tid` varchar(50) NOT NULL COMMENT '所属分类',
  `title` varchar(100) NOT NULL COMMENT '测试标题',
  `keywords` varchar(100) NOT NULL COMMENT '关键字',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `img` varchar(100) NOT NULL COMMENT '预览图片',
  `content` longtext COMMENT '内容',
  `add_time` int(10) DEFAULT NULL COMMENT '录入时间',
  `update_time` int(10) DEFAULT NULL COMMENT '修改时间',
  `adder_id` int(10) NOT NULL COMMENT '录入人',
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序值',
  `apv` smallint(5) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `rewrite` varchar(50) NOT NULL COMMENT 'URL重写值',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1:启用,0:禁用',
  `template` varchar(50) DEFAULT NULL COMMENT '使用模板',
  `isb` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加粗',
  `isi` tinyint(1) NOT NULL DEFAULT '0' COMMENT '斜体',
  `tcolor` char(10) DEFAULT NULL COMMENT '颜色',
  `attrtj` varchar(30) DEFAULT NULL COMMENT '推荐',
  `attrtt` varchar(30) DEFAULT NULL COMMENT '头条',
  `outurl` varchar(150) DEFAULT NULL COMMENT '外部网址',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`),
  KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_ui
-- ----------------------------
DROP TABLE IF EXISTS `ch_ui`;
CREATE TABLE `ch_ui` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '模板对应表',
  `tplgroup` varchar(50) NOT NULL DEFAULT '' COMMENT '模板分组',
  `tplname` varchar(100) NOT NULL DEFAULT '' COMMENT '模板名称',
  `tplpath` varchar(100) NOT NULL DEFAULT '' COMMENT '模板路径',
  PRIMARY KEY (`id`),
  KEY `tplgroup` (`tplgroup`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_user
-- ----------------------------
DROP TABLE IF EXISTS `ch_user`;
CREATE TABLE `ch_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(20) NOT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for ch_video
-- ----------------------------
DROP TABLE IF EXISTS `ch_video`;
CREATE TABLE `ch_video` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(100) NOT NULL COMMENT '视频标题',
  `isb` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加粗',
  `isi` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否斜体',
  `tcolor` char(10) DEFAULT NULL COMMENT '标题颜色',
  `keywords` varchar(100) DEFAULT NULL COMMENT '关键字',
  `description` varchar(200) DEFAULT NULL COMMENT '描述',
  `img` varchar(100) DEFAULT NULL COMMENT '小图片',
  `file` varchar(100) NOT NULL COMMENT '上传视频',
  `rewrite` varchar(50) DEFAULT NULL COMMENT 'URL重写',
  `sort` int(10) NOT NULL COMMENT '排序值',
  `tid` int(10) NOT NULL COMMENT '所属分类',
  `adder_id` int(10) NOT NULL COMMENT '录入人',
  `add_time` int(10) NOT NULL COMMENT '录入时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `attrtj` varchar(30) DEFAULT NULL COMMENT '推荐属性',
  `attrtt` varchar(30) DEFAULT NULL COMMENT '头条属性',
  `outurl` varchar(150) DEFAULT NULL COMMENT '外部网址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `ch_access` VALUES ('1', '1', '1', '0', '');
INSERT INTO `ch_access` VALUES ('1', '7', '3', '6', '');
INSERT INTO `ch_access` VALUES ('1', '15', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '14', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '13', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '12', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '11', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '10', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '9', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '8', '3', '5', '');
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
INSERT INTO `ch_access` VALUES ('1', '81', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '80', '3', '5', '');
INSERT INTO `ch_access` VALUES ('1', '109', '2', '1', '');
INSERT INTO `ch_access` VALUES ('1', '6', '2', '1', '');
INSERT INTO `ch_access` VALUES ('1', '5', '2', '1', '');
INSERT INTO `ch_article` VALUES ('10', '225', '设计师汤娇', '1', '0', '#6600CC', '设计师 汤娇', '设计师汤娇', '5097710ac66b8.jpg', '<p></p><p>设计师汤娇，汤娇上面自动加了内链接的哟。</p><p><br /></p><p></p><p style=\"text-align: center;\"><img src=\"/Public/Upload/article/5097710ac66b8.jpg\" alt=\"\" /><br /></p>', '1346134963', '1369640133', '1', '200', '776', 'help', '1', '', '1,2,3', '1,2', '');
INSERT INTO `ch_article` VALUES ('12', '245', '中国商人黄怒波称冰岛租地尚未确定签约时间表 ', '0', '0', '', '冰岛 租地 签约 集团 时间表 董事长', '北京中坤投资集团董事长黄怒波[CHPAGE]中新网11月4日电进入11月，中坤集团冰岛租地项目未能在10月如期签约引来', '5096659d69a30.jpg', '<p class=\"f_center\"></p><div style=\"TEXT-ALIGN: center\"><img alt=\"北京中坤投资集团董事长黄怒波\" src=\"/Public/Upload/article/remote_130316/51442ed540fc0.jpg\" /></div><div style=\"TEXT-ALIGN: center\">北京中坤投资集团董事长黄怒波</div>[CHPAGE]<p></p><p>中新网11月4日电 进入11月，中坤集团冰岛租地项目未能在10月如期签约引来&quot;项目再度陷入搁置&quot;的猜测。对此，中坤集团董事长黄怒波今日在北京论坛的会议间隙向中新网房产频道坦言，关于冰岛项目现在尚无法确认具体的签约时间，不过我们一直在等待冰岛政府方面的时间安排，现在也在继续耐心等待。</p>[CHPAGE]<p>由于冰岛政府的大选临近，黄怒波表示，项目签约恐怕会受此影响而延期。对于&quot;预计年内能否完成签约&quot;这一问题，黄怒波也只是在采访中再三表示，&quot;需要耐心等待&quot;。</p><p>10月，黄怒波曾高调宣布，冰岛政府将于10月来京与其签约，中坤集团则将以600多万美元的价格租下冰岛300平方公里土地。但是至到时间进入到11月，这一签约仍未能完成，&quot;黄怒波进军冰岛再度被搁置&quot;的传言也因此不胫而走。</p><p>对此，北京晨报（<a class=\"daren-keyword\" userid=\"-7989951459593063769\">微博</a>）在11月2日的报道中援引中坤集团副总裁徐红的话称，网传&quot;被搁置&quot;的说法不准确。冰岛项目进展一直都不容易，中坤更是做好了长期谈判的准备。而无论该项目是否成功，中坤在这&quot;一波三折&quot;的事件中带来的品牌溢价，早就超过了投资额及企业本身的规模和实力。</p><p>徐红用&quot;一波三折&quot;来形容中坤投资冰岛之路并不夸张。此前的2011年8月，中坤集团欲以890万美元购买300平方公里冰岛土地，用于旅游地产开发，但购地计划后被冰岛政府终止。随后，中坤将买地改为租地，计划以600多万美元的价格租下冰岛300平方公里土地，但租地合作开发至今仍无结果。</p><p>不过，除了在冰岛投资旅游地产外，中坤在国内也没闲着。3月，中坤和云南省普洱市达成框架协议，启动中国普洱休闲养生部落项目，总投资规模预计500亿元。黄怒波也在采访中向中新网房产频道表示，&quot;未来的十年主要就是把这个项目做好。&quot;</p><p>黄怒波十分看好中国旅游地产市场的发展前景。他表示，中央对楼市的调控势在必行，许多房企也纷纷开始转型以求在旅游地产的市场分得一杯羹。此前就有媒体在报道中称，目前房地产行业前100名的企业中，已有接近三分之二的企业介入了旅游地产领域。</p><p>而对于房企一窝蜂地涌入旅游地产，黄怒波则面露担忧之色。他表示，旅游地产对很多房企而言，已然是从住宅产业转型的救命稻草，但是许多隐患也渐渐显露出来。&quot;旅游地产这个行业，有许多稀缺的自然资源不可复制，但是经过一些企业像野牛和大象一样野蛮式的开发，很多地方已经被破坏了。&quot;</p>', '1352033505', '1369633251', '1', '199', '1744', 'shangren', '1', '', '1,2', '1,2', '');
INSERT INTO `ch_article` VALUES ('9', '241,246,245', 'CMSHead支持外部图片 一篇文章属于多个栏目', '0', '0', '', 'CMSHead 支持 外部 图片 一篇 文章', 'CMSHead支持外部图片一篇文章属于多个栏目CMSHead支持外部图片一篇文章属于多个栏目CMSHead支持外部图片一篇文章属于多个栏目', 'http://www.baidu.com/img/shouye_b5486898c692066bd2cbaeda86d74448.gif', '<p>CMSHead支持外部图片 一篇文章属于多个栏目</p><p>CMSHead支持外部图片 一篇文章属于多个栏目<br /></p><p>CMSHead支持外部图片 一篇文章属于多个栏目<br /></p>', '1352039941', '1369633258', '1', '100', '200', 'article9', '1', '{tplroot}default/Article/view3.html', '1,2', '1', '');
INSERT INTO `ch_article` VALUES ('40', '242', 'CMSHead支持外部文章', '1', '0', '#0000FF', '', '', '', 'CMSHead支持外部文章', '1362902239', '1364732988', '1', '1', '6', 'article40', '1', '', '1,3', '1,3', 'http://hongdasoft.net/');
INSERT INTO `ch_article` VALUES ('13', '241,268', 'CMSHead支持文章附属不同模块的栏目', '0', '0', '', '', '', 'remote_130315/5142e806451f0.jpg', '<p style=\"text-align: center;\"><img src=\"/Public/Upload/article/remote_130315/5142e806451f0.jpg\" alt=\"\" /></p><p style=\"text-align: center;\">CMSHead支持文章附属不同模块的栏目</p><p style=\"text-align: center;\">比如测试模块，视频模块等的栏目附属到文章模块的“首页推荐”栏目，这样在做大型网站的时候，各栏目可以把需要在首页显示的信息方便的推送到首页。</p><p><br /></p>', '1363339211', '1364738696', '1', '0', '8', 'article13', '1', '', '1', '1', '');
INSERT INTO `ch_article` VALUES ('48', '241', 'CMSHead支持后台URL重写值为空时 自动设置为模块名加id值', '0', '0', '', '', '', 'remote_130315/5142e8a353996.jpg', '<p><img src=\"/Public/Upload/article/remote_130315/5142e8a353996.jpg\" alt=\"\" /></p><p><br /></p><p>CMSHead支持后台URL重写值为空时 自动设置为模块名加id值<br /></p>', '1363339313', '1364733037', '1', '20', '1', 'article48', '1', '', '', ',1', '');
INSERT INTO `ch_article` VALUES ('49', '241', 'CMSHead支持图片本地化', '0', '0', '', '', '', 'remote_130315/5142e8be00232.jpg', '<p><img src=\"/Public/Upload/article/remote_130315/5142e8be00232.jpg\" alt=\"\" /></p><p>CMSHead支持图片本地化，远程文件本地化<br /></p>', '1363339436', '1364733017', '1', '0', '1', 'article49', '1', '', ',1', '1', '');
INSERT INTO `ch_article` VALUES ('53', '268', 'CMSHead V2.1版本亮点功能介绍及下载地址', '0', '0', '', '', '', '', '', '1364739811', '1364740318', '1', '0', '0', 'article53', '1', '', '1,2,3', '1,2,3', '__ROOT__/CMSHead21.html');
INSERT INTO `ch_article` VALUES ('54', '242', 'CMSHead支持外部文章', '1', '0', '#0000FF', '', '', '', 'CMSHead支持外部文章', '1362902239', '1364732988', '1', '1', '6', '', '1', '', '1,3', '1,3', 'http://hongdasoft.net/');
INSERT INTO `ch_article` VALUES ('55', '267', 'CMSHead支持外部文章', '1', '0', '#0000FF', '', '', '', 'CMSHead支持外部文章', '1362902239', '1364732988', '1', '1', '6', '', '1', '', '1,3', '1,3', 'http://hongdasoft.net/');
INSERT INTO `ch_article` VALUES ('56', '242', '测试文章一条CMSHead自动URL重写值', '0', '0', '', '', '', '', '<p>测试文章一条CMSHead自动URL重写值测试文章一条CMSHead自动URL重写值测试文章一条CMSHead自动URL重写值测试文章一条CMSHead自动URL重写值测试文章一条CMSHead自动URL重写值测试文章一条CMSHead自动URL重写值测试文章一条CMSHead自动URL重写值测试文章一条CMSHead自动URL重写值</p><p><img alt=\"\" src=\"/Public/Upload/article/day_130523/201305231231568008.jpg\" /></p>', '1364748567', '1369283523', '1', '0', '0', 'article56', '1', '', '', '', '');
INSERT INTO `ch_article` VALUES ('71', '241', '设计师李芸', '0', '0', '', '', '', '', '<p>设计师李芸</p><p>设计师李芸设计师李芸设计师李芸</p>', '1369633321', '1369633366', '1', '0', '17', '', '1', '', '', '', '');
INSERT INTO `ch_article` VALUES ('72', '267', '汤娇就是很漂亮', '0', '0', '', '汤娇', '', '', '<span style=\"text-align: center; padding: 0px; margin: 0px; \">汤娇确实是非常漂亮的。她是一个设计师。装修方面的设计师哦。</span>', '1369634323', '1369634550', '1', '0', '12', '', '1', '', '', '', '');
INSERT INTO `ch_article` VALUES ('73', '243', '汤娇真的漂亮吗？', '0', '0', '', '汤娇 漂亮', '', '', '汤娇真的漂亮吗？这个谁也说不清楚。请点击汤娇进入查看照片分辨。 请鼠标移到汤娇上面去看看超链接，自动加上的链接哦，在后台架构管理-关键字管理里维护，汤娇真的漂亮吗？这个谁也说不清楚。请点击汤娇进入查看照片分辨。<br />', '1369635205', '1369640427', '1', '0', '7', 'article73', '1', '', '', '', '');
INSERT INTO `ch_attribute` VALUES ('13', 'tuijian', '首页推荐', '1', '100', '1');
INSERT INTO `ch_attribute` VALUES ('14', 'tuijian', '频道推荐', '2', '99', '1');
INSERT INTO `ch_attribute` VALUES ('15', 'tuijian', '栏目推荐', '3', '98', '1');
INSERT INTO `ch_attribute` VALUES ('16', 'tuijian', '推荐', '4', '97', '1');
INSERT INTO `ch_attribute` VALUES ('17', 'toutiao', '头条一', '1', '96', '1');
INSERT INTO `ch_attribute` VALUES ('18', 'toutiao', '头条二', '2', '95', '1');
INSERT INTO `ch_attribute` VALUES ('19', 'toutiao', '头条三', '3', '94', '1');
INSERT INTO `ch_category` VALUES ('233', '0', '', '1', '233,247,260', 'a:3:{i:0;s:3:\"233\";i:1;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"247\";s:10:\"classtitle\";s:12:\"收藏图片\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"247\";}i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"260\";s:10:\"classtitle\";s:12:\"美女图片\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"260\";}}', '图库', '0', '1', '', '', 'Photo', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('234', '0', '', '1', '234,248,249,250,251,252,253', 'a:3:{i:0;s:3:\"234\";i:1;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"248\";s:10:\"classtitle\";s:12:\"搞笑视频\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"248\";}i:2;a:4:{i:0;a:3:{s:7:\"classid\";s:3:\"249\";s:10:\"classtitle\";s:9:\"动作片\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"249\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"250\";s:10:\"classtitle\";s:15:\"好莱坞大片\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"250\";}i:3;a:4:{i:0;a:3:{s:7:\"classid\";s:3:\"251\";s:10:\"classtitle\";s:15:\"香港动作片\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"251\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"252\";s:10:\"classtitle\";s:9:\"甄子丹\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"252\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"253\";s:10:\"classtitle\";s:6:\"吴京\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"253\";}}}}', '视频', '0', '1', '', '', 'Video', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('235', '0', '', '1', '235,237,254,255,257,258,256', 'a:3:{i:0;s:3:\"235\";i:1;a:2:{i:0;a:4:{s:7:\"classid\";s:3:\"237\";s:10:\"classtitle\";s:12:\"古典音乐\";s:10:\"classchild\";s:1:\"0\";s:11:\"classmodule\";s:5:\"Music\";}i:1;s:3:\"237\";}i:2;a:4:{i:0;a:4:{s:7:\"classid\";s:3:\"254\";s:10:\"classtitle\";s:12:\"伤感歌曲\";s:10:\"classchild\";s:1:\"1\";s:11:\"classmodule\";s:5:\"Music\";}i:1;s:3:\"254\";i:2;a:4:{i:0;a:3:{s:7:\"classid\";s:3:\"255\";s:10:\"classtitle\";s:9:\"男歌手\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"255\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"257\";s:10:\"classtitle\";s:9:\"张学友\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"257\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"258\";s:10:\"classtitle\";s:9:\"刘德华\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"258\";}}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"256\";s:10:\"classtitle\";s:9:\"女歌手\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"256\";}}}', '音乐', '0', '1', '', '', 'Music', 'yinle', '', '', null, '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('224', '0', '', '1', '224,227,269,279,225,238,239,242,243,244,240,245,246,241,226,267', 'a:4:{i:0;s:3:\"224\";i:1;a:4:{i:0;a:4:{s:7:\"classid\";s:3:\"227\";s:10:\"classtitle\";s:12:\"社会新闻\";s:10:\"classchild\";s:1:\"1\";s:11:\"classmodule\";s:7:\"Article\";}i:1;s:3:\"227\";i:2;a:2:{i:0;a:4:{s:7:\"classid\";s:3:\"269\";s:10:\"classtitle\";s:18:\"栏目下的图片\";s:10:\"classchild\";s:1:\"0\";s:11:\"classmodule\";s:5:\"Photo\";}i:1;s:3:\"269\";}i:3;a:2:{i:0;a:4:{s:7:\"classid\";s:3:\"279\";s:10:\"classtitle\";s:18:\"栏目下的下载\";s:10:\"classchild\";s:1:\"0\";s:11:\"classmodule\";s:8:\"Download\";}i:1;s:3:\"279\";}}i:2;a:3:{i:0;a:4:{s:7:\"classid\";s:3:\"225\";s:10:\"classtitle\";s:12:\"国内新闻\";s:10:\"classchild\";s:1:\"1\";s:11:\"classmodule\";s:7:\"Article\";}i:1;s:3:\"225\";i:2;a:5:{i:0;a:4:{s:7:\"classid\";s:3:\"238\";s:10:\"classtitle\";s:12:\"江苏新闻\";s:10:\"classchild\";s:1:\"1\";s:11:\"classmodule\";s:7:\"Article\";}i:1;s:3:\"238\";i:2;a:5:{i:0;a:3:{s:7:\"classid\";s:3:\"239\";s:10:\"classtitle\";s:12:\"常州新闻\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"239\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"242\";s:10:\"classtitle\";s:12:\"武进新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"242\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"243\";s:10:\"classtitle\";s:12:\"新北新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"243\";}i:4;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"244\";s:10:\"classtitle\";s:12:\"天宁新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"244\";}}i:3;a:4:{i:0;a:3:{s:7:\"classid\";s:3:\"240\";s:10:\"classtitle\";s:12:\"南京新闻\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"240\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"245\";s:10:\"classtitle\";s:12:\"江宁新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"245\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"246\";s:10:\"classtitle\";s:12:\"六和新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"246\";}}i:4;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"241\";s:10:\"classtitle\";s:12:\"苏州新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"241\";}}}i:3;a:3:{i:0;a:4:{s:7:\"classid\";s:3:\"226\";s:10:\"classtitle\";s:12:\"国际新闻\";s:10:\"classchild\";s:1:\"1\";s:11:\"classmodule\";s:7:\"Article\";}i:1;s:3:\"226\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"267\";s:10:\"classtitle\";s:12:\"吴晶新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"267\";}}}', '新闻中心', '2', '1', '', '', 'Article', 'xwzx', '{tplroot}default/Article/index.html', '', null, '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('225', '224', '0,224', '1', '225,238,239,242,243,244,240,245,246,241', 'a:2:{i:0;s:3:\"225\";i:1;a:5:{i:0;a:4:{s:7:\"classid\";s:3:\"238\";s:10:\"classtitle\";s:12:\"江苏新闻\";s:10:\"classchild\";s:1:\"1\";s:11:\"classmodule\";s:7:\"Article\";}i:1;s:3:\"238\";i:2;a:5:{i:0;a:3:{s:7:\"classid\";s:3:\"239\";s:10:\"classtitle\";s:12:\"常州新闻\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"239\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"242\";s:10:\"classtitle\";s:12:\"武进新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"242\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"243\";s:10:\"classtitle\";s:12:\"新北新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"243\";}i:4;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"244\";s:10:\"classtitle\";s:12:\"天宁新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"244\";}}i:3;a:4:{i:0;a:3:{s:7:\"classid\";s:3:\"240\";s:10:\"classtitle\";s:12:\"南京新闻\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"240\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"245\";s:10:\"classtitle\";s:12:\"江宁新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"245\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"246\";s:10:\"classtitle\";s:12:\"六和新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"246\";}}i:4;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"241\";s:10:\"classtitle\";s:12:\"苏州新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"241\";}}}', '国内新闻', '1', '1', '', '', 'Article', '', '', '', null, '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('226', '224', '0,224', '1', '226,267', 'a:2:{i:0;s:3:\"226\";i:1;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"267\";s:10:\"classtitle\";s:12:\"吴晶新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"267\";}}', '国际新闻', '0', '1', '', '', 'Article', 'gjxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('227', '224', '0,224', '1', '227,269,279', 'a:3:{i:0;s:3:\"227\";i:1;a:2:{i:0;a:4:{s:7:\"classid\";s:3:\"269\";s:10:\"classtitle\";s:18:\"栏目下的图片\";s:10:\"classchild\";s:1:\"0\";s:11:\"classmodule\";s:5:\"Photo\";}i:1;s:3:\"269\";}i:2;a:2:{i:0;a:4:{s:7:\"classid\";s:3:\"279\";s:10:\"classtitle\";s:18:\"栏目下的下载\";s:10:\"classchild\";s:1:\"0\";s:11:\"classmodule\";s:8:\"Download\";}i:1;s:3:\"279\";}}', '社会新闻', '100', '1', '', '', 'Article', 'shxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('237', '235', '0,235', '0', '237', 'a:1:{i:0;s:3:\"237\";}', '古典音乐', '0', '1', '', '', 'Music', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('238', '225', '0,224,225', '1', '238,239,242,243,244,240,245,246,241', 'a:4:{i:0;s:3:\"238\";i:1;a:5:{i:0;a:3:{s:7:\"classid\";s:3:\"239\";s:10:\"classtitle\";s:12:\"常州新闻\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"239\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"242\";s:10:\"classtitle\";s:12:\"武进新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"242\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"243\";s:10:\"classtitle\";s:12:\"新北新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"243\";}i:4;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"244\";s:10:\"classtitle\";s:12:\"天宁新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"244\";}}i:2;a:4:{i:0;a:3:{s:7:\"classid\";s:3:\"240\";s:10:\"classtitle\";s:12:\"南京新闻\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"240\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"245\";s:10:\"classtitle\";s:12:\"江宁新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"245\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"246\";s:10:\"classtitle\";s:12:\"六和新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"246\";}}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"241\";s:10:\"classtitle\";s:12:\"苏州新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"241\";}}', '江苏新闻', '0', '1', '', '', 'Article', 'jsxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('239', '238', '0,224,225,238', '1', '239,242,243,244', 'a:4:{i:0;s:3:\"239\";i:1;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"242\";s:10:\"classtitle\";s:12:\"武进新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"242\";}i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"243\";s:10:\"classtitle\";s:12:\"新北新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"243\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"244\";s:10:\"classtitle\";s:12:\"天宁新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"244\";}}', '常州新闻', '0', '1', '', '', 'Article', 'czxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('240', '238', '0,224,225,238', '1', '240,245,246', 'a:3:{i:0;s:3:\"240\";i:1;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"245\";s:10:\"classtitle\";s:12:\"江宁新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"245\";}i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"246\";s:10:\"classtitle\";s:12:\"六和新闻\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"246\";}}', '南京新闻', '0', '1', '', '', 'Article', 'njxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('241', '238', '0,224,225,238', '0', '241', 'a:1:{i:0;s:3:\"241\";}', '苏州新闻', '0', '1', '', '', 'Article', 'szxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('242', '239', '0,224,225,238,239', '0', '242', 'a:1:{i:0;s:3:\"242\";}', '武进新闻', '0', '1', '', '', 'Article', 'wjxw', '', '', '51417bc7ea399.jpg', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('243', '239', '0,224,225,238,239', '0', '243', 'a:1:{i:0;s:3:\"243\";}', '新北新闻', '0', '1', '', '', 'Article', 'xbxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('244', '239', '0,224,225,238,239', '0', '244', 'a:1:{i:0;s:3:\"244\";}', '天宁新闻', '0', '1', '', '', 'Article', 'tnxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('245', '240', '0,224,225,238,240', '0', '245', 'a:1:{i:0;s:3:\"245\";}', '江宁新闻', '0', '1', '', '', 'Article', 'jnxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('246', '240', '0,224,225,238,240', '0', '246', 'a:1:{i:0;s:3:\"246\";}', '六和新闻', '0', '1', '', '', 'Article', 'lhxw', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('247', '233', '0,233', '0', '247', 'a:1:{i:0;s:3:\"247\";}', '收藏图片', '0', '1', '', '', 'Photo', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('248', '234', '0,234', '0', '248', 'a:1:{i:0;s:3:\"248\";}', '搞笑视频', '0', '1', '', '', 'Video', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('249', '234', '0,234', '1', '249,250,251,252,253', 'a:3:{i:0;s:3:\"249\";i:1;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"250\";s:10:\"classtitle\";s:15:\"好莱坞大片\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"250\";}i:2;a:4:{i:0;a:3:{s:7:\"classid\";s:3:\"251\";s:10:\"classtitle\";s:15:\"香港动作片\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"251\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"252\";s:10:\"classtitle\";s:9:\"甄子丹\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"252\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"253\";s:10:\"classtitle\";s:6:\"吴京\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"253\";}}}', '动作片', '0', '1', '', '', 'Video', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('250', '249', '0,234,249', '0', '250', 'a:1:{i:0;s:3:\"250\";}', '好莱坞大片', '0', '1', '', '', 'Video', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('251', '249', '0,234,249', '1', '251,252,253', 'a:3:{i:0;s:3:\"251\";i:1;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"252\";s:10:\"classtitle\";s:9:\"甄子丹\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"252\";}i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"253\";s:10:\"classtitle\";s:6:\"吴京\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"253\";}}', '香港动作片', '0', '1', '', '', 'Video', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('252', '251', '0,234,249,251', '0', '252', 'a:1:{i:0;s:3:\"252\";}', '甄子丹', '0', '1', '', '', 'Video', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('253', '251', '0,234,249,251', '0', '253', 'a:1:{i:0;s:3:\"253\";}', '吴京', '0', '1', '', '', 'Video', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('254', '235', '0,235', '1', '254,255,257,258,256', 'a:3:{i:0;s:3:\"254\";i:1;a:4:{i:0;a:3:{s:7:\"classid\";s:3:\"255\";s:10:\"classtitle\";s:9:\"男歌手\";s:10:\"classchild\";s:1:\"1\";}i:1;s:3:\"255\";i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"257\";s:10:\"classtitle\";s:9:\"张学友\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"257\";}i:3;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"258\";s:10:\"classtitle\";s:9:\"刘德华\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"258\";}}i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"256\";s:10:\"classtitle\";s:9:\"女歌手\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"256\";}}', '伤感歌曲', '0', '1', '', '', 'Music', '', '', '', null, '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('255', '254', '0,235,254', '1', '255,257,258', 'a:3:{i:0;s:3:\"255\";i:1;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"257\";s:10:\"classtitle\";s:9:\"张学友\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"257\";}i:2;a:2:{i:0;a:3:{s:7:\"classid\";s:3:\"258\";s:10:\"classtitle\";s:9:\"刘德华\";s:10:\"classchild\";s:1:\"0\";}i:1;s:3:\"258\";}}', '男歌手', '0', '1', '', '', 'Music', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('256', '254', '0,235,254', '0', '256', 'a:1:{i:0;s:3:\"256\";}', '女歌手', '0', '1', '', '', 'Music', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('257', '255', '0,235,254,255', '0', '257', 'a:1:{i:0;s:3:\"257\";}', '张学友', '0', '1', '', '', 'Music', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('258', '255', '0,235,254,255', '0', '258', 'a:1:{i:0;s:3:\"258\";}', '刘德华', '0', '1', '', '', 'Music', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('260', '233', '0,233', '0', '260', 'a:1:{i:0;s:3:\"260\";}', '美女图片', '0', '1', '', '', 'Photo', '', '', '', '', '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('264', '0', '', '0', '264', 'a:1:{i:0;s:3:\"264\";}', '测试栏目', '0', '1', '', '', 'Test', 'ceshilanmu', '', '{tplroot}default/Test/view.html', null, '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('269', '227', '0,224,227', '0', '269', 'a:1:{i:0;s:3:\"269\";}', '栏目下的图片', '0', '1', '', '', 'Photo', '', '', '', null, '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('267', '226', '0,224,226', '0', '267', 'a:1:{i:0;s:3:\"267\";}', '吴晶新闻', '0', '1', '', '', 'Article', 'wjxw', '', '', null, '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('268', '0', '', '0', '268', 'a:1:{i:0;s:3:\"268\";}', '首页推荐', '0', '1', '', '', 'Article', '', '', '', null, '0', '', '', '0');
INSERT INTO `ch_category` VALUES ('270', '0', '', '0', '270', 'a:1:{i:0;s:3:\"270\";}', '下载中心', '0', '1', '', '', 'Download', 'down', '', '', null, '0', '', '', '1');
INSERT INTO `ch_category` VALUES ('279', '227', '0,224,227', '0', '279', 'a:1:{i:0;s:3:\"279\";}', '栏目下的下载', '0', '1', '', '', 'Download', 'lanmuxiadexiazai', '', '', null, '0', '', '', '1');
INSERT INTO `ch_category_map` VALUES ('1', '1', '13', '264', 'Test');
INSERT INTO `ch_category_map` VALUES ('2', '0', '13', '268', 'Article');
INSERT INTO `ch_category_map` VALUES ('3', '0', '5', '268', 'Article');
INSERT INTO `ch_category_map` VALUES ('4', '1', '5', '270', 'Download');
INSERT INTO `ch_category_map` VALUES ('5', '0', '244', '268', 'Article');
INSERT INTO `ch_category_map` VALUES ('6', '1', '244', '270', 'Download');
INSERT INTO `ch_category_map` VALUES ('7', '0', '7', '268', 'Article');
INSERT INTO `ch_category_map` VALUES ('8', '1', '7', '270', 'Download');
INSERT INTO `ch_diary` VALUES ('4', '晴朗', '理论网苑正式上线，若有问题请联系我们！', '1', '1347120134', '1');
INSERT INTO `ch_diary` VALUES ('7', '晴朗', '最后更新2013-03-01，将TP框架升级到了最新版—ThinkPHP3.1.2云引擎版本，优化了缓存，请到软件下载中下载最新。', '1', '1362375353', '1');
INSERT INTO `ch_download` VALUES ('5', '270,268', 'CMSHead V2.1_20130401下载', 'V2.1_20130401', '0', '0', '', '', '二级域名，跨模块调用，栏目级的模块混建，可配置页面后缀加上.html，可开启全站整页缓存，文章复制、移动，属性替换，栏目的复制、移动、合并、属性替换，URL重写值为空时自动以模块名+id或栏目中文名全拼作为URL重写值，加了install安装文件，不用选栏目的全站搜索，模版和目录支持中文名，其他的包括文件管理等BUG修复和完善等等，做一个中型门户网站都没挺轻松实现的啦。', '51584227d102c.jpg', '<p><span style=\"color: rgb(0, 0, 153); font-size: 16px;\">CMSHead V2.1_20130401 官网下载地址：</span></p><p><a href=\"http://www.cmshead.com/CMSHeadV2.1_20130401.rar\"><span style=\"font-size: 16px; color: rgb(0, 0, 153);\">http://www.cmshead.com/CMSHeadV2.1_20130401.rar</span></a><br /><br /></p><p><span style=\"color: rgb(0, 0, 153); font-size: 16px; \">CMSHead V2.1_20130401 分流合作网站下载地址：</span><br /></p><p><span style=\"color: rgb(0, 0, 153); font-size: 16px; \"><a href=\"http://down.admin5.com/php/101767.html\" target=\"_blank\">admin5下载</a>&nbsp; &nbsp;&nbsp;<a href=\"http://down.cnzz.cn/info/87814.aspx\" target=\"_blank\">cnzz下载</a>&nbsp; &nbsp;&nbsp;<a href=\"http://edong.onlinedown.net/soft/465150.htm\" target=\"_blank\">华军软件园</a>&nbsp; &nbsp;&nbsp;<a href=\"http://down.chinaz.com/soft/33846.htm\" target=\"_blank\">chinaz下载</a></span></p><p><span style=\"color: rgb(0, 0, 153); font-size: 16px; \"><br /></span></p><p><span style=\"font-size: 16px;\"><span style=\"color:#000099;\">CMSHead V2.1_20130401 测试文件包：</span></span></p><p><a href=\"http://www.cmshead.com/Upload.rar\" style=\"font-size: 16px;\"><span style=\"color:#000099;\">http://www.cmshead.com/Upload.rar</span></a></p><p><span style=\"font-size: 16px;\"><span style=\"color:#000099;\"><br />CMSHead V2.1_20130401 插件分享机制实例（理论网苑公务员学习平台，下载安装后即可使用）：</span></span></p><p><a href=\"http://demo.cmshead.com/CMSHead_App.rar\" style=\"font-size: 16px;\"><span style=\"color:#000099;\">http://demo.cmshead.com/CMSHead_App.rar</span></a></p><p><span style=\"color: rgb(255, 0, 0); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><span style=\"font-size:16px;\"><br /></span></span></p><p><span style=\"color: rgb(255, 0, 0); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><span style=\"font-size:16px;\">CMSHead荣誉：http://www.thinkphp.cn/info/237.html &nbsp;CMSHead介绍：http://www.thinkphp.cn/app/cmshead.html</span></span></p><p><span style=\"color: rgb(255, 0, 0); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><span style=\"font-size:16px;\"><br /></span></span></p><p><span style=\"font-size:16px;\"><span style=\"color: rgb(255, 0, 0); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">新版</span><span style=\"color: rgb(255, 0, 0); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">CMSHead V2.1_20130401版本具</span><span style=\"color: rgb(255, 0, 0); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">有如下实用功能：</span></span></p><p><span style=\"font-size:16px;\"><span style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><span style=\"color:#ff0000;word-wrap: break-word;\">本来是想多做一点功能进去再发布的，但是为了方便朋友们做站，我就提前发个过渡版本吧。</span></span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><span style=\"color:#ff0000;word-wrap: break-word;\">过渡版本已经包括：</span></span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><span style=\"color:#ff0000;word-wrap: break-word;\">二级域名，跨模块调用，栏目级的模块混建，可配置页面后缀加上.html，可开启全站整页缓存，ajax分页支持（请查看留言反馈页面），文章复制、移动，属性替换，栏目的复制、移动、合并、属性替换，URL重写值为空时自动以模块名+id或栏目中文名全拼作为URL重写值，加了install安装文件，不用选栏目的全站搜索，文件和目录支持中文名，其他的包括文件管理等BUG修复和完善等等，做一个中型门户网站都没挺轻松实现的啦。(*^__^*)&nbsp;</span></span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><br style=\"word-wrap: break-word;\" /><span style=\"color:#800080;word-wrap: break-word;\">再下一个版本预计包括：<br style=\"word-wrap: break-word;\" />baidu,google的sitemap.xml等，后台可复制表结构和数据，分段或整个备份数据库，文章里面可以包含图集，还有图库，视频模块的加强，手机客户端的便捷支持，自动提取关键字，分词搜索支持，按相关度排序，通用全能采集系统，支持QQ登录，微博登录，淘宝登录等的简单会员系统，和现在那些流行大站差不多的效果等等。</span></span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><span style=\"color:#ff00ff;word-wrap: break-word;\">朋友们想要什么新功能，可以在论坛回帖详细说明，好的东西，我可以考虑整进去哦。</span></span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">1、新版的模板标签写法有所改动，有一定的写法限制：</span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">除了大于符号必须是 &amp; g t ; （中间空格去掉）以及表达式内的条件必须是双引号以外，其他没什么特别要求</span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">支持类似写法 &lt;volist name=&quot;:ch2(\'news\',\'where:find_in_set(&quot;2&quot;,attrtj) and status=\'.$a.\' and status=1 and img!=&quot;&quot; and title like &quot;%中国商人%&quot; and id&gt;=\'.$a,\'limit:1\')&quot; id=&quot;vo&quot;&gt;</span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><ignore_js_op style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><img id=\"aimg_11\" aid=\"11\" src=\"/Public/Upload/download/remote_130331/51583e025ab3a.jpg\" zoomfile=\"data/attachment/forum/201303/30/133011minyrrigdgy1qm99.jpg\" file=\"data/attachment/forum/201303/30/133011minyrrigdgy1qm99.jpg\" class=\"zoom\" width=\"600\" inpost=\"1\" alt=\"错误写法，不再支持\" title=\"错误写法，不再支持\" lazyloaded=\"true\" style=\"word-wrap: break-word; cursor: pointer;\" />&nbsp;</ignore_js_op><span style=\"color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"></span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">错误写法，不再支持</span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><ignore_js_op style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><img id=\"aimg_12\" aid=\"12\" src=\"/Public/Upload/download/remote_130331/51583e0293d50.jpg\" zoomfile=\"data/attachment/forum/201303/30/133014i3zcnniidmiisa9u.jpg\" file=\"data/attachment/forum/201303/30/133014i3zcnniidmiisa9u.jpg\" class=\"zoom\" width=\"600\" inpost=\"1\" alt=\"新版的规范写法\" title=\"新版的规范写法\" lazyloaded=\"true\" initialized=\"true\" style=\"word-wrap: break-word; cursor: pointer;\" />&nbsp;</ignore_js_op><span style=\"color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"></span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">新版的规范写法</span><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><br style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\" /><span style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">2、新版支持一篇文章属于不同模块的不同栏目，ch1,ch2,Sys:: Page指定的多个栏目，可以是不同模块的多个栏目。但此种情况下，如果各模块的字段有所不同的话，则必须指定field:相同字段列表。否则调不出来信息。因为用到了union。</span></span><br /></p><p><span style=\"font-size:16px;\"><span style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\"><br /></span></span></p><p><span style=\"font-size:16px;\"><span style=\"word-wrap: break-word; color: rgb(68, 68, 68); font-family: \'Microsoft Yahei\', Simsun, Tahoma; line-height: 21px;\">来自：<a href=\"http://bbs.cmshead.com/thread-25-1-1.html\">http://bbs.cmshead.com/thread-25-1-1.html</a><br /></span></span></p>', '1364737227', '1369646495', '1', '999', '763', 'CMSHead21', '1', '', '', '', '');
INSERT INTO `ch_download` VALUES ('6', '279', 'CMSHead V2.1支持栏目级的模型混建_下载模块', '无', '0', '0', '', '', '', '', 'CMSHead V2.1支持栏目级的模型混建_下载模块，你可以在某个栏目下面创建任何模型的模块栏目，调用时必须指定field字段属性，字段必须是他们的相同字段。否则调不出来数据。', '1364747899', '1364747965', '1', '0', '2', 'download6', '1', '', '', '', '');
INSERT INTO `ch_download` VALUES ('7', '270,268', 'CMSHead V2.1_20130528下载', 'V2.1 build 20130528', '0', '0', '', 'CMSHead www.cmshead.com', '软件名称：CMSHead当前版本：V2.1_20130528软件简介：CMSHead是基于ThinkPHP和DWZ(jUI)最新版开发的免费开源的PHPCMS。他目前最大的亮点是插件分享机制（类似于discuz论坛的插件分享）。任何人均可基于CMSHead做一些开发，并且将开发的作品，甚至模板分享给大家使用。当然你可以是免费，也可以收费的方式。后续还会不断增加功能，不断完善。敬请关注。我们觉得功能', '', '<p></p><span style=\"font-size:16px;\">软件名称：CMSHead<br />当前版本：V2.1_20130528<br />软件简介：CMSHead是基于ThinkPHP和DWZ(jUI)最新版开发的免费开源的PHPCMS。他目前最大的亮点是插件分享机制（类似于discuz论坛的插件分享）。任何人均可基于CMSHead做一些开发，并且将开发的作品，甚至模板分享给大家使用。当然你可以是免费，也可以收费的方式。后续还会不断增加功能，不断完善。敬请关注。我们觉得功能不在多，而在精。<br />作者：还是这个味 awen<br />email：awen8180@126.com<br />微博：@还是这个味 http://weibo.com/awen8180<br />QQ讨论群：146570772<br />CMSHead官方网站：<a href=\"http://www.cmshead.com/\" target=\"_blank\">http://www.cmshead.com/</a><br />CMSHead演示站：<a href=\"http://demo.cmshead.com/\" target=\"_blank\">http://demo.cmshead.com/</a><br />CMSHead技术论坛：<a href=\"http://bbs.cmshead.com/\" target=\"_blank\">http://bbs.cmshead.com/</a><br />ThinkPHP官方介绍页：<a href=\"http://www.thinkphp.cn/app/cmshead.html\" target=\"_blank\">http://www.thinkphp.cn/app/cmshead.html</a><br />CMSHead荣誉：<a href=\"http://www.thinkphp.cn/info/237.html\" target=\"_blank\">http://www.thinkphp.cn/info/237.html</a><br />CMSHead谷歌下载空间：<a href=\"http://code.google.com/p/cmshead/\" target=\"_blank\">http://code.google.com/p/cmshead/ </a>（谷歌空间暂时无法更新）<br />------------------------------<br /><span style=\"color:#3333ff;\">CMSHead V2.1_20130528 官网下载地址：</span><br /><a href=\"http://www.cmshead.com/CMSHeadV2.1_20130528.rar\"><span style=\"color:#3333ff;\">http://www.cmshead.com/CMSHeadV2.1_20130528.rar</span></a><br /><br /><span style=\"color:#3333ff;\">关键字提取插件文件下载：</span><br /><a href=\"http://www.cmshead.com/plugin_down/pscws23.rar\"><span style=\"color:#3333ff;\">http://www.cmshead.com/plugin_down/pscws23.rar</span></a><br /><br />PHP通用采集系统插件收费开源，介绍页：<br /><a href=\"http://www.cmshead.com/caiji.html\" target=\"_blank\">http://www.cmshead.com/caiji.html</a>&nbsp;<br />若有需要，可联系作者（QQ：782039296，TEL：18861253319）购买。<br /><br />CMSHead V2.1_20130401 测试文件包：<br /><a href=\"http://www.cmshead.com/Upload.rar\">http://www.cmshead.com/Upload.rar</a><br /><br />CMSHead V2.1_20130401 插件分享机制实例（理论网苑公务员学习平台，下载安装后即可使用）：<br /><a href=\"http://www.cmshead.com/CMSHead_App.rar\">http://www.cmshead.com/CMSHead_App.rar</a><br />------------------------------<br />安装方法：直接运行/install/ 安装。<br />升级方法：升级前先备份你的数据库和程序文件。除前台模版外其他均可覆盖，ThinkPHP删除旧的，直接用新版的。数据库表对照着修改。如果你改了关键部分，请搜出你改的部分，对照修改。<br />------------------------------<br /><br />FAQ：<br />1、后台登录提示没有权限的问题：<br />ch_user表type_id字段设置为9。9表示超级管理员<br /><br />2、要自己修改或ThinkPHP或dwz等文件怎么办：<br />全站搜索awen，找到我的修改处。视情况更新到你那边去。<br /></span><br />', '1369644250', '1369646379', '1', '1000', '28', 'ch20130528', '1', '', '1,2,3', '1,2,3', '');
INSERT INTO `ch_key` VALUES ('5', '汤娇', 'help.html', '0', '1');
INSERT INTO `ch_key` VALUES ('6', '汤娇真的漂亮吗', 'article73.html', '0', '1');
INSERT INTO `ch_link` VALUES ('1', 'CMSHead官网', 'http://www.cmshead.com', 'CMSHead官方网站', '100', '1');
INSERT INTO `ch_link` VALUES ('2', '工作IT网', 'http://www.gongzuoit.com', '找IT工作上工作IT网', '10', '1');
INSERT INTO `ch_link` VALUES ('5', '南充鸿达网络', 'http://www.hongdasoft.net', '我们的公司，我是\"还是这个味\"哦', '11', '1');
INSERT INTO `ch_link` VALUES ('6', 'CMSHead谷歌空间', 'http://code.google.com/p/cmshead/', 'CMSHead谷歌下载地址', '90', '1');
INSERT INTO `ch_link` VALUES ('7', 'CMSHead演示', 'http://demo.cmshead.com', '', '98', '1');
INSERT INTO `ch_link` VALUES ('8', 'CMSHead下载', 'http://demo.cmshead.com/index.php/down1', '', '99', '1');
INSERT INTO `ch_link` VALUES ('9', 'CMSHead技术论坛', 'http://bbs.cmshead.com', '', '96', '1');
INSERT INTO `ch_link` VALUES ('10', 'CMSHead介绍', 'http://www.thinkphp.cn/app/cmshead.html', '', '97', '1');
INSERT INTO `ch_message` VALUES ('17', '16', '超级管理员', 'admin@126.com', '0', '1', '这个美女漂亮吗？我很喜欢。', '1359777461', '', '1', 'article', '10', '设计师汤娇', '/help');
INSERT INTO `ch_message` VALUES ('16', '0', 'awen', 'awen8180@126.com', '1', '0', '测试一下html代码：\n&lt;a href=&quot;fdafads&quot;&gt;放大法&lt;/a&gt;', '1359776147', '127.0.0.1', '1', 'article', '10', '设计师汤娇', '/help');
INSERT INTO `ch_message` VALUES ('23', '0', '还是这个味', 'awen8180@126.com', '0', '0', '恭喜CMSHeadV2.0出炉。', '1359789173', '127.0.0.1', '1', '', '', '', '');
INSERT INTO `ch_message` VALUES ('29', '0', 'awen', '782039296@qq.com', '0', '0', 'Sys::page方法支持ajax分页，应用广泛而方便。', '1364739751', '0.0.0.0', '1', '', '', '', '');
INSERT INTO `ch_message` VALUES ('28', '0', 'awen', 'awen8180@126.com', '0', '0', '测试一下留言分页哦。&lt;a href=&quot;http://www.cmshead.com&quot;&gt;CMSHead&lt;/a&gt;', '1361353989', '182.129.12.249', '1', '', '', '', '');
INSERT INTO `ch_model` VALUES ('1', 'Article', '文章模型', '', '2', '1', '', '', '0', '0', '1');
INSERT INTO `ch_model` VALUES ('2', 'Music', '音乐模型', '', '2', '1', '', '', '0', '0', '1');
INSERT INTO `ch_model` VALUES ('3', 'Video', '视频模型', '', '2', '1', '', '', '0', '0', '1');
INSERT INTO `ch_model` VALUES ('4', 'Photo', '图片模型', '', '2', '1', '', '', '0', '0', '1');
INSERT INTO `ch_model` VALUES ('7', 'Test', '测试模块模型', '', '2', '1', '', '', '0', '0', '1');
INSERT INTO `ch_model` VALUES ('8', 'Download', '下载模块模型', '', '2', '1', '', '', '0', '0', '1');
INSERT INTO `ch_model_children` VALUES ('1', 'Photo', '', 'photo');
INSERT INTO `ch_model_children` VALUES ('2', 'Video', '', 'video');
INSERT INTO `ch_model_children` VALUES ('3', 'Music', '', 'music');
INSERT INTO `ch_model_children` VALUES ('4', 'Article', '', 'article');
INSERT INTO `ch_model_children` VALUES ('6', 'Test', '', 'test');
INSERT INTO `ch_model_children` VALUES ('7', 'Attribute', '', 'attribute');
INSERT INTO `ch_model_children` VALUES ('8', 'Download', '', 'download');
INSERT INTO `ch_model_fieldnotes` VALUES ('1', 'Photo', 'a:16:{s:2:\"id\";a:3:{s:5:\"cname\";s:2:\"id\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:6:\"Unique\";}s:5:\"title\";a:3:{s:5:\"cname\";s:12:\"图片名称\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"isb\";a:3:{s:5:\"cname\";s:12:\"是否加粗\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"isi\";a:3:{s:5:\"cname\";s:12:\"是否斜体\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"tcolor\";a:3:{s:5:\"cname\";s:12:\"标题颜色\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:5:\"intro\";a:3:{s:5:\"cname\";s:12:\"图片简介\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"img\";a:3:{s:5:\"cname\";s:12:\"图片上传\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:4:\"link\";a:3:{s:5:\"cname\";s:12:\"链接地址\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"tid\";a:3:{s:5:\"cname\";s:12:\"所属分类\";s:5:\"cnote\";s:7:\"classid\";s:3:\"key\";s:0:\"\";}s:4:\"sort\";a:3:{s:5:\"cname\";s:9:\"排序值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"adder_id\";a:3:{s:5:\"cname\";s:9:\"录入人\";s:5:\"cnote\";s:6:\"userid\";s:3:\"key\";s:0:\"\";}s:8:\"add_time\";a:3:{s:5:\"cname\";s:12:\"添加时间\";s:5:\"cnote\";s:6:\"time()\";s:3:\"key\";s:0:\"\";}s:6:\"status\";a:3:{s:5:\"cname\";s:6:\"状态\";s:5:\"cnote\";s:17:\"0:禁用,1:启用\";s:3:\"key\";s:0:\"\";}s:6:\"attrtj\";a:3:{s:5:\"cname\";s:12:\"推荐属性\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"attrtt\";a:3:{s:5:\"cname\";s:12:\"头条属性\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"outurl\";a:3:{s:5:\"cname\";s:12:\"外部网址\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}}', 'a:10:{s:2:\"id\";a:2:{s:12:\"searnames\";s:2:\"id\";s:9:\"inputType\";s:0:\"\";}s:5:\"title\";a:2:{s:12:\"searnames\";s:5:\"title\";s:9:\"inputType\";s:0:\"\";}s:5:\"intro\";a:2:{s:12:\"searnames\";s:5:\"intro\";s:9:\"inputType\";s:0:\"\";}s:3:\"img\";a:2:{s:12:\"searnames\";s:3:\"img\";s:9:\"inputType\";s:0:\"\";}s:4:\"link\";a:2:{s:12:\"searnames\";s:4:\"link\";s:9:\"inputType\";s:0:\"\";}s:3:\"tid\";a:2:{s:12:\"searnames\";s:3:\"tid\";s:9:\"inputType\";s:0:\"\";}s:4:\"sort\";a:2:{s:12:\"searnames\";s:4:\"sort\";s:9:\"inputType\";s:0:\"\";}s:8:\"adder_id\";a:2:{s:12:\"searnames\";s:8:\"adder_id\";s:9:\"inputType\";s:0:\"\";}s:8:\"add_time\";a:2:{s:12:\"searnames\";s:8:\"add_time\";s:9:\"inputType\";s:0:\"\";}s:6:\"status\";a:2:{s:12:\"searnames\";s:6:\"status\";s:9:\"inputType\";s:0:\"\";}}');
INSERT INTO `ch_model_fieldnotes` VALUES ('2', 'Video', 'a:18:{s:2:\"id\";a:3:{s:5:\"cname\";s:2:\"id\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:5:\"title\";a:3:{s:5:\"cname\";s:12:\"视频标题\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"isb\";a:3:{s:5:\"cname\";s:12:\"是否加粗\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"isi\";a:3:{s:5:\"cname\";s:12:\"是否斜体\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"tcolor\";a:3:{s:5:\"cname\";s:12:\"标题颜色\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"keywords\";a:3:{s:5:\"cname\";s:9:\"关键字\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"description\";a:3:{s:5:\"cname\";s:6:\"描述\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"img\";a:3:{s:5:\"cname\";s:9:\"小图片\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:4:\"file\";a:3:{s:5:\"cname\";s:12:\"上传视频\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:7:\"rewrite\";a:3:{s:5:\"cname\";s:9:\"URL重写\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:4:\"sort\";a:3:{s:5:\"cname\";s:9:\"排序值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"tid\";a:3:{s:5:\"cname\";s:12:\"所属分类\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"adder_id\";a:3:{s:5:\"cname\";s:9:\"录入人\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"add_time\";a:3:{s:5:\"cname\";s:12:\"录入时间\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"status\";a:3:{s:5:\"cname\";s:6:\"状态\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"attrtj\";a:3:{s:5:\"cname\";s:12:\"推荐属性\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"attrtt\";a:3:{s:5:\"cname\";s:12:\"头条属性\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"outurl\";a:3:{s:5:\"cname\";s:12:\"外部网址\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}}', 'a:12:{s:2:\"id\";a:2:{s:12:\"searnames\";s:2:\"id\";s:9:\"inputType\";s:0:\"\";}s:5:\"title\";a:2:{s:12:\"searnames\";s:5:\"title\";s:9:\"inputType\";s:0:\"\";}s:8:\"keywords\";a:2:{s:12:\"searnames\";s:8:\"keywords\";s:9:\"inputType\";s:0:\"\";}s:11:\"description\";a:2:{s:12:\"searnames\";s:11:\"description\";s:9:\"inputType\";s:0:\"\";}s:3:\"img\";a:2:{s:12:\"searnames\";s:3:\"img\";s:9:\"inputType\";s:0:\"\";}s:4:\"file\";a:2:{s:12:\"searnames\";s:4:\"file\";s:9:\"inputType\";s:0:\"\";}s:7:\"rewrite\";a:2:{s:12:\"searnames\";s:7:\"rewrite\";s:9:\"inputType\";s:0:\"\";}s:4:\"sort\";a:2:{s:12:\"searnames\";s:4:\"sort\";s:9:\"inputType\";s:0:\"\";}s:3:\"tid\";a:2:{s:12:\"searnames\";s:3:\"tid\";s:9:\"inputType\";s:0:\"\";}s:8:\"adder_id\";a:2:{s:12:\"searnames\";s:8:\"adder_id\";s:9:\"inputType\";s:0:\"\";}s:8:\"add_time\";a:2:{s:12:\"searnames\";s:8:\"add_time\";s:9:\"inputType\";s:0:\"\";}s:6:\"status\";a:2:{s:12:\"searnames\";s:6:\"status\";s:9:\"inputType\";s:0:\"\";}}');
INSERT INTO `ch_model_fieldnotes` VALUES ('3', 'Music', 'a:9:{s:2:\"id\";a:3:{s:5:\"cname\";s:2:\"id\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:5:\"title\";a:3:{s:5:\"cname\";s:9:\"音乐名\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"author\";a:3:{s:5:\"cname\";s:6:\"作者\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:4:\"file\";a:3:{s:5:\"cname\";s:12:\"上传音乐\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:4:\"sort\";a:3:{s:5:\"cname\";s:9:\"排序值\";s:5:\"cnote\";s:1:\"0\";s:3:\"key\";s:0:\"\";}s:3:\"tid\";a:3:{s:5:\"cname\";s:12:\"所属分类\";s:5:\"cnote\";s:7:\"classid\";s:3:\"key\";s:0:\"\";}s:8:\"adder_id\";a:3:{s:5:\"cname\";s:9:\"录入人\";s:5:\"cnote\";s:6:\"userid\";s:3:\"key\";s:0:\"\";}s:8:\"add_time\";a:3:{s:5:\"cname\";s:12:\"录入时间\";s:5:\"cnote\";s:6:\"time()\";s:3:\"key\";s:0:\"\";}s:6:\"status\";a:3:{s:5:\"cname\";s:6:\"状态\";s:5:\"cnote\";s:17:\"1:启用,0:禁用\";s:3:\"key\";s:0:\"\";}}', 'a:9:{s:2:\"id\";a:2:{s:12:\"searnames\";s:2:\"id\";s:9:\"inputType\";s:0:\"\";}s:5:\"title\";a:2:{s:12:\"searnames\";s:5:\"title\";s:9:\"inputType\";s:0:\"\";}s:6:\"author\";a:2:{s:12:\"searnames\";s:6:\"author\";s:9:\"inputType\";s:0:\"\";}s:4:\"file\";a:2:{s:12:\"searnames\";s:4:\"file\";s:9:\"inputType\";s:0:\"\";}s:4:\"sort\";a:2:{s:12:\"searnames\";s:4:\"sort\";s:9:\"inputType\";s:0:\"\";}s:3:\"tid\";a:2:{s:12:\"searnames\";s:3:\"tid\";s:9:\"inputType\";s:0:\"\";}s:8:\"adder_id\";a:2:{s:12:\"searnames\";s:8:\"adder_id\";s:9:\"inputType\";s:0:\"\";}s:8:\"add_time\";a:2:{s:12:\"searnames\";s:8:\"add_time\";s:9:\"inputType\";s:0:\"\";}s:6:\"status\";a:2:{s:12:\"searnames\";s:6:\"status\";s:9:\"inputType\";s:0:\"\";}}');
INSERT INTO `ch_model_fieldnotes` VALUES ('4', 'Article', 'a:21:{s:2:\"id\";a:3:{s:5:\"cname\";s:2:\"id\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"tid\";a:3:{s:5:\"cname\";s:12:\"所属分类\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:6:\"Normal\";}s:5:\"title\";a:3:{s:5:\"cname\";s:12:\"文章标题\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:6:\"Normal\";}s:3:\"isb\";a:3:{s:5:\"cname\";s:12:\"是否加粗\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"isi\";a:3:{s:5:\"cname\";s:12:\"是否斜体\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"tcolor\";a:3:{s:5:\"cname\";s:12:\"标题颜色\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"keywords\";a:3:{s:5:\"cname\";s:9:\"关键字\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"description\";a:3:{s:5:\"cname\";s:6:\"描述\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"img\";a:3:{s:5:\"cname\";s:12:\"预览图片\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:7:\"content\";a:3:{s:5:\"cname\";s:6:\"内容\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:9:\"Full Text\";}s:8:\"add_time\";a:3:{s:5:\"cname\";s:12:\"录入时间\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"update_time\";a:3:{s:5:\"cname\";s:12:\"修改时间\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"adder_id\";a:3:{s:5:\"cname\";s:9:\"录入人\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:4:\"sort\";a:3:{s:5:\"cname\";s:9:\"排序值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"apv\";a:3:{s:5:\"cname\";s:9:\"浏览量\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:7:\"rewrite\";a:3:{s:5:\"cname\";s:12:\"URL重写值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"status\";a:3:{s:5:\"cname\";s:6:\"状态\";s:5:\"cnote\";s:17:\"1:启用,0:禁用\";s:3:\"key\";s:0:\"\";}s:8:\"template\";a:3:{s:5:\"cname\";s:12:\"使用模板\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"attrtj\";a:3:{s:5:\"cname\";s:12:\"推荐属性\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"attrtt\";a:3:{s:5:\"cname\";s:12:\"头条属性\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"outurl\";a:3:{s:5:\"cname\";s:12:\"外部网址\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}}', 'a:15:{s:2:\"id\";a:2:{s:12:\"searnames\";s:2:\"id\";s:9:\"inputType\";s:0:\"\";}s:3:\"tid\";a:2:{s:12:\"searnames\";s:3:\"tid\";s:9:\"inputType\";s:0:\"\";}s:5:\"title\";a:2:{s:12:\"searnames\";s:5:\"title\";s:9:\"inputType\";s:0:\"\";}s:8:\"keywords\";a:2:{s:12:\"searnames\";s:8:\"keywords\";s:9:\"inputType\";s:0:\"\";}s:11:\"description\";a:2:{s:12:\"searnames\";s:11:\"description\";s:9:\"inputType\";s:0:\"\";}s:3:\"img\";a:2:{s:12:\"searnames\";s:3:\"img\";s:9:\"inputType\";s:0:\"\";}s:7:\"content\";a:2:{s:12:\"searnames\";s:7:\"content\";s:9:\"inputType\";s:0:\"\";}s:8:\"add_time\";a:2:{s:12:\"searnames\";s:8:\"add_time\";s:9:\"inputType\";s:0:\"\";}s:11:\"update_time\";a:2:{s:12:\"searnames\";s:11:\"update_time\";s:9:\"inputType\";s:0:\"\";}s:8:\"adder_id\";a:2:{s:12:\"searnames\";s:8:\"adder_id\";s:9:\"inputType\";s:0:\"\";}s:4:\"sort\";a:2:{s:12:\"searnames\";s:4:\"sort\";s:9:\"inputType\";s:0:\"\";}s:3:\"apv\";a:2:{s:12:\"searnames\";s:3:\"apv\";s:9:\"inputType\";s:0:\"\";}s:7:\"rewrite\";a:2:{s:12:\"searnames\";s:7:\"rewrite\";s:9:\"inputType\";s:0:\"\";}s:6:\"status\";a:2:{s:12:\"searnames\";s:6:\"status\";s:9:\"inputType\";s:0:\"\";}s:8:\"template\";a:2:{s:12:\"searnames\";s:8:\"template\";s:9:\"inputType\";s:0:\"\";}}');
INSERT INTO `ch_model_fieldnotes` VALUES ('7', 'Test', 'a:21:{s:2:\"id\";a:3:{s:5:\"cname\";s:2:\"id\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"tid\";a:3:{s:5:\"cname\";s:12:\"所属分类\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:6:\"Normal\";}s:5:\"title\";a:3:{s:5:\"cname\";s:12:\"测试标题\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:6:\"Normal\";}s:8:\"keywords\";a:3:{s:5:\"cname\";s:9:\"关键字\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"description\";a:3:{s:5:\"cname\";s:6:\"描述\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"img\";a:3:{s:5:\"cname\";s:12:\"预览图片\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:7:\"content\";a:3:{s:5:\"cname\";s:6:\"内容\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:9:\"Full Text\";}s:8:\"add_time\";a:3:{s:5:\"cname\";s:12:\"录入时间\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"update_time\";a:3:{s:5:\"cname\";s:12:\"修改时间\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"adder_id\";a:3:{s:5:\"cname\";s:9:\"录入人\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:4:\"sort\";a:3:{s:5:\"cname\";s:9:\"排序值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"apv\";a:3:{s:5:\"cname\";s:9:\"浏览量\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:7:\"rewrite\";a:3:{s:5:\"cname\";s:12:\"URL重写值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"status\";a:3:{s:5:\"cname\";s:6:\"状态\";s:5:\"cnote\";s:17:\"1:启用,0:禁用\";s:3:\"key\";s:0:\"\";}s:8:\"template\";a:3:{s:5:\"cname\";s:12:\"使用模板\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"isb\";a:3:{s:5:\"cname\";s:6:\"加粗\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"isi\";a:3:{s:5:\"cname\";s:6:\"斜体\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"tcolor\";a:3:{s:5:\"cname\";s:6:\"颜色\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"attrtj\";a:3:{s:5:\"cname\";s:6:\"推荐\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"attrtt\";a:3:{s:5:\"cname\";s:6:\"头条\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"outurl\";a:3:{s:5:\"cname\";s:12:\"外部网址\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}}', 'a:21:{s:2:\"id\";a:2:{s:12:\"searnames\";s:2:\"id\";s:9:\"inputType\";s:0:\"\";}s:3:\"tid\";a:2:{s:12:\"searnames\";s:3:\"tid\";s:9:\"inputType\";s:0:\"\";}s:5:\"title\";a:2:{s:12:\"searnames\";s:5:\"title\";s:9:\"inputType\";s:0:\"\";}s:8:\"keywords\";a:2:{s:12:\"searnames\";s:8:\"keywords\";s:9:\"inputType\";s:0:\"\";}s:11:\"description\";a:2:{s:12:\"searnames\";s:11:\"description\";s:9:\"inputType\";s:0:\"\";}s:3:\"img\";a:2:{s:12:\"searnames\";s:3:\"img\";s:9:\"inputType\";s:0:\"\";}s:7:\"content\";a:2:{s:12:\"searnames\";s:7:\"content\";s:9:\"inputType\";s:0:\"\";}s:8:\"add_time\";a:2:{s:12:\"searnames\";s:8:\"add_time\";s:9:\"inputType\";s:0:\"\";}s:11:\"update_time\";a:2:{s:12:\"searnames\";s:11:\"update_time\";s:9:\"inputType\";s:0:\"\";}s:8:\"adder_id\";a:2:{s:12:\"searnames\";s:8:\"adder_id\";s:9:\"inputType\";s:0:\"\";}s:4:\"sort\";a:2:{s:12:\"searnames\";s:4:\"sort\";s:9:\"inputType\";s:0:\"\";}s:3:\"apv\";a:2:{s:12:\"searnames\";s:3:\"apv\";s:9:\"inputType\";s:0:\"\";}s:7:\"rewrite\";a:2:{s:12:\"searnames\";s:7:\"rewrite\";s:9:\"inputType\";s:0:\"\";}s:6:\"status\";a:2:{s:12:\"searnames\";s:6:\"status\";s:9:\"inputType\";s:0:\"\";}s:8:\"template\";a:2:{s:12:\"searnames\";s:8:\"template\";s:9:\"inputType\";s:0:\"\";}s:3:\"isb\";a:2:{s:12:\"searnames\";s:0:\"\";s:9:\"inputType\";s:0:\"\";}s:3:\"isi\";a:2:{s:12:\"searnames\";s:0:\"\";s:9:\"inputType\";s:0:\"\";}s:6:\"tcolor\";a:2:{s:12:\"searnames\";s:0:\"\";s:9:\"inputType\";s:0:\"\";}s:6:\"attrtj\";a:2:{s:12:\"searnames\";s:0:\"\";s:9:\"inputType\";s:0:\"\";}s:6:\"attrtt\";a:2:{s:12:\"searnames\";s:0:\"\";s:9:\"inputType\";s:0:\"\";}s:6:\"outurl\";a:2:{s:12:\"searnames\";s:0:\"\";s:9:\"inputType\";s:0:\"\";}}');
INSERT INTO `ch_model_fieldnotes` VALUES ('8', 'Attribute', 'a:6:{s:2:\"id\";a:3:{s:5:\"cname\";s:2:\"id\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"attrtype\";a:3:{s:5:\"cname\";s:12:\"属性分类\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"attrname\";a:3:{s:5:\"cname\";s:12:\"属性名称\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:9:\"attrvalue\";a:3:{s:5:\"cname\";s:9:\"属性值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:4:\"sort\";a:3:{s:5:\"cname\";s:9:\"排序值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"status\";a:3:{s:5:\"cname\";s:6:\"状态\";s:5:\"cnote\";s:17:\"1:启用,0:禁用\";s:3:\"key\";s:0:\"\";}}', 'a:4:{s:2:\"id\";a:2:{s:12:\"searnames\";s:2:\"id\";s:9:\"inputType\";s:0:\"\";}s:8:\"attrtype\";a:2:{s:12:\"searnames\";s:0:\"\";s:9:\"inputType\";s:0:\"\";}s:8:\"attrname\";a:2:{s:12:\"searnames\";s:5:\"title\";s:9:\"inputType\";s:0:\"\";}s:9:\"attrvalue\";a:2:{s:12:\"searnames\";s:0:\"\";s:9:\"inputType\";s:0:\"\";}}');
INSERT INTO `ch_model_fieldnotes` VALUES ('9', 'Category', 'a:20:{s:7:\"classid\";a:3:{s:5:\"cname\";s:8:\"栏目id\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"classpid\";a:3:{s:5:\"cname\";s:11:\"栏目父id\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:9:\"classpids\";a:3:{s:5:\"cname\";s:12:\"栏目父ids\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:10:\"classchild\";a:3:{s:5:\"cname\";s:15:\"是否有下级\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:13:\"classchildids\";a:3:{s:5:\"cname\";s:15:\"栏目下级ids\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:16:\"classarrchildids\";a:3:{s:5:\"cname\";s:18:\"栏目下级对象\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:10:\"classtitle\";a:3:{s:5:\"cname\";s:12:\"栏目标题\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:9:\"classsort\";a:3:{s:5:\"cname\";s:6:\"排序\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"classstatus\";a:3:{s:5:\"cname\";s:6:\"状态\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:13:\"classkeywords\";a:3:{s:5:\"cname\";s:9:\"关键字\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:16:\"classdescription\";a:3:{s:5:\"cname\";s:6:\"描述\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"classmodule\";a:3:{s:5:\"cname\";s:12:\"所属模型\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:12:\"classrewrite\";a:3:{s:5:\"cname\";s:12:\"URL重写值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:13:\"classtemplate\";a:3:{s:5:\"cname\";s:12:\"栏目模版\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:12:\"newstemplate\";a:3:{s:5:\"cname\";s:12:\"文章模版\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"classimg\";a:3:{s:5:\"cname\";s:15:\"栏目预览图\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"classapv\";a:3:{s:5:\"cname\";s:15:\"栏目浏览量\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"classdomain\";a:3:{s:5:\"cname\";s:18:\"栏目二级域名\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"classouturl\";a:3:{s:5:\"cname\";s:18:\"栏目外部网址\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:13:\"classmenushow\";a:3:{s:5:\"cname\";s:21:\"前台菜单中显示\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}}', '');
INSERT INTO `ch_model_fieldnotes` VALUES ('10', 'Download', 'a:22:{s:2:\"id\";a:3:{s:5:\"cname\";s:2:\"id\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"tid\";a:3:{s:5:\"cname\";s:12:\"所属分类\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:6:\"Normal\";}s:5:\"title\";a:3:{s:5:\"cname\";s:12:\"软件标题\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:6:\"Normal\";}s:4:\"rjbb\";a:3:{s:5:\"cname\";s:12:\"软件版本\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"isb\";a:3:{s:5:\"cname\";s:12:\"是否加粗\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"isi\";a:3:{s:5:\"cname\";s:12:\"是否斜体\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"tcolor\";a:3:{s:5:\"cname\";s:12:\"标题颜色\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"keywords\";a:3:{s:5:\"cname\";s:9:\"关键字\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"description\";a:3:{s:5:\"cname\";s:6:\"描述\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"img\";a:3:{s:5:\"cname\";s:12:\"预览图片\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:7:\"content\";a:3:{s:5:\"cname\";s:6:\"内容\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:9:\"Full Text\";}s:8:\"add_time\";a:3:{s:5:\"cname\";s:12:\"录入时间\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:11:\"update_time\";a:3:{s:5:\"cname\";s:12:\"修改时间\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:8:\"adder_id\";a:3:{s:5:\"cname\";s:9:\"录入人\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:4:\"sort\";a:3:{s:5:\"cname\";s:9:\"排序值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:3:\"apv\";a:3:{s:5:\"cname\";s:9:\"浏览量\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:7:\"rewrite\";a:3:{s:5:\"cname\";s:12:\"URL重写值\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"status\";a:3:{s:5:\"cname\";s:6:\"状态\";s:5:\"cnote\";s:17:\"1:启用,0:禁用\";s:3:\"key\";s:0:\"\";}s:8:\"template\";a:3:{s:5:\"cname\";s:12:\"使用模板\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"attrtj\";a:3:{s:5:\"cname\";s:12:\"推荐属性\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"attrtt\";a:3:{s:5:\"cname\";s:12:\"头条属性\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}s:6:\"outurl\";a:3:{s:5:\"cname\";s:12:\"外部网址\";s:5:\"cnote\";s:0:\"\";s:3:\"key\";s:0:\"\";}}', 'a:22:{s:2:\"id\";a:2:{s:12:\"search_names\";s:2:\"id\";s:9:\"inputType\";s:0:\"\";}s:3:\"tid\";a:2:{s:12:\"search_names\";s:3:\"tid\";s:9:\"inputType\";s:0:\"\";}s:5:\"title\";a:2:{s:12:\"search_names\";s:5:\"title\";s:9:\"inputType\";s:0:\"\";}s:4:\"rjbb\";a:2:{s:12:\"search_names\";s:0:\"\";s:9:\"inputType\";s:0:\"\";}s:3:\"isb\";a:2:{s:12:\"search_names\";s:3:\"isb\";s:9:\"inputType\";s:0:\"\";}s:3:\"isi\";a:2:{s:12:\"search_names\";s:3:\"isi\";s:9:\"inputType\";s:0:\"\";}s:6:\"tcolor\";a:2:{s:12:\"search_names\";s:6:\"tcolor\";s:9:\"inputType\";s:0:\"\";}s:8:\"keywords\";a:2:{s:12:\"search_names\";s:8:\"keywords\";s:9:\"inputType\";s:0:\"\";}s:11:\"description\";a:2:{s:12:\"search_names\";s:11:\"description\";s:9:\"inputType\";s:0:\"\";}s:3:\"img\";a:2:{s:12:\"search_names\";s:3:\"img\";s:9:\"inputType\";s:0:\"\";}s:7:\"content\";a:2:{s:12:\"search_names\";s:7:\"content\";s:9:\"inputType\";s:0:\"\";}s:8:\"add_time\";a:2:{s:12:\"search_names\";s:8:\"add_time\";s:9:\"inputType\";s:0:\"\";}s:11:\"update_time\";a:2:{s:12:\"search_names\";s:11:\"update_time\";s:9:\"inputType\";s:0:\"\";}s:8:\"adder_id\";a:2:{s:12:\"search_names\";s:8:\"adder_id\";s:9:\"inputType\";s:0:\"\";}s:4:\"sort\";a:2:{s:12:\"search_names\";s:4:\"sort\";s:9:\"inputType\";s:0:\"\";}s:3:\"apv\";a:2:{s:12:\"search_names\";s:3:\"apv\";s:9:\"inputType\";s:0:\"\";}s:7:\"rewrite\";a:2:{s:12:\"search_names\";s:7:\"rewrite\";s:9:\"inputType\";s:0:\"\";}s:6:\"status\";a:2:{s:12:\"search_names\";s:6:\"status\";s:9:\"inputType\";s:0:\"\";}s:8:\"template\";a:2:{s:12:\"search_names\";s:8:\"template\";s:9:\"inputType\";s:0:\"\";}s:6:\"attrtj\";a:2:{s:12:\"search_names\";s:6:\"attrtj\";s:9:\"inputType\";s:0:\"\";}s:6:\"attrtt\";a:2:{s:12:\"search_names\";s:6:\"attrtt\";s:9:\"inputType\";s:0:\"\";}s:6:\"outurl\";a:2:{s:12:\"search_names\";s:6:\"outurl\";s:9:\"inputType\";s:0:\"\";}}');
INSERT INTO `ch_music` VALUES ('1', '江南Style', '韩国', '50a9a35c38b6a.mp3', '334', '258', '1', '1344518188', '1');
INSERT INTO `ch_music` VALUES ('2', 'DJ', 'DJ', '50a9a056258cd.mp3', '0', '257', '1', '1344518360', '1');
INSERT INTO `ch_node` VALUES ('1', 'Admin', '后台管理', '1', '', '0', '0', '1', '0', '0');
INSERT INTO `ch_node` VALUES ('2', 'Node', '模块管理', '1', '', '100', '1', '2', '0', 'framework');
INSERT INTO `ch_node` VALUES ('3', 'User', '用户管理', '1', '', '98', '1', '2', '0', 'system');
INSERT INTO `ch_node` VALUES ('4', 'Role', '群组管理', '1', '', '99', '1', '2', '0', 'system');
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
INSERT INTO `ch_node` VALUES ('16', 'Category', '分类管理', '1', '', '99', '1', '2', '0', 'framework');
INSERT INTO `ch_node` VALUES ('17', 'Article', '文章管理', '1', '', '100', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('26', 'Music', '音乐管理', '1', '', '96', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('27', 'Video', '视频管理', '1', '', '97', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('28', 'Photo', '图片管理', '1', '', '98', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('29', 'Link', '链接管理', '1', '', '95', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('30', 'Diary', '日记管理', '1', '', '94', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('31', 'Message', '留言评论', '1', '', '93', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('32', 'System', '系统功能', '1', '', '96', '1', '2', '0', 'system');
INSERT INTO `ch_node` VALUES ('33', 'Router', '路由列表', '1', '', '90', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('34', 'File', '文件管理', '1', '', '97', '1', '2', '0', 'system');
INSERT INTO `ch_node` VALUES ('80', 'tree', '树形菜单', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('81', 'seltpl', '选择模板', '1', '', '0', '5', '3', '0', '0');
INSERT INTO `ch_node` VALUES ('82', 'Ui', '模板管理', '1', '', '10', '1', '2', '0', 'ui');
INSERT INTO `ch_node` VALUES ('109', 'Test', '测试模块', '1', '', '0', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('112', 'Attribute', '属性管理', '1', '', '98', '1', '2', '0', 'framework');
INSERT INTO `ch_node` VALUES ('113', 'Download', '下载模块', '1', '', '0', '1', '2', '0', 'content');
INSERT INTO `ch_node` VALUES ('114', 'Key', '关键字管理', '1', '', '0', '1', '2', '0', 'framework');
INSERT INTO `ch_photo` VALUES ('11', '菊花被爆了就是那朵盛开的菊花', '0', '0', '', '', '50a5d2776491d.jpg', '', '247', '1', '0', '1353044584', '1', '', '', '');
INSERT INTO `ch_photo` VALUES ('12', '美女', '0', '0', '', '', '50a5d4db82a09.jpg', 'http://www.sina.com.cn', '260', '12', '0', '1353045182', '1', '', '', '');
INSERT INTO `ch_photo` VALUES ('13', '范德萨发额外热我的撒', '1', '0', '#006600', '', '50d0128a62d98.jpg', '', '247', '0', '0', '1355813487', '1', '2,3', '1,3', '');
INSERT INTO `ch_photo` VALUES ('19', 'CMSHead V2.1支持栏目级的模型混建_图片模块', '0', '0', '', '', '51584cfcc73b7.jpg', '/CMSHead21.html', '269', '0', '0', '1364741307', '1', '', '', '');
INSERT INTO `ch_role` VALUES ('1', '理论网苑', '0', '1', '', '', '1347269667', '0');
INSERT INTO `ch_role` VALUES ('157', '区人力资源和社会保障局', '0', '1', '', '', '1347269667', '0');
INSERT INTO `ch_role` VALUES ('149', '区发展和改革局', '0', '1', '', '', '1347269667', '0');
INSERT INTO `ch_role` VALUES ('148', '区政府办公室', '0', '1', '', '', '1347269667', '0');
INSERT INTO `ch_role_user` VALUES ('1', '3');
INSERT INTO `ch_role_user` VALUES ('148', '783');
INSERT INTO `ch_role_user` VALUES ('148', '784');
INSERT INTO `ch_role_user` VALUES ('148', '785');
INSERT INTO `ch_role_user` VALUES ('149', '786');
INSERT INTO `ch_role_user` VALUES ('149', '787');
INSERT INTO `ch_role_user` VALUES ('149', '788');
INSERT INTO `ch_role_user` VALUES ('154', '801');
INSERT INTO `ch_role_user` VALUES ('154', '802');
INSERT INTO `ch_role_user` VALUES ('154', '803');
INSERT INTO `ch_role_user` VALUES ('157', '813');
INSERT INTO `ch_role_user` VALUES ('157', '814');
INSERT INTO `ch_role_user` VALUES ('157', '816');
INSERT INTO `ch_role_user` VALUES ('173', '916');
INSERT INTO `ch_role_user` VALUES ('173', '917');
INSERT INTO `ch_role_user` VALUES ('173', '918');
INSERT INTO `ch_role_user` VALUES ('209', '993');
INSERT INTO `ch_role_user` VALUES ('209', '994');
INSERT INTO `ch_role_user` VALUES ('209', '995');
INSERT INTO `ch_role_user` VALUES ('219', '1024');
INSERT INTO `ch_role_user` VALUES ('219', '1025');
INSERT INTO `ch_role_user` VALUES ('219', '1026');
INSERT INTO `ch_role_user` VALUES ('225', '1042');
INSERT INTO `ch_role_user` VALUES ('225', '1043');
INSERT INTO `ch_role_user` VALUES ('225', '1044');
INSERT INTO `ch_role_user` VALUES ('231', '1060');
INSERT INTO `ch_role_user` VALUES ('231', '1061');
INSERT INTO `ch_role_user` VALUES ('231', '1062');
INSERT INTO `ch_role_user` VALUES ('232', '1063');
INSERT INTO `ch_role_user` VALUES ('232', '1064');
INSERT INTO `ch_role_user` VALUES ('232', '1065');
INSERT INTO `ch_role_user` VALUES ('235', '1073');
INSERT INTO `ch_role_user` VALUES ('235', '1074');
INSERT INTO `ch_role_user` VALUES ('241', '1091');
INSERT INTO `ch_role_user` VALUES ('241', '1092');
INSERT INTO `ch_role_user` VALUES ('241', '1093');
INSERT INTO `ch_role_user` VALUES ('242', '1094');
INSERT INTO `ch_role_user` VALUES ('242', '1095');
INSERT INTO `ch_role_user` VALUES ('242', '1096');
INSERT INTO `ch_role_user` VALUES ('243', '1097');
INSERT INTO `ch_role_user` VALUES ('243', '1098');
INSERT INTO `ch_role_user` VALUES ('243', '1099');
INSERT INTO `ch_role_user` VALUES ('244', '1100');
INSERT INTO `ch_role_user` VALUES ('244', '1101');
INSERT INTO `ch_role_user` VALUES ('244', '1102');
INSERT INTO `ch_role_user` VALUES ('245', '1103');
INSERT INTO `ch_role_user` VALUES ('245', '1104');
INSERT INTO `ch_role_user` VALUES ('245', '1105');
INSERT INTO `ch_role_user` VALUES ('246', '1106');
INSERT INTO `ch_role_user` VALUES ('246', '1107');
INSERT INTO `ch_role_user` VALUES ('246', '1108');
INSERT INTO `ch_role_user` VALUES ('247', '1109');
INSERT INTO `ch_role_user` VALUES ('247', '1110');
INSERT INTO `ch_role_user` VALUES ('247', '1111');
INSERT INTO `ch_role_user` VALUES ('148', '1114');
INSERT INTO `ch_role_user` VALUES ('148', '1115');
INSERT INTO `ch_role_user` VALUES ('148', '1116');
INSERT INTO `ch_role_user` VALUES ('148', '1117');
INSERT INTO `ch_role_user` VALUES ('148', '1118');
INSERT INTO `ch_role_user` VALUES ('148', '1119');
INSERT INTO `ch_role_user` VALUES ('149', '1121');
INSERT INTO `ch_role_user` VALUES ('149', '1122');
INSERT INTO `ch_role_user` VALUES ('149', '1123');
INSERT INTO `ch_role_user` VALUES ('149', '1124');
INSERT INTO `ch_role_user` VALUES ('149', '1125');
INSERT INTO `ch_role_user` VALUES ('149', '1126');
INSERT INTO `ch_role_user` VALUES ('157', '1127');
INSERT INTO `ch_role_user` VALUES ('157', '1201');
INSERT INTO `ch_role_user` VALUES ('157', '1202');
INSERT INTO `ch_role_user` VALUES ('149', '1377');
INSERT INTO `ch_role_user` VALUES ('149', '1378');
INSERT INTO `ch_role_user` VALUES ('149', '1379');
INSERT INTO `ch_role_user` VALUES ('149', '1380');
INSERT INTO `ch_role_user` VALUES ('157', '1402');
INSERT INTO `ch_role_user` VALUES ('157', '1403');
INSERT INTO `ch_role_user` VALUES ('157', '1404');
INSERT INTO `ch_role_user` VALUES ('3', '1768');
INSERT INTO `ch_role_user` VALUES ('3', '1769');
INSERT INTO `ch_role_user` VALUES ('263', '1769');
INSERT INTO `ch_role_user` VALUES ('3', '1770');
INSERT INTO `ch_role_user` VALUES ('3', '1768');
INSERT INTO `ch_router` VALUES ('234', 'shangren', 'article/view/id/12');
INSERT INTO `ch_router` VALUES ('173', 'wjxw_2', 'article/index/id/267');
INSERT INTO `ch_router` VALUES ('164', 'jsxw', 'article/index/id/238');
INSERT INTO `ch_router` VALUES ('179', 'down1', 'test/view/id/13');
INSERT INTO `ch_router` VALUES ('59', 'ceshilanmu', 'test/index/id/264');
INSERT INTO `ch_router` VALUES ('241', 'help', 'article/view/id/10');
INSERT INTO `ch_router` VALUES ('172', 'lhxw', 'article/index/id/246');
INSERT INTO `ch_router` VALUES ('171', 'jnxw', 'article/index/id/245');
INSERT INTO `ch_router` VALUES ('170', 'tnxw', 'article/index/id/244');
INSERT INTO `ch_router` VALUES ('169', 'xbxw', 'article/index/id/243');
INSERT INTO `ch_router` VALUES ('168', 'wjxw', 'article/index/id/242');
INSERT INTO `ch_router` VALUES ('167', 'szxw', 'article/index/id/241');
INSERT INTO `ch_router` VALUES ('166', 'njxw', 'article/index/id/240');
INSERT INTO `ch_router` VALUES ('165', 'czxw', 'article/index/id/239');
INSERT INTO `ch_router` VALUES ('163', 'shxw', 'article/index/id/227');
INSERT INTO `ch_router` VALUES ('162', 'gjxw', 'article/index/id/226');
INSERT INTO `ch_router` VALUES ('254', 'ch20130528', 'download/view/id/7');
INSERT INTO `ch_router` VALUES ('160', 'xwzx', 'article/index/id/224');
INSERT INTO `ch_router` VALUES ('180', 'yinle', 'music/index/id/235');
INSERT INTO `ch_router` VALUES ('211', 'article13', 'article/view/id/13');
INSERT INTO `ch_router` VALUES ('185', 'article49', 'article/view/id/49');
INSERT INTO `ch_router` VALUES ('184', 'article40', 'article/view/id/40');
INSERT INTO `ch_router` VALUES ('186', 'article48', 'article/view/id/48');
INSERT INTO `ch_router` VALUES ('235', 'article9', 'article/view/id/9');
INSERT INTO `ch_router` VALUES ('192', 'down', 'download/index/id/270');
INSERT INTO `ch_router` VALUES ('256', 'CMSHead21', 'download/view/id/5');
INSERT INTO `ch_router` VALUES ('220', 'article53', 'article/view/id/53');
INSERT INTO `ch_router` VALUES ('221', 'download6', 'download/view/id/6');
INSERT INTO `ch_router` VALUES ('222', 'lanmuxiadexiazai', 'download/index/id/279');
INSERT INTO `ch_router` VALUES ('233', 'article56', 'article/view/id/56');
INSERT INTO `ch_router` VALUES ('243', 'article73', 'article/view/id/73');
INSERT INTO `ch_test` VALUES ('13', '264,268', 'CMSHead V2.0_20130203下载（最后更新2013-03-01）', '', '', '514e09f14e030.jpg', '<span style=\"font-size:16px;\">这是后台通过 架构管理-&gt;模块管理-&gt;Admin-&gt;新增，添加出来的Test模块。复制文章模型模板，然后点代码自动完成，增减一些字段保存，然后点击“创建MVC”，选择文章模板，然后选择一些对应关系，以及输入框类型。点击创建MVC，就出来了。</span><p><span style=\"font-size:16px;\">这样创建一个新的模块是不是很快速啊？完了之后，如果有什么需要定制的东西，你还可以打开MVC页面单独修改之。</span></p><p><br /></p><p><span style=\"font-size:16px;\"><strong><span style=\"color:#ff0000;\">最后更新2013-03-01</span></strong><br /><br />20130203以来的主要更新：<br />将TP框架升级到了最新版——ThinkPHP3.1.2云引擎版本 &nbsp;http://www.thinkphp.cn/down/282.html<br />修改了升级后的相关问题，主要是取静态变量的方法，以及其他一些明显的小问题。<br />改了seo方法，把$position变量改成了$parentCids数组，可以返回当前信息（栏目或详细页）的所有父栏目。那样做选中效果就更方便了。参见Home/default/Public/header.html的<br />#nav_{$parentCids[0]|default=0}{ font-weight:bolder; color:#F00}<br />修复了ch1,ch2缓存问题，事先判断是否有缓存，如果有则直接返回缓存（以前没这个判断）。优化了缓存取值：如果单独写cache:缓存名称，也可以，默认时间为60秒。<br /></span></p><p><span style=\"font-size:16px;\"><br /></span></p><p><span style=\"font-size:16px;\"><br /></span></p><p><span style=\"color:#ff0000;\"><span style=\"font-size:16px;\"><strong>CMSHead V2.0_20130203 本网下载地址：</strong><br /></span></span></p><p><span style=\"color:#ff0000;\"><a href=\"http://demo.cmshead.com/CMSHead.rar\"><span style=\"font-size:16px;\">http://demo.cmshead.com/CMSHead.rar</span></a></span></p><p><span style=\"font-size:16px;\"><br /></span></p><p><strong><span style=\"color:#ff0000;\"><span style=\"font-size:16px;\">CMSHead V2.0_20130203 视频音乐测试文件包：</span></span></strong></p><p><span style=\"color:#ff0000;\"><a href=\"http://demo.cmshead.com/Upload.rar\"><span style=\"font-size:16px;\">http://demo.cmshead.com/Upload.rar</span></a></span></p><p></p><p><strong><span style=\"color:#ff0000;\"><span style=\"font-size:16px;\"><br />CMSHead V2.0_20130203 插件分享机制实例（理论网苑公务员学习平台，下载安装后即可使用）：</span></span></strong></p><p><span style=\"color:#ff0000;\"><a href=\"http://demo.cmshead.com/CMSHead_App.rar\"><span style=\"font-size:16px;\">http://demo.cmshead.com/CMSHead_App.rar</span></a></span></p><p><span style=\"font-size:16px;\"><br /></span></p><p><span style=\"font-size:16px;\"><strong>有什么意见或建议，记得给我留言哦。点底部的留言反馈</strong>。</span></p>', '1360060177', '1364106177', '1', '100', '9898', 'down1', '1', '', '0', '0', '', '', '', '');
INSERT INTO `ch_ui` VALUES ('1', '2013年default', '栏目列表', '{tplroot}default/Article/index.html');
INSERT INTO `ch_ui` VALUES ('2', '2013年default', '栏目信息', '{tplroot}default/Article/view.html');
INSERT INTO `ch_ui` VALUES ('3', '2013年theory', '栏目列表', '{tplroot}theory/Article/index.html');
INSERT INTO `ch_ui` VALUES ('17', '2013年default', 'view3 - 副本', '{tplroot}default/测试/view3 - 副本.html');
INSERT INTO `ch_user` VALUES ('1', 'admin', '系统管理员', '21232f297a57a5a743894a0e4a801fc3', '', '1369631387', '127.0.0.1', '18', '', 'awen8180@126.com', '超级管理员', '1364662790', '1369375699', '1', '9', '');
INSERT INTO `ch_video` VALUES ('1', '爆乳内衣模特表演相貌身材一流', '0', '0', '', '内衣 模特 爆乳', '爆乳内衣模特表演相貌身材一流', '510a0980addd0.jpg', 'http://player.youku.com/player.php/sid/XNDQyODQ5MTY4/v.swf', '', '22', '252', '1', '1344518465', '1', '', '', '');
INSERT INTO `ch_video` VALUES ('3', '江苏常熟', '0', '0', '', '', '', '50a5f6ba94387.jpg', '50a5f6a4e98a0.wmv', '', '0', '250', '1', '1353053400', '1', '', '', '');
INSERT INTO `ch_video` VALUES ('4', '骏马奔腾flv', '0', '0', '', '', '', null, '50d163b3c4a10.flv', '', '21', '248', '1', '1353054482', '1', '', '', '');
INSERT INTO `ch_video` VALUES ('5', '测试标题颜色样式头条图片等', '1', '0', '#006600', '', '', '5141cb2f9a224.jpg', '', '', '0', '248', '1', '1356578442', '1', '2,4', '2,3', 'http://www.cmshead.com/');
INSERT INTO `ch_video` VALUES ('7', '1111', '0', '0', '', '', '', '/Public/Upload/video/519f29b2b333f.jpg', '/Public/Upload/video/519f299c92e01.flv', '', '0', '248', '1', '1369385241', '1', '', '', '');
INSERT INTO `ch_video` VALUES ('8', '11111111111111', '0', '0', '', '', '', '/Public/Upload/video/519f3423637a3.jpg', '/Public/Upload/video/519f342377acf.flv', '', '0', '248', '1', '1369388053', '1', null, null, '');
INSERT INTO `ch_video` VALUES ('9', '2222222222', '0', '0', '', '', '', null, '/Public/Upload/video/519f3519384ba.flv', '', '0', '248', '1', '1369388307', '1', null, null, '');
INSERT INTO `ch_video` VALUES ('10', '3333', '0', '0', '', '', '', '/Public/Upload/video/519f3aa9dea89.jpg', '/Public/Upload/video/519f3aa9e8649.flv', '', '0', '248', '1', '1369388551', '1', null, null, '');
INSERT INTO `ch_video` VALUES ('11', '1111111111', '0', '0', '', '', '', '/Public/Upload/video/519f412a39ba0.gif', '/Public/Upload/video/519f405c58d9f.flv', '', '0', '248', '1', '1369390085', '1', '', '', '');
