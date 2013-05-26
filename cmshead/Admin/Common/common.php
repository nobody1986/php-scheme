<?php
//公共函数

/**
 * 根据字段与搜索域对应关系生成查询条件 
 * 返回支持空格的关键字查询 
 * 参数如：
 * $where = getSearchMap(array('title'=>'keyword','content'=>'keyword'));
   $where['_logic'] = 'OR';
   $map['_complex'] = $where;
 * @param $fieldMap
 * @return array map
 */
function getSearchMap($fieldMap){
	$map = array();
	if(is_array($fieldMap))
	foreach ( $fieldMap as $key => $val ) {		
		if (isset ( $_REQUEST [$val] ) && ($keyword = trim(htmlspecialchars($_REQUEST [$val]))) != '') {
			$mapkey = is_numeric($key) ? $val : $key;
			if(false!==strpos($_REQUEST [$val],' ')){
				$childstr = ''; $childarr = array();
				$arr = explode(' ', $keyword);
				foreach($arr as $v){
					$v = trim($v);
					if($v!=''){
						$childstr .= ($childstr!='' ? '&' : '') . $mapkey;
						$childarr[] = $v;
					}
				}
				if($childarr){
					$childarr['_multi'] = true;
					$map[$childstr]	= $childarr;					
				} 				
			}else{
				$map [$mapkey] = $keyword;
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
		if($v!='') $val=str_replace($v,"<font color=red><b>{$v}</b></font>",$val);		
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

function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}

// 缓存文件
function cmssavecache($name = '', $fields = '') {
	$Model = D ( $name );
	$list = $Model->select ();
	$data = array ();
	foreach ( $list as $key => $val ) {
		if (empty ( $fields )) {
			$data [$val [$Model->getPk ()]] = $val;
		} else {
			// 获取需要的字段
			if (is_string ( $fields )) {
				$fields = explode ( ',', $fields );
			}
			if (count ( $fields ) == 1) {
				$data [$val [$Model->getPk ()]] = $val [$fields [0]];
			} else {
				foreach ( $fields as $field ) {
					$data [$val [$Model->getPk ()]] [] = $val [$field];
				}
			}
		}
	}
	$savefile = cmsgetcache ( $name );
	// 所有参数统一为大写
	$content = "<?php\nreturn " . var_export ( array_change_key_case ( $data, CASE_UPPER ), true ) . ";\n?>";
	file_put_contents ( $savefile, $content );
}

function cmsgetcache($name = '') {
	return DATA_PATH . '~' . strtolower ( $name ) . '.php';
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

function getNodeName($id) {
	if (Session::is_set ( 'nodeNameList' )) {
		$name = Session::get ( 'nodeNameList' );
		return $name [$id];
	}
	$Group = D ( "Node" );
	$list = $Group->getField ( 'id,name' );
	$name = $list [$id];
	Session::set ( 'nodeNameList', $list );
	return $name;
}

function get_pawn($pawn) {
	if ($pawn == 0)
		return "<span style='color:green'>没有</span>";
	else
		return "<span style='color:red'>有</span>";
}
function get_patent($patent) {
	if ($patent == 0)
		return "<span style='color:green'>没有</span>";
	else
		return "<span style='color:red'>有</span>";
}


function getNodeGroupName($id) {
	if (empty ( $id )) {
		return '未分组';
	}
	if (isset ( $_SESSION ['nodeGroupList'] )) {
		return $_SESSION ['nodeGroupList'] [$id];
	}
	$Group = D ( "Group" );
	$list = $Group->getField ( 'id,title' );
	$_SESSION ['nodeGroupList'] = $list;
	$name = $list [$id];
	return $name;
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

function showStatus($status, $id, $callback="") {
	switch ($status) {
		case 0 :
			$info = '<a href="__URL__/resume/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">恢复</a>';
			break;
		case 2 :
			$info = '<a href="__URL__/pass/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">批准</a>';
			break;
		case 1 :
			$info = '<a href="__URL__/forbid/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">禁用</a>';
			break;
		case - 1 :
			$info = '<a href="__URL__/recycle/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="'.$callback.'">还原</a>';
			break;
	}
	return $info;
}

/**
 +----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
 +----------------------------------------------------------
 * @param string $fmode 文件名
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function build_verify($length = 4, $mode = 1) {
	return rand_string ( $length, $mode );
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

/**
	 +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
	 +----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
	 +----------------------------------------------------------
 * @return string
	 +----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '') {
	$str = '';
	switch ($type) {
		case 0 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		case 1 :
			$chars = str_repeat ( '0123456789', 3 );
			break;
		case 2 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
			break;
		case 3 :
			$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		default :
			// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
			$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
			break;
	}
	if ($len > 10) { //位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat ( $chars, $len ) : str_repeat ( $chars, 5 );
	}
	if ($type != 4) {
		$chars = str_shuffle ( $chars );
		$str = substr ( $chars, 0, $len );
	} else {
		// 中文随机字
		for($i = 0; $i < $len; $i ++) {
			$str .= msubstr ( $chars, floor ( mt_rand ( 0, mb_strlen ( $chars, 'utf-8' ) - 1 ) ), 1 );
		}
	}
	return $str;
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
	$ids = is_array($ids) ? $ids : explode(',', $ids);
	$list = D ('Category')->field ( 'id,title' )->cache('getCategoryName',3600)->select();
	foreach($list as $rs){
		if(in_array($rs['id'], $ids)) $str .= ($str ? ',' : '').$rs['title'];
	} 
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


/**
* 遍历目录所有文件
* 
* @param string $path 遍历的绝对路径
* @param string $filtext 过滤的文件格式
* @param boolean $havedir 是否包含扫描到的目录
* @return array 所有文件
*/
function get_allfiles( $path, $filtext=null, $havedir=true ){
    $list = array();
    foreach( glob( $path . '/*') as $item ){
        if( is_dir( $item ) ){
        	if($havedir) $list[] = $item;
            $list = array_merge( $list , get_allfiles( $item, $filtext ) );
        }
        else{   
            if(is_string($filtext)){     
                if(preg_match('/\.('.$filtext.')$/i', $item)) $list[] = $item;
            }
            else{
                $list[] = $item;
            }
        }
    }
    return $list;
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
  @param string editVal!='`~NULL'则name名称为同一组 若没有值需要传入''
*/
function PrintcBox($valuelist, $BName, $chkAttr='', $editVal='`~NULL', $isidrand=false){
    $arr=NULL;$i=0; $isEqu = ($editVal!=='`~NULL'); 
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
        $str_rand = $isidrand ? new_rand(10) : '';
    	if( false!==stripos($chkAttr, 'DataType=') ){//验证字串则只加首个，否则所有都加
    		$chkAttr = ($i==0) ? $chkAttr : '';
    	}
		$f_tmpstr = trim($f_tmpstr);
        if( false===strpos($f_tmpstr, ":") ) $f_tmpstr = $f_tmpstr.":".$f_tmpstr;
        $f_tmparr1 = explode(":", $f_tmpstr);
        if( count($f_tmparr1) == 2 ){
			$EquValue = $isEqu ? $editVal : $GLOBALS[$BName.$f_tmparr1[0]]; //直接得到这个变量
            if( strpos(",$EquValue,", ",$f_tmparr1[0],")!==false ){
                $f_oldstr .= "<input name=\"".$BName.($isEqu ? '[]' : $f_tmparr1[0])."\" id=\"".$BName.$f_tmparr1[0].$str_rand."\" type=\"checkbox\" value=\"".$f_tmparr1[0]."\"".$f_disabled." checked{$chkAttr} /><label for=\"".$BName.$f_tmparr1[0]."\">".$f_tmparr1[1]."</label>\r\n";
                $isselected = true;
            }    
            elseif( trim($f_tmparr1[0]) == "else" ){
                if( $EquValue!='' ){
                    $arr = explode(":", $f_tmparr(count($f_tmparr) - 2));
                    $intTmp = $arr[0];
                    if( !$isselected && is_numeric($intTmp)){
                        if( intval($EquValue) != intval($intTmp) ){
                            $f_oldstr .= "<input name=\"".$BName.($isEqu ? '[]' : $f_tmparr1[0])."\" id=\"".$BName."_0\" type=\"checkbox\" value=\"0\"".$f_disabled." checked{$chkAttr} /><label for=\"".$BName."_0\">".$f_tmparr1[1]."</label>\r\n";
                        }else{
                            $f_oldstr .= "<input name=\"".$BName.($isEqu ? '[]' : $f_tmparr1[0])."\" id=\"".$BName."_0\" type=\"checkbox\" value=\"0\"".$f_disabled."{$chkAttr} /><label for=\"".$BName."_0\">".$f_tmparr1[1]."</label>\r\n";
                        }
                    }else{
                        $f_oldstr .= "<input name=\"".$BName.($isEqu ? '[]' : $f_tmparr1[0])."\" id=\"".$BName."_0\" type=\"checkbox\" value=\"0\"".$f_disabled."{$chkAttr} /><label for=\"".$BName."_0\">".$f_tmparr1[1]."</label>\r\n";
                    }
                }else{
                    $f_oldstr .= "<input name=\"".$BName.($isEqu ? '[]' : $f_tmparr1[0])."\" id=\"".$BName."_0\" type=\"checkbox\" value=\"0\"".$f_disabled."{$chkAttr} /><label for=\"".$BName."_0\">".$f_tmparr1[1]."</label>\r\n";
                }
            }elseif( trim($f_tmparr1[0]) . trim($f_tmparr1[1]) != "" ){
                $f_oldstr .= "<input name=\"".$BName.($isEqu ? '[]' : $f_tmparr1[0])."\" id=\"".$BName.$f_tmparr1[0].$str_rand."\" type=\"checkbox\" value=\"".$f_tmparr1[0]."\"".$f_disabled."{$chkAttr} /><label for=\"".$BName.$f_tmparr1[0]."\">".$f_tmparr1[1]."</label>\r\n";
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
    	if( false!==stripos($chkAttr, 'DataType=') ){//验证字串则只加首个，否则所有都加
    		$chkAttr = ($i==0) ? $chkAttr : '';
    	}
    	
		$f_tmpstr = trim($f_tmpstr);
        if( false===strpos($f_tmpstr, ":") ) $f_tmpstr = $f_tmpstr.":".$f_tmpstr;
        $f_tmparr1 = explode(":", $f_tmpstr);        
        if( count($f_tmparr1) == 2 ){
            if( strpos(",$EquValue,", ",$f_tmparr1[0],")!==false ){
            	$new_chkAttr = str_replace('{$value}', $f_tmparr1[0], $chkAttr);
                $f_oldstr .= "<input name=\"{$BName}\" id=\"".$BName.$f_tmparr1[0]."\" type=\"radio\" value=\"".$f_tmparr1[0]."\"".$f_disabled." checked{$new_chkAttr} /><label for=\"".$BName.$f_tmparr1[0]."\">".$f_tmparr1[1]."</label>\r\n";
                $isselected = true;
            }elseif( trim($f_tmparr1[0]) == "else" ){
            	$new_chkAttr = str_replace('{$value}', '0', $chkAttr);
                if( $EquValue!='' ){
                    $arr = explode(":", $f_tmparr(count($f_tmparr) - 2));
                    $intTmp = $arr[0];
                    if( !$isselected && is_numeric($intTmp)){
                        if( intval($EquValue) != intval($intTmp) ){
                            $f_oldstr .= "<input name=\"{$BName}\" id=\"".$BName."_0\" type=\"radio\" value=\"0\"".$f_disabled." checked{$new_chkAttr} /><label for=\"".$BName."_0\">".$f_tmparr1[1]."</label>\r\n";
                        }else{
                            $f_oldstr .= "<input name=\"{$BName}\" id=\"".$BName."_0\" type=\"radio\" value=\"0\"".$f_disabled."{$new_chkAttr} /><label for=\"".$BName."_0\">".$f_tmparr1[1]."</label>\r\n";
                        }
                    }else{
                        $f_oldstr .= "<input name=\"{$BName}\" id=\"".$BName."_0\" type=\"radio\" value=\"0\"".$f_disabled."{$new_chkAttr} /><label for=\"".$BName."_0\">".$f_tmparr1[1]."</label>\r\n";
                    }
                }else{
                    $f_oldstr .= "<input name=\"{$BName}\" id=\"".$BName."_0\" type=\"radio\" value=\"0\"".$f_disabled."{$new_chkAttr} /><label for=\"".$BName."_0\">".$f_tmparr1[1]."</label>\r\n";
                }
            }elseif( trim($f_tmparr1[0]) . trim($f_tmparr1[1]) != "" ){
            	$new_chkAttr = str_replace('{$value}', $f_tmparr1[0], $chkAttr);
                $f_oldstr .= "<input name=\"{$BName}\" id=\"".$BName.$f_tmparr1[0]."\" type=\"radio\" value=\"".$f_tmparr1[0]."\"".$f_disabled."{$new_chkAttr} /><label for=\"".$BName.$f_tmparr1[0]."\">".$f_tmparr1[1]."</label>\r\n";
            }
        }
		$i++;
    }
    return $f_oldstr;
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