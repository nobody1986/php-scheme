<?php
// 文章模型
class ArticleModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','文章标题必填！',1),
		array('tid','require','所属分类必填！',1),
	);
}