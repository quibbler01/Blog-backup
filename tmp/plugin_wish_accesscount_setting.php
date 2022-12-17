<?php
/*
	唯诚网络出品91wc.net
	技术维护QQ：1198845956
*/
!defined('DEBUG') AND exit('Access Denied.');
if($method == 'GET') {
    //展示
	$setting['wish_accesscount'] = setting_get('wish_accesscount');
	include _include(APP_PATH.'plugin/wish_accesscount/setting.htm');
} else {
    //保存
    $data['count'] = param('wish_accesscount_count', '', FALSE);
    $data['words'] = param('wish_accesscount_words', '', FALSE);
	setting_set('wish_accesscount', $data);
	message(0, '修改成功');
}
