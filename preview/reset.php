<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title></title>
		<style type="text/css">
			#retrive{padding:20px;}
			#retrive li{ height:30px;}
			.lititle{display:inline-block; width:90px;}
			.buglist, .txtreg{border:1px solid #ccc; height:20px; line-height:20px;}
			.tips{line-height:18px; margin-left:10px; padding:0 0 0 16px; font-weight:400;vertical-align:middle; background:url(img/sprint2.gif) no-repeat 50px 50px;}
			.onfocus{color:gray; background-position:0 -80px;}
			.onerror{color:red; background-position:0 -98px;}
			.onpass{color:green; background-position:1px -63px;}
			.onvali{color:gray; background:url(img/loading32.gif) no-repeat 0 1.5px;}
		</style>
	</head>
<body>
	<form method="post" action="/ajax/member?action=forget_ok_fm" id="formresetpwd" name="formresetpwd">
	<ul id="retrive"><!--发密码重置请求-->
		<li class="infoli">如果您登录遇到困难，您可以在此键入登录用的邮箱地址，稍候您会收入到一封如何重置密码的邮件，请照提示操作。</li>
		<li><span class="lititle">您的注册邮箱:</span><input type="text" name="member_mail" id="txtmail" value="" des="您注册用的电子邮箱地址" class="buglist txtreg" style="width:250px;" /><span class="tips"></span></li>
		<li><span class="lititle">&nbsp;</span><input type="submit" name="btnretrive" value="找回密码" id="btnretrive" class="roundbtn" /> </li>
	</ul>
</form>
</body>
<script type="text/javascript">
$(function(){
	var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var vali=true;
	$(".buglist").focus(function(){var o=$(this); o.nextAll(".tips").text(o.attr("des")).removeClass("onerror onpass").addClass("onfocus").show();});
	$("#txtmail").blur(function(){var o=$(this);var t=o.nextAll(".tips"); var v=o.val();if(v==o.data("va")){onpass(t,"输入正确");return;}if(v==""||!em_p.test(v)){onerror(t,"邮件地址为空，或格式不正确");vali=false;}else{if(t.is("onvali"))return;t.addClass("onvali");$.get(encodeURI("/ajax/member?action=validate_mail&mail="+v+"&t="+new Date().getMilliseconds()),function(d){if(d.split('|')[0]=="0"){onpass(t,"输入正确");o.data("va",v);}else{onerror(t,"邮件地址不存在");vali=false;}t.removeClass("onvali");});}});
	
	$("#formresetpwd").submit(function(e){
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
			var x=$.post(url,fo.serialize(),function(d){$("#msgs").stop(false,true);fo.removeData("xmlhttp");var f=d.split('|'); if(f[0]=="1") popAlert(f[1]);else popAlert(f[1],2);});
			fo.data("xmlhttp",x);
		}
	}
	
});
</script>
</html>