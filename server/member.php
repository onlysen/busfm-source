<?php
require_once('includes/global.php');
require_once('includes/front.php');
require_once(ROOT_PATH.'languages/'.$config['site_language'].'/front.php');
if($config['member_open']=='no'){
	message(array('text'=>$language['member_sysytem_is_close'],'link'=>'./'));
}
$action=isset($_GET['action'])?$_GET['action']:'';
if($action=='login'){
	check_request();
	$smarty=new smarty();smarty_header();
	$smarty->display('ajax_member_login.html');
}
if($action=='login_ok'){
	check_request();
	$member_mail=empty($_GET['member_mail'])?'':trim(addslashes($_GET['member_mail']));
	$member_password=empty($_GET['member_password'])?'':password($_GET['member_password']);
	if(empty($member_mail)){
		exit('error:mail_is_empty');
	}
	if(!is_email($member_mail)){
		exit('error:mail_is_error');
	}
	if(empty($member_password)){
		exit('error:password_is_empty');
	}
	$row=$db->getone("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$member_mail."' and member_password='".$member_password."'");
	if($row){
		if($row['member_validation']==0){
			exit('error:account_is_not_activate');
		}
		if($row['member_state']==0){
			exit('error:account_is_lock');
		}
		$_SESSION['member_id']=$row['member_id'];
		$_SESSION['member_mail']=$row['member_mail'];
		$_SESSION['member_nickname']=$row['member_nickname'];
		$_SESSION['member_photo']=$row['member_photo'];
		$_SESSION['group_id']=$row['group_id'];
		
		setcookie("member_id", $row['member_id'], time()+3600000);
		setcookie("member_mail", $row['member_mail'], time()+3600000);
		setcookie("member_nickname", $row['member_nickname'], time()+3600000);
		setcookie("member_photo", $row['member_photo'], time()+3600000);
		setcookie("group_id", $row['group_id'], time()+3600000);
		 
		$update=array();
		$update['member_last_time']=time();
		$update['member_last_ip']=get_ip();
		$db->update($db_prefix."member",$update,"member_mail='".$member_mail."'");
		clear_cache();
	}else{
		exit('error:login_failed');
	}
}
if($action=='login_fm'){
	check_request();
	$member_mail=empty($_POST['member_mail'])?'':trim(addslashes($_POST['member_mail']));
	$member_password=empty($_POST['member_password'])?'':password($_POST['member_password']);
	if(empty($member_mail)){
		exit('0|登陆邮箱不能为空');
	}
	if(!is_email($member_mail)){
		exit('0|登陆邮箱格式错误');
	}
	if(empty($member_password)){
		exit('0|密码不能为空');
	}
	$row=$db->getone("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$member_mail."' and member_password='".$member_password."'");
	if($row){
		if($row['member_validation']==0){
			exit('0|帐户没有激活,去你的邮箱看看!');
		}
		if($row['member_state']==0){
			exit('0|帐户已被锁定');
		}
		$_SESSION['member_id']=$row['member_id'];
		$_SESSION['member_mail']=$row['member_mail'];
		$_SESSION['member_nickname']=$row['member_nickname'];
		$_SESSION['member_photo']=$row['member_photo'];
		$_SESSION['group_id']=$row['group_id'];
		$_SESSION['join_time']=$row['member_join_time'];
		
		setcookie("member_id", $row['member_id'], time()+3600000,"/","bus.fm");
		setcookie("member_mail", $row['member_mail'], time()+3600000,"/","bus.fm");
		setcookie("member_nickname", $row['member_nickname'], time()+3600000,"/","bus.fm");
		setcookie("member_photo", $row['member_photo'], time()+3600000,"/","bus.fm");
		setcookie("group_id", $row['group_id'], time()+3600000,"/","bus.fm");
		setcookie("join_time", $row['member_join_time'], time()+3600000,"/","bus.fm");
		 
		$update=array();
		$update['member_last_time']=time();
		$update['member_last_ip']=get_ip();
		$db->update($db_prefix."member",$update,"member_mail='".$member_mail."'");
		clear_cache();
		exit('1|登陆成功|'.date("Y年m月d日H时i分",$row['member_join_time']));
	}else{
		exit('0|登录失败');
	}
}
if($action=='register'){
	check_request();
	$smarty=new smarty();smarty_header();
	$smarty->display('ajax_member_register.html');
}

if($action=='register_ok'){
	check_request();
	$member_mail=empty($_GET['member_mail'])?'':trim(addslashes($_GET['member_mail']));
	$member_password=empty($_GET['member_password'])?'':trim(addslashes($_GET['member_password']));
	$member_password_confirm=empty($_GET['member_password_confirm'])?'':trim(addslashes($_GET['member_password_confirm']));
	$member_safecode=empty($_GET['member_safecode'])?'':trim(addslashes($_GET['member_safecode']));
	$member_nickname=empty($_GET['member_nickname'])?'':trim(addslashes($_GET['member_nickname']));
	$member_state=empty($_GET['member_state'])?0:intval($_GET['member_state']);
	if(empty($member_mail)){
		exit('error:mail_is_empty');
	}
	if(!is_email($member_mail)){
		exit('error:mail_is_error');
	}
	$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$member_mail."'");
	if($count>0){
		exit('error:mail_is_occupy');
	}
	if(empty($member_password)){
		exit('error:password_is_empty');
	}
	if($member_password!=$member_password_confirm){
		exit('error:password_is_error');
	}
	if(empty($member_safecode)){
		exit('error:safecode_is_empty');
	}
	if(empty($member_nickname)){
		exit('error:nickname_is_empty');
	}
	$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_nickname='".$member_nickname."'");
	if($count>0){
		exit('error:nickname_is_occupy');
	}
	$insert=array();
	$insert['member_mail']=$member_mail;
	$insert['member_password']=password($member_password);
	$insert['member_safecode']=password($member_safecode);
	$insert['member_nickname']=$member_nickname;
	$insert['member_sex']=0;
	if($config['member_validation']=='yes'){
		$key=md5($member_mail.$member_password);
		$insert['member_validation']=0;
		$insert['member_validation_key']=$key;
	}else{
		$insert['member_validation']=1;
	}
	$insert['member_state']=1;
	$insert['member_join_time']=$_SERVER['REQUEST_TIME'];
	$insert['member_last_time']=$_SERVER['REQUEST_TIME'];
	$insert['member_last_ip']=get_ip();
	$db->insert($db_prefix."member",$insert);
	clear_cache();
	if($config['member_validation']=='yes'){
		send_mail($member_mail,$config['smtp_user'],'Please activate the account!','<a href="http://bus.fm/admin/member.php?action=member_validation&key='.$key.'">Click!</a>');
	}else{
		$_SESSION['member_id']=$db->insert_id();
		$_SESSION['member_mail']=$member_mail;
		$_SESSION['member_nickname']=$member_nickname;
		$_SESSION['group_id']=0;
		
		setcookie("member_id", $db->insert_id(), time()+3600000);
		setcookie("member_mail", $member_mail, time()+3600000);
		setcookie("member_nickname", $member_nickname, time()+3600000);
		setcookie("group_id", 0, time()+3600000);
 
	}
}

if($action=='register_fm'){
	check_request();
	$member_mail=empty($_POST['member_mail'])?'':trim(addslashes($_POST['member_mail']));
	$member_password=empty($_POST['member_password'])?'':trim(addslashes($_POST['member_password']));
	$member_password_confirm=empty($_POST['member_password_confirm'])?'':trim(addslashes($_POST['member_password_confirm']));
	$member_safecode=empty($_POST['member_safecode'])?'':trim(addslashes($_POST['member_safecode']));
	$member_nickname=empty($_POST['member_nickname'])?'':trim(addslashes($_POST['member_nickname']));
	$member_state=empty($_POST['member_state'])?0:intval($_POST['member_state']);
	if(empty($member_mail)){
		exit('0|电子邮箱不能为空');
	}
	if(!is_email($member_mail)){
		exit('0|电子邮箱格式错误');
	}
	
	if(empty($member_password)){
		exit('0|密码不能为空');
	}
	if($member_password!=$member_password_confirm){
		exit('0|密码错误');
	}
	/*if(empty($member_safecode)){
		exit('0|邀请码不能为空');
	}
	$count=$db->getone("SELECT valid FROM ".$db_prefix."safecode WHERE safecode='".$member_safecode."'");
	if($count['valid']==0){
		exit('0|无效的邀请码');
	} */ //2011-2-14

	if(empty($member_nickname)){
		exit('0|昵称不能为空');
	}
	$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_nickname='".$member_nickname."'");
	if($count>0){
		exit('0|昵称跟别人重复');
	}
	
	$count2=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$member_mail."'");
	if($count2>0){
		exit('0|邮箱跟别人重复');
	}
	$insert=array();
	$insert['member_mail']=$member_mail;
	$insert['member_password']=password($member_password);
	$insert['member_safecode']=1;//$member_safecode; //2011-2-14 取消
	$insert['member_nickname']=$member_nickname;
	$insert['member_sex']=0;
	if($config['member_validation']=='yes'){
		$key=md5($member_mail.$member_password);
		$insert['member_validation']=0;
		$insert['member_validation_key']=$key;
	}else{
		$insert['member_validation']=1;
	}
	$insert['member_state']=1;
	$insert['member_join_time']=$_SERVER['REQUEST_TIME'];
	$insert['member_last_time']=$_SERVER['REQUEST_TIME'];
	$insert['member_last_ip']=get_ip();
	$db->insert($db_prefix."member",$insert);
	//修改邀请码状态
	//$db->update($db_prefix."safecode",array('valid'=>0),"safecode='".$member_safecode."'"); //2011-2-14
	
	
	if($config['member_validation']=='yes'){
		send_mail($member_mail,$config['smtp_user'],'请激活 BUS.FM 帐号！','恭喜你，已注册成功 巴士电台 <a href="http://bus.fm" target="_blank">BUS.FM</a>,激活请<a href="http://bus.fm/admin/member.php?action=member_validation&key='.$key.'"  target="_blank">点击这里!</a>');
		exit('1|注册成功，请至填写的邮箱验证您的账户');
	}else{
		$uid=$_SESSION['member_id']=$db->insert_id();
		$_SESSION['member_mail']=$member_mail;
		$_SESSION['member_nickname']=$member_nickname;
		$_SESSION['group_id']=0;
		
		setcookie("member_id", $db->insert_id(), time()+3600000);
		setcookie("member_mail", $member_mail, time()+3600000);
		setcookie("member_nickname", $member_nickname, time()+3600000);
		setcookie("group_id", 0, time()+3600000);
		//送邀请码
		 
		/*for($i=0;$i<5;$i++){
			$insert['safecode']= substr(md5($uid.'_'.rand(1000,time())),2,12); 
			$insert['uid']=$uid;
			$db->insert($db_prefix."safecode",$insert);
		}*/   //2011-2-14
		exit('1|注册成功');//成功
	}
	clear_cache();
}

