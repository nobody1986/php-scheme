<?php
/**
 * 仿discuz的插件模式，开发者均可分享应用，使用者可以下载安装应用
 * 节点模块
 */
class NodeAction extends CommonAction {
	public function _filter(){
        if(!empty($_GET['group_id'])) {
            $this->map['group_id'] =  $_GET['group_id'];
            $this->assign('nodeName','分组');
        }elseif(empty($_POST['search']) && !isset($this->map['pid']) ) {
			$this->map['pid']	=	0;
		}
		if($_GET['pid']!=''){
			$this->map['pid']=$_GET['pid'];
		}
		$_SESSION['currentNodeId']	=	$this->map['pid'];
		//获取上级节点
		$node  = M("Node");
        if(isset($this->map['pid'])) {
            if($node->getById($this->map['pid'])) {
                $this->assign('level',$node->level+1);
                $this->assign('nodeName',$node->name);
            }else {
                $this->assign('level',1);
            }
        }
	}

	public function _before_index() {
		$this->sortBy = 'group_id,sort desc,id asc';
		$tables = array(); $list = M('Node')->query('show tables;');
		foreach($list as $rs){
			$tables[] = $rs['Tables_in_'.C('DB_NAME')];
		}
		$this->assign('tables', $tables);
	}
	// 获取配置类型
	public function _before_add() {
		$node	=	M('Node');
		$node->getById($_SESSION['currentNodeId']);
        $this->assign('pid',$node->id);
		$this->assign('level',$node->level+1);
	}
	public function add() {
		$this->display ();
	}	
    public function _before_edit() {
		$this->_before_add();
    }
    public function _before_insert() {
    	if($_POST['group_id']){//模块操作才需要首字母大写
    		$_POST['name'] = ucfirst(strtolower($_POST['name']));    		
    	}else{
    		$_POST['group_id'] = 0;
    	}
		$arr = is_numeric($_POST['id']) ? is_model($_POST['id'],1) : array(0, $_POST['name']); 
		if(!$_POST['is_model']){
			M('Model')->where("ename='{$arr[1]}'")->delete();
		}else{
			$data = array('ename'=>$arr[1], 'cname'=>str_replace(array('管理','系统','平台'),'',$_POST['title']).'模型');
			if($arr[0]){
				M('Model')->where(array('ename'=>$arr[1]))->save($data);
			}else{
				M('Model')->add($data);
			}
			//创建上传目录
			$pdir = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.strtolower($_POST['name']);
			if(!is_dir($pdir)) mkdir($pdir, 0777);
		}
		if($_POST['is_model'] && $_POST['copy_table']!=''){
			$arr = explode('_',$_POST['copy_table']);
			$oldTable = C('DB_PREFIX').strtolower($arr[0]);
			$rs = M('Node')->query('show create table `'.$oldTable.'`');
			//解析SQL具体的参数，好写入表
			$sysTplFields = array();
            if(preg_match('/CREATE TABLE `'.$oldTable.'` \(([\s\S]+)\)/', $rs[0]['Create Table'], $sqlArr)){
                if(preg_match_all('/`(.+?)` ([^\( ,]+)(\(\d+\))?(.*)?,?/', $sqlArr[1], $sqlArr)){    					
                    for($i=0,$n=count($sqlArr[0]); $i<$n; $i++){
                        if(preg_match('/ COMMENT [\'"](.*?)[\'"]/', $sqlArr[4][$i], $sqlArr2)){
                            if(($pos = strpos($sqlArr2[1],' '))!==false){
                                $sysTplFields[$sqlArr[1][$i]]['cname'] = substr($sqlArr2[1],0,$pos);
                                $sysTplFields[$sqlArr[1][$i]]['cnote'] = substr($sqlArr2[1],$pos+1);
                            }else{
                                $sysTplFields[$sqlArr[1][$i]]['cname'] = $sqlArr2[1];
                                $sysTplFields[$sqlArr[1][$i]]['cnote'] = '';
                            }
                        }		
                        $sysTplFields[$sqlArr[1][$i]]['key'] = '';				
                        if(preg_match('/(.*?)KEY `.*?` \(`'.$sqlArr[1][$i].'`\)/', $rs[0]['Create Table'], $sqlArr2)){
                        	switch(trim($sqlArr2[1])){
                        		case 'UNIQUE':
                        			$sysTplFields[$sqlArr[1][$i]]['key'] = 'Unique';
                        			break;
                        		case 'FULLTEXT':
                        			$sysTplFields[$sqlArr[1][$i]]['key'] = 'Full Text';
                        			break;
                        		default:
                        			$sysTplFields[$sqlArr[1][$i]]['key'] = 'Normal';
                        			break;
                        	}                        	
                        }
                    }
                }
            }
            $row = M('Model_fieldnotes')->where(array('ename'=>$_POST['name']))->count();
			if( $row ){				
				M('Model_fieldnotes')->where(array('ename'=>$_POST['name']))->setField(array('fieldnotes'=>serialize($sysTplFields)));
			}else{
				M('Model_fieldnotes')->add(array('ename'=>$_POST['name'],'fieldnotes'=>serialize($sysTplFields)));
			}         
			M('Node')->query('DROP TABLE IF EXISTS `'.C('DB_PREFIX').strtolower($_POST['name']).'`');//删除老表
            //复制表结构$oldTable到新的表
            $sql = str_replace('CREATE TABLE `'.$oldTable.'`','CREATE TABLE IF NOT EXISTS `'.C('DB_PREFIX').strtolower($_POST['name']).'`',$rs[0]['Create Table']);
            M('Node')->query($sql);
		}
    }
	public function _before_update() {
		$this->_before_insert();
	}
	
	//设置禁用
	public function _before_forbid($status=0) {
		//把模型表的记录也设置为相同状态
		$names = array();	
		$list = M('Node')->where("id in ({$_REQUEST['id']})")->field('name')->select();
		foreach($list as $rs){
			$names[] = $rs['name'];
		}		
		M('Model')->where('ename in ("'.implode('","', $names).'")')->setField('status',$status);
		//把NODE表的子菜单记录也设置为相同状态
		$list = M('model_children')->where('ename in ("'.implode('","', $names).'")')->field('childmenus')->select();
		if($list){
			$str = '';
			foreach($list as $rs){
				$arr = unserialize($rs['childmenus']);
				foreach($arr as $v){
					$str .= ($str ? "','" : '') . $v['cm_ename'];
				}
			}
			$list = M('Node')->where("name in ('$str')")->field('id')->select();
			foreach($list as $rs){
				$_REQUEST['id'] .= ','.$rs['id'];
			}
		}
	}
	//设置审核
	public function _before_resume() {
		$this->_before_forbid(1);
	}
    
    //扫描新应用
    public function scannew(){  
    	import('ORG.Io.Dir');
    	$apps = array(); $i = 0;
    	//取得data目录下的txt文件
    	$dir = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/data/';
		$data = Dir::getList($dir);
    	foreach($data as $v){
			if(preg_match('/^\w+\.xml$/i', $v)){
				$ename = ucfirst(strtolower(substr($v,0,-4)));
				$xml = simplexml_load_file($dir.$v);
				if($ename==$xml->ename){
					$apps[$i] = $xml;
					$apps[$i]['installed'] = M('Node')->where(array('name'=>$ename))->count() ? true : false;
					$i++;
				}
			}
		}		
		$this->assign('apps',$apps);
    	$this->display();
    }    
    
