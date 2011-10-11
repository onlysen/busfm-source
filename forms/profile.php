<?PHP
	require_once("includes/header.php");
	require_once("includes/function.php");
	$uid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id']; 
	$display1=$display2="none";
	if(!$uid){ $title="输入您的登录信息";$display1="block";}
	else{ $title="您的个人资料"; $display2="block";}
?>
<div id="dtitle"><?PHP echo $title ?></div>
	<form id="form1" name="form1" action="/ajax/member?action=login_fm" method="post">
		<ul id="u-login" style="width:330px; margin-top:60px; display:<?PHP echo $display1; ?>">
			<li><span class="lititle">您的E-mail地址：</span><input type="text" value="" class="loginTxt" maxlength="40" id="txtemail" name="member_mail" /></li>
			<li><span class="lititle">您的注册密码：</span><input type="password" value="" class="loginTxt" maxlength="20" id="txtpwd" name="member_password" /></li>
			<li style="text-align:center;"><input type="checkbox" id="cbxCookie" name="cbxCookie" style="border:none;vertical-align:middle;" checked /><label for="cbxCookie">记住我，下次自动登录</label></li>
			<li style="height:30px; line-height:30px; text-align:center;"><input type="submit" value="登&nbsp;录" class="graybtn" /></li>
			<li class="regli"><a href="/my/pwd" style="float:right;">忘记密码？</a><a href="/my/signup">没有账号？</a></li>
		</ul>
	</form>
	<form id="form2" name="form1" action="/ajax/member?action=edit_member_fm" method="post">
	<p style="display:/*<?php echo $display2; ?>*/none;color:gray;">hello <?PHP echo $_COOKIE['member_nickname']; ?>, this function's under construction, please wait in paitent...</p>
		<ul id="u-profile" style="width:400px; margin-top:30px; display:<?PHP echo $display2; ?>;">
			<li><span class="lititle">昵称：</span><input type="text" des="请填入您想使用的昵称" name="nickname" id="nickname" maxlength="20" class="buglist" value="<?PHP echo $_COOKIE['member_nickname']; ?>" /><span class="tips"></span></li>
			<li><span class="lititle">邮件地址：</span><label name="email" id="email" class="pflist"><?PHP echo $_COOKIE['member_mail'] ?></label><span class="tips"></span></li>
			<li><span class="lititle">注册时间：</span><label des="" name="env" id="env" class="pflist"><?PHP echo date('Y-m-d H:i:s',$_COOKIE['join_time']); ?></label><span class="tips"></span></li>
			<li><span class="lititle">收听时长：</span><label des="" name="env" id="env" class="pflist"><?PHP echo time2Units(time()-$_COOKIE['join_time']); ?></label><span class="tips"></span></li>
			<!--<li><span class="lititle">收藏曲目：</span><label des="" name="env" id="env" class="pflist">362首</label><a href="#" onclick="">查看</a><span class="tips"></span></li>-->
			<li><span class="lititle">&nbsp;</span><input type="submit" id="btnok" value="更新个人资料" style="margin-right:20px;" /><input type="button" id="btnpwd" value="密码管理" onclick="location.href='/my/pwd/2';" />
		</ul>
	</form>
<?PHP
	require("includes/footer.php");
?>
<script type="text/javascript">
$(function(){
	$("#profile").addClass("cur");
	$("#reg").hide();
	//登录
	$("#form1").bind("submit",function(e){
		var emailtxt=$("#txtemail").val();
		if(emailtxt==""||!em_p.test(emailtxt)||$("#txtpwd").val()=="") top.popAlert("登录信息无效");
		else{
			var url=encodeURI($(this).attr("action")+"&t="+new Date().getMilliseconds());
			$.post(url,$(this).serialize(),function(d){
				var f=d.split('|');
				if(f[0]=="0") popAlert(f[1],2);
				else{
					//取出所需要的资料
					// getProfile();
					// $("#u-login").slideUp(1000);
					// $("#u-profile").slideDown(1000);
					
					//暂时直接跳转
					location.href="/my/profile";
				}
			});
		}
		e.preventDefault();
	});
	//个人资料
	var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var vali=true;
	var onvali=false;
	$(".buglist").focus(function(){var o=$(this); o.nextAll(".tips").text(o.attr("des")).removeClass("onerror onpass").addClass("onfocus").show();});
	//$("#email").blur(function(){var o=$(this);var t=o.nextAll(".tips"); var v=o.val();if(v==""||!em_p.test(v)){t.text("邮件地址不可为空，或必须拥有合法的格式").removeClass("onfocus onpass").addClass("onerror");vali=false;}else{t.text("输入正确").removeClass("onerror onfocus").addClass("onpass");}});
	$("#nickname").blur(function(){var o=$(this);var t=o.nextAll(".tips"); if(o.val()==""){t.text("昵称不能为空").removeClass("onfocus onpass").addClass("onerror");vali=false;}else{
		//todo:ajax查昵称是否被使用
		if(o.val()==top.getCookie("member_nickname")){t.text("您当前的昵称").removeClass("onerror onfocus").addClass("onpass");vali=false;return;}
		$.get(encodeURI("/ajax/member?action=validate_nickname&name="+o.val()+"&t="+new Date().getMilliseconds()),function(d){
		f=d.split('|');
		if(f[0]=="1"){t.text("可以使用").removeClass("onerror onfocus").addClass("onpass");}
		else{ t.text("昵称被占用").removeClass("onfocus onpass").addClass("onerror");vali=false;}
		});
	}
	});
	$("#form2").submit(function(e){
		e.preventDefault();
		$(".buglist").blur();
		goaction(this);
	});
	
	/*
	*@el:表单元素id
	*/
	function goaction(el){
		popAlert("提交中...");
		if(onvali){setTimeout(function(){goaction(el,ap);},333);return;}
		var fo=$(el);
		if(!vali) vali=true;
		else{
			if(fo.data("xmlhttp")!=undefined){popAlert("请不要重复提交",2);return;}
			var url=encodeURI(fo.attr("action")+"&t="+new Date().getMilliseconds());
			var x=$.post(url,fo.serialize(),function(d){$("#msgs").stop(false,true);fo.removeData("xmlhttp");var f=d.split('|'); if(f[0]=="1") popAlert(f[1]);else popAlert(f[1],2);});
			fo.data("xmlhttp",x);
		}
	}
});
function getProfile(){$("#nickname").val(top.getCookie("member_nickname"));$("#email").text(top.getCookie("member_mail"));$("#dtitle").text("您的个人资料");}
</script>
</html>