if($action=='edit_member'){
	check_request();
	if(!check_login()){
		message(array('text'=>$language['please_login'],'link'=>'./'));
	}
	$smarty=new smarty();smarty_header();
	$smarty->assign('here',here('member_edit'));
	$smarty->assign('action',$action);
	$smarty->assign('member_info',get_member_info($_SESSION['member_id']));
	$smarty->display('member.html');
}
if($action=='edit_member_ok'){
	check_request();
	if(!check_login()){
		message(array('text'=>$language['please_login'],'link'=>'member.php'));
	}
	$member_id=empty($_POST['member_id'])?0:intval($_POST['member_id']);
	$member_password=empty($_POST['member_password'])?'':trim(addslashes($_POST['member_password']));
	$member_password_confirm=empty($_POST['member_password_confirm'])?'':trim(addslashes($_POST['member_password_confirm']));

	$member_name=empty($_POST['member_name'])?'':trim(addslashes($_POST['member_name']));
	$member_sex=empty($_POST['member_sex'])?0:intval($_POST['member_sex']);
	$member_birthday=empty($_POST['member_birthday'])?0:strtotime($_POST['member_birthday']);
	$member_phone=empty($_POST['member_phone'])?'':trim(addslashes($_POST['member_phone']));
	$member_from=empty($_POST['member_from'])?'':trim(addslashes($_POST['member_from']));
	$member_other=empty($_POST['member_other'])?'':trim(addslashes($_POST['member_other']));

	if(!empty($member_password)){
		if($member_password!=$member_password_confirm){
			message(array('text'=>$language['password_is_error'],'link'=>''));
		}
	}
	$member_photo=upload($_FILES['member_photo'],false);
	$member_photo_old=empty($_POST['member_photo_old'])?'':trim($_POST['member_photo_old']);
	$member_photo_delete=empty($_POST['member_photo_delete'])?'':trim($_POST['member_photo_delete']);
	$update=array();
	if(!empty($member_password)){
	$update['member_password']=password($member_password);
	}
	$update['member_name']=$member_name;
	$update['member_sex']=$member_sex;
	$update['member_birthday']=$member_birthday;
	$update['member_phone']=$member_phone;
		if(!empty($member_photo)){
			@unlink(ROOT_PATH."/uploads/".$member_photo_old);
			$update['member_photo']=$member_photo;
			$_SESSION['member_photo']=$member_photo; 
			setcookie("member_photo", $member_photo, time()+3600000);
			if($config['image_thumb_open']=='yes'){
				make_thumb(ROOT_PATH.'/uploads/'.$member_photo,100,100);
			}
		}
		if(!empty($member_photo_delete)){
			@unlink(ROOT_PATH."/uploads/".$member_photo_delete);
			$update['member_photo']='';
			$_SESSION['member_photo']='';
			setcookie("member_photo", '', time()+3600000);
		}
	$update['member_from']=$member_from;
	$update['member_other']=$member_other;
	$db->update($db_prefix."member",$update,"member_id=$member_id");
	clear_cache();
	message(array('text'=>$language['member_update_success'],'link'=>'index.php'));
}
if($action=='edit_member_fm'){
	check_request();
	if(!isset($_COOKIE["member_id"])) exit('0|<a href="#" id="notlogin">请先登录</a>');
	$member_id=$_COOKIE["member_id"];
	$member_nickname=empty($_POST['nickname'])?'':trim(addslashes($_POST['nickname']));
	$update=array('member_nickname'=>$member_nickname);
	$r=$db->update($db_prefix."member",$update,"member_id=$member_id");
	if($r){
		$_SESSION['member_nickname']=$member_nickname;
		setcookie("member_nickname", $member_nickname, time()+3600000,"/","bus.fm");
		exit('1|昵称修改成功');
	}
	else exit("0|修改失败，请稍后再试");
}
if($action=='edit_pwd_fm'){
	check_request();
	if(!isset($_COOKIE["member_id"])) exit('0|<a href="#" id="notlogin">请先登录</a>');
	$member_id=$_COOKIE["member_id"];
	$pwd=empty($_POST['member_password1'])?'':trim(addslashes($_POST['member_password1']));
	$update=array();
	$update['member_password']=password($pwd);
	$r=$db->update($db_prefix."member",$update,"member_id=$member_id");
	if($r)exit('1|密码修改成功，请重新登录');
	else exit("0|修改失败，请稍后再试");
}
if($action=='forget'){
	//check_request();
	if(!$_POST){
		$cont=empty($_GET['cont'])?'':trim(addslashes($_GET['cont']));
		if(!$cont)exit("0|地址错误！");
		$cont1 = base64_decode($cont);
		$cont2 = explode('=',$cont1);
		if((time()-$cont2[1])>3600){//一小时
			exit("0|地址失效，请重新提交申请！");
		}
		$smarty=new smarty();smarty_header();
		$smarty->assign('member_mail',$cont2[0]);
		$smarty->display('ajax_member_forget.html');
	}else{
		$member_password=empty($_POST['member_password'])?'':trim(addslashes($_POST['member_password']));
		$member_mail=empty($_POST['member_mail'])?'':trim(addslashes($_POST['member_mail']));
		if(!$member_mail)exit("0|邮箱不能为空");
		if(!$member_password)exit("0|密码不能为空");
		$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$_POST['member_mail']."'");
		if($count>0){
			$db->update($db_prefix."member",array('member_password'=>password($member_password)),"member_mail='".$member_mail."'");
			exit("1|修改成功！");
		}else{
			//echo($language['password_is_failed']);
			exit("0|邮箱错误！");
		}
	} 
	clear_cache();
}
if($action=='forget_ok'){
	//check_request();
	$member_mail=empty($_GET['member_mail'])?'':trim(addslashes($_GET['member_mail']));
	//$member_safecode=empty($_GET['member_safecode'])?'':trim(addslashes($_GET['member_safecode']));
	$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$member_mail."'");
	if($count>0){
		//获取会员信息
		$member=$db->getone("SELECT * FROM ".$db_prefix."member WHERE  member_mail='".$member_mail."'");
		$url = base64_encode($member_mail."=".time());
		//$new_password=create_password();
		//$db->update($db_prefix."member",array('member_password'=>password($new_password)),"member_mail='".$member_mail."'");
		send_mail($member_mail,$config['smtp_user'],'BUS.FM New Password!','Hello '.$member['member_nickname'].', <br /><br />We send you this email because you seem to have forgotten your password. If you don\'t know what this email is about, please simply ignore it.<br /><br />To change your old password to this one, please go to the following URL:<br /><br /><a href="http://bus.fm/my/pwd./1&cont='.$url.'" target="_blank">http://bus.fm/my/pwd/1&cont='.$url.'</a><br /><br />You can also log into your account with the new password mentioned above. If you don\'t go to this URL, you can keep using your old password.<br /><br /> Best Regards, <br />admin@Bus.fm');//http://bus.fm/forms/pwd.php?type=1&cont=  //原http://bus.fm/admin/member.php?action=forget&cont=
		//send_mail($member_mail,$config['smtp_user'],'BUS.FM 获取新密码!','欢迎登陆 <a href="http://bus.fm" target="_blank">BUS.FM</a>  你的新密码:'.$new_password.'  <a href="http://bus.fm" target="_blank">点击登陆 BUS.FM 修改!</a>');
		//echo($language['password_is_retrieve']);
		exit("1|已经发送修改密码页面！一个小时内有效。请登陆您的邮箱查看！");
	}else{
		//echo($language['password_is_failed']);
		exit("0|邮箱错误！");
	}
	clear_cache();
}
if($action=='forget_ok_fm'){
	//check_request();
	$member_mail=empty($_POST['member_mail'])?'':trim(addslashes($_POST['member_mail']));
	//$member_safecode=empty($_GET['member_safecode'])?'':trim(addslashes($_GET['member_safecode']));
	$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$member_mail."'");
	if($count>0){
		//获取会员信息
		$member=$db->getone("SELECT * FROM ".$db_prefix."member WHERE  member_mail='".$member_mail."'");
		$url = base64_encode($member_mail."=".time());
		//$new_password=create_password();
		//$db->update($db_prefix."member",array('member_password'=>password($new_password)),"member_mail='".$member_mail."'");
		send_mail($member_mail,$config['smtp_user'],'BUS.FM New Password!','Hello '.$member['member_nickname'].', <br /><br />We send you this email because you seem to have forgotten your password. If you don\'t know what this email is about, please simply ignore it.<br /><br />To change your old password to this one, please go to the following URL:<br /><br /><a href="http://bus.fm/my/pwd/1&cont='.$url.'" target="_blank">http://bus.fm/my/pwd/1&cont='.$url.'</a><br /><br />You can also log into your account with the new password mentioned above. If you don\'t go to this URL, you can keep using your old password.<br /><br /> Best Regards, <br />admin@Bus.fm');//http://bus.fm/forms/pwd.php?type=1&cont=  //原http://bus.fm/admin/member.php?action=forget&cont=
		//send_mail($member_mail,$config['smtp_user'],'BUS.FM 获取新密码!','欢迎登陆 <a href="http://bus.fm" target="_blank">BUS.FM</a>  你的新密码:'.$new_password.'  <a href="http://bus.fm" target="_blank">点击登陆 BUS.FM 修改!</a>');
		//echo($language['password_is_retrieve']);
		exit("1|已经发送修改密码页面！一个小时内有效。请登陆您的邮箱查看！");
	}else{
		//echo($language['password_is_failed']);
		exit("0|邮箱错误！");
	}
	clear_cache();
}
if($action=='member_validation'){
	$key=empty($_GET['key'])?'':trim($_GET['key']);
	
	if($key==''){
		message(array('text'=>$language['activation_failed'],'link'=>'/index.html'));
	}
	$row=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_validation_key='$key'");
	if($row>0){
		$db->update($db_prefix."member",array('member_validation'=>1),"member_validation_key='".$key."'");
		//送邀请码
		/*$row2=$db->getall("SELECT * FROM ".$db_prefix."member WHERE member_validation_key='".$key."'"); 
		$uid = $row2[0]['member_id'];
		if($uid){
			for($i=0;$i<5;$i++){
				$insert['safecode']= substr(md5($uid.'_'.rand(1000,time())),2,12); 
				$insert['uid']=$uid;
				$db->insert($db_prefix."safecode",$insert);
			}
		}*/   //2011-2-14
		message(array('text'=>$language['activation_success'],'link'=>'/index.php'));
	}else{
		message(array('text'=>$language['activation_failed'],'link'=>'/index.php'));
	}
}


