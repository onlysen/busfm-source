<?PHP
	require("includes/header.php");
	$type=empty($_GET['type'])?'3':$_GET['type'];
	if($type=="1") $title='重置密码';
	else if($type=="2") $title='修改密码';
	else $title='找回密码';
?>
<div id="dtitle"><?PHP echo $title; ?></div>
<?PHP
	if($type=='3'){
?>
<form method="post" action="/ajax/member?action=forget_ok_fm" id="form1" name="form1">
	<ul id="retrive"><!--发密码重置请求-->
		<li class="infoli">If you are having difficulty logging in, you can reset your password by typing the email address that you log in with. You will receive an email with instructions on how to reset your password. </li>
		<li><span class="lititle">您的注册邮箱:</span><input type="text" name="member_mail" id="txtmail1" value="" des="您注册用的电子邮箱地址" class="buglist txtreg" /><span class="tips"></span></li>
		<li><span class="lititle">&nbsp;</span><input type="submit" name="btnretrive" value="找回密码" id="btnretrive" /> </li>
	</ul>
</form>
<?PHP
	}else if($type=='1'){
		$cont=empty($_GET['cont'])?'':trim(addslashes($_GET['cont']));
		if(!$cont) echo "<li><span class='tips onerror'>地址错误！</span></li>";
		else{
		$cont1 = base64_decode($cont);
		$cont2 = explode('=',$cont1);
		if((time()-$cont2[1])>3600){//一小时
			exit("地址失效，请重新提交申请！");
		}
?>
<form method="post" action="/ajax/member?action=forget" id="form2" name="form2">
	<ul id="reset"><!--请求被接受，重置密码-->
		<li class="infoli">To complete your password reset, type your new password below and confirm and submit. Once accepted, you will be immediately logged into your account.</li>
		<li><span class="lititle">E-mail:</span><input type="text" name="member_mail" class="txtreg" id="txtmail2" readonly value="<?PHP echo $cont2[0];?>" /></li>
		<li><span class="lititle">设置密码:</span><input type="password" name="member_password" id="txtpwd1" value="" des="6-20位字母和数字" class="buglist txtreg" /><span class="tips"></span></li>
		<li><span class="lititle">确认密码:</span><input type="password" name="member_password2" id="txtpwd2" value="" des="请确认刚才输入的密码" class="buglist txtreg" /><span class="tips"></span></li>
		<li><span class="lititle">&nbsp;</span><input type="submit" name="btnreset" value="重置密码" /> </li>
	</ul>
</form>
<?PHP
		}
	}else{
?>
<form method="post" action="/ajax/member?action=edit_pwd_fm" id="form3" name="form3">
	<ul id="change">
		<li><span class="lititle">现用密码:</span><input type="password" name="member_password" id="txtpwd3" value="" des="您目前在使用的密码" class="buglist txtreg" /><span class="tips"></span></li>
		<li><span class="lititle">设置密码:</span><input type="password" name="member_password1" id="txtpwd4" value="" des="6-20位字母和数字" class="buglist txtreg" /><span class="tips"></span></li>
		<li><span class="lititle">确认密码:</span><input type="password" name="member_password2" id="txtpwd5" value="" des="请确认刚才输入的密码" class="buglist txtreg" /><span class="tips"></span></li>
		<li><span class="lititle">&nbsp;</span><input type="submit" name="btnchange" value="修改密码" />
		<a href="/my/pwd">忘记密码</a> </li>
	</ul>
</form>
<?PHP
	}
	require("includes/footer.php");
