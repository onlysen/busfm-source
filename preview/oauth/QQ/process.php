<?php
if(!isset($_COOKIE["member_id"])) exit("请先登录");
$userid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id']; 
//设置include_path 到 OpenSDK目录
set_include_path('lib/');
require_once 'OpenSDK/Tencent/Weibo.php';
include '../../../admin/basevar.php';
OpenSDK_Tencent_Weibo::init($configs['qq']['appkey'],$configs['qq']['appsecret']);
//打开session
session_start();
header('Content-Type: text/html; charset=utf-8');
if(isset($_GET['exit']))
{
	unset($_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN]);
	unset($_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]);
	unset($_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]);
	unset($_SESSION[OpenSDK_Tencent_Weibo::OAUTH_NAME]);
	//从数据库移除授权信息
	require_once('../../../admin/includes/config.php');
	require_once('../../../admin/includes/class_db.php');
	$db=new db($db_host,$db_user,$db_password,$db_name);
	$db->delete($db_prefix."oauth_user","userid=$userid and siteid='qq'");
	exit('1');
}
else if(isset($_GET['go_oauth']))
{ 
	$callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	$request_token = OpenSDK_Tencent_Weibo::getRequestToken($callback);
	$url = OpenSDK_Tencent_Weibo::getAuthorizeURL($request_token);
	header('Location: ' . $url);
}
else if( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']))
{
	//从Callback返回时
	if(OpenSDK_Tencent_Weibo::getAccessToken($_GET['oauth_verifier']))
	{
		// $uinfo = OpenSDK_Tencent_Weibo::call('user/info');
		// echo '从Opent返回并获得授权。你的微博帐号信息为：<br />';
		// echo 'Access token: ' , $_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN] , '<br />';
		// echo 'oauth_token_secret: ' , $_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET] , '<br />';
		// echo '你的微博帐号信息为:<br /><pre>';
		// var_dump($uinfo);
		//写入授权信息到数据库
		require_once('../../../admin/includes/config.php');
		require_once('../../../admin/includes/class_db.php');
		$db=new db($db_host,$db_user,$db_password,$db_name);
		$ua=$db->getone("select userid from ".$db_prefix."oauth_user where userid=$userid and siteid='qq' ");
		if(!$ua)
			$id=$db->insert($db_prefix."oauth_user",array('userid'=>$userid,'siteid'=>'qq','access_token'=>$_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN],'oauth_token_secret'=>$_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]));
		header('Location: ' . $configs['domain'].'#cpanel');
	}
	else
	{
		var_dump($_SESSION);
		echo '获得Access Tokn 失败';
	}
}
else {
	$oauthed=isset($_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]) && isset($_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]);
	if(!$oauthed){
		require_once('../../../admin/includes/config.php');
		require_once('../../../admin/includes/class_db.php');
		$db=new db($db_host,$db_user,$db_password,$db_name);
		$oauthed_db=$db->getone("select * from ".$db_prefix."oauth_user where userid=$userid and siteid='qq' ");
		if($oauthed_db){
			$_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]=$oauthed_db['access_token'];
			$_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]=$oauthed_db['oauth_token_secret'];
			// $_SESSION['tencent_access_token']=$oauthed_db['access_token'];
			// $_SESSION['tencent_oauth_token_secret']=$oauthed_db['oauth_token_secret'];
			$oauthed=true;
		}
	}
	if($oauthed)
	{
		//已经取得授权
		if(isset($_GET["cont"])){
			$r=OpenSDK_Tencent_Weibo::call('t/add',array('content'=>urldecode($_GET['cont']),'clientip'=>get_ip()),'POST');
			if($r['errcode']==0) exit("0|ok");
			else exit($r['errcode'].'|'.getErrMsg($r['errcode'],$r['msg']));
		}
	}
	else
	{
		echo '<a href="?go_oauth">点击去授权</a>';
	}
}
function get_ip(){
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}
function getErrMsg($code,$msg){
	switch($code){
		case 0: return '成功';
		case 4: return '有过多脏话';
		case 5: return '禁止访问';
		case 6: return '父节点已不存在';
		case 8: return '内容超过最大长度';
		case 9: return '包含垃圾信息：广告，恶意链接、黑名单号码等';
		case 10: return '发表太快，被频率限制';
		case 12: return '源消息审核中';
		case 13: return '重复发表';
		default: return $msg;
	}
}