<?php
return array (
  'db' => 
  array (
    'type' => 'pdo_mysql',
    'mysql' => 
    array (
      'master' => 
      array (
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'Fluent0418',
        'name' => 'xiuno4',
        'tablepre' => 'bbs_',
        'charset' => 'utf8',
        'engine' => 'myisam',
      ),
      'slaves' => 
      array (
      ),
    ),
    'pdo_mysql' => 
    array (
      'master' => 
      array (
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'Fluent0418',
        'name' => 'xiuno4',
        'tablepre' => 'bbs_',
        'charset' => 'utf8',
        'engine' => 'myisam',
      ),
      'slaves' => 
      array (
      ),
    ),
  ),
  'cache' => 
  array (
    'enable' => true,
    'type' => 'mysql',
    'memcached' => 
    array (
      'host' => 'localhost',
      'port' => '11211',
      'cachepre' => 'bbs_',
    ),
    'redis' => 
    array (
      'host' => 'localhost',
      'port' => '6379',
      'cachepre' => 'bbs_',
    ),
    'xcache' => 
    array (
      'cachepre' => 'bbs_',
    ),
    'yac' => 
    array (
      'cachepre' => 'bbs_',
    ),
    'apc' => 
    array (
      'cachepre' => 'bbs_',
    ),
    'mysql' => 
    array (
      'cachepre' => 'bbs_',
    ),
  ),
  'tmp_path' => './tmp/',
  'log_path' => './log/',
  'view_url' => 'view/',
  'upload_url' => 'upload/',
  'upload_path' => './upload/',
  'logo_mobile_url' => 'view/img/logo.png',
  'logo_pc_url' => 'view/img/logo.png',
  'logo_water_url' => 'view/img/water-small.png',
  'sitename' => '析物言理的笔记本',
  'sitebrief' => '<iframe width="250" height="125" src="http://naozhong.net.cn/embed/shijian/#theme=0&m=0&showdate=1" frameborder="0" allowfullscreen></iframe>
<p style="text-indent: 2em;">爱编程的95后，沉淀积累技术。
<p style="text-indent: 2em;">  喜欢研究，朝着架构师方向努力。
<p style="text-indent: 2em;">  踩坑达人，总能遇到各种玄学坑。
<br><br><b>Git ：</b><a href="https://github.com/quibbler01" target="_blank"  style="color:green">https://github.com/quibbler01</a>
<iframe frameborder="no" border="0" marginwidth="0" marginheight="0" width=280 height=86 src="//music.163.com/outchain/player?type=2&id=452111369&auto=0&height=66"></iframe>',
  'timezone' => 'Asia/Shanghai',
  'lang' => 'zh-cn',
  'runlevel' => 5,
  'runlevel_reason' => '备案信息变更，维护中...',
  'cookie_domain' => '',
  'cookie_path' => '',
  'auth_key' => 'H4E6GP86K5W7SMCVFTFAYEQHD8BVDFK9MKHNFM5DKBQ4597PVNCZUPZWFTEYKAVP',
  'pagesize' => 20,
  'postlist_pagesize' => 20,
  'cache_thread_list_pages' => 10,
  'online_update_span' => 120,
  'online_hold_time' => 3600,
  'session_delay_update' => 0,
  'upload_image_width' => 927,
  'order_default' => 'lastpid',
  'attach_dir_save_rule' => 'Ym',
  'update_views_on' => 1,
  'user_create_email_on' => 0,
  'user_create_on' => 0,
  'user_resetpw_on' => 0,
  'admin_bind_ip' => 0,
  'cdn_on' => 0,
  'url_rewrite_on' => 0,
  'disabled_plugin' => 0,
  'version' => '4.0.4',
  'static_version' => '?1.0',
  'installed' => 1,
  'site_keywords' => '个人，博客，技术，安卓，积累',
  'user_create_io' => 1,
  'attach_maxsize' => 20480000,
);
?>