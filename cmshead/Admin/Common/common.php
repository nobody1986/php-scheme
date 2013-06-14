<?php
//返回前台DEFAULT_THEME
function getHomeDefTheme(){
	include __ROOT__.'/Home/Conf/config.php';	
	return $home_config['DEFAULT_THEME'] ? $home_config['DEFAULT_THEME'] : 'default';
}
/**
 * 根据字段与搜索域对应关系生成查询条件 
 * 返回支持空格的关键字查询 
 * 可以单独制定哪些字段需要like模糊查询<input type="hidden" name="_search_likes" value="*"/>字段名逗号分隔，*为所有
 * 参数如：
 * $where = getSearchMap(array('title'=>'keyword','content'=>'keyword'));
   $where['_logic'] = 'OR';
   $map['_complex'] = $where;
 * @param $fieldMap
 * @return array map
 */
function getSearchMap($fieldMap){
	$map = array ();
	if(is_array($fieldMap) && !empty($fieldMap)){
		$_search_likes = $_REQUEST ['_search_likes']; $arr_search_likes = (false!==strpos($_search_likes,',')) ? explode(',',$_search_likes) : array();
		foreach ( $fieldMap as $key => $val ) {
			if (isset ( $_REQUEST [$val] ) && ($keyword = htmlspecialchars(trim($_REQUEST [$val]))) != '') {
				$mapkey = is_numeric($key) ? $val : $key;
				$is_like = ($_search_likes=='*' || in_array($mapkey, $arr_search_likes));
				
				if(false!==strpos($keyword,' ')){
					$childstr = ''; $childarr = array();
					$arr = explode(' ', $keyword);
					foreach($arr as $v){
						$v = trim($v);
						if($v!=''){
							$childstr .= ($childstr!='' ? '&' : '') . $mapkey;
							$childarr[] = $is_like ? array('like', "%{$v}%") : $v;
						}
					}
					if($childarr){
						$childarr['_multi'] = true;
						$map[$childstr]	= $childarr;
					} 				
				}else{
					$map [$mapkey] = $is_like ? array('like', "%{$keyword}%") : $keyword;
				}
			}
		}
	}
	return $map;
}

/**
 * 内容中的关键字套红
 * @param string $val
 * @param string $keyword
 * @param int $strlen
 * @return string
 */
function setSearchKey(&$val, $keyword, $strlen=0){
	if($strlen>0){
		$val = msubstr(preg_replace('/\s|&[a-zA-Z]+;/','',strip_tags($val)), 0 ,$strlen);		
	} 
	$arr = explode(' ', $keyword);
	foreach($arr as $v){
		$v = trim($v);
		if($v!='') $val=str_ireplace($v,"<font color=red><b>{$v}</b></font>",$val);		
	}
	return $val;
}

/**
 * unset数组的value写法
 * @param $searchArray
 * @param $remove
 * @return array
 */
function unsetValue(&$searchArray, $remove){	
	foreach($searchArray as $key=>$val){
		if(is_array($remove)){
			if(in_array($val,$remove)) unset($searchArray[$key]);			
		}elseif(is_string($remove)){
			if($remove===$val) unset($searchArray[$key]);
		}
	}
}

