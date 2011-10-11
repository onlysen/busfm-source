<?php
require_once('includes/config.php');
require_once('includes/class_db.php');
require_once('includes/global.php');
require_once('includes/front.php');
$action=isset($_GET['action'])?$_GET['action']:''; 
if($action=='add'){
	//check_request();
	$smarty=new smarty();smarty_header();
	$smarty->display('ajax_micblog_add.html');
}

if($action=='add_ok'){
	//check_request();
	$content=empty($_GET['content'])?'':trim(addslashes($_GET['content']));
	$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id'];
	if(empty($content)){
		exit('0|无内容，请重新填写');
	} 
	$content = trim($content); // 去掉数据两端的空格  
	$content =str_replace("<","&lt;",$content); 
	$content =str_replace(">","&gt;",$content); 
	$content =str_replace("\n","<br />",$content); 
 	//$content// 转换HTML
	$count=$db->getcount("SELECT * FROM ".$db_prefix."micblog WHERE uid='".$uid."' AND content='".$content."'");
	if($count>0){
		exit('0|重复内容');
	}
	$uname=isset($_SESSION['member_name'])?$_SESSION['member_name']:$_COOKIE['member_nickname'];
	$insert=array();
	$insert['content']=$content;
	$insert['addtime']=$_SERVER['REQUEST_TIME'];
	$insert['uid']=$uid;
	$insert['uname']=$uname;
	$db->insert($db_prefix."micblog",$insert);
	clear_cache(); 
	exit("1|操作成功!|345|2011-03-25 17:13:35");
	
}

if($action=='del'){
//~ exit("1|删除成功");
	check_request();
	 $id=empty($_GET['id'])?0:intval($_GET['id']);
	 $uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id'];
	 $count=$db->getcount("SELECT * FROM ".$db_prefix."micblog WHERE uid='".$uid."' AND  id='".$id."' ");
	 if($count>0){
		 if(!empty($content_id)){ 
			 $db->delete($db_prefix."micblog","id=".$id.""); 
		 }
		 clear_cache();
		 echo("1|删除成功！"); 
	 }else{
		 echo("0|没有该心情 "); 
	 }
}  

if($action=='list'){
// sleep(5);
// exit("0|err");
echo '[["30","音乐是一种态度<br/>不论是作者还是聆听者<br/>它流经心灵<br/>纵然期待影响现实的力量是种奢望<br/>如果刚巧有些声音<br/>会叫你记得一段往事<br/>伤痛或是甜蜜<br/>那记忆便有了厚厚的壳<br/>恒久的 温暖有如初生","2010-12-30"],["29","你要相信世界上一定会有一个你的爱人，无论你此刻正被光芒环绕、被掌声淹没，还是那时你正孤独地走在寒冷的街道上北大雨淋湿。无论是飘着小雪的微亮清晨，还是被热浪炙烤的薄暮黄昏，他一定会穿越这个世界上汹涌着的人群，他一一地走过他们，怀着一颗热力跳动的心脏走向你。他一定会捧着满腔的热和目光里沉甸甸的爱，走向你、抓紧你。他会迫不及待地走到你的身边，如果他年轻，那天他一定会像顽劣的孩童霸占着自己的玩具不肯与人分享般地拥抱你；如果他已经不再年轻，那他一定会像披荆斩棘归来的猎人，在你身旁燃起篝火，然后拥抱着你疲惫而放心地睡去。<br/>他一定会找到你。你要等。","2010-12-29"],["28","你随便的说,我却认真的难过。 <br/>在千年万年的时光裂缝与罅隙中，我总是意犹未尽地想起你。","2010-12-29"],["27","一个人总要走陌生的路，看陌生的风景，听陌生的歌。 <br/>躲在某一时间，想念一段时光的掌纹。躲在某一地点， <br/>想念一个站在来路也站在去路的，让我牵挂的人。","2010-12-29"],["14","my diary","2010-12-27"]]';
	}
if($action=='next'){
// sleep(5);
// exit("0|err");
echo '[["938","No more love songs, no more special days","2011-02-18 08:13:31","0"],["484","每次回家就只为了印证一件事：是否真的已经失去了过去","2011-02-03 05:18:16","1"],["99","we just two lost soul swimming in the fish bow year after year","2011-01-13 01:07:10","0"],["67","总有一些世界观是傻傻的矗在那里，无论多少的现实多少的嘲讽多少的鸽子都改变不了。我们总是要怀有理想的。","2011-01-03 06:26:07","1"],["43","如果他已经不再年轻，那他一定会像披荆斩棘归来的猎人，在你身旁燃起篝火，然后拥抱着你疲惫而放心地睡去。他一定会找到你。你要等。","2010-12-31 02:23:01","1"],["31","我喜欢现在这首歌。。","2010-12-30 02:25:24","0"],["29","你要相信世界上一定会有一个你的爱人，无论你此刻正被光芒环绕、被掌声淹没，还是那时你正孤独地走在寒冷的街道上北大雨淋湿。无论是飘着小雪的微亮清晨，还是被热浪炙烤的薄暮黄昏，他一定会穿越这个世界上汹涌着的人群，他一一地走过他们，怀着一颗热力跳动的心脏走向你。他一定会捧着满腔的热和目光里沉甸甸的爱，走向你、抓紧你。他会迫不及待地走到你的身边，如果他年轻，那天他一定会像顽劣的孩童霸占着自己的玩具不肯与人分享般地拥抱你；如果他已经不再年轻，那他一定会像披荆斩棘归来的猎人，在你身旁燃起篝火，然后拥抱着你疲惫而放心地睡去。 他一定会找到你。你要等。 ","2010-12-29 06:42:40","0"],["28","你随便的说,我却认真的难过。 在千年万年的时光裂缝与罅隙中，我总是意犹未尽地想起你。","2010-12-29 06:42:22","0"],["27","一个人总要走陌生的路，看陌生的风景，听陌生的歌。 躲在某一时间，想念一段时光的掌纹。躲在某一地点， 想念一个站在来路也站在去路的，让我牵挂的人。","2010-12-29 06:42:02","0"]]';
	}
 
if($action=='s'){
exit('1|修改成功');
}
 
?>