<?php
/**
 * 此文件是CMSHead模板中使用的函数库 awen add 2012-11-02
 */
/**
 * volist name的函数
 * 用法：<volist name=":ch1('news','223,224,225,21', '4,3', 'cacheName:60', 'debug')" id="vo">
 * @param $modelName 强制指定模型名称 模型名称可映射CMSHEAD_KEY_ROUTER 默认Article
 * @param $SqlOrCids Sql语句或栏目IDs
 * @param $limit 条数 
 * @param $cache 缓存name:time or time
 * @return array 二维
 */
function ch1($modelName='Article', $SqlOrCids='', $limit='', $cache='', $debug=false){	
	$args = func_get_args();
	foreach($args as $k=>$arg){
		if($arg=='debug'){
			$debug=true;
			unset($args[$k]);
			break;
		}
	}
	if( $args[0]=='' || preg_match('/^(select.+from.*)|(\d+(,\d+)*)$/i', $args[0]) ){ //未强制指定模型名称		
		$modelName = 'Article';
		$SqlOrCids = $args[0];
		$limit = count($args)>1 ? $args[1] : '';
		$cache = count($args)>2 ? $args[2] : '';		
	}else{
		$tmp = is_array(C('CMSHEAD_KEY_ROUTER')) ? C('CMSHEAD_KEY_ROUTER') : array();
		if( array_key_exists($modelName, $tmp) ){
			$modelName = $tmp[$modelName];
		}
		$modelName = ucfirst(strtolower($modelName));
	}
	//若有缓存数据，直接返回
	$objcache = array();
	if($cache){
		$arr = explode(':',$cache);
		$objcache[0] = is_numeric($arr[1]) ?  $arr[0] : (is_numeric($arr[0]) ? md5(serialize($args)) : $arr[0]); //名称
		$objcache[1] = NULL; //data 后面设置
		$objcache[2] = is_numeric($arr[1]) ? $arr[1] : (is_numeric($arr[0]) ? $arr[0] : 60); //时间
		if( $objcache[0] && $dataCache = S($objcache[0]) ){
			return $dataCache;
		}
	}
		
	$where = array();
	if(preg_match('/^select.+from[\s]+([^\s]+).*$/i',$SqlOrCids,$arr)){ //SQL语句
		$inputType = 'sql';
		$modelName = ucfirst(strtolower(str_replace(C('DB_PREFIX'),'',$arr[1])));
		$list = M('Node')->query($SqlOrCids);
	}elseif(preg_match('/^\d+(,\d+)*$/',$SqlOrCids)){ //栏目ids
		if($modelName == 'Category'){
			$where['classpid'] = array('in', explode(',', $SqlOrCids));
		}else{ //同一篇文章可以属于多个栏目 tid是逗号分隔的ids
			$allChildtids = explode(',', childClass($SqlOrCids,'id'));
			foreach($allChildtids as $id){
				$str .= ($str!='' ? ' or ' : '')."find_in_set($id,tid)";
			}
			if($str!='') $where['_string'] .= "($str)";
		}
		$model = M($modelName);
	}else{
		$model = M($modelName);
	}
	
	if($model){
		if($modelName=='Category'){
			$where['classstatus'] = 1;			
			if(APP_NAME!='Admin') $where['classmenushow'] = 1;			
			$model->where($where)->order('classsort desc,classid asc');
		}else{
			$fields = $model->getDbFields();
			
			//取得在其他模块栏目下的文章附属到当前栏目下的部分  准备工作
			if($inputType!='sql'){
				$fields[] = '\''.$modelName.'\' as `module_name`';	
				if(in_array('tid', $fields)){		
					$class_fields = M('Category')->getDbFields();
					$allfields = array_merge($class_fields,$fields);			
				}else $allfields = $fields;	
				$model->field( $allfields );
			}
			
			if(in_array('status', $fields)) $where['status'] = 1;
			if($where) $model->where($where);
			
			$params = array();
			if(in_array('sort', $fields)) $params[] = 'sort desc';
			if(in_array('add_time', $fields)) $params[] = 'add_time desc';
			$pk = in_array('id', $fields) ? 'id' : $model->getPk();
			$params[] = $pk.' desc';
			if($params) $model->order(implode(',',$params));
			//同时取栏目表字段
			if(in_array('tid', $fields)){
				$model->join('inner join '.C('DB_PREFIX').'category ON '.C('DB_PREFIX').'category.classid = '.C('DB_PREFIX').strtolower($modelName).'.tid and '.C('DB_PREFIX').'category.classstatus=1');
			}
		}		
		if($limit!='') $model->limit($limit);
		
		//取得在其他模块栏目下的文章附属到当前栏目下的部分
		if(isset($allfields)){
			$baseSql = $model->buildSql();
			array_pop($allfields);
			if(preg_match('/^([\( ]*select )(.+?)(order by .+?)?(limit .+)?$/i', $baseSql, $baseSqlArr)){
				$sql = $baseSqlArr[1].' SQL_CALC_FOUND_ROWS '.$baseSqlArr[2];
				
				$tmpwhere['mapissource']=0;
				if($allChildtids){
					$tmpwhere['mapclassid']=array('in', $allChildtids);			
				}else{
					$tmpwhere['mapclassmodule']=$modelName;
				}
				$tmp = M('category_map')->Distinct(true)->field('mapinfoid')->where($tmpwhere)->select();
				if($tmp){					
					$otherInfoIds = array();
					foreach($tmp as $tmprs){
						$otherInfoIds[] = $tmprs['mapinfoid'];
					}
					$tmp = M('category_map')->field('mapinfoid,mapclassmodule')->where(array('mapissource'=>1, 'mapinfoid'=>array('in',$otherInfoIds)))->select();
					
					foreach($tmp as $tmprs){
						$str_fields = '`'.implode('`,`',$allfields).'`,\''.$tmprs['mapclassmodule'].'\' as `module_name`';//字段必须对应起来！！
						$sql .= ' union select '.$str_fields.' from `'.C('DB_PREFIX').strtolower($tmprs['mapclassmodule']).'`';
						if(isset($class_fields)){
							$sql .= ' inner join '.C('DB_PREFIX').'category ON '.C('DB_PREFIX').'category.classid = '.C('DB_PREFIX').strtolower($tmprs['mapclassmodule']).'.tid and '.C('DB_PREFIX').'category.classstatus=1';
						}
						$sql .= ' where `id`=\''.$tmprs['mapinfoid'].'\' ';
						if($where['status']) $sql .= ' and `status`=1 ';
					}
				}
				unset($tmp);
				
				$sql .= (false===stripos($baseSqlArr[3],'limit') ? $baseSqlArr[3] : '').$baseSqlArr[4];
				if($debug) return $sql;
				$list = $model->query($sql);
			}
		}
		
		if($debug && !isset($list)) return $model->buildSql();
		if(!isset($list)) $list = $model->select();
	}
	
	//获取信息的url
	$need_url = !in_array('url', $fields) && !in_array('classurl', $fields);
	if( $need_url ){		
		if( function_exists('changurl') ){
			foreach($list as $key=>$rs){
				if($rs['module_name']) $modelName = $rs['module_name'];
				if($modelName == 'Category'){
					$rs['classmethod']=$rs['classmodule'].'/index';
				}else	
					$rs['method']=$modelName.'/view';
				$list[$key] = @call_user_func_array( 'changurl', array($rs) );
			}
		}
	}
	
	if(is_array($objcache) && $objcache){ //缓存
		$objcache[1] = $list;
		call_user_func_array('S', $objcache); unset($objcache);
	}
	return $list;
}

