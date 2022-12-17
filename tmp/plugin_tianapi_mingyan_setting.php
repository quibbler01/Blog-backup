<?php

/*
	插件配置文件 (无配置则不需要此文件)
*/

!defined('DEBUG') AND exit('Access Denied.');

if ($method == 'GET') {
    $input['tianapi_apikey'] = form_textarea('tianapi_apikey', kv_get('apikey'), '', 38);

    include _include(APP_PATH.'plugin/tianapi_mingyan/setting.htm');
} else {
	$tianapi_apikey = param('tianapi_apikey');
    kv_set('apikey', $tianapi_apikey);
    message(0, '修改成功');
}



?>