<?php
/*
if(!file_exists("install/install.lock")){
	header('Location:install/index.php');
}
*/
define('APP_NAME','Home');    		// 定义项目名称
define('APP_PATH','./Home/');		//定义项目目录
define('APP_DEBUG',true);			//开启调试模式
//define('HTML_PATH',APP_PATH.'Runtime/Html/');//静态缓存目录配置，默认是APP_PATH.'Html/'
require('ThinkPHP/ThinkPHP.php');   // 加载入口文件