?>
<script type="text/javascript">
$(function(){
	$("#pwd").addClass("cur");
	<?PHP if($_GET['type']=='2') echo '$("#reg").hide();'; ?>
	var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var pwd_p=/^[0-9a-zA-Z]{6,20}$/;
	var vali=true;
	var onvali=false;//email
	var onvalip=false;//pwd
	$(".buglist").focus(function(){var o=$(this); o.nextAll(".tips").text(o.attr("des")).removeClass("onerror onpass").addClass("onfocus").show();});
	$("#txtmail1").blur(function(){var o=$(this);var t=o.nextAll(".tips"); var v=o.val();if(onvali)return;if(v==o.data("va")){t.removeClass().addClass("tips onpass").text("输入正确");return;}if(v==""||!em_p.test(v)){t.text("邮件地址为空，或格式不正确").removeClass().addClass("tips onerror");vali=false;}else{onvali=true;t.text("验证中...").removeClass().addClass("tips onvali");$.get(encodeURI("/ajax/member?action=validate_mail&mail="+v+"&t="+new Date().getMilliseconds()),function(d){if(d.split('|')[0]=="0"){t.text("输入正确").removeClass().addClass("tips onpass");o.data("va",v);}else{t.text("邮件地址不存在").removeClass().addClass("tips onerror");vali=false;}onvali=false;});}});
	$("#txtpwd1").blur(function(){var o=$(this); var t=o.nextAll(".tips"); var v=o.val();if(v==""||!pwd_p.test(v)){t.text("不允许的密码格式").removeClass("onfocus onpass").addClass("onerror");vali=false;}else{t.text("输入正确").removeClass("onfocus onerror").addClass("onpass");}});
	$("#txtpwd2").blur(function(){var o=$(this); var t=o.nextAll(".tips"); var v=o.val();if(v==""||!pwd_p.test(v)||(v!=$("#txtpwd1").val())){var a="不允许的密码格式";if(v!=$("#txtpwd1").val()) a="密码输入不一致";t.text(a).removeClass("onfocus onpass").addClass("onerror");vali=false;}else{t.text("输入正确").removeClass("onfocus onerror").addClass("onpass");}});
	$("#txtpwd3").blur(function(){var o=$(this);var t=o.nextAll(".tips"); var v=o.val();if(onvalip)return;if(v==o.data("va")){t.removeClass().addClass("tips onpass").text("输入正确");return;}if(v==""){t.text("不允许的密码格式").removeClass().addClass("tips onerror");vali=false;}else{onvalip=true;t.text("验证中...").removeClass().addClass("tips onvali");$.get(encodeURI("/ajax/member?action=check_member_pwd&pwd="+v+"&t="+new Date().getMilliseconds()),function(d){var f=d.split('|');if(f[0]=="1"){t.text("输入正确").removeClass().addClass("tips onpass");o.data("va",v);}else{t.text(f[1]).removeClass().addClass("tips onerror");vali=false;}onvalip=false;});}});
	$("#txtpwd4").blur(function(){var o=$(this); var t=o.nextAll(".tips"); var v=o.val();if(v==""||!pwd_p.test(v)){t.text("不允许的密码格式").removeClass("onfocus onpass").addClass("onerror");vali=false;}else{t.text("输入正确").removeClass("onfocus onerror").addClass("onpass");}});
	$("#txtpwd5").blur(function(){var o=$(this); var t=o.nextAll(".tips"); var v=o.val();if(v==""||!pwd_p.test(v)||(v!=$("#txtpwd4").val())){var a="不允许的密码格式";if(v!=$("#txtpwd4").val()) a="密码输入不一致";t.text(a).removeClass("onfocus onpass").addClass("onerror");vali=false;}else{t.text("输入正确").removeClass("onfocus onerror").addClass("onpass");}});
	
	$("#form1").submit(function(e){
		$(".buglist",this).blur();
		goaction(this,"retrive");
		e.preventDefault();
	});
	$("#form2").submit(function(e){
		$(".buglist",this).blur();
		goaction(this,"reset");
		e.preventDefault();
	});
	$("#form3").submit(function(e){
		$(".buglist",this).blur();
		goaction(this,"change");
		e.preventDefault();
	});
	/*
	*@el:表单元素id
	*@ap:表单里的ul元素id
	*/
	function goaction(el,ap){
		popAlert("提交中...");
		if(onvali){setTimeout(function(){goaction(el,ap);},333);return;}
		var fo=$(el);
		if(!vali) vali=true;
		else{
			if(fo.data("xmlhttp")!=undefined){popAlert("请不要重复提交",2);return;}
			var url=encodeURI(fo.attr("action")+"&t="+new Date().getMilliseconds());
			var x=$.post(url,fo.serialize(),function(d){$("#msgs").stop(false,true);fo.removeData("xmlhttp");var f=d.split('|'); if(f[0]=="1") sucMsg(ap,f[1]);else popAlert(f[1],2);});
			fo.data("xmlhttp",x);
		}
	}
	
});
function sucMsg(id,msg,status){
	var sta=status||true;
	if(sta) status="onpass";
	else status="onerror";
	$("#"+id).empty().append("<li><span class='tips "+status+"'>"+msg+"<span></li>");
}
</script>
</html>