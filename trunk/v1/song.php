<?PHP if($_SESSION['member_id']!='4'&&$_COOKIE['member_id']!='4') exit(0); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>song search</title>
		<style type="text/css">
		.gb{background:#ddd;}
		td{padding:3px 5px;}
		</style>
	</head>
<body>
	<form id="form1" name="form1" action="" method="post">
		title:<input type="title" name="title" id="title" value="<?PHP echo $_POST['title']; ?>" />
		artist:<input type="artist" name="artist" id="artist" value="<?PHP echo $_POST['artist']; ?>" />
		album:<input type="album" name="album" id="album" value="<?PHP echo $_POST['album']; ?>" />
		<input type="hidden" name="hid" id="hid" value="hid" />
		<input type="submit" name="submit" id="submit" value="submit" />
	</form>
<?PHP
 if(!empty($_POST['hid'])){
	if(empty($_POST['title'])&&empty($_POST['artist'])&&empty($_POST['album'])) exit("filter key words required.");
	require_once("../admin/includes/config.php");
	require_once("../admin/includes/class_db.php");
	$db=new db($db_host,$db_user,$db_password,$db_name);
	$where=" where 1=1 ";
	if(!empty($_POST['title'])) $where.=" and content_title like '%".addslashes($_POST['title'])."%' ";
	if(!empty($_POST['artist'])) $where.=" and content_keywords like '%".addslashes($_POST['artist'])."%' ";
	if(!empty($_POST['album'])) $where.=" and content_password like '%".addslashes($_POST['album'])."%' ";
	$song=$db->getall("select * from ".$db_prefix."content $where");
	echo "select * from ".$db_prefix."content $where <br/>";
	if(count($song)>0){
		$i=0;
		echo "<table><tr><th>row</th><th>id</th><th>title</th><th>artist</th><th>album</th><th>url</th></tr>";
		foreach($song as $s){
			echo "<tr><td>".$i++."</td><td class='gb'>".$s['content_id']."</td><td>".$s['content_title']."</td><td class='gb'>".$s['content_keywords']."</td><td>".$s['content_password']."</td><td class='gb'>".geturl($s['content_id'])."</td><td>".$s['content_url']."</td><td class='gb'>".$s['content_thumb']."</td></tr>";
		}
		echo "</table>";
	}
	else echo 'no result.';
}
function geturl($id){
	// $url="http://bus.fm?g=";
	$url="http://bus.fm/share/";
	$url.=random(4);
	$url.=base64_encode($id);
	$url.=random(3);
	$url=str_replace("=","",$url);
	return "<a href='$url' target='_blank'>$url</a>";
}
// 功能：获取指定长度的随机字母数字组合   
function random($length, $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz') {
    $random = '';   
    $strlen = strlen($string);   
  
    for ($i=0; $i<$length; ++$i)   
    {   
        $random .= $string{mt_rand(0, $strlen-1)};     
    }   
  
    return $random;   
} 
?>
</body>
</html>