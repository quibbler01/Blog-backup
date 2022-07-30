<?php
/*
	插件升级文件
*/
!defined('DEBUG') AND exit('Forbidden');
$tablepre = $db->tablepre;
$sql = "ALTER TABLE {$tablepre}user ADD COLUMN email_notice TINYINT NOT NULL DEFAULT 1";
$r = db_exec($sql);

?>