<?php
// 留言评论模块
class MessageAction extends CommonAction {
	//后台回复
	public function insert(){
		$model = D ('Message');
		$data = $_POST;
		$user = D('User')->find($_POST['adder_id']);
		$data['adder_name'] = $user['nickname'];
		$data['adder_email'] = $user['email'];
		
		$rs = M('Message')->field('modelname,modekeyvalue,fromurl,fromtitle')->where(array('id'=>$_POST['pid']))->find();
		if($rs){
			$data['modelname'] = $rs['modelname'];
			$data['modekeyvalue'] = $rs['modekeyvalue'];
			$data['fromurl'] = $rs['fromurl'];
			$data['fromtitle'] = $rs['fromtitle'];
		}
		
		if (false === $model->create ($data)) {
			$this->error ( $model->getError () );
		}		
		$list=$model->add ();
		if ($list!==false) { //保存成功
			import ( "ORG.Util.Cookie" );
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('新增成功！');
		} else {
			//失败提示
			$this->error ('新增失败！');
		}
	}
	//删除回复的内容，不知道为什么没起作用
	public function _before_foreverdelete(){
		M('Message')->where(array('pid'=>array('in', $_REQUEST['id'])))->delete();
	}
}