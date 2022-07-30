<?php

/*
	插件配置文件 (无配置则不需要此文件)
*/

!defined('DEBUG') AND exit('Access Denied.');

// 1.0版本 直接跳转到首页点击头部导航 ”</>“ 按钮进入编辑状态，暂无后台设置。
http_location('../'.url('index'));

?>