    //安装新应用  主要是更新一些表，和导入sql
    public function installApp(){
   		//导入sql 
   		if($_POST['import_sql']){
   			//1、新应用的SQL是否存在
	    	$newfile = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/data/'.strtolower($_POST['ename']).'.sql';
	    	if(!is_file($newfile)) $this->error('没有找到新应用的SQL文件'.$newfile);
	    	
      		//2、如果未安装过此应用或备份文件不存在，则应该先备份原数据库
   			$bakfromflag = 'node_no_'.strtolower($_POST['ename']); 
   			$baklocalpath = __ROOT__.'/data/bak/'.C('DB_NAME').'_from_'.$bakfromflag.'.sql';
   			if( !M('Node')->where(array('name'=>$_POST['ename']))->count() || !file_exists($_SERVER[DOCUMENT_ROOT].$baklocalpath) ){ 
		    	if(!SystemAction::backdb($bakfromflag)){
		    		$this->error('原数据库备份失败！请先手动备份到'.$baklocalpath);	
		    	}
   			}
   			
   			//3、检查整理并导入新应用的SQL，取得新表数组
	    	$childtables = SystemAction::querysql($newfile, $_POST['db_prefix']);
	    	if(!$childtables) $this->error('新应用'.$_POST['cname'].'安装失败！未导入数据库表。');	    	
   		}
    	//模型子菜单表
    	if($_POST['cm_ename']){
	    	$k=0; $data = array();
	    	for($i=0,$n=count($_POST['cm_ename']); $i<$n; $i++){
	    		$data[$k]['cm_ename'] = $_POST['cm_ename'][$i];
	    		$data[$k]['cm_cname'] = $_POST['cm_cname'][$i];
	    		$data[$k]['cm_menugroup'] = $_POST['cm_menugroup'][$i];
	    		$data[$k]['cm_sort'] = is_numeric($_POST['cm_sort'][$i]) ? $_POST['cm_sort'][$i] : 1;
	    		$k++;
	    	}
	    	$childmenus = $data;
	    	$data = array(
	    		'ename'=>$_POST['ename'],
	    		'childmenus'=>serialize($childmenus),
	    	);
	    	if(is_array($childtables)) $data['childtables'] = implode(',',$childtables);
	        if( M('Model_children')->where(array('ename'=>$_POST['ename']))->count() ){
	    		M('Model_children')->where(array('ename'=>$_POST['ename']))->data($data)->save();
	    	}else{
	    		M('Model_children')->data($data)->add();
	    	}  
    	}
    	
    	//node节点表
    	$isadd = true; $data = array(); 
    	$data['name'] = $_POST['ename'];
    	$data['title'] = $_POST['cname'];
    	$data['remark'] = strip_tags(trim($_POST['notes']));
    	$data['sort'] = is_numeric($_POST['sort']) ? $_POST['sort'] : 1;
    	$data['group_id'] = $_POST['menugroup'];
    	$data['status'] = 1;
    	$data['pid'] = 1;
    	$data['level'] = 2;
    	$data['type'] = 0;
   		if( M('Node')->where(array('name'=>$_POST['ename']))->count() ){
   			$isadd = false;   				
    		M('Node')->where(array('name'=>$_POST['ename']))->data($data)->save();
    	}else{
    		M('Node')->data($data)->add();
    	}    
    	
    	if(isset($childmenus))
    	foreach($childmenus as $vo){
    		$data = array();
    		$data['name'] = $vo['cm_ename'];
	    	$data['title'] = $vo['cm_cname'];
	    	$data['remark'] = $_POST['cname'].'的子菜单';
	    	$data['sort'] = $vo['cm_sort'];
	    	$data['group_id'] = $vo['cm_menugroup'];
	    	$data['status'] = 1;
	    	$data['pid'] = 1;
	    	$data['level'] = 2;
	    	$data['type'] = 0;
	   		if( M('Node')->where(array('name'=>$data['name']))->count() ){
	   			$isadd = false;   				
	    		M('Node')->where(array('name'=>$data['name']))->data($data)->save();
	    	}else{
	    		M('Node')->data($data)->add();
	    	}    
    	}
    	
    	//模型表
    	$data = array();
    	$data['ename'] = $_POST['ename'];
    	$data['cname'] = str_replace(array('管理','系统','平台'),'',$_POST['cname']).'模型';
    	$data['notes'] = $_POST['notes'];    	
    	$data['menugroup'] = $_POST['menugroup'];    	
    	$data['sort'] = $_POST['sort'];    	
    	$data['author'] = $_POST['author'];    	
    	$data['version'] = $_POST['version'];    	
    	$data['add_time'] = time();    	
    	$data['update_time'] = time();
    	if( M('Model')->where(array('ename'=>$_POST['ename']))->count() ){
    		M('Model')->where(array('ename'=>$_POST['ename']))->data($data)->save();
    	}else{
    		M('Model')->data($data)->add();
    	}    	
    	
		$this->success('应用'.$_POST['cname'].($isadd ? '安装' : '更新').'成功！'.(is_array($childtables) ? '<br>导入'.count($childtables).'个表，明细：<br><span style="color:blue">'.implode('<br>',$childtables).'</span>' : '').'<br>刷新后台即可看到新应用。');
    }    
    
