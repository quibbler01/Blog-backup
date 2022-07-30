<?php 

!defined('DEBUG') AND exit('Access Denied.');

$action = param(3);

$fidarr = param('fid',array());



if($method == 'GET') {	
//初始化设置	
$ax_forum_out = kv_get('ax_forum_out');
if(empty($ax_forum_out)) {
	$ax_forum_out = array(
	'fidarr'=>$fidarr,
	);
  kv_set('ax_forum_out', $ax_forum_out);
}	

			include _include(APP_PATH.'plugin/ax_forum_out/setting.htm');


			
} else{

	$ax_forum_out['fidarr'] = $fidarr;



	kv_set('ax_forum_out', $ax_forum_out);		
	message(0, '修改成功');
}
	



?>
