<?php

/*
	插件安装文件
*/

!defined('DEBUG') AND exit('Forbidden');

$setting = setting_get('huux_kvcode');

if(empty($setting)) {
	$setting = array(
		'kvcode_on' => 1,
		'header_link_after' => '<style></style>'
	);
	setting_set('huux_kvcode', $setting);
}
?>