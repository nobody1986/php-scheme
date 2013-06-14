<?php
/*音乐精选类*/
class MusicAction extends CommonAction {
	//列表
	public function index($id=0){
		$id = $id ? $id : $_GET['id'];
		if(!is_numeric($id)) $this->error('参数错误！');
		$type = D('Category')->where('status=1')->find($id);
		$type or $this->error('没有这个分类！');	
		$type['method']=__FUNCTION__; //固定的
		$map = D('Common')->getCategoryMap($id);
		$list = D('Music')->where($map)->order('sort DESC')->select();
		foreach ($list as $key=>$val){
			if(!strstr($val['url'],'http'))$list[$key]['url']='__PUBLIC__/Upload/music/'.$val['url'];
		}
		$types = D('Category')->where('status=1 AND pid!=0 AND module="Music"')->order('sort DESC')->select();
		foreach ($types as $key=>$val){
			$val['method']=$val['module'].'/index';
			$types[$key] = $this->changurl($val);
		}
		$this->assign('types',$types);
		$this->assign('list',$list);
		$this->seo($type['title'], $type['keywords'], $type['description'], D('Common')->getPosition($id));
		$this->choosetpl($type);
	}
	//推荐音乐
	public function add(){
		if($_SESSION['verify']!=md5($_POST['verify'])){
			 echo '<div class="pop">验证码错误，发表失败！</div>';
			 return false;
		}else{
			$data = $_POST;
			if(D('Music')->add($data)){
				echo '<div class="pop">发表成功，请等待审核！</div>'; 
			}else{
				echo '<div class="pop">发表失败！</div>';
			}
		}
	}
}