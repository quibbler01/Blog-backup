<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);

if($action == 'digest') {
	$page = param(2, 1);
	$pagesize = 20;
	
	$digests = $user['digests'];
	$pagination = pagination(url("user-$uid-{page}-1"), $digests, $page, $pagesize);
	$threadlist = thread_digest_find_by_uid($uid, $page, $pagesize);
	
	include _include(APP_PATH.'plugin/xn_digest/view/htm/my_digest.htm');
}

$user = user_read($uid);
user_login_check();

$header['mobile_title'] = $user['username'];
$header['mobile_linke'] = url("my");

is_numeric($action) AND $action = '';

$active = $action;



if(empty($action)) {
	
	$header['title'] = lang('my_home');
	
	
	
	include _include(APP_PATH.'view/htm/my.htm');
	
/*	
} elseif($action == 'profile') {
	
	if($ajax) {
		// user_safe_info($user);
		message(0, $user);
	} else {
		include _include(APP_PATH.'view/htm/my_profile.htm');
	}
*/
	
} elseif($action == 'password') {
	
	if($method == 'GET') {
		
		
		
		include _include(APP_PATH.'view/htm/my_password.htm');
		
	} elseif($method == 'POST') {
		
		
		
		$password_old = param('password_old');
		$password_new = param('password_new');
		$password_new_repeat = param('password_new_repeat');
		$password_new_repeat != $password_new AND message(-1, lang('repeat_password_incorrect'));
		md5($password_old.$user['salt']) != $user['password'] AND message('password_old', lang('old_password_incorrect'));
		$password_new = md5($password_new.$user['salt']);
		$r = user_update($uid, array('password'=>$password_new));
		$r === FALSE AND message(-1, lang('password_modify_failed'));
		
		
		message(0, lang('password_modify_successfully'));
		
	}
	

} elseif($action == 'thread') {

	
	
	$page = param(2, 1);
	$pagesize = 20;
	$totalnum = $user['threads'];
	
	
	
	$pagination = pagination(url('my-thread-{page}'), $totalnum, $page, $pagesize);
	$threadlist = mythread_find_by_uid($uid, $page, $pagesize);
	
	if($ajax) {
	$user = user_safe_info($user);
	foreach($threadlist as &$thread) $thread = thread_safe_info($thread);
	 message(0, array('user'=>$user, 'threadlist'=>$threadlist));
}
	
	include _include(APP_PATH.'view/htm/my_thread.htm');

	
} elseif($action == 'avatar') {
	
	if($method == 'GET') {
		
		
		
		include _include(APP_PATH.'view/htm/my_avatar.htm');
	
	} else {
		
		
		
		$width = param('width');
		$height = param('height');
		$data = param('data', '', FALSE);
		
		empty($data) AND message(-1, lang('data_is_empty'));
		$data = base64_decode_file_data($data);
		$size = strlen($data);
		$size > 40000 AND message(-1, lang('filesize_too_large', array('maxsize'=>'40K', 'size'=>$size)));
		
		$filename = "$uid.png";
		$dir = substr(sprintf("%09d", $uid), 0, 3).'/';
		$path = $conf['upload_path'].'avatar/'.$dir;
		$url = $conf['upload_url'].'avatar/'.$dir.$filename;
		!is_dir($path) AND (mkdir($path, 0777, TRUE) OR message(-2, lang('directory_create_failed')));
		
		
		file_put_contents($path.$filename, $data) OR message(-1, lang('write_to_file_failed'));
		
		user_update($uid, array('avatar'=>$time));
		
		
		
		message(0, array('url'=>$url));
		
	}
}


if ($action == 'signature') {
    if ($method == 'GET') {
        include _include(APP_PATH.'plugin/art_signature/view/htm/signature.htm');
    } elseif ($method == 'POST') {
        $strlimit = $get_signature['characters'];
        if (isset($strlimit) && $strlimit >= 1 && $strlimit <= 255) {
            $strlimit = $strlimit;
        } else {
            $strlimit = "120";
        }
        $my_signature = param('my_signature', '', $htmlspecialchars = false);
        if (!empty($my_signature)) {
            if (xn_strlen($my_signature) > $strlimit) {
                message(0, "不能超过".$strlimit."个字符哦。");
            } else {
                include _include(APP_PATH.'plugin/art_signature/model/closetags.php');				
                $my_signature = strip_tags($my_signature, "<a>");				
                $my_signature = CloseTags($my_signature);
                $my_signature = htmlspecialchars($my_signature);
                $do = user_update($uid, array('signature' => $my_signature));
                $do === false and message(0, '签名设定失败！');
                message(0, "签名设定成功");
            }
        } else {
            user_update($uid, array('signature' => '')) and message(0, "您没有输入内容，所以前台会显示“懒人签名”。");
        }
    }
}

elseif ($action == 'post_like') {
	
	if (isset($haya_post_like_config['open_my_post_like'])
		&& $haya_post_like_config['open_my_post_like'] != 1
	) {
		message(-1, lang('haya_post_like_my_no_post_like_tip'));
	}

	$pagesize = intval($haya_post_like_config['my_post_like_pagesize']);
	$page = param(2, 1);
	$cond['uid'] = $uid; 
	
	$haya_post_like_count = haya_post_like_count($cond);
	$haya_post_like_post_list = haya_post_like_find($cond, array('create_date' => -1), $page, $pagesize);
	if (!empty($haya_post_like_post_list)) {
		foreach ($haya_post_like_post_list as & $haya_post_like_post_value) {
			$haya_post_like_post_value['thread'] = thread_read_cache($haya_post_like_post_value['tid']);
		}
	}
	
	$pagination = pagination(url("my-post_like-{page}"), $haya_post_like_count, $page, $pagesize);
	
	include _include(APP_PATH.'plugin/haya_post_like/view/htm/my_post_like.htm');
}





?>