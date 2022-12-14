<?php

!defined('DEBUG') AND exit('Access Denied.');

include _include(XIUNOPHP_PATH.'xn_send_mail.func.php');

$action = param(1);

is_numeric($action) AND $action = '';

if($action == 'digest') {

	$_uid = param(2, 1);
	$page = param(3, 1);
	$pagesize = 20;
	
	$_user = user_read($_uid);
	$digests = $_user['digests'];
	$pagination = pagination(url("user-$_uid-{page}-1"), $digests, $page, $pagesize);
	$threadlist = thread_digest_find_by_uid($_uid, $page, $pagesize);
	
	thread_list_access_filter($threadlist, $gid);
	
	$active = 'thread';
	if($ajax) {
		foreach($threadlist as &$thread) $thread = thread_safe_info($thread);
		message(0, $threadlist);
	} else {
		include _include(APP_PATH.'view/htm/user.htm');
		exit;
	}
}

if(empty($action)) {

        

        $_uid = param(1, 0);
        empty($_uid) AND $_uid = $uid;
        $_user = user_read($_uid);

	empty($_user) AND message(-1, lang('user_not_exists'));
	
        $header['title'] = $_user['username'];
        $header['mobile_title'] = $_user['username'];

        if($ajax) {
	$_user = user_safe_info($_user);
        foreach($threadlist as &$thread) $thread = thread_safe_info($thread);
        message(0, array('user'=>$_user, 'threadlist'=>$threadlist));
}
        
	include _include(APP_PATH.'view/htm/user.htm');
	
} elseif($action == 'thread') {

        

        $_uid = param(2, 0);
        empty($_uid) AND $_uid = $uid;
        $_user = user_read($_uid);
        
        empty($_user) AND message(-1, lang('user_not_exists'));
        $header['title'] = $_user['username'];
        $header['mobile_title'] = $_user['username'];

        $page = param(3, 1);
        $pagesize = 20;
        $totalnum = $_user['threads'];
        $pagination = pagination(url("user-thread-$_uid-{page}"), $totalnum, $page, $pagesize);
        $threadlist = mythread_find_by_uid($_uid, $page, $pagesize);
        thread_list_access_filter($threadlist, $gid);

        
       
	include _include(APP_PATH.'view/htm/user_thread.htm');
	
} elseif($action == 'login') {

		!ipaccess_check($longip, 'logins') AND message(-1, '?????? IP ?????????????????????????????????');
	
	if($method == 'GET') {

		
		
		$referer = user_http_referer();
			
		$header['title'] = lang('user_login');
		
		
		
		include _include(APP_PATH.'view/htm/user_login.htm');

	} else if($method == 'POST') {

				ipaccess_inc($longip, 'logins');
		
		$email = param('email');			// ????????????????????? / email or mobile
		$password = param('password');
		empty($email) AND message('email', lang('email_is_empty'));
		if(is_email($email, $err)) {
			$_user = user_read_by_email($email);
			empty($_user) AND message('email', lang('email_not_exists'));
		} else {
			$_user = user_read_by_username($email);
			empty($_user) AND message('email', lang('username_not_exists'));
		}

		!is_password($password, $err) AND message('password', $err);
		$check = (md5($password.$_user['salt']) == $_user['password']);
		
		!$check AND message('password', lang('password_incorrect'));

		// ???????????????????????????
		// update login times
		user_update($_user['uid'], array('login_ip'=>$longip, 'login_date' =>$time , 'logins+'=>1));

		// ???????????? $uid ??????????????????????????? register_shutdown_function() ????????? session (??????: model/session.func.php)
		// global variable $uid will save to session in register_shutdown_function() (file: model/session.func.php)
		$uid = $_user['uid'];
		
		$_SESSION['uid'] = $uid;
		
		user_token_set($_user['uid']);
		
		
		
		// ?????? token????????????????????????
		
		message(0, lang('user_login_successfully'));

	}

} elseif($action == 'create') {

	

!empty($conf['user_create_io']) && message(0, jump('?????????, ????????????????????????', http_referer(), 1));


	
	empty($conf['user_create_on']) AND message(-1, lang('user_create_not_on'));
	
	if($method == 'GET') {
		
		
		
		$referer = user_http_referer();
		$header['title'] = lang('create_user');
		
		
		
		include _include(APP_PATH.'view/htm/user_create.htm');

	} else if($method == 'POST') {
				
		
		
		$email = param('email');
		$username = param('username');
		$password = param('password');
		$code = param('code');
		empty($email) AND message('email', lang('please_input_email'));
		empty($username) AND message('username', lang('please_input_username'));
		empty($password) AND message('password', lang('please_input_password'));
		
		if($conf['user_create_email_on']) {
			$sess_email = _SESSION('user_create_email');
			$sess_code = _SESSION('user_create_code');
			empty($sess_code) AND message('code', lang('click_to_get_verify_code'));
			empty($sess_email) AND message('code', lang('click_to_get_verify_code'));
			$email != $sess_email AND message('code', lang('verify_code_incorrect'));
			$code != $sess_code AND message('code', lang('verify_code_incorrect'));
		}
		
		!is_email($email, $err) AND message('email', $err);
		$_user = user_read_by_email($email);
		$_user AND message('email', lang('email_is_in_use'));
		
		!is_username($username, $err) AND message('username', $err);
		$_user = user_read_by_username($username);
		$_user AND message('username', lang('username_is_in_use'));
		
		!is_password($password, $err) AND message('password', $err);
		
		$salt = xn_rand(16);
		$pwd = md5($password.$salt);
		$gid = 101;
		$_user = array (
			'username' => $username,
			'email' => $email,
			'password' => $pwd,
			'salt' => $salt,
			'gid' => $gid,
			'create_ip' => $longip,
			'create_date' => $time,
			'logins' => 1,
			'login_date' => $time,
			'login_ip' => $longip,
		);
		$uid = user_create($_user);
		$uid === FALSE AND message('email', lang('user_create_failed'));
		$user = user_read($uid);
	
		// ?????? session
		
		unset($_SESSION['user_create_email']);
		unset($_SESSION['user_create_code']);
		$_SESSION['uid'] = $uid;
		user_token_set($uid);
		
		$extra = array('token'=>user_token_gen($uid));
		
		include APP_PATH.'plugin/jan_identicon/Identicon.php';	ipaccess_inc($longip, 'users');
		
		message(0, lang('user_create_sucessfully'), $extra);
	}
	
} elseif($action == 'logout') {
	
	
	
	$uid = 0;
	$_SESSION['uid'] = $uid;
	user_token_clear();
	
	
	
	message(0, jump(lang('logout_successfully'), http_referer(), 1));
	//message(0, jump('????????????', './', 1));
	
// ??????????????? 1 ??? | reset password first step
} elseif($action == 'resetpw') {
	
	
	
	!$conf['user_resetpw_on'] AND message(-1, '??????????????????????????????');
		
	if($method == 'GET') {

		
		
		$header['title'] = lang('resetpw');
		
		
		
		include _include(APP_PATH.'view/htm/user_resetpw.htm');

	} else if($method == 'POST') {
		
		
		
		$email = param('email');
		empty($email) AND message('email', lang('please_input_email'));
		!is_email($email, $err) AND message('email', $err);
		
		$_user = user_read_by_email($email);
		!$_user AND message('email', lang('email_is_not_in_use'));

		$code = param('code');
		empty($code) AND message('code', lang('please_input_verify_code'));
		
		$sess_email = _SESSION('user_resetpw_email');
		$sess_code = _SESSION('user_resetpw_code');
		empty($sess_code) AND message('code', lang('click_to_get_verify_code'));
		empty($sess_email) AND message('code', lang('click_to_get_verify_code'));
		$email != $sess_email AND message('code', lang('verify_code_incorrect'));
		$code != $sess_code AND message('code', lang('verify_code_incorrect'));
	
		$_SESSION['resetpw_verify_ok'] = 1;
		
		
		
		message(0, lang('check_ok_to_next_step'));
	}

// ??????????????? 3 ??? | reset password step 3
} elseif($action == 'resetpw_complete') {
	
	
	
	// ????????????
	$email = _SESSION('user_resetpw_email');
	$resetpw_verify_ok = _SESSION('resetpw_verify_ok');
	(empty($email) || empty($resetpw_verify_ok)) AND message(-1, lang('data_empty_to_last_step'));
	
	$_user = user_read_by_email($email);
	empty($_user) AND message(-1, lang('email_not_exists'));
	$_uid = $_user['uid'];
	
	if($method == 'GET') {

		
		
		$header['title'] = lang('resetpw');
		
		
		
		include _include(APP_PATH.'view/htm/user_resetpw_complete.htm');

	} else if($method == 'POST') {
		
		
		
		$password = param('password');
		empty($password) AND message('password', lang('please_input_password'));
		
		$salt = $_user['salt'];
		$password = md5($password.$salt);
		user_update($_uid, array('password'=>$password));
		
		!is_password($password, $err) AND message('password', $err);
		
		unset($_SESSION['user_resetpw_email']);
		unset($_SESSION['user_resetpw_code']);
		unset($_SESSION['resetpw_verify_ok']);
		
		
		
		message(0, lang('modify_successfully'));
		
	}

// ???????????????
} elseif($action == 'send_code') {
	
	$method != 'POST' AND message(-1, lang('method_error'));
	
	
	
	$action2 = param(2);
	
	// ????????????
	if($action2 == 'user_create') {
		
		$email = param('email');
		
		empty($email) AND message('email', lang('please_input_email'));
		!is_email($email, $err) AND message('email', $err);
		empty($conf['user_create_email_on']) AND message(-1, lang('email_verify_not_on'));
		$_user = user_read_by_email($email);
		!empty($_user) AND message('email', lang('email_is_in_use'));
		
		$code = rand(100000, 999999);
		$_SESSION['user_create_email'] = $email;
		$_SESSION['user_create_code'] = $code;
		
	
	// ?????????????????????????????????
	} elseif($action2 == 'user_resetpw') {
		
		$email = param('email');
		
		empty($email) AND message('email', lang('please_input_email'));
		!is_email($email, $err) AND message('email', $err);
		$_user = user_read_by_email($email);
		empty($_user) AND message('email', lang('email_is_not_in_use'));
		
		empty($conf['user_resetpw_on']) AND message(-1, lang('resetpw_not_on'));
		
		$code = rand(100000, 999999);
		$_SESSION['user_resetpw_email'] = $email;
		$_SESSION['user_resetpw_code'] = $code;

	} else {
		message(-1, 'action2 error');
	}
	
	
	$subject = lang('send_code_template', array('rand'=>$code, 'sitename'=>$conf['sitename']));
	$message = $subject;
	
	$smtplist = include _include(APP_PATH.'conf/smtp.conf.php');
	$n = array_rand($smtplist);
	$smtp = $smtplist[$n];
	
	
	$r = xn_send_mail($smtp, $conf['sitename'], $email, $subject, $message);
	
	
	if($r === TRUE) {
		message(0, lang('send_successfully'));
	} else {
		xn_log($errstr, 'send_mail_error');
		message(-1, $errstr);
	}

// ??????????????????????????????| sync login implement simply
/* 
	????????????????????? token ????????????????????? | send user information to other system by token
	??????????????? auth_key ????????????????????? xn_encrypt() xn_decrypt() ???????????????all subsystem set auth_key to correct by xn_encrypt() xn_decrypt()
*/
} elseif($action == 'synlogin') {

	// ??????????????? token | check token
	$token = param('token');
	$return_url = param('return_url');
	
	$s = xn_decrypt($token);
	!$s AND message(-1, lang('unauthorized_access'));
	list($_time, $_useragent) = explode("\t", $s);
	$useragent != $_useragent AND message(-1, lang('authorized_get_failed'));
	
	empty($_SESSION['return_url']) AND $_SESSION['return_url'] = $return_url;
	if(!$uid) {
		http_location(url('user-login'));
	} else {
		$return_url = _SESSION('return_url');
		
		empty($return_url) AND message(-1, lang('request_synlogin_again'));
		unset($_SESSION['return_url']);
		
		$arr = array(
			'uid'=>$user['uid'],
			'gid'=>$user['gid'],
			'username'=>$user['username'],
			'avatar_url'=>$user['avatar_url'],
			'email'=>$user['email'],
			'mobile'=>$user['mobile'],
		);
		$s = xn_json_encode($arr);
		$s = xn_encrypt($s);
		
		// ??? token ????????? URL??????????????? | add token into URL, jump back
		$url = xn_urldecode($return_url).'?token='.$s;
		//$url = xn_url_add_arg($return_url, 'token', $s);
		http_location($url);
	}

} else {
	
}




?>
