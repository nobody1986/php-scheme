<?php
/*空类*/
class EmptyAction extends Action {
	//空模型
	public function index(){		
		$this->redirect('index/index');
    }
	//空操作
	public function _empty(){
		$this->redirect("/");
	}
}