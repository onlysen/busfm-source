<?PHP
// sleep(3);
//~ exit("1|err msg");
//~ if($_GET["action"]=="validate_mail") exit ("1|0");
require_once('includes/config.php');
require_once('includes/class_db.php');
require_once('includes/global.php');
require_once('includes/front.php');
$action=$_GET["action"];
if($_GET["action"]=="validate_nickname"){//exit("1|success");
	check_request();
	if(!isset($_COOKIE["member_id"])) exit('0|请先登录');
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

if($_GET["action"]=='check_member_pwd'){
// exit("0|0");
	check_request();
	if(!isset($_COOKIE["member_id"])) exit('0|请先登录');
	$member_id=$_COOKIE["member_id"];
	$member_password=empty($_POST['member_password'])?'':password($_POST['member_password']);
	$r=$db->getone("select member_password from ".$db_prefix."member where member_id=$member_id and member_password=$member_password");
	if($r) exit('1|success');
	exit('0|密码错误');
}
if($action=='edit_pwd_fm'){
	if(!isset($_COOKIE["member_id"])) exit('0|请先登录');
	$member_id=$_COOKIE["member_id"];
	$pwd=empty($_GET['pwd'])?'':trim(addslashes($_GET['pwd']));
	$update=array();
	$update['member_password']=$pwd;
	$r=$db->update($db_prefix."member",$update,"member_id=$member_id");
	if($r)exit('1|密码修改成功，请重新登录');
	else exit("0|修改失败，请稍后再试");
}

if($action=='login_fm'){
	$db=new db($db_host,$db_user,$db_password,$db_name);
	$member_mail=empty($_REQUEST['member_mail'])?'':trim(addslashes($_REQUEST['member_mail']));
	$member_password=empty($_REQUEST['member_password'])?'':password($_REQUEST['member_password']);
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
		//授权
		$oauth_user_row=$db->getone("SELECT * FROM ".$db_prefix."oauth_user WHERE userid='".$row['member_id']."' ");
		if($oauth_user_row){
			require_once('basevar.php');
			if($oauth_user_row['siteid']=='qq'){
				$_SESSION[$configs['qq']['access_token']]=$oauth_user_row['access_token'];
				$_SESSION[$configs['qq']['oauth_token_secret']]=$oauth_user_row['oauth_token_secret '];
			}else{
				$_SESSION[$configs['sina']['token']]=array('access_token'=>$oauth_user_row['access_token'],'oauth_token_secret'=>$oauth_user_row['oauth_token_secret']);
			}
		}
		
		setcookie("member_id", $row['member_id'], time()+3600000,"/","bus.tv");
		setcookie("member_mail", $row['member_mail'], time()+3600000,"/","bus.tv");
		setcookie("member_nickname", $row['member_nickname'], time()+3600000,"/","bus.tv");
		setcookie("member_photo", $row['member_photo'], time()+3600000,"/","bus.tv");
		setcookie("group_id", $row['group_id'], time()+3600000,"/","bus.tv");
		setcookie("join_time", $row['member_join_time'], time()+3600000,"/","bus.tv");
		 
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
// require_once("includes/config.php");
// require_once("includes/class_db.php");
// $db=new db($db_host,$db_user,$db_password,$db_name);
// $update=array('member_nickname'=>$_POST['nickname']);
// $r=$db->update($db_prefix."member",$update,"member_id=1");
// if($r)exit("1|success");
// else exit("0|failuer");

// if($_GET["action"]=="register_fm"){
	// exit('1|sucess');
// }
// echo strtotime("2010-12-30 12:33:56").'<br>';
	// exit(date("Y-m-d H:i:s","1293696797"));
	//sleep(2);
	exit("1|sucess");
	echo '[["338","等待","http://ftp.luoo.net/radio/radio32/02.mp3","阿修罗乐队","唤醒沉睡的你","http://img3.douban.com/lpic/s1460783.jpg"],["114","The Way We Get By","http://ftp.luoo.net/radio/radio9/3.mp3","Kill the Moonlight","Kill the Moonlight","http://img3.douban.com/lpic/s1435558.jpg"],["271","Train","http://ftp.luoo.net/radio/radio25/07.mp3","3 Doors Down","3 Doors Down","http://img3.douban.com/lpic/s3310549.jpg"],["161","All Of Yours","http://ftp.luoo.net/radio/radio16/03.mp3","Making April","Runaway World","http://img5.douban.com/lpic/s3707515.jpg"],["281","Somewhere Else","http://ftp.luoo.net/radio/radio26/03.mp3","Razorlight","Somewhere Else, Pt. 2","http://img3.douban.com/lpic/s1483874.jpg"],["141","Smile [2005 mix]","http://ftp.luoo.net/radio/radio13/03.mp3","Flat7","Lost in Blue","http://img3.douban.com/lpic/s3076728.jpg"],["165","Free Loop","http://ftp.luoo.net/radio/radio16/08.mp3","Daniel Powter","dp","http://img3.douban.com/lpic/s4485293.jpg"],["87","尘世尘埃","http://ftp.luoo.net/radio/radio7/01.mp3","纹子&凸古堂乐队","幕舞会","http: //img3.douban.com/lpic/s3138987.jpg"],["416","Nine Million Bicycles","http://ftp.luoo.net/radio/radio39/07.mp3","Katie Melua","Piece By Piece","http://otho.douban.com/lpic/s2658447.jpg"],["339","化学心情下的爱情反应","http://ftp.luoo.net/radio/radio32/03.mp3","达达乐队","天使","http: //img3.douban.com/lpic/s3185598.jpg"]]'
?>