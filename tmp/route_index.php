<?php

/*
* Copyright (C) 2015 xiuno.com
*/

!defined('DEBUG') AND exit('Access Denied.');



$page = param(1, 1);
$order = $conf['order_default'];
$order != 'tid' AND $order = 'lastpid';
$pagesize = $conf['pagesize'];
$active = 'default';

// 从默认的地方读取主题列表
$thread_list_from_default = 1;

//<?
//过滤$forumlist_show
$thread_list_from_default = 0;

if($thread_list_from_default == 0){
    $wish_forumlist_show = $forumlist_show;
    $wish_indexhideforum = setting_get('wish_indexhideforum');

    if(!empty($wish_indexhideforum['hide_forums'])){
        $wish_indexhideforum['hide_forums'] = str_replace('，', ',', $wish_indexhideforum['hide_forums']);
        $wish_indexhideforum['hide_forums'] = explode(',', $wish_indexhideforum['hide_forums']);
        $wish_indexhideforum['hide_forums'] = array_map('trim', $wish_indexhideforum['hide_forums']);
        $wish_indexhideforum['hide_forums'] = array_filter($wish_indexhideforum['hide_forums']);
        if(!empty($wish_indexhideforum['hide_forums'])){
            //这种方法可以避免$key和fid不一致的意外
            foreach ($wish_forumlist_show as $key => $forum_item){
                if(in_array($forum_item['fid'], $wish_indexhideforum['hide_forums'])){
                    unset($wish_forumlist_show[$key]);
                }
            }
            //也可以用这种方法，性能高了一点点
            /*foreach ($wish_indexhideforum['hide_forums'] as $hide_fid){
                unset($wish_forumlist_show[$hide_fid]);
            }*/
        }
    }

    //查询帖子数据
    $fids = arrlist_values($wish_forumlist_show, 'fid');
    $threads = arrlist_sum($wish_forumlist_show, 'threads');
    $pagination = pagination(url("$route-{page}"), $threads, $page, $pagesize);

    
    $threadlist = thread_find_by_fids($fids, $page, $pagesize, $order, $threads);

    //判断是否在导航显示
    if(!empty($wish_indexhideforum['show_in_nav']) && $wish_indexhideforum['show_in_nav'] == 'no'){
        $forumlist_show = $wish_forumlist_show;
    }
}

if($thread_list_from_default) {
	$fids = arrlist_values($forumlist_show, 'fid');
	$threads = arrlist_sum($forumlist_show, 'threads');
	$pagination = pagination(url("$route-{page}"), $threads, $page, $pagesize);
	
	
	$threadlist = thread_find_by_fids($fids, $page, $pagesize, $order, $threads);
}

// 查找置顶帖
if($order == $conf['order_default'] && $page == 1) {
	$toplist3 = thread_top_find(0);
	$threadlist = $toplist3 + $threadlist;
}

// 过滤没有权限访问的主题 / filter no permission thread
thread_list_access_filter($threadlist, $gid);

// SEO
$header['title'] = $conf['sitename']; 				// site title
$header['keywords'] = ''; 					// site keyword
$header['description'] = $conf['sitebrief']; 			// site description
$_SESSION['fid'] = 0;



include _include(APP_PATH.'view/htm/index.htm');

?>