function getStatus($status, $imageShow = true) {
	switch ($status) {
		case 0 :
			$showText = '禁用';
			$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/error.png" WIDTH="20" HEIGHT="17" BORDER="0" ALT="禁用">';
			break;
		case 2 :
			$showText = '待审';
			$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/locked.png" WIDTH="20" HEIGHT="17" BORDER="0" ALT="待审">';
			break;
		case -1 :
			$showText = '删除';
			$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
			break;
		case 1 :
		default :
			$showText = '正常';
			$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/ok.png" WIDTH="20" HEIGHT="17" BORDER="0" ALT="正常">';

	}
	return ($imageShow) ?  $showImg  : $showText;
}
function is_Model($id, $imageShow = true){
	$ename = M('Node')->where('id='.$id)->getField('name');
	$is_model = M('Model')->where("ename='{$ename}'")->count() ? 1 : 0;
	if($imageShow===0) return $is_model;
	if($imageShow===1) return array($is_model,$ename);
	if($is_model){
		$showText = '是';
		$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/ok.png" WIDTH="20" HEIGHT="17" BORDER="0" ALT="是">';
	}else{
		$showText = '否';
		$showImg = '<IMG SRC="' . __PUBLIC__ . '/Images/error.png" WIDTH="20" HEIGHT="17" BORDER="0" ALT="否">';
	}
	return ($imageShow) ?  $showImg  : $showText;
}
function getDefaultStyle($style) {
	if (empty ( $style )) {
		return 'blue';
	} else {
		return $style;
	}

}
function IP($ip = '', $file = 'UTFWry.dat') {
	$_ip = array ();
	if (isset ( $_ip [$ip] )) {
		return $_ip [$ip];
	} else {
		import ( "ORG.Net.IpLocation" );
		$iplocation = new IpLocation ( $file );
		$location = $iplocation->getlocation ( $ip );
		$_ip [$ip] = $location ['country'] . $location ['area'];
	}
	return $_ip [$ip];
}

function getNodeGroupName($id) {
	if (empty ( $id )) {
		return '未分组';
	}
	$array = C('ADMIN_BMENU');
	return $array[$id];
}

function getCardStatus($status) {
	switch ($status) {
		case 0 :
			$show = '未启用';
			break;
		case 1 :
			$show = '已启用';
			break;
		case 2 :
			$show = '使用中';
			break;
		case 3 :
			$show = '已禁用';
			break;
		case 4 :
			$show = '已作废';
			break;
	}
	return $show;

}

function showStatus($status, $id, $module_name='', $callback='') {
	static $pkname =  '';
	$moduleName = $module_name ? $module_name : MODULE_NAME;
	$moduleURL = $module_name ? '__APP__/'.$module_name : '__URL__';
	if($pkname=='') $pkname = M($moduleName)->getPk();
	
	switch ($status) {
		case 0 :
			$info = '<a href="'.$moduleURL.'/resume/'.$pkname.'/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">审核</a>';
			break;
		case 2 :
			$info = '<a href="'.$moduleURL.'/pass/'.$pkname.'/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">批准</a>';
			break;
		case 1 :
			$info = '<a href="'.$moduleURL.'/forbid/'.$pkname.'/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">禁用</a>';
			break;
		case - 1 :
			$info = '<a href="'.$moduleURL.'/recycle/'.$pkname.'/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">还原</a>';
			break;
	}
	return $info;
}


function getGroupName($id) {
	if ($id == 0) {
		return '无上级组';
	}
	if ($list = F ( 'groupName' )) {
		return $list [$id];
	}
	$dao = D ( "Role" );
	$list = $dao->select( array ('field' => 'id,name' ) );
	foreach ( $list as $vo ) {
		$nameList [$vo ['id']] = $vo ['name'];
	}
	$name = $nameList [$id];
	F ( 'groupName', $nameList );
	return $name;
}
function sort_by($array, $keyname = null, $sortby = 'asc') {
	$myarray = $inarray = array ();
	# First store the keyvalues in a seperate array
	foreach ( $array as $i => $befree ) {
		$myarray [$i] = $array [$i] [$keyname];
	}
	# Sort the new array by
	switch ($sortby) {
		case 'asc' :
			# Sort an array and maintain index association...
			asort ( $myarray );
			break;
		case 'desc' :
		case 'arsort' :
			# Sort an array in reverse order and maintain index association
			arsort ( $myarray );
			break;
		case 'natcasesor' :
			# Sort an array using a case insensitive "natural order" algorithm
			natcasesort ( $myarray );
			break;
	}
	# Rebuild the old array
	foreach ( $myarray as $key => $befree ) {
		$inarray [] = $array [$key];
	}
	return $inarray;
}

function pwdHash($password, $type = 'md5') {
	return hash ( $type, $password );
}

