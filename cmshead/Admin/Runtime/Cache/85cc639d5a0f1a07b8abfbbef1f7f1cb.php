<?php if (!defined('THINK_PATH')) exit();?>
<ul class="tree treeFolder collapse">
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="javascript:;"<?php if(!empty($_REQUEST['selroot']) || empty($vo['sub_category'])): ?>onclick="selectClass('<?php echo ($vo["id"]); ?>','<?php echo ($vo["title"]); ?>');"<?php endif; ?>><?php echo ($vo["title"]); ?></a>
		<?php if(!empty($vo["sub_category"])): ?><ul>
				<?php if(is_array($vo["sub_category"])): $i = 0; $__LIST__ = $vo["sub_category"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_vo): $mod = ($i % 2 );++$i;?><li><a href="javascript:;" onclick="selectClass('<?php echo ($sub_vo["id"]); ?>','<?php echo ($sub_vo["title"]); ?>');"><?php echo ($sub_vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul><?php endif; ?>
	</li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
<script language="javascript">
function selectClass(cid,cname){
	if($('#<?php echo ($cid); ?>').length>0){
		var v = (","+$('#<?php echo ($cid); ?>').val()+",").indexOf(","+cid+",")>-1 ? 
			(","+$('#<?php echo ($cid); ?>').val()+",").replace(eval('/,'+cid+',|,'+cid+'|'+cid+',/g'),',') : 
			($('#<?php echo ($cid); ?>').val()+","+cid);
			v = v.replace(/^,|,$/g,'');
		$('#<?php echo ($cid); ?>').val(v);
	}
	if($('#<?php echo ($cname); ?>').length>0){
		var v = (","+$('#<?php echo ($cname); ?>').val()+",").indexOf(","+cname+",")>-1 ? 
			(","+$('#<?php echo ($cname); ?>').val()+",").replace(eval('/,'+cname+',|,'+cname+'|'+cname+',/g'),',') : 
			($('#<?php echo ($cname); ?>').val()+","+cname);
			v = v.replace(/^,|,$/g,'');
		$('#<?php echo ($cname); ?>').val(v);
	}
}
</script>