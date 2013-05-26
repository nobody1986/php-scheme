<?php if (!defined('THINK_PATH')) exit();?><div class="page">
	<div class="layoutBox">
        <div class="pageHeader">
            <form id="pagerForm" onsubmit="return navTabSearch(this);" action="__URL__" method="post">
            <input type="hidden" name="pageNum" value="1"/>
            <div class="searchBar">
                <ul class="searchContent">
                    <li>
                        <label>用户名：</label>
                        <input type="text" name="account" value=""/>
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
				<li><a class="add" href="__URL__/add" target="dialog" width="590" height="340" mask="true"><span>新增</span></a></li>
				<li><a class="edit" href="__URL__/edit/id/{sid_user}" target="dialog" width="590"  mask="true" warn="请选择一条记录"><span>编辑</span></a></li>
                <li><a class="delete" href="__URL__/foreverdelete/navTabId/__MODULE__" target="selectedTodo" posttype="string" rel="id" title="确实要删除这些记录吗？" warn="请至少选择一条记录"><span>删除</span></a></li>
				<li class="line">line</li>
				<li><a class="icon" href="__URL__/password/id/{sid_user}" target="dialog" mask="true" warn="请选择一条记录"><span>修改密码</span></a></li>
			</ul>
		</div>

		<table class="list" width="100%" layoutH="100">
			<thead>
			<tr>
				<th><input type="checkbox" group="id" class="checkboxCtrl"></th>
				<th>编号</th>
				<th>用户名</th>
				<th>昵称</th>
				<th>Email</th>
				<th>添加时间</th>
				<th>上次登录</th>
				<th>登录次数</th>
				<th>状态</th>
			</tr>
			</thead>
			<tbody>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr target="sid_user" rel="<?php echo ($vo['id']); ?>">
					<td><input type="checkbox" name="id" value="<?php echo ($vo['id']); ?>" /></td>
					<td><?php echo ($vo['id']); ?></td>
					<td><?php echo ($vo['account']); ?></td>
					<td><?php echo ($vo['nickname']); ?></td>
					<td><?php echo ($vo['email']); ?></td>
					<td><?php echo (date("Y-m-d",$vo['create_time'])); ?></td>
					<td><?php if(!empty($$vo['last_login_time'])): echo (date("Y-m-d H:i:s",$vo['last_login_time'])); endif; ?></td>
					<td><?php echo ($vo['login_count']); ?></td>
					<td><?php echo (showstatus($vo['status'],$vo['id'])); ?> | <a class="delete" href="__URL__/foreverdelete/id/<?php echo ($vo['id']); ?>/navTabId/__MODULE__" target="ajaxTodo" title="确实要删这条记录吗？"><span>删除</span></a></td>
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