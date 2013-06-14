<?php
// 文章模块
class ArticleAction extends CommonAction {	
	//添加文章
	public function insert() {
		$model = D ('Article');
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		if(!empty($_FILES['img']['name'])){
			import("ORG.Net.UploadFile");
			$upload = new UploadFile();
			$upload->maxSize  = 1048576 * 3; //3M
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg'); 
			$upload->savePath =  './Public/Upload/article/';
			$upload->saveRule = 'uniqid';
			$upload->thumb = true;
			$upload->thumbMaxWidth = 100;
			$upload->thumbMaxHeight = 100;
			$upload->uploadReplace = false;
			$upload->thumbPrefix = '100_100_';
			if(!$upload->upload()) { 
				$this->error($upload->getErrorMsg());
			}else{
				$imgs = $upload->getUploadFileInfo(); 
				$model->img = $imgs[0]['savename'];
			}
		}
		//保存当前数据对象
		if ($model->add ()!==false) {
			//如果有填写Rewrite值,在Router表插入一条记录
			if($_POST['rewrite']){
				$data['rewrite']=$_POST['rewrite'];
				$data['url']='article/view/id/'.$model->getLastInsID();
				D('Router')->add($data);
			}
			echo '<script type="text/javascript">
					var response = {
						"status":"1",
						"info":"\u64cd\u4f5c\u6210\u529f",
						"navTabId":"Article",
						"forwardUrl":"",
						"callbackType":"closeCurrent"
					};
					if(window.parent.donecallback) {
						 window.parent.donecallback(response);
					}
			    </script>';
		} else {
			//失败提示
			$this->error ('新增失败!');
		}
	}
	//xheditor上传文件保存
	public function upload() {
		header('Content-Type: text/html; charset=UTF-8');
		$inputname='filedata';//表单文件域name
		$attachdir='./Public/Upload/article';//上传文件保存路径，结尾不要带/
		$dirtype=1;//1:按天存入目录 2:按月存入目录 3:按扩展名存目录  建议使用按天存
		$maxattachsize=1048576 * 300;//最大上传大小，默认是300M
		$upext='zip,rar,txt,doc,docx,ppt,xls,xlsx,csv,jpg,jpeg,gif,png,bmp,swf,flv,fla,avi,wmv,wma,rm,mov,mpg,rmvb,3gp,mp4,mp3';//上传扩展名
		$msgtype=2;//返回上传参数的格式：1，只返回url，2，返回参数数组
		$immediate=isset($_GET['immediate'])?$_GET['immediate']:0;//立即上传模式
		ini_set('date.timezone','Asia/Shanghai');//时区
			
		if(isset($_SERVER['HTTP_CONTENT_DISPOSITION']))//HTML5上传
		{
			if(preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i',$_SERVER['HTTP_CONTENT_DISPOSITION'],$info))
			{
				$temp_name=ini_get("upload_tmp_dir").'\\'.date("YmdHis").mt_rand(1000,9999).'.tmp';
				file_put_contents($temp_name,file_get_contents("php://input"));
				$size=filesize($temp_name);
				$_FILES[$info[1]]=array('name'=>$info[2],'tmp_name'=>$temp_name,'size'=>$size,'type'=>'','error'=>0);
			}
		}
		
		$err = "";
		$msg = "''";
		
		$upfile=@$_FILES[$inputname];
		if(!isset($upfile)){
			$err='文件域的name错误';
		}elseif(!empty($upfile['error'])){
			switch($upfile['error'])
			{
				case '1':
					$err = '文件大小超过了php.ini定义的upload_max_filesize值';
					break;
				case '2':
					$err = '文件大小超过了HTML定义的MAX_FILE_SIZE值';
					break;
				case '3':
					$err = '文件上传不完全';
					break;
				case '4':
					$err = '无文件上传';
					break;
				case '6':
					$err = '缺少临时文件夹';
					break;
				case '7':
					$err = '写文件失败';
					break;
				case '8':
					$err = '上传被其它扩展中断';
					break;
				case '999':
				default:
					$err = '无有效错误代码';
			}
		}elseif(empty($upfile['tmp_name']) || $upfile['tmp_name'] == 'none'){
			$err = '无文件上传';
		}else{
			$temppath=$upfile['tmp_name'];
			$fileinfo=pathinfo($upfile['name']);
			$extension=$fileinfo['extension'];
			if(preg_match('/'.str_replace(',','|',$upext).'/i',$extension))
			{
				$bytes=filesize($temppath);
				if($bytes > $maxattachsize)$err='请不要上传大小超过'.$maxattachsize.'的文件';
				else
				{
					switch($dirtype)
					{
						case 1: $attach_subdir = 'day_'.date('ymd'); break;
						case 2: $attach_subdir = 'month_'.date('ym'); break;
						case 3: $attach_subdir = 'ext_'.$extension; break;
					}
					$attach_dir = $attachdir.'/'.$attach_subdir;
					if(!is_dir($attach_dir))
					{
						@mkdir($attach_dir, 0777);
						@fclose(fopen($attach_dir.'/index.htm', 'w'));
					}
					PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
					$filename=date("YmdHis").mt_rand(1000,9999).'.'.$extension;
					$target = $attach_dir.'/'.$filename;
					
					rename($upfile['tmp_name'],$target);
					@chmod($target,0755);
					$target=__ROOT__.'/Public/Upload/article/'.$attach_subdir.'/'.$filename;
					if($immediate=='1')$target='!'.$target;
					if($msgtype==1)$msg="'$target'";
					else $msg="{'url':'".$target."','localname':'".preg_replace("/([\\\\\/'])/",'\\\$1',$upfile['name'])."','id':'1'}";
				}
			}
			else $err='上传文件扩展名必需为：'.$upext;		
			@unlink($temppath);			
		}
		echo "{'err':'".preg_replace("/([\\\\\/'])/",'\\\$1',$err)."','msg':".$msg."}";
	}
	//更新文章
	public function update() {
		$model = D ('Article');
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		if(!empty($_FILES['img']['name'])){
			import("ORG.Net.UploadFile");
			$upload = new UploadFile();
			$upload->maxSize  = 1048576 * 3 ; 
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg'); 
			$upload->savePath =  './Public/Upload/article/';
			$upload->saveRule = 'uniqid';
			$upload->thumb = true;
			$upload->thumbMaxWidth = 100;
			$upload->thumbMaxHeight = 100;
			$upload->uploadReplace = false;
			$upload->thumbPrefix = '100_100_';
			if(!$upload->upload()) { 
				$this->error($upload->getErrorMsg());
			}else{
				$imgs = $upload->getUploadFileInfo(); 
				$model->img = $imgs[0]['savename'];
			}
		}
		//保存当前数据对象		
		if ($model->save()!==false) {
			//Rewrite值判断
			D('Router')->where("url='article/view/id/{$_POST['id']}'")->delete();
			if($_POST['rewrite']){
				$data['url']="article/view/id/".$_POST['id'];
				$data['rewrite']=$_POST['rewrite'];
				D('Router')->add($data);
			}
			echo '<script type="text/javascript">
					var response = {
						"status":"1",
						"info":"\u64cd\u4f5c\u6210\u529f",
						"navTabId":"Article",
						"forwardUrl":"",
						"callbackType":"closeCurrent"
					};
					if(window.parent.donecallback) {
						 window.parent.donecallback(response);
					}
			    </script>';
		} else {
			//失败提示
			$this->error ('编辑失败!');
		}
	}
	//删除文章时删除预览图片,删除路由规则
	public function _before_foreverdelete() {
		$ids = $this->_beforDelFiles(MODULE_NAME);
		if($ids){			
			$list = D('Article')->where("id in $ids")->field('rewrite')->select();
			foreach($list as $rs) $rewrite .= ($rewrite ? "','" : '') . $rs['rewrite'];
			if($rewrite) D('Router')->where("rewrite in '$rewrite'")->delete();			
		}
	}
	//删除图片
	public function delimg(){
		if(is_numeric($_GET['id'])){
			$id = $_GET['id'];			
			$src = './Public/Upload/article/'.D('Article')->where('id='.$id)->getField('img');
			D('Article')->where('id='.$id)->setField('img','');
			if(is_file($src))unlink($src);
		}
		echo '{
				"status":"1",
				"info":"\u64cd\u4f5c\u6210\u529f",
				"navTabId":"_blank",
				"forwardUrl":"",
				"callbackType":""
			}';
	}
	//树形结构数据组装
	public function tree(){
		$where = isset($_REQUEST['mod']) ? " and module='{$_REQUEST['mod']}'" : '';
		$model = D("Category");
		$list = $model->where('pid=0'.$where)->select();
		if($list){
			foreach ($list as $key=>$val){
				$list[$key]['sub_category'] = $model->where('pid='.$val['id'].$where)->select(); 
			}
		}		
		$this->assign('list',$list);
		$this->assign('cid',isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 'tid');
		$this->assign('cname',isset($_REQUEST['cname']) ? $_REQUEST['cname'] : 'categoryName');
		$this->display();
	}
}