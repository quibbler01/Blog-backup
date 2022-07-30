<?php

// 其实可以直接传入$arr，不用每次重复读取。
function kvcode_get($_k, $k = NULL) {
	$k = $k ? $k : 'huux_kvcode';
	$arr = setting_get($k); //重复读取
	empty($arr) AND $arr = array();
	$r = isset($arr[$_k]) ? $arr[$_k] : '';
	return $r;
}

function kvcode_set($_k, $_v, $k = NULL) {	
	if(empty($_k)) return FALSE;
	$k = $k ? $k : 'huux_kvcode';
	$arr = setting_get($k);
	empty($arr) AND $arr = array();
	$arr[$_k] = $_v;
	$r = setting_set($k, $arr);
	empty($r) AND $r = FALSE;
	return TRUE;
}
function kvcode_display($_k, $c = NULL, $p = NULL) {
	global $gid;
	if(empty($_k)) return '';
	$c = $c ? $c : 'container > row > col';
	$p = $p ? $p.' : ' : '';
	$s = kvcode_get($_k);
	$on = kvcode_get('kvcode_on');
	if($on && $gid == 1) $s = '<div class="kvcode-container"><b class="l">'.$p.$c.'</b><b class="r btn" role="button" data-modal-url="'.url("kvcode-$_k").'" data-modal-title="'.lang('kvcode_editcode').'" data-modal-arg="'.$_k.'" data-modal-size="lg modal-kvcode">'.lang('kvcode_editcode').'</b><div class="kvcode-body">'.$s.'</div></div>';
	return $s;
}

?>