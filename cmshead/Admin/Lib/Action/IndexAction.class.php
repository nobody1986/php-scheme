<?php
//后台首页模块
class IndexAction extends CommonAction {
	public function index() {
		UiAction::menu('index');
		$this->display ();
	}
}