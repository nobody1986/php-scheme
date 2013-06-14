<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class Page {
    
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页URL地址
    public $url     =   '';
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config  =	array('header'=>'条记录','prev'=>'上页','next'=>'下页','first'=>'首页','last'=>'尾页','theme'=>"<span>%totalRow% %header%</span>\r\n<span>%nowPage%/%totalPage% 页</span>\r\n%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%");
    // 默认分页变量名
    protected $varPage;

    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$parameter='',$url='') {
        $this->totalRows    =   $totalRows;
        $this->parameter    =   $parameter;
        $this->varPage      =   C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
        if(!empty($listRows)) {
            $this->listRows =   intval($listRows);
        }
        $this->totalPages   =   ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages    =   ceil($this->totalPages/$this->rollPage);
        $this->nowPage  = !empty($_REQUEST[$this->varPage])?intval($_REQUEST[$this->varPage]):1; //awen 基于dwz修改
        if($this->nowPage<1){
            $this->nowPage  =   1;
        }elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage  =   $this->totalPages;
        }
        $this->firstRow     =   $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show($ajax_PageBox='') {
        if(0 == $this->totalRows) return '';
        $p = $this->varPage;
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        //awen edit  /Message  /news/?p=1  or /index.php/Article/view/id/12/?p=3
        if(empty($this->parameter) && !empty($_POST)){
        	unset($_POST['__hash__']);
        	foreach($_POST as $key=>$val){
        		//$val = iconv('gbk', 'utf-8', $val);
        		$this->parameter .= "&{$key}=".( is_array($val) ? serialize($val) : htmlspecialchars(trim($val)) );
        	}
        }
        $url = preg_match('/^\/\w+\/*$/',$_SERVER['REQUEST_URI']) ? rtrim($_SERVER['REQUEST_URI'],'/').'/index' : $_SERVER['REQUEST_URI']; 
        $url = rtrim(preg_replace('/[\/]+/','/',str_replace(array('?','&','='), '/', $url.'&'.$this->parameter)),'/');
        if( !preg_match('/\.([a-zA-Z]+)([\?\/&].*)*$/',$url) ) $url .= '/'; //没有包含.html的话，则加上/
        $url = preg_replace("/[\/]{$p}[\/][^\/]*/", '', $url);
        $url .= '?'; //如果没有url重写值（参数是单数）则不用此行
        
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href=\"{$url}{$p}={$upRow}\">".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a href=\"{$url}{$p}={$downRow}\">".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<a href=\"{$url}{$p}={$preRow}\">上".$this->rollPage."页</a>";
            $theFirst = "<a href=\"".rtrim($url,'?\/')."\">".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href=\"{$url}{$p}={$nextRow}\">下".$this->rollPage."页</a>";
            $theEnd = "<a href=\"{$url}{$p}={$theEndRow}\">".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "\r\n<a href=\"{$url}{$p}={$page}\">".$page."</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "\r\n<span class=\"current\">".$page."</span>";
                }
            }
        }
        $pageStr	 =	 str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        if($ajax_PageBox!='' && preg_match('/^[\w_-]+$/',$ajax_PageBox)){//ajax调用的
         	$pageStr .= '<script language="javascript">
                $("#pageShow a").click(function(i){
                    $.post($(this).attr("href").replace(/[\/?&]*(pageBox[\/=]'.$ajax_PageBox.')/gi,""), {pageBox:"'.$ajax_PageBox.'"}, function(data){                                              
                    	$("#'.$ajax_PageBox.'").html(data);
                    });
                    return false;
                });
                </script>';
        }
        return "<div id=\"pageShow\">{$pageStr}</div>";
    }

}