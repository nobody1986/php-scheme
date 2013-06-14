<?php
//公共模型
class CommonAction extends Action {
	public $sortBy = ''; //单独定义排序字段
	public $map; //模板里可直接调用，Sys类的查询条件
	public $id; //模板里可直接调用id值
		
	function _initialize() {
		if(MODULE_NAME=='Ui' && ACTION_NAME=='menu') return; //左侧菜单
		// 用户权限检查
		if (C ( 'USER_AUTH_ON' ) && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
			import ( 'ORG.Util.RBAC' );
			if (! RBAC::AccessDecision ()) {
				$url = PHP_FILE . C ( 'USER_AUTH_GATEWAY' );
				$url .= isset($_GET['returnUrl']) ? '?returnUrl='.$_GET['returnUrl'] : '';
				//检查认证识别号
				if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
					//跳转到认证网关
					redirect ( $url );
				}
				// 没有权限 抛出错误
				if (C ( 'RBAC_ERROR_PAGE' )) {
					// 定义权限错误页面
					redirect ( C ( 'RBAC_ERROR_PAGE' ) );
				} else {
					if (C ( 'GUEST_AUTH_ON' )) {
						$this->assign ( 'jumpUrl', $url );
					}
					// 提示错误信息
					$this->error ( L ( '_VALID_ACCESS_' ) );
				}
			}
		}
		load('extend'); //ThinkPHP/Extend/Function/extend.php
	}
	
	public function index() {
		//列表过滤器，生成查询Map对象
		$this->map = $this->_search ();		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $this->map );
		}
		$this->_list();		
		$this->display ();
	}
	/**
     +----------------------------------------------------------
	 * 取得操作成功后要返回的URL地址
	 * 默认返回当前模型的默认操作
	 * 可以在action控制器中重载
     +----------------------------------------------------------
	 * @access public
     +----------------------------------------------------------
	 * @return string
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	function getReturnUrl() {
		return __URL__ . '?' . C ( 'VAR_MODULE' ) . '=' . MODULE_NAME . '&' . C ( 'VAR_ACTION' ) . '=' . C ( 'DEFAULT_ACTION' );
	}

	/**
     +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
     +----------------------------------------------------------
	 * @access protected
     +----------------------------------------------------------
	 * @param string $name 数据对象名称
     +----------------------------------------------------------
	 * @return HashMap
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	protected function _search($name = '') {
		//生成查询条件 “常州  车讯”这样的条件，必须构造成'title&title'=>array('常州','车讯','_multi'=>true),即可。
		/*		
		$map = array(
			'title&title'=>array('常州','车讯','_multi'=>true),
			'module'=>'Article',
			);
		*/	
		if (empty ( $name )) {
			$name = $this->getActionName();
		}
		$name=$this->getActionName();
		$model = D ( $name );
		$fields = $model->getDbFields ();
		unsetValue($fields, 'tid');
		$map = getSearchMap($fields);
		$map = array_merge(D('Common')->getCategoryMap($_REQUEST['tid']), $map);	
		if(isset($_REQUEST['keytype'])){
			$where = getSearchMap(array($_REQUEST['keytype']=>'keyword'));
			if($where) $map['_complex'] = $where;
		}
		//dump($map);
		return $map;
	}
	
	protected function _list() {
		$model = M(MODULE_NAME); $map = $this->map; $sortBy = $this->sortBy;
		//查询的公共字段
		if (method_exists ( $this, '_fields' )) {//为_list方法指定字段
			$fields = $this->_fields ();
		}else{//默认字段
			$fields = $model->getDbFields();
//	 		if( false!==($sk=array_search('content',$fields)) ) unset( $fields[$sk] );
		}
		
		//排序字段 默认为主键名
		$sort = '';
		if (isset ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = ! empty ( $sortBy ) ? $sortBy : (in_array('sort', $fields) ? 'sort desc,' : '').(in_array('add_time', $fields) ? 'add_time desc,' : '').$model->getPk ();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
			$order .= $sort = $_REQUEST ['_sort'] ? ' asc' : ' desc';
		}elseif(empty ( $sortBy )) $order .= ' desc';
		
		$fields[] = '\''.MODULE_NAME.'\' as `module_name`';			
		$model->field($fields)->where($map)->order($order);
		$baseSql = $model->buildSql();
		array_pop($fields); $allFN = count($fields);
		if(preg_match('/^([\( ]*select )(.+?)(order by .+?)?(limit .+)?$/i', $baseSql, $baseSqlArr)){
			$sql = $baseSqlArr[1].' SQL_CALC_FOUND_ROWS '.$baseSqlArr[2];
			
			//取得在其他模块栏目下的文章附属到当前栏目下的部分 
			$tmpwhere['mapissource']=0;
			if($_REQUEST['tid']){
				$tmpwhere['mapclassid']=array('in', $_REQUEST['tid']);			
			}else{
				$tmpwhere['mapclassmodule']=MODULE_NAME;
				if(preg_match('/(( and|or )?\(?[\s]*find_in_set\(\d+,[\s]*`?tid`?\)[\s]*\)?)+/i', $baseSqlArr[2], $classidWhere)){//继承tid条件
					$tmpwhere['_string']=str_ireplace(array('`tid`','tid'),'`mapclassid`',$classidWhere[0]);
				}
			}
			$tmp = M('category_map')->Distinct(true)->field('mapinfoid')->where($tmpwhere)->select();
			if($tmp){					
				$otherInfoIds = $tmpArr = array();
				foreach($tmp as $tmprs){
					$otherInfoIds[] = $tmprs['mapinfoid'];
				}
				$tmp = M('category_map')->field('mapinfoid,mapclassmodule')->where(array('mapclassmodule'=>array('neq', MODULE_NAME), 'mapissource'=>1, 'mapinfoid'=>array('in',$otherInfoIds)))->select();
				foreach($tmp as $tmprs){
					$tmpArr[$tmprs['mapclassmodule']][] = $tmprs['mapinfoid'];					
				}
				
				foreach($tmpArr as $key=>$val){ //模型名=>信息id数组		
					$fields_copy = 	$fields;
					$fields_copy[$allFN] = '\''.$key.'\' as `module_name`';	
					//select a,b,c from hotline union select a,b,'' as c from tv
					$fields_union = M($key)->getDbFields();	
					foreach($fields_copy as $k=>$v){
						if( false===strpos($v, ' ') && !in_array($v, $fields_union) ){
							$fields_copy[$k] = "'' as `$v`";
						}
					}
					$model2 = M($key)->field($fields_copy)->where( array('id'=>array('in', $val)) );
					$tmp = $model2->buildSql();
					if($tmp) $sql .= ' union '.$tmp;
				}
			}
			unset($tmp);
			
			//为获取总条数做准备
			$model->query($sql.' limit 1');
			$tmp = $model->query('select FOUND_ROWS() as rowCount;');
			$rowCount = $tmp[0]['rowCount'];
			
			if($rowCount){
				import('ORG.Util.Page');
				if (! empty ( $_REQUEST ['listRows'] )) {
					$pageSize = $_REQUEST ['listRows'];
				} else {
					$pageSize = MODULE_NAME=='Node' ? 500 : C('PAGE_LISTROWS');
				}
				$pageClass = new Page($rowCount, $pageSize);		
				$page = $pageClass->show();			
				
				$sql .= (false===stripos($baseSqlArr[3],'limit') ? $baseSqlArr[3] : '')." limit {$pageClass->firstRow},{$pageClass->listRows}";
				$list = $model->query($sql);
			}
//			echo($sql);
			
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			$order = explode(' ',$order); $order = $order[0];
			//模板赋值显示
			$this->assign ( 'list', $list );
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( 'page', $page );
			$this->assign ( 'totalCount', $rowCount );
			$this->assign ( 'numPerPage', $pageSize );
		}
		
		$this->assign ( 'currentPage', is_numeric($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);
		import ( "ORG.Util.Cookie" );	
		Cookie::set ( '_currentUrl_', __SELF__ );
		return;
	}

	function insert() {
		//B('FilterString');
		//值为数组的 转换成逗号分隔的字符串
		foreach($_POST as $key=>$val){
			if(is_array($val)){
				$_POST[$key] = implode(',', $val);
			}
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		
		$this->do_upload($model); //上传字段处理
		
		if ( $model->add ()!==false ) { //保存成功
			import ( "ORG.Util.Cookie" );
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('新增成功！');
		} else {
			//失败提示
			$this->error ('新增失败！');
		}
	}

	public function add() {
		$this->display ();
	}

	function read() {
		$this->edit ();
	}

	function edit() {
		$name=$this->getActionName();
		$model = M ( $name );
		$id = $_REQUEST [$model->getPk ()];
		$vo = $model->find ( $id );
		$this->assign ( 'vo', $vo );
		$this->display ();
	}

	function update() {
		//B('FilterString');
		//值为数组的 转换成逗号分隔的字符串
		foreach($_POST as $key=>$val){
			if(is_array($val)){
				$_POST[$key] = implode(',', $val);
			}
		}		
		if($_POST['fieldarrs']){ //多选型字段, 模板中<input type="hidden" name="fieldarrs" value="attrtt,attrtj" />
			foreach(explode(',',$_POST['fieldarrs']) as $key){
				if(!isset($_POST[$key])) $_POST[$key] = '';
			}
		}
		$name=$this->getActionName();
		$model = D ( $name );
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		
		$this->do_upload($model); //上传字段处理
		
		// 更新数据
		if ($model->save () !== false) {
			//成功提示
			import ( "ORG.Util.Cookie" );
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('编辑成功！');
		} else {
			//错误提示
			$this->error ('编辑失败！');
		}
	}

	//删除到回收站
	public function delete() {
		$name=$this->getActionName();
		$model = M ($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = $_REQUEST [$pk];			
			$condition = array ($pk => array ('in', explode(',',$id) ) );
			$list=$model->where ( $condition )->setField ( $name=='Category' ? 'classstatus' : 'status', - 1 );
			if ($list!==false) {
				$this->success ('删除成功！' );
			} else {
				$this->error ('删除失败！');
			}			
		}
	}
	
	//彻底删除
	public function foreverdelete() {
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = $_REQUEST [$pk];			
			$condition = array ($pk => array ('in', explode(',',$id) ) );
			if (false !== $model->where ( $condition )->delete ()) {
				$this->success ('删除成功！');
			} else {
				$this->error ('删除失败！');
			}			
		}
		$this->forward ();
	}

	//清空回收站
	public function clear() {
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			if (false !== $model->where ( ($name=='Category' ? 'classstatus' : 'status').'=-1' )->delete ()) {
				$this->assign ( "jumpUrl", $this->getReturnUrl () );
				$this->success ( L ( '_DELETE_SUCCESS_' ) );
			} else {
				$this->error ( L ( '_DELETE_FAIL_' ) );
			}
		}
		$this->forward ();
	}
	
	//默认禁用操作
	public function forbid() {
		$name=$this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_REQUEST [$pk];
		$condition = array ($pk => array ('in', explode(',',$id) ) );
		if ($model->forbid ( $condition, $name=='Category' ? 'classstatus' : 'status' )!==false) {
			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success ( '状态禁用成功' );
		} else {
			$this->error  (  '状态禁用失败！' );
		}
	}
	
	//批准状态
	public function checkPass() {
		$name=$this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_REQUEST [$pk];
		$condition = array ($pk => array ('in', explode(',',$id) ) );
		if (false !== $model->checkPass( $condition, $name=='Category' ? 'classstatus' : 'status' )) {
			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success ( '状态批准成功！' );
		} else {
			$this->error  (  '状态批准失败！' );
		}
	}

	//从回收站还原
	public function recycle() {
		$name=$this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_REQUEST [$pk];
		$condition = array ($pk => array ('in', explode(',',$id) ) );
		if (false !== $model->recycle ( $condition, $name=='Category' ? 'classstatus' : 'status' )) {
			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success ( '状态还原成功！' );

		} else {
			$this->error   ( '状态还原失败！' );
		}
	}
	
	//查看回收站
	public function recycleBin() {
		$name=$this->getActionName();
		$map = $this->_search ();
		$map [$name=='Category' ? 'classstatus' : 'status'] = - 1;
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
	}

	/**
     +----------------------------------------------------------
	 * 默认审核操作
	 *
     +----------------------------------------------------------
	 * @access public
     +----------------------------------------------------------
	 * @return string
     +----------------------------------------------------------
	 * @throws FcsException
     +----------------------------------------------------------
	 */
	function resume() {
		//审核指定记录
		$name=$this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_REQUEST [$pk];
		$condition = array ($pk => array ('in', explode(',',$id) ) );
		if (false !== $model->resume ( $condition, $name=='Category' ? 'classstatus' : 'status' )) {
			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success ( '状态审核成功！' );
		} else {
			$this->error ( '状态审核失败！' );
		}
	}
	
	/**
	 * 后台URL重写值为空时 自动设置为模块名加id值0,1
	 * @return unknown_type
	 */
	public function auto_URLRewrite($id=null){
		if(ACTION_NAME=='update') return;
			
		if(C('ADMIN_AUTO_REWRITE')){
			$pk = M(MODULE_NAME)->getPk();
			if(!$id) $id = is_numeric($_POST[$pk]) ? $_POST[$pk] : M(MODULE_NAME)->getLastInsID();
			if(isset($_POST['rewrite']) && $_POST['rewrite']==''){
				$_POST['rewrite'] = strtolower(MODULE_NAME) . $id;				
				$data['rewrite']=$_POST['rewrite'];
				$data['url']=strtolower(MODULE_NAME).'/view/id/'.$id;
				if(D('Router')->add($data)){
					M(MODULE_NAME)->where(array($pk=>$id))->setField('rewrite', $_POST['rewrite']);
				}
			}
			if(isset($_POST['classrewrite']) && $_POST['classrewrite']==''){
				//$_POST['classrewrite'] = (is_numeric($_POST['classid']) ? $_POST['classid'] : M(MODULE_NAME)->getLastInsID());
				//全拼模式
				load('pinyin');
				$pinyin = implode('',pinyin($_POST['classtitle']));
				//排除自己，否则再次设置的时候出错
				$i = M('router')->where(array('rewrite'=>$pinyin))->count(); 
				$m = M('router')->where(array('url'=>strtolower($_POST['classmodule']).'/index/id/'.$_POST['classid']))->count(); 						
				$n += intval($i-$m);						
				$pinyin .= $n>0 ? '_'.($n+1) : '';
				$_POST['classrewrite'] = $pinyin;
				M(MODULE_NAME)->where(array($pk=>$id))->setField('classrewrite', $_POST['classrewrite']);
			}
		} 
	}
	
	/**
	 * 删除相应模型所有上传类字段，上传的文件 及其缩略相关文件 并返回记录ids
	 * @param $moduleName
	 * @return ids
	 */
	public function _beforDelFiles($module){
		$pk = M($module)->getPk();
		$pkValue = $_REQUEST[$pk];
		if( is_array($pkValue) || preg_match('/^\w+(,\w+)*$/',$pkValue) ){
			$pkValue = is_array($pkValue) ? $pkValue : explode(',',$pkValue);
			M('category_map')->where( array('mapclassmodule'=>$module, 'mapinfoid'=>array('in', $pkValue)) )->delete();
			//-----------------------------------检测上传字段，取得上传字段的值，删除相应文件，同时要删除此文件的缩略图，复件等
			$needfields = array();
			$fields = M($module)->getDbFields();
			if(in_array('img', $fields)) $needfields[] = 'img';
			if(in_array('file', $fields)) $needfields[] = 'file';
			//取得其他上传字段
			$fieldsmap = M('model_fieldnotes')->where('ename="'.$module.'"')->getField('fieldsmap');
			if( $fieldsmap && is_array($fieldsmap = unserialize($fieldsmap)) ){
				foreach($fields as $val){
					//3:上传图片,4:上传文件
					if( in_array($fieldsmap[$val]['inputType'], array(3,4)) ) $needfields[] = $val;
				}
			}			
			if($needfields){ //有上传的字段则可以检测文件并删除
				$list = M($module)->where( array($pk=>array('in', $pkValue)) )->field($needfields)->select();
				if($list){
					import('ORG.Io.Dir');
					$rootpath = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.strtolower($module).'/';
					$mfiles = Dir::getList($rootpath);
					$strmfiles = implode(':', $mfiles).':'; //为了性能把所有文件打包到字符串 :号不能命名在文件名里
					foreach($list as $rs){
						foreach($needfields as $field){
							if( preg_match('/^(https?|ftp):\/\//i', $rs[$field]) ) continue; //排除外部链接
							//取名称和后缀名
//							$tmp = explode('.', $rs[$field]);
							$tmp = explode('/', $rs[$field]);//只取最后一个文件名***							
							$tmp = explode('.', $tmp[count($tmp)-1]);
							
							$dbfileExt = $tmp[count($tmp)-1];//只取后缀名
							unset($tmp[count($tmp)-1]);
							$dbfilename = implode('.', $tmp);
							//会删除相应的缩略图等文件
							if( preg_match_all('/[^:]*?'.preg_quote($dbfilename).'[^:]*?\.'.$dbfileExt.':/i', $strmfiles, $arrlikes) ){	
								foreach($arrlikes[0] as $perlike){
									$perlike = trim($perlike,':');
									if(is_file($rootpath.$perlike)) @unlink($rootpath.$perlike);
									if(is_file($perlike)) @unlink($perlike);
								}
							}
						}
					}			
				}	
			}
			//-----------------------------------
			return $pkValue;
		}
		return 0;
	}
	
	//删除图片\视频\音乐等上传文件
	public function delfile(){
		$action = $this->getActionName();
		$pkname = M($action)->getPk();
		$field = ($_GET['field']!='') ? $_GET['field'] : 'img';
		if (method_exists ( $this, '_delfile' )) {
			$field = $this->_delfile();
		}	
		if(is_numeric($_GET[$pkname])){
			$id = $_GET[$pkname];	
			$src = M($action)->where(array($pkname=>$id))->getField($field);
			M($action)->where(array($pkname=>$id))->setField($field,'');
			if( !preg_match('/^(https?|ftp):\/\//i', $src) ){//排除外部链接
				import('ORG.Io.Dir');
				$rootpath = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.strtolower($action).'/';
				$mfiles = Dir::getList($rootpath);
				$strmfiles = implode(':', $mfiles).':'; //为了性能把所有文件打包到字符串 :号不能命名在文件名里
				//取名称和后缀名
//				$tmp = explode('.', $src);
				$tmp = explode('/', $src);//只取最后一个文件名***							
				$tmp = explode('.', $tmp[count($tmp)-1]);
				
				$dbfileExt = $tmp[count($tmp)-1];//只取后缀名				
				unset($tmp[count($tmp)-1]);
				$dbfilename = implode('.', $tmp);
				//会删除相应的缩略图等文件
				if( preg_match_all('/[^:]*?'.preg_quote($dbfilename).'[^:]*?\.'.$dbfileExt.':/i', $strmfiles, $arrlikes) ){	
					foreach($arrlikes[0] as $perlike){
						$perlike = trim($perlike,':');
						if(is_file($rootpath.$perlike)) @unlink($rootpath.$perlike);
						if(is_file($perlike)) @unlink($perlike);
					}
				}					
			}
		}
		$this->success('删除成功');
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
    public function setSort(){
    	$action = $this->getActionName();
		$pkname = M($action)->getPk();
		is_numeric($_GET['id']) or $this->error($pkname.'参数错误');
		$field = ($action=='Category') ? 'classsort' : ($_GET['field']!='' ? $_GET['field'] : 'sort');
		M($action)->where(array($pkname=>$_GET['id']))->save( array( $field=> is_numeric($_POST['value']) ? $_POST['value'] : 0 )  );		
		$this->success( M($action)->getError() ? M($action)->getError() : ucfirst($action) );
    }	
    
    /**
     * 内容中的文件（包括图片WORD等）本地化
     * @param  string $content
     * @return array($content,$firstImg)
     */
    public function remoteToLocal($content){
		$firstImg = '';
		if( !empty($content) && preg_match_all('/(https?:\/\/[^\'"<>\(\);\s]+?)([^\/\s\'\"=<>\(\);:\*\|]+?)\.('.str_replace(array(',',"\s"),'|',C('REMOTE_ALLOWEXTS')).')/i',$content,$remote_files) ){
			$basedir = 'remote_'.date('ym').'/';	
			$savebase = __ROOT__.'/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.strtolower(MODULE_NAME).'/'.$basedir;
			is_dir($_SERVER['DOCUMENT_ROOT'].$savebase) or mkdir($_SERVER['DOCUMENT_ROOT'].$savebase, 0777);
			foreach( $remote_files[0] as $k=>$files ){
				$localFileName = $remote_files[2][$k].'.'.$remote_files[3][$k]; //uniqid()
				if( is_file($_SERVER['DOCUMENT_ROOT'].$savebase.$localFileName) ){//存在则跳过，只做替换输出
					if( preg_match('/^jpeg|jpg|png|gif|bmp$/i', $remote_files[3][$k]) ) $firstImg = $basedir.$localFileName;
	        		$content = str_replace($files, $savebase.$localFileName, $content);
	        		continue; 
				} 
				
				$objfile=false; $downN=0;
				while( false===$objfile && $downN<10 ){ //有时候由于网络原因1次下载不下来，自动重试10次下载
					$objfile = @file_get_contents($files);
					++$downN;
				}	
				if($objfile!==false){					
					if( preg_match('/^jpeg|jpg|png|gif|bmp$/i', $remote_files[3][$k]) ) $firstImg = $basedir.$localFileName;
					$fp = fopen($_SERVER['DOCUMENT_ROOT'].$savebase.$localFileName, 'w');
					if(fwrite($fp,$objfile)){
						$content = str_replace($files, $savebase.$localFileName, $content);
					}
					@fclose($fp);
				}
			}
		}
		return array($content,$firstImg);
    }
    
	/**
	 * 文章附属到其他模块则需要另外存表 category_map  还要处理删除
	 * @param $id
	 * @param $tid
	 * @return unknown_type
	 */
    protected function to_category_map($id=null,$tid=null,$old_tid=null){
    	$this->auto_URLRewrite();//后台URL重写值为空时 自动设置为模块名加id值0,1
    	
		if(is_null($id))$id = is_numeric($_POST['id']) ? $_POST['id'] : M(MODULE_NAME)->getLastInsID();			
		if($id && $_POST['tid']){
			$oldtids = explode(',',$old_tid ? $old_tid : $_POST['old_tid']);
			$newtids = $tids = explode(',',$tid ? $tid : $_POST['tid']); 
			$list = M('Category')->field('classid,classmodule')->where(array('classid'=>array('in',$tids)))->select();
			foreach($list as $rs){
				if($rs['classmodule']!=MODULE_NAME){
					$data = array();
					$data['mapissource']=0;
					$data['mapinfoid']=intval($id);
					$data['mapclassid']=$rs['classid'];
					$data['mapclassmodule']=$rs['classmodule'];
					if( !M('category_map')->where($data)->count() ){
						M('category_map')->add($data);						
					}
					unset($newtids[array_search($rs['classid'], $newtids)]);
				}
				if($oldtids)unset($oldtids[array_search($rs['classid'], $oldtids)]);
			}	
			if($newtids && $newtids!=$tids){
				foreach($newtids as $k=>$tid){
					$data = array();
					$data['mapissource']=($k==0) ? 1 : 0;
					$data['mapinfoid']=intval($id);
					$data['mapclassid']=$tid;
					$data['mapclassmodule']=MODULE_NAME;
					if( !M('category_map')->where($data)->count() ){
						M('category_map')->add($data);	
					}
				}
			}
			//删除category_map里多余的
			if($oldtids)M('category_map')->where(array('mapinfoid'=>intval($id), 'mapclassid'=>array('in',$oldtids)))->delete();			
		}
    }
    
	
	//复制信息
	public function copy(){ 
		if( $_SERVER['REQUEST_METHOD']=='POST' ){
			if(!preg_match('/^\d+(,\d+)*$/',$_POST['id'])) $this->error('必须至少选择一项信息。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			if(!preg_match('/^\d+(,\d+)*$/',$_POST['tid']) || $_POST['tid']==0) $this->error('必须选择一个目标分类。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$pk = M(MODULE_NAME)->getPk();
			$fields = M(MODULE_NAME)->getDbFields();
			$fields = trim(',`'.implode('`,`',$fields).'`,', ',');
			$fields = str_replace("`$pk`", 0, $fields);
			$fields = str_replace("`rewrite`", "''", $fields); //去掉rewrite属性
			foreach( explode(',',$_POST['tid']) as $tid ){
				$new_fields = $tid>0 ? str_replace('`tid`', $tid, $fields) : $fields;
				$sql = 'insert into `'.C('DB_PREFIX').MODULE_NAME.'` select '.$new_fields.' from `'.C('DB_PREFIX').MODULE_NAME.'` where '.$pk.' in ('.$_POST['id'].')';
				M(MODULE_NAME)->query($sql);
			}
			$this->success('操作成功！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
		}else{
			if(!preg_match('/^\d+(,\d+)*$/',$_GET['id'])) $this->error('必须至少选择一项信息！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$this->assign('id', $_GET['id']);
			$this->display();
		}
	}
	
	//移动信息
	public function move(){
		if( $_SERVER['REQUEST_METHOD']=='POST' ){
			if(!preg_match('/^\d+(,\d+)*$/',$_POST['id'])) $this->error('必须至少选择一项信息。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			if(!is_numeric($_POST['tid'])) $this->error('必须选择一个目标分类。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$pk = M(MODULE_NAME)->getPk();			
			$sql = 'update `'.C('DB_PREFIX').MODULE_NAME.'` set `tid`=\''.$_POST['tid'].'\' where '.$pk.' in ('.$_POST['id'].')';			
			M(MODULE_NAME)->query($sql);			
			$this->success('操作成功！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
		}else{
			if(!preg_match('/^\d+(,\d+)*$/',$_GET['id'])) $this->error('必须至少选择一项信息！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$this->assign('id', $_GET['id']);
			$this->display();
		}
	}
	
	//附属栏目
	public function tidadd(){
		if( $_SERVER['REQUEST_METHOD']=='POST' ){
			if(!preg_match('/^\d+(,\d+)*$/',$_POST['id'])) $this->error('必须至少选择一项信息。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			if(!preg_match('/^\d+(,\d+)*$/',$_POST['tid'])) $this->error('必须至少选择一项分类。<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$pk = M(MODULE_NAME)->getPk();
			$sql = 'update `'.C('DB_PREFIX').MODULE_NAME.'` set `tid`=concat(`tid`, \','.$_POST['tid'].'\') where '.$pk.' in ('.$_POST['id'].')';
			M(MODULE_NAME)->query($sql);
			
			//选择了其他模块的栏目的处理
			$_POST['tid'] = M(MODULE_NAME)->where($pk.' in ('.$_POST['id'].')')->limit(1)->getField('tid');
			foreach( explode(',',$_POST['id']) as $id )
				$this->to_category_map($id,$_POST['tid']);
			
			$this->success('操作成功！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
		}else{
			if(!preg_match('/^\d+(,\d+)*$/',$_GET['id'])) $this->error('必须至少选择一项信息！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$this->assign('id', $_GET['id']);
			$this->display();
		}
	}
	
	//设置属性、替换内容等等
	public function attr(){
		if( $_SERVER['REQUEST_METHOD']=='POST' ){	
//			dump($_POST);exit;		
			$fields = array(); $pk = M(MODULE_NAME)->getPk();
			if($_POST['template']!=''){
				$sql = 'update `'.C('DB_PREFIX').MODULE_NAME.'` set `template`=\''.($_POST['template']=='NULL' ? '' : $_POST['template']).'\' where '.$pk.' in ('.$_POST['id'].')';
				M(MODULE_NAME)->query($sql);
			}							
			if(isset($_POST['tcolor']) && $_POST['tcolor']!='NULL'){
				M(MODULE_NAME)->where($pk.' in ('.$_POST['id'].')')->setField('tcolor',$_POST['tcolor']);
			}
			if(isset($_POST['isb']) && $_POST['isb']!='NULL'){
				M(MODULE_NAME)->where($pk.' in ('.$_POST['id'].')')->setField('isb',intval($_POST['isb']));
			}
			if(isset($_POST['isi']) && $_POST['isi']!='NULL'){
				M(MODULE_NAME)->where($pk.' in ('.$_POST['id'].')')->setField('isi',intval($_POST['isi']));
			}
			
			if($_POST['attrttSet']!='NULL'){
				$fields[] = 'attrtt';
			}
			if($_POST['attrtjSet']!='NULL'){
				$fields[] = 'attrtj';
			}
			if($_POST['stitle']!='' || $_POST['rtitle']!=''){
				$fields[] = 'title';
			}
			if($_POST['skeywords']!='' || $_POST['rkeywords']!=''){
				$fields[] = 'keywords';
			}
			if($_POST['sdescription']!='' || $_POST['rdescription']!=''){
				$fields[] = 'description';
			}
			if($_POST['scontent']!='' || $_POST['rcontent']!=''){
				$fields[] = 'content';
			}
			
			if($fields){
				$list = M(MODULE_NAME)->field($pk.','.implode(',',$fields))->where($pk.' in ('.$_POST['id'].')')->select();				
				foreach($list as $rs){
					if(array_key_exists('attrtt', $rs)){
						if($_POST['attrttSet']==0){//去掉属性
							if(!$_POST['attrtt']){
								$rs['attrtt'] = '';
							}else{
								$tmp = explode(',',trim($rs['attrtt'],','));
								foreach($_POST['attrtt'] as $v){//存在的属性则去掉
									unset( $tmp[array_search($v, $tmp)] );
								}
								$rs['attrtt'] = implode(',',$tmp);
							}
						}elseif($_POST['attrttSet']==1){//附加属性
							$tmp = explode(',',trim($rs['attrtt'],','));
							foreach($_POST['attrtt'] as $v){//不存在的属性则附加
								if( !in_array($v, $tmp) ) $tmp[] = $v;
							}
							$rs['attrtt'] = implode(',',$tmp);
						}elseif($_POST['attrttSet']==2){//覆盖属性
							$rs['attrtt'] = implode(',',$_POST['attrtt']);
						}						
					}
					if(array_key_exists('attrtj', $rs)){
						if($_POST['attrtjSet']==0){//去掉属性
							if(!$_POST['attrtj']){
								$rs['attrtj'] = '';
							}else{
								$tmp = explode(',',trim($rs['attrtj'],','));
								foreach($_POST['attrtj'] as $v){//存在的属性则去掉
									unset( $tmp[array_search($v, $tmp)] );
								}
								$rs['attrtj'] = implode(',',$tmp);
							}
						}elseif($_POST['attrtjSet']==1){//附加属性
							$tmp = explode(',',trim($rs['attrtj'],','));
							foreach($_POST['attrtj'] as $v){//不存在的属性则附加
								if( !in_array($v, $tmp) ) $tmp[] = $v;
							}
							$rs['attrtj'] = implode(',',$tmp);
						}elseif($_POST['attrtjSet']==2){//覆盖属性
							$rs['attrtj'] = implode(',',$_POST['attrtj']);
						}						
					}
					if(array_key_exists('title', $rs)){
						if($_POST['stitle']==''){
							$rs['title'] = $_POST['rtitle'];
						}else{
							$tmp = @preg_replace("/{$_POST['stitle']}/i", $_POST['rtitle'], $rs['title']);
							if(!empty($tmp)){
								$rs['title'] = $tmp;
							}else{
								$rs['title'] = str_ireplace($_POST['stitle'], $_POST['rtitle'], $rs['title']);
							}
						}						
					}
					if(array_key_exists('keywords', $rs)){						
						if($_POST['skeywords']==''){
							$rs['keywords'] = $_POST['rkeywords'];
						}else{
							$tmp = @preg_replace("/{$_POST['skeywords']}/i", $_POST['rkeywords'], $rs['keywords']);
							if(!empty($tmp)){
								$rs['keywords'] = $tmp;
							}else{
								$rs['keywords'] = str_ireplace($_POST['skeywords'], $_POST['rkeywords'], $rs['keywords']);
							}
						}					
					}
					if(array_key_exists('description', $rs)){						
						if($_POST['sdescription']==''){
							$rs['description'] = $_POST['rdescription'];
						}else{
							$tmp = @preg_replace("/{$_POST['sdescription']}/i", $_POST['rdescription'], $rs['description']);
							if(!empty($tmp)){
								$rs['description'] = $tmp;
							}else{
								$rs['description'] = str_ireplace($_POST['sdescription'], $_POST['rdescription'], $rs['description']);
							}
						}						
					}
					if(array_key_exists('content', $rs)){							
						if($_POST['scontent']==''){
							$rs['content'] = $_POST['rcontent'];
						}else{
							$tmp = @preg_replace("/{$_POST['scontent']}/i", $_POST['rcontent'], $rs['content']);
							if(!empty($tmp)){
								$rs['content'] = $tmp;
							}else{
								$rs['content'] = str_ireplace($_POST['scontent'], $_POST['rcontent'], $rs['content']);
							}
						}					
					}
					M(MODULE_NAME)->where($pk.'='.$rs[$pk])->setField($rs);
				}
			}
			
			$this->success('操作成功！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
		}else{
			if(!preg_match('/^\d+(,\d+)*$/',$_GET['id'])) $this->error('必须至少选择一项信息！<script>if($.pdialog) $.pdialog.closeCurrent();</script>');
			$this->assign('id', $_GET['id']);
			$this->display();
		}
	}    
	
	//自动获取关键字
	public function auto_getkey(){
		echo autoget_keywords($_POST['title'],$_POST['content']);
	}
	
	//自动获取摘要
	public function auto_getdes(){
		echo autoget_description($_POST['content']);
	}
	
	//上传字段的处理
	function do_upload(&$model){
		//合并为多文件上传
		$upload_arr = array(); $pkname = NULL;	 			
		foreach($_FILES as $key=>$val){
			if($val['name']){
				$upload_arr[$key]=$val['name'];
			}
		}
		if(!empty($upload_arr)){
			import("ORG.Net.UploadFile");
			$upload = new UploadFile();
			$upload->maxSize  = 1048576 * 1024; //1G
			$upload->allowExts  = array('jpg','jpeg','gif','png', 'swf','flv','fla','avi','wmv','wma','rm','mov','mpg','rmvb','3gp','mp4');
			$upload->savePath =  './Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.strtolower(MODULE_NAME).'/';
			$upload->saveRule = 'uniqid';
			$upload->thumb = true;
			$upload->thumbMaxWidth = 100;
			$upload->thumbMaxHeight = 100;
			$upload->uploadReplace = false;
			$upload->thumbPrefix = '100_100_';
			if(!$upload->upload()) {
				$this->error($upload->getErrorMsg());
			}else{
				$files = $upload->getUploadFileInfo();
				$count = 0;
				foreach($upload_arr as $k=>$v){
					$model->$k = ltrim($upload->savePath,'.').$files[$count]['savename'];
					$count++;
				}
			}
		}
		foreach($_FILES as $k=>$v){//上传优先，兼容外部文件地址
			if(empty($v['name'])){
				$model->$k = ($_POST[$k.'url']!='') ? $_POST[$k.'url'] : $model->$k;
			}
			if( false!==stripos(ACTION_NAME,'update') ){
				if(!$pkname) $pkname = $model->getPk();
				if( $model->$k!='' && !empty($_POST[$pkname]) ){ //删除旧文件
					$oldfile = $model->where( array($pkname, $_POST[$pkname]) )->getField($k);
					if($oldfile && $model->$k!=$oldfile){
						$file = $_SERVER['DOCUMENT_ROOT'].chimg($oldfile,MODULE_NAME,100,100);
						if(is_file($file)) unlink($file);
						$file = $_SERVER['DOCUMENT_ROOT'].chimg($oldfile,MODULE_NAME);
						if(is_file($file)) unlink($file);
					}
				}
			}
		}
	}
	
	//xheditor上传文件保存
	public function upload() {
		header('Content-Type: text/html; charset=UTF-8');
		$inputname='filedata';//表单文件域name
		$attachdir='./Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.strtolower(MODULE_NAME);//上传文件保存路径，结尾不要带/
		$dirtype=1;//1:按天存入目录 2:按月存入目录 3:按扩展名存目录  建议使用按天存
		$maxattachsize=1048576 * 1024;//最大上传大小，默认是1G
		$upext='zip,rar,txt,doc,docx,ppt,xls,xlsx,csv,jpg,jpeg,gif,png,bmp,swf,flv,fla,avi,wmv,wma,rm,mov,mpg,rmvb,3gp,mp4,mp3';//上传扩展名
		$msgtype=2;//返回上传参数的格式：1，只返回url，2，返回参数数组
		$immediate=isset($_GET['immediate'])?$_GET['immediate']:0;//立即上传模式
		ini_set('date.timezone','Asia/Shanghai');//时区
			
		if(isset($_SERVER['HTTP_CONTENT_DISPOSITION']))//HTML5上传
		{
			if(preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i',$_SERVER['HTTP_CONTENT_DISPOSITION'],$info))
			{
				$temp_name=ini_get("upload_tmp_dir").'/'.date("YmdHis").mt_rand(1000,9999).'.tmp';
				file_put_contents($temp_name,file_get_contents("php://input"));
				$size=filesize(ini_get("upload_tmp_dir"));
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
					$target=__ROOT__.'/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/article/'.$attach_subdir.'/'.$filename;
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
}