if($action=='member_info'){
	check_request();
	$smarty=new smarty();smarty_header();
	$smarty->display('ajax_member_info.html');
	exit;
}

if($action=='logout'){
	check_request();
	setcookie("member_id", "null", time()-3600000,"/","bus.fm");
	unset($_SESSION['member_id'],$_SESSION['member_mail'],$_SESSION['member_nickname'],$_SESSION['member_photo']);
	unset($_COOKIE['member_id'],$_COOKIE['member_mail'],$_COOKIE['member_nickname'],$_COOKIE['member_photo'],$_COOKIE['join_time']);
	clear_cache();
	exit;
}



if($action=='content_add'){
	if(!check_login()){
		message(array('text'=>$language['please_login'],'link'=>''));
	}
	$channel_id=empty($_GET['channel_id'])?0:intval($_GET['channel_id']);
	if($channel_id==0){
		message(array('text'=>$language['parameter_is_lost'],'link'=>''));
	}
	$channel_info=get_channel_info($channel_id);
	if(!check_permissions($channel_info['read_permissions'])){
		message(array('text'=>$language['permissions_is_not_enough'],'link'=>''));
	}
	$content=array();
	$content['channel_id']=$channel_id;
	$content['id']=0;
	$content['title']='';
	$content['text']='';
	$content['thumb']='';
	$content['password']='';
	$content['link']=array();
	$content['attachment']=array();
	$content['is_best']=0;
	$content['is_comment']=1;
	$content['state']=1;
	$smarty=new smarty();smarty_header();
	$parameters=array();
	$parameters['id']=$channel_id;
	$parameters['mode']='insert';
	$smarty->assign('here',here('member_content',$parameters));
	$smarty->assign('content',$content);
	$smarty->assign('channel_info',$channel_info);
	if(check_have_category($channel_id)){
		$smarty->assign('category_list',category_option_list(0,$channel_id,0));
	}else{
		$smarty->assign('category_list','');
	}
	$smarty->assign('channel_category',get_category($channel_id,0));
	$smarty->assign('mode','insert');
	$smarty->display('content_info.html');
}
if($action=='content_insert'){
	check_request();
	if(!check_login()){
		message(array('text'=>$language['please_login'],'link'=>''));
	}
	$content_id=empty($_POST['content_id'])?0:intval($_POST['content_id']);
	$content_title=empty($_POST['content_title'])?'':trim(addslashes($_POST['content_title']));
	$content_text=empty($_POST['content_text'])?'':trim(addslashes($_POST['content_text']));
	$content_password=empty($_POST['content_password'])?'':trim(addslashes($_POST['content_password']));
	$channel_id=empty($_POST['channel_id'])?0:intval($_POST['channel_id']);
	$category_id=empty($_POST['category_id'])?0:intval($_POST['category_id']);
	if($content_title==''){
		message(array('text'=>$language['content_title_is_empty'],'link'=>''));
	}
	if($content_text==''){
		message(array('text'=>$language['content_text_is_empty'],'link'=>''));
	}
	$content_thumb=upload($_FILES['content_thumb'],false);
	$insert=array();
	$insert['content_title']=$content_title;
	$insert['content_text']=$content_text;
	$insert['content_password']=$content_password;
	if(!empty($content_thumb)){
		if($config['image_thumb_open']=='yes'){
			make_thumb(ROOT_PATH.'/uploads/'.$content_thumb,$config['image_thumb_width'],$config['image_thumb_height']);
		}
		$insert['content_thumb']=$content_thumb;
	}
	$insert['content_is_comment']=1;
	$insert['content_state']=1;
	$insert['content_time']=$_SERVER['REQUEST_TIME'];
	$insert['member_id']=$_SESSION['member_id'];
	$insert['channel_id']=$channel_id;
	$insert['category_id']=$category_id;
	$db->insert($db_prefix."content",$insert);
	$insert_content_id=$db->insert_id();
	if(!empty($_POST['content_link'])){
		foreach($_POST['content_link'] as $value){
			if(!empty($value)){
				$db->insert($db_prefix."content_link",array('link_url'=>$value,'content_id'=>$insert_content_id));
			}
		}
	}
	$content_attachment=upload($_FILES['content_attachment'],true);
	foreach($content_attachment as $value){
		if(!empty($value)){
			$db->insert($db_prefix."content_attachment",array('attachment_name'=>$value,'content_id'=>$insert_content_id));
			if($config['image_text_open']=='yes'){
				make_watermark(ROOT_PATH.'/uploads/'.$value,ROOT_PATH.'/uploads/'.$config['image_file'],$config['image_pos'],$config['image_text']);
			}
		}
	}
	clear_cache();
	message(array('text'=>$language['content_insert_success'],'link'=>'channel.php?id='.$channel_id));
}

