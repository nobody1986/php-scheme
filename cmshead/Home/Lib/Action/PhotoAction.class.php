<?php
/*图片相册类*/
class PhotoAction extends CommonAction {
	//列表
	public function index($id=0){
		$id = $id ? $id : $_GET['id'];
		if(!is_numeric($id)) $this->error('参数错误！');
		$type = D('Category')->where('status=1')->find($id); 
		$type or $this->error('没有这个分类！');	
		$type['method']=__FUNCTION__; //固定的
		$map = D('Common')->getCategoryMap($id);
		$map['status'] = array('eq',1);
		
		//分页取数据
		import("ORG.Util.Page");
		$Photo = D("Photo");			
		$count = $Photo->where($map)->count(); 
		$Page = new Page($count,20);
		$show = $Page->show(); 
		$list = $Photo->where($map)->order('sort DESC,add_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $key=>$val){
			$val['method']='Photo/view';
			$list[$key]=$this->changurl($val);
		}		
		$types = D('Category')->where('status=1 AND pid!=0 AND module="Photo"')->order('sort DESC')->select();
		foreach ($types as $key=>$val){
			$val['method']=$val['module'].'/index';
			$types[$key]=$this->changurl($val);
		}
		$this->assign('types',$types);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->seo($type['title'], $type['keywords'], $type['description'], D('Common')->getPosition($id));
		$this->choosetpl($type);
	}
}