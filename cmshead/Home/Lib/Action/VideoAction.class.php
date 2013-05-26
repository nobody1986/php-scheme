<?php
/*视频推荐类*/
class VideoAction extends CommonAction {
	//列表
	public function index($id=0){
		$id = $id ? $id : $_GET['id'];
		if(!is_numeric($id)) $this->error('参数错误！');
		$type = D('Category')->where('status=1')->find($id); 
		$type or $this->error('没有这个分类！');	
		$type['method']=__FUNCTION__; //固定的
		$map = D('Common')->getCategoryMap($id);
		$map['status'] = array('eq',1);
		//分页取数据
		import("ORG.Util.Page");
		$Video = D("Video");			
		$count = $Video->where($map)->count(); 
		$Page = new Page($count,20);
		$show = $Page->show(); 
		$list = D('Video')->where($map)->order('sort DESC,add_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($list as $key => $val){
			$val['method']='Video/view';
			$list[$key] = $this->changurl($val);
		}
		$types = D('Category')->where('status=1 AND pid!=0 AND module="Video"')->order('sort DESC')->select();
		foreach ($types as $key => $val){
			$val['method']=$val['module'].'/index';
			$types[$key] = $this->changurl($val);
		}
		$this->assign('types',$types);
		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->seo($type['title'], $type['keywords'], $type['description'], D('Common')->getPosition($id));
		$this->choosetpl($type);
	}
	//查看
	public function view(){
		$id = $id ? $id : $_GET['id'];
		if(!is_numeric($id)) $this->error('参数错误！');
		$info = D('Video')->where('status=1')->find($id);
		$info or $this->error('没有这条记录！');	
		$info['method']=__FUNCTION__; //固定的
		$types = D('Category')->where('status=1 AND pid!=0 AND module="Video"')->order('sort DESC')->select();
		$this->assign('types',$types);
		$this->assign('info',$info);
		if(!strstr($info['url'],'http'))$this->assign('local',true);
		$this->seo($info['title'], $info['keywords'], $info['description'], D('Common')->getPosition($info['tid']));
		$this->choosetpl($info);
	}
	//视频推荐
	public function add(){
		if($_SESSION['verify']!=md5($_POST['verify'])){
			 echo '<div class="pop">验证码错误，发表失败！</div>';
			 return false;
		}else{
			$data = $_POST;
			if(D('Video')->add($data)){
				echo '<div class="pop">发表成功，请等待审核！</div>'; 
			}else{
				echo '<div class="pop">发表失败！</div>';
			}
		}
	}
}