if($action=='content_edit'){
	$content_id=empty($_GET['content_id'])?0:intval($_GET['content_id']);
	$content_info=get_content_info($content_id);
	if(empty($_SESSION['admin_id'])){
		if(!check_login()){
			message(array('text'=>$language['please_login'],'link'=>''));
		}
		if($content_info['member_id']!=$_SESSION['member_id']){
			message(array('text'=>$language['permissions_is_not_enough'],'link'=>''));
		}
	}
	$row=$db->getone("SELECT * FROM ".$db_prefix."content WHERE content_id='".$content_id."'");
	$content=array();
	$content['id']=$row['content_id'];
	$content['title']=$row['content_title'];
	$content['text']=$row['content_text'];
	$content['thumb']=$row['content_thumb'];
	$content['password']=$row['content_password'];
	$content['link']=get_content_link_list($content_id);
	$content['attachment']=get_content_attachment_list($content_id);
	$content['channel_id']=$row['channel_id'];
	$channel_info=get_channel_info($row['channel_id']);
	$smarty=new smarty();smarty_header();
	$parameters=array();
	$parameters['id']=$content_info['channel_id'];
	$parameters['mode']='update';
	$smarty->assign('here',here('member_content',$parameters));
	$smarty->assign('content',$content);
	$smarty->assign('channel_info',$channel_info);
	if(check_have_category($row['channel_id'])){
		$smarty->assign('category_list',category_option_list(0,$row['channel_id'],$row['category_id']));
	}else{
		$smarty->assign('category_list','');
	}
	$smarty->assign('mode','update');
	$smarty->display('content_info.html');
}

