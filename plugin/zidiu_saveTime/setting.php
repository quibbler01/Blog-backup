<?php
/*
	自丢网www.zidiu.com
	技术维护QQ：515138
*/
!defined('DEBUG') AND exit('Access Denied.');
if($method == 'GET') {
	$setting['zidiu_saveTime'] = setting_get('zidiu_saveTime');
	include _include(APP_PATH.'plugin/zidiu_saveTime/setting.htm');
} else {

	setting_set('zidiu_saveTime', param('zidiu_saveTime', '', FALSE));
	message(0, '修改成功');
}
	
?>