<?php

/*
	自丢网www.zidiu.com
	技术维护QQ：515138
*/

!defined('DEBUG') AND exit('Access Denied.');

if($method == 'GET') {
	$setting['zidiu_mzsmdb'] = setting_get('zidiu_mzsmdb');
	include _include(APP_PATH.'plugin/zidiu_mzsmdb/setting.htm');
} else {
	setting_set('zidiu_mzsmdb', param('zidiu_mzsmdb', '', FALSE));
	message(0, '修改成功');
}
	
?>