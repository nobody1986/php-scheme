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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;