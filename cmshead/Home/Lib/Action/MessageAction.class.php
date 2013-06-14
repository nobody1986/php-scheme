<?php
/*留言评论模块*/
class MessageAction extends CommonAction {
	public function index(){
		$this->assign('title','留言列表'.' - '.C('SITE_NAME'));
		$info['method']=str_replace('Action::', '/', __METHOD__); //固定的
		$this->choosetpl($info);
	}
	
	//发表留言
	public function add(){
		if($_SESSION['verify']!=md5($_POST['verify'])){
			 echo '验证码错误，发表失败！';
			 return false;
		}else{
			unset($_SESSION['verify']);
			$data = safe($_POST);
			$data['status'] = C('HOME_SEND_STATUS');
			$data['ip'] = get_client_ip();
			$data['add_time'] = time();
			
			//这个参数是传入的 根据app()函数返回的一些当前页面信息
			if(isset($data['AppParams'])){ 	
				list($data['modelname'], $data['modekeyvalue'], $data['fromurl']) = explode('┆', $data['AppParams']);
				unset($data['AppParams']);
			}
			
			if(D('Message')->add($data)){
				if(C('HOME_SEND_STATUS')){
					echo '发表成功！';
				}else{
					echo '发表成功，请等待审核！';
				} 
			}else{
				echo '发表失败！';
			}
		}
	}
	
	//问政面对面ajax每隔5秒自动刷新
	public function wzmdm(){
		$list = ch2('Message','where:modelname=Article and modekeyvalue=401 and status=1 and pid=0');
		$str = '';
		$str.='<!--pageBox-->
			<div id="pageBox">';
		foreach($list as $k=>$vo){
			$vo['reply'] = D('Message')->where('status=1 AND pid='.$vo['id'])->select();
			$str.="
			<dl class='list-none metlist'>
				<dt class='title'><span class='tt'>".($k+1)."<sup>#</sup></span><span class='name'>{$vo['adder_name']}</span><span class='time'> ".date('Y-m-d H:i:s',$vo['add_time'])."</span></dt>
				<dd class='info'><span class='tt'>留言内容</span><span class='text'>{$vo['content']}</span></dd>";
			if(count($vo['reply'])){
				foreach($vo['reply'] as $reply){
					
					$str.="<dd class='reinfo'><span class='tt'>管理员回复</span><span class='text'>{$reply['content']}</span></dd>";
				}
			}
			$str.="</dl>";
		}
		$str.='<div id="flip">
						<style>
						.page  { padding:3px; margin:3px; text-align:center; font-family:Tahoma, Arial, Helvetica, Sans-serif;  font-size: 12px;}
						.page  a { border:1px solid #ddd; padding:2px 5px 2px 5px; margin:2px; color:#aaa; text-decoration:none;}
						.page  a:hover { border:1px solid #666; }
						.page  a:hover { border:1px solid #666; }
						.page  .current {border:1px solid #666; padding:2px 5px 2px 5px; margin:2px; color:#aaa; background-color:#f0f0f0; text-decoration:none;}.digg4  span.disabled { border:1px solid #f3f3f3; padding:2px 5px 2px 5px; margin:2px; color:#ccc;} 
						</style>
						<div class="page">   
						</div>
					</div>
			</div>
			<!--pageBox-->';
		echo $str;	
		mysql_close();
	}
	
	//显示留言添加页面
	public function showmessage(){
		$this->display();
	}
	
	//我要爆料
	public function baoliao(){
		$this->display();
	}
	
	//举报
	public function jubao(){
		$this->display();
	}
		
	//显示联系我们页面
	public function linkus(){
		$this->display();
	}
}