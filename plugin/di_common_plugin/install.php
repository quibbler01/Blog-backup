<?php

/*
	插件安装文件
*/

!defined('DEBUG') AND exit('Forbidden');
//初始化
if(empty(kv_get('common_plugin')))  
	kv_set('common_plugin',array('list'=>'一行一个，填写插件英文名'));

?>