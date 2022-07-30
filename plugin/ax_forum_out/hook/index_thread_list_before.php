<?php exit;
$ax_forum_out = kv_get('ax_forum_out');
$fids_filter = $ax_forum_out['fidarr'];
foreach($fids_filter as $k) {
    if(isset($forumlist_show[$k])){
        unset($forumlist_show[$k]);
    }
}

?>