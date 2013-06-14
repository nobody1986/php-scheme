<?php
//前台文章管理
class ArticleAction extends CommonAction{
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
//		dump(Sys::page('新闻','field:id,title,isb,isi,tcolor,add_time,img,content,apv','page:5')); exit;	
		$this->choosetpl($type);
	}	
	
	//查看文章详细信息
	public function view($id=0){
		$id = $id ? $id : $_GET['id'];
		if(!is_numeric($id)) $this->error('参数错误！');
		parent::$id = $id; //chapp可用
		$status = (false!==strpos($_SERVER['HTTP_REFERER'], C('SITE_URL').'/admin.php')) ? '' : ' AND status=1'; //后台预览无需条件
		$info = M('Article')->where("id=$id{$status}")->join(C('DB_PREFIX').'category ON '.C('DB_PREFIX').'category.classid = '.C('DB_PREFIX').'article.tid and '.C('DB_PREFIX').'category.classstatus=1')->find();		
		$info or $this->error('没有这条记录！');		
		//分页处理
		$strpages = '';
		if(false!==strpos($info['content'],'[CHPAGE]')){
			$pages = explode('[CHPAGE]', $info['content']);
			$varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
			$pageNum = (is_numeric($_GET[$varPage]) && $_GET[$varPage]>1 && $_GET[$varPage]<=100) ? intval($_GET[$varPage]) : 1;
			if($pageNum>count($pages)) $pageNum = count($pages);
			$info['content'] = preg_replace('/(^(\s*|(&nbsp;)*)<\/\w+?>)|(<\w+?>(\s*|(&nbsp;)*)<\/\w+?>)/i','',$pages[$pageNum-1]); //去掉半截子html代码
			//  /news/?p=1  or /index.php/Article/view/id/12/?p=3   	 	
       	 	$url = preg_match('/^\/\w+\/*$/',$_SERVER['REQUEST_URI']) ? rtrim($_SERVER['REQUEST_URI'],'/').'/index' : $_SERVER['REQUEST_URI']; 
	        $url = rtrim(preg_replace('/[\/]+/','/',str_replace(array('?','&','='), '/', $url.'&'.$this->parameter)),'/');
	        if( !preg_match('/\.([a-zA-Z]+)([\?\/&].*)*$/',$url) ) $url .= '/'; //没有包含.html的话，则加上/
	        $url = preg_replace("/[\/]{$varPage}[\/][^\/]*/", '', $url);
	        $url .= '?'; //如果没有url重写值（参数是单数）则不用此行
			
			if($pageNum>1) $strpages .= '<a href="'.$url.$varPage.'='.($pageNum-1).'">上页</a>'; 
			if($pageNum<count($pages)) $strpages .= '<a href="'.$url.$varPage.'='.($pageNum+1).'">下页</a>'; 
			foreach($pages as $k=>$v){
				$strpages .= ($pageNum-1==$k) ? "\r\n".'<strong>'.($k+1).'</strong>' : "\r\n".'<a href="'.$url.$varPage.'='.($k+1).'">'.($k+1).'</a>';
			}
		}		
		if(!$pageNum || $pageNum==1){
			M('Article')->where("id=$id")->setInc('apv'); //浏览量
		}
		$info['method']=str_replace('Action::', '/', __METHOD__); //固定的
		
		$info = changurl($info);
		$info['content'] = replace_key($info['content']);//自动为关键字加内链  *新*
		$this->assign('info',$info);
		$this->assign('pages',$strpages);
		
		$pre = D('Article')->where("id<$id AND status=1")->order('id DESC')->field('id,title,apv,outurl,rewrite')->find();
		$pre['method']=$info['method'];
		$pre = changurl($pre);
		$this->assign('pre',$pre);//上一篇
		
		$next = D('Article')->where("id>$id AND status=1")->order('id')->field('id,title,apv,outurl,rewrite')->find();
		$next['method']=$info['method'];
		$next = changurl($next);
		$this->assign('next',$next);//下一篇
		
		$rand = D('Article')->where("status=1")->order('rand()')->limit(8)->select();
		foreach ($rand as $key => $val){
			$val['method']=$info['method'];
			$rand[$key] = changurl($val);
		}
		$this->assign('rand',$rand);//随机8篇
		
		$message = D('Message')->where("modelname='article' and modekeyvalue=$id AND status=1 AND pid=0")->select();
		if(is_array($message)){
			foreach ($message as $key=>$val){
				$message[$key]['reply'] = D('Message')->where('status=1 AND pid='.$val['id'])->select();
			}
		}
		$this->assign('msg_list',$message);//评论	
		
		$this->seo($info);	
		$this->choosetpl($info);
	}
	
	//文件下载
    public function download(){
		$filename = $_SERVER[DOCUMENT_ROOT].__ROOT__.'/Public/Upload/article/'.$_GET['filename'];
		header("Content-type: application/octet-stream");  
		header("Content-Length: ".filesize($filename));  
		header("Content-Disposition: attachment; filename={$_GET['filename']}");	
		$fp = fopen($filename, 'rb');  
		fpassthru($fp);  
		fclose($fp); 
    }
}