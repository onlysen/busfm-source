<?PHP
	require("includes/header.php");
?>
	<style type="text/css">
		#code{position:absolute; top:100px; right:50px; background:#ffa; padding:10px; display:none; width:220px; height:200px; overflow-y:auto;}
		#code div{color:#388; padding:2px 5px; font-family:arial; font-size:30px;}
		#code div:hover{color:red;}
		#form1 .invalid{color:#ccc!important; text-decoration:line-through;}
		#ctrleft{font-family:arial; font-size:20px; padding:0 5px; color:red;}
	</style>
	<form id="form1" name="form1" action="bug.php" method="post">
		<div id="dtitle">邀请朋友</div>
		<ul>
			<li>电台巴士正处于封闭内测阶段，但我们还是开放了少量邀请，</li>
			<li>您可以邀请您的朋友来试用我们的服务</li>
			<li>试听我们精心为您挑选的每一首歌曲</li>
			<li>您还可以邀请<span id="ctrleft">0</span>位朋友</li>
			<!--li>E-mail：<input type="text" des="您朋友的邮件地址" name="email" id="email" maxlength="100" class="buglist" style="width:250px;"></input><span class="tips"></span></li>
			<li><span class="lititle">&nbsp;</span><input type="submit" id="btnok" value="发送邀请" /-->
			<li>您可以通过QQ、MSN、微博等，直接将邀请码发送给他/她^^</li>
		</ul>
		<div id="code"></div>
	</form>
<?PHP
	require("includes/footer.php");
?>
<script type="text/javascript">
$(function(){
	$("#apply").addClass("cur");
	$("#reg").hide();
	var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var vali=true;
	$(".buglist").focus(function(){var o=$(this); o.nextAll(".tips").text(o.attr("des")).removeClass("onerror onpass").addClass("onfocus").show();});
	$("#email").blur(function(){var o=$(this);var t=o.nextAll(".tips"); var v=o.val();if(v==""||!em_p.test(v)){t.text("邮件地址不正确").removeClass("onfocus onpass").addClass("onerror");vali=false;}else{t.text("输入正确").removeClass("onerror onfocus").addClass("onpass");}});
	$("#form1").submit(function(e){
		$(".buglist").blur();
		if(!vali) vali=true;
		e.preventDefault();
	});
	//取邀请码
	$.get("/ajax/safecode?action=add",function(d){f=d.split('|');if(f[0]=="1"){
		$.get("/ajax/safecode?action=list",function(r){
			s=r.split('|');if(s[0]=="0") return;
			var j=eval(r);
			var o=$("#code").show();
			$(j).each(function(){
				//每个邀请码连一次数据库，检查是否被使用过（添加invalid类）
				$("<div/>",{text:$(this)[1],"class":$(this)[2]=="1"?"":"invalid"}).appendTo(o);
			});
			$("#ctrleft").text($("div",o).not(".invalid").length);
		});
		var o=$(".code");
	}})
});
</script>
</html>