if($action=='content_update'){
	check_request();
	$content_id=empty($_POST['content_id'])?0:intval($_POST['content_id']);
	$content_info=get_content_info($content_id);
	if(empty($_SESSION['admin_id'])){
		if(!check_login()){
			message(array('text'=>$language['please_login'],'link'=>''));
		}
		if($content_info['member_id']!=$_SESSION['member_id']){
			message(array('text'=>$language['permissions_is_not_enough'],'link'=>''));
		}
	}
	$content_id=empty($_POST['content_id'])?0:intval($_POST['content_id']);
	$content_title=empty($_POST['content_title'])?'':trim(addslashes($_POST['content_title']));
	$content_text=empty($_POST['content_text'])?'':trim(addslashes($_POST['content_text']));
	$content_password=empty($_POST['content_password'])?'':trim(addslashes($_POST['content_password']));
	$category_id=empty($_POST['category_id'])?0:intval($_POST['category_id']);
	$channel_id=empty($_POST['channel_id'])?0:intval($_POST['channel_id']);
	if($content_title==''){
		message(array('text'=>$language['content_title_is_empty'],'link'=>''));
	}
	if($content_text==''){
		message(array('text'=>$language['content_text_is_empty'],'link'=>''));
	}
	$content_thumb=upload($_FILES['content_thumb']);
	$content_thumb_old=empty($_POST['content_thumb_old'])?'':trim(addslashes($_POST['content_thumb_old']));
	$content_thumb_delete=empty($_POST['content_thumb_delete'])?'':trim(addslashes($_POST['content_thumb_delete']));
	$update=array();
	$update['content_title']=$content_title;
	$update['content_text']=$content_text;
	$update['content_password']=$content_password;
	if(!empty($content_thumb)){
		if(!empty($content_thumb_old)){//If something thumbnail delete
			@unlink(ROOT_PATH."/uploads/".$content_thumb_old);
		}
		if($config['image_thumb_open']=='yes'){//If set to generate thumbnail is generated
			make_thumb(ROOT_PATH.'/uploads/'.$content_thumb,$config['image_thumb_width'],$config['image_thumb_height']);
		}
		$update['content_thumb']=$content_thumb;
	}
	if(!empty($content_thumb_delete)){//If forced deletion shrinkage plan deleted
		@unlink(ROOT_PATH."/uploads/".$content_thumb_delete);
		$update['content_thumb']='';
	}
	$update['category_id']=$category_id;
	//print_r($update);exit;
	$db->update($db_prefix."content",$update,"content_id='".$content_id."'");

	if(!empty($_POST['content_link_delete'])){
		foreach($_POST['content_link_delete'] as $value){
			if(!empty($value)){
				$db->delete($db_prefix."content_link","link_id='".$value."'");
			}
		}
	}

	if(!empty($_POST['content_attachment_delete'])){
		foreach($_POST['content_attachment_delete'] as $value){
			if(!empty($value)){
				$row=$db->getone("SELECT attachment_name FROM ".$db_prefix."content_attachment WHERE attachment_id='".$value."'");
				@unlink(ROOT_PATH."/uploads/".$row['attachment_name']);
				$db->delete($db_prefix."content_attachment","attachment_id='".$value."'");
			}
		}
	}
	if(!empty($_POST['content_link'])){
		foreach($_POST['content_link'] as $value){
			if(!empty($value)){
				$db->insert($db_prefix."content_link",array('link_url'=>$value,'content_id'=>$content_id));
			}
		}
	}
	$content_attachment=upload($_FILES['content_attachment'],true);
	foreach($content_attachment as $value){
		if(!empty($value)){
			$db->insert($db_prefix."content_attachment",array('attachment_name'=>$value,'content_id'=>$content_id));
			if($config['image_text_open']=='yes'){
				make_watermark(ROOT_PATH.'/uploads/'.$value,ROOT_PATH.'/uploads/'.$config['image_file'],$config['image_pos'],$config['image_text']);
			}
		}
	}
	clear_cache();
	message(array('text'=>$language['content_update_success'],'link'=>'channel.php?id='.$channel_id));
}

