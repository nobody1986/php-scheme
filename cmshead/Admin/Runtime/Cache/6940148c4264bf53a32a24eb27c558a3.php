<?php if (!defined('THINK_PATH')) exit();?><div class="page">
	<div class="layoutBox">
        <div class="pageHeader">
            <form id="pagerForm" onsubmit="return navTabSearch(this);" action="__URL__" method="post">
            <input type="hidden" name="pageNum" value="1"/>
            <div class="searchBar">
                <ul class="searchContent">
                    <li>
                        <label>内容：</label>
                        <input type="text" name="content" value="<?php echo ($_REQUEST["content"]); ?>"/>
                    </li>
                    <li>
                        <label>父信息ID：</label>
                        <input type="text" name="pid" value="<?php echo ($_REQUEST["pid"]); ?>"/>
                    </li>
                    <li>
                        <label>文章ID：</label>
                        <input type="text" name="aid" value="<?php echo ($_REQUEST["aid"]); ?>"/>
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
                <li><a class="delete" href="__URL__/foreverdelete/navTabId/__MODULE__" target="selectedTodo" posttype="string" rel="id" title="确实要删除这些记录吗？" warn="请至少选择一条记录"><span>删除</span></a></li>
			</ul>
		</div>
		<table class="list" width="100%" layoutH="100">
			<thead>
			<tr>
				<th><input type="checkbox" group="id" class="checkboxCtrl"></th>
				<th>编号</th>
				<th>父信息ID</th>
				<th>文章ID</th>
				<th>内容</th>
				<th>发表人昵称</th>
				<th>Email</th>
				<th>发表时间</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_message" rel="<?php echo ($vo['id']); ?>">
					<td><input type="checkbox" name="id" value="<?php echo ($vo['id']); ?>" /></td>
					<td><?php echo ($vo['id']); ?></td>
					<td><?php echo ($vo['pid']); ?></td>
					<td><?php echo ($vo['aid']); ?></td>
					<td title="<?php echo ($vo['content']); ?>"><?php echo ($vo['content']); ?></td>
					<td><?php echo ($vo['adder_name']); ?></td>
					<td><?php echo ($vo['adder_email']); ?></td>
					<td><?php echo (date('Y-m-d',$vo['add_time'])); ?></td>
					<td><?php echo (getstatus($vo['status'])); ?></td>
					<td><?php echo (showstatus($vo['status'],$vo['id'])); ?> <?php if(($vo["pid"]) == "0"): ?>| <a class="add" href="__URL__/reply/id/<?php echo ($vo["id"]); ?>" target="dialog" width="590" height="180" mask="true"><span>回复</span></a><?php endif; ?> | <a class="delete" href="__URL__/foreverdelete/id/<?php echo ($vo['id']); ?>/navTabId/__MODULE__" target="ajaxTodo" title="确实要删这条记录吗？"><span>删除</span></a> | <a href="__ROOT__/index.php/Article/view/id/<?php echo ($vo['aid']); ?>" target="_blank"><span>预览</span></a></td>					
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