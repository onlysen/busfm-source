<?PHP
	require("includes/header.php");
?>
<div id="dtitle">请填写注册信息</div>
			<form id="form2" name="form2" action="/ajax/member?action=register_fm" method="post">
				<ul id="u-reg" style="width:400px">
					<li><span class="lititle">您的E-mail地址：</span>
					<input type="text" value="" class="loginTxt txtreg" maxlength="40" id="txtemail_r" name="member_mail" des="请输入E-mail地址" /><span class="tips"></span></li>
					<li><span class="lititle">您的注册密码：</span>
					<input type="password" value="" class="loginTxt txtreg" maxlength="20" id="txtpwd_r" name="member_password" des="6-20位字母和数字" /><span class="tips"></span></li>
					<li><span class="lititle">请再输一次：</span>
					<input type="password" value="" class="loginTxt txtreg" maxlength="20" id="txtpwd_r2" name="member_password_confirm" des="重输刚才的密码" /><span class="tips"></span></li>
					<li><span class="lititle">选择个昵称吧：</span>
					<input type="text" value="" class="loginTxt txtreg" maxlength="20" id="txtnickname" name="member_nickname" des="请输入要使用的昵称" /><span class="tips"></span></li>
					<!--<li><span class="lititle">邀请码：</span>
					<input type="text" value="" class="loginTxt txtreg" maxlength="20" id="txtapply" name="member_safecode" des="请输入邀请码" /><span class="tips"></span></li>-->
					<li style="text-align:center;"><input type="submit" value="确&nbsp;定" class="graybtn" /></li>
					<li class="regli"><center><a href="/my/profile">已经有账号？</a></center></li>
				</ul>
				<ul id="u-reg-suc" style="display:none;">
					<li>注册成功，请至注册填写的邮箱验证您申请的账号。</li>
				</ul>
			</form>
<?PHP
	require("includes/footer.php");
?>
<script type="text/javascript">
$(function(){
$("#reg").addClass("cur");
$(".txtreg").focus(function(){var o=$(this); o.nextAll(".tips").text(o.attr("des")).removeClass("onerror onpass").addClass("onfocus").show();});
var vali=true;
var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
var em_o=false;//标志ajax查询邮件地址是否被注册已结束
var nn_o=false;//昵称ajax查询
var an_o=true;//邀请码ajax查询
$("#txtemail_r").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o); return;} if(o.val()==""||!(em_p.test(o.val()))) ifvali(false,o,'邮件地址不正确'); else{ em_o=false; $.get(encodeURI("/ajax/member?action=validate_mail&mail="+o.val()+"&t="+new Date().getMilliseconds()),function(d){ em_o=true; var f=d.split('|'); if(f[0]=="0") ifvali(false,o,f[1]); else ifvali(true,o);});}});
$("#txtpwd_r").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o); return;} if(o.val()==""||!(/^[0-9a-zA-Z]{6,20}$/.test(o.val()))) ifvali(false,o,'密码6-20位数字和字母'); else ifvali(true,o);});
$("#txtpwd_r2").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o); return;} if(o.val()!=$("#txtpwd_r").val()) ifvali(false,o,'密码输入不一致'); else ifvali(true,o);});
$("#txtnickname").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o);return;} if(o.val()==""){ifvali(false,o,'请输入您的昵称');}else{ nn_o=false;$.get(encodeURI("/ajax/member?action=validate_nickname&name="+o.val()+"&t="+new Date().getMilliseconds()),function(d){nn_o=true;var f=d.split('|');if(f[0]=="0"){ifvali(false,o,f[1]);}else ifvali(true,o);});}});
// $("#txtapply").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o);return;} if(o.val()==""){ifvali(false,o,'请输入邀请码');}else{ an_o=false;$.get(encodeURI("/ajax/member?action=validate_safecode&safecode="+o.val()+"&t="+new Date().getMilliseconds()),function(d){an_o=true;var f=d.split('|');if(f[0]=="0"){ifvali(false,o,f[1]);}else ifvali(true,o);});}});
//注册
$("#form2").bind("submit",function(e){
	$(".txtreg").blur();
	reg();
	e.preventDefault();
});
function reg(){
	popAlert("提交中...");
	if(!em_o||!nn_o||!an_o) setTimeout(function(){reg();},333);//有验证还在ajax的交互中，不允许提交
	else{
		if(vali){
			var fo=$("#form2");
			if(fo.data("xmlhttp")!=undefined){popAlert("请求正在处理，请勿重复提交",2); return;}
			var url=encodeURI(fo.attr("action")+"&t="+new Date().getMilliseconds());
			var x=$.post(url,fo.serialize(),function(d){
				fo.removeData("xmlhttp");
				var r=d.split('|');
				if(r[0]=="0") top.popAlert(r[1],2);
				else{
					$("#msgs").hide();
					$("#u-reg").slideUp();
					$("#u-reg-suc").slideDown();
				}
			});
			fo.data("xmlhttp",x);
		}
		else vali=true;
	}
}
/*
*b是否验证通过，o，对应的文本框对象，t，错误提示
*/
function ifvali(b,o,t){
	var p=o.nextAll(".tips");
	t=t||"";
	if(b){//通过验证
		p.removeClass("onerror onfocus").addClass("onpass").text(t);
		o.data({"otxt":o.val()});
	}else{// 未通过验证
		vali=false;
		p.removeClass("onfocus onpass").addClass("onerror").text(t);
	}
}	
});
</script>
</html>