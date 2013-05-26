<?php
class CommonModel extends Model {
	public function getPosition($id){
		$type = D('Category')->where('status=1')->find($id);
		if($type['pid']==0){
			$position = $id;
		}else{
			$position = $type['pid'];
		}
		return $position;
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
			$list = D('Category')->where('status=1')->field('id,pid')->where('id in ('.$ids.')')->select();
				foreach($list as $rs){
					$map['_string'] .= ' or find_in_set('.$rs['id'].', tid)';
					if($haveChild && $rs['pid']==0){ //大栏目
						$types = D('Category')->where('status=1 AND pid='.$rs['id'])->field('id')->select();
						if(is_array($types)){
							foreach($types as $val) $map['_string'] .= ' or find_in_set('.$val['id'].', tid)';					
						}
					}
				}
		}
		if(isset($map['_string'])) $map['_string'] = '('.ltrim($map['_string'], ' or ').')'; 
		return $map;
	}   
}