/**
 * 连贯操作，参数个数次序随意，函数后面的多个参数用:分隔，一个参数里面的多个值用,号，第一个参数必须是模型名，或者栏目IDS（但如果省略则是Article）
 * 用法：ch2('article','field:id,img,title,rewrite','where:arrtt fin 1,2 and img!=""','order:sort DESC,add_time DESC','limit:0,1','cache:Index_SlideNews:3','debug')
 * 或者：S('Index_SlideNews');
 * 或者：ch2('limit:2','order:id desc');
 * 模型名称（可省略）或连贯操作的函数名都可映射CMSHEAD_KEY_ROUTER 
 * @return array 二维
 */
function ch2(){
	$list = $existsKeys = $whereParams = $whereParams_union = $orderParams = $allFields = $objcache = $allChildtids = array();
	$modelKeys = array('table','where','order','field','limit','cache','distinct','join','group','page');
	$args = func_get_args();
	$debug = array_search('debug', $args); if($debug) unset($args[$debug]);//调试模式
	if($args){		
		foreach($args as $arg){
			$arr = explode(':', $arg);
			$tmp = is_array(C('CMSHEAD_KEY_ROUTER')) ? C('CMSHEAD_KEY_ROUTER') : array();
			if( array_key_exists($arr[0], $tmp) ){
				$modelKey = $tmp[$arr[0]];
			}else $modelKey = $arr[0];
			$existsKeys[] = $modelKey;
			
			//若有缓存数据，直接返回
			if($modelKey=='cache'){
				$objcache[0] = is_numeric($arr[2]) ?  $arr[1] : (is_numeric($arr[1]) ? md5(serialize($args)) : $arr[1]); //名称
				$objcache[1] = NULL; //data 后面设置
				$objcache[2] = is_numeric($arr[2]) ? $arr[2] : (is_numeric($arr[1]) ? $arr[1] : 60); //时间
				if( $objcache[0] && $dataCache = S($objcache[0]) ){
					return $dataCache;
				}
			}  
		}		
		
		if(preg_match('/^\d+(,\d+)*$/',$args[0])){ //栏目ids
			$allChilds = childClass($args[0],'classid,classmodule');			
			$modelName = M('Category')->where(array('classid'=>$allChilds[0]['classid']))->limit(1)->getField('classmodule');
			//同一篇文章可以属于多个栏目 tid是逗号分隔的ids  2013-2-7
			//$whereParams['tid'] = array('in', $tmp);
			foreach($allChilds as $k=>$val){
				$allChildtids[$k] = $val['classid']; 
				$str .= ($str!='' ? ' or ' : '')."find_in_set({$val['classid']},tid)";
			}
			if($str!='') $whereParams['_string'] .= "($str)";			
		}else{
			$tmp = is_array(C('CMSHEAD_KEY_ROUTER')) ? C('CMSHEAD_KEY_ROUTER') : array();
			if( array_key_exists($args[0], $tmp) ){
				$modelName = $tmp[$args[0]];
			}else $modelName = $args[0];
		}
	}
	if( preg_match('/^[a-z]\w+$/i',$modelName) ){ //判断模型合法性
		$array = M('Node')->cache('tables', 600)->query('show tables;');
		foreach($array as $arr){
			if( strtolower(C('DB_PREFIX').$modelName) == strtolower($arr['Tables_in_'.C('DB_NAME')]) ){
				$modelName = ucfirst(strtolower($modelName));
				$modelisOK = true;
				break;
			}
		}
	}
	if(!$modelisOK) $modelName = 'Article'; //默认模型
	$model = M($modelName);
	$fields = $model->getDbFields();		
	$havepage = in_array('page', $existsKeys);
	$whereEndFlag = false;
	
	foreach($args as $arg){
		$arr = explode(':', $arg);
		if(($num = count($arr)) > 1 ){			
			$tmp = is_array(C('CMSHEAD_KEY_ROUTER')) ? C('CMSHEAD_KEY_ROUTER') : array();
			if( array_key_exists($arr[0], $tmp) ){
				$modelKey = $tmp[$arr[0]];
			}else $modelKey = $arr[0];		
			
			if( in_array($modelKey, $modelKeys) ){
				if($havepage){
					if($modelKey=='limit' or $modelKey=='cache'){
						unset($existsKeys[$modelKey]);
						continue;
					}
				}
				$params = trim(str_replace(array('&amp;','&quot;','&#039;','&lt;','&gt;','&nbsp;'), array('&','"',"'",'<','>',' '), implode(':', array_slice($arr,1)) )); //去掉	$modelKey的其他	
//				echo $modelKey.' => '.$params.'<br>';					
				//解析参数中存在的变量
				if(preg_match_all('/([\'"] *\. *)(.*?)( *\. *[\'"])/', $params, $tmpArr)){
					for($k=0,$m=count($tmpArr[0]); $k<$m; $k++){
						$temp = NULL;
						@eval('$temp = '.$tmpArr[2][$k].';');
						if(!is_null($temp)) $params = str_replace($tmpArr[0][$k] , trim($tmpArr[1][$k],'.').$temp.trim($tmpArr[3][$k],'.') , $params);
					}
				}				
				
				//如果不是后台预览，则自动加上部分默认where条件，★where可能有多个★
				if($modelKey=='where'){
					if(substr($params,0,1)!='@'){ //其他直接call ch2传入的条件
						//查询条件快捷模式写法 where:attrtj:1,2 and attrtt:2,3 and img!=''
						if(substr($params,-1)=='$'){//结束符号，不用再附加了
							$params = rtrim($params,'$');
							if(!$whereEndFlag) $whereEndFlag = true;
						}
						if( preg_match_all('/(`?\w+`?)\s*(:|=|\s+in\s+|\s+fin\s+|\s+like\s+|!=|<>|>|<|>=|<=)([^\s\(\)]+)/i', $params, $paramsArr) ){
							foreach($paramsArr as $pAk=>$pAv){
								if( !trim($paramsArr[1][$pAk],'`') || !trim($paramsArr[2][$pAk]) ) continue;
								$strparam = '';
								switch( trim($paramsArr[2][$pAk]) ){
									case ':':
									case '=':
										$strparam = '`'.trim($paramsArr[1][$pAk],'`')."`='".trim($paramsArr[3][$pAk],'"\'')."'";
										break;
									case 'like':
										$strparam = '`'.trim($paramsArr[1][$pAk],'`')."` like '%".trim($paramsArr[3][$pAk],'"\'')."%'";
										break;
									case 'in':
										$strparam = '`'.trim($paramsArr[1][$pAk],'`')."` in ('".str_replace(',',"','",trim($paramsArr[3][$pAk],'"\''))."')";
										break;
									case 'fin':
										foreach( explode(',' ,$paramsArr[3][$pAk]) as $tk=>$tval ){
											$strparam .= ($tk ? ' or ' : '').'find_in_set(\''.trim($tval,'"\'').'\',`'.trim($paramsArr[1][$pAk],'`').'`)';											
										}
										if($strparam!='') $strparam = "($strparam)";
										break;
									default:
										$strparam = '`'.trim($paramsArr[1][$pAk],'`')."`".trim($paramsArr[2][$pAk])."'".trim($paramsArr[3][$pAk],'"\'')."'";
										break;	
								}
								$params = str_replace($paramsArr[0][$pAk], $strparam, $params);
								
								//union表继承用户指定的where *应该把where中的字段取出再在union表字段中做对比，没有的需要剔除。但是太麻烦了，一般也不需要*
								//类似：$whereParams_union['img']=array('neq','');
								if( in_array(trim($paramsArr[1][$pAk],'`'), array('img')) ){
									$whereParams_union[trim($paramsArr[1][$pAk],'`')] = array(str_replace(array('!=','=','<>','>','<','>=','<='),array('neq','eq','neq','gt','lt','egt','elt'),trim($paramsArr[2][$pAk])) , trim($paramsArr[3][$pAk],'"\''));							
								}
							}
						}
						if($params!=''){							
							$whereParams['_string'] .= $whereParams['_string'] ? " and ($params)" : $params;
						} 
					}elseif(!$whereEndFlag){ //分页Sys:page传入的条件，序列化where:@...
						//$usemap = (!class_exists('CommonAction') || ($tmp = strtolower(CommonAction::$App['app'][0]))=='index' || $tmp==strtolower($modelName));
						//if($usemap){
							$params = unserialize(substr($params,1));
							if(is_array($params)){
								$whereParams = array_merge( $whereParams, $params );
							}else{
								$whereParams['_string'] .= $whereParams['_string'] ? " and ($params)" : $params;
							}
						//}						
					}
				}			
				//自动加上URL重写值rewrite字段
				if($modelKey=='field' && $params!='*' && !in_array('distinct', $existsKeys)){
					$allFields = explode(',', $params);
					if( in_array('rewrite', $fields) && !in_array('rewrite', $allFields) ) $allFields[] = 'rewrite';
				}	
				//自动加上默认排序字段
				if($modelKey=='order'){
					if(substr($params,-1)=='$'){//结束符号，不用再附加了
						$params = rtrim($params,'$');
					}else{
						if($params=='2'){ //最热
							$params = '';
							if(in_array('apv', $fields)) $params = 'apv desc,';
							if(in_array('sort', $fields) && false===strpos($params,'sort')) $params .= 'sort desc,';
						}elseif($params=='1'){ //最新
							 $params = '';
							 //if(in_array('id', $fields) && false===strpos($params,'id')) $params .= 'id desc,';
						}else{ //其他						
							if(in_array('sort', $fields) && false===strpos($params,'sort')) $params = 'sort desc,'.trim($params,',');
						}
						$params = trim($params,',');
					}
					if($params!='') $orderParams = explode(',',preg_replace('/desc|asc|\s/i','',$params));
				}	
							
				if($num>1) $params = explode(':', $params);
				if($modelKey=='page' && is_array($params)){
					$pageParams = $params;
				}
				if($modelKey!='where' && $modelKey!='field'){
					if(is_array($params)) @call_user_func_array( array($model, $modelKey), $params );
					else @call_user_func( array($model, $modelKey), $params );					
				}
			}
		}
	}

	//执行查询条件，加上默认条件
	if(!($_SESSION[C('USER_AUTH_KEY')] && $_GET['preview']==1)){ //非后台预览
		if(in_array('classstatus', $fields)){
			$whereParams['classstatus'] = 1;
			if(APP_NAME!='Admin') $whereParams['classmenushow'] = 1;
		}
		elseif(in_array('status', $fields)){
			if(in_array('tid', $fields)) $whereParams['classstatus'] = 1;
			$whereParams['status'] = 1;
		} 
	}
	$model->where($whereParams);
	
	//自动加上默认排序字段
	if(!in_array('order', $existsKeys)){
		$params = '';
		if(in_array('sort', $fields)){$orderParams[]='sort'; $params = 'sort desc,'.$params;}
		if($modelName!='Category'){
			if(in_array('add_time', $fields)){$orderParams[]='add_time'; $params .= 'add_time desc,';}
			if(in_array('id', $fields)){$orderParams[]='id'; $params .= 'id desc,';}
		}
		$model->order(rtrim($params,','));
	}	
	
	//读取字段的处理 为可能的union操作做准备
	if( !$allFields ){//没有指定具体字段则为所有字段
		$allFields = $fields;		
	}else{//指定了几个字段则把排序字段包括进去
		$allFields = array_unique(array_merge($allFields,$orderParams));
	}
	if(in_array('tid', $fields)){
		$class_fields = '*';
		$allFields[] = C('DB_PREFIX').'category.'.$class_fields;
	}
	
	$allFields[] = '\''.$modelName.'\' as `module_name`';//最后一个
	$model->field($allFields);
	
	//同时取栏目表信息
	if($modelName!='Category' && in_array('tid', $fields)){
		$model->join('inner join '.C('DB_PREFIX').'category ON '.C('DB_PREFIX').'category.classid = '.C('DB_PREFIX').strtolower($modelName).'.tid and '.C('DB_PREFIX').'category.classstatus=1');
	}		
	
	//最后
	$baseSql = $model->buildSql();
	array_pop($allFields);
	
	if(preg_match('/^([\( ]*select )(.+?)(order by .+?)?(limit .+)?$/i', $baseSql, $baseSqlArr)){		
		$sql = $baseSqlArr[1].' SQL_CALC_FOUND_ROWS '.$baseSqlArr[2];
		
		/**union操作**/
		//if(!$usemap){//map里的字段可能在别的表里不一定是那个名称，这个不做对应处理了，影响效率，一般只查某个模块下的，也没必要
			//栏目混合附属 其他模块栏目下的文章附属到当前栏目下的部分
			$tmpwhere['mapissource']=0;
			if($allChildtids){
				$tmpwhere['mapclassid']=array('in', $allChildtids);			
			}else{
				$tmpwhere['mapclassmodule']=$modelName;
				if(preg_match('/(( and|or )?\(?[\s]*find_in_set\(\d+,[\s]*`?tid`?\)[\s]*\)?)+/i', $baseSqlArr[2], $classidWhere)){//继承Sys::page方法的tid条件
					$tmpwhere['_string']=str_ireplace(array('`tid`','tid'),'`mapclassid`',$classidWhere[0]);
				}
			}
			
			$tmp = M('category_map')->Distinct(true)->field('mapinfoid')->where($tmpwhere)->select();
			if($tmp){
				$otherInfoIds = $tmpArr = $otherTableFields = array();
				foreach($tmp as $tmprs){
					$otherInfoIds[] = $tmprs['mapinfoid'];
				}
				//查找源头表，信息   ；  相同模型名合并到一起
				$tmp = M('category_map')->field('mapinfoid,mapclassmodule')->where(array('mapclassmodule'=>array('neq', $modelName), 'mapissource'=>1, 'mapinfoid'=>array('in',$otherInfoIds)))->select();				
				foreach($tmp as $tmprs){
					$tmpArr[$tmprs['mapclassmodule']][] = $tmprs['mapinfoid'];					
				}
				
				foreach($tmpArr as $key=>$val){ //模型名=>信息id数组
					$allFields2 = $allFields;
					//源表在新表中不存在的字段，就替换成 '' as 字段名
					$otherTableFields = M($key)->getDbFields();//新表的
					foreach($allFields2 as $k=>$fromField){
						if( false===strpos($fromField,'.') && !in_array($fromField, $otherTableFields) ) $allFields2[$k] = "'' as `$fromField`";
					}
					
					$allFields2[] = '\''.$key.'\' as `module_name`';
										
					$whereParams_union['id'] = array('in', $val);
					
					$model2 = M($key)->field($allFields2)->where( $whereParams_union );	
					if(isset($class_fields)){
						$model2->join('inner join '.C('DB_PREFIX').'category ON '.C('DB_PREFIX').'category.classid = '.C('DB_PREFIX').strtolower($key).'.tid and '.C('DB_PREFIX').'category.classstatus=1');
					}
					$tmp = $model2->buildSql();
					if($tmp) $sql .= ' union '.$tmp;
				}
//				echo 'union语句：'.$sql;exit;
			}
			unset($tmp);
			//栏目级的模型混建支持
			$unionSql = ''; $tmpArr = $otherTableFields = array();
			foreach($allChilds as $k=>$val){
				if($val['classmodule']!=$modelName && !in_array($val['classmodule'], $tmpArr)){
					$tmp = ' union '.str_ireplace(array('`'.C('DB_PREFIX').$modelName.'`', C('DB_PREFIX').$modelName.'.', "'{$modelName}' as ", 'SQL_CALC_FOUND_ROWS'),
					 array('`'.C('DB_PREFIX').strtolower($val['classmodule']).'`', C('DB_PREFIX').strtolower($val['classmodule']).'.', "'{$val['classmodule']}' as ", ''), $sql);
					
					//源表在新表中不存在的字段，就替换成 '' as 字段名
					$otherTableFields = M($val['classmodule'])->getDbFields();//新表的
					foreach($allFields as $fromField){
						if( false===strpos($fromField,'.') && !in_array($fromField, $otherTableFields) ) $tmp = str_ireplace("`$fromField`", "'' as `$fromField`", $tmp);
					} 						
					$unionSql .= $tmp;		
				}
				$tmpArr[] = $val['classmodule'];			
			}
			if($unionSql) $sql .= $unionSql;
		//}		
		
		$sql .= (false===stripos($baseSqlArr[3],'limit') ? $baseSqlArr[3] : '');
		if( !in_array('page', $existsKeys) ){
			if( in_array('limit', $existsKeys) ) $sql .= $baseSqlArr[4];
		}else{
			//为获取总条数做准备
			$model->query($sql.' limit 1');
			$tmp = $model->query('select FOUND_ROWS() as rowCount;');
			$rowCount = $tmp[0]['rowCount'];
			$pageSize = is_array($pageParams) ? $pageParams[0] : 20;
			if($rowCount){
				import('ORG.Util.Page');
				$pageClass = new Page($rowCount, $pageSize);
				$page = $pageClass->show(is_array($pageParams) ? $pageParams[1] : '');
				$sql .= " limit {$pageClass->firstRow},{$pageClass->listRows}";	
			}else{
				$sql .= " limit 0,{$pageSize}";	
			}
		}
					
		if($debug) return $sql;
		$list = $model->query($sql);
	}else{
		if($debug) return $model->buildSql();
		$list = $model->select();	
	}
//	echo $sql;
	//获取信息的url
	$need_url = !in_array('url', $fields) && !in_array('classurl', $fields);
	if( $need_url ){
		if( function_exists('changurl') ){
			foreach($list as $key=>$rs){
				if($rs['module_name']) $modelName = $rs['module_name'];
				if($modelName == 'Category'){
					$rs['classmethod']=$rs['classmodule'].'/index';
				}else	
					$rs['method']=$modelName.'/view';
				$list[$key] = @call_user_func_array( 'changurl', array($rs) );
			}
		}
	}
	
	if(is_array($objcache) && $objcache){ //缓存
		$objcache[1] = $list;
		call_user_func_array('S', $objcache); unset($objcache);
	}
	return isset($page) ? array('list'=>$list, 'page'=>$page) : $list;
}
/**
 * 取得当前网址下的app信息
 * @return array action app vars
 */
