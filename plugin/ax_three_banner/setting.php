<?php 
!defined('DEBUG') AND exit('Access Denied.');
$action = param(3);

$banner_img1 = param('banner_img1');
$banner_url1 = param('banner_url1');

$banner_img2 = param('banner_img2');
$banner_url2 = param('banner_url2');

$banner_img3 = param('banner_img3');
$banner_url3 = param('banner_url3');

$ax_banner = kv_get('ax_banner');

if($method == 'GET') {

			if(empty($ax_banner)) {
				$ax_banner = array(
					'banner_img1'=>$banner_img1,
					'banner_url1'=>$banner_url1, 

					'banner_img2'=>$banner_img2,
					'banner_url2'=>$banner_url2,

					'banner_img3'=>$banner_img3,
					'banner_url3'=>$banner_url3,

				);
				kv_set('ax_banner', $ax_banner);
			}


			
				include _include(APP_PATH.'plugin/ax_three_banner/setting.htm');
			
			
		
	

} else {

		
			//插件基础设置
			$ax_banner['banner_img1'] = $banner_img1;
			$ax_banner['banner_url1'] = $banner_url1;

			$ax_banner['banner_img2'] = $banner_img2 ;
			$ax_banner['banner_url2'] = $banner_url2 ;
			
			$ax_banner['banner_img3'] = $banner_img3 ;
			$ax_banner['banner_url3'] = $banner_url3;
			kv_set('ax_banner', $ax_banner);	
		
		message(0, '提交成功！');
}
	


?>
