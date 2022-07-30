<?php
/**
 * 文件信息
 *
 * 工具：PhpStorm
 * 作者：邹万才
 * 网名：秋风雁飞
 * 日期：2019-8-1
 * 时间：下午 11:14
 */
//读取配置
$aiweline_news_conf = setting_get('aiweline_news_conf');

$api = isset($aiweline_news_conf['api']) ? $aiweline_news_conf['api'] : false;
$fid = isset($aiweline_news_conf['fid']) ? $aiweline_news_conf['fid'] : false;
$uid = isset($aiweline_news_conf['uid']) ? $aiweline_news_conf['uid'] : false;
$map = isset($aiweline_news_conf['map']) ? json_decode($aiweline_news_conf['map']) : false;
!$api AND message('api', lang('Api不能为空！'));
!$fid AND message('fid', lang('版块ID不能为空！'));
!$uid AND message('uid', lang('代发用户ID不能为空！'));
!$map AND message('map', lang('数据库映射字段为空！或者存在配置错误，请检查！'));

//检查请求
$api_news = aiweline_news_api($api);

$api_news[$map->code] != 200 AND message('map', lang('api请求失败。'));
$api_news = $api_news[$map->data];
empty($api_news) AND message('map', lang('api请求无数据，请检查是否数据字段映射错误。'));

//api信息列表
 $api_news_list = array();

//检查是否配置data_filter  tech,auto
if (property_exists($map, 'data_filter')) {
    if (!empty($map->data_filter) && $map->data_filter != '*') {
        $filters = explode(',', $map->data_filter);
        foreach ($filters as $filter) {
            if (isset($api_news[$filter])) {
                $api_news_list = array_merge($api_news_list, $api_news[$filter]);
            } else {
                message('map', $filter . lang('属性在data数据集中根本不存在，请重新检查你的map映射规则。'));
            }
        }
        $api_news = $api_news_list;
    }else{
      foreach($api_news as $value){
         $api_news_list = array_merge($api_news_list,$value);
        }
      $api_news = $api_news_list;
    }
}else{
  foreach($api_news as $value){
         $api_news_list = array_merge($api_news_list,$value);
        }
         $api_news = $api_news_list;
}
//pb($api_news,1);
//检查板块
$forum = forum_read($fid);
empty($forum) AND message('fid', lang('forum_not_exists'));

//检查用户权限
//$r = forum_access_user($fid, $gid, 'allowthread');
//!$r AND message(-1, lang('user_group_insufficient_privilege'));

//数据库准备
use \Db\Mysql;

$mysql = new Mysql();
$mysql->query('SET NAMES UTF8');

//字段映射
$db_map = explode(',', $map->db_fields_map);

//允许的图片格式
$image_types = explode(',', $map->allow_image_type);

//解析数据变量
$parser_data = array();

//正则提取
$pattern = $aiweline_news_conf['pattern'];

//正则剔除
$not_pattern = explode('=>', $aiweline_news_conf['not_pattern']);