function chapp(){
	//取得当前控制器类名
	$url = (0===strpos($_SERVER['REQUEST_URI'],__ROOT__)) ? substr($_SERVER['REQUEST_URI'], strlen(__ROOT__)) : $_SERVER['REQUEST_URI'];  //以前用的PATH_INFO，有缺陷	
	$rewrite = trim(urldecode($url),'/');	
	$rewrite = str_replace(__APP__.'/','',__ACTION__); 
	$rewrite = preg_replace('/[\/]+/','/',str_replace(array('?','&','='), '/', $rewrite));
	$exp = explode('/', $rewrite);
	$rs = M("Router")->where(array('rewrite'=>$exp[0]))->field('url')->find();
	if($rs){
		//把news/p/2 转成 article/index/id/224/p/2 附加参数
		for($i=1,$n=count($exp); $i<$n; $i++){
			if($exp[$i]!='') $rs['url'] .= '/'.$exp[$i];
		}
		//形如id/2/a/1/b/B/c/C的 参数转化为数组
		$exp = explode('/', $rs['url']);
	}
	$vars = array();
	if(CommonAction::$id) $vars['id'] = CommonAction::$id;
	for($i=2,$n=count($exp)-1; $i<$n; $i++){
		$vars[$exp[$i]]=$exp[$i+1];
		$i++;
	}
	$action = array(ucfirst(strtolower($exp[0])).'Action', strtolower($exp[1]));
	return array('action'=>$action, 'app'=>$exp, 'vars'=>$vars, 'url'=>$url);
}

