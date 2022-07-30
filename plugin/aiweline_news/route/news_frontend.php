<?php
defined('DEBUG') OR exit('Forbidden');

header("Content-Type: text/html;charset=utf-8");

//  定义网站地址
define('WEBSITE',$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/');

// 定义插件根目录
define('AIWELINE_NEWS_ROOT', APP_PATH.'plugin/aiweline_news/');

// 定义插件视图根目录
define('AIWELINE_NEWS_HTML', AIWELINE_NEWS_ROOT.'html/');

// 定义插件动作根目录
define('AIWELINE_NEWS_INC', AIWELINE_NEWS_ROOT.'inc/');

// 统一请求
$method = strtolower($method);

$action = param(1);
empty($action) and $action = 'pull';

$actions = array(
	'pull'
);

// 引入动作
if (!in_array($action, $actions)) {
	message(1, jump('访问错误', 'back'));
}

// 判断文件
$aiwelineFile = AIWELINE_NEWS_INC.$action.'.php';
if (!file_exists($aiwelineFile)) {
	message(1, jump($action . '动作不存在', 'back'));
}

//导入自定义数据库
include _include(AIWELINE_NEWS_ROOT.'Db/Mysql.php');

//功能函数
include _include(AIWELINE_NEWS_ROOT.'mode/news.func.php');

// 具体文件
include _include($aiwelineFile);


