<?php
if(!defined('CMSHEAD_INSTALL')) exit('Access Denied');
$lang = array(
	'install_is_lock' => '你的网站已经安装过CMSHEAD，需重新安装请手动删除网站目录下Install/install.lock文件', 
	'install_db_error' => '数据库文件无法读取，请检查Install/inc/cmshead.sql是否存在。',
	'install_title' => 'CMSHEAD 2.0 正式版 安装向导',
	'welcome_to_install' => '欢迎安装',
	'install_wizard' => '安装向导',
	'install_error' => '安装出错',
	'install_tips' => '注意',
	'install_tips_content' => '这个安装程序仅仅用在你首次安装CMSHEAD。如果你已经在使用 CMSHEAD 或者要更新到一个新版本，请不要运行这个安装程序。',
	'install_notes' => '安装须知',
	'install_notes_content' => '<p>一、运行环境需求：PHP(5.2.0+)+MYSQL(4.1+)</p><p>二、安装步骤：<br />1、使用ftp工具以二进制模式，将该软件包里的 CMSHEAD 目录下的文件上传到您的空间，假设上传后目录网站根目录。<br />2、如果您使用的是Linux 或 Freebsd 服务器，先确认以下目录或文件属性为 (777) 可写模式。<br />目录: Public<br />目录: Install<br />文件: config.php<br />3、运行 http://yourwebsite/Install/index.php 安装程序，填入安装相关信息与资料，完成安装！<br />4、运行 http://yourwebsite/index.php 开始体验CMSHEAD2.0Beta版！<br />5、如安装完成后访问异常，请手动删除Admin/Runtime和Home/Runtime目录下所有文件。</p>',
	'install_license' => '安装许可协议',
	'install_license_content' =>'
版权所有 (C) 2010，CMSHEAD 保留所有权利。

CMSHEAD是由 还是这个味 独立开发的CMS程序，基于PHP脚本和MySQL数据库。本程序源码开放的，任何人都可以从互联网上免费下载，并可以在不违反本协议规定的前提下进行使用而无需缴纳程序使用费。

官方网址： www.cmshead.com

为了使你正确并合法的使用本软件，请你在使用前务必阅读清楚下面的协议条款：

一、本授权协议适用且仅适用于CMSHEAD任何版本，CMSHEAD官方拥有对本授权协议的最终解释权和修改权。

二、协议许可的权利和限制
1、您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用，但我们也不承诺对个人用户提供任何形式的技术支持。
2、您可以在协议规定的约束和限制范围内修改CMSHEAD源代码或界面风格以适应您的网站要求，但不可以公开对外发布。
3、您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。
4、未经商业授权，不得将本软件用于商业用途(企业网站或以盈利为目的经营性网站)，否则我们将保留追究的权力。

三、免责声明
1、本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。
2、用户出于自愿而使用本软件，您必须了解使用本软件的风险，任何情况下，程序的质量风险和性能风险完全由您承担。有可能证实该程序存在漏洞，您需要估算与承担所有必需服务，恢复，修正，甚至崩溃所产生的代价！在尚未购买产品技术服务之前，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。
3、请务必仔细阅读本授权协议，在您同意授权协议的全部条件后，即可继续CMSHEAD的安装。即：您一旦开始安装CMSHEAD，即被视为完全同意本授权协议的全部内容，如果出现纠纷，我们将根据相关法律和协议条款追究责任。

',
	'install_agree' => '我已看过并同意安装许可协议',
	'install_disagree_license' => '您必须在同意授权协议的全部条件后，方可继续CMSHEAD的安装',
	'install_env' => '服务器配置',
	'php_os' => '操作系统',
	'php_version' => 'PHP版本',
	'file_upload' => '附件上传',
	'php_extention' => 'PHP扩展',
	'php_extention_unload_gd' => '您的服务器没有安装这个PHP扩展：gd',
	'php_extention_unload_mbstring' => '您的服务器没有安装这个PHP扩展：mbstring',
	'php_extention_unload_mysql' => '您的服务器没有安装这个PHP扩展：mysql',
	'php_extention_unload_curl' => '您的服务器没有安装这个PHP扩展：curl',
	'mysql' => 'MySQL数据库',
	'mysql_unsupport' => '您的服务器不支持MYSQL数据库，无法安装CMSHEAD。',
	'dirmod' => '目录和文件属性',
	'install_setting' => '数据库和网站设置',
	'install_mysql_host' => '数据库地址',
	'install_mysql_host_intro' => '格式：地址(:端口)，一般为 localhost',
	'install_mysql_username' => '数据库用户名',
	'install_mysql_password' => '数据库密码',
	'install_mysql_name' => '数据库名',
	'install_mysql_prefix' => '表名前缀',
	'install_mysql_prefix_intro' => '同一数据库安装多CMSHEAD程序时可改变默认值',
	'founder' => '创建者设置',
	'install_founder_name' => '创建者账号',
	'install_founder_password' => '密码',
	'install_founder_rpassword' => '重复密码',
	'install_founder_email' => '电子邮件',
	'site_url' => '网站域名',
	'site_name'=> '网站名称',
	'site_keywords'=> '网站关键字',
	'site_description'=> '网站描述',
	'icp_num' => 'ICP备案号',
	'icp_num_intro' =>'如:京ICP:1000000号',
	'site_url_intro' => '将作为网站首页的地址',
	'install_mysql_host_empty' => '数据库服务器不能为空',
	'install_mysql_username_empty' => '数据库用户名不能为空',
	'install_mysql_name_empty' => '数据库名不能为空',
	'install_founder_name_empty' => '创建者用户名不能为空',
	'install_founder_password_length' => '创建者密码必须大于6位',
	'install_founder_rpassword_error' => '两次输入管理员密码不同',
	'install_founder_email_empty' => '创建者Email不能为空',
	'mysql_invalid_configure' => '数据库配置信息不完整',
	'mysql_invalid_prefix' => '您指定的数据表前缀包含点字符(".")，请返回修改。',
	'forbidden_character' => '用户名包含非法字符',
	'founder_invalid_email' => '电子邮件格式不正确',
	'founder_invalid_configure' => '创建者信息不完整',
	'founder_invalid_password' => '密码长度必须大于6位',
	'founder_invalid_rpassword' => '两次输入的密码不一致',
	'config_log_success' => '数据库配置信息写入完成',
	'config_read_failed' => '数据库配置文件写入错误，请检查config.php文件是否存在或属性是否为777',
	'cmshead_rebuild' => '数据库中已经安装过 CMSHEAD，继续安装会清空原有数据！',
	'mysql_import_data' => '点击下一步开始导入数据',
	'import_processing' => '导入数据库',
	'import_processing_error' => '导入数据库失败',
	'create_table' => '创建表',
	'create_founder' => '创建创建者帐户',
	'create_founder_success' => '创建者帐户创建成功',
	'create_founder_error' => '创建者帐户创建失败',
	'install_success' => '安装成功',
	'install_failure' => '安装失败',
	'reinstall' => '<a href="index.php">请点击这里重新安装</a>',
	'install_success_intro' => '安装程序执行完毕，请尽快删除整个 Install 目录，以免被他人恶意利用。如果Home和Admin目录下有Runtime目录，请将这两个目录一并删除，否则会出现无法访问的情况，。如要重新安装，请删除Install目录中的 install.lock 文件！<br /><a href="../index.php">请点击这里开始体验CMSHEAD吧！</a>',
	'install_dbFile_error' => '数据库文件无法读取，请检查Install/inc/cmshead.sql是否存在。',
	
	'install_step_wizard' => '安装向导',
	'install_step_notes' => '安装须知',
	'install_step_license' => '许可协议',
	'install_step_os' => '环境检测',
	'install_step_option' => '安装配置',
	'install_step_config' => '写入配置',
	'install_step_import' => '导入数据',
	'install_step_finish' => '安装完成',
	
	'install_btn_start' => '开始安装CMSHEAD！',
	'install_btn_prev' => '上一步',
	'install_btn_next' => '下一步',
	'go_home' => '前往前台',
	'go_admin' => '前往后台',
	'support' => '支持',
	'unsupport' => '不支持',
);
?>