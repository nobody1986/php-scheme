<?php
// 分类模块
class CategoryAction extends CommonAction {
	//赋值可用模块
	public function _before_index() {
		$model	=	D("Category");
		$this->assign('module',$model->getModule());
	}
	//赋值可用模块
	public function _before_add() {
		$model	=	D("Category");
		$list	=	$model->where('status=1 AND pid=0')->select();
		$this->assign('list',$list);
		$this->assign('module',$model->getModule());
	}
	//赋值可用模块
	public function _before_edit() {
		$model	=	D("Category");
		$list	=	$model->where('status=1 AND pid=0')->select();
		$this->assign('list',$list);
		$this->assign('module',$model->getModule());
	}
	//添加分类
	public function insert(){
		$model = D ('Category');
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		//保存当前数据对象
		if ($model->add ()!==false) { //保存成功
			if($_POST['rewrite']){
				$data['rewrite']=$_POST['rewrite'];
				$data['url']=strtolower($_POST['module']).'/index/id/'.$model->getLastInsID();
				D('Router')->add($data);
			}
			$this->success ('新增成功!');
		} else {
			//失败提示
			$this->error ('新增失败!');
		}
	}
	//编辑分类
	public function update() {
		$model = D ( 'Category' );
		$category = $model->find($_POST['id']);		
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		// 更新数据
		if (false !== $model->save ()) {
			//成功提示
			D('Router')->where("url='".strtolower($category['module'])."/index/id/".$_POST['id']."'")->delete();
			if($_POST['rewrite']){
				$data['url']=strtolower($_POST['module'])."/index/id/".$_POST['id'];
				$data['rewrite']=$_POST['rewrite'];
				D('Router')->add($data);
			}
			$this->success ('编辑成功!');
		} else {
			//错误提示
			$this->error ('编辑失败!');
		}
	}	
	
	//树形结构数据组装 想把分类搞成无限分类的。后面再考虑
	public function tree(){
		$model = D("Category");
		$list = $model->where('pid=0')->select();
		if($list){
			foreach ($list as $key=>$val){
				$list[$key]['sub_category'] = $model->where('pid='.$val['id'])->select(); 
			}
		}		
		$this->assign('list',$list);
		$this->display();
	}
	
	//树形递归子函数 暂时没想出来啊 最后来弄吧
	private function _tree($model, $pid=0, &$list=NULL){
		$list = $model->field('id,pid,title')->where('pid='.$pid)->select();
		if($list){			
			foreach ($list as $key=>$val){
				$arr = $model->field('id,pid,title')->where('pid='.$val['id'])->select();
				if($arr){
					$list[$key]['sub_category'] = $arr;	
					foreach ($arr as $k=>$v){
						$arr[$k]['sub_category'] = $this->_tree($model, $v['id'], $list);		
					}
				}
			}
		}
		return $list;
	}
	
	//删除分类的同时，删除路由规则以及子栏目等
	public function _before_foreverdelete() {
		if($_REQUEST['id']){
			$id = is_array($_REQUEST['id']) ? implode(',',$_REQUEST['id']) :(preg_match('/^\d+(,\d+)*$/',$_REQUEST['id']) || is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : 0);
			if($id){
				//子栏目
				D('Category')->where("pid in ($id)")->delete();
				
				//路由
				$rewrites = D('Category')->where("id in ($id)")->field('rewrite')->select();
				foreach($rewrites as $v){
					if($v['rewrite']!='') $rewrite .= ($rewrite!='' ? ',' : '') . "'{$v['rewrite']}'";	
				} 		
				if($rewrites) D('Router')->where('rewrite in ('.$rewrite.')')->delete();								
			}
		}
	}
}