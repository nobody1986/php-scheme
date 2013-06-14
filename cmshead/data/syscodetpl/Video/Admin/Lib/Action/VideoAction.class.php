<?php
// 视频模块
class VideoAction extends CommonAction {
	//赋值视频可用分类
	public function _before_add() {
		$list	=	M("Category")->where('classstatus=1 AND classmodule="Video"')->select();
		$this->assign('list',$list);
	}
	//赋值视频可用分类
	public function _before_edit() {
		$this->_before_add();
	}
	//添加视频,上传视频图片
	public function insert() {
		//值为数组的 转换成逗号分隔的字符串
		foreach($_POST as $key=>$val){
			if(is_array($val)){
				$_POST[$key] = implode(',', $val);
			}
		}		
		$model = D (MODULE_NAME);
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}	
		
		$this->do_upload($model); //上传字段处理
		
		//保存当前数据对象
		if ($model->add ()!==false) { //保存成功
			if($_POST['rewrite']){
				$data['rewrite']=$_POST['rewrite'];
				$data['url']='video/view/id/'.$model->getLastInsID();
				if(!D('Router')->add($data)){
					$model->where( 'id='.(is_numeric($_POST['id']) ? $_POST['id'] : $model->getLastInsID()) )->setField('rewrite','');
				}
			}
			$this->to_category_map();
			$this->success ('新增成功！');
		} else {
			//失败提示
			$this->error ('新增失败！');
		}		
	}
	//更新视频
	public function update(){
		//值为数组的 转换成逗号分隔的字符串
		foreach($_POST as $key=>$val){
			if(is_array($val)){
				$_POST[$key] = implode(',', $val);
			}
		}			
		if($_POST['fieldarrs']){ //多选型字段, 模板中<input type="hidden" name="fieldarrs" value="attrtt,attrtj" />
			foreach(explode(',',$_POST['fieldarrs']) as $key){
				if(!isset($_POST[$key])) $_POST[$key] = '';
			}
		}
		$model = D ( 'Video' );
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}	
		
		$this->do_upload($model); //上传字段处理
		
		//保存当前数据对象
		if ($model->save () !== false) {
			//成功提示
			D('Router')->where("url='video/view/id/{$_POST['id']}'")->delete();
			if($_POST['rewrite']){
				$data['url']="video/view/id/".$_POST['id'];
				$data['rewrite']=$_POST['rewrite'];
				if(!D('Router')->add($data)){
					$model->where( 'id='.(is_numeric($_POST['id']) ? $_POST['id'] : $model->getLastInsID()) )->setField('rewrite','');
				}
			}
			$this->to_category_map();
			$this->success ('编辑成功！');
		} else {
			//错误提示
			$this->error ('编辑失败！');
		}	
	}
	
	//删除测试时删除预览图片,删除路由规则
	public function _before_foreverdelete() {
		$ids = $this->_beforDelFiles(MODULE_NAME);
		if($ids){
			$list = M(MODULE_NAME)->where( array(M(MODULE_NAME)->getPk()=>array('in', $ids)) )->field('rewrite')->select();
			foreach($list as $rs) $rewrite .= ($rewrite ? "','" : '') . $rs['rewrite'];
			if($rewrite) M('Router')->where( "rewrite in ('{$rewrite}')" )->delete();
		}
	}
	
	public function view(){
		is_numeric($_GET['id']) or $this->error('参数错误！');
		parent::$id = $_GET['id'];
		$this->display();
	}
}