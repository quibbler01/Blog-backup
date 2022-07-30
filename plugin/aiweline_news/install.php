<?php

defined('DEBUG') OR exit('Forbidden');

// 添加插件配置
$aiweline_news_config = array(
    "api" => 'https://www.apiopen.top/journalismApi',
    'map' => '                                                {
    "code": "code",
    "msg": "msg",
    "data": "data",
    "data_filter": "tech,auto",
    "db_fields_map": "title=>subject,link__digest__source=>message",
    "allow_image_type":"jpg,png"
}  ',
    "allow_image_type" => "jpg,png",
    "pattern" => '/<div class="article-content">[\s\S]*<\/div>/is',
    "not_pattern" => '/<div class="footer">[\s\S]*<\/div>/is',
    "news_count" => 0,
    "show_in_index" => 0,
    "replace_rules" => 'data-src=>src,playsinline="true"=>,webkit-playsinline="true"=>',
);
setting_set('aiweline_news_conf', $aiweline_news_config);
?>