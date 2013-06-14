<?php
// 音乐模型
class MusicModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','音乐名必填！',1),
		array('tid','require','所属分类必填！',1),
	);	
}