<?php
// 图片管理模块
class PhotoAction extends CommonAction {
	//删除信息时删除预览图片,删除路由规则
	public function _before_foreverdelete() {
		$ids = $this->_beforDelFiles(MODULE_NAME);
		if($ids){
			$list = M(MODULE_NAME)->where( array(M(MODULE_NAME)->getPk()=>array('in', $ids)) )->field('rewrite')->select();
			foreach($list as $rs) $rewrite .= ($rewrite ? "','" : '') . $rs['rewrite'];
			if($rewrite) M('Router')->where( "rewrite in ('{$rewrite}')" )->delete();
		}
	}
}