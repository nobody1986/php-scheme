<?php
// 属性管理模型
class AttributeModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('attrtype','require','属性分类必填！',1),
		array('attrname','require','属性名称必填！',1),
		array('attrvalue','require','属性值必填！',1),
	);
}