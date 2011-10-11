<?PHP
if(!isset($_COOKIE["member_id"])) exit("请先登录");
require_once('../admin/includes/config.php');
require_once('../admin/includes/class_db.php');
$db=new db($db_host,$db_user,$db_password,$db_name);
$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id'];
$sql = "SELECT * FROM ".$db_prefix."micblog WHERE uid='".$uid."' ORDER BY id DESC ";
$page_size=15;
$page_current=1;
$count=$db->getcount($sql);
$res=$db->getall($sql." limit ".(($page_current-1)*$page_size).",".$page_size);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title></title>
	</head>
<body>
	<div id="diary" class="page-body">
<style type="text/css">
.diarybox{width:100%; height:70px;border:1px solid #eee; padding:5px; resize: none}
.diaryfoot{margin:10px 20px;}
.diary-list{ min-height:360px;overflow-y:auto;}
.diary-list li{border-bottom:1px solid #eee;padding:3px 15px;}
.diary-list li:hover{background:#ffd}
.diary-list li .diary-entry{font-size:14px; }
.icodel,.icopublic,.icoprivate{background:url(img/sprint2.gif) no-repeat 0 -317px; display:inline-block; padding-left:14px; height:14px; cursor:pointer;filter:alpha(opacity=60); opacity:0.6;}
.diary-meta span{margin-right:5px;}
.diary-meta span:hover{filter:alpha(opacity=100); opacity:1;}
.icopublic{background-position:0 -346px;}
.icoprivate{background-position:0 -332px;}
.diary-icos{display:none;}
.diary-list li:hover .diary-icos{display:inline-block;}
.diary-more{height:30px; line-height:30px; text-align:center;cursor:pointer;}
.diary-more:hover{background-color:#ffc;}
.diary-loading{background:url(img/loading.gif) center center no-repeat;}
#txtctr{margin-right:20px; font-weight:600; color:#999; font-size:16px; font-family:'helvetica neue', 'lucida grande', helvetica, arial;#top:6px; #right:85px;}
</style>
		<h3>个人日记</h3>
		<textarea class="round diarybox" id="txtdiary" tabindex="1"></textarea>
		<div class="diaryfoot">
			<input type="submit" id="subdiary" value="发&nbsp;布" class="roundbtn fright" tabindex="1" />
			<div id="txtctr" class="fright">200</div>
			<input type="checkbox" name="isprivate" id="isprivate" value="0" tabindex="2" />
			<label for="isprivate" title="把日记设为公开状态，可能会被推荐到电台首页哦">这是一条私人日记</label>
			<?PHP
			require_once('../admin/includes/config.php');
			require_once('../admin/includes/class_db.php');
			$db=new db($db_host,$db_user,$db_password,$db_name);
			$ua=$db->getone("select userid from ".$db_prefix."oauth_user where userid=$uid and siteid='sina' ");
			$ua2=$db->getone("select userid from ".$db_prefix."oauth_user where userid=$uid and siteid='qq' ");
			if($ua||$ua2){
				echo '<input type="checkbox" name="synsns" id="synsns" value="1" tabindex="2" checked />';
				$linked="";
				if($ua) $linked.='[sina]';
				if($ua2) $linked.='[qq]';
				echo '<input type="hidden" name="hidsns" id="hidsns" value="'.$linked.'" />';
			}else{
				echo '<input type="checkbox" name="synsns" id="synsns" value="1" tabindex="2" disabled />';
			}
			?><label for="synsns" title="同步到微博">同步到微博</label>
			<span class="icon-question" title="您可以在个人中心设置可同步的微博"></span>
		</div>
		<div class="diary-list">
			<ul>
			<?PHP
				if($res){
					foreach($res as $row){
						//$row['recom'] -1:private,0:public,1:recomond
						$status=$row['recom']==-1?'icoprivate':'icopublic';
						$statustext=$row['recom']==-1?'私人':'公开';
						echo '<li data-entry="'.$row['id'].'">';
						echo '<div class="diary-entry">'.urldecode ($row['content']).'</div>';
						echo '<div class="diary-meta">';
						echo '<span class="diarytime">'.date("Y-m-d h:i:s",$row['addtime']).'</span>';
						echo '<span class="diary-del icodel diary-icos" title="删除">删除</span>';
						echo '<span class=" diary-icos diary-status '.$status.'" title="'.$statustext.'">状态</span></div></li>';
					}
				}else{echo '';}
			?>
			</ul>
		</div>
		<?php if($res) echo '<div class="diary-more">更多...</div>';?>
	</div>
</body>
</html>