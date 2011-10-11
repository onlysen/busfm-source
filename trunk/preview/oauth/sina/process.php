<?php
if(!isset($_COOKIE["member_id"])) exit("请先登录");
$userid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id']; 
include '../../../admin/basevar.php';
include_once 'config.php';
include_once 'weibooauth.php' ;
//打开session
session_start();
header('Content-Type: text/html; charset=utf-8');
if(isset($_GET['exit']))
{
	unset($_SESSION['last_key']);
	//从数据库移除授权信息
	require_once('../../../admin/includes/config.php');
	require_once('../../../admin/includes/class_db.php');
	$db=new db($db_host,$db_user,$db_password,$db_name);
	$db->delete($db_prefix."oauth_user","userid=$userid and siteid='sina'");
	exit('1');
}
else if(isset($_GET['go_oauth']))
{
	$o = new WeiboOAuth($configs['sina']['appkey'],$configs['sina']['appsecret']);
	$callback = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	$keys = $o->getRequestToken();
	$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callback);
	$_SESSION['keys'] = $keys;
	header('Location: ' . $aurl);
}
else if(isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']))
{
	//从Callback返回时
	$o = new WeiboOAuth( WB_AKEY , WB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );
	$last_key = $o->getAccessToken(  $_GET['oauth_verifier'] ) ;
	if(isset($last_key['error_code'])&&isset($last_key['error'])) exit('授权失败：'.$last_key['error']);
	$_SESSION['last_key'] = $last_key;
	//写入授权信息到数据库
	require_once('../../../admin/includes/config.php');
	require_once('../../../admin/includes/class_db.php');
	$db=new db($db_host,$db_user,$db_password,$db_name);
	$ua=$db->getone("select userid from ".$db_prefix."oauth_user where userid=$userid and siteid='sina' ");
	if(!$ua)	$id=$db->insert($db_prefix."oauth_user",array('userid'=>$userid,'siteid'=>'sina','access_token'=>$last_key['oauth_token'],'oauth_token_secret'=>$last_key['oauth_token_secret']));
	header('Location: ' . $configs['domain'].'#cpanel');
}
else{
	$oauthed=isset($_SESSION['last_key'])&&isset($_SESSION['last_key']['oauth_token'])&&isset($_SESSION['last_key']['oauth_token_secret']);
	if(!$oauthed){
		require_once('../../../admin/includes/config.php');
		require_once('../../../admin/includes/class_db.php');
		$db=new db($db_host,$db_user,$db_password,$db_name);
		$oauth_user_row=$db->getone("select * from ".$db_prefix."oauth_user where userid=$userid and siteid='sina' ");
		if($oauth_user_row){
			$_SESSION['last_key']=array('oauth_token'=>$oauth_user_row['access_token'],'oauth_token_secret'=>$oauth_user_row['oauth_token_secret']);
			$oauthed=true;
		}
	}
	if($oauthed)
	{
		//已经取得授权
		//发送
		if(isset($_GET["cont"])){
			$c = new WeiboClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
			$msg = $c->update($_GET['cont']);
			if ($msg === false || $msg === null){
				exit("未知错误");
			}
			if (isset($msg['error_code']) && isset($msg['error'])){
				exit($msg['error']);
			}
			exit('ok');
		}
	}
	else
	{
		echo '<a href="?go_oauth">点击去授权</a>';
	}
}
?>