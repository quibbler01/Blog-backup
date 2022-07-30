<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);

// 不允许删除的版块 / system keeped forum
$system_forum = array(1);



if(empty($action) || $action == 'list') {
	
	
	
	if($method == 'GET') {
		
		
		
		$header['title']        = lang('forum_admin');
		$header['mobile_title'] = lang('forum_admin');
	
		$maxfid = forum_maxid();
		
		
		
		include _include(ADMIN_PATH."view/htm/forum_list.htm");
	
	} elseif($method == 'POST') {
		
		$fidarr = param('fid', array(0));
		$namearr = param('name', array(''));
		$rankarr = param('rank', array(0));
		$iconarr = param('icon', array(''));
		
		
		
		$arrlist = array();
		foreach($fidarr as $k=>$v) {
			$arr = array(
				'fid'=>$k,
				'name'=>array_value($namearr, $k),
				'rank'=>array_value($rankarr, $k)
			);
			
			if(!isset($forumlist[$k])) {
				
				forum_create($arr);
			} else {
				
				forum_update($k, $arr);
			}
			// icon
			if(!empty($iconarr[$k])) {
				
				$s = array_value($iconarr, $k);
				$data = substr($s, strpos($s, ',') + 1);
				$data = base64_decode($data);
				
				$iconfile = "../upload/forum/$k.png";
				file_put_contents($iconfile, $data);
				
				forum_update($k, array('icon'=>$time));
			}
			
			
		}
		
		// 删除 / delete
		$deletearr = array_diff_key($forumlist, $fidarr);
		foreach($deletearr as $k=>$v) {
			if(in_array($k, $system_forum)) continue;
			
			forum_delete($k);
			
		}
		
		forum_list_cache_delete();
		
		
		
		
		
		message(0, lang('save_successfully'));
	}

} elseif($action == 'update') {
	
	$_fid = param(2, 0);
	$_forum = forum_read($_fid);
	empty($_forum) AND message(-1, lang('forum_not_exists'));
	
	
	
	if($method == 'GET') {
		
		$header['title']        = lang('forum_edit');
		$header['mobile_title'] = lang('forum_edit');
	
		
		
		$accesslist = forum_access_find_by_fid($_fid);
		
		if(empty($accesslist)) {
			foreach($grouplist as $group) {
				$accesslist[$group['gid']] = $group; // 字段名相同，直接覆盖。 / same field, directly overwrite
			}
		} else {
			foreach($accesslist as &$access) {
				$access['name'] = $grouplist[$access['gid']]['name']; // 字段名相同，直接覆盖。 / same field, directly overwrite
			}
		}
		array_htmlspecialchars($_forum);
		
		$input = array();
		$input['name'] = form_text('name', $_forum['name']);
		$input['rank'] = form_text('rank', $_forum['rank']);
		$input['brief'] = form_textarea('brief', $_forum['brief'], '100%', 80);
		$input['announcement'] = form_textarea('announcement', $_forum['announcement'], '100%', 80);
		$input['accesson'] = form_checkbox('accesson', $_forum['accesson']);
		$input['modnames'] = form_text('modnames', user_ids_to_names($_forum['moduids']));
		
		
		
		$tag_maxid = tag_maxid();
		$tag_cate_maxid = tag_cate_maxid();
		$tagcatelist = tag_cate_find_by_fid($_fid);
		
		
		
		include _include(ADMIN_PATH."view/htm/forum_update.htm");
	
	} elseif($method == 'POST') {	
		
		$name = param('name');
		$rank = param('rank', 0);
		$brief = param('brief', '', FALSE);
		$announcement = param('announcement', '', FALSE);
		$modnames = param('modnames');
		$accesson = param('accesson', 0);
		$moduids = user_names_to_ids($modnames);
		
		
		
		$arr = array (
			'name' => $name,
			'rank' => $rank,
			'brief' => $brief,
			'announcement' => $announcement,
			'moduids' => $moduids,
			'accesson' => $accesson,
		);

		
		
		forum_update($_fid, $arr);
		
		if($accesson) {
			$allowread = param('allowread', array(0));
			$allowthread = param('allowthread', array(0));
			$allowpost = param('allowpost', array(0));
			$allowattach = param('allowattach', array(0));
			$allowdown = param('allowdown', array(0));
			foreach($grouplist as $_gid=>$v) {
				$access = array (
					'allowread'=>array_value($allowread, $_gid, 0),
					'allowthread'=>array_value($allowthread, $_gid, 0),
					'allowpost'=>array_value($allowpost, $_gid, 0),
					'allowattach'=>array_value($allowattach, $_gid, 0),
					'allowdown'=>array_value($allowdown, $_gid, 0),
				);
				forum_access_replace($_fid, $_gid, $access);
			}
		} else {
			forum_access_delete_by_fid($_fid);
		}
		
		
		
		
		
		$tagcatelist = tag_cate_find_by_fid($_fid);
 		$tagcatelist = arrlist_change_key($tagcatelist, 'cateid');
		$cate_name_arr = param('cate_name', array(''));
		$cate_rank_arr = param('cate_rank', array(0));
		$cate_enable_arr = param('cate_enable', array(0));
		$cate_isforce_arr = param('cate_isforce', array(0));
		$cate_id_arr = array_keys($cate_name_arr);
		$cate_id_arr_old = arrlist_values($tagcatelist, 'cateid');
		$cate_defaulttagid = array_value($_POST, 'cate_defaulttagid', array());
		
		$update = FALSE;
		// 新增 + 更新 / new + update
		foreach($cate_id_arr as $cateid) {
			$defaulttagid = intval(array_value($cate_defaulttagid, $cateid));
			$arr = array(
				'cateid'=>$cateid,
				'fid'=>$_fid,
				'name'=>$cate_name_arr[$cateid],
				'rank'=>$cate_rank_arr[$cateid],
				'enable'=>array_value($cate_enable_arr, $cateid),
				'isforce'=>array_value($cate_isforce_arr, $cateid),
				'defaulttagid'=>$defaulttagid,
			);
			if(isset($tagcatelist[$cateid])) {
				tag_cate_update($cateid, $arr);
			} else {
				if(!$arr['name']) continue;
				tag_cate_create($arr);
			}
			$update = TRUE;
		}
		// 删除 / delete
		$cate_id_delete = array_diff($cate_id_arr_old, $cate_id_arr);
		foreach($cate_id_delete as $cateid) {
			tag_cate_delete($cateid);
			$update = TRUE;
		}
		
		// tag
		$taglist = tag_fetch_from_catelist($tagcatelist);
		$taglist = arrlist_change_key($taglist, 'tagid');
		$tag_name_arr = param('tag_name', array(''));
		$tag_rank_arr = param('tag_rank', array(0));
		$tag_style_arr = param('tag_style', array(''));
		$tag_enable_arr = param('tag_enable', array(0));
		$tag_cate_id_arr = param('tag_cate_id', array(0));
		$tag_id_arr = array_keys($tag_name_arr);
		$tag_id_arr_old = arrlist_values($taglist, 'tagid');
		foreach($tag_id_arr as $tagid) {
			$cateid = array_value($tag_cate_id_arr, $tagid);
			$arr = array(
				'tagid'=>$tagid,
				'cateid'=>$cateid,
				'name'=>$tag_name_arr[$tagid],
				'rank'=>$tag_rank_arr[$tagid],
				'style'=>array_value($tag_style_arr, $tagid, ''),
				'enable'=>array_value($tag_enable_arr, $tagid, 0),
			);
			if(isset($taglist[$tagid])) {
				tag_update($tagid, $arr);
			} else {
				if(!$arr['name']) continue;
				tag_create($arr);
			}
			$update = TRUE;
		}
		$tag_id_delete = array_diff($tag_id_arr_old, $tag_id_arr);
		foreach($tag_id_delete as $tagid) {
			tag_delete($tagid);
			$update = TRUE;
		}
		
		$update AND setting_set('tag_update_time', $time);
		

		
		forum_list_cache_delete();
		
		message(0, lang('edit_sucessfully'));	
	}

// 废弃
} elseif($action == 'getname') {
	
	$uids = xn_urldecode(param(2));
	$arr = explode(',', $uids);
	$names = array();
	$err = '';
	
	
	
	foreach($arr as $_uid) {
		$_uid = intval($_uid);
		if(empty($_uid)) continue;
		$_user = user_read($_uid);
		if(empty($_user)) { $err .= lang('item_not_exists', array('item'=>$_uid)); continue; }
		if($_user['gid'] > 4) { $err .= lang('item_not_moderator', array('item'=>$_uid));  continue; }
		$names[] = $_user['username'];
	}
	$s = implode(',', $names);
	$err AND message(-1, $err);
	
	
	
	message(0, $s);
	
} elseif($action == 'delete') {
	
	$_fid = param(2, 0);
	$_forum = forum_read($_fid);
	empty($_forum) AND message(-1, lang('forum_not_exists'));
	
	in_array($_fid, $system_forum) AND message(-1, 'Not allowed');;
	
	
	
	$threadlist = thread_find_by_fid($_fid, 1, 20);
	if(!empty($threadlist)) {
		message(-1, lang('forum_delete_thread_before_delete_forum'));
	}
	
	$sublist = forum_find_son_list($forumlist, $_fid);
	if(!empty($sublist)) {
		message(-1, lang('forum_please_delete_sub_forum'));
	}
	
	forum_delete($_fid);
	
	forum_list_cache_delete();
	
	
	
	message(0, lang('forum_delete_successfully'));
	
}

function user_names_to_ids($names, $sep = ',') {
	$namearr = explode($sep, $names);
	$r = array();
	foreach($namearr as $name) {
		$user = user_read_by_username($name);
		if(empty($user)) continue;
		$r[] = $user ? $user['uid'] : 0;
	}
	return implode($sep, $r);
}

function user_ids_to_names($ids, $sep = ',') {
	$idarr = explode($sep, $ids);
	$r = array();
	foreach($idarr as $id) {
		$user = user_read($id);
		if(empty($user)) continue;
		$r[] = $user ? $user['username'] : '';
	}
	return implode($sep, $r);
}



?>