/**
 * 逗号分隔的多个id。如果当前分类正好是其中一个,则返回当前分类id,否则返回首页分类id
 * @param $ids
 * @return first id
 */
function idsfirst($ids){
	if(is_numeric($_REQUEST['id']) && false!==strpos( ','.$ids.',' , ','.$_REQUEST['id'].',')) return $_REQUEST['id'];
	return (false!==($i=strpos($ids,','))) ? substr($ids,0,$i) : $ids;
}

/**
 * 根据ID获得分类名 id形如1或者2,3,4 后台有所不同
 * @param $id
 * @return string
 */
function getCategoryName($ids){
	if (empty ( $ids )) return '顶级分类';
	$list = M('Category')->field ( 'classtitle' )->where("classid in ($ids)")->select();
	foreach($list as $rs) $str .= ($str ? ',' : '').$rs['classtitle'];	
	return $str;
}
/**
 * 根据ID获得分类名 id形如1或者2,3,4 后台有所不同
 * @param $id
 * @return string
 */
function getCategoryModule($ids){
	if (empty ( $ids )) return '';
	$list = M('Category')->field ( 'classmodule' )->where("classid in ($ids)")->select();
	foreach($list as $rs) $str .= ($str ? ',' : '').$rs['classmodule'];	
	return $str;
}
//根据ID获得用户名
function getUserName($id){
	if (empty ( $id )) return '游客';
	if($id==1) return '超级管理员';	
	return D ('User')->where(array('id'=>$id))->getField ( 'nickname' );
}
//获取模块名称
function getModuleName($key){
	if (empty ( $key )) {
		return '未知模块';
	}
	$Category = D('Category')->getModule();
	return $Category[$key];
}
//获取上层节点ID
function getParentNodeId($pid){
	$id = D('Node')->where('id='.$pid)->getField('pid');
	return $id?$id:0;
}
//获取所有上级分类
function getParents($classid,$isall=1){
	$classpids = M('category')->where('classid='.$classid)->getField($isall ? 'classpids' : 'classpid');	
	return ($classpids!==false) ? ($isall ? explode(',',$classpids) : $classpids) : null;
}
/**
* @desc 替换成自己想显示的信息
* 格式:Replacestr(Rs("Audited"),"1:已通过审核,0:<span class=\"tx\">未通过审核</span>")
* @param $dbvalue 要选中的值
* @param $strlist 值:显示的值,值:显示的值... 
*/
function ReplaceStr($dbvalue, $strlist){
    $f_oldstr = $strlist;
    if( is_null($dbvalue)) $dbvalue = "";
    $f_tmparr = explode(",", $f_oldstr);       
    foreach( $f_tmparr as $f_tmpstr ){          
        $f_tmparr1 = explode(":", $f_tmpstr);
        if(count($f_tmparr1) == 2){
            if( trim($dbvalue) == trim($f_tmparr1[0]) ){
                $f_oldstr = trim($f_tmparr1[1]);
                break;
            }    
            elseif( trim($f_tmparr1[0]) == "else" )
                $f_oldstr = trim($f_tmparr1[1]);                
            else
                $f_oldstr = $dbvalue;
        }  
    }
    return $f_oldstr;
}