    //卸载应用   如果删除的是主应用表，则要根据model_children表判断相关表，并做关联删除
    public function removeApp(){      	
		$id = $_REQUEST ['id'];
		$root = $_SERVER['DOCUMENT_ROOT'].__ROOT__;
		if ( preg_match('/^\d+(,\d+)*$/',$id) ) {
			//卸载应用文件
			$condition = array ('id' => array ('in', explode ( ',', $id ) ) );
			$list = M('Node')->field('name')->where ( $condition )->select();
			if($list){
				import('ORG.Io.Dir');
				$arrchildren = $arr = $arr2 = $apps = $root_phpfiles = array();
				//取得根目录下的php文件
				$data = Dir::getList($root);
				foreach($data as $v){
					if(preg_match('/^\w+\.php$/i', $v)){
						$root_phpfiles[] = $v;
					}
				}
				//识别入口文件
				foreach($root_phpfiles as $v){
					$content = file_get_contents($root.'/'.$v);
					if(preg_match('/define\([\'"]APP_PATH[\'"] *, *[\'"](.+?)[\'"]\)\;/i', $content, $arr)){
						$apps[] = $arr[1];
					}
				}
				//取得所有要卸载应用的相关表
				foreach($list as $key=>$rs){
					$arrchildren = M('Model_children')->where(array('ename'=>$rs['name']))->field('childmenus,childtables')->find();					
					if($arrchildren){
						$arrchildren['childmenus'] = unserialize($arrchildren['childmenus']);
						$arr2 = explode(',',$arrchildren['childtables']);
						foreach($arrchildren['childmenus'] as $v){	
							$arr2[] = $v['cm_ename'];
						}
					}else{
						$arr2[$key] = $rs['name'];
					}
				}
				//print_r($arr2);exit;
				//循环删除选中的应用的所有APP_PATH下的相关文件
				foreach($arr2 as $name){
					if($name){
						$name = strtolower($name); $M_name = ucfirst($name); 
						$file = $root.'/data/'.$name.'.xml';
						if(is_file($file)) unlink($file);
						$file = $root.'/data/'.$name.'.sql';
						if(is_file($file)) unlink($file);
							
						$dir = $root.'/Public/'.$M_name;
						if(is_dir($dir)) Dir::delDir($dir);
						$dir = $root.'/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.$name;
						if(is_dir($dir)) Dir::delDir($dir);
						if($apps)
						foreach($apps as $v){ //$v = ./Admin/ ./Home/ 等等
							if($v){
								$dir = $root.'/'.$v.'Tpl/'.$M_name;
								if(is_dir($dir)) Dir::delDir($dir);
								$dir = $root.'/'.$v.'Tpl/default/'.$M_name;
								if(is_dir($dir)) Dir::delDir($dir);
								
								$dir = $root.'/'.$v.'Tpl/'.$name;
								if(is_dir($dir)) Dir::delDir($dir);
								$dir = $root.'/'.$v.'Tpl/default/'.$name;
								if(is_dir($dir)) Dir::delDir($dir);
								
								$file = $root.'/'.$v.'/Lib/Action/'.$M_name.'Action.class.php';
								if(is_file($file)) unlink($file);
								$file = $root.'/'.$v.'/Lib/Model/'.$M_name.'Model.class.php';
								if(is_file($file)) unlink($file);
								if( !in_array($M_name, array('Sys')) ){						
									$file = $root.'/'.$v.'/Lib/Extend/'.$M_name.'.class.php';
									if(is_file($file)) unlink($file);		
								}
								//将配置文件（主要是Home下）中的默认模板主题名称改为default
								$content = file_get_contents($root.'/'.$v.'Conf/config.php');
								$content = preg_replace('/\'DEFAULT_THEME\'\s*=>\s*\''.$name.'\',/i', '\'DEFAULT_THEME\'    		=> \'default\',', $content);
								if($content) file_put_contents($root.'/'.$v.'Conf/config.php', $content);
							}
						}
						M('Category')->where(array('classmodule'=>$M_name))->delete();
						M('Model')->where(array('ename'=>$M_name))->delete();
						M('Model_children')->where(array('ename'=>$M_name))->delete();
						M('Model_fieldnotes')->where(array('ename'=>$M_name))->delete();
						M('Message')->where(array('modelname'=>$M_name))->delete();
						M('Router')->where(array('url'=>"{$M_name}/%"))->delete();
						M('Node')->query('DROP TABLE IF EXISTS `'.C('DB_PREFIX').$name.'`');
					}
				}
				if (false !== M('Node')->where ( $condition )->delete ()) {
					if($arrchildren){	
						foreach($arrchildren['childmenus'] as $v){
							M('Node')->where ( array('name'=>$v['cm_ename']) )->delete ();
							M('Node')->query('DROP TABLE IF EXISTS `'.C('DB_PREFIX').$v['cm_ename'].'`');
						}
					}
					//****如果有原数据库备份则还原原来的数据库****
					foreach($list as $key=>$rs){
						$baklocalpath = __ROOT__.'/data/bak/'.C('DB_NAME').'_from_node_no_'.$rs['name'].'.sql';
				   		if(is_file($_SERVER['DOCUMENT_ROOT'].$baklocalpath)){
				   			if( !SystemAction::querysql($_SERVER['DOCUMENT_ROOT'].$baklocalpath) ){
				   				$this->success ('应用卸载成功！但是原数据库导入失败。请手工导入'.$baklocalpath);
				   			}
				   			//unlink($_SERVER['DOCUMENT_ROOT'].$baklocalpath);
				   		}
					}
					//****end****
					$this->success ('应用卸载成功！');
				} else {
					$this->error ('应用卸载失败！');
				}
			}else{
				$this->error ('无效的id，应用卸载失败！');				
			}

		} else {
			$this->error ( '非法操作' );
		}
    }
    
    //chead工具，代码自动完成  创建模块应用的表，同时创建相应控制器、视图、模型等
    public function chead($ename=''){  
    	if($ename==''){
	    	$id = $_GET['id'];
	    	is_numeric($id) or $this->error('id参数错误');
	    	$info = M('Node')->find($id);
	    	$info or $this->error('没有这条记录');    	
	    	$name = $info['name'];
    	}else{
    		$name = $ename;
    	}
    	//字段附加备注信息
        $fieldnotes = M('Model_fieldnotes')->where(array('ename'=>$name))->getField('fieldnotes');
        $fieldnotes = unserialize($fieldnotes);
        
        //字段主信息
    	$result = M('Node')->query('SHOW COLUMNS FROM '.C('DB_PREFIX').strtolower($name));
    	
    	$fields   =   array();
        foreach ($result as $key => $val) {
            $fields[$val['Field']] = array(
                'name'    => $val['Field'],
            	//字段附加备注信息
                'cname'    => $fieldnotes[$val['Field']]['cname'],
                'cnote'    => $fieldnotes[$val['Field']]['cnote'],
                'key'    => $fieldnotes[$val['Field']]['key'],
            
                'type'    => preg_replace('/\(\d*\)/','',$val['Type']),
                'size'    => preg_replace('/\D/','',$val['Type']),
                'isnull' => (bool) ($val['Null'] == 'YES'), // not null is empty, null is yes
                'default' => $val['Default'],
                'primary' => (strtolower($val['Key']) == 'pri'),
                'autoinc' => (strtolower($val['Extra']) == 'auto_increment'),
            );
        }
        if($ename==''){   
	    	$this->assign('fields', $fields);
	    	$this->assign('info', $info);
	    	$this->display();
        }else{
        	return $fields;
        }
    }    
    
    //创建模块 的数据表,MVC代码等
    public function dochead(){
    	if(method_exists(__CLASS__, '_dochead_'.$_POST['saveType'])){
    		$this->{'_dochead_'.$_POST['saveType']}();
    	}else{
    		$this->error('_dochead_'.$_POST['saveType'].'方法不存在');
    	}	
    }
    
