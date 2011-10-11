<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>巴士电台</title>
		<link rel="/shortcut icon" href="favicon.ico">
		<link rel="icon" href="/favicon.gif" type="image/gif">
		<link href="/css/form.css" rel="stylesheet" type="text/css" />
	</head>	
<body>
	<div id="header">
		<div id="logo">
			<span style="margin-left:135px; color:#088; font-family:arial;">beta</span>
		</div>
		<div id="info">
		<?PHP
			if(isset($_COOKIE['member_id'])) echo '<a href="/my/profile">'.$_COOKIE['member_nickname'].'的账号</a>|<a href="javascript:logout();">退出</a>'; 
			else echo '<a href="/my/profile">登录电台</a>';
		?></div>
	</div>
	<div id="cont">
		<ul id="navi">
			<?PHP if(isset($_COOKIE["member_id"])): ?>
			<li id="profile"><a href="/my/profile">个人资料</a></li>
			<?PHP else: ?>
			<li id="reg"><a href="/my/signup">注册账号</a></li>
			<?PHP endif; ?>
			<!--<li id="apply"><a href="/my/apply">邀请好友</a></li>-->
			<li id="pwd"><a href="/my/pwd">密码维护</a></li>
			<li id="recom"><a href="/my/recommend">推荐歌曲</a></li>
			<li id="about"><a href="/about">关于巴士</a></li>
			<li id="buslink"><a href="/links">巴士链接</a></li>
			<!--li id="bug" hrefs="/my/bugReport">问题反馈</li-->
		</ul>
		<div id="tabbg" class="round"></div>
		<div id="tab" class="round">