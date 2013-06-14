<?php
/**
  * 辛辛苦苦抽时间搞开源！方便我自己的同时也方便大家。
  * 如果可以请帮我多多宣传CMSHead，谢谢！让我们一起努力，来把CMSHead做成最强悍的开源CMS，虽然任重道远，但是我还是有信心去努力！欢迎您加群讨论QQ群号：146570772
  * Author：还是这个味 782039296@qq.com
*/
if(!defined('THINK_PATH')) exit();
return array(
	'OUTPUT_ENCODE'	=>	false, //关闭页面压缩，解决可能的330错误 (net::ERR_CONTENT_DECODING_FAILED)
	'DB_TYPE'		=>	'mysql',// 数据库类型	
	'DB_HOST'		=>	'localhost',// 数据库服务器地址
	'DB_NAME'		=>	'newcmshead',// 数据库名称
	'DB_USER'		=>	'root',// 数据库用户名
	'DB_PWD'		=>	'awen520',// 数据库密码
	'DB_PREFIX'		=>	'ch_',// 数据表前缀
	'DB_CHARSET'	=>	'utf8',// 网站编码
	'DB_PORT'		=>	'3306',// 数据库端口 
	
	//网站系统设置
	'SITE_URL'     		=>  'http://www.cmshead.com',// 网站地址
	'SITE_NAME'			=>  'CMSHead网站管理系统',
	'SITE_KEYWORDS'		=>  'CMSHead,PHP CMS,ThinkPHPCMS,DWZ,jUI',
	'SITE_DESCRIPTION'	=>  'CMSHead是一套基于ThinkPHP和DWZ(jUI)的PHP CMS，类似于帝国CMS，织梦CMS（DEDECMS）等，但它还具有插件分享机制等优点。',
	'EMAIL'				=>	'',
	'OFFLINEMESSAGE'	=>	'本站正在维护中，暂不能访问。<br /> 请稍后再访问本站。',
	'ICP_NUM'			=>	'蜀ICP备00000000号',
	'CMSHEAD_VERSION'	=>	'2.1_20130528', //请勿随意修改
	
	//前台网友交互发布的信息默认是否审核0,1
	'HOME_SEND_STATUS'	=> 0,	

	//模板与关键字隐射关系，左边是别名，用于cmshead自定义函数ch1,ch2...中，如果这里改了，请把模板里的做相应修改哦
	'CMSHEAD_KEY_ROUTER' => array(
		'news'=>'Article',
		'新闻'=>'Article',
		'class'=>'Category',
		'栏目'=>'Category',
		'图片'=>'Photo',
		'视频'=>'Video',
		'音乐'=>'Music',

		'理论网苑'=>'Theory',

		'表'=>'Table',
		'条件'=>'where',
		'排序'=>'order',
		'字段'=>'field',
		'条数'=>'limit',
		'缓存'=>'cache',
	),
);