<?php
$uid = param(1);
if($user['uid']) {
    if($user['uid'] == $uid) {
        message(0, jump('您不能对自己操作该功能', http_referer(), 2));
    } else {
        if($rs = db_find_one('nciaer_blacklist', array('uid' => $user['uid'], 'blackuid' => $uid))) {
            db_delete('nciaer_blacklist', array('uid' => $user['uid'], 'blackuid' => $uid));
        } else {
            db_insert('nciaer_blacklist', array('uid' => $user['uid'], 'blackuid' => $uid, 'dateline' => time()));
        }
        message(0, jump('操作成功', http_referer(), 2));
    }
} else {
    message(0, jump('请先登录', http_referer(), 2));
}
