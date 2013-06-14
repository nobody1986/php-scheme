<?php
class CommonModel extends Model {
	public function getPosition($id){
		$type = M('Category')->where('classstatus=1')->find($id);
		return $type['classpid'] ? $type['classpid'] : $id;
	}
    
	/**
	 * 生成分类查询条件，同一篇文章可以属于多个分类 tid形如2,3,4
	 * @param $ids
	 * @param $haveChild
	 * @return $map['_string']
	 */
	public function getCategoryMap($ids, $haveChild=1){
		$map = array();
		if(preg_match('/^\d+(,\d+)*$/', $ids)){			
			$list = M('Category')->where('classstatus=1')->field('classid,classchildids')->where('classid in ('.$ids.')')->select();
				foreach($list as $rs){
					if($haveChild){
						foreach(explode(',',$rs['classchildids']) as $val) $map['_string'] .= ' or find_in_set('.$val.', tid)';
					}else{
						$map['_string'] .= ' or find_in_set('.$rs['classid'].', tid)';					
					}
				}
		}
		if(isset($map['_string'])) $map['_string'] = ltrim($map['_string'], ' or '); 
		return $map;
	}    
}