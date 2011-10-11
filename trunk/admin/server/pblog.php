<?php
if(Extension_Loaded('zlib')) Ob_Start('ob_gzhandler'); 
Header("Content-type: text/html"); 

require_once('includes/global.php');
require_once('includes/front.php');
require_once(ROOT_PATH.'languages/'.$config['site_language'].'/front.php');
 
$sql = "SELECT * FROM ".$db_prefix."micblog  where recom = 1   ORDER BY  RAND() LIMIT 15";
$res2=$GLOBALS['db']->getall("SELECT * FROM ".$db_prefix."micblog WHERE recom=3 ORDER BY id DESC");//公告
$r2=count($res2);
if($r2>0){//公告
echo "[";
echo "[\"".$res2[0]['id']."\",\"".$res2[0]['content']."\",\"1\",\"admin\",\"3\",\"".date("Y-m-d H:i:s",$res2[0]['addtime'])."\"]";
echo "]"; 	 
}else{	 
	$res=$GLOBALS['db']->getall($sql);
	if($res){
		 echo "[";
			$r=0;
		foreach($res as $row){
			$uname=$row['uname'];
			if($row['uname']!=""||$row['uname']!="NULL"){
				$user=$GLOBALS['db']->getone("SELECT * FROM ".$db_prefix."member where member_id=".$row['uid']);
				$uname=$user['member_nickname'];
			}
			echo "[\"".$row['id']."\",\"".$row['content']."\",\"".$row['uid']."\",\"".$uname."\",\"".$row['recom']."\",\"".date("Y-m-d H:i:s",$row['addtime'])."\"]";
			
			if(++$r!=count($res)) echo ',';
		}
		echo "]"; 	
	} 
}
if(Extension_Loaded('zlib')) Ob_End_Flush(); 
?>