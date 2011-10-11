<?PHP
	require("includes/header.php");
?>
<style type="text/css">
.about{margin:15px; color:#666; line-height:20px;}
#sns a.snsicon{ background:url(/image/sns.gif) 0 0 no-repeat; display:inline-block; width:16px; height:16px; margin-right:1px; opacity:0.8; filter:alpha(opacity=80);}
#sns a.snsicon:hover{ opacity:1; filter:alpha(opacity=100);}
</style>
<div id="dtitle">巴士链接</div>
<div class="about">
	<p id="sns">巴士在SNS：
		<a href="#" name="xl" style="" class="snsicon" title="新浪"></a>
		<a href="#" name="tx" style="background-position: 0pt -1072px;" class="snsicon" title="腾讯"></a>
		<a href="#" name="db" style="background-position: 0pt -112px;" class="snsicon" title="豆瓣"></a>
		<a href="#" name="ff" style="background-position: 0pt -272px;" class="snsicon" title="饭否"></a>
		<a href="#" name="rr" style="background-position: 0pt -32px;" class="snsicon" title="人人"></a>
		<a href="#" name="bz" style="background-position: 0pt -912px;" class="snsicon" title="Google Buzz"></a>
		<a href="#" name="tt" style="background-position: 0pt -624px;" class="snsicon" title="twitter"></a>
		<a href="#" name="fb" style="background-position: 0pt -592px;" class="snsicon" title="Facebook"></a>
	</p>
	<p>巴士应用：
		
	</p>
	<p>友情链接：</p>
</div>
<?PHP
	require("includes/footer.php");
?>
<script type="text/javascript">
$(function(){
$("#buslink").addClass("cur");
});
</script>
</html>