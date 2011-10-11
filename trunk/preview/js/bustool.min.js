/*弹出提示信息
 *msg:文本内容
 *msgtype:消息类型 1:普通提示，2：警告、错误
*/
function popAlert(msg,msgtype){
	var mt=msgtype||1;
	var mdiv=$("#msgs");
	var stay=mt==1?3000:15000;
	mdiv.html(msg).stop(true,true).css("margin-left",function(){return -($(this).width()/2);}).animate({opacity:0.8},1000).delay(stay).animate({opacity:0},2000);
	if(mt==1) mdiv.removeClass("msg_err");
	else mdiv.addClass("msg_err");
}
//取cookie值
function getCookie(name){
		var arr = document.cookie.match(new RegExp("(^|;\\s*)"+name+"=([^;]*)(;|$)"));
		if(arr != null) return unescape(decodeURI(arr[2])); return "";
} 
//设置cookie
function setCookie(c_name,value,expiredays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+	((expiredays==null) ? "" : ";expires="+exdate.toUTCString());
}
	
//取小数点后e位
function round(v,e){
	var t=1;
	//for(;e>0;t*=10,e--);
	while(e>0){t*=10,e--;}
	// for(;e<0;t/=10,e++);
	while(e<0){t/=10,e++;}
	return Math.round(v*t)/t;
} 
//数组去重
function arrayUnique(arr){
    var length = arr.length;
    while(--length){
		//如果在前面已经出现，则将该位置的元素删除
        if(arr.lastIndexOf(arr[length],length-1) > -1) {
            arr.splice(length,1);
        }
    }
    return arr;    
}
function onerror(el,msg){$(el).text(msg).removeClass("onfocus onpass").addClass("onerror");}
function onpass(el,msg){$(el).text(msg).removeClass("onfocus onerror").addClass("onpass");}