if($action=='content_delete'){
	check_request();
	if(empty($_SESSION['admin_id'])){
		if(!check_login()){
			message(array('text'=>$language['please_login'],'link'=>''));
		}
		if($content_info['member_id']!=$_SESSION['member_id']){
			message(array('text'=>$language['permissions_is_not_enough'],'link'=>''));
		}
	}
	$content_id=empty($_GET['content_id'])?0:$_GET['content_id'];
	$content_info=get_content_info($content_id);

	if(!empty($content_id)){
		$row=$db->getone("SELECT content_thumb FROM ".$db_prefix."content WHERE content_id='".$content_id."'");
		if(!empty($row['content_thumb'])){
			@unlink(ROOT_PATH."/uploads/".$row['content_thumb']);
		}
		$res=$db->getall("SELECT attachment_name FROM ".$db_prefix."content_attachment WHERE content_id='".$content_id."'");
		foreach($res as $row){
			@unlink(ROOT_PATH."/uploads/".$row['attachment_name']);
		}
		$db->delete($db_prefix."content_link","content_id=".$content_id."");
		$db->delete($db_prefix."content_attachment","content_id=".$content_id."");
		$db->delete($db_prefix."content_comment","content_id=".$content_id."");
		$db->delete($db_prefix."content","content_id=".$content_id."");
	}
	clear_cache();
	message(array('text'=>$language['content_delete_success'],'link'=>'channel.php?id='.$content_info['channel_id']));
}