/**
 * 动态生成缩略图或格式化图片输出
 * 调用方法：{$vo.img|chimg=500,500} {$vo.img|chimg='theory',500,500} {$vo.img|chimg}
 * @param $img
 * @param $modelNames 可能是多个逗号分隔 ，如果是网站其他目录，则为空
 * @param $w
 * @param $h
 * @return thumb img full src
 */
function chimg($img,$modelNames='article',$w=0,$h=0){
	if(!trim($img,"\s")) return (APP_NAME=='Admin') ? '' : __ROOT__.'/Public/Images/noimg.jpg';
	$args = func_get_args();
	if(($n=count($args))<4){
		if($n==3 && !is_numeric($args[0]) && is_numeric($args[1]) && is_numeric($args[2])){			
			$img = $args[0]; $modelNames = 'article'; 
			$w = $args[1]; $h = $args[2];
		}
	}
	$isTrueImg = preg_match('/\.(jpeg|jpg|gif|png)$/i', $img);
	$dirroot = $_SERVER['DOCUMENT_ROOT'].__ROOT__;
	
	if(!empty($modelNames)){
		$modelNames = explode(',',$modelNames);
		foreach($modelNames as $modelName){
			$localpath = '/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.strtolower($modelName).'/';
			if(!is_dir($dirroot.$localpath)){
				$tmp = C('CMSHEAD_KEY_ROUTER');
				$localpath = '/Public/'.(C('UPLOAD_DIR')?C('UPLOAD_DIR'):'Upload').'/'.strtolower($tmp[$modelName]).'/';
			}
			if(is_file($dirroot.$localpath.$img)) break;
		}
	}
	
	if($w>0 && $h>0 && $isTrueImg){
		$thumbImg = strlen(dirname($img))>1 ? dirname($img).'/'.$w.'_'.$h.'_'.basename($img) : $w.'_'.$h.'_'.$img;		
		if(is_file($dirroot.$thumbImg)){
			$thumbImg = __ROOT__.$thumbImg;
		}elseif(is_file($dirroot.$localpath.$thumbImg)){
			$thumbImg = __ROOT__.$localpath.$thumbImg;
		}elseif(is_file($dirroot.$img)){
			import('ORG.Util.Image');
			Image::thumb2($dirroot.$img, $dirroot.$thumbImg, '', $w, $h);			
			$thumbImg = __ROOT__.$thumbImg;
		}elseif(is_file($dirroot.$localpath.$img)){			
			import('ORG.Util.Image');
			Image::thumb2($dirroot.$localpath.$img, $dirroot.$localpath.$thumbImg, '', $w, $h);			
			$thumbImg = __ROOT__.$localpath.$thumbImg;
		}else{
			$thumbImg = $img;
		}
	}else{
		$thumbImg = is_file($dirroot.$img) ? __ROOT__.$img : ( is_file($dirroot.$localpath.$img) ? __ROOT__.$localpath.$img : $img );
	}
	if(APP_NAME=='Admin') return $thumbImg;
	return (empty($thumbImg) && $isTrueImg) ? __ROOT__.'/Public/Images/noimg.jpg' : $thumbImg;
}

