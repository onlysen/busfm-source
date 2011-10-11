</div>
	</div>
	<div id="msgs" class="alertdiv"></div>	
	<?PHP include("copyright.php"); ?>
</body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	//全局ajax错误捕获
	$("body").ajaxError(function(e,x,o){top.popAlert("服务器错误",2);});
});
//弹出提示信息
/*msg:文本内容
 *msgtype:消息类型 1:普通提示，2：警告、错误
*/
function popAlert(msg,msgtype){
	var mt=msgtype||1;
	var mdiv=$("#msgs");
	var stay=mt==1?3000:15000;
	mdiv.text(msg).stop(true,true).css("margin-left",function(){return -($(this).width()/2)}).animate({opacity:1},1000).delay(stay).animate({opacity:0},2000);
	if(mt==1) mdiv.removeClass("errdiv").addClass("alertdiv");
	else mdiv.removeClass("alertdiv").addClass("errdiv");
}
//取cookie值
function getCookie(name){
		var arr = document.cookie.match(new RegExp("(^|;\\s*)"+name+"=([^;]*)(;|$)"));
		if(arr != null) return unescape(decodeURI(arr[2])); return "";
} 
function logout(){
	$.get("/admin/member.php?action=logout&t="+new Date().getMilliseconds(),function(){
		location=location;
	});
}
</script>