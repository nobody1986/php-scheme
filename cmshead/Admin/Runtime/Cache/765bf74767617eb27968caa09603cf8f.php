<?php if (!defined('THINK_PATH')) exit();?><div class="page">
	<div class="layoutBox">
	
	<form method="post" action="__URL__/insert/navTabId/__MODULE__" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">
		<input type="hidden" name="adder_id" value="<?php echo $_SESSION[C('USER_AUTH_KEY')] ?>"/> 
		<input type="hidden" name="add_time" value="<?php echo time() ?>" />
		<div class="pageFormContent" layoutH="55">

			<div class="unit">
				<label>天气情况：</label>
				<input type="text" class="required" size="30" name="weather"/>
			</div>			
			<div class="unit">
				<label>状态：</label>
				<select name="status">
					<option value="1">启用</option>
					<option value="0">禁用</option>
				</select>
			</div>
			<div class="unit">
				<label>内容：</label>
				<textarea name="content" class="required" rows="3" cols="57"></textarea>
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