/**
 * 输出视频
 * @param $file
 * @param $width
 * @param $height
 * @return html
 */
function chvideo($file,$modelNames='video',$width=660,$height=450){
	if(empty($file)) return '没有视频文件';
	$local = (false===stripos(str_ireplace(C('SITE_URL'),'',$file),'http'));	
	$flash = preg_match('/\.(swf|flv)/i', $file);
	$file = chimg($file, $modelNames);
	if($flash){
		if($local){
			$str = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'.$width.'" height="380">
                        <param name="movie" value="__PUBLIC__/Plugin/Flvplayer/Flvplayer.swf">
                        <param name="quality" value="high">
                        <param name="wmode" value="transparent">
                        <param name="allowFullScreen" value="true" />
                        <param name="FlashVars" value="vcastr_file='.$file.'&IsAutoPlay=0" />	
                        <embed src="__PUBLIC__/Plugin/Flvplayer/Flvplayer.swf" allowFullScreen="true" FlashVars="vcastr_file='.$file.'&IsAutoPlay=0" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" wmode="transparent"></embed>
                        </object>';
		}else{
			$str = '<embed src="'.$file.'" allowFullScreen="true" quality="high" width="'.$width.'" height="'.$height.'" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>';
		}
	}else{
		$str = '<EMBED type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/MediaPlayer/" width='.$width.' height='.$height.' src="'.$file.'" autorewind="true" showdisplay="false" showstatusbar="true" showcontrols="true" autostart="true" filename="'.$file.'"></EMBED>';
	}
	return $str;
}

