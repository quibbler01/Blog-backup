<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(3);
if(empty($action)){
	if($method == 'GET') {
		
		$config = setting_get('Sm_Statement');
		
		
		$input['radio'] = form_radio_yes_no('radio',$config['radio']);	
		$input['title'] = form_text('title',$config['title']);			
		$input['textarea'] = form_textarea('textarea',$config['textarea'], '100%', 200);	

		include _include(APP_PATH.'plugin/Sm_Statement/setting.htm'); 
		
	} else {
		
		$config = array();

		$config['radio'] = param('radio',0);
		$config['title'] = param('title');
		$config['textarea'] = param('textarea');
		
		setting_set('Sm_Statement',$config);
			
		message(0, '修改成功');
	
	}
}
?>