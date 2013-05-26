模板：view2.php<br />

<include file="Public:header" />
    <div class="wrap_left">
        <div class="rbox5">
            <div class="rbox5_top"></div>
            <div class="rbox5_center">
                <h2>{$info.title}</h2>
                <p>{$info.content}</p>                    
            </div>
            <div class="rbox5_bottom"></div>
        </div><!--文章信息-->
        
        <div class="rbox5">
            <div class="rbox5_top"></div>
            <div class="rbox5_center">
                <div class="pre">上一篇：<notempty name="art_pre"><a href="{$art_pre.url}">{$art_pre.title}</a><else/>无上一篇</notempty></div>
                <div class="next">下一篇：<notempty name="art_next"><a href="{$art_next.url}">{$art_next.title}</a><else/>无一下篇</notempty></div>
            </div>
            <div class="rbox5_bottom"></div>
        </div><!--上一篇下一篇-->

        <div class="rbox5">
            <div class="rbox5_top"></div>
            <div class="rbox5_center">
				<h2>随机推荐</h2>
				<volist name="art_rand" id="vo">
					<div class="pre"><a href="{$vo.url}">{$vo.title}</a></div>
				</volist>
            </div>
            <div class="rbox5_bottom"></div>
        </div><!--随机推荐-->
		
        <div class="rbox5">
            <div class="rbox5_top"></div>
            <div class="rbox5_center">
                <notempty name="msg_list">
                <volist name="msg_list" id="vo">
                    <div class="msg_list">
	                    <div class="left">
	                    	<img class="avatar" src="http://www.gravatar.com/avatar/{$vo.adder_email}?d=http%3A%2F%2Fwww.gravatar.com%2Favatar%2Faafaa838988511ab9d5942afddc615a2.png?s=32&s=32&r=g"/>
	                    </div>
	                    <div class="right"><span>{$vo.adder_name}&nbsp;说：{$vo.content}</span></div>
                    	<div class="clear"></div>
                    </div>
					<notempty name="vo.reply">
					<div class="com_list">
						<volist name="vo.reply" id="reply">
						<div class="left">
	                    	<img class="avatar" src="http://www.gravatar.com/avatar/{$reply.adder_email}?d=http%3A%2F%2Fwww.gravatar.com%2Favatar%2Faafaa838988511ab9d5942afddc615a2.png?s=32&s=32&r=g"/>
	                    </div>
						<div class="right">{$reply.adder_name} ：{$reply.content}</div>
						<div class="clear"></div>
						</volist>
					</div>
					</notempty>
                </volist>
                <else/>
                    	暂无评论！
                </notempty>
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
    	var aid = "{$info.id}";        
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
<include file="Public:footer" />