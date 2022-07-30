elseif($action == 'email_notice') {
	
	if($method == 'GET') {
		 $checked=$user['email_notice'];
		include _include(APP_PATH.'plugin/di_email_notice/view/my_email_notice.htm');
	
	} else {
		$res=user_update($uid, array('email_notice'=>param('email_notice')));
		if($res)  message(0, '设置成功');
		else    message(0, '设置失败');
		
	}
}
