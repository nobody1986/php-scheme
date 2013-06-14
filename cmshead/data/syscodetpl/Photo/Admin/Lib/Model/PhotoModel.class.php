<?php
// 图片管理模型
class PhotoModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','图片名称必填！',1),
		array('tid','require','所属分类必填！',1),
	);	
}