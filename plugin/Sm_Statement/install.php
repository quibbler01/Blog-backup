<?php
!defined('DEBUG') AND exit('Forbidden');

$kv = array(
'radio' => 1,
'title' => "友情提示：",
'textarea'=> "想怎么写就怎么写，支持HTML",
);
setting_set('Sm_Statement', $kv);
?>