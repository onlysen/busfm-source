<?PHP
require_once('admin/includes/config.php');
require_once('admin/includes/class_db.php');
$db=new db($db_host,$db_user,$db_password,$db_name);
if(!empty($_GET['g'])){
	$id=base64_decode(getIDFromRandom($_GET['g']));
	$song=$db->getone("select * from ".$db_prefix."content where content_id=$id");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="description" content="个人视角的独立摇滚电台，你喜欢，或是不喜欢，它就是这么存在着" /> 
	<meta name="keywords" content="摇滚,独立,音乐,电台,rock,indie,music,radio,bus.fm" />
	<meta name="application-name" content="Bus Fm" />
    <meta name="msapplication-starturl" content="/" />
	<meta name="msapplication-task" content="name=意见反馈;action-uri=/blog/archives/20;icon-uri=/favicon.ico">
	<meta name="msapplication-task" content="name=快捷键;action-uri=/blog/archives/82;icon-uri=/favicon.ico">
	<title><?PHP if(isset($song)) echo $song['content_title'].' - '.$song['content_keywords'].' - '; ?>巴士电台</title>
	<link href="/css/public.css?v=1.3.3.2" rel="stylesheet" type="text/css" />
	<!--[if IE 6]>
	<link href="css/ie6sucks.css?v=0.3" rel="stylesheet" type="text/css" />
	<![endif]-->
</head>
<?PHP
if(isset($_COOKIE["member_id"])){
	$member_id=$_COOKIE["member_id"];
	$name=$db->getone("select member_nickname from ".$db_prefix."member where member_id=$member_id");
	$member_nickname=$name[0];
	if($_COOKIE["member_nickname"]!=$member_nickname)
	setcookie("member_nickname", $member_nickname, time()+3600000,"/","bus.fm");
}
?>
<body style="position:relative;">
<span style="color:#088; font-family:arial; margin-left:170px;">beta</span>
	<div id="jp"></div>
	<div id="publicdiary">
	<div class="pbmain">
		<pre>
		音乐是一种态度
		不论是作者还是聆听者
		它流经心灵
		纵然期待影响现实的力量是种奢望
		如果刚巧有些声音
		会叫你记得一段往事
		伤痛或是甜蜜
		那记忆便有了厚厚的壳
		恒久的 温暖有如初生
		</div>
		<span class="pbauthor">
		落在低处
		</span>
		</pre>
	</div>
	<div id="coverimg">
		<img src="<?PHP if(isset($song)) echo $song['content_thumb']; else echo "/image/loading.gif";?>" alt="" class="<?PHP if(!isset($song)) echo "loading";?>"/>
	</div>
	<div id="songmeta">
		<div id="metas"></div>
		<div id="historys">
		</div>
	</div>
	<div class="statusbar">
		<div class="player-area">
			<div id="jplayer_control">
				<span class="sep"></span>
				<a hidefocus href="#" id="jplayer_previous" title="上一曲" style="display:none;"><span></span></a>
				<a hidefocus href="#" id="jplayer_play"><span title="播放"></span></a>
				<a hidefocus href="#" id="jplayer_pause"><span title="暂停"></span></a>
				<a hidefocus href="#" id="jplayer_stop" title="停止" style="display:none;"><span></span></a>
				<a hidefocus href="#" id="jplayer_next" title="跳过"><span></span></a>
				<span class="sep"></span>
				<a hidefocus href="#" id="fav"><span title="收藏" class=""></span></a>
				<a hidefocus href="#" id="recom"><span title="分享" class=""></span></a>
				<span class="sep"></span><!--[if lt ie 8]>&nbsp;<![endif]-->
				<span class="sep" id="mrleft"></span>
				<span id="PlayInfo">
					<span id="jplayer_load_bar"></span>
					<span id="pt">00:00</span>/<span id="tt">00:00</span>
				</span>
				<span id="volumeInfo">
					<span id="jplayer_vbar">
						<span id="vamount"></span>
					</span>
					<span class="sep"></span>
					<a hidefocus href="javascript:void(0);" id="jplayer_vmin"><span></span></a>
				</span>
				<span class="bar-right">
					<span class="sep"></span>
					<a hidefocus href="#" title="频道列表" id="channel_ico"><span></span></a>
					<a hidefocus href="#" title="个人专区" id="user_ico"><span></span></a>
				</span>
			</div>
			<!-- <div id="PlayInfo">
			</div> -->
		</div>
	</div>
	<div id="SongInfo">
		<div>
			<ul>
				<li></li>
				<li></li>
				<li></li>
			</ul>
		</div>
	</div>
	<div id="jplayer_playlist">
		<ul>
			<!-- The function displayPlayList() uses this unordered list -->
			<li></li>
		</ul>
	</div> 
	<div id="sidebar">
		<div id="sidebar-switch"></div>
		<div id="channel-list">
			<ul>
				<li cid="1">白</li>
				<li cid="2">灰</li>
				<li cid="3">黑</li>
				<li cid="4">红</li>
				<li cid="99">私人频道</li>
			</ul>
		</div>
		<div id="user-panel">
			<form id="form1" name="form1" action="/ajax/member?action=login_fm" method="post">
				<ul id="u-login">
					<li>您的E-mail地址：</li>
					<li><input type="text" value="" class="loginTxt" maxlength="40" id="txtemail" name="member_mail" /></li>
					<li>您的注册密码：</li>
					<li><input type="password" value="" class="loginTxt" maxlength="20" id="txtpwd" name="member_password" /></li>
					<li><input type="checkbox" id="cbxCookie" name="cbxCookie" checked /><label for="cbxCookie">记住我，下次自动登录</label></li>
					<li style="height:30px; line-height:30px; text-align:center;"><input type="submit" value="登&nbsp;录" class="graybtn" /></li>
					<li class="regli"><a href="/my/pwd" target="_blank" style="float:right;">忘记密码？</a><a href="javascript:void(0);" onclick="getaccount();">没有账号？</a></li>
					<li id="loginmsg"></li>
				</ul>
			</form>
			<form id="form2" name="form2" action="/ajax/member?action=register_fm" method="post">
				<ul id="u-reg">
					<li>您的E-mail地址：</li>
					<li><input type="text" value="" class="loginTxt txtreg" maxlength="40" id="txtemail_r" name="member_mail" /></li>
					<li>您的注册密码：</li>
					<li><input type="password" value="" class="loginTxt txtreg" maxlength="20" id="txtpwd_r" name="member_password" title="6-20位字母和数字" /></li>
					<li>请再输一次：</li>
					<li><input type="password" value="" class="loginTxt txtreg" maxlength="20" id="txtpwd_r2" name="member_password_confirm" /></li>
					<li>选择个昵称吧：</li>
					<li><input type="text" value="" class="loginTxt txtreg" maxlength="20" id="txtnickname" name="member_nickname" /></li>
					<!--<li>邀请码：</li>
					<li><input type="text" value="" class="loginTxt txtreg" maxlength="20" id="txtapply" name="member_safecode" /></li>-->
					<li style="text-align:center;"><input type="submit" value="确&nbsp;定" class="graybtn" /></li>
					<li class="regli"><center><a href="javascript:void(0);" onclick="goLogin();" hidefocus>已经有账号？</a></center></li>
					<li id="lblmsg"><!--勿删后面的P标签--><p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p></li>
				</ul>
			</form>
			<ul id="u-info">
				<li>乘客<span class="u-info-name"></span>您好：</li>
				<li>您于<span class="u-info-regtimes <?PHP if(isset($_COOKIE['join_time'])) echo 'havetime'; ?>"><?PHP echo date('Y年m月d日H时i分',$_COOKIE['join_time']); ?></span>搭乘本巴士</li>
				<li>目前已经行驶了<span class="u-info-lasttime">102小时58分</span></li>
				<li>这是一个漫长而未知的旅程</li>
				<li class="opli">
					<a href="javascript:void(0);" id="diarybox">日记</a>
					<a href="my/apply" target="_blank" id="invitef">邀请</a>
					<a href="javascript:logout();" id="logout">离开</a>
					<a href="my/profile" target="_blank" id="prof">管理</a>
				</li>
			</ul>
		</div>
		<div id="sidecover" class="sidewidget"></div>
		<div id="sideloading" class="sidewidget"></div>
	</div>
	<!--div id="favlist">
		<ul>
			<li>sude – live at denmark</li>
		</ul>
		<div id="list-top"></div>
		<div id="list-bottom"></div>
	</div-->
	<div id="clipbord" class="round">
		<div class="n-close"></div>
		<p>分享到：
			<a href="#" name="xl" style="" class="snsicon" title="新浪"></a>
			<a href="#" name="tx" style="background-position: 0pt -1072px;" class="snsicon" title="腾讯"></a>
			<a href="#" name="db" style="background-position: 0pt -112px;" class="snsicon" title="豆瓣"></a>
			<a href="#" name="ff" style="background-position: 0pt -272px;" class="snsicon" title="饭否"></a>
			<a href="#" name="rr" style="background-position: 0pt -32px;" class="snsicon" title="人人"></a>
			<a href="#" name="bz" style="background-position: 0pt -912px;" class="snsicon" title="Google Buzz"></a>
			<a href="#" name="tt" style="background-position: 0pt -624px;" class="snsicon" title="twitter"></a>
			<a href="#" name="fb" style="background-position: 0pt -592px;" class="snsicon" title="Facebook"></a>
		</p>
		<p>或按(<b>Ctrl+C</b>)复制以下地址，分享给站外好友:</p>
		<p><input type="text" value="" onclick="select();" class="mp3url" /></p>
	</div>
	<div id="msgs" class="alertdiv round"></div>
<?PHP 
$r=$db->getone("select * from ".$db_prefix."gongao where endtime>UNIX_TIMESTAMP( NOW( ) )  order by id desc limit 1");
if($r){
	echo '<div id="notice" class="round box-shadow-left"><div class="n-close"></div><div class="n-body">';
	echo $r["content"];
	echo "<input type='hidden' id='noticeid' value='".$r["id"]."'/>";
	echo '</div><div class="n-arr"></div></div>';
}
?>
	<!--box begin-->
			<div id="box-main">
			<div id="b-head">
				<div class="h-right"></div>
				<div class="h-left"></div>
				<div class="h-mid">
					<div id="boxclose"></div>
				</div>
			</div>
			<div id="b-body">
				<div class="b-main">
					<div class="b-cont">
						<div id="diarylist">
							<div class="diarytxt">
								<div class="diarybody">
									你随便的说<br />
									我却认真的难过
								</div>
								<div id="diaryop">
									<span id="diarydel" class="icodel" title="删除">删除</span>
									<span id="diarystatus" class="icopublic" title="公开">状态</span>
									<!--span class="icoprivate"></span-->
									<span class="diarytime">2010-12-31</span>
								</div>
							</div>
							<div class="leftarr"></div>
							<div class="rightarr"></div>
						</div>
						<textarea id="txtdiary" name="txtdiary" class="round"></textarea>
						<div class="right" id="diaryfoot">
							<div id="txtctr">200</div>
							<div id="privateflag">
								<input type="checkbox" name="isprivate" id="isprivate" value="0" />
								<label for="isprivate" title="把日记设为公开状态，可能会被推荐到电台首页哦">这是一条私人日记</label>
							</div>
							<div id="subdiary" class="round"></div>&nbsp;
						</div>
					</div>
				</div>
			</div>
			<div id="b-foot">
				<div class="f-right"></div>
				<div class="f-left"></div>
				<div class="f-mid"></div>
			</div>
			<div id="boxtitle"></div>
		</div>
		<?PHP
		$channelid=1;
		// if(!empty($_GET['g'])){
			$id=base64_decode(getIDFromRandom($_GET['g']));
			// $song=$db->getone("select * from ".$db_prefix."content where content_id=$id");
			if(!empty($song)){
				$info="<div style='display:none;' id='hidPriPlay' sid='$id'>";
				$info.="<span class='hpp_title'>".$song['content_title']."</span>";
				$info.="<span class='hpp_url'>".$song['content_url']."</span>";
				$info.="<span class='hpp_artist'>".$song['content_keywords']."</span>";
				$info.="<span class='hpp_album'>".$song['content_password']."</span>";
				$info.="<span class='hpp_thumb'>".$song['content_thumb']."</span>";
				$info.="</div>";
				echo $info;
				$channelid=$song['channel_id'];
			}
		// }
		//功能，从URL中恢复歌曲ID，默认ID前三后四为扰码
		function getIDFromRandom($random){
			$l=strlen($random);
			$s=-$l+4;
			$e=$l-7;
			return substr($random,$s,$e);
		}
		?>
	<!--box end-->
	<!--float song meta-->
	<div id="his-tips"><div class="ht-cont round box-shadow"></div><div class="ht-arr"></div></div>
	<div id="keyaction" class="round10"></div>
	<div id="keyhelp" class="round10">
		<div class="kh-top">
			<span class="fright"><a href="/blog/archives/82" class="flink" target="_blank">Open in a new Window</a> | <span class="flink" id="khclose">Close</span></span>
			<span class="kh-title">Keyboard shortcuts</span>
		</div>
		<div class="kh-body">
			<ul>
				<li><em><span>Space</span> or <span>shift+p</span></em> : Pause/Play</li>
				<li><em><span>n</span> or <span>shift+→</span></em> : Next</li>
				<li><em><span>f</span></em> : Favrate/Unfavrate</li>
				<li><em><span>s</span></em> : Share to Friends</li>
				<li><em><span>u</span> or <span>shift+↑</span></em> : Volume Up</li>
				<li><em><span>d</span> or <span>shift+↓</span></em> : Volume Down</li>
				<li><em><span>m</span></em> : Mute/Unmute</li>
				<li><em><span>w</span></em> : Write Diary</li>
				<li><em><span>/</span></em> : Show/Hide Hotkeys</li>
				<li><em><span>esc</span></em> : Close Hotkey/Share dialog</li>
			</ul>
		</div>
	</div>
	<!--[if lt ie 8]>
	<div id="ieupgrade">
	<span class="ieclose"></span>
	<span class="ietxt">本站大量内容基于HTML5和CSS3，为了得到最好的浏览效果和使用体验，请尝试下载或更新至最新的浏览器及版本...</span>
	</div>
	<![endif]-->
	<input type="hidden" id="hidchannel" name="hidchannel" value="<?PHP echo $channelid; ?>" />
	<?PHP include("forms/includes/copyright.php"); ?>
</body>
<script type="text/javascript" src="/js/jquery.1.4.min.js"></script>
<script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="/js/playControl.min.js?v=2.4.1.4"></script>
<!--script type="text/javascript" src="js/jquery.mousewheel.min.js"></script-->
<script type="text/javascript" src="/js/control.min.js?v=1.9.9.5"></script>
<!--[if IE 6]>
<script type="text/javascript" src="js/jquery.pngfix.min.js"></script>
<script type="text/javascript">
$(function(){$(".h-mid").pngFix();});
</script>
<![endif]-->
<!--[if IE 9]>
<script type="text/javascript" src="js/ie9.min.js"></script>
<![endif]-->
<script type="text/javascript" src=" http://js.tongji.linezing.com/1444603/tongji.js"></script>
</html>