/**
 * 格式化，去掉html代码，可带截取
 * @param $str
 * @param $length
 * @return string or array's string
 */
function encode($str, $length=0){
	if(is_array($str)){
		foreach($str as $k => $v) $data[$k] = encode($v, $length);
		return $data;
	}
	
	if(!empty($length)){
		$estr = htmlspecialchars( preg_replace('/(&[a-zA-Z]{2,5};)|(\s)/','',strip_tags(str_replace('[CHPAGE]','',$str))) );
		if($length<0) return $estr;
		return msubstr($estr,0,$length);
	}
	return htmlspecialchars( trim(strip_tags($str)) );
}
//截取字符串，msubstr的别名
function cutstr($str,$length,$suffix=0){
	if(is_array($str)){
		foreach($str as $k => $v) $data[$k] = cutstr($v, $length, $suffix);
		return $data;
	}
	if($length>0) $str = str_replace('[CHPAGE]','',$str);
	return msubstr($str, 0, $length, 'utf-8', $suffix);
}
//模板中参数的过滤，保留html代码，通常用在ch2,ch1中接收参数时候
function safe($value,$type='str'){
	if(is_array($value)){
		foreach($value as $k => $v) $data[$k] = safe($v,$type);
		return $data;
	}
	
	switch($type){
		case 'str':
			return str_replace(array('"',"'",'%','[CHPAGE]'), array('&quot;','&#039;','％',''),htmlspecialchars(trim($value)));
			break;
		case 'int':
			return is_numeric($value) ? $value : -1;
			break;
		case 'date':
		case 'time':
			return is_date($value) ? $value : '';
			break;
		default:
			return $value;
			break;
	}
}

