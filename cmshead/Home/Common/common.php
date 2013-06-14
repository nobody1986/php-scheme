<?php
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
		$val = msubstr(preg_replace('/\s|&[a-zA-Z]+;|\[CHPAGE\]/i','',strip_tags($val)), 0 ,$strlen);		
	} 
	$arr = explode(' ', $keyword);
	foreach($arr as $v){
		$v = trim($v);
		if($v!='') $val=str_ireplace($v,"<font color=red><b>{$v}</b></font>",$val);		
	}
	return $val;
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

//根据ID获得分类名 id形如1或者2,3,4
function getCategoryName($id){
	if (empty ( $id )) return '顶级分类';
	return M('Category')->where(array('classid'=>idsfirst($id)))->getField ( 'classtitle' );
}
//根据ID获得模型名
function getModuleById($id){
	return M('Category')->where(array('classid'=>idsfirst($id)))->getField ( 'classmodule' );
}
//根据ID获得用户名
function getUserName($id){
	if (empty ( $id )) return '游客';
	return M('User')->where(array('id'=>$id))->getField ( 'nickname' );
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
 * 内容自动为关键字加内链
 * @param $content 当前文章内容
 * @return string 返回替换后的内容
 */
 function replace_key($content=''){
 	if(empty($content)) return $content;
	$keyarr = M('Key')->where('status=1')->order('sort desc,length(keyname) DESC,id desc')->limit(100)->select();
	$arr = array();
	foreach($keyarr as $k=>$v){
		$arr[] = array($v['keyname'],$v['url']);
	}
	if(!empty($arr)){
		$content = noreapeat_key($content,$arr);
	}
	return $content;
}

/**
 * 不重复替换关键字
 * @param $keywords 当前文章内容
 * @param $modulname 要替换的关键字数组，例如:array(array('优化', 'http://www.baidu.com'),array('google', 'http://www.google.com')); 
 * @return $str 替换后的内容
 */
function noreapeat_key($str, $key_array){  
	$array_first = $key_array; 
	$array_last = array();  
	foreach($array_first as $key=>$value){  
		 $array_last[$key] = array('TFXS00' . $key, $value[0], '<a target="_blank" class="contentkeyword" href="' . $value[1] . '" title="' . $value[0] . '">' . $value[0] . '</a>');  
	}  
	$count = count($array_last);  
	for($i=0;$i<$count;$i++){
		for($j=$count-1;$j>$i;$j--){  
			//如果后一个元素长度大于前一个则调换位置  
			if(strlen($array_last[$j][1]) > strlen($array_last[$j-1][1])){  
				$tmp = $array_last[$j];
				$array_last[$j] = $array_last[$j-1];  
				$array_last[$j-1] = $tmp;  
			 }
		}  
	} 
	 $keys = $array_last;  
	 foreach($keys as $nkeys){  
		$str = str_ireplace($nkeys[1], $nkeys[0], $str);
	 }
	 foreach($keys as $nkeys){  
		$str = str_ireplace($nkeys[0], $nkeys[2], $str);
	 }  
	 return $str;  
}