    //★创建和编辑主表  ★暂时不好修改字段顺序
    private function _dochead_1(){
    	is_array($_POST['name']) or $this->error('提交数据类型错误');
    	(count(array_unique($_POST['name'])) == count($_POST['name'])) or $this->error('字段名不能重复');
    	(count(array_unique($_POST['cname'])) == count($_POST['cname'])) or $this->error('字段中文名不能重复');
    	preg_match('/^\w+$/',$_POST['modelname']) or $this->error('表名未指定');
    	
    	$tableData = $primarys = $otherKey = array();
    	foreach($_POST['name'] as $key=>$name){
    		$name = trim(str_replace(array('"',"'",'\\','/'),'',$name));
    		$cname = trim(str_replace(array('"',"'",'\\','/'),'',$_POST['cname'][$key]));
    		if($name!='' && $cname!=''){
	    		$tableData[$key]['name'] = $name;
	    		$tableData[$key]['name_old'] = $_POST['name_old'][$key];
	    		$tableData[$key]['cname'] = $cname;
	    		$tableData[$key]['cnote'] = trim(str_replace(array('"',"'",'\\','/'),'',$_POST['cnote'][$key]));
	    		$tableData[$key]['default'] = str_replace(array('"',"'",'\\','/'),'',$_POST['default'][$key]);
	    		$tableData[$key]['type'] = $_POST['type'][$key];
	    		if($_POST['type'][$key]=='longtext'){
	    			$tableData[$key]['size'] = '';
	    		}else{
	    			$tableData[$key]['size'] = (is_numeric($_POST['size'][$key]) && $_POST['size'][$key]>0) ? $_POST['size'][$key] : 0;
	    		}
	    		$tableData[$key]['isnull'] = isset($_POST['isnull'.($key+1)]) ? 1 : 0;
	    		$tableData[$key]['primary'] = isset($_POST['primary'.($key+1)]) ? 1 : 0;
	    		$tableData[$key]['autoinc'] = isset($_POST['autoinc'.($key+1)]) ? 1 : 0;
	    		$tableData[$key]['key'] = $_POST['key'][$key]; 
	    		 		
	    		if($tableData[$key]['primary']){
	    			$primarys[] = "`$name`";
	    		}
	    		if($tableData[$key]['key']=='Unique'){
	    			$otherKey[] = 'UNIQUE KEY `'.$name.'` (`'.$name.'`)';
	    		}elseif($tableData[$key]['key']=='Full Text'){
	    			$otherKey[] = 'FULLTEXT KEY `'.$name.'` (`'.$name.'`)';
	    		}elseif($tableData[$key]['key']=='Normal'){
	    			$otherKey[] = 'KEY `'.$name.'` (`'.$name.'`)';
	    		}
    		}
    	}
    	//如果有这张表并且有内容则修改否则重建表
    	$temp = M('Node')->query('SHOW TABLES LIKE \''.C('DB_PREFIX').strtolower($_POST['modelname']).'\'');
    	$isedit = ( $temp && $temp[0]['Tables_in_'.C('DB_NAME').' ('.C('DB_PREFIX').strtolower($_POST['modelname']).')'] && M($_POST['modelname'])->limit(1)->count() );
    	if($isedit){
    		//删除字段
    		$delnames = explode(',',trim($_POST['delnames'],','));
    		foreach($delnames as $val){
    			if($val!='') M('Node')->query('ALTER TABLE '.C('DB_PREFIX').strtolower($_POST['modelname']).' DROP `'.$val.'`');
    		}
			//删除所有主键
    		M('Node')->query('ALTER TABLE '.C('DB_PREFIX').strtolower($_POST['modelname']).' DROP PRIMARY KEY');	
    		//删除所有索引
    		$fieldnotes = M('Model_fieldnotes')->where(array('ename'=>$_POST['modelname']))->getField('fieldnotes');
        	$fieldnotes = unserialize($fieldnotes);
        	foreach($fieldnotes as $key=>$val){
        		if($val['key']!=''){        			
	    			M('Node')->query('ALTER TABLE '.C('DB_PREFIX').strtolower($_POST['modelname']).' DROP index `'.$key.'`');	
        		}
        	}
    		//增加和修改字段主键索引
    		foreach($tableData as $key=>$val){
    			//if($val['name']!=$val['name_old']){
    				if($val['name_old']==''){ //增加字段
    					M('Node')->query('ALTER TABLE '.C('DB_PREFIX').strtolower($_POST['modelname']).
    					" ADD `{$val['name']}` {$val['type']} ".($val['size']!='' ? "({$val['size']}) " : ' ').
		    			(!$val['isnull'] ? 'NOT NULL' : 'NULL').
		    			($val['autoinc'] ? ' AUTO_INCREMENT' : '').
		    			($val['default']!='' ? ' DEFAULT \''.$val['default'].'\'' : '').
		    			' COMMENT \''.trim($val['cname'].' '.$val['cnote']).'\'');
    				}else{ //修改字段
    					M('Node')->query('ALTER TABLE '.C('DB_PREFIX').strtolower($_POST['modelname']).
    					" CHANGE `{$val['name_old']}` `{$val['name']}` {$val['type']} ".($val['size']!='' ? "({$val['size']}) " : ' ').
		    			(!$val['isnull'] ? 'NOT NULL' : 'NULL').
		    			($val['autoinc'] ? ' AUTO_INCREMENT' : '').
		    			($val['default']!='' ? ' DEFAULT \''.$val['default'].'\'' : '').
		    			' COMMENT \''.trim($val['cname'].' '.$val['cnote']).'\'');
    				}
    			//}
    			//新增主键
    			if($val['primary']){
    				M('Node')->query('ALTER TABLE '.C('DB_PREFIX').strtolower($_POST['modelname']).' ADD PRIMARY KEY ('.$val['name'].')');	
    			}
    			//新增索引
    			if($val['key']=='Unique'){
    				M('Node')->query('ALTER TABLE '.C('DB_PREFIX').strtolower($_POST['modelname']).' ADD UNIQUE KEY `'.$val['name'].'` (`'.$val['name'].'`)');
	    		}elseif($val['key']=='Full Text'){
    				M('Node')->query('ALTER TABLE '.C('DB_PREFIX').strtolower($_POST['modelname']).' ADD FULLTEXT KEY `'.$val['name'].'` (`'.$val['name'].'`)');
	    		}elseif($val['key']=='Normal'){
    				M('Node')->query('ALTER TABLE '.C('DB_PREFIX').strtolower($_POST['modelname']).' ADD KEY `'.$val['name'].'` (`'.$val['name'].'`)');
	    		}	
			}
    	}
    	else{
	    	$sql = "CREATE TABLE `".C('DB_PREFIX').strtolower($_POST['modelname'])."` (";
	    	foreach($tableData as $key=>$val){
	    		$sql .= "`{$val['name']}` {$val['type']} ".($val['size']!='' ? "({$val['size']}) " : ' ').
	    			(!$val['isnull'] ? 'NOT NULL' : 'NULL').
	    			($val['autoinc'] ? ' AUTO_INCREMENT' : '').
	    			($val['default']!='' ? ' DEFAULT \''.$val['default'].'\'' : '').
	    			' COMMENT \''.trim($val['cname'].' '.$val['cnote']).'\',';    		
	    	}
	    	if($primarys) $sql .= 'PRIMARY KEY ('.implode(',',$primarys).'),';
	    	if($otherKey) $sql .= implode(',',$otherKey);
			$sql = rtrim($sql,',');
			$sql .= ") ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=".C('DB_CHARSET').";";
	    	M('Node')->query('DROP TABLE IF EXISTS `'.C('DB_PREFIX').strtolower($_POST['modelname']).'`;');
	    	M('Node')->query($sql);
    	}   	
    	
    	//表信息
    	$data = M('Model_children')->where("ename='{$_POST['modelname']}'")->find();
    	if($data) $isedit = 1;
    	$data['ename'] = $_POST['modelname'];	
    	//childtables主表加入进去
    	$arr = explode(',',$data['childtables']);
    	if(!$arr || !in_array(strtolower($_POST['modelname']),$arr)) $arr[] = strtolower($_POST['modelname']);
    	$data['childtables'] = trim(implode(',', $arr),',');
    	if($isedit){
	    	M('Model_children')->where("ename='{$_POST['modelname']}'")->data($data)->save();
    	}else{
	    	M('Model_children')->data($data)->add();
    	}
    	
    	//字段附加信息
    	$data = array();
    	$data['ename'] = $_POST['modelname'];  
    	//fieldnotes
    	foreach($tableData as $key=>$val){
    		$data['fieldnotes'][$val['name']] = array(
    		'cname'=>$val['cname'],
    		'cnote'=>$val['cnote'],
    		'key'=>$val['key'],
    		);
    	}
    	$data['fieldnotes'] = serialize($data['fieldnotes']);
    	if(M('Model_fieldnotes')->where("ename='{$_POST['modelname']}'")->count()){
	    	M('Model_fieldnotes')->where("ename='{$_POST['modelname']}'")->data($data)->save();
    	}else{
	    	M('Model_fieldnotes')->data($data)->add();
    	}
    	$this->success('主表维护成功！<script>show_map()</script>');
    }
    
