<?php

defined('DEBUG') OR exit('Forbidden');

if ($method == 'get') {

    $header['title'] = '微蓝新闻';
    $aiweline_news_conf = setting_get('aiweline_news_conf');

    //var_dump(AIWELINE_NEWS_HTML.'setting.htm');die;
    include _include(AIWELINE_NEWS_HTML.'setting.htm');
} else {
    $mapJsonStr = param('map','{}');
    //去除特殊转行，制表符等
    $jsonStr =  preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', trim($mapJsonStr));
    //去除空格
    $jsonStr = preg_replace('# #','',$jsonStr);
    //html解码，使得内容不包含html实体元素
    $jsonStr = htmlspecialchars_decode($jsonStr);
    //去除bom空格
    $jsonArr= json_decode(trim($jsonStr,chr(239).chr(187).chr(191)),true);
    $aiweline_news_conf = array(
        'api'=>param('api'),
        'uid'=>param('uid'),
        'fid'=>param('fid'),
        'download'=>param('download'),
        'news_count'=>param('news_count'),
        'show_in_index'=>param('show_in_index'),
        'pattern'=>trim(html_entity_decode(param('pattern')),' '),
        'not_pattern'=>trim(html_entity_decode(param('not_pattern')),' '),
        'map'=>trim($jsonStr,' '),
        'replace_rules'=>trim(html_entity_decode(param('replace_rules')),' ')
    );

    setting_set('aiweline_news_conf', $aiweline_news_conf);

    message(1, jump('配置保存成功', WEBSITE.'admin/'.url('aiweline_news')));
}
