<?php if (!defined('THINK_PATH')) exit();?><div class="page">
	<div class="layoutBox">
        <div class="pageHeader">
            <form id="pagerForm" onsubmit="return navTabSearch(this);" action="__URL__" method="post">
            <input type="hidden" name="pageNum" value="1"/>
            <div class="searchBar">
                <ul class="searchContent">
                    <li>
                        <label>视频标题：</label>
                        <input type="text" name="title" value="<?php echo ($_REQUEST["title"]); ?>"/>
                    </li>
                </ul>
                <div class="subBar">
                    <ul>
                        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li>
                    </ul>
                </div>
            </div>
            </form>
        </div>
	
		<div class="panelBar">
			<ul class="toolBar">
				<li><a class="add" href="__URL__/add" target="dialog" width="610" height="500" mask="true"><span>新增</span></a></li>
				<li><a class="edit" href="__URL__/edit/id/{sid_category}" target="dialog" width="610" height="550" mask="true" warn="请选择一条记录"><span>编辑</span></a></li>
                <li><a class="delete" href="__URL__/foreverdelete/navTabId/__MODULE__" target="selectedTodo" posttype="string" rel="id" title="确实要删除这些记录吗？" warn="请至少选择一条记录"><span>删除</span></a></li>
			</ul>
		</div>

		<table class="list" width="100%" layoutH="100">
			<thead>
			<tr>
				<th><input type="checkbox" group="id" class="checkboxCtrl"></th>
				<th>编号</th>
				<th>视频标题</th>
				<th>预览图片</th>
				<th>所属分类</th>
				<th>录入者</th>
				<th>添加时间</th>
				<th>排序值</th>
				<th>URL重写</th>
				<th>使用模板</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_category" rel="<?php echo ($vo['id']); ?>">
					<td><input type="checkbox" name="id" value="<?php echo ($vo['id']); ?>" /></td>
					<td><?php echo ($vo['id']); ?></td>
					<td><a class="edit" href="__URL__/edit/id/<?php echo ($vo['id']); ?>" target="dialog" width="610" height="550" mask="true"><?php echo ($vo['title']); ?></a></td>
					<td><?php if(!empty($vo["img"])): ?><a href="__PUBLIC__/Upload/video/<?php echo ($vo["img"]); ?>" target="_blank"><img src="__PUBLIC__/Upload/video/<?php echo ($vo["img"]); ?>" height="40" border="0" /></a><?php endif; ?></td>
					<td><?php echo (getcategoryname($vo['tid'])); ?></td>
					<td><?php echo (getusername($vo['adder_id'])); ?></td>
					<td><?php echo (date('Y-m-d H:i:s',$vo['add_time'])); ?></td>
					<td><?php echo ($vo['sort']); ?></td>
					<td><?php echo ($vo['rewrite']); ?></td>
					<td><?php echo ($vo['template']); ?></td>
					<td><?php echo (getstatus($vo['status'])); ?></td>
					<td><?php echo (showstatus($vo['status'],$vo['id'])); ?> | <a class="add" href="__URL__/view/id/<?php echo ($vo["id"]); ?>" target="dialog" width="590" height="400" rel="<?php echo ($vo["id"]); ?>" title="<?php echo ($vo["title"]); ?>" >播放</a> | <a class="delete" href="__URL__/foreverdelete/id/<?php echo ($vo['id']); ?>/navTabId/__MODULE__" target="ajaxTodo" title="确实要删这条记录吗？"><span>删除</span></a> | <a href="__ROOT__/index.php/Video/view/id/<?php echo ($vo['id']); ?>" target="_blank"><span>预览</span></a></td>					
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>

		<div class="panelBar">
			<div class="pages">
				<span>共<?php echo ($totalCount); ?>条</span>
			</div>
			<div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
		</div>

	</div>
</div>