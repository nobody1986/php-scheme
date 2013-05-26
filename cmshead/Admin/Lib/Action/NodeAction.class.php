<?php
/**
 * 仿discuz的插件模式，开发者均可分享应用，使用者可以下载安装应用
 * 节点模块
 */
class NodeAction extends CommonAction {
	public function _filter(&$map)
	{
        if(!empty($_GET['group_id'])) {
            $map['group_id'] =  $_GET['group_id'];
            $this->assign('nodeName','分组');
        }elseif(empty($_POST['search']) && !isset($map['pid']) ) {
			$map['pid']	=	0;
		}
		if($_GET['pid']!=''){
			$map['pid']=$_GET['pid'];
		}
		$_SESSION['currentNodeId']	=	$map['pid'];
		//获取上级节点
		$node  = M("Node");
        if(isset($map['pid'])) {
            if($node->getById($map['pid'])) {
                $this->assign('level',$node->level+1);
                $this->assign('nodeName',$node->name);
            }else {
                $this->assign('level',1);
            }
        }
	}

	public function _before_index() {
		$this->sortBy = ' group_id desc,sort asc,id asc';		
		$model	=	M("Group");
		$list	=	$model->where('status=1')->getField('id,title');
		$this->assign('groupList',$list);
	}

	// 获取配置类型
	public function _before_add() {
		$model	=	M("Group");
		$list	=	$model->where('status=1')->select();
		$this->assign('list',$list);
		$node	=	M("Node");
		$node->getById($_SESSION['currentNodeId']);
        $this->assign('pid',$node->id);
		$this->assign('level',$node->level+1);
	}

    public function _before_patch() {
		$model	=	M("Group");
		$list	=	$model->where('status=1')->select();
		$this->assign('list',$list);
		$node	=	M("Node");
		$node->getById($_SESSION['currentNodeId']);
        $this->assign('pid',$node->id);
		$this->assign('level',$node->level+1);
    }
	public function _before_edit() {
		$model	=	M("Group");
		$list	=	$model->where('status=1')->select();
		$this->assign('list',$list);
	}

    /**
     +----------------------------------------------------------
     * 默认排序操作
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function sort()
    {
		$node = M('Node');
        if(!empty($_GET['sortId'])) {
            $map = array();
            $map['status'] = 1;
            $map['id']   = array('in',$_GET['sortId']);
            $sortList   =   $node->where($map)->order('sort asc')->select();
        }else{
            if(!empty($_GET['pid'])) {
                $pid  = $_GET['pid'];
            }else {
                $pid  = $_SESSION['currentNodeId'];
            }
            if($node->getById($pid)) {
                $level   =  $node->level+1;
            }else {
                $level   =  1;
            }
            $this->assign('level',$level);
            $sortList   =   $node->where('status=1 and pid='.$pid.' and level='.$level)->order('sort asc')->select();
        }
        $this->assign("sortList",$sortList);
        $this->display();
        return ;
    }
    
    //卸载应用  
    public function removeApp(){
		$id = $_REQUEST ['id'];
		if ( preg_match('/^\d+(,\d+)*$/',$id) ) {
			//卸载应用文件
			import('ORG.Io.Dir');
			$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
			$list = M('Node')->where ( $condition )->select();
			foreach($list as $rs){
				Dir::delDir($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Admin/Tpl/'.$rs['name']);
				Dir::delDir($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Admin/Lib/Action/'.$rs['name'].'Action.class.php');
				Dir::delDir($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Admin/Lib/Model/'.$rs['name'].'Model.class.php');
			}
			if (false !== M('Node')->where ( $condition )->delete ()) {
				$this->success ('应用卸载成功！');
			} else {
				$this->error ('应用卸载失败！');
			}
		} else {
			$this->error ( '非法操作' );
		}
    }
    
    //分享应用 将应用分享到官网应用中心平台
    public function share(){    	
    	$this->edit();
    }    
    
    public function shareApp(){
		$id = $_REQUEST ['id'];
		if ( preg_match('/^\d+(,\d+)*$/',$id) ) {
			import('ORG.Io.Dir');
			import('ORG.Io.Zip');
			$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
			$list = M('Node')->where ( $condition )->select();
			foreach($list as $rs){
				$AppRoot = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/shareApps/upload/'.$rs['name'].'/';
				//先删除拷贝的临时应用目录
				if(is_dir($AppRoot)) Dir::delDir( $AppRoot );
				//拷贝应用文件到指定临时目录
				$source = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Admin/Tpl/'.$rs['name'];
				if(is_dir($source)){
					$dest = $AppRoot.'Admin/Tpl/';
					Dir::createDir($dest);
					Dir::copyDir($source, $dest.$rs['name']);
				}
				$source = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Admin/Lib/Action/'.$rs['name'].'Action.class.php';
				if(is_file($source)){
					$dest = $AppRoot.'Admin/Lib/Action/';
					Dir::createDir($dest);
					copy($source, $dest.$rs['name'].'Action.class.php');
				}
				$source = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Admin/Lib/Model/'.$rs['name'].'Model.class.php';
				if(is_file($source)){
					$dest = $AppRoot.'Admin/Lib/Model/';
					Dir::createDir($dest);
					copy($source, $dest.$rs['name'].'Model.class.php');
				}
				//导出相关表
				import("Db");
				$db =   DB::getInstance();  
				$tbname = C('DB_PREFIX').strtolower($rs['name']);
				//表结构
				$tempsql.="DROP TABLE IF EXISTS `$tbname`;\n";
				$struct=$db->query("show create table `$tbname`");
				$tempsql.= $struct[0]['Create Table'].";\n\n";  
				$sql='';
				//数据
				$coumt=$db->getFields($tbname);
				$row=D($rs['name'])->select(); 
		   
				$values = array();  
				foreach ($row as $value) {  
					$sql = "INSERT INTO `{$tbname}` VALUES (";  
					foreach($value as $v) {  
						$sql .="'".mysql_real_escape_string($v)."',";  
					}
					$sql=substr($sql,0,-1);  
					$sql .= ");\n\n";  
					$tempsql.= $sql;  
					$sql='';  
				}   
				$filename= $rs['name'].'.sql';	
				$dest = $AppRoot;
				Dir::createDir($dest);
				$filepath = $dest.$filename;
				file_put_contents($filepath,$tempsql);
				//压缩文件
				$archive = new Zip();
				$archive->doZip($AppRoot, $AppRoot.'../'.$rs['name'].'.zip');
				$AppFiles .= ($AppFiles ? '|' : '').$rs['name'].'.zip';				
			}
			$str = '<form name="form1" method="post" action="'.C('CMSHEADSERVERURL').'/admin.php/Node/shareApp/navTabId/Node" target="Frame1">
					<input type="hidden" name="AppPath" value="'.C('CMSHEAD_URL').'/shareApps/upload/" />
					<input type="hidden" name="AppFiles" value="'.$AppFiles.'" />
				  </form><iframe name="Frame1" frameborder="0" scrolling="no" width="100%" height="90"></iframe>';
			$str .= '<script>document.form1.submit();</script>';
			$this->success ($str);
			
		} else {
			$this->error ( '非法操作' );
		}
    }
}