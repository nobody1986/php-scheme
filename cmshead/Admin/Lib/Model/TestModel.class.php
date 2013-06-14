<?php
// 测试模型
class TestModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','测试标题必填！',1),
		array('tid','require','所属分类必填！',1),
	);
}