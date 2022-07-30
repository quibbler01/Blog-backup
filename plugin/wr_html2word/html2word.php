<meta charset="utf-8" />
<?php
$local_url =  'http://'.$_SERVER['HTTP_HOST'].'/';
$title = urldecode($_SERVER["QUERY_STRING"]);
class word{
function start(){
ob_start();
echo 
'<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<xml><w:WordDocument><w:View>Print</w:View></xml>
</head>'; 
}
function save($path){
echo "</html>";
$data = $html;
}
function wirtefile($fn,$data){
$fp=fopen($fp,$data);
fwrite($fp,$data);
}
}
function getNeedBetween($text,$mark1,$mark2){ //只需要输入源文本、开始文本、结束文本，即可截取中间范围，无需手工输入长度
$st =stripos($text,$mark1);
$ed =stripos($text,$mark2);
$mark1_lengh = strlen($mark1);
if(($st==false)||($ed==false)||($st>=$ed))
return '一定有什么地方出错了，你还是联系管理员吧';
$text=substr($text,($st+$mark1_lengh),($ed-$st-$mark1_lengh));  
return $text;
}
function trimall($str){
    $qian=array(" ","　","\t","\n","\r");
    return str_replace($qian, '', $str);  
}

$html = file_get_contents($_SERVER["HTTP_REFERER"]);  //把前一个页面的内容输出到变量里
//截取出正文
$text_begin = '<div class="message break-all" isfirst="1">';
$text_end = '</div>

				
				<div class="plugin d-flex justify-content-center mt-3">
';
$text = getNeedBetween($html,$text_begin,$text_end);

//默认的图片地址是相对路径，处理补全逻辑。不同的编辑器生成的html页面里图片地址格式不同，注意区分。
$img_url_add = '<img src="'.$local_url.'upload';
$img_url_add2 = 'alt="image.png" src="'.$local_url.'upload';
$text = str_replace('<img src="upload',$img_url_add,$text);
$text = str_replace('alt="image.png" src="upload',$img_url_add2,$text);

//截取出本文地址
$url_after_begin = '<li class="breadcrumb-item active"><a href="';
$url_after_end = '" title="首页返回主题第一页"';
$url_after = getNeedBetween($html,$url_after_begin,$url_after_end);
$url_all = $local_url.$url_after;

$word = new word();
$word->start();
$wordname=$title.'.doc';
echo $text;

@header('Content-type:application/msword');
header('Content-Disposition: attachment; filename='.$wordname.'');
@readfile($wordname);
ob_flush();//每次执行前刷新缓存
flush();

?>