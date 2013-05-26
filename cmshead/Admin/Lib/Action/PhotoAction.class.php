<?php
// 图片模块
class PhotoAction extends CommonAction {
	//列出图片可用分类
	public function _before_add() {
		$model	=	M("Category");
		$list	=	$model->where('status=1 AND module="Photo"')->select();
		$this->assign('list',$list);
	}
	//列出图片可用分类
	public function _before_edit() {
		$model	=	M("Category");
		$list	=	$model->where('status=1 AND module="Photo"')->select();
		$this->assign('list',$list);
	}
	//添加图片
	public function _before_insert() {
		if(!empty($_FILES['img']['name'])){
			import("ORG.Net.UploadFile");
			$upload = new UploadFile();
			$upload->maxSize  = 1048576 * 3 ; 
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg'); 
			$upload->savePath =  './Public/Upload/photo/';
			$upload->saveRule = 'uniqid';
			$upload->thumb = true;
			$upload->thumbMaxWidth = 150;
			$upload->thumbMaxHeight = 120;
			$upload->uploadReplace = false;
			$upload->thumbPrefix = '150_120_';
			if(!$upload->upload()) { 
				$this->error($upload->getErrorMsg());
			}else{
				$imgs = $upload->getUploadFileInfo();
				$_POST['img'] = $imgs[0]['savename'];
				echo 
				'<script type="text/javascript">
				var response = {
					"status":"1",
					"info":"\u64cd\u4f5c\u6210\u529f",
					"navTabId":"Photo",
					"forwardUrl":"",
					"callbackType":"closeCurrent"
				};
				if(window.parent.donecallback) {
					 window.parent.donecallback(response);
				}
			   </script>';
			}
		}
	}
	//删除图片
	public function _before_foreverdelete() {
		$ids = $this->_beforDelFiles(MODULE_NAME);
	}	
}