/**
* @desc 显示一组下拉Options
* 格式1:PrintOption(Rs("Audited"),"1:已通过审核,else:未通过审核")    
* 格式2:PrintOption(2,"1~10") 
* @param $EquValue  相等的值
* @param $valuelist 值:名称,值:名称...
*/   
function PrintOption($EquValue, $valuelist){
    $isselected = false; $f_oldstr = "";
    if(is_null($EquValue)) $EquValue = "";
    if(strpos($valuelist, "~")!==false){                 
        //1~10循环10个
        $arr = explode("~",$valuelist);    
        $intJ = $arr[0];$intK = $arr[1];                 
        $valuelist = "";
        if(is_numeric($intJ) && is_numeric($intK)){               
            for( $Inti = $intJ;$Inti<=$intK;$Inti++ ){                   
                $f_tmpstr = $f_tmpstr.",".$Inti;
            }    
            if($f_tmpstr != "") $valuelist = substr($f_tmpstr, 1);               
        }         
    }             
    $f_tmparr = explode(",",$valuelist);  
           
    foreach( $f_tmparr as $f_tmpstr ){            
        if(false===strpos($f_tmpstr, ":")) $f_tmpstr = $f_tmpstr.":".$f_tmpstr;
        $f_tmparr1 = explode(":",$f_tmpstr);         
        if(count($f_tmparr1) == 2){
            if( strpos(",".trim($EquValue).",", ",".trim($f_tmparr1[0]).",")!==false ){                  
                $f_oldstr .= "<option value=\"".$f_tmparr1[0]."\" selected>".$f_tmparr1[1]."</option>\r\n";
                $isselected = True;
            }
            elseif(trim($f_tmparr1[0]) == "else"){
                if( $EquValue != "" ){
                    $arr = explode(":",$f_tmparr(count($f_tmparr) - 1));
                    $intTmp = $arr[0];
                    if( !$isselected && is_numeric($intTmp) ){
                        if( intval($EquValue) != intval($intTmp) )
                            $f_oldstr .= "<option value=\"0\" selected>".$f_tmparr1[1]."</option>\r\n";
                        else
                            $f_oldstr .= "<option value=\"0\">".$f_tmparr1[1]."</option>\r\n";                     
                    }else
                        $f_oldstr .= "<option value=\"0\">".$f_tmparr1[1]."</option>\r\n";                  
                }else{
                    $f_oldstr .= "<option value=\"0\">".$f_tmparr1[1]."</option>\r\n";
                }
            } 
            else{
                $f_oldstr .= "<option value=\"".$f_tmparr1[0]."\">".$f_tmparr1[1]."</option>\r\n";
            }
        }
    }
    return $f_oldstr;
}

