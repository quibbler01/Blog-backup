<?php

/*
	Xiuno BBS 4.0 插件实例：TAG 插件安装
	admin/plugin-install-xn_tag.htm
*/

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;

$sql = "create TABLE if not exists {$tablepre}navlinks (
    `lid` int(11) unsigned not null auto_increment,
    `icon` char (255)   not null    default '',
    `name` char(255)    not null    default '',
    `link` char(255)    not null    default '',
    `rank` tinyint(11)   not null    default 0,
    primary key (lid)
)";
$r = db_exec($sql);
?>
