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
	//check_request();
	$smarty=new smarty();smarty_header();
	$smarty->display('ajax_micblog_add.html');
}

if($action=='add_ok'){
	//check_request();
	$content=empty($_GET['content'])?'':trim(addslashes($_GET['content']));
	$isprivate=empty($_GET['isprivate'])?0:trim(addslashes($_GET['isprivate']));
	$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id'];
	if(empty($content)){
		exit('0|无内容，请重新填写');
	} 
	$content = trim($content); // 去掉数据两端的空格
	
	$content =str_replace("<","&lt;",$content); 
	$content =str_replace(">","&gt;",$content); 
	$content =str_replace("&lt;br /&gt;","<br />",$content);   
	$content =str_replace("\n","<br />",$content); 
	$content =str_replace("[n]","<br />",$content); 
 	//$content// 转换HTML
	$count=$db->getcount("SELECT * FROM ".$db_prefix."micblog WHERE uid='".$uid."' AND content='".$content."'");
	if($count>0){
		exit('0|重复内容');
	}
	$uname=isset($_SESSION['member_name'])?$_SESSION['member_name']:$_COOKIE['member_nickname'];
	$insert=array();
	$insert['content']=$content;
	$insert['recom']=$isprivate;
	$insert['addtime']=$_SERVER['REQUEST_TIME'];
	$insert['uid']=$uid;
	$insert['uname']=$uname;
	$db->insert($db_prefix."micblog",$insert);
	//$count=$db->getcount("SELECT * FROM ".$db_prefix."micblog WHERE uid='".$uid."' AND content='".$content."'");
	
	clear_cache(); 
	exit("1|添加成功|".$db->insert_id()."|".date("Y-m-d H:i:s",$insert["addtime"]));
	//exit("1|操作成功！");
	
}

if($action=='del'){
	//check_request();
	$id=empty($_GET['id'])?0:intval($_GET['id']);
	$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id'];
	$count=$db->getcount("SELECT * FROM ".$db_prefix."micblog WHERE uid='".$uid."' AND  id='".$id."' ");
	if($count>0){
		if(!empty($id)){ 
			$db->delete($db_prefix."micblog","id=".$id.""); 
		}
		clear_cache();
		echo("1|删除成功！"); 
	}else{
		echo("0|没有该心情 "); 
	}
}  

if($action=='list'){
	//check_request();
	$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id'];
	$list=array();
	$res=$db->getall("SELECT * FROM ".$db_prefix."micblog WHERE uid='".$uid."' ORDER BY id DESC");
	$ctr=0;
	$r=count($res);
	if($res){
		echo "[";
		foreach($res as $row){
			echo "[\"".$row['id']."\",\"".$row['content']."\",\"".date("Y-m-d h:i:s",$row['addtime'])."\",\"".$row['recom']."\"]";
			//$list[$row['admin_id']]['id']=$row['admin_id']; 
			if(++$ctr!=$r) echo ',';
		}
		echo "]";
	}else{
		exit("0|你还没有添加心情~");
	}
	
} 

if($action=='s'){
		//check_permissions('category_write');
		$category_id=empty($_GET['id'])?0:intval($_GET['id']); 
		$isprivate=empty($_GET['isprivate'])?0:trim(addslashes($_GET['isprivate']));
		$update=array(); 
		$update['recom']=$isprivate; 
		$r=$db->update($db_prefix."micblog",$update,"id=$category_id");
	  	if($r) exit("1|心情状态修改成功~");
		else exit("0|修改状态失败");
		 
	}

 
?>