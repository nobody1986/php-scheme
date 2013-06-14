<?php
//后台界面管理 与界面有关的函数
class UiAction extends CommonAction {
	
	// 左侧菜单页面
	public function menu($from='') {
		header('Content-Type:text/html;charset=utf-8');
        PublicAction::checkUser();
        C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
            //显示菜单项
            $sortTmp = $menu  = array(); $BMENU = C('ADMIN_BMENU');
        	//读取数据库模块列表生成菜单项
			$node = M('Node');
			$id	= $node->getField('id');
			$where['level']=2;
			$where['status']=1;
			$where['pid']=$id;
			if(!empty($_GET['group'])){
				$where['group_id']=$_GET['group'];
			}
			$list = $node->where($where)->field('id,name,group_id,title')->order('sort desc,id asc')->select();
			$accessList = $_SESSION['_ACCESS_LIST'];
			foreach($list as $rs) {
			     if(isset($accessList[strtoupper(APP_NAME)][strtoupper($rs['name'])]) || $_SESSION['administrator']) {
					//设置模型访问权限
					$rs['access'] = 1;
					if($rs['group_id']){
						//获取左侧各模块的栏目树形菜单，方便直接往栏目里添加信息
						$map = array('classpid'=>0, 'classmodule'=>$rs['name']);
						$rs['classTreeHTML'] = D('Category')->tree($map,'info_addview',0);
						
						$menu[$rs['group_id']][] = $rs; //以分组分开存储
					}
			    }
			}
			if(!empty($_GET['group'])){ //当前选中的某个大菜单
            	if(!array_key_exists($_GET['group'], $menu)) $menu[$_GET['group']] = null;
            }else{
	            foreach($BMENU as $key=>$val){ //按ADMIN_BMENU的顺序排序
	            	if(array_key_exists($key, $menu)){ //如果要显示空菜单则去掉这个条件
	            		$sortTmp[$key] = $menu[$key];
	            	}
	            }
	            $menu = $sortTmp; unset($sortTmp);
            }
            $this->assign('menu',$menu);
            $this->assign('BMENU',$BMENU);
		}		
		if($from=='') $this->display();
	}
		
	//选择分类的页面。树形菜单展示
	public function tree(){		
		$map = array('classpid'=>0);
		if( !empty($_REQUEST['mod']) ) $map['classmodule'] = $_REQUEST['mod'];
		$tree = D('Category')->tree($map,$_REQUEST['link'],$_REQUEST['selparent']);
		$this->assign('tree',$tree);
		$this->display();
	}
	
	/**
	 * 选择模板弹出层 路径包含{tplroot}
	 * @return unknown_type
	 */
	public function seltpl(){
		$tplname = isset($_GET['tplname']) ? $_GET['tplname'] : 'template'; 
		$filtext = 'shtml|html|htm|shtm|tpl|php|asp|jsp|txt';
		import('ORG.Io.Dir');	
		$roottpl = __ROOT__.'/Home/Tpl/'; 

		echo '<div class="page"><div class="layoutBox"><div layoutH="-70">';
		//从模板管理里找出模板对应关系，可以直接选中文名称得到模板，那样更方便易记
		$tplgroup = M('ui')->distinct(true)->field('tplgroup')->select();		
		if($tplgroup){
			$usedDefTheme = getHomeDefTheme(); //获得前台正在使用的模板组别
			echo '<ul class="tree treeFolder">';
			echo '<li><a href="javascript:;">模板对应关系</a><ul>';
			foreach($tplgroup as $rs){
				echo '<li><a href="javascript:;">'.$rs['tplgroup'].'</a><ul>';
				$list = M('ui')->field('tplname,tplpath')->where(array('tplgroup'=>$rs['tplgroup']))->select();
				foreach($list as $rs2){
					$strClass = ( 0===strpos($rs2['tplpath'], '{tplroot}'.$usedDefTheme.'/') ) ? ' style="color:#090"' : ' style="color:#777"'; //使用中的高亮，未使用的可以选择但是灰色					
					echo '<li><a href="javascript:;" onclick="var p=$.pdialog.findObj(\'input[name='.$tplname.']\');p.fobj.val(\''.$rs2['tplpath'].'\');$.pdialog.closeCurrent();"'.$strClass.'>'.$rs2['tplname'].'</a>';	
				}
				echo '</ul></li>';
			}				
			echo '</ul></li>';
			echo '</ul>';
		}
		//同时可以直接选取Home分组下的模板文件，树形结构		
		echo '<ul class="tree treeFolder">';
			$this->_seltpl($tplname, $roottpl);
		echo '</ul>';
		
		echo '</div></div></div>';
	}
	
	private function _seltpl($tplname, $rootpath){
		static $roottpl='',$usedDefTheme=''; 
		if(!$roottpl) $roottpl = $rootpath; //传入的根目录做个保持
		if(!$usedDefTheme){
			$isfirst = true;
			$usedDefTheme = getHomeDefTheme(); //获得前台正在使用的模板组别
		} 
		
		//寻找下级
		$childpath = Dir::getList($_SERVER['DOCUMENT_ROOT'].$rootpath);
		if(!$isfirst){
			$strClass = ( 0===strpos($rootpath.'/', $roottpl.$usedDefTheme.'/') ) ? ' style="color:#090"' : ' style="color:#777"'; //使用中的高亮，未使用的可以选择但是灰色
		}
		$showpath = str_replace($roottpl, '', rtrim($rootpath,'/')); //去掉根目录 
		$showpath = iconv('gbk','utf-8',$showpath); //支持显示中文
		
		if($childpath){
			echo '<li><a href="javascript:;"'.$strClass.'>'.$showpath.'</a>';
			echo '<ul>';
			for($i=2,$n=count($childpath); $i<$n; $i++){
				$this->_seltpl($tplname, $rootpath.$childpath[$i].'/');
			}
			echo '</ul>';
		}else{ 			
			echo '<li><a href="javascript:;" onclick="var p=$.pdialog.findObj(\'input[name='.$tplname.']\');p.fobj.val(\'{tplroot}'.$showpath.'\');$.pdialog.closeCurrent();"'.$strClass.'>'.$showpath.'</a>';			
		}
		echo '</li>';
	}

	public function view(){ 
		$_POST['tplpath']!='' or die('模板路径不能为空');
		$_POST['tplpath']=iconv("utf-8", "gbk", $_POST['tplpath']); 
		if(false!==strpos($_POST['tplpath'],'{tplroot}')){
			$tplpath = str_replace('{tplroot}', $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Home/Tpl/', $_POST['tplpath']);
			echo file_get_contents($tplpath);
		}else{
			echo '只支持选择模板';
		}
	}
	
	public function _before_add(){
		$arr_tplgroup = M('ui')->distinct(true)->field('tplgroup')->select();
		$this->assign('arr_tplgroup', $arr_tplgroup);
	}
	public function _before_edit(){
		$this->_before_add();
	}
	//保存模板，备份模板
	public function _before_update(){
		if($_POST['tplContent']!=''){
			if(false!==strpos($_POST['tplpath'],'{tplroot}')){
				$defrootpath = iconv('utf-8','gbk',$_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Home/Tpl/');
				$tplpath = iconv('utf-8','gbk',str_replace('{tplroot}', $defrootpath, $_POST['tplpath']));
				//先备份原来的模板
				if($_POST['bakold']){
					rename( $tplpath, dirname($tplpath).'/'.preg_replace('/^(.+)(\.\w+)$/', '\1_sysbak_'.date('Y-m-d_His').'\2', basename($tplpath)) );
				}
				
				//新增时创建模板总目录下不存在的子目录2012-2-5
				if( !is_file($tplpath) ){
					$basedir = dirname($tplpath);
					if(strlen($basedir) > strlen($defrootpath) && false!==strpos($basedir, $defrootpath)){
						$k = count(explode('/',trim($defrootpath,'/')));
						$arr = explode('/',$basedir);				
						static $newdir=''; $newdir = $defrootpath;
						for($i=$k;$i<count($arr);$i++){
							$newdir .= $arr[$i].'/';
							is_dir($newdir) or mkdir($newdir, 0777);							
						}
					}
				}
		 		file_put_contents($tplpath, $_POST['tplContent']);
			}
		}
	}
	public function _before_insert(){
		$this->_before_update();
	}
}