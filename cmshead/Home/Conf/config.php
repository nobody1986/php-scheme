<?php
$config	=	require './config.php';
$home_config =  array(
	'APP_FILE_CASE'			=> true,			// 是否检查文件的大小写 对Windows平台有效
	'TMPL_CACHE_ON'			=> false, 		//开启模板缓存
	'URL_CASE_INSENSITIVE'  => true, 		//URL不区分大小写
	'URL_MODEL'             => 2,           //服务器开启Rewrite模块时，可去除URL中的index.php
	'URL_HTML_SUFFIX'=>'.html',      //伪静态acan
	
	'USER_AUTH_KEY'			=> 'authId',			// 用户认证SESSION标记
	'DB_LIKE_FIELDS'		=> 'title|remark|content',	//搜索Like匹配字段
	'LOAD_EXT_FILE'			=> 'cmshead,myfuns',  //自动加载Home/Common下的函数
	'APP_AUTOLOAD_PATH'     => '@.Extend', //自动加载Home/Lib/Extend下的所有应用类模板扩展类库 	
 	'DEFAULT_THEME'    		=> 'default',  // 默认模板主题名称
	
	/*静态缓存*/
//	'HTML_CACHE_ON'=>true, 
//	'HTML_READ_TYPE'=>1,
//    'HTML_CACHE_RULES'=> array( 
//		'Index:index'=>array('{$_SERVER.REQUEST_URI|md5}',60), 
//      ), 
);
return array_merge($config,$home_config);