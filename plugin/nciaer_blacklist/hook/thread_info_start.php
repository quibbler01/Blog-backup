<?php exit;
if($user['uid']) {
    $authorid = $thread['user']['uid'];
    if(db_find_one('nciaer_blacklist', array('uid' => $authorid, 'blackuid' => $user['uid']))) {
        message(0, '您在作者的黑名单中，无法查看作者的贴子');
    }
}
