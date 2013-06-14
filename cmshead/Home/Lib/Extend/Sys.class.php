<?php
//系统自带，基础功能或其他小系统如留言、评论等的模板扩展 awen
class Sys{
	public $page;
	
	function __construct(){
				
	}	
		
	/**
	 * 分页取数据 ch2函数的取分页的写法
	 * 取列表时和ch2写法一样，只是多加上page:条数，取page时参数为空即可。
	 * @return array
	 */
	function page(){
		$args = func_get_args();
		if(!$args) return $this->page;
		if(CommonAction::$map) array_push($args, 'where:@'.serialize(CommonAction::$map));
		$data = call_user_func_array('ch2', $args); 
		if(!is_array($data)) return $data;
		if( isset($data['page']) ) $this->page = $data['page'];
		return $data['list'];
	}
	
	//你的位置，位置导航html
	function position($id=0,$space=NULL){
		$id = $id ? $id : (CommonAction::$App['vars']['id'] ? CommonAction::$App['vars']['id'] : $_GET['id']);
		if(is_null($space)) $space = '&nbsp;<span class="gt"></span>&nbsp;';
		//取得当前控制器类名
		$appArr = CommonAction::$App;
		if(CommonAction::$pos!='') return $space.CommonAction::$pos; //直接的位置的中文赋值
			
		if($appArr['app'][0]=='index'){
			 switch($appArr['app'][1]){
			 	case 'index':
			 		return '';
			 		break;
			 	case 'diary':
			 		return $space.'日记';
			 		break;
			 	default:
			 		return $space.$appArr['app'][1];
			 		break;
		 	}
		} 
		if(!is_numeric($id)){
			if($appArr['app'][2]=='id' && is_numeric($appArr['app'][3])){
				$id = $appArr['app'][3];
			}else{
				return '';
			}	
		} 
		$return = array();
		if($appArr['app'][1]=='index'){ //id属于栏目
			
		}else{ //id属于内容
			$id = M($appArr['app'][0])->where("id=$id")->getField('tid');
		}
		//自身
		$rs = M('Category')->where("classid in ($id)")->find();
		$rs['classmethod'] = ucfirst($appArr['app'][0]).'/index'; // ucfirst($arr['app'][0]).'/'.$arr['app'][1];
		$rs = changurl($rs);
		//父辈
		if($rs['classpids']!=''){
			$arr = explode(',', $rs['classpids']);
			foreach($arr as $v){
				if($v){
					$rs2 = M('Category')->where("classid=$v")->find();
					$rs2['classmethod'] = $rs['classmethod'];
					$rs2 = changurl($rs2);
					$return[] = '<a href="'.$rs2['url'].'">'.$rs2['classtitle'].'</a>';
				}
			}
		}
		$return[] = '<a href="'.$rs['url'].'">'.$rs['classtitle'].'</a>';
		if($appArr['app'][1]!='index'){
			$return[] = '正文';			
		}
		return $space.implode($space, $return);
	}
	
	/**
	 * 输出栏目的下拉菜单
	 * @param $map where条件 可直接指定模块名（模块映射的别名）
	 * @param $params 下拉菜单的附加参数
	 * @return select html
	 */
	function classSelect($map=array('classpid'=>0),$params=''){
		$return = '';
		if(is_string($map) && preg_match('/[^=<>!%\'"]/',$map)){
			$tmp = is_array(C('CMSHEAD_KEY_ROUTER')) ? C('CMSHEAD_KEY_ROUTER') : array();
			if( array_key_exists($map, $tmp) ){
				$map = $tmp[$map];
			}
			$map=array('classmodule'=>$map);
		} 
		$list = M('Category')->field('classid,classpid,classtitle,classchild,classarrchildids')->where($map)->select();
		if($list){
			$return .= '<select '.trim($params).'>';
			foreach($list as $rs){
				if($rs['classpid']==0){
					$return .= '<option value="'.$rs['classid'].'">┌'.$rs['classtitle'].'</option>';
					$return .= Sys::_for_classSelect($rs['classarrchildids']);
				}			
			}
			$return .= '</select>';
		}
		return $return;
	}
	//输出栏目的下拉菜单
	function _for_classSelect($arrchildids, $space=''){
		if(empty($arrchildids)) return;
		$strchild = '';
		if(!is_array($arrchildids)) $arrchildids = unserialize($arrchildids);
		for($i=0,$n=count($arrchildids); $i<$n; $i++){
			if(is_array(($data = $arrchildids[$i]))){
				$strchild .= '<option value="'.$data[0]['classid'].'">├'.$space.$data[0]['classtitle'].'</option>';				
				$strchild .= Sys::_for_classSelect( array_slice($arrchildids[$i],2), $space.'─' );
			}	
		}
		return $strchild;
	}
	//最新留言、评论 可带分页或缓存
	function newmessage($type='ly',$cache='600',$page=''){
		$return = array();
		//最新留言
		if($type=='ly'){
			if(!empty($page)){
				$return = Sys::page('Message','where:status=1 AND pid=0 AND !modekeyvalue','add_time DESC','page:'.$page);				
			}else{
				if(false!==strpos($cache,':')){
					$arr = explode(':',$cache);
					$cName = $arr[0]; $cTime = $arr[1];
				}else{
					$cName = 'Common_liuyan'; $cTime = $cache;
				}
				if( !($return=S($cName)) ){
					$return = M('Message')->where('status=1 AND pid=0 AND !modekeyvalue')->order('add_time DESC')->limit(5)->select();					
					S($cName, $return, $cTime);
				}
			}
			return $return;
		}elseif($type=='pl'){
			//最新评论
			if(!empty($page)){
				$return = Sys::page('Message','where:status=1 AND pid=0 AND modekeyvalue','add_time DESC','page:'.$page);				
			}else{
				if(false!==strpos($cache,':')){
					$arr = explode(':',$cache);
					$cName = $arr[0]; $cTime = $arr[1];
				}else{
					$cName = 'Common_pinglun'; $cTime = $cache;
				}
				if( !($return=S($cName)) ){
					$return = M('Message')->where('status=1 AND pid=0 AND modekeyvalue')->order('add_time DESC')->limit(5)->select();					
					S($cName, $return, $cTime);
				}
			}
			return $return;
		}
	}	
}