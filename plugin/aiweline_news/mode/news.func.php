<?php
function pb($data,$flag=true){
  echo '<pre>';
  echo print_r($data);
  //echo '</pre>';
  if($flag){
    die;
  }
}
/*
*从api获取的新闻
*/
function aiweline_news_api($api)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api);
    //如果接口URL是https的,我们将其设为不验证,如果不是https的接口,这句可以不用加
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //参数为1表示传输数据，为0表示直接输出显示。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //参数为0表示不带头文件，为1表示带头文件
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //请求超时
    curl_setopt($ch, CONNECTION_TIMEOUT, 3);
    $output = curl_exec($ch);
    curl_close($ch);//json_encode();

    return json_decode($output, true);
}

function aiweline_news_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    //如果接口URL是https的,我们将其设为不验证,如果不是https的接口,这句可以不用加
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //参数为1表示传输数据，为0表示直接输出显示。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //参数为0表示不带头文件，为1表示带头文件
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //请求超时
    curl_setopt($ch, CONNECTION_TIMEOUT, 3);
    $output = curl_exec($ch);
    curl_close($ch);//json_encode();

    return $output;
}

function aiweline_xpath($url)
{
    $html = file_get_contents($url);

    $dom = new DOMDocument();

    //从一个字符串加载HTML
    @$dom->loadHTML($html);

    //使该HTML规范化

    $dom->normalize();

    //用DOMXpath加载DOM，用于查询

    $xpath = new DOMXPath($dom);

    return $xpath;
}

function aiweline_news_download_curl_image($url, $path = APP_PATH . "upload/aiweline_news/images")
{
    // 因为不知道最后接受到的文件是什么格式，先建立一个临时文件，用于保存
    $tmpFile = tempnam(sys_get_temp_dir(), 'image');

    # 文件下载 BEGIN #

    // 打开临时文件，用于写入（w),b二进制文件
    $resource = fopen($tmpFile, 'wb');

    // 初始化curl
    $curl = curl_init($url);
    // 设置输出文件为刚打开的
    curl_setopt($curl, CURLOPT_FILE, $resource);
    // 不需要头文件
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 执行
    curl_exec($curl);
    // 关闭curl
    curl_close($curl);

    // 关闭文件
    fclose($resource);

    # 文件下载 END #

    // 获取文件类型
    if (function_exists('exif_imagetype')) {
        // 读取一个图像的第一个字节并检查其签名(这里需要打开mbstring及php_exif)
        $fileType = exif_imagetype($tmpFile);
    } else {
        // 获取文件大小，里面第二个参数是文件类型 （这里后缀可以直接通过getimagesize($url)来获取，但是很慢）
        $fileInfo = getimagesize($tmpFile);
        $fileType = $fileInfo[2];
    }


    // 根据文件类型获取后缀名
    $extension = image_type_to_extension($fileType);

    // 计算指定文件的 MD5 散列值，作为保存的文件名，重复下载同一个文件不会产生重复保存，相同的文件散列值相同
    $md5FileName = md5_file($tmpFile);

    // 最终保存的文件
    $returnFile = $path . $md5FileName . $extension;

    // 检查传过来的路径是否存在，不存在就创建
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }

    // 复制临时文件到最终保存的文件中
    copy($tmpFile, $returnFile);

    // 释放临时文件
    @unlink($tmpFile);

    // 返回保存的文件路径
    $domain = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/';
    return $domain . $returnFile;
}

/**
 *新闻保存
 */
function aiweline_news_save()
{

}
