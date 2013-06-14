<?php
/**
 * 在rewrite 模式下Index模块的其他方法可能会转入空操作，这是PATH_INFO参数缺陷。
 * 修复办法：
 * 1、在空操作中做单独处理
 * 2、dispaly强制指定模板
 * @author Administrator
 *
 */
class IndexAction extends CommonAction{
    //首页    
	public function index(){		
//		echo(ch1('268','debug')); exit;	
		//支持二级域名解析 2013-3-13
		$inputURL = thisURL(1);	
		$full_inputDomain = str_ireplace('www.','',$inputURL['domain']); //完整 
		$left_inputDomain = str_ireplace( preg_replace('/^https?:\/\/(www)?/i', '', C('SITE_URL')), '', $full_inputDomain); //前缀
		if($left_inputDomain!=''){
			$rs = M('category')->where("classstatus=1 and (replace(classdomain,'www.','')='{$left_inputDomain}' or replace(classdomain,'www.','')='{$full_inputDomain}')")->field('classid,classmodule')->find();		
			if($rs){
				$vars = array('id'=>$rs['classid']);
				R(ucfirst($rs['classmodule']).'/index', $vars); exit;
			}
		}
		
		$this->seo();
		$this->display();
    }
	//站长日记
	public function diary(){
		$this->seo('站长日记');
		$this->display('Index:diary');
	}
	
	//站内搜索，可修改定制
	public function search(){
		header('Content-Type:text/html;charset=utf-8');
		$keytype = ucfirst(strtolower(htmlspecialchars(trim($_REQUEST['keytype']))));
		$keyword = htmlspecialchars(trim($_REQUEST['keyword']));
		//$keyword = iconv('gbk', 'utf-8', $keyword);
		if($keytype!=''){
			if(!($tables = S('tables'))){			
				import("Db");
				$db =   DB::getInstance();  
				$tables = $db->getTables();
				S('tables',$tables,3600);
			}
			$keytype = (in_array(C('DB_PREFIX').strtolower($keytype), $tables)) ? $keytype : 'Article';			
		}else{
			$keytype = 'Article';
		}
		
		$fields = M($keytype)->getDbFields();
		$checkField = array();
		if(in_array('title',$fields)) $checkField['title'] = 'keyword';
		//if(in_array('content',$fields)) $checkField['content'] = 'keyword';
		
		$where = getSearchMap($checkField);
		if($where){ 
			$where['_logic'] = 'OR';
			$map['_complex'] = $where;
		}	
		parent::$map = $map;
		
		parent::$pos = '在'.M('Model')->where(array('ename'=>$keytype))->getField('cname').'中搜索'.$keyword.'的结果';
		
		$this->seo(parent::$pos);
		$this->assign('keytype',$keytype);
		$this->assign('keyword',$keyword);	
		$this->display('Index:search');
	}	
	
	//全站搜索，acan
	public function globalsearch(){
		header('Content-Type:text/html;charset=utf-8');
		$keytype = ucfirst(strtolower(htmlspecialchars(trim($_REQUEST['keytype']))));//可传入多个表，用逗号分隔例如article,music
		$keytype_arr = explode(',',$keytype);
		$keyword = htmlspecialchars(trim($_REQUEST['keyword']));
		
		import("Db");
		$db =   DB::getInstance();  
		$tables = $db->getTables();
		//过滤没有的表
		foreach($keytype_arr as $k=>$v){
			if(!in_array(C('DB_PREFIX').strtolower($v), $tables)){
				unset($keytype_arr[$k]);	
			}
		}
		$keytype_arr = 	$keytype_arr?$keytype_arr:array('Article');	
		foreach($keytype_arr as $k=>$v){
			$fields = M($v)->getDbFields();
			$checkField = array();
			if(in_array('title',$fields)) $checkField['title'] = 'keyword';
			$where = getSearchMap($checkField);
			if($where){ 
				$where['_logic'] = 'OR';
				$map['_complex'] = $where;
			}
			break;
		}
		import("ORG.Util.Page");
		$model = M();
		$tablecount = count($keytype_arr);
		if($tablecount>=1){
			$evalstr = 	'$model->field(\'id,title,tid,apv,add_time\')->table(\''.C('DB_PREFIX').strtolower($keytype_arr[0]).'\')->where($map)';
			unset($keytype_arr[0]);
			foreach($keytype_arr as $k=>$v){
				$evalstr.='->union(array(\'field\'=>\'id,title,tid,apv,add_time\',\'table\'=>\''.C('DB_PREFIX').strtolower($v).'\',\'where\'=>$map))';	
			}
			eval('$count=count('.$evalstr.'->select());');
			
			$Page= new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
			$show= $Page->show();// 分页显示输出
			$evalstr.='->select();';
			$lastsql = M()->getLastSql();
			$lastsql.= " limit ".$Page->firstRow.','.$Page->listRows; 
			$list = M()->query($lastsql);	
		}
		
		foreach($list as $k=>$v){
			$list[$k]['classurl']=chURL(array_shift(explode(',',$v['tid'])));	
			$pidinfo=M('Category')->where('classid='.array_shift(explode(',',$v['tid'])))->find();
			$list[$k]['classtitle'] = $pidinfo['classtitle'];
			$list[$k]['tablename'] = strtolower($pidinfo['classmodule']);
			$list[$k]['url'] = chURL($v['id'],$pidinfo['classmodule']);
			$list[$k]['oldtitle'] = $v['title'];
			$list[$k]['title'] = str_replace($keyword,"<font style='color:red'>".$keyword."</font>",htmlspecialchars($v['title']));
		}
		
		parent::$map = $map;
		
		parent::$pos = "全站搜索<span style='color:red'>".$keyword."</span>的结果";
		$this->assign('page',$show);
		$this->assign('list',$list);	
		$this->assign('position',parent::$pos);	
		$this->seo('全站搜索'.$keyword.'的结果');
		$this->assign('keyword',$keyword);	
		$this->display('Index:globalsearch');
	}
	
}