<?PHP
$action=empty($_GET["action"])?'':$_GET["action"];
switch($action){
	case "url"://生成歌曲外链
		$id=empty($_GET["id"])?0:$_GET["id"];
		//$url="http://".$_SERVER['HTTP_HOST']."?g=";
		$url="http://bus.fm/share/";
		$url.=random(4);
		$url.=base64_encode($id);
		$url.=random(3);
		$url=str_replace("=","",$url);
		exit("$url");
		break;
	default:
		break;
}
// 功能：获取指定长度的随机字母数字组合   
function random($length, $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'){
	$random = '';   
    $strlen = strlen($string);   
  
    for ($i=0; $i<$length; ++$i)   
    {   
        $random .= $string{mt_rand(0, $strlen-1)};     
    }   
  
    return $random;   
}  
?>