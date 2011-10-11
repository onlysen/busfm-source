<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>巴士电台</title>
		<link href="css/public.css?v=0.14" rel="stylesheet" type="text/css" />
		<style type="text/css">
			.resetpage{padding:20px;}
			.resetpage li{height:30px;}
			#txtmail2{border:none; overflow:visible; white-space:nowrap;}
			.buglist{width:200px;}
			.lititle{display:inline-block; width:90px;}
			.buglist, .txtreg{border:1px solid #ccc; height:20px; line-height:20px;}
			.tips{line-height:18px; margin-left:10px; padding:0 0 0 16px; font-weight:400;vertical-align:middle; background:url(img/sprint2.gif) no-repeat 50px 50px;}
			.onfocus{color:gray; background-position:0 -80px;}
			.onerror{color:red; background-position:0 -98px;}
			.onpass{color:green; background-position:1px -63px;}
			.onvali{color:gray; background:url(img/loading32.gif) no-repeat 0 1.5px;}
		</style>
	<!--[if IE 6]>
	<link href="css/ie6sucks.css?v=0.3" rel="stylesheet" type="text/css" />
	<![endif]-->
	</head>
<body>
	<!--960-->
	<!--header-->
	<div id="header" class="bgray">
		<div id="hd-container">
			<div class="login-bar">
				<ul id="p-context-menu">
					<li><a href="#cpanel" class="page-cpanel">个人中心</a></li>
					<li><a href="javascript:void(0);" onclick="logout();">退出</a></li>
				</ul>
			</div>
			<div class="channel-bar">
				<a id="page-navi-home" href="index.php">电台首页</a>
				<a href="#">重设密码</a>
			</div>
			<div class="uparr" style="right:35px;"></div>
		</div>
	</div>
	<!--header end-->
	<!--mainbody-->
	<div id="mainbody" class="resetpage">
	<?PHP
		$cont=empty($_GET['cont'])?'':trim(addslashes($_GET['cont']));
		if(!$cont) echo "<span class='tips onerror'>地址错误！</span>";
		else{
			try{
				$cont1 = base64_decode($cont);
				$cont2 = explode('=',$cont1);
				if((time()-$cont2[1])>3600){//一小时
					echo "<span class='tips onerror'>地址失效，请重新提交申请！</span>";
				}else{
	?>
		<form method="post" action="/ajax/member?action=forget" id="formrp" name="formrp">
			<ul id="reset"><!--请求被接受，重置密码-->
				<li class="infoli">为了完成密码重设步骤, 请在下方输入您要重新设置的密码并重新输入以确认，一旦修改成功，请以新密码重新尝试登录。</li>
				<li><span class="lititle">E-mail:</span><input type="text" name="member_mail" class="txtreg" id="txtmail2" readonly value="<?PHP echo $cont2[0];?>" /></li>
				<li><span class="lititle">设置密码:</span><input type="password" name="member_password" id="txtpwd1" value="" des="6-20位字母和数字" class="buglist txtreg" /><span class="tips"></span></li>
				<li><span class="lititle">确认密码:</span><input type="password" name="member_password2" id="txtpwd2" value="" des="请确认刚才输入的密码" class="buglist txtreg" /><span class="tips"></span></li>
				<li><span class="lititle">&nbsp;</span><input type="submit" name="btnreset" value="重置密码" class="roundbtn" /> </li>
			</ul>
		</form>
	<?php
				}
			}
			catch(Exception $e){
				echo "<span class='tips onerror'>地址错误！</span>";
			}
		}
	?>
	</div>
	<!--mainbody end-->
	<!--footer-->
	<div id="footer-index" class="footer bgray">
		<div id="udiary"></div>
		<div id="copyright">
		&copy; 2011 巴士电台 版权所有
		</div>
	</div>
	<!--footer end-->
	<div id="msgs" class="round10 box-shadow"></div>
</body>
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/bustool.min.js"></script>
<script type="text/javascript">
$(function(){
	var pwd_p=/^[0-9a-zA-Z]{6,20}$/;
	var vali=true;
	$(".buglist").focus(function(){var o=$(this); o.nextAll(".tips").text(o.attr("des")).removeClass("onerror onpass").addClass("onfocus").show();});
	$("#txtpwd1").blur(function(){var o=$(this); var t=o.nextAll(".tips"); var v=o.val();if(v==""||!pwd_p.test(v)){onerror(t,"不允许的密码格式");vali=false;}else onpass(t,"输入正确");});
	$("#txtpwd2").blur(function(){var o=$(this); var t=o.nextAll(".tips"); var v=o.val();if(v==""||!pwd_p.test(v)||(v!=$("#txtpwd1").val())){var a="不允许的密码格式";if(v!=$("#txtpwd1").val()) a="密码输入不一致";onerror(t,a);vali=false;}else onpass(t,"输入正确")});
	
	$("#formrp").submit(function(e){
		$(".txtreg",this).blur();
		if($(".onvali",this).length>0) return false;
		goaction(this);
		e.preventDefault();
	});
	/*
	*@el:表单元素id
	*@ap:表单里的ul元素id
	*/
	function goaction(el){
		if(!vali) vali=true;
		else{
			popAlert("提交中...");
			if($(".onvalie",el).length>0){setTimeout(function(){goaction(el,ap);},333);return;}
			var fo=$(el);
			if(fo.data("xmlhttp")!=undefined){popAlert("请不要重复提交",2);return;}
			var url=encodeURI(fo.attr("action")+"&t="+new Date().getMilliseconds());
			var x=$.post(url,fo.serialize(),function(d){$("#msgs").stop(false,true);fo.removeData("xmlhttp");var f=d.split('|'); if(f[0]=="1"){alert('密码设置成功，点击确定重新登录');location.href="index.php";}else popAlert(f[1],2);});
			fo.data("xmlhttp",x);
		}
	}
	
});
</script>
</html>