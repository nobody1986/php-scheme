<?php
class CommonModel extends Model {

	// 获取当前用户的ID
    public function getMemberId() {
        return isset($_SESSION[C('USER_AUTH_KEY')])?$_SESSION[C('USER_AUTH_KEY')]:0;
    }

   /**
     +----------------------------------------------------------
     * 根据条件禁用表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function forbid($options,$field='status'){

        if(FALSE === $this->where($options)->setField($field,0)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

	 /**
     +----------------------------------------------------------
     * 根据条件批准表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */

    public function checkPass($options,$field='status'){
        if(FALSE === $this->where($options)->setField($field,1)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }


    /**
     +----------------------------------------------------------
     * 根据条件恢复表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function resume($options,$field='status'){
        if(FALSE === $this->where($options)->setField($field,1)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

    /**
     +----------------------------------------------------------
     * 根据条件恢复表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function recycle($options,$field='status'){
        if(FALSE === $this->where($options)->setField($field,0)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

    public function recommend($options,$field='is_recommend'){
        if(FALSE === $this->where($options)->setField($field,1)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

    public function unrecommend($options,$field='is_recommend'){
        if(FALSE === $this->where($options)->setField($field,0)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
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
		if(isset($map['_string'])) $map['_string'] = ltrim($map['_string'], ' or '); 
		return $map;
	}    
}