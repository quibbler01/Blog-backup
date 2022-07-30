<?php

/*
	插件配置文件 (无配置则不需要此文件)
*/

!defined('DEBUG') AND exit('Access Denied.');

if ($method == 'GET') {
	$kv = kv_get('email_notice');
	//var_dump($kv);
	$input = array();
	$input['email'] = form_text('email', $kv['email']);
	$input['password'] = form_text('password', $kv['password']);
	$input['smtp'] = form_text('smtp', $kv['smtp']);
	$input['port'] = form_text('port', $kv['port']);
	$input['fromname'] = form_text('fromname', $kv['fromname']);
	$input['email_title'] = form_text('email_title', $kv['email_title']);
	$input['email_message'] = form_textarea('email_message', $kv['email_message']);
	
	
	
	include _include(APP_PATH.'plugin/di_email_notice/setting.htm');
	
} else {
	if($_POST['type']){//测试
	include _include(APP_PATH . 'plugin/di_email_notice/model/email_notice.fun.php');
	 //获取配置信息
	$email=kv_get('email_notice');
	$fromname=$email['fromname'];
	$email_title=$email['email_title'];
	$email_message=$email["email_message"];
	$email_user=$email['email'];
	$email_password=(string)$email['password'];
	$email_smtp=$email['smtp'];
	$email_port=$email['port'];
	if(!$email_user) exit('请先保存数据后测试');
	$r=sendEmail($_POST['email'],'测试标题（变量未经过替换）',$email_message,$email_user,$email_password,$email_smtp,$email_port,$fromname); 
	if($r) exit('测试成功');
	else exit('测试失败');

	}else{//保存数据
		$kv = array();
		$kv['email'] = param('email');
		$kv['password'] = param('password');
		$kv['smtp'] = param('smtp');
		$kv['port'] = param('port');
		$kv['fromname'] = param('fromname');
		$kv['email_title'] = param('email_title');
		$kv['email_message'] = param('email_message');
		kv_set('email_notice', $kv);
		
		message(0,'修改成功');
		}
	
}

	
?>