<?php
require_once('includes/global.php');
require_once('includes/front.php');
require(ROOT_PATH.'languages/'.$config['site_language'].'/front.php');
if(isset($_GET['id'])){
	if(isset($_SESSION['member_mail'])||isset($_COOKIE['member_mail'])){
	if(isset($_SESSION['member_id'])){$member_id = $_SESSION['member_id'];}
	if(isset($_COOKIE['member_id'])){$member_id = $_COOKIE['member_id'];}
		$id=intval($_GET['id']);
		if($id){ 
				$count2=$db->getcount("SELECT * FROM ".$db_prefix."member_mp3 WHERE user_id=".$member_id." AND mp3_id=".$id);
				if($count2>0){
					echo 1;
				}else{ 
					echo 0;
				} 
		}else{
			echo 0;
		}
	}else{
		echo 0;
	}
}else{
	echo 0;
}
?>