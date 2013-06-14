<?php
$config	=	require './config.php';
$admin_config	= array(
	'APP_FILE_CASE'				=>true,				// 是否检查文件的大小写 对Windows平台有效
    'URL_MODEL'					=>1, 				// 如果你的环境不支持PATHINFO 请设置为3
	'TMPL_CACHE_ON'				=>true, 			//开启模板缓存
	'URL_CASE_INSENSITIVE'  	=>true, 			//URL不区分大小写
	'VAR_PAGE'					=>'pageNum',        //dwz必须是这个名称
	'USER_AUTH_ON'				=>true,
	'USER_AUTH_TYPE'			=>1,				// 默认认证类型 1 登录认证 2 实时认证
	'USER_AUTH_KEY'				=>'authId',			// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'			=>'administrator',
	'USER_AUTH_MODEL'			=>'User',			// 默认验证数据表模型
	'AUTH_PWD_ENCODER'			=>'md5',			// 用户认证密码加密方式
	'USER_AUTH_GATEWAY'			=>'/Public/login',	// 默认认证网关
	'NOT_AUTH_MODULE'			=>'Public',			// 默认无需认证模型
	'REQUIRE_AUTH_MODULE'		=>'',				// 默认需要认证模型
	'NOT_AUTH_ACTION'			=>'',				// 默认无需认证操作
	'REQUIRE_AUTH_ACTION'		=>'',				// 默认需要认证操作
    'GUEST_AUTH_ON'          	=> false,    		// 是否开启游客授权访问
    'GUEST_AUTH_ID'           	=>0,     			// 游客的用户ID

    'DB_LIKE_FIELDS'			=>'title|remark|content',	//搜索Like匹配字段
	'RBAC_ROLE_TABLE'			=>$config['DB_PREFIX'].'role',
	'RBAC_USER_TABLE'			=>$config['DB_PREFIX'].'role_user',
	'RBAC_ACCESS_TABLE' 		=>$config['DB_PREFIX'].'access',
	'RBAC_NODE_TABLE'			=>$config['DB_PREFIX'].'node',

    /* 分页设置 */
	'PAGE_ROLLPAGE'   	        => 5,      // 分页显示页数
	'PAGE_LISTROWS'         	=> 20,     // 分页每页显示记录数
	
	'LOAD_EXT_FILE'			=> 'cmshead',  //自动加载Home/Common下的函数
	'DEFAULT_THEME'    		=> 'default',  // 默认模板主题名称

	'ADMIN_BMENU'			=> array(
		'content'=>'内容管理',
		'framework'=>'架构管理',
		'system'=>'系统管理',
		'ui'=>'界面管理',
		'plugin'=>'插件管理',
	), //后台大菜单，顺序可随意调整
	
	'REMOTE_ALLOWEXTS'		=> 'jpeg,jpg,gif,bmp,png,ico', //远程文件本地化允许的文件	,mp3,wmv,wma,mpg,rm,avi,vob,swf,flv,fla,doc,docx,ppt,pptx,pdf,pdg,mdb,xls,xlsx,txt,ini,psd,xml,rar,zip,7z

	//后台URL重写值为空时 自动设置为模块名加id值0,1
	'ADMIN_AUTO_REWRITE'=> 1,	
);
return array_merge($config,$admin_config);