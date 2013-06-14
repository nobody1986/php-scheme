<?php
// 链接模型
class LinkModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','链接名称必填！',1),
		array('url','require','链接地址必填！',1),
	);	
}