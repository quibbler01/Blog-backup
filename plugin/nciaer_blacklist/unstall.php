<?php
!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;
$sql = "DROP TABLE IF EXISTS {$tablepre}nciaer_blacklist;";
$r = db_exec($sql);