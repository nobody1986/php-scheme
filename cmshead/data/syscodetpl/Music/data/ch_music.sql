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