<?php
!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;

$sql = "CREATE TABLE IF NOT EXISTS {$tablepre}nciaer_blacklist (
    uid int(11) unsigned NOT NULL DEFAULT 0, 
    blackuid int(11) unsigned NOT NULL DEFAULT 0,
	dateline int(10) unsigned NOT NULL DEFAULT 0,
	key(uid, blackuid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
$r = db_exec($sql);

