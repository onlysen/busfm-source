﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.gif" type="image/gif">
		<title>电台巴士</title>
		<style type="text/css">
		*{margin:0; padding:0;}
		html,body{width:100%; height:100%;font-size:12px; overflow:hidden;}
		#header{height:60px;}
		#logo{background:url(../image/bg2.png) no-repeat 0 0; position:relative; left:33px; width:63px; height:118px;}
		#cont{position:relative;}
		ul#navi{ position:absolute;left:0;top:80px; width:200px; z-index:100;}
		#navi li{list-style:none; height:48px; line-height:48px; color:#777; font-family:微软雅黑,宋体; font-size:20px; padding-right:65px; text-align:right; cursor:pointer; width:135px;}
		#navi li.cur{ background:url(../image/pnlarr.png) no-repeat right -10px; color:#088;}
		#navi li:hover{color:#a00;}
		#tab{border:1px solid #8bb; height:550px; position:absolute; left:199px; top:0;z-index:20; background:#fcfcdd url(../image/formbg2.png) no-repeat right bottom; _background-image:url(../image/ie6/form2.gif);}
		
		#footer{position:absolute; bottom:0; left:0; width:100%; height:24px; line-height:24px; background:#efefef; color:#666; z-index:9998; padding-left:15px;}
		#footer a{color:#666; margin:auto 6px; text-decoration:none;}
		#footer a:hover{text-decoration:underline;}
		#footer .rights{float:right; margin-right:35px;}
		
		#msgs{ position:absolute; top:33px;left:48%; opacity:0; filter:alpha(opacity=0); padding:5px 10px; -moz-border-radius: 5px;-khtml-border-radius: 5px;-webkit-border-radius:5px;border-radius:5px;}
		.alertdiv{border:1px solid #088; background:#088; color:#eee;}
		.errdiv{border:1px solid #a33; background:#a33; color:#eee;}
		
		#tabbg{background:gray; opacity:0.22; filter:alpha(opacity=22); width:826px; height:552px; position:absolute; top:6px; left:206px}
		</style>
	</head>
<body>
	<div id="header">
		<div id="logo">
			<span style="margin-left:46px; color:#fff; font-family:arial;">alp<span style="color:#088; font-family:arial;">ha</span></span>
		</div>
	</div>
	<div id="cont">
		<ul id="navi">
			<li id="profile" hrefs="profile.html">个人资料</li>
			<li id="reg" hrefs="reg.html">注册账号</li>
			<li id="apply" hrefs="apply.html">邀请好友</li>
			<li id="pwd" hrefs="pwd.html">密码维护</li>
			<li id="recommend" hrefs="recommend.html">推荐歌曲</li>
			<!--li id="bug" hrefs="bugReport.html">问题反馈</li-->
		</ul>
		<div id="tabbg"></div>
		<div id="tab">
			<iframe src="about:blank" width="824" height="500" id="ifmain" name="ifmain" frameborder=0 scrolling="auto" allowTransparency="true"></iframe>
		</div>
	</div>
	<div id="msgs" class="alertdiv"></div>
	<div id="footer">
		<span class="rights">&copy;Bus.Fm 版权所有 since 2011</span>
		<span class="links"><a href="about:blank" target="_blank">关于我们</a>|<a href="/blog/" target="_blank">官方博客</a></span>	
	</div>
</body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$("#navi li").click(function(){
		var sid=new Date().getMilliseconds();
		var u="?jsid="+sid+"&op="+$(this).attr("id");
		location.href=u;
	});
	gopage();
});
//确定iframe内容
function gopage(){
	var act=request(location.search,"op");
	$(".cur").removeClass("cur");
	var u=$("#"+act).addClass("cur").attr("hrefs");
	var type=request(location.search,"ty");
	var sid=new Date().getMilliseconds();
	u+="?sid="+sid+"&type="+type;
	$("#ifmain").attr({src:u});
}

//弹出提示信息
/*msg:文本内容
 *msgtype:消息类型 1:普通提示，2：警告、错误
*/
function popAlert(msg,msgtype){
	var mt=msgtype||1;
	var mdiv=$("#msgs");
	var stay=mt==1?3000:15000;
	mdiv.text(msg).stop(true,true).css("margin-left",function(){return -($(this).width()/2)}).animate({opacity:1},1000).delay(stay).animate({opacity:0},2000);
	if(mt==1) mdiv.removeClass("errdiv").addClass("alertdiv");
	else mdiv.removeClass("alertdiv").addClass("errdiv");
}
//取cookie值
function getCookie(name){
		var arr = document.cookie.match(new RegExp("(^|;\\s*)"+name+"=([^;]*)(;|$)"));
		if(arr != null) return unescape(decodeURI(arr[2])); return "";
} 
//取url参数
function request(url,name){
	var s=url.match(new RegExp("(^\\?|.*&)"+name+"=([^&]*)($|&.*)","i"));
	if(s!=null) return s[2];
	else return "";
}
function getpwd(){location.href="default.html?t="+new Date().getMilliseconds()+"&ty=3&op=pwd";}//找回密码
</script>
</html>