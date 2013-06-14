<?php
/*视频推荐类*/
class VideoAction extends CommonAction {
	//列表
	public function index($id=0){
		$id = $id ? $id : $_GET['id'];
		if(!is_numeric($id)) $this->error('参数错误！');
		parent::$id = $id; //chapp可用
		$type = D('Category')->find($id); 
		$type && $type['classstatus']==1 or $this->error('没有这个分类！');	
		$type['method']=str_replace('Action::', '/', __METHOD__); //固定的
		$map = D('Common')->getCategoryMap($id);
		parent::$map = $map['_string'] ? $map['_string'] : NULL;

		$this->seo($type);
		$this->choosetpl($type);
	}
	//查看
	public function view($id=0){
		$id = $id ? $id : $_GET['id'];
		if(!is_numeric($id)) $this->error('参数错误！');
		parent::$id = $id; //chapp可用
		$info = M('Video')->where('status=1')->find($id);
		$info or $this->error('没有这条记录！');	
		M('Video')->where("id=$id")->setInc('apv'); //浏览量
		$info['method']=str_replace('Action::', '/', __METHOD__); //固定的
		$info['fileurl'] = $info['file'] ? chimg($info['file'], 'video') : $info['url'];
		$this->assign('local',false===stripos($info['fileurl'],'http'));
		$this->assign('flash',preg_match('/\.(swf|flv)/i', $info['fileurl']));
		$this->assign('info',$info);
		$this->seo($info);
		$this->choosetpl($info);
	}
	//视频推荐
	public function add(){
		if($_SESSION['verify']!=md5($_POST['verify'])){
			 echo '验证码错误，发表失败！';
			 return false;
		}else{
			unset($_SESSION['verify']);
			$data = safe($_POST);
			$data['status'] = C('HOME_SEND_STATUS');
			$data['add_time'] = time();
			if(D('Video')->add($data)){
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
}