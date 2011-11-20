<?PHP
include '../admin/basevar.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>巴士电台</title>
		<script>
		if (typeof localStorage == "undefined"){
			alert("您正在使用的浏览器不支持巴士电台的大部分功能，正在为您跳转至旧版...");
			location.href="/";
		}
		</script>
		<link href="css/public.css?v=0.21" rel="stylesheet" type="text/css" />
		<style type="text/css">
		</style>
	<!--[if IE 6]>
	<link href="css/ie6sucks.css?v=0.3" rel="stylesheet" type="text/css" />
	<![endif]-->
	</head>
<body>
	<div id="jp"></div>
	<!--960-->
	<!--header-->
	<div id="header" class="bgray">
		<div id="hd-container">
			<div class="login-bar">
				<a href="javascript:void(0);" id="actlogin" class="unlogin">登录</a>
				<a href="javascript:void(0);" id="actreg" class="unlogin">注册</a>
				<a href="#diary" class="logged">日记</a>
				<span class="logged">&nbsp;|&nbsp;</span>
				<span id="login-name" class="logged ucontext"></span>
				<span class="login-action downarr logged ucontext"></span>
				<ul id="p-context-menu">
					<li><a href="#cpanel" class="page-cpanel">个人中心</a></li>
					<li><a href="javascript:void(0);" onclick="logout();">退出</a></li>
				</ul>
			</div>
			<div class="channel-bar">
				<a href="javascript:void(0);" class="cb-public">公共频道</a>
				<a href="javascript:void(0);" class="cb-private">私人频道</a>
			</div>
			<div class="uparr"></div>
		</div>
	</div>
	<!--header end-->
	<!--mainbody-->
	<div id="mainbody">
		<div id="mainpage">
			<div id="toolbar">
				<div id="v-handle"></div>
				<div id="channellist">
					<span class="cur round10 wavestatic ch-public" cid="1"><a href="javascript:void(0);">白MHz</a></span>
					<span cid="2" class="round10 ch-public"><a href="javascript:void(0);">灰MHz</a></span>
					<span cid="3" class="round10 ch-public"><a href="javascript:void(0);">黑MHz</a></span>
					<span cid="4" class="round10 ch-public"><a href="javascript:void(0);" style="margin-right:156px;">红MHz</a></span>
					<span cid="99" class="round10 ch-private"><a href="javascript:void(0);">MeMHz</a></span>
				</div>
				<div id="changeview"></div>
			</div>
			<div id="singleview" class="slidepage">
				<div id="dock"><img src="img/dock.png" alt=""></div>
				<div id="leftdetail">
					<div class="song-meta">
						<div><span id="s-title"></span></div>
						<div>歌手：<span id="s-artist"></span></div>
						<div class="song-meta-ext">
							<div><span id="s-album"></span></div>
							<div id="metas"></div>
						</div>
					</div>
					<div id="song-history"></div>
				</div>
				<div id="rightcover"><div class="cover-img"><img src="img/default_l.png" alt="" /></div></div>
			</div>
			<div id="multiview" class="slidepage">
				<ul id="alumn-wall"></ul>
				<div id="m-song-meta">
					<p class="msmeta-title"></p>
					<p class="msmeta-detail"></p>
				</div>
			</div>
			<div id="player">
				<div id="jplayer_control" title="鼠标滚轮可以调音量哦">
					<span class="player-right"></span>
					<span class="player-left"></span>
					<a hidefocus href="#" id="jplayer_play"><span title="播放"></span></a>
					<a hidefocus href="#" id="jplayer_stop" style="display:none;"><span title="停止"></span></a>
					<a hidefocus href="#" id="jplayer_pause" style="display:none;"><span title="暂停"></span></a>
					<a hidefocus href="#" id="fav"><span title="收藏" class=""></span></a>
					<a hidefocus href="#" id="jplayer_next" title="跳过"><span></span></a>
					<span class="sep"></span>
					<div id="seekbar">
						<div class="j-play-bar"></div>
						<div  class="j-seek-bar"></div>
					</div>
					<div class="duration"><span id="pt">00:00</span>/<span id="tt">00:00</span></div>
					<a hidefocus href="javascript:void(0);" id="jplayer_vmin"><span></span></a>
					<div id="jpalyer_v_wrap">
						<div id="jplayer_vbar" class="jpva">
							<div id="vamount" class="jpva"></div>
						</div>
					</div>
					<span class="sep narrsep"></span>
					<div id="share" title="分享到微博">分享<div class="downarr"></div>
					</div>
				</div>
			</div>
		</div>
		<div id="subpage"></div>
	</div>
	<!--mainbody end-->
	<!--footer-->
	<div id="footer-index" class="footer bgray">
	<div id="pbwrap">
		<div id="pbcountdown"><div><!--countdown--></div></div>
		<div id="publicdiary"><table id="diatb"><tr><td valign="middle" height=80><span class="pbauthor"></span>: <span class="pbmain"></span></td></tr></table></div></div>
		<div id="copyright">
		<span>&copy;2011 <a href="#">Bus.Fm</a> 版权所有</span>&nbsp;|
		<span><a href="#about" class="page-about">关于巴士</a></span>&nbsp;|
		<span><a href="/blog" class="page-about" target="_blank">官方博客</a></span>&nbsp;|
		<!-- <span><a href="#links" class="page-links">巴士链接</a></span>&nbsp;| -->
		<span><a href="/blog/archives/138" target="_blank">意见反馈</a></span>&nbsp;|
		<span><a href="javascript:void(0);" id="openkeydialog">快捷键</a></span>&nbsp;&nbsp;
		<a href="http://weibo.com/indieradio" target="_blank"><img src="img/sinacolor.png" alt="微博" title="微博" style="position:relative; top:3px;"></a>&nbsp;&nbsp;
		<a href="#android"><img src="img/android.png" alt="android应用" title="android应用" style="position:relative; top:5px;"></a>
		</div>
	</div>
	<!--footer end-->
	<!--float layers-->
	<div id="logo"></div>
	<div id="clipbord" class="round">
		<div class="n-uparr"></div>
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
		<p><input type="text" value="" onclick="select();" class="mp3url" readonly /></p>
	</div>
	<div id="msgs" class="round10 box-shadow"></div>
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
	<div id="srccover"></div>
	<div id="dg-container">
		<div class="dg-c-header">
			<span class="dg-c-close"><a href="javascript:void(0);"></a></span>
			<span class="dg-c-title">登录</span><span class="dg-c-arr"> &#187;</span>
		</div>
		<div class="dg-c-body">
			<div class="dg-c-info"><div class="dgcinfo-body"></div></div>
			<form id="form1" name="form1" action="/ajax/member?action=login_fm" method="post">
				<ul id="u-login">
					<li>您的E-mail地址：</li>
					<li><input type="text" value="" class="loginTxt" maxlength="40" id="txtemail" name="member_mail" tabindex="1" />&nbsp;
					<a href="javascript:void(0);" onclick="getaccount();">没有账号？</a></li>
					<li>您的注册密码：</li>
					<li><input type="password" value="" class="loginTxt" maxlength="20" id="txtpwd" name="member_password" tabindex="1" />&nbsp;
					<a href="#reset">忘记密码？</a></li>
					<li style="height:34px;line-height:34px;"><input type="checkbox" id="cbxCookie" name="cbxCookie" checked /><label for="cbxCookie">记住我，下次自动登录</label></li>
					<li><input type="submit" value="登&nbsp;录" class="roundbtn" tabindex="1" /></li>
					<li id="loginmsg"></li>
				</ul>
			</form>
			<form id="form2" name="form2" action="/ajax/member?action=register_fm" method="post">
				<ul id="u-reg">
					<li>您的E-mail地址：</li>
					<li><input type="text" value="" class="loginTxt txtreg" maxlength="40" id="txtemail_r" name="member_mail" tabindex="1" />&nbsp;<a href="javascript:void(0);" onclick="goLogin();" hidefocus>已经有账号？</a></li>
					<li>您的注册密码：</li>
					<li><input type="password" value="" class="loginTxt txtreg" maxlength="20" id="txtpwd_r" name="member_password" title="6-20位字母和数字" tabindex="1" /></li>
					<li>请再输一次：</li>
					<li><input type="password" value="" class="loginTxt txtreg" maxlength="20" id="txtpwd_r2" name="member_password_confirm" tabindex="1" /></li>
					<li>选择个昵称吧：</li>
					<li><input type="text" value="" class="loginTxt txtreg" maxlength="20" id="txtnickname" name="member_nickname" tabindex="1" /></li>
					<li>&nbsp;</li>
					<li><input type="submit" value="注&nbsp;册" class="roundbtn" tabindex="1" /></li>
				</ul>
			</form>
		</div>
	</div>
	<div id="ajaxload"></div>
	<input type="hidden" name="hidchannel" id="hidchannel" value="1" />
</body>
<script type="text/javascript">
var domain='<?=$configs["domain"]?>';
</script>
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="js/reflection.js"></script>
<script type="text/javascript" src="js/bustool.js?v=0.1"></script>
<script type="text/javascript" src="js/busplayer.min.js?v=0.55"></script>
<script type="text/javascript" src="js/busfunc.js?v=0.26"></script>
<script type="text/javascript" src="js/buspages.js?v=0.22"></script>
<!--[if IE 9]>
<script type="text/javascript" src="js/ie9.min.js"></script>
<![endif]-->
<script type="text/javascript" src=" http://js.tongji.linezing.com/1444603/tongji.js"></script>
</html>