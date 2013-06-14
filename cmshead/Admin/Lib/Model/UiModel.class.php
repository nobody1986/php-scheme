<?php
// 分类模型
class CategoryModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('tplname','require','模板名称必填！',1),
		array('tplpath','require','模板路径必填！',1),
		array('tplname','','模板名称已存在！',0,'unique'),
	);
}