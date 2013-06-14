<?php
// 视频模型
class VideoModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','视频标题必填！',1),
		array('tid','require','所属分类必填！',1),
	);	
}