<?PHP
	require("includes/header.php");
?>
	<form id="form1" name="form1" action="/my/bug" method="post">
		<div id="dtitle">推荐歌曲信息</div>
		<div>
			<p>&nbsp;</p><p style="color:gray;">hello <?PHP echo $_COOKIE['member_nickname']; ?>, this function's under construction, please wait in paitent...<p>
		</div>
		<ul style="display:none;">
			<li><span class="lititle">歌曲名称：</span><input type="text" des="请填入您的电子邮箱地址，方便我们更好地解决问题。" name="email" id="email" maxlength="100" class="buglist"></input><span class="tips"></span></li>
			<li><span class="lititle">环境描述：</span><input type="text" des="如果可能，请简单描述您电脑所用的操作系统和浏览页面的浏览器，如windows xp professional ie 6.0" name="env" id="env" maxlength="200" class="buglist"></input><span class="tips"></span></li>
			<li style="height:auto;"><span class="lititle" style="vertical-align:top;">详细描述：</span><textarea des="请详细描述错误发生时的一些情况" name="describ" id="describ" maxlength="1000" rows="10" cols="66" class="buglist"></textarea><span class="tips"></span></li>
			<li><span class="lititle">&nbsp;</span><input type="submit" id="btnok" value="提交" />
		</ul>
	</form>
<?PHP
	require("includes/footer.php");
?>
<script type="text/javascript">
$(function(){
	$("#recom").addClass("cur");
	// $("#reg").hide();
	var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var vali=true;
	$("#ddlFunc").change(function(){if($(this).val()=="-1")$("#opration").show();else $("#opration").hide();})
	.focus(function(){$("#opration").triggerHandler('focus');})
	.blur(function(){if($(this).val()!="-1"){$(this).nextAll(".tips").text("输入正确").removeClass("onfocus onfocus").addClass("onpass");}else{$("#opration").focus();}});
	$(".buglist").focus(function(){var o=$(this); o.nextAll(".tips").text(o.attr("des")).removeClass("onerror onpass").addClass("onfocus").show();});
	$("#email").blur(function(){var o=$(this);var t=o.nextAll(".tips"); var v=o.val();if(v==""||!em_p.test(v)){t.text("邮件地址不可为空，或必须拥有合法的格式").removeClass("onfocus onpass").addClass("onerror");vali=false;}else{t.text("输入正确").removeClass("onerror onfocus").addClass("onpass");}});
	$("#env").blur(function(){$(this).nextAll(".tips").text("输入正确").removeClass("onfocus").addClass("onpass");});
	$("#opration").blur(function(){var t=$(this).nextAll(".tips");if($("#ddlFunc").val()=="-1"&&$(this).val()==""){t.text("请描述您进行的操作").removeClass("onpass onfocus").addClass("onerror");vali=false;}else{t.text("输入正确").removeClass("onerror onfocus").addClass("onpass");}});
	$("#describ").blur(function(){var o=$(this);var t=o.nextAll(".tips"); if(o.val()==""){t.text("问题描述不可为空").removeClass("onfocus onpass").addClass("onerror");vali=false;}else{t.text("输入正确").removeClass("onerror onfocus").addClass("onpass");}});
	$("#form1").submit(function(e){
		$(".buglist").blur();
		if(!vali){
			e.preventDefault();
		}
		vali=true;
	});
});
</script>
</html>