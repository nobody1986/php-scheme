/**
 * @author awen 还是这个味
 */
/*@cc_on _d=document;eval('var document=_d')@*/
/*@cc_on eval((function(props) {var code = [];for (var i = 0,l = props.length;i<l;i++){var prop = props[i];window['_'+prop]=window[prop];code.push(prop+'=_'+prop);}return 'var '+code.join(',');})('document self top parent alert setInterval clearInterval setTimeout clearTimeout'.split(' ')))@*/

//列表页面设置排序
function setSort(strURL,obj,id,rel){
	if( obj.tagName=='INPUT' ){
		if( $(obj).val()!='' && !isNaN($(obj).val()) ){
			setTimeout(function(){
				$.post(strURL+'/setSort/field/sort/id/'+id, {ajax:1, value:$(obj).val()}, function(data){
					$(obj).replaceWith($(obj).val());//防止多次提交
					if(data.status==1){	
						navTab.reloadFlag((rel && rel!='undefined') ? rel : data.info);
					}else{
						alert(data.info);
					}
				},'json');
			},1000);
		}
	}else{
		$(obj).html('<input type="text" size="2" value="'+$(obj).text()+'" oninput="setSort(\''+strURL+'\',this,'+id+',\''+rel+'\');" onpropertychange="setSort(\''+strURL+'\',this,'+id+',\''+rel+'\');" />');
		$(obj).find('input').focus();
	}
}

//***左边菜单中栏目左侧的<div class="file">图标加上点击“新增到此栏目”功能 start***
var expobj,last_expobj; //type = 1 or 2
function _last_expClick(obj,type){
	if(!obj) return;
	if(type==2) last_expobj=$(obj);
	else expobj=$(obj);
	
	setTimeout(function(){
		if(type==2){ //直接last_expandable
			//找到div.file
			last_expobj.parent().siblings('ul').find('div.file').addClass('fadd').click(
				function(){
					var aobj = $(this).siblings('a.info_addview');
					$raddURL = aobj.attr('href').replace(/\/index\//i,'/add/');
					navTab.openTab('addtoatid', $raddURL, { title:"新增到 "+aobj.text(), fresh:false, data:{} });
				}
			);				
			//点击后 递归调用
			_last_expClick(last_expobj[0],2);
		}else if(expobj){ //expandable
			var Lexpobj = expobj.parent().siblings('ul').find('div.last_expandable'); //最后一个加号
			Lexpobj = Lexpobj[0] ? Lexpobj : expobj.parent().siblings('ul').find('div.expandable');
			if(Lexpobj[0]){
				//点加号
				Lexpobj.click( 
					function(){
						//找到div.file
						$(this).parent().siblings('ul').find('div.file').addClass('fadd').click(
							function(){
								var aobj = $(this).siblings('a.info_addview');
								$raddURL = aobj.attr('href').replace(/\/index\//i,'/add/');
								navTab.openTab('addtoatid', $raddURL, { title:"新增到 "+aobj.text(), fresh:false, data:{} });
							}
						);
						//点击后 递归调用
						_last_expClick(this,1);
					}						
				);
				//点标题也要能起作用
				Lexpobj.siblings('a').click(
					function(){
						//找到div.file
						$(this).parent().siblings('ul').find('div.file').addClass('fadd').click(
							function(){
								var aobj = $(this).siblings('a.info_addview');
								$raddURL = aobj.attr('href').replace(/\/index\//i,'/add/');
								navTab.openTab('addtoatid', $raddURL, { title:"新增到 "+aobj.text(), fresh:false, data:{} });
							}
						);
						//点击后 递归调用
						_last_expClick(this,1);
					}						
				);
			}
		}
	},1000);
}

$(function(){
	setTimeout(function(){
		$('ul.tree li,ul.tree li div').click( //ie下面的兼容代码。点击栏目标题前面的图标更精准2013-2-5
			function(){
				return false;
			}
		);
		
		//第一个模块默认展开的模块，能找到div.file
		$('.info_addview').siblings('div.file').addClass('fadd').click(
			function(){
				var aobj = $(this).siblings('a.info_addview');
				$raddURL = aobj.attr('href').replace(/\/index\//i,'/add/');
				navTab.openTab('addtoatid', $raddURL, { title:"新增到 "+aobj.text(), fresh:false, data:{} });
			}
		);	
		//其他模块默认收缩的模块，要最后一个加号点击展开以后才会产生div.file
		$('div.expandable').click(
			function(){
				_last_expClick(this,1);
			}
		);
		$('div.expandable').siblings('a').click(
			function(){
				_last_expClick(this,1);
			}
		);
		$('div.last_expandable').click(
			function(){				
				_last_expClick(this,2);
			}
		);
		$('div.last_expandable').siblings('a').click(
			function(){				
				_last_expClick(this,2);
			}
		);
	},1000);
});	
//***左边菜单中栏目左侧的<div class="file">图标加上点击"新增到此栏目"功能 end***

//自动提取关键字
function setInputFromInput(obj,type){
	var title = $("input[name='title']").val()?$("input[name='title']").val():'';
	var editorcontent = $("textarea[name='content']").val().replaceAll('&nbsp;','');
	//提取关键字
	if(type==1){
		if(!editorcontent.replace(/\s+/g,'')){
			alert('提取关键字之前请先输入内容');
			$("textarea[name='content']").focus();
		}else{
			$.ajax({
			   type: "POST",
			   url: APP+"/Common/auto_getkey?fresh=" + Math.random(),
			   async: false,
			   //data: "id="+id+"&modlename=guangbo",
			   data: "title="+title+"&content="+editorcontent,
			   dataType: "text",
			   success: function(data){	
					if(data){
						$("input[name='keywords']").val(data);
					}else{
						$("input[name='keywords']").val(data);
					}
			   }
			})
		
		}
	}
	//提取内容摘要
	if(type==2){
		if(!editorcontent.replace(/\s+/g,'')){
			alert('提取简介之前请先输入内容');
			$("textarea[name='content']").focus();
		}else{
			$.ajax({
			   type: "POST",
			   url: APP+"/Common/auto_getdes?fresh=" + Math.random(),
			   async: false,
			   data: "content="+editorcontent,
			   dataType: "text",
			   success: function(data){	
					if(data){
						$("textarea[name='description']").val(data);
					}else{
						$("textarea[name='description']").val(data);
					}
			   }
			})
		
		}
	}
}