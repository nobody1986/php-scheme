<?php
/*对外接口类*/
class ApiAction extends Action {
	//版本升级
	public function upgrade(){
		$c_version = $_GET['version'];
		$s_version = C('CMSHEAD_VERSION');
		if($s_version>$c_version){
			echo '有可用版本更新！';
		}else{
			echo '已是最新版';
		}
	}
	//空操作
	public function _empty(){
		return false;
	}
}