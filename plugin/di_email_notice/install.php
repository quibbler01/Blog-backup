<?php

/*
	邮件提醒插件安装文件
	QQ:1778871523
	qq群:558951704
*/

!defined('DEBUG') AND exit('Forbidden');

// 初始化参数数据
$kv = kv_get('email_notice');
if (!$kv) {
    $kv = array(
        'email' => '', 
        'password' => '',
        'smtp' => 'smtp.139.com',
        'port' => '465',//端口
        'fromname' => "发件人姓名(昵称)",//端口
        'email_title' => "邮件标题",//端口
        'email_message' => "邮件内容",//端口
              
    );
    kv_set('email_notice', $kv);
}
	
//数据库增加一个字段
$tablepre = $db->tablepre;
$sql = "ALTER TABLE {$tablepre}user ADD COLUMN email_notice TINYINT NOT NULL DEFAULT 1";
$r = db_exec($sql);

?>