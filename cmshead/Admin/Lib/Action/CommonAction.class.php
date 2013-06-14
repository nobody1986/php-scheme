<?php
//公共模块
class CommonAction extends Action {
	protected $sortBy = ''; //单独定义排序字段
	
	function _initialize() {
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
	}
	
	public function index() {
		//列表过滤器，生成查询Map对象
		$map = $this->_search ();		
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map, $this->sortBy );
		}		
		$this->display ();
		return;
	}
	/**
     +----------------------------------------------------------
	 * 取得操作成功后要返回的URL地址
	 * 默认返回当前模块的默认操作
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
		return $map;
	}

	/**
     +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
     +----------------------------------------------------------
	 * @access protected
     +----------------------------------------------------------
	 * @param Model $model 数据对象
	 * @param HashMap $map 过滤条件
	 * @param string $sortBy 排序
	 * @param boolean $asc 是否正序
     +----------------------------------------------------------
	 * @return void
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	protected function _list($model, $map, $sortBy = '') {
		//排序字段 默认为主键名
		$sort = '';
		if (isset ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
			$order .= $sort = $_REQUEST ['_sort'] ? ' asc' : ' desc';
		}elseif(empty ( $sortBy )) $order .= ' desc';
		//取得满足条件的记录数
		$count = $model->where ( $map )->count ( '*' );
		if ($count > 0) {
			import ( "ORG.Util.Page" );
			//创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = C('PAGE_LISTROWS');
			}
			$p = new Page ( $count, $listRows );
			//分页查询数据	
			$voList = $model->where($map)->order($order)->limit($p->firstRow . ',' . $p->listRows)->select();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}			
			//分页显示
			$page = $p->show ();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			$order = explode(' ',$order); $order = $order[0];
			//模板赋值显示
			$this->assign ( 'list', $voList );
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( "page", $page );
		}
		$this->assign ( 'totalCount', $count );
		$this->assign ( 'numPerPage', $listRows );
		$this->assign ( 'currentPage', is_numeric($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);
		import ( "ORG.Util.Cookie" );	
		Cookie::set ( '_currentUrl_', __SELF__ );
		return;
	}

	function insert() {
		//B('FilterString');
		$name=$this->getActionName();
		$model = D ($name);
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		//保存当前数据对象
		$list=$model->add ();
		if ($list!==false) { //保存成功
			import ( "ORG.Util.Cookie" );
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('新增成功!');
		} else {
			//失败提示
			$this->error ('新增失败!');
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
		$vo = $model->getById ( $id );
		$this->assign ( 'vo', $vo );
		$this->display ();
	}

	function update() {
		//B('FilterString');
		$name=$this->getActionName();
		$model = D ( $name );
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		// 更新数据
		if (false !== $model->save ()) {
			//成功提示
			import ( "ORG.Util.Cookie" );
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('编辑成功!');
		} else {
			//错误提示
			$this->error ('编辑失败!');
		}
	}

	//删除到回收站
	public function delete() {
		$name=$this->getActionName();
		$model = M ($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = $_REQUEST [$pk];
			if ( preg_match('/^\d+(,\d+)*$/',$id) ) {
				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
				$list=$model->where ( $condition )->setField ( 'status', - 1 );
				if ($list!==false) {
					$this->success ('删除成功！' );
				} else {
					$this->error ('删除失败！');
				}
			} else {
				$this->error ( '非法操作' );
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
			if ( preg_match('/^\d+(,\d+)*$/',$id) ) {
				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
				if (false !== $model->where ( $condition )->delete ()) {
					$this->success ('删除成功！');
				} else {
					$this->error ('删除失败！');
				}
			} else {
				$this->error ( '非法操作' );
			}
		}
		$this->forward ();
	}

	//清空回收站
	public function clear() {
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			if (false !== $model->where ( 'status=-1' )->delete ()) {
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
		$condition = array ($pk => array ('in', $id ) );
		if ($model->forbid ( $condition )!==false) {
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
		$id = $_GET [$pk];
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->checkPass( $condition )) {
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
		$id = $_GET [$pk];
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->recycle ( $condition )) {
			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success ( '状态还原成功！' );

		} else {
			$this->error   ( '状态还原失败！' );
		}
	}
	
	//查看回收站
	public function recycleBin() {
		$map = $this->_search ();
		$map ['status'] = - 1;
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		$this->display ();
	}

	/**
     +----------------------------------------------------------
	 * 默认恢复操作
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
		//恢复指定记录
		$name=$this->getActionName();
		$model = D ($name);
		$pk = $model->getPk ();
		$id = $_GET [$pk];
		$condition = array ($pk => array ('in', $id ) );
		if (false !== $model->resume ( $condition )) {
			$this->assign ( "jumpUrl", $this->getReturnUrl () );
			$this->success ( '状态恢复成功！' );
		} else {
			$this->error ( '状态恢复失败！' );
		}
	}

	//批量修改排序
	function saveSort() {
		$seqNoList = $_POST ['seqNoList'];
		if (! empty ( $seqNoList )) {
			//更新数据对象
		$name=$this->getActionName();
		$model = D ($name);
			$col = explode ( ',', $seqNoList );
			//启动事务
			$model->startTrans ();
			foreach ( $col as $val ) {
				$val = explode ( ':', $val );
				$model->id = $val [0];
				$model->sort = $val [1];
				$result = $model->save ();
				if (! $result) {
					break;
				}
			}
			//提交事务
			$model->commit ();
			if ($result!==false) {
				//采用普通方式跳转刷新页面
				$this->success ( '更新成功' );
			} else {
				$this->error ( $model->getError () );
			}
		}
	}
	
	
	/**
	 * 选择模板弹出层 路径包含{tplroot}
	 * @return unknown_type
	 */
	public function seltpl(){
		$tplname = isset($_GET['tplname']) ? $_GET['tplname'] : 'template'; 
		$tplpath = '';
		$filtext = 'shtml|html|htm|shtm|tpl|php|asp|jsp|txt';
		$rootpath = __ROOT__.'/Home/Tpl';
		$rootdir = $_SERVER['DOCUMENT_ROOT'].$rootpath;
		$allpath = get_allfiles($rootdir, $filtext, true);
		echo '<ul class="tree treeFolder">
				<li><a href="javascript:;">'.$rootpath.'</a><ul>';
			foreach($allpath as $k=>$item){		
				$tplpath = iconv('gbk','utf-8',substr($item, strlen($rootdir)+1));	
							
				//取 /Article/add.html 中的/Article
				if(preg_match('/\.('.$filtext.')$/i', $item)){//文件则多一级/
					$tmp = substr($item, 0, strrpos($item, '/'));
					$mypathname = substr($tmp,strrpos($tmp, '/')); //当前pathname
				}else{
					$mypathname = substr($item,strrpos($item, '/')); //当前pathname					
				}
				if(preg_match('/\.('.$filtext.')$/i', $allpath[$k+1])){//文件则多一级/
					$tmp = substr($allpath[$k+1], 0, strrpos($allpath[$k+1], '/'));		
					$nextpathname = substr($tmp,strrpos($tmp, '/')); //下一个pathname
				}else{
					$nextpathname = substr($allpath[$k+1],strrpos($allpath[$k+1], '/')); //下一个pathname
				}
				
				if(preg_match('/\.('.$filtext.')$/i', $tplpath)){//文件
					echo '<li><a href="javascript:;" onclick="$(\'#'.$tplname.'\').val(\'{tplroot}'.$tplpath.'\');$.pdialog.closeCurrent();">'.$tplpath.'</a></li>';
					if($mypathname!=$nextpathname){
						echo '</ul>
							</li>';
					}
				}
				else{//目录					
					echo '<li><a href="javascript:;">'.$tplpath.'</a>';
					if($mypathname==$nextpathname){
						echo '<ul>';
					}
				}
			}
		echo '	</ul></li>
			</ul>';
	}
	/**
	 * 删除相应模块上传的文件 并返回记录ids
	 * @param $moduleName
	 * @return ids
	 */
	public function _beforDelFiles($module){
		if( is_array($_REQUEST['id']) || preg_match('/^\d+(,\d+)*$/',$_REQUEST['id']) ){
			$modulePath = strtolower($module);
			$ids = is_array($_REQUEST['id']) ? explode(',',$_REQUEST['id']) : $_REQUEST['id'];			
			$imgs = D(ucfirst($module))->where('id in ('.$ids.')')->field('img')->select();			
			foreach($imgs as $img){
				$src = './Public/Upload/'.$modulePath.'/'.$img['img'];
				is_file($src) and unlink($src);
				$src = './Public/Upload/'.$modulePath.'/thumb_'.$img['img'];
				is_file($src) and unlink($src);
			}
			return $ids;
		}
		return 0;
	}
}