    //★创建基本的MVC ,AJAX提交★
    private function _dochead_2(){
    	preg_match('/^\w+$/',$_POST['modelname']) or $this->error('<li>模块名未指定</li>');
    	$_POST['modeltitle']!='' or $this->error('<li>应用名未指定</li>');
    	$modeltitle = str_replace(array('管理','模块','模型'),'',$_POST['modeltitle']);
    	$root = $_SERVER['DOCUMENT_ROOT'];
    	$return = $baseCode = ''; $sysTplFields = array();
		import('ORG.Io.Dir');
		
    	$root = $_SERVER['DOCUMENT_ROOT'].__ROOT__;
    	$dir = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/data/syscodetpl';
    	$selectArr = explode('_',$_POST['modelsystpl']);
    	$modelsystpl = $selectArr[0]!='' ? $selectArr[0] : $_POST['modelname']; //选择的系统内置模板类型 Article、Photo ...
    	$modelsysname = $selectArr[1]!='' ? $selectArr[1] : $modeltitle; //选择的系统内置模板名称文章、图片 ...
    	if(!is_dir($dir.'/'.$modelsystpl)){
    		$modelsystpl = 'Article'; $modelsysname = '文章';
    	} 
    	$tplDir = $dir.'/'.$modelsystpl; 
    	if(!is_dir($tplDir)) $this->error('<li class="err">错误，系统内置代码模板 <strong>'.$modelsysname.'模板</strong> '.$tplDir.' 未找到</li>');
    	
    	//取得当前表的字段
		$thisFields = $this->chead($_POST['modelname']);
    	
    	/*****下拉菜单，选择系统内置模板后，ajax显示一下找到的模板表和本表的对应关系选择界面******/
    	if($_GET['action']=='selecttpl'){
    		$file = $dir.'/'.$modelsystpl.'/data/ch_'.strtolower($modelsystpl).'.sql';
    		if(!is_file($file)){
    			$this->error('<li class="err">错误，系统内置代码模板的Sql文件 <strong>'.$file.'</strong>未找到</li>');
    		}else{
    			//取得上一次设置的表字段对应关系    			
				$dbFieldsMap = M('Model_fieldnotes')->where(array('ename'=>$_POST['modelname']))->getField('fieldsmap');
				if($dbFieldsMap) $dbFieldsMap = unserialize($dbFieldsMap);
				else $dbFieldsMap = array();
				
    			//某某模板的表字段
    			$strTplFields = ''; $str=''; $sqlContent = file_get_contents($file);
    			if(preg_match('/CREATE TABLE `ch_'.strtolower($modelsystpl).'` \(([\s\S]+)\)/', $sqlContent, $sqlArr)){
    				if(preg_match_all('/`(.+?)` ([^\( ,]+)(\(\d+\))?(.*)?,?/', $sqlArr[1], $sqlArr)){    					
	    				for($i=0,$n=count($sqlArr[0]); $i<$n; $i++){ 
	    					$sysTplFields[$sqlArr[1][$i]]['name'] = $sqlArr[1][$i];
	    					$sysTplFields[$sqlArr[1][$i]]['type'] = $sqlArr[2][$i];
	    					$sysTplFields[$sqlArr[1][$i]]['size'] = ($sqlArr[3][$i]!='') ? trim($sqlArr[3][$i],'()') : 0;
	    					$sysTplFields[$sqlArr[1][$i]]['isnull'] = (strpos($sqlArr[4][$i],'NOT NULL')!==false) ? 0 : 1;
	    					$sysTplFields[$sqlArr[1][$i]]['autoinc'] = (strpos($sqlArr[4][$i],'AUTO_INCREMENT')!==false) ? 1 : 0;
	    					
	    					if(preg_match('/ COMMENT [\'"](.*?)[\'"]/', $sqlArr[4][$i], $sqlArr2)){
		    					if(($pos = strpos($sqlArr2[1],' '))!==false){
		    						$sysTplFields[$sqlArr[1][$i]]['cname'] = substr($sqlArr2[1],0,$pos);
		    						$sysTplFields[$sqlArr[1][$i]]['cnote'] = substr($sqlArr2[1],$pos+1);
		    					}else{
		    						$sysTplFields[$sqlArr[1][$i]]['cname'] = $sqlArr2[1];
		    						$sysTplFields[$sqlArr[1][$i]]['cnote'] = '';
		    					}
	    					}
	    					if(preg_match('/ DEFAULT [\'"](.*?)[\'"]/', $sqlArr[4][$i], $sqlArr2)){
	    						$sysTplFields[$sqlArr[1][$i]]['default'] = $sqlArr2[1];
	    					}	    					
	    				}	
	    				
	    				//输出字段对应关系的选择界面
    					$return.='<table id="sysTplFieldsTable" class="list">
                        <thead>
                        <tr>
                            <th>字段名</th>
                            <th>中文名</th>
                            <th>备注参数</th>
                            <th>默认值</th>
                            <th>类型</th>
                            <th>空值</th>
                            <th>自增</th>
                            <th style="color:#009B1F">到'.$_POST['modeltitle'].'对应字段</th>
                            <th style="color:#009B1F">输入框类型</th>
                        </tr>
                        </thead>
                        <tbody>';
	    				foreach($thisFields as $k=>$rs){
	    					$return.='<tr onmouseover="this.style.backgroundColor=\'#7cc5e5\';" onmouseout="this.style.backgroundColor=\'\';">
                            <td><strong>'.$rs['name'].'</strong><input type="hidden" name="replace_names[]" value="'.$rs['name'].'" /></td>
                            <td>'.$rs['cname'].'</td>
                            <td>'.$rs['cnote'].'</td>
                            <td>'.$rs['default'].'</td>
                            <td>'.$rs['type'].($rs['size']>0 ? '('.$rs['size'].')' : '').'</td>
                            <td>'.($rs['isnull'] ? '<strong>是</strong>' : '否').'</td>
                            <td>'.($rs['autoinc'] ? '<strong>是</strong>' : '否').'</td>
                            <td><select name="search_names[]" style="width:150px;"><option value="" style="color:#FF0000">【--无对应--】</option>';	
		    				foreach($sysTplFields as $k=>$v){
					        	$return .= '<option value="'.$k.'"'.(($k==$rs['name'] || $k==$dbFieldsMap[$rs['name']]['search_names']) ? ' selected style="background-color:#00FF99"' : '').'>'.$k.'（'.$v['cname'].($v['cnote']!='' ? ' '.$v['cnote'] : '').'）</option>';
					        }
                            $return.='</select></td><td>';
                            if(!$rs['autoinc']){
	                            $return.='<select name="inputType[]"><option value="" style="color:#FF0000">不设置</option>';
	                            //根据字段类型和备注设置确定可设置哪些类型的输入框
	                            $types = '1:单行文本,2:多行文本,12:内容编辑器,3:上传图片,4:上传文件,5:日期,6:数字,7:金额';
	                            if( preg_match('/^((.+?:.+?)(,.+?:.+)*)|(.+?,.+)|(\d+~\d+)$/', $rs['cnote']) ) $types .= ',8:复选框,9:单选框,10:下拉菜单';
	                            $return.=PrintOption($dbFieldsMap[$rs['name']]['inputType'], $types);
	                            $return.='</select>';
                            }else{
	                            $return.='——<input type="hidden" name="inputType[]" value="">';    	
                            }
                            $return.='</td>
                        </tr>';
	    				}
    					$return.='</tbody>
                    </table>';
    					$return .= '<input type="hidden" name="sysTplFields" value="'.urlencode(serialize($sysTplFields)).'">';
	    				$this->success($return);
    				}
    			}
    		}
    		$this->error('<li class="err">错误，系统内置代码模板的表字段解析失败</li>');
    	}
    	/*****ajax get end******/    
    		
    	$sysTplFields = unserialize(urldecode($_POST['sysTplFields'])); //选定的系统内置模板表字段
    	
    	//根据选择的模板类型，读取系统内置代码模板，然后替换一些东西后输出到相应位置
    	$target = $root.'/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.strtolower($_POST['modelname']);
    	if(!is_dir($target)){
			mkdir($target, 0777);
			$return .= "<li class=\"success\">&nbsp;创建新目录".$target."</li>\n";
		}
		$data = Dir::get_allfiles($tplDir);
		foreach($data as $key=>$dirfile){
			$target = str_replace($tplDir.'/', $root.'/', $dirfile); //目标目录或文件
			$target = str_replace(array($modelsystpl.'Model', $modelsystpl.'Action','/'.$modelsystpl.'/','/'.strtolower($modelsystpl).'/','/'.$modelsystpl.'.','/'.strtolower($modelsystpl).'.'), 
					array($_POST['modelname'].'Model', $_POST['modelname'].'Action','/'.$_POST['modelname'].'/','/'.strtolower($_POST['modelname']).'/','/'.$_POST['modelname'].'.','/'.strtolower($_POST['modelname']).'.'), $target);
			if(is_dir($dirfile)){
				if(!is_dir($target)){
					mkdir($target, 0777);
					$return .= "<li class=\"success\">&nbsp;创建新目录 {$target}</li>\n";
				} 
			}else{
				if( preg_match('/\/\w+_'.$modelsystpl.'\.sql$/i', $target) ) continue; //排除sql文件
				if(!is_dir(dirname($target))){
					mkdir(dirname($target), 0777);
					$return .= "<li class=\"success\">&nbsp;创建新目录".dirname($target)."</li>\n";
				} 
				if( !is_array($_POST['files']) || in_array($target, $_POST['files']) ){ //初次生成或者选中覆盖以后生成
					if( !is_file($target) || in_array($target, $_POST['files']) ){
						
						if(($baseCode = file_get_contents($dirfile))===false){ //读取代码模板数据
							$return .= "<li class=\"err\"><label><input type=\"checkbox\" name=\"files[]\" value=\"$target\" checked>{$target} 读取失败，选择重试</label></li>\n";
						}else{							
							//根据表字段对应关系替换页面中的代码 目标不为空则替换，否则新建源的表单							
							if(is_array($_POST['search_names']) && is_array($_POST['replace_names'])){
								//★去掉★ 模板代码中的  $sysTplFields在search_names中未出现过的  字段
								foreach($sysTplFields as $k=>$v){
									if( !in_array($k, $_POST['search_names']) ){
										if( strpos($target, $root.'/Admin/Tpl/')!==false ){ //后台文件
											//新增修改页面
											$baseCode = preg_replace('/<div +class="unit">\s*<label>.+?<\/label>([\s\S]{0,100})(<.*? name="'.$v['name'].'".*?>|\.'.$v['name'].'|\[[\'"]'.$v['name'].'[\'"]\])([\s\S]*?)<\/div>\s*/i','',$baseCode);
											//列表页面
											$baseCode = str_replace('<th>'.$v['cname'].'</th>','',$baseCode);
											$baseCode = preg_replace('/<td>\{\$\w+\[\''.$v['name'].'\'\](.*?)\}<\/td>/i','',$baseCode);
										}else{
											//前台不做去掉操作
										}
									}
								}																
							
								$this->_for_dochead_2($target, $thisFields, $sysTplFields, $baseCode);
								
								//**配置文件中指定的隐射关系的别名也要做替换
								$tmpReNames = array(); $tmp = is_array(C('CMSHEAD_KEY_ROUTER')) ? C('CMSHEAD_KEY_ROUTER') : array();
								foreach($tmp as $tmpReName=>$tmpToName){							
									if( $tmpToName == $modelsystpl ){
										$tmpReNames[] = preg_quote($tmpReName);
									}
								}
								if($tmpReNames){
									$baseCode = preg_replace('/([\(\'",=])('.implode('|',$tmpReNames).')([\)\'",])/i', '${1}'.$_POST['modelname'].'\\3', $baseCode);
								}
								
								//****最后替换一下未完成的替换，中英文的替换。 $modelsysname ->  $modeltitle
								$baseCode = str_replace(array($modelsystpl, strtolower($modelsystpl), $modelsysname), array($_POST['modelname'], strtolower($_POST['modelname']), $modeltitle), $baseCode);									
							}
							if(file_put_contents($target,$baseCode)){
								$return .= "<li class=\"success\"><label><input type=\"checkbox\" name=\"files[]\" value=\"$target\">覆盖 {$target} 已生成</label></li>\n";
							}else{
								$return .= "<li class=\"err\"><label><input type=\"checkbox\" name=\"files[]\" value=\"$target\" checked>{$target} 生成失败，选择重试</label></li>\n";
							}			
						}
						
					}else{
						$return .= "<li><label><input type=\"checkbox\" name=\"files[]\" value=\"$target\">覆盖 {$target} 已存在 </label></li>\n";
					}
		    	}
				
			}
			
		}
		
		if(strpos($return,'checkbox')){
			$return = '<li><label><input type="checkbox" onclick="selectAll(this)">全选下列文件以重新生成</label></li>'."\n".$return;
		}
		$this->success(rtrim($return,"\n"));
    }
    
