<?php
!defined('DEBUG') AND exit('Access Denied.');
$action = param(1);
user_login_check();
$gid != 1 AND message(-1, 'FALSE'); // 暂定管理员操作

if (empty($method) || $method == 'GET') {
	
	$codehtml = kvcode_get($action);
	include _include(APP_PATH.'plugin/huux_kvcode/view/htm/kvcode_post.htm');

} elseif($action == 'set') {

	$_k = param(2,'');
	$_v = param('codehtml', '', FALSE); // 不过滤
	$r = kvcode_set($_k, $_v); // TRUE or FALSE
	$r === FALSE AND message(-1, lang('update_failed'));
	message(0, lang('update_successfully'));

} elseif($action == 'on') {
	 
	if(kvcode_get('kvcode_on')) {
	 	kvcode_set('kvcode_on', 0);
	 	message(0, lang('update_successfully'));
	} else {
	 	kvcode_set('kvcode_on', 1);
	 	message(0, lang('update_successfully'));
	}

}
?>