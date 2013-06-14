<?php
// 文章模型
class ArticleAction extends CommonAction {	
	//添加文章
	public function insert() {
		//值为数组的 转换成逗号分隔的字符串
		foreach($_POST as $key=>$val){
			if(is_array($val)){
				$_POST[$key] = implode(',', $val);
			}
		}
		$model = D ('Article');
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		
		//内容中的图片本地化
		if($_POST['remoteToLocal']){
			$contentArr = $this->remoteToLocal($model->content);
			$model->content = $contentArr[0];
		}
		
		$this->do_upload($model); //上传字段处理
		
		//如果没有图片则取内容中的首张
		if($model->img=='' && $contentArr){
			$model->img = $contentArr[1];
		}	
		
		//保存当前数据对象
		if ($model->add ()!==false) {
			//如果有填写Rewrite值,在Router表插入一条记录
			if($_POST['rewrite']){
				$data['rewrite']=$_POST['rewrite'];
				$data['url']='article/view/id/'.$model->getLastInsID();
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
	
	//更新文章
	public function update() {
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
		$model = D ('Article');
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
	
		//内容中的图片本地化
		if($_POST['remoteToLocal']){
			$contentArr = $this->remoteToLocal($model->content);
			$model->content = $contentArr[0];
		}
		
		$this->do_upload($model); //上传字段处理
		
		//如果没有图片则取内容中的首张
		if($model->img=='' && $contentArr){
			$model->img = $contentArr[1];
		}	
				
		//保存当前数据对象		
		if ($model->save()!==false) {
			//Rewrite值判断
			D('Router')->where("url='article/view/id/{$_POST['id']}'")->delete();
			if($_POST['rewrite']){
				$data['url']="article/view/id/".$_POST['id'];
				$data['rewrite']=$_POST['rewrite'];
				if(!D('Router')->add($data)){
					$model->where( 'id='.(is_numeric($_POST['id']) ? $_POST['id'] : $model->getLastInsID()) )->setField('rewrite','');
				}
			}
			$this->to_category_map();
			$this->success ('编辑成功！');
		} else {
			//失败提示
			$this->error ($model->getError());
		}
	}
	
	//删除文章时删除预览图片,删除路由规则
	public function _before_foreverdelete() {
		$ids = $this->_beforDelFiles(MODULE_NAME);
		if($ids){
			$list = M(MODULE_NAME)->where( array(M(MODULE_NAME)->getPk()=>array('in', $ids)) )->field('rewrite')->select();
			foreach($list as $rs) $rewrite .= ($rewrite ? "','" : '') . $rs['rewrite'];
			if($rewrite) M('Router')->where( "rewrite in ('{$rewrite}')" )->delete();
		}
	}
}