//插入主题
foreach ($api_news as $api_new) {
    //主题对应数据库数据解析
    $new_data = array();
    $error = false;
    foreach ($db_map as $item) {
        $content = null;
        $source_to_db_arr = explode('=>', $item);
        //检查是否多个字段合并内容分隔符‘__’
        if (strstr($item, '__')) {
            foreach (explode('__', $source_to_db_arr[0]) as $v) {
                //检查@
                $v_attr = false;
                if (strstr($v, '@')) {
                    $v_fields = explode('@', $v);
                    $v = $v_fields[0];
                    $v_attr = $v_fields[1];
                }
                //检查字段是否存在
                if (isset($api_new[$v])) {
                    //检查字符串
                    if (is_string($api_new[$v])) {
                        //网址检测
                        if (preg_match("#(http|https)://(.*\.)?.*\..*#i", $api_new[$v])) {
                            $url_arr = explode('.', $api_new[$v]);

                            if (in_array($url_arr[count($url_arr) - 1], $image_types)) {
                                $content .= "<br><img src='" . aiweline_news_download_curl_image($api_new[$v], 'upload/plugin/aiweline/news/images/') . "'>";
                            } else {
                                //读取网页信息到本地
                                $html = aiweline_news_curl($api_new[$v]);
                                $matchs = null;
                                preg_match($pattern, $html, $matchs);
                                foreach ($matchs as $match) {
                                    foreach ($not_pattern as $n_p) {
                                        $m = null;
                                        preg_match($n_p, $match, $m);
                                        $m_content = str_replace($m, '', $match);
                                        if ($aiweline_news_conf['replace_rules']) {
                                            foreach (explode(',', $aiweline_news_conf['replace_rules']) as $replace_rule) {
                                                $rule_arr = explode('=>', $replace_rule);
                                                if (count($rule_arr) != 2) message('content', lang('请检查你的属性替换规则！'));
                                                $m_content = str_replace($rule_arr[0], $rule_arr[1], $m_content);
                                            }
                                            $content .= $m_content;
                                        } else {
                                            $content .= $m_content;
                                        }
                                    }

                                }
                            }
                        } else {
                            $content .= $api_new[$v];
                        }
                    }
                    //检查数组
                    if (is_array($api_new[$v])) {
                        //存在@属性
                        if ($v_attr) {
                            foreach ($api_new[$v] as $a_item) {
                                //检查网址
                                if (preg_match("#(http|https)://(.*\.)?.*\..*#i", $a_item[$v_attr])) {
                                    //检查网址是否图片网址
                                    $url_arr = explode('.', $a_item[$v_attr]);
                                    if (in_array($url_arr[count($url_arr) - 1], $image_types)) {
                                        if ($aiweline_news_conf['download'] == 1 || $aiweline_news_conf['download'] == '1') {
                                            $content .= "<br><img src='" . aiweline_news_download_curl_image($a_item[$v_attr], 'upload/plugin/aiweline/news/images/') . "'>";
                                        } else {
                                            $content .= "<br><img src='" . $a_item[$v_attr] . "'>";
                                        }
                                    } else {
                                        //读取网页信息到本地
                                        $html = aiweline_news_curl($a_item[$v_attr]);
                                        $matchs = null;
                                        preg_match($pattern, $html, $matchs);
                                        foreach ($matchs as $match) {
                                            foreach ($not_pattern as $n_p) {
                                                $m = null;
                                                preg_match($n_p, $match, $m);
                                                //检查属性替换
                                                $m_content = str_replace($m, '', $match);
                                                if ($aiweline_news_conf['replace_rules']) {
                                                    foreach (explode(',', $aiweline_news_conf['replace_rules']) as $replace_rule) {
                                                        $rule_arr = explode('=>', $replace_rule);
                                                        if (count($rule_arr) != 2) message('content', lang('请检查你的属性替换规则！'));
                                                        $m_content = str_replace($rule_arr[0], $rule_arr[1], $m_content);
                                                    }
                                                    $content .= $m_content;
                                                } else {
                                                    $content .= $m_content;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $content .= $a_item[$v_attr];
                                }
                            }
                        } else {
                          $error = true;
                          break;
                            //message('check', lang('请检查映射@子属性：' . $v_attr . '，此字段未能在"' . $v . '"中找到。187'));
                        }

                    }
                } else {
                  $error = true;
                  break;
                    //message('check', lang('请检查映射：' . $v . '，此字段在原json中找不到！,193'));
                }
            }
        } else {
            //检查字段
            if (!isset($api_new[$source_to_db_arr[0]])) {
              $error = true;
              break;
               //message('check', lang('请检查映射：' . $source_to_db_arr[0] . '，此字段在原json中找不到！200'));
            };
            if (preg_match("#(http|https)://(.*\.)?.*\..*#i", $api_new[$source_to_db_arr[0]])) {
                $url_arr = explode('.', $api_new[$source_to_db_arr[0]]);
                if (in_array($url_arr[count($url_arr) - 1], $image_types)) {
                    if ($aiweline_news_conf['download'] == 1 || $aiweline_news_conf['download'] == '1') {
                        $content .= "<br><img src='" . aiweline_news_download_curl_image($api_new[$source_to_db_arr[0]], 'upload/plugin/aiweline/news/images/') . "'>";
                    } else {
                        $content .= "<br><img src='" . $api_new[$source_to_db_arr[0]] . "'>";
                    }
                } else {
                    //读取网页信息到本地
                    $html = aiweline_news_curl($api_new[$source_to_db_arr[0]]);
                    $matchs = null;
                    preg_match($pattern, $html, $matchs);
                    foreach ($matchs as $match) {
                        foreach ($not_pattern as $n_p) {
                            $m = null;
                            preg_match($n_p, $match, $m);
                            //检查属性替换
                            $m_content = str_replace($m, '', $match);
                            if ($aiweline_news_conf['replace_rules']) {
                                foreach (explode(',', $aiweline_news_conf['replace_rules']) as $replace_rule) {
                                    $rule_arr = explode('=>', $replace_rule);
                                    if (count($rule_arr) != 2) message('content', lang('请检查你的属性替换规则！'));
                                    $m_content = str_replace($rule_arr[0], $rule_arr[1], $m_content);
                                }
                                $content .= $m_content;
                            } else {
                                $content .= $m_content;
                            }
                        }
                    }
                }
            } else {
                $content .= $api_new[$source_to_db_arr[0]];
            }
        }
        $new_data[$source_to_db_arr[1]] = $content;
        $new_data['doctype'] = 1;
    }
    //数据库导入
    if (trim($new_data['subject']) && !$mysql->table('thread')->where(['subject' => $new_data['subject']])->find() && !$error) {
        $time = time();
        //主题发表事务
        $mysql->begin_transaction();
        $thread = false;
        try {
            $mysql->table('thread')->insert(
                array(
                    'fid' => $fid,
                    'uid' => $uid,
                    'create_date' => $time,
                    'last_date' => $time,
                    'subject' => $new_data['subject']
                )
            )->fetch();
            $mysql->commit();
        } catch (Exception $e) {
            $mysql->rollBack();
            message('thread', lang('主题发表失败！'));
        }
        //POST数据发表事务
        $mysql->begin_transaction();
        try {
            //查找thread数据
            $thread_info = $mysql->table('thread')
                ->where(['subject' => $new_data['subject'], 'create_date' => $time, 'last_date' => $time])
                ->find();
            //插入post
            $mysql->table('post')->insert(
                array(
                    'tid' => $thread_info['tid'],
                    'uid' => $uid,
                    'doctype' => 1,
                    'create_date' => $time,
                    'isfirst' => 1,
                    'message' => $new_data['message'],
                    'message_fmt' => $new_data['message'],
                )
            )->fetch();
            //查找post数据
            $post_info = $mysql->table('post')
                ->where(['tid' => $thread_info['tid']])
                ->find();

            //更新thread数据
            $mysql->table('thread')
                ->where(['subject' => $new_data['subject'], 'create_date' => $time, 'last_date' => $time])
                ->update(['firstpid' => $post_info['pid'], 'lastpid' => $post_info['pid']]);
            $mysql->commit();
        } catch (Exception $e) {
            $mysql->rollBack();
            message('post', lang('post数据失败！'));
        }
        //mythread数据发表事务
        $mysql->begin_transaction();
        try {
            $thread_ = $mysql->table('thread')
                ->where(
                    [
                        'subject' => $new_data['subject'],
                        'create_date' => $time,
                        'last_date' => $time
                    ]
                )->find();
            $mysql->table('mythread')->insert(
                array(
                    'tid' => $thread_['tid'],
                    'uid' => $uid
                )
            )->fetch();
            $mysql->commit();
        } catch (Exception $e) {
            $mysql->rollBack();
            message('mythread', lang('mythread数据失败！'));
        }
        //设置导入新闻条数计数
        $aiweline_news_conf = setting_get('aiweline_news_conf');
        $aiweline_news_conf['news_count'] += 1;
        setting_set('aiweline_news_conf', $aiweline_news_conf);
    }
    array_push($parser_data, $new_data);
}
message(1, jump('头条拉取成功！', WEBSITE . 'admin/' . url('aiweline_news')));
