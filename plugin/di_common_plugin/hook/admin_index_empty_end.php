	// 初始化插件变量 / init plugin var
	
	plugin_init();
	$pluginlist = $plugins;	

  $common_plugin_text=kv_get('common_plugin');
  $common_plugin=explode("\n",$common_plugin_text['list']);
  $common_plugin=array_trim($common_plugin);//清除空白字符
  $commonlist=array();
  foreach($pluginlist as $dir=> $plugin) {
	  if(in_array($dir,$common_plugin)){//常用插件核心语句
			$commonlist[$dir]=$plugin;
	   }
  }

  
  