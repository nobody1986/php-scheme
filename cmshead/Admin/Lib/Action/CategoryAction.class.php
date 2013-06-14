<?php
// 分类模型
class CategoryAction extends CommonAction {
	//赋值可用模型
	function _filter(&$map){
		if( !isset($map['classpid']) ) $map['classpid'] = isset($_REQUEST['classpid']) ? $_REQUEST['classpid'] : 0;
	}
	
	public function _before_index() {
		$this->assign('classmodule',D('Category')->getModule());
	}
	//赋值可用模型
	public function _before_add() {
		$this->assign('classmodule',D('Category')->getModule());
	}
	//赋值可用模型
	public function _before_edit() {
		$this->assign('classmodule',D('Category')->getModule());
	}
	//添加分类
	public function insert(){
		$model = D ('Category');
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}	
		
		$this->do_upload($model); //上传字段处理
		
		if (($classid = $model->add ())!==false) { //保存成功
			D('Category')->setParentsChilds($classid); //更新指定目录及其一条线上的所有栏目的pid,pids,child,childids字段
			$this->auto_URLRewrite($classid);
			if($_POST['classrewrite']){
				$data['rewrite']=$_POST['classrewrite'];
				$data['url']=strtolower($_POST['classmodule']).'/index/id/'.$classid;
				M('Router')->add($data);
			}
			$this->success ('新增成功！');
		} else {
			//失败提示
			$this->error ('新增失败！');
		}
	}
	//编辑分类
	public function update() {
		$model = D ( 'Category' );	
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		
		$this->do_upload($model); //上传字段处理
			
		// 更新数据
		if ($model->save () !== false) {
			D('Category')->setParentsChilds($_POST['classid']); //更新指定目录及其一条线上的所有栏目的pid,pids,child,childids字段
			$this->auto_URLRewrite();
			//路由的保存			
			$data['url']=strtolower($_POST['classmodule'])."/index/id/".$_POST['classid'];
			if($_POST['classrewrite']){
				$data['rewrite']=$_POST['classrewrite'];
				if(M('Router')->where("url='{$data['url']}'")->count()){
					M('Router')->where("url='{$data['url']}'")->save($data);
				}else{
					M('Router')->add($data);
				}
			}else{
				M('Router')->where("url='{$data['url']}'")->delete();
			}
			$this->success ('编辑成功！');
		} else {
			//错误提示
			$this->error ('编辑失败！');
		}
	}	
	
	//删除分类的同时，删除路由规则以及子栏目以及其模型下对应的文章等等
	public function _before_foreverdelete() {
		if($_REQUEST['classid']){
			$classid = is_array($_REQUEST['classid']) ? implode(',',$_REQUEST['classid']) :(preg_match('/^\d+(,\d+)*$/',$_REQUEST['classid']) || is_numeric($_REQUEST['classid']) ? $_REQUEST['classid'] : 0);
			if($classid){
				//路由
				$list = D('Category')->where("classid in ($classid)")->field('classrewrite,classmodule')->select();
				foreach($list as $rs){
					if($rs['classrewrite']!='') $rewrite .= ($rewrite!='' ? "','" : '') . "'{$rs['classrewrite']}'";	
					M($rs['classmodule'])->where("tid='$classid'")->delete();
				} 		
				if($rewrite) M('Router')->where('rewrite in ('.$rewrite.')')->delete();	

				//子栏目
				D('Category')->where("classpid in ($classid)")->delete();
			}
		}
	}
	
	//彻底删除
	public function foreverdelete() {
		//删除所有下级，取一个上级，用函数更新所有上级
		if ( preg_match('/^\d+(,\d+)*$/',$_REQUEST['classid']) ) {
			$list = M('Category')->where("classid in ({$_REQUEST['classid']})")->field('classpid,classchildids')->select();
			foreach($list as $rs){
				if (M('Category')->where ( array('classid'=> array('in', explode(',',$rs['classchildids'])) ) )->delete()) {
					if($rs['classpid']){
						D('Category')->setParentsChilds($rs['classpid']); //更新指定目录及其一条线上的所有栏目的pid,pids,child,childids字段						
					}
				}
			} 	
			$this->success ('删除成功！');
		} else {
			$this->error ( '非法操作' );
		}		
		$this->forward ();
	}
	
	//复制栏目
	public function copy(){
		if( $_SERVER['REQUEST_METHOD']=='POST' ){
			if(!preg_match('/^\d+(,\d+)*$/',$_POST['classid'])) $this->error('必须至少选择一项栏目。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			if(!preg_match('/^\d+(,\d+)*$/',$_POST['classpid'])) $this->error('必须至少选择一项父级目录。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$pk = 'classid'; $from_classids = explode(',', $_POST['classid']);
			$fields = M('category')->getDbFields();
			$fields = trim(',`'.implode('`,`',$fields).'`,', ',');
			$fields = str_replace("`$pk`", 0, $fields);
			$fields = str_replace("`classrewrite`", "''", $fields); //去掉rewrite属性
			$fields = str_replace("`classdomain`", "''", $fields);
			$fields = str_replace("`classpids`", "''", $fields); //去掉关系字段由函数填充
			$fields = str_replace("`classchild`", "''", $fields);
			$fields = str_replace("`classchildids`", "''", $fields);
			$fields = str_replace("`classarrchildids`", "''", $fields);
			
			foreach( explode(',',$_POST['classpid']) as $classpid ){
				//标题附加上_copy
				if($_POST['rtitle']!=''){
					if($_POST['stitle']!=''){
						$new_fields = str_replace('`classtitle`', "replace(`classtitle`,'{$_POST['stitle']}','{$_POST['rtitle']}') as `classtitle`", $fields);
					}else{
						$new_fields = str_replace('`classtitle`', "concat(`classtitle`, '{$_POST['rtitle']}') as `classtitle`", $fields);						
					}
				}else{
					$new_fields = $fields;
				}
				//设置为目标父级栏目的模型名称
				$new_fields = str_replace('`classmodule`', "'".M('category')->where(array($pk=>$classpid))->getField('classmodule')."'", $new_fields);
				//要取源栏目的所有子栏目，所以需要循环读取
				foreach($from_classids as $from_cid){					 
					$classchildids = M('category')->where(array($pk=>$from_cid))->getField('classchildids'); //一个源栏目的所有子栏目
					if(false===strpos($classchildids,',')){ //没有下级
						//设置为目标父级栏目
						$new_fields2 = str_replace('`classpid`', $classpid, $new_fields);
						$sql = 'insert into `'.C('DB_PREFIX').'category'.'` select '.$new_fields2.' from `'.C('DB_PREFIX').'category'.'` where '.$pk.'='.$from_cid;				
						M('category')->query($sql);
						D('Category')->setParentsChilds(M('category')->order($pk.' desc')->getField($pk)); //更新指定目录及其一条线上的所有栏目的pid,pids,child,childids字段
					}else{
						$classchildids = explode(',',$classchildids);
						$parentInfo = array();
						foreach($classchildids as $childcid){ //自己,下级,下下级,下下下级,下级...
							if($parentInfo){
								$childcid_pid = M('category')->where(array($pk=>$childcid))->getField('classpid'); //226->224
								$new_fields2 = str_replace('`classpid`', $parentInfo[$childcid_pid], $new_fields);
							}else{
								$new_fields2 = str_replace('`classpid`', $classpid, $new_fields);
							}
							$sql = 'insert into `'.C('DB_PREFIX').'category'.'` select '.$new_fields2.' from `'.C('DB_PREFIX').'category'.'` where '.$pk.'='.$childcid;				
							M('category')->query($sql);
							
							$parentInfo[$childcid] = M('category')->order($pk.' desc')->getField($pk);
							D('Category')->setParentsChilds($parentInfo[$childcid]); //更新指定目录及其一条线上的所有栏目的pid,pids,child,childids字段
						}
					}
				}						
			}
			$this->success('操作成功！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			
		}else{
			if(!preg_match('/^\d+(,\d+)*$/',$_GET['classid'])) $this->error('必须至少选择一项栏目！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$this->assign('classid', $_GET['classid']);
			$this->display();
		}
	}
	
	//移动栏目
	public function move(){
		if( $_SERVER['REQUEST_METHOD']=='POST' ){
			if(!preg_match('/^\d+(,\d+)*$/',$_POST['classid'])) $this->error('必须至少选择一项栏目。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			if(!is_numeric($_POST['classpid'])) $_POST['classpid']=0;
			$pk = 'classid'; $from_classids = explode(',', $_POST['classid']);
			foreach($from_classids as $from_cid){
				$from_cid_oldpid = M('category')->where(array($pk=>$from_cid))->getField('classpid'); //旧的父级	
				if(!$from_cid_oldpid){
					$from_cid_oldpid = M('category')->where($pk.'='.$from_cid.' and classchild=1')->getFiled('classchildids');
					if($from_cid_oldpid){
						$from_cid_oldpid = explode(',',$from_cid_oldpid); $from_cid_oldpid = $from_cid_oldpid[1];
					}
				} 				
				$data = array('classpid'=>$_POST['classpid']);
				M('category')->where($pk.'='.$from_cid)->save($data);
				D('Category')->setParentsChilds($from_cid);  
				
				if($from_cid_oldpid) D('Category')->setParentsChilds($from_cid_oldpid); 
			}				
			$this->success('操作成功！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			
		}else{
			if(!preg_match('/^\d+(,\d+)*$/',$_GET['classid'])) $this->error('必须至少选择一项栏目！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$this->assign('classid', $_GET['classid']);
			$this->display();
		}
	}
	
	//合并栏目
	public function merge(){
		if( $_SERVER['REQUEST_METHOD']=='POST' ){
			if(!preg_match('/^\d+(,\d+)*$/',$_POST['classid'])) $this->error('必须至少选择一项栏目。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			if(!is_numeric($_POST['classpid'])) $this->error('必须选择一项父级目录。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$pk = 'classid'; $from_classids = explode(',', $_POST['classid']);	
			foreach($from_classids as $from_cid){
				//1把源栏目下的信息的所属tid替换为目标栏目  2把源栏目的下一级栏目全部移动到目标栏目 3然后把源栏目删除
				$from_rs = M('category')->where(array($pk=>$from_cid))->field('classmodule,classpid')->find();				
				M('category')->query("update `".C('DB_PREFIX').$from_rs['classmodule']."` set tid=TRIM(BOTH ',' FROM replace(concat(',',tid,',') , ',{$from_cid},' , ',{$_POST['classpid']},')) where find_in_set({$from_cid},tid)");
				
				$classids = array();				
				$list = M('category')->where(array('classpid'=>$from_cid))->field($pk)->select(); 
				foreach($list as $rs){
					$classids[] = $rs[$pk];
				}								
				$data = array('classpid'=>$_POST['classpid']);
				M('category')->where( array($pk=>array('in', $classids)) )->save($data);
				D('Category')->setParentsChilds($_POST['classpid']);  
				
				M('category')->where(array($pk=>$from_cid))->delete(); 
				if($from_rs['classpid']) D('Category')->setParentsChilds($from_rs['classpid']);
			}				
			$this->success('操作成功！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			
		}else{
			if(!preg_match('/^\d+(,\d+)*$/',$_GET['classid'])) $this->error('必须至少选择一项栏目！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$this->assign('classid', $_GET['classid']);
			$this->display();
		}
	}
	
	//设置属性、替换内容等等
	public function attr(){
		if( $_SERVER['REQUEST_METHOD']=='POST' ){
			load('pinyin');						
			$fields = array(); $pk = 'classid';
			$classids = $_POST['haveChild'] ? childClass($_POST['classid'],'id') : $_POST['classid'];
			
			switch($_POST['setURLType']){
				case 1://设置URL重写值(分类中文名的拼音首字母)				
				case 2://设置URL重写值(分类中文名的拼音全拼)		
					$list = M('category')->field($pk.',classtitle,classmodule')->where($pk.' in ('.$classids.')')->select();
					foreach($list as $rs){
						$pinyin = implode('',pinyin($rs['classtitle'],$_POST['setURLType']==1 ? true : false));
						$pinyin = str_replace(array('+','*','%','/','\\'), '', $pinyin);
						
						$n = 0;
						//已有个数后缀，则取出后缀再加1
						$pinyin_rewrites = M('router')->where( "rewrite like '{$pinyin}\_%' and url!='".strtolower($rs['classmodule']).'/index/id/'.$rs[$pk]."'" )->order('length(rewrite) desc,id desc')->field('rewrite')->select();
						if($pinyin_rewrites){
							foreach($pinyin_rewrites as $v){								
								if(preg_match('/^\w+_(\d+)$/', $v['rewrite'], $arr)){
									$n = intval($arr[1]);
									break;
								}
							}
						}
						if($n==0){
							//首次自动计算需要添加的个数后缀							
							//排除自己，否则再次设置的时候出错
							$i = M('router')->where(array('rewrite'=>$pinyin))->count(); 
							$m = M('router')->where(array('url'=>strtolower($rs['classmodule']).'/index/id/'.$rs[$pk]))->count(); 						
							$n = intval($i-$m);
						}
						
						//都要检查根目录是否有这个目录，有的话要排除
						$fileName .= $pinyin . ($n>0 ? '_'.($n+1) : '');
						$f_n = ( is_dir($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.$fileName) || is_file($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/'.$fileName) ) ? 1 : 0;
						
						$n += $f_n;
						$pinyin .= $n>0 ? '_'.($n+1) : '';
						
//						dump($pinyin);exit;
						
						$sql = 'update `'.C('DB_PREFIX').'category` set `classrewrite`=\''.$pinyin.'\' where '.$pk.'='.$rs[$pk];
						M('category')->query($sql);
						$data = array(
							'rewrite'=>$pinyin,
							'url'=>strtolower($rs['classmodule']).'/index/id/'.$rs[$pk],
						);
						if( false!=($id = M('router')->where(array('url'=>strtolower($rs['classmodule']).'/index/id/'.$rs[$pk]))->getField('id')) ){
							M('router')->where( array('id'=>$id) )->save($data);
						}else{
							M('router')->add($data);
						}
					}
					break;			
				case 3://去掉URL重写值
					$sql = 'update `'.C('DB_PREFIX').'category` set `classrewrite`=\'\' where '.$pk.' in ('.$classids.')';
					M('category')->query($sql);
					$list = M('category')->field($pk.',classmodule')->where($pk.' in ('.$classids.')')->select();
					foreach($list as $rs){
						M('router')->where(array('url'=>strtolower($rs['classmodule']).'/index/id/'.$rs[$pk]))->delete();
					}
					break;
				case 4://URL重写值转二级域名
					$sql = 'update `'.C('DB_PREFIX').'category` set `classdomain`=`classrewrite` where '.$pk.' in ('.$classids.')';
					M('category')->query($sql);
					break;
				case 5://去掉二级域名
					$sql = 'update `'.C('DB_PREFIX').'category` set `classdomain`=\'\' where '.$pk.' in ('.$classids.')';
					M('category')->query($sql);
					break;
				case 6://去掉外部网址
					$sql = 'update `'.C('DB_PREFIX').'category` set `classouturl`=\'\' where '.$pk.' in ('.$classids.')';
					M('category')->query($sql);
					break;
				case 10://清空栏目下所有信息
					$list = M('category')->where($pk.' in ('.$classids.')')->field('classid,classmodule')->select();
					foreach($list as $rs){
						M($rs['classmodule'])->where(array('tid'=>$rs['classid']))->delete();
						M('category_map')->where(array('mapclassmodule'=>$rs['classmodule'], 'mapclassid'=>$rs['classid']))->delete();
					}
					break;
			}
			
			if($_POST['classtemplate']!=''){
				$sql = 'update `'.C('DB_PREFIX').'category` set `classtemplate`=\''.($_POST['classtemplate']=='NULL' ? '' : $_POST['classtemplate']).'\' where '.$pk.' in ('.$classids.')';
				M('category')->query($sql);
			}
			if($_POST['newstemplate']!=''){
				$sql = 'update `'.C('DB_PREFIX').'category` set `newstemplate`=\''.($_POST['newstemplate']=='NULL' ? '' : $_POST['newstemplate']).'\' where '.$pk.' in ('.$classids.')';
				M('category')->query($sql);
			}							
			
			if($_POST['skeywords']!='' || $_POST['rkeywords']!=''){
				$fields[] = 'classkeywords';
			}
			if($_POST['sdescription']!='' || $_POST['rdescription']!=''){
				$fields[] = 'classdescription';
			}
			
			if($fields){
				$list = M('category')->field($pk.','.implode(',',$fields))->where($pk.' in ('.$classids.')')->select();
				foreach($list as $rs){					
					if(array_key_exists('classkeywords', $rs)){	
						if($_POST['sclasskeywords']==''){
							$rs['classkeywords'] = $_POST['rclasskeywords'];
						}else{
							$tmp = @preg_replace("/{$_POST['sclasskeywords']}/i", $_POST['rclasskeywords'], $rs['classkeywords']);
							if(!empty($tmp)){
								$rs['classkeywords'] = $tmp;
							}else{
								$rs['classkeywords'] = str_ireplace($_POST['sclasskeywords'], $_POST['rclasskeywords'], $rs['classkeywords']);
							}
						}					
					}
					if(array_key_exists('classdescription', $rs)){
						if($_POST['sclassdescription']==''){
							$rs['classdescription'] = $_POST['rclassdescription'];
						}else{
							$tmp = @preg_replace("/{$_POST['sclassdescription']}/i", $_POST['rclassdescription'], $rs['classdescription']);
							if(!empty($tmp)){
								$rs['classdescription'] = $tmp;
							}else{
								$rs['classdescription'] = str_ireplace($_POST['sclassdescription'], $_POST['rclassdescription'], $rs['classdescription']);
							}
						}						
					}
					M('category')->where($pk.'='.$rs[$pk])->setField($rs);
				}
			}
			
			$this->success('操作成功！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
		}else{
			if(!preg_match('/^\d+(,\d+)*$/',$_GET['classid'])) $this->error('必须至少选择一项信息！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$this->assign('classid', $_GET['classid']);
			$this->display();
		}
	}	
}