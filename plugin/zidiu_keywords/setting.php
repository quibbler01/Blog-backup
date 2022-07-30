<?php

/*
	自丢网www.zidiu.com
	技术维护QQ：515138
*/
!defined('DEBUG') AND exit('Access Denied.');
if($method == 'GET') {
	$setting['zidiu_keywords'] = setting_get('zidiu_keywords');
	include _include(APP_PATH.'plugin/zidiu_keywords/setting.htm');
} else {

	setting_set('zidiu_keywords', param('zidiu_keywords', '', FALSE));
	setting_set('zidiu_keywords', param('zidiu_keywords', '', FALSE));
	message(0, '修改成功');
}
	
?>