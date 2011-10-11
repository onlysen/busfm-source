<?php
require_once('includes/global.php');
require_once('includes/front.php');
require_once(ROOT_PATH.'languages/'.$config['site_language'].'/front.php');

 
	if (isset($_GET['id'])){
		$id=(int)floor(abs($_GET['id']));
	 }else{
	 	$id=1;
	 } 
	if($id<80){
		$sql ="select * from `".$GLOBALS['db_prefix']."content` WHERE  `content_state`!=0 and `channel_id`=".$id." ORDER BY  RAND() LIMIT 10";
		$res = $GLOBALS['db']->getAll($sql);
		$num=0;
		$s='[';
		foreach ($res AS $row){
			$num++;
			$s.= '[';
			$s.= '"'.$row['content_id'].'",';
			$s.= '"'.$row['content_title'].'",';
			$s.= '"'.$row['content_url'].'",';
			$s.= '"'.$row['content_keywords'].'",';
			$s.= '"'.$row['content_password'].'",';
			 
			if(strrpos($row['content_thumb'],"http://")!==false && strrpos($row['content_thumb'],"http://")==0){$thumb=$row['content_thumb'];}else{$thumb="http://bus.fm/admin/uploads/".$row['content_thumb'];}
					 
			$s.= '"'.$thumb.'"';
			if($num==count($res))$s.= ']';else $s.= '],';
		}
		$s.= ']';
		echo $s;
	 
	}else{
	//////////////////私人收藏
	if($id==99){
		if(isset($_SESSION['member_id'])||isset($_COOKIE['member_id'])){}else{exit("0|请登陆后再来这里。");}
	$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id'];
	 
	 
		$sql ="select * from `".$GLOBALS['db_prefix']."content` as c ,`".$GLOBALS['db_prefix']."member_mp3` as m  WHERE  c.content_state!=0 and c.content_id=m.mp3_id and m.user_id=".$uid." ORDER BY  RAND() LIMIT 10";
		$res = $GLOBALS['db']->getAll($sql);
		$num=0;
		$s='[';
		foreach ($res AS $row){
			$num++;
			$s.= '[';
			$s.= '"'.$row['content_id'].'",';
			$s.= '"'.$row['content_title'].'",';
			$s.= '"'.$row['content_url'].'",';
			$s.= '"'.$row['content_keywords'].'",';
			$s.= '"'.$row['content_password'].'",';
			 
			if(strrpos($row['content_thumb'],"http://")!==false && strrpos($row['content_thumb'],"http://")==0){$thumb=$row['content_thumb'];}else{$thumb="http://luoo.net/bus/uploads/".$row['content_thumb'];}
					 
			$s.= '"'.$thumb.'"';
			if($num==count($res))$s.= ']';else $s.= '],';
		}
		$s.= ']';
		if (isset($_GET['callback']))$c=$_GET['callback'];
		if(isset($c))echo $c."(".$s.")";else echo $s;
	}elseif($id==100){
		$eggstring=fopen_url("http://api.bus.fm/pt/getshengqu");
		if (isset($_GET['callback']))$c=$_GET['callback'];
		if(isset($c))echo $c."(".$eggstring.")";else echo $eggstring;
	}
	} 
/** 
    获取远程文件内容 
    @param $url 文件http地址 
*/ 
function fopen_url($url) 
{ 
    if (function_exists('file_get_contents')) { 
        $file_content = @file_get_contents($url); 
    } elseif (ini_get('allow_url_fopen') && ($file = @fopen($url, 'rb'))){ 
        $i = 0; 
        while (!feof($file) && $i++ < 1000) { 
            $file_content .= strtolower(fread($file, 4096)); 
        } 
        fclose($file); 
    } elseif (function_exists('curl_init')) { 
        $curl_handle = curl_init(); 
        curl_setopt($curl_handle, CURLOPT_URL, $url); 
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2); 
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($curl_handle, CURLOPT_FAILONERROR,1); 
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Trackback Spam Check'); //引用垃圾邮件检查
        $file_content = curl_exec($curl_handle); 
        curl_close($curl_handle); 
    } else { 
        $file_content = ''; 
    } 
    return $file_content; 
}
?>