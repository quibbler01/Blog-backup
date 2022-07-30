
/**
* 文件信息
*
* 工具：PhpStorm
* 作者：邹万才
* 网名：秋风雁飞
* 日期：2019-8-4
* 时间：下午 08:46
*/
$aiweline_news_conf = setting_get('aiweline_news_conf');
$aiweline_show_in_index = $aiweline_news_conf['show_in_index'];
$aiweline_fid = isset($aiweline_news_conf['fid'])?$aiweline_news_conf['fid']:0;
if($aiweline_fid && !$aiweline_show_in_index){
    unset($fids[array_search($aiweline_fid,$fids)]);
}