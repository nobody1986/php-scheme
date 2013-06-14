<?php
/*友情链接类*/
class LinkAction extends CommonAction {
	//列表
	public function index(){
		$this->seo('友情链接列表');
		$this->display();
	}
	//申请链接
	public function add(){
		if($_SESSION['verify']!=md5($_POST['verify'])){
			 echo '验证码错误，发表失败！';
			 return false;
		}else{
			unset($_SESSION['verify']);
			$data = safe($_POST);
			if(D('Link')->add($data)){
				echo '发表成功，请等待审核！'; 
			}else{
				echo '发表失败！';
			}
		}
	}
}