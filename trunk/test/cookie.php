<?php	
	if(!isset($_COOKIE["username"])) setcookie("username","myname");
	if(!isset($_COOKIE["uid"])) setcookie("uid","bbb");
	if(!isset($_COOKIE["pwd"])) setcookie("pwd","aaa");
	print_r($_COOKIE);
?>
<script type="text/javascript">
	//<![CDATA[
	//var c=document.cookie;
	//document.write("<br/>"+c);
	//var s=c.indexOf("pwd");
	//document.write("<br/>"+s);
	//var e=c.indexOf(";",s);
	//document.write("<br/>"+e);
	//document.write("<br/>"+c.substr(s,e));
	document.write("<br/>"+getCookie("pwd"));

// ���Ƕ���һ��������������ȡ�ض���cookieֵ��
function getCookie(cookie_name){
	var allcookies = document.cookie;
	var cookie_pos = allcookies.indexOf(cookie_name);

	// ����ҵ����������ʹ���cookie���ڣ�
	// ��֮����˵�������ڡ�
	if (cookie_pos != -1){
		// ��cookie_pos����ֵ�Ŀ�ʼ��ֻҪ��ֵ��1���ɡ�
		cookie_pos += cookie_name.length + 1;
		var cookie_end = allcookies.indexOf(";", cookie_pos);

		if (cookie_end == -1){
			cookie_end = allcookies.length;
		}

		var value = unescape(allcookies.substring(cookie_pos, cookie_end));
	}	
	return value;
}

	//]]>
</script>