/**
* @desc 显示一组checkbox复选框 
* 格式1 PrintcBox("1:英语,2:法语","language")
* 格式2 PrintcBox("1~10","language:dis")   
  @param string 值:名称,值:名称...
  @param string 复选框name属性名
  @param string 第一个选项附加的验证字串
  @param string 选中的值 逗号分隔
  @param string name类型,1为name[]  2为name1,nam2  否则为name
*/
function PrintcBox($valuelist, $BName, $chkAttr='', $EquValue='', $nameType=1){
    $arr=NULL;$i=0;
    if(strpos($BName, ":dis")!==false){
        $f_disabled = " disabled";
        $arr = explode(":dis", $BName);
        $BName = $arr[0];
    }
    
    $isselected = false; $f_oldstr = "";
    if( strpos($valuelist, "~")!==false ){
        //1~10循环10个
        $arr = explode("~", $valuelist);
        $intJ = $arr[0];  $intK = $arr[1];
        $valuelist = "";
        if( is_numeric($intJ) && is_numeric($intK) ){
            for( $Inti = $intJ; $Inti <= $intK; $Inti++ ){
                $f_tmpstr = $f_tmpstr.",".$Inti;
            }
            if( $f_tmpstr != "" ) $valuelist = substr($f_tmpstr, 1);
        }
    }
    $f_tmparr = explode(",", $valuelist);

    foreach( $f_tmparr as $f_tmpstr ){
        $str_rand = ''; //uniqid();
    	if( false!==stripos($chkAttr, 'DataType=') || false!==stripos($chkAttr, 'class=') ){//验证字串则只加首个，否则所有都加
    		$chkAttr = ($i==0) ? $chkAttr : '';
    	}
		$f_tmpstr = trim($f_tmpstr);
        if( false===strpos($f_tmpstr, ":") ) $f_tmpstr = $f_tmpstr.":".$f_tmpstr;
        $f_tmparr1 = explode(":", $f_tmpstr);
        if( count($f_tmparr1) == 2 ){
			if($EquValue=='') $EquValue = htmlspecialchars($_REQUEST[$BName.$f_tmparr1[0]]); //直接得到这个变量的get,post值
            if( strpos(",$EquValue,", ",$f_tmparr1[0],")!==false ){
                $f_oldstr .= "<label><input name=\"".$BName.($nameType==1 ? '[]' : ($nameType==2 ? $f_tmparr1[0] : ''))."\" id=\"".$BName.$f_tmparr1[0].$str_rand."\" type=\"checkbox\" value=\"".$f_tmparr1[0]."\"".$f_disabled." checked{$chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                $isselected = true;
            }    
            elseif( trim($f_tmparr1[0]) == "else" ){
                if( $EquValue!='' ){
                    $arr = explode(":", $f_tmparr(count($f_tmparr) - 2));
                    $intTmp = $arr[0];
                    if( !$isselected && is_numeric($intTmp)){
                        if( intval($EquValue) != intval($intTmp) ){
                            $f_oldstr .= "<label><input name=\"".$BName.($nameType==1 ? '[]' : ($nameType==2 ? $f_tmparr1[0] : ''))."\" id=\"".$BName."_0\" type=\"checkbox\" value=\"0\"".$f_disabled." checked{$chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                        }else{
                            $f_oldstr .= "<label><input name=\"".$BName.($nameType==1 ? '[]' : ($nameType==2 ? $f_tmparr1[0] : ''))."\" id=\"".$BName."_0\" type=\"checkbox\" value=\"0\"".$f_disabled."{$chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                        }
                    }else{
                        $f_oldstr .= "<label><input name=\"".$BName.($nameType==1 ? '[]' : ($nameType==2 ? $f_tmparr1[0] : ''))."\" id=\"".$BName."_0\" type=\"checkbox\" value=\"0\"".$f_disabled."{$chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                    }
                }else{
                    $f_oldstr .= "<label><input name=\"".$BName.($nameType==1 ? '[]' : ($nameType==2 ? $f_tmparr1[0] : ''))."\" id=\"".$BName."_0\" type=\"checkbox\" value=\"0\"".$f_disabled."{$chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                }
            }elseif( trim($f_tmparr1[0]) . trim($f_tmparr1[1]) != "" ){
                $f_oldstr .= "<label><input name=\"".$BName.($nameType==1 ? '[]' : ($nameType==2 ? $f_tmparr1[0] : ''))."\" id=\"".$BName.$f_tmparr1[0].$str_rand."\" type=\"checkbox\" value=\"".$f_tmparr1[0]."\"".$f_disabled."{$chkAttr} />".$f_tmparr1[1]."</label>\r\n";
            }
        }
		$i++;
    }
    return $f_oldstr;
}

/**
* @desc 显示一组radio单选框 
* 格式1 PrintcRadio("1:英语,2:法语","language")
* 格式2 PrintcRadio("1~10","language:dis")   
  @param string 值:名称,值:名称...
  @param string 单选框name属性名
  @param string 第一个选项附加的验证字串
  @param string 选中的值
*/
function PrintRadio($valuelist, $BName, $chkAttr='', $EquValue=''){
    $arr=NULL;$i=0;
    if(strpos($BName, ":dis")!==false){
        $f_disabled = " disabled";
        $arr = explode(":dis", $BName);
        $BName = $arr[0];
    }
    
    $isselected = false; $f_oldstr = "";
    if( strpos($valuelist, "~")!==false ){
        //1~10循环10个
        $arr = explode("~", $valuelist);
        $intJ = $arr[0];  $intK = $arr[1];
        $valuelist = "";
        if( is_numeric($intJ) && is_numeric($intK) ){
            for( $Inti = $intJ; $Inti <= $intK; $Inti++ ){
                $f_tmpstr = $f_tmpstr.",".$Inti;
            }
            if( $f_tmpstr != "" ) $valuelist = substr($f_tmpstr, 1);
        }
    }
    $f_tmparr = explode(",", $valuelist);

    foreach( $f_tmparr as $f_tmpstr ){
    	if( false!==stripos($chkAttr, 'DataType=') || false!==stripos($chkAttr, 'class=') ){//验证字串则只加首个，否则所有都加
    		$chkAttr = ($i==0) ? ' '.trim($chkAttr) : '';
    	}
    	
		$f_tmpstr = trim($f_tmpstr);
        if( false===strpos($f_tmpstr, ":") ) $f_tmpstr = $f_tmpstr.":".$f_tmpstr;
        $f_tmparr1 = explode(":", $f_tmpstr);        
        if( count($f_tmparr1) == 2 ){
            if( strpos(",$EquValue,", ",$f_tmparr1[0],")!==false ){
            	$new_chkAttr = str_replace('{$value}', $f_tmparr1[0], $chkAttr);
                $f_oldstr .= "<label><input name=\"{$BName}\" id=\"".$BName.$f_tmparr1[0]."\" type=\"radio\" value=\"".$f_tmparr1[0]."\"".$f_disabled." checked{$new_chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                $isselected = true;
            }elseif( trim($f_tmparr1[0]) == "else" ){
            	$new_chkAttr = str_replace('{$value}', '0', $chkAttr);
                if( $EquValue!='' ){
                    $arr = explode(":", $f_tmparr(count($f_tmparr) - 2));
                    $intTmp = $arr[0];
                    if( !$isselected && is_numeric($intTmp)){
                        if( intval($EquValue) != intval($intTmp) ){
                            $f_oldstr .= "<label><input name=\"{$BName}\" id=\"".$BName."_0\" type=\"radio\" value=\"0\"".$f_disabled." checked{$new_chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                        }else{
                            $f_oldstr .= "<label><input name=\"{$BName}\" id=\"".$BName."_0\" type=\"radio\" value=\"0\"".$f_disabled."{$new_chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                        }
                    }else{
                        $f_oldstr .= "<label><input name=\"{$BName}\" id=\"".$BName."_0\" type=\"radio\" value=\"0\"".$f_disabled."{$new_chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                    }
                }else{
                    $f_oldstr .= "<label><input name=\"{$BName}\" id=\"".$BName."_0\" type=\"radio\" value=\"0\"".$f_disabled."{$new_chkAttr} />".$f_tmparr1[1]."</label>\r\n";
                }
            }elseif( trim($f_tmparr1[0]) . trim($f_tmparr1[1]) != "" ){
            	$new_chkAttr = str_replace('{$value}', $f_tmparr1[0], $chkAttr);
                $f_oldstr .= "<label><input name=\"{$BName}\" id=\"".$BName.$f_tmparr1[0]."\" type=\"radio\" value=\"".$f_tmparr1[0]."\"".$f_disabled."{$new_chkAttr} />".$f_tmparr1[1]."</label>\r\n";
            }
        }
		$i++;
    }
    return $f_oldstr;
}

/**
* @desc 判断日期时间格式是否正确
* @param datestring 日期字符串如2009-01-30 或 2009-01-30 10 或 2009-01-30 10:01 或 2009-01-30 10:00:01
* @param chktime 是否检查时间
* @return true or false
*/
function is_date($str, $chktime=true){    
    if( preg_match('/^(\d{2,4})-(\d{1,2})-(\d{1,2})$/',$str,$arr) ){ 
        return checkdate((int)$arr[2], (int)$arr[3], (int)$arr[1]);
    }
    
    if($chktime)
    if( preg_match('/^(\d{2,4})-(\d{1,2})-(\d{1,2}) (\d{1,2})(:(\d{1,2}))?(:(\d{1,2}))?$/',$str,$arr) ){
    	$isdate = checkdate((int)$arr[2], (int)$arr[3], (int)$arr[1]);
        if($isdate && $arr[4]>=0 && $arr[4]<=23) {//日期正确小时正确
        	if( !isset($arr[5]) && !isset($arr[7]) ){//没有分 秒
				return true;
        	}elseif( !isset($arr[7]) ) {//没有秒
        		if($arr[6]>=0 && $arr[6]<=59) return true;
        	}else{
        		if($arr[6]>=0 && $arr[6]<=59 && $arr[8]>=0 && $arr[8]<=59) return true;        		
        	}
        }
    }
 
    return false;
}

/*以下是根据文件大小获得flv格式的文件的总的时间长度的函数，单位s */
function BigEndian2Int($byte_word, $signed = false) {
	$int_value = 0;
	$byte_wordlen = strlen($byte_word);
	for ($i = 0; $i < $byte_wordlen; $i++) {
		$int_value += ord($byte_word{$i}) * pow(256, ($byte_wordlen - 1 - $i));
	}
	if ($signed) {
		$sign_mask_bit = 0x80 << (8 * ($byte_wordlen - 1));
		if ($int_value & $sign_mask_bit) {
			$int_value = 0 - ($int_value & ($sign_mask_bit - 1));
		}
	}
	return $int_value;
}


//获得视频的数字时间
function getTime($name){
    if(!file_exists($name)){
        return;
    }
    $flv_data_length=filesize($name);
    $fp = @fopen($name, 'rb');
    $flv_header = fread($fp, 5);
    fseek($fp, 5, SEEK_SET);
    $frame_size_data_length =BigEndian2Int(fread($fp, 4));
    $flv_header_frame_length = 9;
    if ($frame_size_data_length > $flv_header_frame_length) {
       fseek($fp, $frame_size_data_length - $flv_header_frame_length, SEEK_CUR);
    }
    $duration = 0;
    while ((ftell($fp) + 1) < $flv_data_length) {
         $this_tag_header     = fread($fp, 16);
         $data_length         = BigEndian2Int(substr($this_tag_header, 5, 3));
         $timestamp           = BigEndian2Int(substr($this_tag_header, 8, 3));
         $next_offset         = ftell($fp) - 1 + $data_length;
         if ($timestamp > $duration) {
          $duration = $timestamp;
         }
         fseek($fp, $next_offset, SEEK_SET);
    }
    fclose($fp);
    return $duration;
}

//转化为0：03：56的时间格式
function fn($time){
	$num = $time;
	$sec = intval($num/1000);
	$h = intval($sec/3600);
	$m = intval(($sec%3600)/60);
	$s = intval(($sec%60));
	$tm = $h.':'.$m.':'.$s;
	return $tm;
}
	
//传入正确的flv文件路径,例如:"baofeng.flv"
//获得flv文件的总秒数
function getsec($filename){
	if(end(explode('.',$filename))=='flv'){
		return intval(getTime($filename)/1000);
	}else{
		return '不是flv文件！';
	}
}

/*  获得flv格式总时间的所有函数结束 */

/**
 * 内容自动提前关键字
 * @param $title 当前文章关键字
 * @param $content 当前文章内容
 * @return string 返回替换后的内容
 */
 function autoget_keywords($title='',$content=''){
	if($title=='' && $content=='') return '';
	$file = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/plugin/pscws23/interface.php';
	if( is_file($file) ){
		require_once($file);
		$title = iconv('utf-8', 'gbk', $title);
		$content = iconv('utf-8', 'gbk', $content);
		$pscws_title = strip_tags($title);
		$pscws_content = strip_tags(preg_replace('/(<STYLE.*?>[\s\S]*?<\/STYLE>)|(&[a-z]+;)|\s/i','',$content));
		$keywords = get_hot_keywords($pscws_title . $pscws_content, 5);				
		return iconv('gbk', 'utf-8', $keywords);
	}
	return '插件未找到';
 }
 
 /**
 * 内容自动提取导读摘要
 * @param $content 当前文章内容
 * @return string 返回替换后的内容
 */
 function autoget_description($content=''){
	if($content=='') return '';
	 $pscws_content = strip_tags(preg_replace('/(<STYLE.*?>[\s\S]*?<\/STYLE>)|(&[a-z]+;)|\s/i','',$content));
	 $pscws_content = str_replace(array("\r","\n"), '', $pscws_content);
	 return msubstr($pscws_content, 0, 200, 'utf-8', '');
 }