/**
 * 检测当前模块自定义的隐射的某个字段的名称，比如用户可能把title改成biaoti，这里根据title返回biaoti
 * @param $modelName 模型名称
 * @param $fromFieldName 隐射的字段如title
 * @return 修改后的字段如biaoti
 */
function trueMapField($modelName, $fromFieldName='title'){
	static $truefield = '';
	if($truefield) return $truefield;
    $fieldsmap = M('model_fieldnotes')->where(array('ename'=>$modelName))->getField('fieldsmap');
    if($fieldsmap && is_array($fieldsmap=unserialize($fieldsmap))){
    	foreach($fieldsmap as $field=>$val){
    		if($val['search_names']==$fromFieldName){
    			return $truefield = $field;
    		}
    	}
    }
    return $fromFieldName;
   
}

/**  
 * 2013-2-7 获取指定栏目的所有子栏目（包括自己）
 * @param $cids 指定的栏目ids，多个逗号分隔
 * @param $fields 要获取的字段信息，多个逗号分隔，如果只获取classid，则指定成classid或id
 * @return string ids或array list
 */
function childClass($cids,$fields='*'){
	if(!is_array($cids)) $cids = explode(',',$cids);
	$model = M('Category')->where( array('classid'=>array('in', $cids)) );
	$list = $model->field('classchildids')->select();
	if($list){
		foreach($list as $rs){
			$childClassIds .= ($childClassIds!='' ? ',':''). $rs['classchildids'];
		}
		if($fields=='classid' || $fields=='id') return $childClassIds;
	}else{
		return $cids;
	}	
	$model = M('Category')->where( array('classid'=>array('in', $childClassIds)) );
	if($fields!='*') $model->field($fields);
	return $model->select();
}

//标题风格样式 db读取的 ，最后应用  如{$vo.title|cutstr=30,1|titlestyle=$vo['tcolor'],$vo['isb'],$vo['isi']}
function titlestyle($title='',$tcolor='',$isb=0,$isi=0){
	$style='';
	if($tcolor!='') $style.='color:'.$tcolor.';';
	if($isb) $style.='font-weight:bold;';
	if($isi) $style.='font-style:italic;';
	return $style!='' ? '<span style="'.$style.'">'.$title.'</span>' : $title;
}

/**
 * 取得要显示的信息URL，兼容顺序外部网址 > 二级域名 > URL重写值
 * 需要参数rewrite或Article/view
 */
function changurl($ary){
    if(is_array($ary)){
        if(array_key_exists('url',$ary) || array_key_exists('classurl',$ary)) return $ary;
        $SITE_URL = C('SITE_URL');
        $homeRootURL = appRootURL(1); //默认用主域名绝对路径
       
        //检测当前访问域名是否是二级域名。如果是，则判断，如果和本$ary是同一个模块，则用二级域名访问。	
        $inputURL = thisURL(1);
        if( $inputURL['http'].$inputURL['domain']!=$SITE_URL ){
	        $full_inputDomain = str_ireplace('www.','',$inputURL['domain']); //完整 
			$left_inputDomain = str_ireplace( preg_replace('/^https?:\/\/(www)?/i', '', $SITE_URL), '', $full_inputDomain); //前缀
			$rs = M('category')->where("classstatus=1 and (replace(classdomain,'www.','')='{$left_inputDomain}' or replace(classdomain,'www.','')='{$full_inputDomain}')")->field('classid,classmodule')->find();		
			if( $rs && ($ary['classmodule'] == $rs['classmodule'] || $ary['module_name'] == $rs['classmodule']) ){
				$homeRootURL = $inputURL['http'].$full_inputDomain;
			}
			unset($rs);
        }elseif( !$ary['classdomain'] && $ary['classpids'] ){  
        	//否则在主域名访问的时候 ，如果当前栏目没有二级域名   则从下到上逐级查找二级域名，有则采用   0,331
        	$rs = M('category')->where("classstatus=1 and classdomain!='' and classid in ({$ary['classpids']})")->field('classdomain')->order('classid desc')->find();
        	if($rs){
        		$arr = explode('//',str_ireplace('www.','',$SITE_URL));
                if( false!==stripos($rs['classdomain'], $arr[1]) ){
                	$homeRootURL = $arr[0].'//'.$rs['classdomain'];
                }else{
                    $homeRootURL = $arr[0].'//'.$rs['classdomain'].'.'.$arr[1];
                }
        	}
        	unset($rs);
        }
        
        $fix = C('URL_HTML_SUFFIX'); //伪静态后缀acan
        if(array_key_exists('classmethod',$ary)){ //只取栏目表时
        	if($ary['classouturl']!=''){
                $ary['url']=$ary['classouturl'];
            }elseif($ary['classdomain']!=''){
                $arr = explode('//',str_ireplace('www.','',$SITE_URL));                
                if( false!==stripos($ary['classdomain'], $arr[1]) ){//如果是test.cmshead.com 包含 cmshead.com
                    $ary['url']=$arr[0].'//'.$ary['classdomain'];
                }else{
                    $ary['url']=$arr[0].'//'.$ary['classdomain'].'.'.$arr[1];
                }    				
            }elseif($ary['classrewrite']!=''){
                $ary['url']=$homeRootURL.'/'.$ary['classrewrite'].$fix;
            }elseif($ary['classmethod']!=''){
                $ary['url']=$homeRootURL.'/'.ucfirst(strtolower($ary['classmethod'])).'/id/'.$ary['classid'].$fix;
            }
        }else{
            if(array_key_exists('classmodule',$ary)){ //同时取栏目表时
                if($ary['classouturl']!=''){
                	$ary['url']=$ary['classouturl'];
           		}elseif($ary['classdomain']!=''){ 
                    $arr = explode('//',str_ireplace('www.','',$SITE_URL));
                    if( false!==stripos($ary['classdomain'], $arr[1]) ){
                        $ary['classurl']=$arr[0].'//'.$ary['classdomain'];
                    }else{
                        $ary['classurl']=$arr[0].'//'.$ary['classdomain'].'.'.$arr[1];
                    }    				
                }elseif($ary['classrewrite']!=''){
                    $ary['classurl']=$homeRootURL.'/'.$ary['classrewrite'].$fix;
                }else{
                    $ary['classmethod']=$ary['classmodule'].'/index';
                    $ary['classurl']=$homeRootURL.'/'.ucfirst(strtolower($ary['classmethod'])).'/id/'.$ary['classid'].$fix;
                }
            }
        	if($ary['outurl']){
                $ary['url']=$ary['outurl'];
            }elseif($ary['rewrite']){
                $ary['url']=$homeRootURL.'/'.$ary['rewrite'].$fix;
            }elseif($ary['method']){
                $ary['url']=$homeRootURL.'/'.ucfirst(strtolower($ary['method'])).'/id/'.$ary['id'].$fix;
            }
        }
    }
    return $ary;
}   
    
