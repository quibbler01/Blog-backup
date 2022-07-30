<?php
!defined('DEBUG') AND exit('Access Denied.');
if ($method == 'GET') {
    $popup = kv_get('nciaer_popup');
    $title = $popup['title'];
    $content = $popup['content'];
    $showmode = $popup['showmode'];
    $anim = $popup['anim'];
    $btntext = $popup['btntext'];
    $closetime = $popup['closetime'];
    include _include(APP_PATH . 'plugin/nciaer_popup/setting.htm');
} else {
    $title = param('title');
    $content = param('content');
    $showmode = param('showmode', 0);
    $anim = param('anim', 0);
    $btntext = param('btntext', '知道了');
    $closetime = param('closetime', '');
    $popup = array();
    $popup['title'] = $title;
    $popup['content'] = $content;
    $popup['showmode'] = $showmode;
    $popup['anim'] = $anim;
    $popup['btntext'] = $btntext;
    $popup['closetime'] = $closetime;
    kv_set('nciaer_popup', $popup);
    message(0, '提交成功！');
}
