<?php

!defined('DEBUG') AND exit('Access Denied.');

if ($method == 'GET') {
    $config = setting_get('zl_share');
    $config = $config ? json_decode($config, true) : array(
        "qzone" => "on",
        "qq" => "on",
        "tencent" => "on",
        "weibo" => "on",
        "wechat" => "on",
        "douban" => "on",
        "diandian" => "on",
        "linkedin" => "on",
        "facebook" => "on",
        "twitter" => "on",
        "google" => "on"
    );
    include_once _include(APP_PATH.'plugin/zl_share/setting.htm');
} else {
    $config = json_encode($_POST);
    setting_set('zl_share', $config);
    echo json_encode(array("code" => 1));
}