/////////////////////////////////
if($action=='check_member_mail'){
	check_request();
	$member_mail=empty($_GET['member_mail'])?'':trim($_GET['member_mail']);
	if(!is_email($member_mail)){
		echo('1');
		exit;
	}
	$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$member_mail."'");
	if($count>0){
		echo('1');
	}else{
		echo('0');
	}
	exit;
}
if($action=='check_member_pwd'){
	check_request();
	if(!isset($_COOKIE["member_id"])) exit('0|<a href="#" id="notlogin">请先登录</a>');
	$member_id=$_COOKIE["member_id"];
	$member_password=empty($_GET['pwd'])?'':password($_GET['pwd']);
	$r=$db->getone("select member_password from ".$db_prefix."member where member_id=$member_id and member_password='$member_password'");
	if($r) exit('1|success');
	exit('0|密码错误'.$member_password);
}
if($action=='check_member_nickname'){
	check_request();
	$member_nickname=empty($_GET['member_nickname'])?'':trim($_GET['member_nickname']);
	$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_nickname='".$member_nickname."'");
	if($count>0){
		echo('1');
	}else{
		echo('0');
	}
	exit;
}
if($action=='validate_mail'){
	$member_mail=empty($_GET['mail'])?'':trim(addslashes($_GET['mail']));
	$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$member_mail."'");
	if($count>0){
		exit('0|邮箱跟别人重复');
	}else{
    	exit('1|可以使用');
    }
}
if($action=='validate_nickname'){
	$member_nickname=empty($_GET['name'])?'':trim(addslashes($_GET['name']));
	$count=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_nickname='".$member_nickname."'");
	if($count>0){
		exit('0|昵称跟别人重复');
	}else{
    	exit('1|可以使用');
    }
}
if($action=='validate_safecode'){
	//$safecode=empty($_GET['safecode'])?'':trim(addslashes($_GET['safecode']));
	//$count=$db->getone("SELECT valid FROM ".$db_prefix."safecode WHERE safecode='".$safecode."'");
	//if($count['valid']==0){
	//	exit('0|无效的邀请码');
	//}else{
    	exit('1|邀请码有效');
   // } //2011-2-14
}
?>