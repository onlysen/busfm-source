<?PHP
require_once('admin/includes/config.php');
require_once('admin/includes/class_db.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache, must-revalidate">
	<meta http-equiv="expires" content="wed, 26 feb 1997 08:21:57 gmt">
	<meta name="description" content="个人视角的独立摇滚电台，你喜欢，或是不喜欢，它就是这么存在着" /> 
	<meta name="keywords" content="摇滚,独立,音乐,电台,rock,indie,music,radio,bus.fm" /> 
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" href="favicon.gif" type="image/gif">
	<title>巴士电台</title>
	<link href="css/public.css?v=0.9" rel="stylesheet" type="text/css" />
	<!--[if IE 6]>
	<link href="css/ie6sucks.css?v=0.3" rel="stylesheet" type="text/css" />
	<![endif]-->
</head>
<body style="position:relative;">
<span style="margin-left:169px; color:#fff; font-family:arial;"><span style="color:#088;">beta</span></span>
	<div id="jp"></div>
	<div id="debugdiv" style="float:right; display:none;">
		<a hidefocus href="#" id="toggleDebug">debug</a>
		<span></span>
	</div>
	<div id="coverimg">
		<img src="image/loading.gif" alt="" class="loading"/>
	</div>
	<div class="statusbar">
		<div class="player-area">
			<div id="SongInfo">
				<div>
					<ul>
						<li>Unknow Title</li>
						<li>Unknow Artist</li>
						<li>Unknow Album</li>
					</ul>
				</div>
			</div>
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
		<p>请按右键或(<b>Ctrl+C</b>)复制以下地址，分享给站外好友：<br/>
		<input type="text" value="" onclick="select();" class="mp3url" /></p>
	</div>
	<div id="msgs" class="alertdiv round"></div>
	<div id="notice" class="round">
		<div class="n-close"></div>
		<div class="n-body">
<?PHP 
$db=new db($db_host,$db_user,$db_password,$db_name);
$r=$db->getone("select * from ".$db_prefix."gongao where endtime>UNIX_TIMESTAMP( NOW( ) )  order by id desc limit 1");
if($r){
	echo $r["content"];
	echo "<input type='hidden' id='noticeid' value='".$r["id"]."'/>";
}
?>
		</div>
		<div class="n-arr"></div>
	</div>
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
		if(!empty($_GET['g'])){
			$id=base64_decode(getIDFromRandom($_GET['g']));
			$song=$db->getone("select * from ".$db_prefix."content where content_id=$id");
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
		}
		//功能，从URL中恢复歌曲ID，默认ID前三后四为扰码
		function getIDFromRandom($random){
			$l=strlen($random);
			$s=-$l+4;
			$e=$l-7;
			return substr($random,$s,$e);
		}
		?>
	<!--box end-->
	<!--[if lt ie 8]>
	<div id="ieupgrade">
	<span class="ieclose"></span>
	<span class="ietxt">本站基于HTML5和CSS3开发，尽管我们尽可能为老款浏览器做了兼容，为了得到最好的浏览效果和体验，请下载或更新至最新的浏览器及版本...</span>
	</div>
	<![endif]-->
	<input type="hidden" id="hidchannel" name="hidchannel" value="<?PHP echo $channelid; ?>" />
	<div id="footer">
		<span class="rights">&copy;2011 Bus.Fm 版权所有</span>
		<span class="links">
			<a href="/about" target="_blank">关于我们</a>|
			<a href="/blog/" target="_blank">官方博客</a>|
			<a href="/blog/?p=20" target="_blank">意见反馈</a>|
			交流群：36766919
		</span>	
	</div>
</body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="js/playControl.min.js?v=2.1.1"></script>
<!--script type="text/javascript" src="js/jquery.mousewheel.min.js"></script-->
<script type="text/javascript" src="js/control.min.js?v=1.8.7"></script>
<!--[if IE 6]>
<script type="text/javascript" src="js/jquery.pngfix.min.js"></script>
<script type="text/javascript">
$(function(){$(".h-mid").pngFix();});
</script>
<![endif]-->
<script type="text/javascript" src=" http://js.tongji.linezing.com/1444603/tongji.js"></script>
</html>