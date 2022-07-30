<?php exit;
$source = param('source', '', FALSE);
if($source){
	post_update($pid, array('source'=>$source,'message'=>$message,'doctype'=>$doctype,));
}

?>