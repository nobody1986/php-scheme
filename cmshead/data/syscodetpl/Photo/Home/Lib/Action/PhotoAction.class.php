<?php
// 图片管理模块
class PhotoAction extends CommonAction {
	//列表
	public function index($id=0){
		$id = $id ? $id : $_GET['id'];
		if(!is_numeric($id)) $this->error('参数错误！');
		parent::$id = $id; //chapp可用
		$type = M('Category')->find($id); 
		$type && $type['classstatus']==1 or $this->error('没有这个分类！');	
		$type['method']=str_replace('Action::', '/', __METHOD__); //固定的
		$map = D('Common')->getCategoryMap($id);
		parent::$map = $map['_string'] ? $map['_string'] : NULL;

		$this->seo($type);
		$this->choosetpl($type);
	}
}