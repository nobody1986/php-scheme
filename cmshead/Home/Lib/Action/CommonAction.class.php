<?php
/*前台动作基类*/
class CommonAction extends Action {
	public static $App; //模板里可直接调用App
	public static $map; //模板里可直接调用，Sys类的查询条件
	public static $id; //模板里可直接调用id值
	public static $pos; //模板里可直接调用当前位置pos中文赋值
	
	//初始化
	function _initialize(){
		load('extend'); //ThinkPHP/Extend/Function/extend.php
		header('Content-Type:text/html;charset=utf-8');
	}
    //空操作
	public function _empty(){
		$this->redirect(__ROOT__);
	}
	//访问内部地址的时候，判断是否需要跳转，此方法也可以扩展，判断会员是否登录才能查看等等
	public function _before_view(){
		if(is_numeric($_GET['id'])){			
			if(strtolower(MODULE_NAME)=='category'){
				$rs = M(MODULE_NAME)->field('classouturl')->where(array('classid'=>$_GET['id']))->find();
				if($rs){
					if($rs['classouturl']!=''){
						header('Location:'.$rs['classouturl']);exit;
					};
				}
			}else{
				$rs = M(MODULE_NAME)->field('outurl')->where(array(M(MODULE_NAME)->getPk()=>$_GET['id']))->find();
				if($rs){
					if($rs['outurl']!=''){
						header('Location:'.$rs['outurl']);exit;
					};
				}
			}		
		}
	}
	//验证码
	public function verify(){
		$type = isset($_GET['type'])?$_GET['type']:'gif';
        import("ORG.Util.Image");
        Image::buildImageVerify(4,1,$type);
    }
    //SEO赋值
    public function seo($array){
    	if(!is_array($array)){
	    	if(is_string($array) && $array!=''){
	    		$array = array('title'=>$array);
	    	}else{
	    		$array = array();
	    	}
    	} 
    	/*说明：为什么要这么麻烦用App变量呢？因为此系统有URL重写功能，比如http://localhost/news映射的是http://localhost/Article/index/id/224
    	 * 前者无法直接得到$_GET['id']信息，所以有了chapp()方法。
    	 * 在类中可以用CommonAction::$App变量获取当前URL等信息，当然也可以直接调用chapp()函数。
    	 * 在模板中则可以采用此方法：用 {~$cid = $App['vars']['id'] ? $App['vars']['id'] : $_GET['id']} 代替直接的$_GET['id']
    	*/
    	$array['App'] = CommonAction::$App = chapp(); 
    	//检测自定义的title名称
    	$titleFieldName = trueMapField($array['App']['app'][0], 'title');
    	$array['title'] = ($array[$titleFieldName]!='' ? $array[$titleFieldName].' - ' : '');
    	$array['title'] = ($array['title']!='' ? $array['title'] : ($array['classtitle']!='' ? $array['classtitle'].' - ' : '')).C('SITE_NAME'); 
    	
		$array['keywords'] = ($array['keywords']!='' ? $array['keywords'].' - ' : ($array['classkeywords']!='' ? $array['classkeywords'].' - ' : '')).C('SITE_KEYWORDS'); //acan
		$array['description'] = ($array['description']!='' ? $array['description'].' - ' : ($array['classdescription']!='' ? $array['classdescription'].' - ' : '')).C('SITE_DESCRIPTION'); //acan
    	//if($array['keywords']=='') $array['keywords'] = C('SITE_KEYWORDS'); 
    	//if($array['description']=='') $array['description'] = C('SITE_DESCRIPTION'); 
    	$array['ActionName'] = $this->getActionName();
    	
    	//取得所属当前信息的所有父栏目，如果没有父栏目则返回当前栏目，模版中使用的方法是 根栏目为$parentCids[0]|default=0，父栏目为{$parentCids[count($parentCids)-1]}
    	$array['parentCids'] = array();
		$classid = $array['App']['vars']['id'] ? $array['App']['vars']['id'] : $_GET['id'];
		if(is_numeric($classid) && $classid>0){
    		if($array['App']['app'][1]!='index'){
				$classid = M($array['App']['app'][0])->where('id='.$classid)->getField('tid');
			}
			if($classid!=''){
				$rs = M('Category')->field('classid,classpids')->where("classstatus=1 and classid in ($classid)")->find();
				if($rs){
					if( $rs['classpids'] ){
						$array['parentCids'] = array_slice(explode(',',$rs['classpids']), 1);
					}else{
						$array['parentCids'][] = $rs['classid'];
					}
				}
			}
		}
    	if(!$array['parentCids']) $array['parentCids'] = array(0);
    	  	
    	$this->assign($array);
    }    
    /**
     * 模板选择
     * 格式：template -> Article:index 对应 Tpl/default/Article/index.html
     * 格式：template -> abc.html 对应 Tpl/default/Article/abc.html
     * 需要参数：method  $type['method']=str_replace('Action::', '/', __METHOD__); //固定的 
     *   形如：Article/index 或 Article/view
     */     
    public function choosetpl($ary=NULL){
    	$strtpl = 'Article:view';    	
    	if(is_array($ary) && $ary){
	    	if( isset($ary['classtemplate']) && false!==stripos($ary['method'], '/index') ){ //访问的是栏目页
	    		$template = $ary['classtemplate'];
	    	}else{
		    	//自定义模板：文章模板优先，其次是分类指定的文章模板，最后是默认模板
		    	$template = $ary['template']!='' ? $ary['template'] : $ary['newstemplate'];
	    	}	    		    	
    		if(false!==strpos($template, ':')){
    			$exp = explode(':', $template);
    			$file = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Home/Tpl/'.$exp[0].'/'.$exp[1].'.html';
    			$t = @is_file($file);
    		}    		
    		if($t){
    			$strtpl = $template;
    		}else{
    			if($template!=''){
    				if(false!==strpos($template,'/')){
    					$strtpl = str_replace(array('{tplroot}','//'), array($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Home/Tpl/','/'), $template); //APP_TMPL_PATH
    				}else{//纯文件名如view3.html
    					$meduleName = MODULE_NAME;
    					if($ary['method']){
    						$meduleName = explode('/',$ary['method']); $meduleName = ucfirst(strtolower($meduleName[0]));
    					}
    					$strtpl = $_SERVER['DOCUMENT_ROOT'].APP_TMPL_PATH.$meduleName.'/'.(false!==strpos($template,'.') ? $template : $template.C('TMPL_TEMPLATE_SUFFIX'));
    				}
    			}elseif($ary['method']){ 
    				$strtpl = str_replace('/',':',ucfirst(strtolower($ary['method'])));
    			}
    		}	    	
    	}
    	
    	//ajax分页的接收和处理返回 Sys::page('新闻','page:2:pageBox')，pageBox区分大小写
		if($this->isAjax()){
			$pageBox = $_REQUEST['pageBox'];
			if(empty($pageBox) || !preg_match('/^[\w_-]+$/',$pageBox)) die('模板错误，filter参数必须指定！');
			$strtplLocal = $strtpl;
			if(strpos($strtplLocal,':')>1){
				$tmp = explode(':',$strtplLocal);
				$strtplLocal = $_SERVER['DOCUMENT_ROOT'].APP_TMPL_PATH.$tmp[0].'/'.$tmp[1].C('TMPL_TEMPLATE_SUFFIX');
			}				
			if( false!==($tplContent = @file_get_contents($strtplLocal)) ){
				if(@preg_match('/<!--'.$pageBox.'-->\s*<\w+ *id=["\']?'.$pageBox.'["\']?.*?>([\s\S]+?)<\/\w+>\s*<!--'.$pageBox.'-->/i', $tplContent, $tplContentArr)){
					if( !empty($tplContentArr[1]) ){
						ob_start();
						$this->show( $tplContentArr[1] );
						$out = ob_get_contents();
						ob_end_clean();
						echo preg_replace('/Process:\s*\d\.\d+[\s\S]*$/','',$out);
					}
				}else{
					echo 'filter参数错误，未找到标志！';
				}							
				exit;
			}	
		}    	
		
    	$this->display($strtpl);
    }
    //文件下载
    public function download(){
		$filename = $_SERVER[DOCUMENT_ROOT].__ROOT__.'/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/download/'.$_GET['filename'];
		header("Content-type: application/octet-stream");  
		header("Content-Length: ".filesize($filename));  
		header("Content-Disposition: attachment; filename={$_GET['filename']}");	
		$fp = fopen($filename, 'rb');  
		fpassthru($fp);  
		fclose($fp); 
    }
}