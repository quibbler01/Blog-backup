<?php

/*
	插件配置文件 (无配置则不需要此文件)
*/

!defined('DEBUG') AND exit('Access Denied.');

if($method == 'GET') {
	//初始化插件列表
	 plugin_init();
	$pluginlist = $plugins;	
	//获取配置数据
	$kv = kv_get('common_plugin');
	$input = array();
	$input['list'] = form_textarea('list', $kv['list']);
	
	include _include(APP_PATH.'plugin/di_common_plugin/setting.htm'); // 这里包含一个插件配置界面文件
	
} else {

		kv_set('common_plugin',$_POST);
		message(0,'修改成功');


}
	
?>