<?PHP 
if(!isset($_COOKIE["member_id"])) exit("请先登录");
$userid=isset($_SESSION['member_id'])?$_SESSION['member_id']:$_COOKIE['member_id']; 
require_once("../admin/includes/function.php");
include '../admin/basevar.php';
//打开session
session_start();
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		#cpanel table{width:100%;}
		#cpanel td{vertical-align:top;}
		#cpanel ul{margin-top:20px;}
		#cpanel li{height:24px; line-height:24px; padding:5px 10px;}
		.lititle{display:inline-block; width:70px;}
		.buglist, .txtreg{border:1px solid #ccc; height:20px; line-height:20px;}
		.tips{line-height:18px; margin-left:10px; padding:0 0 0 16px; font-weight:400;vertical-align:middle; background:url(img/sprint2.gif) no-repeat 50px 50px;}
		.onfocus{color:gray; background-position:0 -80px;}
		.onerror{color:red; background-position:0 -98px;}
		.onpass{color:green; background-position:1px -63px;}
		.onvali{color:gray; background:url(img/loading32.gif) no-repeat 0 1.5px;}
		#rtd{border-left:1px solid #dfdfdf; padding-left:30px;}
	</style>
</head>
<body>
	<div id="cpanel" class="page-body pagebg2">
		<table>
			<tr>
				<td width="390">
<form id="form-profile" name="form-profile" action="/ajax/member?action=edit_member_fm" method="post">
	<h3>个人资料</h3>
	<ul id="u-profile">
		<li><span class="lititle">昵称：</span><input type="text" des="请填入您想使用的昵称" name="nickname" id="nickname" maxlength="20" class="buglist" value="<?PHP echo $_COOKIE['member_nickname']; ?>" /><span class="tips"></span></li>
		<li><span class="lititle">邮件地址：</span><label name="email" id="email" class="pflist"><?PHP echo $_COOKIE['member_mail'] ?></label><span class="tips"></span></li>
		<li><span class="lititle">注册时间：</span><label des="" name="env" id="env" class="pflist"><?PHP echo date('Y-m-d H:i:s',$_COOKIE['join_time']); ?></label><span class="tips"></span></li>
		<li><span class="lititle">收听时长：</span><label des="" name="env" id="env" class="pflist"><?PHP echo time2Units(time()-$_COOKIE['join_time']); ?></label><span class="tips"></span></li>
		<li><span class="lititle">&nbsp;</span><input type="submit" id="btnok" class="roundbtn" value="更新个人资料" style="margin-right:20px;" /></li>
	</ul>
	<ul id="sns">
		<?PHP
			if(isset($_SESSION[$configs['sina']['token']])&&isset($_SESSION[$configs['sina']['token']]['oauth_token'])&&isset($_SESSION[$configs['sina']['token']]['oauth_token_secret'])){
				echo '<li id="auth_sina">新浪微博：<a href="javascript:void(0);" onclick="unauth(2);">解除授权</a></li>';
			}else{
				require_once('../admin/includes/config.php');
				require_once('../admin/includes/class_db.php');
				$db=new db($db_host,$db_user,$db_password,$db_name);
				$ua=$db->getone("select userid from ".$db_prefix."oauth_user where userid=$userid and siteid='sina' ");
				if($ua) echo '<li id="auth_sina">新浪微博：<a href="javascript:void(0);" onclick="unauth(2);">解除授权</a></li>';
				else echo '<li>新浪微博：<a href="oauth/sina/process.php?go_oauth">授权</a></li>';
			}
			if(isset($_SESSION[$configs['qq']['access_token']]) && isset($_SESSION[$configs['qq']['oauth_token_secret']])){
				echo '<li id="auth_qq">腾讯微博：<a href="javascript:void(0);" onclick="unauth(1);">解除授权</a></li>';
			}else{
				require_once('../admin/includes/config.php');
				require_once('../admin/includes/class_db.php');
				$db=new db($db_host,$db_user,$db_password,$db_name);
				$ua2=$db->getone("select userid from ".$db_prefix."oauth_user where userid=$userid and siteid='qq' ");
				if($ua2) echo '<li id="auth_qq">腾讯微博：<a href="javascript:void(0);" onclick="unauth(1);">解除授权</a></li>';
				else echo '<li>腾讯微博：<a href="oauth/QQ/process.php?go_oauth">授权</a></li>';
			}
		?>
	</ul>
</form>
				</td>
				<td id="rtd">
<form method="post" action="/ajax/member?action=forget" id="form-pwd" name="form-pwd">
	<h3>修改密码</h3>
	<ul id="reset">
		<li><span class="lititle">现用密码:</span><input type="password" name="member_password" id="txtpwd3" value="" des="您目前在使用的密码" class="txtreg" /><span class="tips"></span></li>
		<li><span class="lititle">设置密码:</span><input type="password" name="member_password1" id="txtpwd4" value="" des="6-20位字母和数字" class="txtreg" /><span class="tips"></span></li>
		<li><span class="lititle">确认密码:</span><input type="password" name="member_password2" id="txtpwd5" value="" des="请确认刚才输入的密码" class="txtreg" /><span class="tips"></span></li>
		<li><span class="lititle">&nbsp;</span><input type="submit" name="btnreset" class="roundbtn" value="重置密码" /> </li>
	</ul>
</form>
				</td>
			</tr>
		</table>
<script type="text/javascript">
$(function(){
	//个人资料
	var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var pro_vali=true;
	$(".buglist").focus(function(){var o=$(this); o.nextAll(".tips").text(o.attr("des")).removeClass("onerror onpass").addClass("onfocus").show();});
	$("#nickname").blur(function(){var o=$(this);var t=o.nextAll(".tips");
		if(o.val()==""){onerror(t,"昵称不能为空");vali=false;}
		else{
			if(o.val()==getCookie("member_nickname")){onerror(t,"您当前的昵称");vali=false;return;}
			if(t.is("onvali")) return;
			$.get(encodeURI("/ajax/member?action=validate_nickname&name="+o.val()+"&t="+new Date().getMilliseconds()),function(d){
				f=d.split('|');
				if(f[0]=="1"){onpass(t,"可以使用");}
				else{ onerror(t,"昵称被占用");vali=false;}
				t.removeClass("onvali");
			});
			t.addClass("onvali").text("验证中...");
		}
	});
	$("#form-profile").submit(function(e){
		e.preventDefault();
		if($(".onvali",this).length>0) return false;
		$(".buglist").blur();
		goaction(this);
	});
	/*
	*提交修改昵称的表单
	*@el:表单元素id
	*/
	function goaction(el){
		if(!pro_vali) vali=true;
		else{
			popAlert("提交中...");
			if($(".onvali",el).length>0){setTimeout(function(){goaction(el);},333);return;}
			var fo=$(el);
			if(fo.data("xmlhttp")!=undefined){popAlert("请不要重复提交",2);return;}
			var url=encodeURI(fo.attr("action")+"&t="+new Date().getMilliseconds());
			var x=$.post(url,fo.serialize(),function(d){$("#msgs").stop(false,true);fo.removeData("xmlhttp");var f=d.split('|'); if(f[0]=="1"){$("#login-name").text($("#nickname").val());popAlert(f[1]);}else popAlert(f[1],2);});
			fo.data("xmlhttp",x);
		}
	}

	//修改密码的逻辑
	var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var pwd_p=/^[0-9a-zA-Z]{6,20}$/;
	var p_vali=true;
	$(".txtreg").focus(function(){var o=$(this); o.nextAll(".tips").text(o.attr("des")).removeClass("onerror onpass").addClass("onfocus").show();});	
	$("#txtpwd3").blur(function(){var o=$(this);var t=o.nextAll(".tips"); var v=o.val();if(v==o.data("va")){onpass(t,"输入正确");return;}if(v==""){onerror(t,"不允许的密码格式");p_vali=false;}else{if(t.is("onvali"))return;t.addClass("onvalie");$.get(encodeURI("/ajax/member?action=check_member_pwd&pwd="+v+"&t="+new Date().getMilliseconds()),function(d){var f=d.split('|');if(f[0]=="1"){onpass(t,"输入正确");o.data("va",v);}else{onerror(t,f[1]);vali_p=false;}t.removeClass("onvali");});}});
	$("#txtpwd4").blur(function(){var o=$(this); var t=o.nextAll(".tips"); var v=o.val();if(v==""||!pwd_p.test(v)){onerror(t,"不允许的密码格式");vali_p=false;}else onpass(t,"输入正确");});
	$("#txtpwd5").blur(function(){var o=$(this); var t=o.nextAll(".tips"); var v=o.val();if(v==""||!pwd_p.test(v)||(v!=$("#txtpwd4").val())){var a="不允许的密码格式";if(v!=$("#txtpwd4").val()) a="密码输入不一致";onerror(t,a);vali_p=false;}else onpass(t,"输入正确");});
	
	$("#form-pwd").submit(function(e){
		$(".txtreg",this).blur();
		if($(".onvali",this).length>0) return false;
		goaction2(this);
		e.preventDefault();
	});
	/*
	*提交修改密码表单
	*@el:表单元素id
	*/
	function goaction2(el){
		if(!p_vali) vali=true;
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
//解除微博授权
function unauth(channel){
	if(channel==1){
		$.get('oauth/QQ/process.php?exit',function(d){if(d==1){
			$("#auth_qq").remove();
			$("#sns").append('<li>腾讯微博：<a href="oauth/QQ/process.php?go_oauth">授权</a></li>');
		}else popAlert(d,2);});
	}else if(channel==2){
		$.get('oauth/sina/process.php?exit',function(d){if(d==1){
			$("#auth_sina").remove();
			$("#sns").prepend('<li>新浪微博：<a href="oauth/sina/process.php?go_oauth">授权</a></li>');
		}else popAlert(d,2);});
	}
}

</script>
	</div>
</body>
</html>