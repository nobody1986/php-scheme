<?php
define('APP_NAME','Admin');				// 定义项目名称
define('APP_PATH','./Admin/');			// 定义项目目录
define('APP_DEBUG',true);				//开启调试模式
//关闭反斜杠设置
if( !ini_set ('magic_quotes_gpc', 0) ){
	if ( get_magic_quotes_gpc() ) {
	   function stripslashes_deep($value) {
	       $value = is_array($value) ? array_map('stripslashes_deep', $value) : (isset($value) ? stripslashes($value) : null);
	       return $value;
	   }
	
	   $_POST = stripslashes_deep($_POST);
	   $_GET = stripslashes_deep($_GET);
	   $_COOKIE = stripslashes_deep($_COOKIE);
	   $_REQUEST = stripslashes_deep($_REQUEST);
	}
}
require('ThinkPHP/ThinkPHP.php');		// 加载入口文件
