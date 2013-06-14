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
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
