<?php
// 关键字模型
class KeyModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('keyname','require','关键字标题必填！',1),
		array('keyname','','关键字已存在！',0,'unique'),
		array('url','require','链接地址必填！',1),
	);
}