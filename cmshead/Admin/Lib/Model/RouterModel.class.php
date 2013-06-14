<?php
// 路由模型
class RouterModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('rewrite','require','URL重写值必填！',1),
		array('url','require','URL重写的url必填！',1),
		array('rewrite','','URL重写值已存在！',0,'unique'),
		array('url','','URL重写的url已存在！',0,'unique'),
	);
}