    //选择字段隐射关系后对$baseCode的操作
    private function _for_dochead_2($target, $thisFields, $sysTplFields, &$baseCode){
    	$dbFieldsMap = array();
	    for($i=0,$n=count($_POST['search_names']); $i<$n; $i++){
			//记录到数据库的
			$dbFieldsMap[$_POST['replace_names'][$i]] = array(
				'search_names' => $_POST['search_names'][$i],
				'inputType' => $_POST['inputType'][$i],
			);
			
			if($_POST['search_names'][$i]!=''){ //选了对应关系  纯替换 
				$this->_for_for_dochead_2($target, $thisFields, $sysTplFields, $baseCode, $i, 'edit');
			}else{ //无对应或者多出来的字段	 在模板代码中   新增字段
				$this->_for_for_dochead_2($target, $thisFields, $sysTplFields, $baseCode, $i, 'new');
			}
		}
		
		//把对应关系记录到Model_fieldnotes表
		$data = array(
			'fieldsmap'=>serialize($dbFieldsMap),
		);
		M('Model_fieldnotes')->where(array('ename'=>$_POST['modelname']))->save($data);
    }
    
    //修改或者新增字段的具体操作
    private function _for_for_dochead_2($target, $thisFields, $sysTplFields, &$baseCode, $i, $doType){    	   	 
    	$root = $_SERVER['DOCUMENT_ROOT'].__ROOT__;
    	$modelname = strtolower($_POST['modelname']);	
    	if( strpos($target, $root.'/Admin/Tpl/')!==false ){ //后台模板
			$isedit = (strpos(basename($target), 'edit.')!==false);
			$strid = M($_POST['modelname'])->getPk();
			$strReadonly = (stripos($thisFields[$_POST['replace_names'][$i]]['cnote'], 'readonly')!==false) ? ' readonly' : '';  $nameClass = '';
			if( !$thisFields[$_POST['replace_names'][$i]]['isnull'] ) $nameClass = 'required';
			if( preg_match('/email|url|date|number|digits|creditcard|phone|alphanumeric|lettersonly/', $thisFields[$_POST['replace_names'][$i]]['cnote'], $arr) ) $nameClass.=' '.$arr[0];	
			//1:单行文本,2:多行文本,12:内容编辑器,3:上传图片,4:上传文件,5:日期,6:整数,7:浮点数,8:复选框,9:单选框,10:下拉菜单
			$bleftSpace = "\r\n\t\t\t";  $leftSpace = "\r\n\t\t\t\t"; $newFieldInput = '';
			switch ($_POST['inputType'][$i]){
				case 1:
					if($nameClass!='') $strClass = ' class="'.trim($nameClass).'"';
					$strInput = '<input type="text" name="'.$_POST['replace_names'][$i].'" size="40" value="'.($isedit ? '{$vo.'.$_POST['replace_names'][$i].'}' : $thisFields[$_POST['replace_names'][$i]]['default']).'"'.$strClass.$strReadonly.'>';
					break;
				case 2:
					if($nameClass!='') $strClass = ' class="'.trim($nameClass).'"';
					$strInput = '<textarea name="'.$_POST['replace_names'][$i].'" rows="5" cols="50"'.$strClass.$strReadonly.'>'.($isedit ? '{$vo.'.$_POST['replace_names'][$i].'}' : $thisFields[$_POST['replace_names'][$i]]['default']).'</textarea>';
					break;
				case 12:
					$strClass = ' class="'.(($nameClass!='') ? 'editor '.trim($nameClass) : 'editor').'"';
					$strInput = '<textarea name="'.$_POST['replace_names'][$i].'"'.$strClass.$strReadonly.' height="350" tools="full" pagetag upLinkUrl="__APP__/'.$modelname.'/upload/" upImgUrl="__APP__/'.$modelname.'/upload/" upFlashUrl="__APP__/'.$modelname.'/upload/" upMediaUrl="__APP__/'.$modelname.'/upload/">'.($isedit ? '{$vo.'.$_POST['replace_names'][$i].'}' : $thisFields[$_POST['replace_names'][$i]]['default']).'</textarea>';
					break;
				case 3:
					$strAlt = (false!==strpos($nameClass,'required')) ? '<span style="color:red">上传或外部必填一项</span>' : '';
					if($isedit){
						$newFieldInput = '<?php if($vo[\''.$_POST['replace_names'][$i].'\']!=\'\' && !preg_match(\'/^\/|((https?|ftp):\/\/)/i\',$vo[\''.$_POST['replace_names'][$i].'\'])){ ?>'.$bleftSpace.'<div class="unit">'.$leftSpace.'<label>'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.
						$leftSpace.'<a href="{$vo.'.$_POST['replace_names'][$i].'|chimg='.$modelname.'}" target="_blank"><img src="{$vo.'.$_POST['replace_names'][$i].'|chimg='.$modelname.',100,100}" height="40" border="0" /></a><input type="hidden" name="'.$_POST['replace_names'][$i].'" value="{$vo.'.$_POST['replace_names'][$i].'}" />'.
						$leftSpace.'<a href="__URL__/delfile/id/{$vo.'.$strid.'}/field/'.$_POST['replace_names'][$i].'/navTabId/__MODULE__" target="ajaxTodo" title="你确定要删除已上传图片吗？">删除图片</a>'.$bleftSpace.'</div>'.$bleftSpace.'<?php }?>'.
						$bleftSpace.'<div class="unit">'.$leftSpace.'<label>上传'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.'<input type="file" name="'.$_POST['replace_names'][$i].'" />'.$leftSpace.$strAlt.$bleftSpace.'</div>'.$bleftSpace.'<div class="unit">'.
						$leftSpace.'<label>外部'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.'<input type="text" name="'.$_POST['replace_names'][$i].'url" id="'.$_POST['replace_names'][$i].'url" size="40" value="<?php echo preg_match(\'/^\/|((https?|ftp):\/\/)/i\',$vo[\''.$_POST['replace_names'][$i].'\']) ? $vo[\''.$_POST['replace_names'][$i].'\'] : \'\'; ?>">'.
						$bleftSpace.'</div>';						
					}else{
						$newFieldInput = '<div class="unit">'.$leftSpace.'<label>上传'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.'<label><input type="file" name="'.$_POST['replace_names'][$i].'" /></label>'.$leftSpace.$strAlt.$bleftSpace.'</div>'.$bleftSpace.
						'<div class="unit">'.$leftSpace.'<label>外部'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.'<input type="text" name="'.$_POST['replace_names'][$i].'url" size="40" value="'.$thisFields[$_POST['replace_names'][$i]]['default'].'" class="url"'.$strReadonly.'>'.$bleftSpace.'</div>';						
					}
					break;
				case 4:
					$strAlt = (false!==strpos($nameClass,'required')) ? '<span>上传或外部必填一项</span>' : '';
					if($isedit){
						$newFieldInput = '<?php if($vo[\''.$_POST['replace_names'][$i].'\']!=\'\' && !preg_match(\'/^\/|((https?|ftp):\/\/)/i\',$vo[\''.$_POST['replace_names'][$i].'\'])){ ?>'.$bleftSpace.'<div class="unit">'.
						$leftSpace.'<label>'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.'{$vo.'.$_POST['replace_names'][$i].'}<input type="hidden" name="'.$_POST['replace_names'][$i].'" value="{$vo.'.$_POST['replace_names'][$i].'}" />'.$leftSpace.
						'<a href="__URL__/delfile/id/{$vo.id}/field/'.$_POST['replace_names'][$i].'/navTabId/__MODULE__" target="ajaxTodo" title="你确定要删除已上传文件吗？">删除文件</a>'.$bleftSpace.
						'</div>'.$bleftSpace.'<?php }?>'.$bleftSpace.'<div class="unit">'.$leftSpace.'<label>上传'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.'<input type="file" name="'.$_POST['replace_names'][$i].'" />'.
						$leftSpace.$strAlt.$bleftSpace.'</div>'.$bleftSpace.'<div class="unit">'.$leftSpace.'<label>外部'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.
						'<input type="text" name="'.$_POST['replace_names'][$i].'url" id="'.$_POST['replace_names'][$i].'url" size="40" value="<?php echo preg_match(\'/^\/|((https?|ftp):\/\/)/i\',$vo[\''.$_POST['replace_names'][$i].'\']) ? $vo[\''.$_POST['replace_names'][$i].'\'] : \'\'; ?>">'.$bleftSpace.'</div>';						
					}else{
						$newFieldInput = '<div class="unit">'.$leftSpace.'<label>上传'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.'<label><input type="file" name="'.$_POST['replace_names'][$i].'" /></label>'.$leftSpace.$strAlt.$bleftSpace.'</div>'.$bleftSpace.
						'<div class="unit">'.$leftSpace.'<label>外部'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.'<input type="text" name="'.$_POST['replace_names'][$i].'url" size="40" value="'.$thisFields[$_POST['replace_names'][$i]]['default'].'" class="url"'.$strReadonly.'>'.$bleftSpace.'</div>';						
					}					
					break;
				case 5:
					$strClass = ' class="'.trim($nameClass).'" readonly';
					$strClass .= ' datefmt="yyyy-MM-dd HH:mm:ss"';
					$strInput = '<input type="text" name="'.$_POST['replace_names'][$i].'" size="40" value="'.($isedit ? '{$vo.'.$_POST['replace_names'][$i].'}' : $thisFields[$_POST['replace_names'][$i]]['default']).'"'.$strClass.'>';
					break;	
				case 6: //整数
					$strClass = ' class="'.trim($nameClass).'"';
					$strInput = '<input type="text" name="'.$_POST['replace_names'][$i].'" size="40" value="'.($isedit ? '{$vo.'.$_POST['replace_names'][$i].'}' : $thisFields[$_POST['replace_names'][$i]]['default']).'"'.$strClass.$strReadonly.'>';
					break;
				case 7: //浮点数
					$strClass = ' class="'.trim($nameClass).'"';
					$strInput = '<input type="text" name="'.$_POST['replace_names'][$i].'" size="40" value="'.($isedit ? '{$vo.'.$_POST['replace_names'][$i].'}' : $thisFields[$_POST['replace_names'][$i]]['default']).'"'.$strClass.$strReadonly.'>';
					break;
				case 8:
					if($nameClass!='') $strClass = ' class="'.trim($nameClass).'"';
					$strInput = '<ul><?php echo PrintcBox(\''.$thisFields[$_POST['replace_names'][$i]]['cnote'].'\', \''.$_POST['replace_names'][$i].'\', \''.$strClass.$strReadonly.'\', '.($isedit ? '$vo[\''.$_POST['replace_names'][$i].'\']' : '\''.$thisFields[$_POST['replace_names'][$i]]['default'].'\'').') ?></ul>';													
					break;	
				case 9:
					if($nameClass!='') $strClass = ' class="'.trim($nameClass).'"';
					$strInput = '<ul><?php echo PrintRadio(\''.$thisFields[$_POST['replace_names'][$i]]['cnote'].'\', \''.$_POST['replace_names'][$i].'\', \''.$strClass.$strReadonly.'\', '.($isedit ? '$vo[\''.$_POST['replace_names'][$i].'\']' : '\''.$thisFields[$_POST['replace_names'][$i]]['default'].'\'').') ?></ul>';													
					break;	
				case 10:
					if($nameClass!='') $strClass = ' class="'.trim($nameClass).'"';
					$strInput = '<select name="'.$_POST['replace_names'][$i].'"'.$strClass.$strReadonly.'>'.$leftSpace;
					$strInput .= '<?php echo PrintOption('.($isedit ? '$vo[\''.$_POST['replace_names'][$i].'\']' : '\''.$thisFields[$_POST['replace_names'][$i]]['default'].'\'').', \''.$thisFields[$_POST['replace_names'][$i]]['cnote'].'\') ?>'.$leftSpace;
					$strInput .= '</select>';
					break;
				default:
					if($nameClass!='') $strClass = ' class="'.trim($nameClass).'"';
					$strInput = '<input type="text" name="'.$_POST['replace_names'][$i].'" size="40" value="'.($isedit ? '{$vo.'.$_POST['replace_names'][$i].'}' : $thisFields[$_POST['replace_names'][$i]]['default']).'"'.$strClass.$strReadonly.'>';									
					break;
			}			
			if($newFieldInput=='') $newFieldInput = '<div class="unit">'.$leftSpace.'<label>'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>'.$leftSpace.$strInput.$bleftSpace.'</div>';			
			if( $doType=='new' ){
				$baseCode = preg_replace('/<\/div>[\s]*<div class="formBar">/i', "\t{$newFieldInput}\r\n\t\t</div>\r\n\t\t<div class=\"formBar\">", $baseCode);
			}else{
				if($_POST['search_names'][$i]!=''){
					//新增修改页面
					if($_POST['inputType'][$i]==''){ //***有对应关系的时候（edit），不设置输入框类型，则只替换名称，不替换输入框类型***
						$baseCode = str_replace('<label>'.$sysTplFields[$_POST['search_names'][$i]]['cname'].'：</label>','<label>'.$thisFields[$_POST['replace_names'][$i]]['cname'].'：</label>',$baseCode);
					}else{
						$baseCode = preg_replace('/<div +class="unit">\s*<label>.+?<\/label>([\s\S]{0,100})(<.*? name="'.$_POST['search_names'][$i].'".*?>|\.'.$_POST['search_names'][$i].'|\[[\'"]'.$_POST['search_names'][$i].'[\'"]\])([\s\S]*?)<\/div>/i', $newFieldInput, $baseCode);
					}
					
					//★★输入框属性的替换 如class="required"  readonly 这里是上次忘记做的部分，今天补上2013-3-11★★
					if($nameClass!=''){	
						$nameClass = trim($nameClass);
						//先替换以前本来就有class属性的
						if( preg_match_all('/(<.+?)( name="'.$_POST['search_names'][$i].'")(.*?>)/i', $baseCode, $tmpArr) ){
							foreach($tmpArr[0] as $tk=>$tmpVal){
								if( preg_match('/ type=["\']file["\']/i',$tmpArr[0][$tk]) ){ //上传的因为加了个外部的输入框，不能直接加必填
									if(false!==stripos($nameClass,'required')){
										$baseCode = str_replace($tmpArr[0][$tk], '<label>'.$tmpArr[0][$tk]."</label>\r\n\t\t\t\t<span style=\"color:red\">上传或外部必填一项</span>", $baseCode);
									}
								}else{
									if( preg_match('/( class=["\'])(.*?)(["\'])/i',$tmpArr[0][$tk],$arr1) ){
										if(false===stripos($arr1[2],'required')){
											$newtmpArr0 = str_replace($arr1[0], $arr1[1].$arr1[2].' '.$nameClass.$arr1[3], $tmpArr[1][$tk]) . $tmpArr[2][$tk]
												. str_replace($arr1[0], $arr1[1].$arr1[2].' '.$nameClass.$arr1[3], $tmpArr[3][$tk]);
											$baseCode = str_replace($tmpArr[0][$tk], $newtmpArr0, $baseCode);
										}
									}
								}
							}
						}
					}
					
					//列表页面
					$baseCode = str_ireplace(array('<th>'.$sysTplFields[$_POST['search_names'][$i]]['cname'].'</th>','/'.$_POST['search_names'][$i].'/','\\'.$_POST['search_names'][$i].'\\'),
					array('<th>'.$thisFields[$_POST['replace_names'][$i]]['cname'].'</th>','/'.$_POST['replace_names'][$i].'/','\\'.$_POST['replace_names'][$i].'\\'),$baseCode);
					
					//★后台通用替换★ 字段名称替换，但是只能把vo['name']  vo.name后面 |'"] name="name" 等等这几种情况做替换，而 不能直接替换字段名称，那样会乱套的	
					$baseCode = preg_replace('/((\w+\[[\'"]?)|(\w+[\.:])|[=\'"]|->)('.$_POST['search_names'][$i].')([\W])/i', '${1}'.$_POST['replace_names'][$i].'\\5', $baseCode);
				}
			}
		}else{ //非后台模板文件			
			if( $doType=='new' ){
				//新增上传部分的处理代码，其他暂无特殊处理
				$strInput = '';
				if($_POST['inputType'][$i]==3){
					$strInput = '$this->do_upload($model); //上传字段处理';
				}elseif($_POST['inputType'][$i]==4){
					$strInput = '$this->do_upload($model); //上传字段处理';
				}
				if($strInput!=''){
					$baseCode = str_replace('//保存当前数据对象', $strInput."\r\n\t\t".'//保存当前数据对象', $baseCode);
				}
			}
			
			if($_POST['search_names'][$i]!=''){
				$baseCode = str_replace(array($_POST['search_names'][$i].'Model', $_POST['search_names'][$i].'Action'),
						array($_POST['replace_names'][$i].'Model', $_POST['replace_names'][$i].'Action'), $baseCode);
				
				$baseCode = str_ireplace(array("'".$_POST['search_names'][$i]."'", '"'.$_POST['search_names'][$i].'"', '/'.$_POST['search_names'][$i].'/', '\\'.$_POST['search_names'][$i].'\\'), 
						array("'".$_POST['replace_names'][$i]."'", '"'.$_POST['replace_names'][$i].'"', '/'.$_POST['replace_names'][$i].'/', '\\'.$_POST['replace_names'][$i].'\\'), $baseCode);					
						
				$baseCode = str_replace(array("'".strtolower($_POST['search_names'][$i])."'", '"'.strtolower($_POST['search_names'][$i]).'"', '/'.strtolower($_POST['search_names'][$i]).'/', '\\'.strtolower($_POST['search_names'][$i]).'\\'), 
						array("'".strtolower($_POST['replace_names'][$i])."'", '"'.strtolower($_POST['replace_names'][$i]).'"', '/'.strtolower($_POST['replace_names'][$i]).'/', '\\'.strtolower($_POST['replace_names'][$i]).'\\'), $baseCode);
				
				$baseCode = preg_replace('/((\w+\[[\'"]?)|(\w+[\.:])|->)('.$_POST['search_names'][$i].')([\W])/i', '${1}'.$_POST['replace_names'][$i].'\\5', $baseCode);
			}
		}
    }
}