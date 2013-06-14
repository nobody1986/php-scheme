<?php
// 属性管理模块
class AttributeAction extends CommonAction {
	public function _before_add(){
		$arr_attrtype = M('attribute')->distinct(true)->field('attrtype')->select();
		$this->assign('arr_attrtype', $arr_attrtype);
	}
	public function _before_edit(){
		$this->_before_add();
	}
}