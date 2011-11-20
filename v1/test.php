<?PHP
exit(0);
require_once("admin/includes/config.php");
require_once("admin/includes/class_db.php");
$db=new db($db_host,$db_user,$db_password,$db_name);
$member_id=$_COOKIE["member_id"];
$a="select member_nickname from ".$db_prefix."member where member_id=1";
$nickname=$db->getone($a);
exit($nickname[0]);
// $song=$db->getone("select * from ".$db_prefix."content where content_id=$id");
$id=$_GET['id'];
$url="http://bus.fm?g=";
$url.=random(4);
$url.=base64_encode($id);
$url.=random(3);
exit($url);
// $r=$db->getone("select * from ".$db_prefix."gongao where endtime>UNIX_TIMESTAMP( NOW( ) )  order by id desc limit 1");
//$r=$db->getcount("SELECT * FROM ".$db_prefix."member WHERE member_mail='404error@163.com'");

$member_id=$_COOKIE["member_id"];
$r=$db->getone("select member_password from ".$db_prefix."member where member_id=$member_id ");
var_dump($r);
exit();
// $update=array('member_nickname'=>'walker');
// $r=$db->update($db_prefix."member",$update,"member_id=1");
// if($r)echo "success";
// else echo "fail";
// $member['member_nickname']=$_GET

// $member_mail=empty($_GET['member_mail'])?'':trim($_GET['member_mail']);
// $member_mail="admin@admin.com";
// $member=$db->getone("SELECT * FROM ".$db_prefix."member WHERE member_mail='".$member_mail."'");
// var_dump($member);
// echo date('Y-m-d H:i:s',1292223361);
echo time2Units(time()-1292223361);

/**
* 时间差计算
*
* @param Timestamp $time
* @return String Time Elapsed
* @author Shelley Shyan
* @copyright http://phparch.cn (Professional PHP Architecture)
*/
function time2Units ($time)
{
  $year  = floor($time / 60 / 60 / 24 / 365);
  $time  -= $year * 60 * 60 * 24 * 365;
  $month  = floor($time / 60 / 60 / 24 / 30);
  $time  -= $month * 60 * 60 * 24 * 30;
  $week  = floor($time / 60 / 60 / 24 / 7);
  $time  -= $week * 60 * 60 * 24 * 7;
  $day    = floor($time / 60 / 60 / 24);
  $time  -= $day * 60 * 60 * 24;
  $hour  = floor($time / 60 / 60);
  $time  -= $hour * 60 * 60;
  $minute = floor($time / 60);
  $time  -= $minute * 60;
  $second = $time;
  $elapse = '';

  $unitArr = array('年'  =>'year', '个月'=>'month',  '周'=>'week', '天'=>'day',
                    '小时'=>'hour', '分钟'=>'minute', '秒'=>'second'
                    );

  foreach ( $unitArr as $cn => $u )
  {
      if ( $$u > 0 )
      {
          $elapse = $$u . $cn;
          break;
      }
  }

  return $elapse;
}

// $past = 2052345678; // Some timestamp in the past
// $now  = time();    // Current timestamp
// $diff = $now - $past;

// echo '发表于' . time2Units($diff) . '前';

// 功能：获取指定长度的随机字母数字组合   
function random($length, $string = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz')   
{   
    $random = '';   
    $strlen = strlen($string);   
  
    for ($i=0; $i<$length; ++$i)   
    {   
        $random .= $string{mt_rand(0, $strlen-1)};     
    }   
  
    return $random;   
}  
//功能，从URL中恢复歌曲ID，默认ID前三后四为扰码
function getIDFromRandom($random){
	$l=strlen($random);
	$s=-$l+4;
	$e=$l-7;
	return substr($random,$s,$e);
}
?>