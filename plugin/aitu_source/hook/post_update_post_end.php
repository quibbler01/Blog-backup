<?php exit;
$source = param('source', '', FALSE);
$r = post_update($pid, array('source'=>$source,'doctype'=>$doctype, 'message'=>$message));
$r === FALSE AND message(-1, lang('update_post_failed'));

?>
