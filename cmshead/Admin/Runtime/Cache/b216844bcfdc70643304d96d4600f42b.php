<?php if (!defined('THINK_PATH')) exit();?><div class="page">
	<div class="layoutBox">
	
	<form method="post" action="__URL__/insert/navTabId/__MODULE__" class="pageForm required-validate" enctype="multipart/form-data" onsubmit="return iframeCallback(this, navTabAjaxDone);">
		<input type="hidden" name="adder_id" value="<?php echo $_SESSION[C('USER_AUTH_KEY')] ?>"/>
		<input type="hidden" name="add_time" value="<?php echo time() ?>"/>
		<input type="hidden" name="update_time" value="<?php echo time() ?>"/>
		<div class="pageFormContent" layoutH="55">

			<div class="unit">
				<label>标题：</label>
				<input type="text" class="required" size="60" maxlength="120" name="title"/>
			</div>
			<div class="unit">
				<label>所属分类：</label>
                <input type="text" class="required" size="42" readonly="readonly" id="categoryName_add" name="categoryName" />
                <a class="unit btn" href="__URL__/tree/mod/__MODULE__/cid/tid_add/cname/categoryName_add" target="dialog" rel="tree" mask="true" title="选择分类" width="400" height="450">选择分类</a>
                <input type="hidden" id="tid_add" name="tid" />
			</div>
			<div class="unit">
				<label>关键字：</label>
				<input type="text" name="keywords" size="60"/>
			</div>
			<div class="unit">
				<label>描述：</label>
				<input type="text" name="description" size="60"/>
			</div>
			<div class="unit">
				<label>预览图片：</label>
				<input type="file" name="img">
			</div>
			<div class="unit">
				<label>状态：</label>
				<select name="status">
					<option value="1">启用</option>
					<option value="0">禁用</option>
				</select>
			</div>
			<div class="unit">
				<label>排序值：</label>
				<input type="text" name="sort" size="30"/>
			</div>
			<div class="unit">
				<label>URL重写值：</label>
				<input type="text" name="rewrite" size="30"/>
			</div>
			<div class="unit">
				<label>使用模板：</label>
				<input type="text" name="template" id="template" size="30" value="<?php echo ($vo["template"]); ?>"/>
                <a href="__URL__/seltpl/tplname/template" class="unit btn" target="dialog" rel="btn_tpl" mask="true" title="选择模板" width="600" height="600">选择模板</a>
                <span>格式如：</span>
                <select>
                <option>直接选择模板</option>
                <option>index2.html</option>
                <option>index2.php</option>
                <option>index2</option>
                <option>Public:abc</option>
                </select>
			</div>					
			<div class="unit">
				<label>内容：</label>
				<textarea class="editor" name="content" height="350" tools="full" upLinkUrl="__APP__/article/upload/" upImgUrl="__APP__/article/upload/" upFlashUrl="__APP__/article/upload/" upMediaUrl="__APP__/article/upload/"></textarea>
			</div>
			
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	
	</div>
</div>