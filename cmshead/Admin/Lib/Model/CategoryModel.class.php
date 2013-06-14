<?php
// 分类模型
class CategoryModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','分类名必填！',1),
		array('title','','分类名已存在！',0,'unique',self::MODEL_INSERT),
	);
	public function getModule(){
		$modules = array('Article'=>'文章模块','Music'=>'音乐模块','Video'=>'视频模块','Photo'=>'图片模块','Theory'=>'理论网苑');
		return $modules;
	}
}