<?php if (!defined('THINK_PATH')) exit();?><div class="page">
	<div class="layoutBox">
		<div class="panelBar">
			<ul class="toolBar">
				<li><a class="delete" href="__URL__/clear/navTabId/__MODULE__" target="ajaxTodo"><span>清除缓存</span></a></li>
				<li><a class="add" href="__URL__/backdb/"><span>备份数据库</span></a></li>
				<li><a class="edit" href="__URL__/querysql/navTabId/__MODULE__" target="dialog" width="800"><span>执行SQL语句</span></a></li>
				<li><a class="edit" href="__URL__/rss/navTabId/__MODULE__" target="ajaxTodo"><span>生成RSS</span></a></li>
			</ul>
		</div>		
	</div>
</div>