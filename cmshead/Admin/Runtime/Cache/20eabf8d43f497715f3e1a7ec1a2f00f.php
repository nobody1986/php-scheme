<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo (C("site_name")); ?></title>

<link href="__PUBLIC__/Theme/Admin/Dwz/themes/default/style.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Theme/Admin/Dwz/themes/css/core.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="__PUBLIC__/Theme/Admin/Dwz/themes/css/ieHack.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script src="__PUBLIC__/Theme/Admin/Dwz/js/speedup.js" type="text/javascript"></script>
<script src="__PUBLIC__/Theme/Admin/Dwz/js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/Theme/Admin/Dwz/js/jquery.cookie.js" type="text/javascript"></script>
<script src="__PUBLIC__/Theme/Admin/Dwz/js/jquery.validate.js" type="text/javascript"></script>
<script src="__PUBLIC__/Theme/Admin/Dwz/js/jquery.bgiframe.js" type="text/javascript"></script>
<script src="__PUBLIC__/Theme/Admin/Dwz/js/dwz.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/Theme/Admin/Dwz/js/dwz.regional.zh.js" type="text/javascript"></script>
<script src="__PUBLIC__/Theme/Admin/Dwz/js/xheditor/xheditor-zh-cn.min.js" type="text/javascript"></script>
<script type="text/javascript">
var APP = '__APP__';
function fleshVerify(){
	//重载验证码
	$('#verifyImg').attr("src", '__APP__/Public/verify/'+new Date().getTime());
}
function dialogAjaxMenu(json){
	dialogAjaxDone(json);
	if (json.statusCode == DWZ.statusCode.ok){
		$("#sidebar").loadUrl("__APP__/Public/menu");
	}
}
function navTabAjaxMenu(json){
	navTabAjaxDone(json);
	if (json.statusCode == DWZ.statusCode.ok){
		$("#sidebar").loadUrl("__APP__/Public/menu");
	}
}
function AjaxMenu(){
	$("#sidebar").loadUrl("__APP__/Public/menu");
}
$(function(){
	DWZ.init("__PUBLIC__/Theme/Admin/Dwz/dwz.frag.xml", {
		loginUrl:"login.html",
		debug:false,
		statusCode:{ok:1,error:0},
		callback:function(){
			initEnv();
			$("#themeList").theme({themeBase:"__PUBLIC__/Theme/Admin/Dwz/themes"});
		}
	});
});
//清理浏览器内存,只对IE起效，FF不需要
if ($.browser.msie) {
	window.setInterval("CollectGarbage();", 10000);
}
</script>
</head>

<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<a class="logo" href="<?php echo (C("baseurl")); ?>">Logo</a>
				<ul class="nav">
					<li class="first"><a href="__ROOT__/" target="_blank">前台首页</a></li>
					<li><a href="__APP__/Public/main" target="dialog" width="580" height="360" rel="sysInfo">系统消息</a></li>
					<li><a href="__APP__/Public/password/" target="dialog" width="520" height="230" mask="true">修改密码</a></li>
					<li><a href="__APP__/Public/profile/" target="dialog" width="560" height="270" mask="true">修改资料</a></li>
					<li><a href="__APP__/Public/logout/">退出</a></li>
				</ul>
				<ul class="themeList" id="themeList">
					<li theme="default"><div class="selected">蓝色</div></li>
					<li theme="green"><div>绿色</div></li>
					<li theme="purple"><div>紫色</div></li>
					<li theme="silver"><div>银色</div></li>
				</ul>
			</div>
		</div>
		
		<div id="leftside">
			<div id="sidebar_s">
				<div class="collapse">
					<div class="toggleCollapse"><div></div></div>
				</div>
			</div>
			
			<div id="sidebar">
			<div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>
				<div class="accordion" fillSpace="sideBar">
	<div class="accordionHeader">
		<h2><span>Folder</span>应用中心</h2>
	</div>
	<div class="accordionContent">
		<ul class="tree treeFolder">
			<?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if((strtolower($item['name'])) != "public"): if((strtolower($item['name'])) != "index"): if(($item['group_id']) == "2"): if(($item['access']) == "1"): ?><li><a href="__APP__/<?php echo ($item['name']); ?>/index/" target="navTab" rel="<?php echo ($item['name']); ?>"><?php echo ($item['title']); ?></a></li><?php endif; endif; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
    
	<div class="accordionHeader">
		<h2><span>Folder</span>系统设置</h2>
	</div>
	<div class="accordionContent">
		<ul class="tree treeFolder">
			<?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if((strtolower($item['name'])) != "public"): if((strtolower($item['name'])) != "index"): if(($item['group_id']) == "1"): if(($item['access']) == "1"): ?><li><a href="__APP__/<?php echo ($item['name']); ?>/index/" target="navTab" rel="<?php echo ($item['name']); ?>"><?php echo ($item['title']); ?></a></li><?php endif; endif; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
</div>

			</div>
		</div>

		<div id="container">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:void(0)"><span><span class="home_icon">我的主页</span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:void(0)">我的主页</a></li>
				</ul>
				<div class="navTab-panel tabsPageContent">
					<div>
						<div class="accountInfo">
							<div class="right">
								<p><?php echo (date('Y-m-d g:i a',time())); ?></p>
							</div>
							<p><span><?php echo (C("site_name")); ?></span></p>
							<p>欢迎光临, <?php echo ($_SESSION['loginUserName']); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="taskbar" style="left:0px; display:none;">
			<div class="taskbarContent">
				<ul></ul>
			</div>
			<div class="taskbarLeft taskbarLeftDisabled" style="display:none;">taskbarLeft</div>
			<div class="taskbarRight" style="display:none;">taskbarRight</div>
		</div>
		<div id="splitBar"></div>
		<div id="splitBarProxy"></div>
	</div>
	
	<div id="footer">版权所有(C) <a href="http://www.cmshead.com/" target="_blank">CMSHead</a> V<?php echo (C("cmshead_version")); ?> ALL RIGHTS RESERVED, Author's QQ:782039296</div>

	<!--拖动效果-->
	<div class="resizable"></div>
	<!--阴影-->
	<div class="shadow" style="width:508px; top:148px; left:296px;">
		<div class="shadow_h">
			<div class="shadow_h_l"></div>
			<div class="shadow_h_r"></div>
			<div class="shadow_h_c"></div>
		</div>
		<div class="shadow_c">
			<div class="shadow_c_l" style="height:296px;"></div>
			<div class="shadow_c_r" style="height:296px;"></div>
			<div class="shadow_c_c" style="height:296px;"></div>
		</div>
		<div class="shadow_f">
			<div class="shadow_f_l"></div>
			<div class="shadow_f_r"></div>
			<div class="shadow_f_c"></div>
		</div>
	</div>
	<!--遮盖屏幕-->
	<div id="alertBackground" class="alertBackground"></div>
	<div id="dialogBackground" class="dialogBackground"></div>

	<div id='background' class='background'></div>
	<div id='progressBar' class='progressBar'>数据加载中，请稍等...</div>

</body>
</html>