<?php
/**
 * 转载标注插件安装文件
 *
 * @create 2020-03-17
 * @author 成都威尔德 https://www.werde.cn
 */ 
!defined('DEBUG') and exit('Forbidden');
$tablepre = $db->tablepre;
$sql = "ALTER TABLE {$tablepre}post ADD COLUMN source VARCHAR(255) NOT NULL DEFAULT '' COMMENT '转载出处'";
$r = db_exec($sql);
db_exec("ALTER TABLE {$tablepre}thread ADD COLUMN thumbnail VARCHAR(980) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '0' COMMENT '文章缩略图';");

$get_signature = kv_get('post_source');
if (!$get_signature) { $get_signature = array('position'=>'1', 'html'=>'1', 'characters'=>'120', 'report'=>'https://www.werde.cn/'); kv_set('post_source', $get_signature); }
$r === false and message(-1, '<p>创建表结构失败，请检查数据库是否已存在相同字段。</p> <a role="button" class="btn btn-secondary btn-block m-t-1" href="javascript:history.back();">返回</a>');