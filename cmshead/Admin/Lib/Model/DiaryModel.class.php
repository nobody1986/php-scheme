<?php
// 日记模型
class DiaryModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('weather','require','天气情况必填！',1),
	);	
}