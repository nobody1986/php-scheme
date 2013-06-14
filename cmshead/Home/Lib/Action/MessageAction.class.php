<?php
/*留言评论模块*/
class MessageAction extends CommonAction {
	public function index(){
		$message = D('Message')->where('status=1 AND pid=0 AND aid=0')->order('add_time DESC')->select();
		if(is_array($message)){
				foreach ($message as $key=>$val){
					$message[$key] = $this->msgmodify($val);
					$message[$key]['reply'] = D('Message')->where('status=1 AND pid='.$val['id'])->select();
				}
			}
		$this->assign('list',$message);
		$this->assign('position',0);
		$this->assign('title','留言列表');
		$this->display();
	}
	
	//发表留言
	public function add(){
		if($_SESSION['verify']!=md5($_POST['verify'])){
			 echo '<div class="pop">验证码错误，发表失败！</div>';
			 return false;
		}else{
			$data = $_POST;
			$data['ip'] = get_client_ip();
			$data['add_time'] = time();
			if(D('Message')->add($data)){
				echo '<div class="pop">发表成功，请等待审核！</div>'; 
			}else{
				echo '<div class="pop">发表失败！</div>';
			}
		}
	}
}