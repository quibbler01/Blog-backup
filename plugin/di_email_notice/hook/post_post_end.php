include _include(APP_PATH . 'plugin/di_email_notice/model/email_notice.fun.php');
//获取帖子标题
$thread_msg=thread_read($tid);
$thread_title=$thread_msg['subject'];
$thread_uid=$thread_msg['uid'];//楼主uid
//获取楼主用户名
$thread_user_msg=user_read($thread_uid);
$user_name=$thread_user_msg['username'];
$user_email=$thread_user_msg['email'];
//获取回帖者用户名
$reply_user_msg=user_read($uid);
$reply_nick=$reply_user_msg['username'];
//回复内容
$reply_content=$message;
//回复时间
$reply_time=date("Y/m/d H:i:s",$time);

//帖子链接
$thread_url=$_SERVER['SERVER_NAME'].url("thread-".$tid);

//判断是否为引用
if($quotepid !=0){
	$quotepost_msg=user_read($quotepost['uid']);
	$user_email=$quotepost_msg['email'];
	$user_name=$quotepost_msg['username'];
	
	
}

 //获取配置信息
$email=kv_get('email_notice');
$fromname=$email['fromname'];
$email_title=$email['email_title'];
$email_message=$email["email_message"];
$email_user=$email['email'];
$email_password=(string)$email['password'];
$email_smtp=$email['smtp'];
$email_port=$email['port'];


//变量替换
$str=array('{thread_title}','{user_name}','{reply_nick}','{reply_content}','{reply_time}','{thread_url}');

$var=array($thread_title,$user_name,$reply_nick,$reply_content,$reply_time,$thread_url);

$email_message=str_replace($str,$var,$email["email_message"]);
$email_title=str_replace($str,$var,$email["email_title"]);
$fromname=str_replace($str,$var,$email["fromname"]); 
$Tuser=user_read($uid);
if($thread_user_msg['email_notice']){
	sendEmail($user_email,$email_title,$email_message,$email_user,$email_password,$email_smtp,$email_port,$fromname); 
}

 