/**
 * 取得前台$appname访问根路径
 * @param $type = 0相对，1绝对
 * @param $appname = home默认
 * @return rooturl
 */
function appRootURL($type=0,$appname='home'){
    if(strtolower(APP_NAME)!=$appname){//跨组调用
    	$appConfig = include __ROOT__.'/'.ucfirst(strtolower($appname)).'/Conf/config.php';
		return ($type ? C('SITE_URL') : '__ROOT__').($appConfig['URL_MODEL']==2 ?  '' : ('/'.($appname=='home' ? 'index' : $appname).'.php'));
    }
    return $type ? C('SITE_URL').(C('URL_MODEL')==2 ?  '' : ('/'.($appname=='home' ? 'index' : $appname).'.php')) : '__APP__';
}

//取得当前网址信息
function thisURL($returnType=0){
	$http = $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$domain = $_SERVER['HTTP_HOST'];
	$params = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME'].($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : $_SERVER['PATH_INFO']);
	if($returnType==0) return $http.$domain.$params;
	return array('http'=>$http, 'domain'=>$domain, 'params'=>$params);
}

/**
 * 根据id得到URL地址   用法 {:chURL(224)}   {:chURL(40,'article')} 
 * @param $id 指定的栏目id或者信息id
 * @param $method  category为取目录地址，其他模块名称，则取详细地址（同时取目录地址）
 * @param $getType 0只取url，1取数组
 * @return url or array
 */
function chURL($id,$method='category',$getType=0){
	if(!is_numeric($id) || $id<1) return '';
	$tmp = is_array(C('CMSHEAD_KEY_ROUTER')) ? C('CMSHEAD_KEY_ROUTER') : array();
	if( array_key_exists($method, $tmp) ){
		$method = $tmp[$method];
	}
	if( strtolower($method)=='category' ){
		$rs = M($method)->where( array('classid'=>$id) )->field('classid,classouturl,classdomain,classrewrite,classmodule')->find();
		if(!$rs) return '';
		$rs['classmethod'] = $rs['classmodule'].'/index';
		$return = changurl($rs);
		return $getType ? $return : $return['url'];
	}else{
		$rs = M($method)->join('inner join '.C('DB_PREFIX').'category ON '.C('DB_PREFIX').'category.classid = '.C('DB_PREFIX').strtolower($method).'.tid and '.C('DB_PREFIX').'category.classstatus=1')->where( array('id'=>$id) )->field('id,outurl,rewrite, classid,classouturl,classdomain,classrewrite,classmodule')->find();		
		if(!$rs) return '';
		$rs['method'] = $rs['classmodule'].'/view';
		$return = changurl($rs);
		return $getType ? $return : $return['url'];
	}	
}

/**
 * 调取相关文章的函数
 * @param $keywords 指定关键字
 * @param $modulname 某某模型
 * @return array
 */
 function chRelate($keywords='',$modulnames='Article',$limit='10'){
	 if(!$keywords) return NULL;
	 $keywordsArr = explode(' ',$keywords);
	 foreach ($keywordsArr as $k => $v) {
		if (!$v) unset($keywordsArr[$k]);
	 }
	 $keywordsStr = implode("%' or `keywords` like '%",$keywordsArr);
	 $where = "`keywords` like '%".$keywordsStr."%'";
	 $where .= ' or '.str_replace('`keywords`', '`title`', $where);
	 $return = M($modulnames)->where($where)->field('id,title,tid,add_time')->limit($limit)->select();
	 foreach($return as $key=>$val){
		 $return[$key]['url'] = chURL($val['id'],$modulnames);
	 } 
	 return($return); 
 }