<?php
// 分类模型
class CategoryModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('classtitle','require','分类名必填！',1),
		//array('classtitle','','分类名已存在！',0,'unique'),
	);
	public function getModule(){
		$modules = array();
		$list = M('Model')->field('ename,cname')->select();
		foreach($list as $rs){
			$modules[$rs['ename']] = $rs['cname'];
		}
		return $modules;
	}
	
	/**
	 * 更新指定目录及其一条线上的所有栏目的classpid,classpids,classchild,classchildids字段
	 * @param $id
	 * @param $pid id对应的父级
	 * @return array:classpids,classchildids
	 */
	public function setParentsChilds($id, $pid=NULL){
		if(!is_numeric($id)) return NULL;
		if($pid===NULL) $pid = M('Category')->where('classid='.$id)->getField('classpid');
		/**修改所有(旧的+新的)父级包括自己的childids**/
		//旧的
		$classRs = M('Category')->field('classpids,classmodule')->where('classid='.$id)->find();
		$old_pids = $classRs['classpids']; //$old_module = $classRs['classmodule'];
		$classRs = $this->get_pids($pid);//新的
		$new_pids = $classRs['classpids']; 
		//$new_module = is_null($classRs['classmodule'])? $old_module : $classRs['classmodule'];
		M('Category')->where('classid='.$id)->setField(array('classpid'=>$pid,'classpids'=>$new_pids)); //先更新自己的parent    //'classmodule'=>$new_module,
		$all_pids = ($old_pids!=$new_pids) ? trim($new_pids.','.$old_pids, ',') : $old_pids;
		$all_pids .= ','.$id;//包括自己	
		$tmparr = explode(',', trim('0,'.$all_pids,','));			
		for($i=count($tmparr),$n=0; $i>=$n; $i--){//由低到高设置，顺序很重要
			if( ($val=$tmparr[$i])>0 ){//赋值再判断是否>0
				$data = $this->get_childids($val); //子级
				M('Category')->where('classid='.$val)->setField($data);
			}
		}
		/**修改所有新的子级不包括自己(上面已更新过)的pids**/	
		unset($data,$tmparr);
		$classRs = M('Category')->field('classchildids')->where('classid='.$id)->find();
		$tmparr = explode(',', $classRs['classchildids']);
		for($i=1,$n=count($tmparr); $i<$n; $i++){//由高到低设置，顺序很重要			
			$data = $this->get_pids($tmparr[$i],'classid'); //传入自己获得父级的pids	
			//$data['classmodule'] = $new_module; //模型保持一致
			M('Category')->where('classid='.$tmparr[$i])->setField($data);
		}
		unset($old_pids,$new_pids,$data,$tmparr);
		return array('classpids'=>$new_pids, 'classchildids'=>$classRs['childids']);
	}	
	
	/**
	 * 得到需要设置的父级的 classpids 字串(目录保存时用)
	 * @param $theid  classid or classpid
	 * @param $db
	 * @param $theidType classpid or classid
	 * @return array('classpids'=>'parentlist','classmodule'=>module) 
	 */
	public function get_pids($theid, $theidType='classpid'){
		if(empty($theid)) return array('classpids'=>''); //根目录返回空   //, 'classmodule'=>null
		if( $theidType!='classpid' ){//传入参数是自己
			$theid = M('Category')->where('classid='.$theid)->getField('classpid');
		}
		$tmp = M('Category')->where('classid='.$theid)->field('classpids')->find();   //,classmodule
		$pids = $tmp['classpids'] ? preg_replace('/(,'.$theid.')+$/','',$tmp['classpids']).','.$theid : "0,$theid";	
		return array('classpids'=>$pids); //, 'classmodule'=>$tmp['classmodule']		
	}
	
	/**
	 * 得到需要设置的子类 classchildids 字串(目录保存时用)
	 * @param $id
	 * @return array('classchildids'=>'childlist','classchild'=>child) 
	 */
	public function get_childids($id){
		if(empty($id)) return array('classchildids'=>'', 'classchild'=>0);
		$child = 0; $str = $id; $arr = array($id);  
		$list = M('Category')->where('classpid='.$id)->field('classid,classtitle,classchildids,classarrchildids,classchild, classmodule')->order('classsort desc,classid asc')->select();
		if($list){
			$child = 1;
			foreach($list as $rs){
				$str .= ($str ? ','  : '').($rs['classchildids'] ? $rs['classchildids'] : $rs['classid']);
				$first = array(array('classid'=>$rs['classid'],'classtitle'=>$rs['classtitle'],'classchild'=>$rs['classchild'] ,'classmodule'=>$rs['classmodule']));
				$arr[] = $rs['classarrchildids'] ? array_merge( $first, unserialize($rs['classarrchildids']) ) : $first;
			} 				
		}
		return array( 'classchild'=>$child, 'classchildids'=>$str, 'classarrchildids'=>serialize($arr));
	} 		
	
	/**
	 * 输出栏目的树形菜单HTML
	 * @param $map
	 * @param $link  edit（修改栏目时）,info_addview（左侧菜单栏）,空表示默认选择栏目
	 * @param $selparent 是否允许选择父栏目
	 * @return string html <ul><li>....</li></ul>
	 */
	public function tree($map=array('classpid'=>0),$link='',$selparent=''){
		$return = '';
		if($link==''){
			if($_REQUEST['cid']!='' || $_REQUEST['cname']!=''){ //附加“清空”按钮
				$return .= '<ul class="tree treeFolder">';
				$return .= '	<li><a href="javascript:;" style="color:red" onclick="$(\'#'.$_REQUEST['cid'].'\').val(\'\');$(\'#'.$_REQUEST['cname'].'\').val(\'\');">清空重置</a></li>';
				$return .= '</ul>';
			}
		}
		$list = M('Category')->field('classid,classpid,classtitle,classchild,classarrchildids, classmodule')->where($map)->select();
		if($list){
			if($link=='info_addview'){
				$return .= '<ul>';
			}else{
				$return .= '<ul class="tree treeFolder expand collapse">';
			}
			foreach($list as $rs){
				if($rs['classpid']==0){
					if($link=='edit'){
						$strlink = '<a class="edit" href="__APP__/Category/edit/classid/'.$rs['classid'].'" target="dialog" width="700" height="550" rel="'.$rs['classid'].'">'.$rs['classtitle'].'</a>';
					}elseif($link=='info_addview'){
						$strlink = '<a class="info_addview" href="__APP__/'.$rs['classmodule'].'/index/tid/'.$rs['classid'].'" target="navTab" rel="'.$rs['classmodule'].'">'.$rs['classtitle'].'</a>';						
					}else{
						$strlink = '<a href="javascript:;"'.(($selparent || !$rs['classchild']) ? ' onclick="selectClass(\''.$rs['classid'].'\',\''.$rs['classtitle'].'\');"' : '').'>'.$rs['classtitle'].'</a>';
					}					
					if($rs['classchild']==0){
						$return .= '<li>'.$strlink.'</li>';
					}else{
						$return .= '<li>'.$strlink;
						$return .= $this->_for_tree($rs['classarrchildids'], $link, $selparent);
						$return .= '</li>';
					}
				}			
			}
			$return .= '</ul>';
		}
		return $return;
	}
	//输出子栏目的树菜单
	function _for_tree($arrchildids,$link='',$selparent=''){
		if(empty($arrchildids)) return;
		$strchild = '';
		if(!is_array($arrchildids)) $arrchildids = unserialize($arrchildids);
		for($i=0,$n=count($arrchildids); $i<$n; $i++){
			if(is_array(($data = $arrchildids[$i]))){
				$classmodule = $data[0]['classmodule'] ? $data[0]['classmodule'] : M('Category')->where(array('classid'=>$data[0]['classid']))->getField('classmodule');
				if($link=='edit'){
					$strlink = '<a class="edit" href="__APP__/Category/edit/classid/'.$data[0]['classid'].'" target="dialog" width="700" height="550" rel="'.$data[0]['classid'].'">'.$data[0]['classtitle'].'</a>';
				}elseif($link=='info_addview'){
					$strlink = '<a class="info_addview" href="__APP__/'.$classmodule.'/index/tid/'.$data[0]['classid'].'" target="navTab" rel="'.$classmodule.'">'.$data[0]['classtitle'].'</a>';	
				}else{
					$strlink = '<a href="javascript:;"'.(($selparent || !$data[0]['classchild']) ? ' onclick="selectClass(\''.$data[0]['classid'].'\',\''.$data[0]['classtitle'].'\');"' : '').'>'.$data[0]['classtitle'].'</a>';
				}				
				$strchild .= '<li>'.$strlink;
				//若还有子类则输出 <ul>......</ul>
				$strchild .= $this->_for_tree( array_slice($arrchildids[$i],2), $link, $selparent );
				$strchild .= '</li>';
			}	
		}
		return ($strchild!='') ? '<ul>'.$strchild.'</ul>' : '';
	}
}