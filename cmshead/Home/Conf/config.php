<?php

$config = require './config.php';
$home_config = array(
    'APP_FILE_CASE' => true, // 是否检查文件的大小写 对Windows平台有效
    'TMPL_CACHE_ON' => false, //开启模板缓存
    'URL_CASE_INSENSITIVE' => true, //URL不区分大小写
    'URL_MODEL' => 2, //服务器开启Rewrite模块时，可去除URL中的index.php
    'USER_AUTH_KEY' => 'authId', // 用户认证SESSION标记
    'DB_LIKE_FIELDS' => 'title|remark|content', //搜索Like匹配字段
);
return array_merge($config, $home_config);