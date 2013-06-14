<?php
// 下载模型
class DownloadModel extends CommonModel {
	// 自动验证设置
	protected $_validate	 =	 array(
		array('title','require','下载标题必填！',1),
		array('tid','require','所属分类必填！',1),
	);
}