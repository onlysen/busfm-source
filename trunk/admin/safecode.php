<?php
require_once('includes/global.php');
require_once('includes/front.php');
require_once(ROOT_PATH.'languages/'.$config['site_language'].'/front.php');
if(isset($_SESSION['member_id'])||isset($_COOKIE['member_id'])){}else{exit("0|请登陆后再来这里。");}
$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id']; 
if(!$uid){
	exit("0|请登陆后再来这里。");
}
$action=isset($_GET['action'])?$_GET['action']:''; 
if($action=='add'){
	$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id'];
	$count=$db->getcount("SELECT * FROM ".$db_prefix."safecode WHERE uid='".$uid."' ");
	if($count>0){
		exit('1|您已经申请过了邀请码！');
	}else{
		$insert=array();
		for($i=0;$i<5;$i++){
			$insert['safecode']= substr(md5($uid.'_'.rand(1000,time())),2,12); 
			$insert['uid']=$uid;
			$db->insert($db_prefix."safecode",$insert);
		}
		clear_cache(); 
		exit("1|申请邀请码成功！");
	}
}
  
if($action=='list'){
	//check_request();
	$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id'];
	$list=array();
	$res=$db->getall("SELECT * FROM ".$db_prefix."safecode WHERE uid='".$uid."' ORDER BY id DESC");
	if($res){
		echo "[";
		$r=0;
		foreach($res as $row){
			echo "[\"".$row['id']."\",\"".$row['safecode']."\",\"".$row['valid']."\"]";
			//$list[$row['admin_id']]['id']=$row['admin_id']; 
			if(++$r!=count($res)) echo ',';
		}
		echo "]";
	}else{
		exit("0|你还没有申请邀请码~");
	}
	
} 

 
?>