<?php if (!defined('THINK_PATH')) exit();?>模板：view_test.html<br />

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($title); ?></title>
<meta name="keywords" content="<?php echo ($keywords); ?>" />
<meta name="description" content="<?php echo ($description); ?>" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Theme/Front/default/style.css" />
<script language="javascript" src="__PUBLIC__/Js/jquery.js"></script>
<style type="text/css">
#nav_<?php echo ($position); ?>{ font-weight:bolder; color:#F00}
</style>
</head>
<body>
<div class="wrap">
	<div class="header">
    	<div class="logo"><img src="__PUBLIC__/Images/logo.png" /></div>
	</div><!--头部-->
    
    <div class="nav">
        <ul>
            <li><a href="__APP__" id="nav_0">首页</a></li>
            <?php if(is_array($nav_list)): $i = 0; $__LIST__ = $nav_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>            	           
                <a href="<?php echo ($vo["url"]); ?>" id="nav_<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></a>
                <?php if(($vo["module"]) == "Article"): if(!empty($vo["sub_nav"])): ?><ul>
                    <div class="nav_top"></div>
                    <div class="nav_center">
                        <?php if(is_array($vo["sub_nav"])): $i = 0; $__LIST__ = $vo["sub_nav"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($sub["url"]); ?>" id="nav_<?php echo ($sub["id"]); ?>"><?php echo ($sub["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        <div class="clear"></div>                
                    </div>
                    <div class="nav_bottom"></div>
                </ul><?php endif; endif; ?>              
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div><!--导航-->
    
    <div class="crumb">
		<span class="crumb_ad"><font color="red">欢迎使用 CMSHead V1.0 正式版！！！</font></span>你的位置：<a href="__APP__">首页</a>
        <?php if(($position) != "0"): ?><span class="gt"></span>
	        <a href="__APP__/<?php echo (getmodulebyid($position)); ?>/index/id/<?php echo ($position); ?>"><?php echo (getcategoryname($position)); ?></a>
	        <?php if((ACTION_NAME) == "view"): ?><span class="gt"></span>
	        <?php echo ($info["title"]); endif; endif; ?>
    </div><!--位置-->	

    <div class="wrap_left">
        <div class="rbox5">
            <div class="rbox5_top"></div>
            <div class="rbox5_center">
                <h2><?php echo ($info["title"]); ?></h2>
                <p><?php echo ($info["content"]); ?></p>                    
            </div>
            <div class="rbox5_bottom"></div>
        </div><!--文章信息-->
        
        <div class="rbox5">
            <div class="rbox5_top"></div>
            <div class="rbox5_center">
                <div class="pre">上一篇：<?php if(!empty($art_pre)): ?><a href="<?php echo ($art_pre["url"]); ?>"><?php echo ($art_pre["title"]); ?></a><?php else: ?>无上一篇<?php endif; ?></div>
                <div class="next">下一篇：<?php if(!empty($art_next)): ?><a href="<?php echo ($art_next["url"]); ?>"><?php echo ($art_next["title"]); ?></a><?php else: ?>无一下篇<?php endif; ?></div>
            </div>
            <div class="rbox5_bottom"></div>
        </div><!--上一篇下一篇-->

        <div class="rbox5">
            <div class="rbox5_top"></div>
            <div class="rbox5_center">
				<h2>随机推荐</h2>
				<?php if(is_array($art_rand)): $i = 0; $__LIST__ = $art_rand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="pre"><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["title"]); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="rbox5_bottom"></div>
        </div><!--随机推荐-->
		
        <div class="rbox5">
            <div class="rbox5_top"></div>
            <div class="rbox5_center">
                <?php if(!empty($msg_list)): if(is_array($msg_list)): $i = 0; $__LIST__ = $msg_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="msg_list">
	                    <div class="left">
	                    	<img class="avatar" src="http://www.gravatar.com/avatar/<?php echo ($vo["adder_email"]); ?>?d=http%3A%2F%2Fwww.gravatar.com%2Favatar%2Faafaa838988511ab9d5942afddc615a2.png?s=32&s=32&r=g"/>
	                    </div>
	                    <div class="right"><span><?php echo ($vo["adder_name"]); ?>&nbsp;说：<?php echo ($vo["content"]); ?></span></div>
                    	<div class="clear"></div>
                    </div>
					<?php if(!empty($vo["reply"])): ?><div class="com_list">
						<?php if(is_array($vo["reply"])): $i = 0; $__LIST__ = $vo["reply"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$reply): $mod = ($i % 2 );++$i;?><div class="left">
	                    	<img class="avatar" src="http://www.gravatar.com/avatar/<?php echo ($reply["adder_email"]); ?>?d=http%3A%2F%2Fwww.gravatar.com%2Favatar%2Faafaa838988511ab9d5942afddc615a2.png?s=32&s=32&r=g"/>
	                    </div>
						<div class="right"><?php echo ($reply["adder_name"]); ?> ：<?php echo ($reply["content"]); ?></div>
						<div class="clear"></div><?php endforeach; endif; else: echo "" ;endif; ?>
					</div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <?php else: ?>
                    	暂无评论！<?php endif; ?>
            </div>				
            <div class="rbox5_bottom"></div>
        </div><!--评论列表-->
        
        <div class="rbox5">
            <div class="rbox5_top"></div>
            <div class="rbox5_center">
            	<h2>发表评论</h2>
            	<div id="postTemp"></div>
	            <div class="sub_form">
	           		<input type="hidden" name="pid" id="pid" value="0" />
	           		<div>
	               	<table><tr><td>
	               	<img src="__APP__/common/verify" alt="点击刷新验证码" id="verifyImg" class="code" onclick="this.src+='?' + Math.random();" /></td><td>
	                     <input type="text" name="verify" id="verify" class="short" maxlength="4" />
	                     <label class="required">验证码（必须）</label></td></tr></table>
	                </div>
	                <div>
                        <input type="text" name="adder_name" id="adder_name" class="long" />
                        <label class="required">您的大名（必须）</label>
                    </div>
                    <div>
                        <input type="text" name="adder_email" id="adder_email" class="long" />
                        <label class="required">您的Email（必须）</label>
                    </div>
	                <div>
	                     <textarea name="content" id="content"></textarea>
	                     <label class="required">评论内容（必须）</label>
	                </div>
					<div>
	                     <input type="submit" class="button" value="" onclick="return chksub()" />
	           		</div>
	       		</div>
            </div>
            <div class="rbox5_bottom"></div>            
        </div><!--发表评论-->
        <script type="text/javascript">
    	var aid = "<?php echo ($info["id"]); ?>";        
        $(document).ready(function(){
        	$('#pid').attr('value',0);
			$('.reply').click(function(){
				$('#pid').attr('value',$(this).attr('pid'));
				$('#postTemp').prev().html('回复评论: '+$(this).next().html()); 
				$('#content').focus();
			})
        });  
        
        function chksub(){
			if($('#verify').val()==''){
				alert('请填写验证码！');
				$('#verify').focus();
				return false;
			}
			if($('#adder_name').val()==''){
				alert('请填您的大名！');
				$('#adder_name').focus();
				return false;
			}
			if($('#adder_email').val()==''){
				alert('请填写您的Email！');
				$('#adder_email').focus();
				return false;
			}
			if($('#content').val()==''){
				alert('请填写评论内容！');
				$('#content').focus();
				return false;
			}
			$("#postTemp").html('<div class="pop">正在发表，请稍等。。。</div>');
			$.post("__APP__/message/add", {"verify":$("#verify").val(),"content":$("#content").val(),"adder_email":$("#adder_email").val(),"adder_name":$("#adder_name").val(),"aid":aid,"pid":$("#pid").val(),"type":1}, function(msg) {
				$("#postTemp").html(msg);
				$("#verify").val('');
				$("#content").val('');
				$("#verifyImg").attr("src",function(){this.src+='?'+Math.random();});
				$(".pop").fadeOut(3000);
			});
        }      
        </script>
    </div>        
            <div class="wrap_right">
            <div class="rbox4">
                <div class="rbox4_top"></div>
                <div class="rbox4_center">
                    <div class="item_title">
						<a href="javascript:;" onclick="show('hot_block')" style="font-weight:bolder">热门文章 </a> ｜ 
						<a href="javascript:;" onclick="show('top_block')" style="color:#a1a1a1">最新文章</a>
					</div>
                   
                    <div id="hot_block">
                    <?php if(is_array($hot_art)): $i = 0; $__LIST__ = $hot_art;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="line">
                            <div title="<?php echo ($vo["title"]); ?>"  class="nowrap">
								<div class="hot_ico"></div>
								<a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["title"]); ?>"><?php echo ($vo["title"]); ?></a>
							</div>
                            <div style="color:#A1A1A1">浏览次数：<?php echo ($vo["apv"]); ?> 次</div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div><!--热门-->
                   
                    <div id="top_block" style="display:none">
                    <?php if(is_array($new_art)): $i = 0; $__LIST__ = $new_art;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="line">
                            <div title="<?php echo ($vo["title"]); ?>"  class="nowrap">
								<div class="new_ico"></div>
								<a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["title"]); ?>"><?php echo ($vo["title"]); ?></a>
							</div>
                            <div style="color:#A1A1A1">浏览次数：<?php echo ($vo["apv"]); ?> 次</div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div><!--最新-->
                    
                </div>
                <div class="rbox4_bottom"></div>
            </div><!--文章Tab-->
            
            <div class="rbox4">
                <div class="rbox4_top"></div>
                <div class="rbox4_center">
                     <div class="item_title"><b>站内搜索</b></div>
                     <form action="__APP__/Index/search" method="post">
                     <div class="search">                        
                     	<input type="text" name="keyword" id="keyword" class="text" value="<?php echo ($keyword); ?>">
                        <input type="submit" value="" class="btn" >                        
                    </div>
                    </form>
                </div>
                <div class="rbox4_bottom"></div>
            </div><!--站内搜索-->
                                    
            <div class="rbox4">
                <div class="rbox4_top"></div>
                <div class="rbox4_center">
                    <div class="item_title">
						<a href="javascript:;" onclick="show('mes_block')" style="font-weight:bolder">最新留言</a> ｜ 
						<a href="javascript:;" onclick="show('com_block')" style="color:#a1a1a1">最新评论</a>
					</div>
                    
                    <div id="mes_block">
                    <?php if(is_array($new_leave)): $i = 0; $__LIST__ = $new_leave;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="block" >   
                            <div class="left"><img class="avatar" src="http://www.gravatar.com/avatar/<?php echo ($vo["adder_email"]); ?>?d=http%3A%2F%2Fwww.gravatar.com%2Favatar%2Faafaa838988511ab9d5942afddc615a2.png?s=32&s=32&r=g"/></div>
                            <div class="right" title="<?php echo ($vo["content"]); ?>"><?php echo ($vo["adder_name"]); ?> 说:<div class="nowrap"><?php echo ($vo["content"]); ?></div></div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div><!--留言-->
                    
                    <div id="com_block" style="display:none">
                    <?php if(is_array($new_comment)): $i = 0; $__LIST__ = $new_comment;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="block" >   
                            <div class="left"><img class="avatar" src="http://www.gravatar.com/avatar/<?php echo ($vo["adder_email"]); ?>?d=http%3A%2F%2Fwww.gravatar.com%2Favatar%2Faafaa838988511ab9d5942afddc615a2.png?s=32&s=32&r=g"/></div>
                            <div class="right">
                            	<div class="nowrap"><?php echo ($vo["adder_name"]); ?> 评论了： <a href="__APP__/article/view/id/<?php echo ($vo["aid"]); ?>" style="color:#f00"  title="<?php echo (getarticlebyid($vo["aid"])); ?>"> <?php echo (getarticlebyid($vo["aid"])); ?></a></div>
                            	<div class="nowrap" title="<?php echo ($vo["content"]); ?>" ><?php echo ($vo["content"]); ?></div>		
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div> <!--评论-->                   
                </div>
                <div class="rbox4_bottom"></div>
            </div><!--评论留言Tab-->
        </div>
        <script language="javascript">
		function show(obj){
			$('#'+obj).siblings("[class!=item_title]").css('display','none');
			$('#'+obj).css('display','');
		}
		$(document).ready(function(){
			$('.rbox4 .item_title a').click(function(){
				$(this).css({color:"#3C99C9","font-weight":"bolder"});
				$(this).siblings().css({color:"#a1a1a1","font-weight":"normal"});							   
			});
		});
		</script>
	

	<div class="flink"><b>酷站链接：</b>
	<?php if(is_array($link)): $i = 0; $__LIST__ = $link;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["intro"]); ?>" target="_blank"><?php echo ($vo["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>        
    </div>
        
    <div class="footer">
	<a href="__APP__" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('<?php echo (C("xpcms_url")); ?>');">设为首页</a> | 
    <a href="javascript:window.external.addfavorite('<?php echo (C("xpcms_url")); ?>','CMSHead');">加入收藏</a> | 
    <a href="__ROOT__/rss.xml">RSS订阅</a> | 
    <a href="mailto:<?php echo (C("email")); ?>">联系站长</a> | 
    <a href="__ROOT__/admin.php" target="_blank">后台登录</a> | 
    <a href="__APP__/link">友情链接</a> | 
    <a href="__APP__/message">留言反馈</a> | 
    <a href="javascript:;"><?php echo (C("icp_num")); ?></a> | 
    <a href="http://www.cmshead.com" target="_blank">Powered By CMSHead <?php echo (C("xpcms_version")); ?></a> | 
	</div>
</div><!--main end-->
</body>
</html>