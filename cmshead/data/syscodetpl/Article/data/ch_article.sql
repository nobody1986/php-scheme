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