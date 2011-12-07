/*弹出提示信息
 *msg:文本内容
 *msgtype:消息类型 1:普通提示，2：警告、错误
*/
function popAlert(msg,msgtype){
	var mt=msgtype||1;
	var mdiv=$("#msgs");
	var stay=mt==1?3000:15000;
	mdiv.html(msg).stop(true,true).show().css("margin-left",function(){return -($(this).width()/2);}).animate({opacity:0.8},1000).delay(stay).animate({opacity:0},2000,function(){$(this).hide();});
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

/*!
	reflection.js for jQuery v1.1
	(c) 2006-2011 Christophe Beyls <http://www.digitalia.be>
	MIT-style license.
*/
(function(a){a.fn.extend({reflect:function(b){b=a.extend({height:1/3,opacity:0.5},b);return this.unreflect().each(function(){var c=this;if(/^img$/i.test(c.tagName)){function d(){var g=c.width,f=c.height,l,i,m,h,k;i=Math.floor((b.height>1)?Math.min(f,b.height):f*b.height);l=a("<canvas />")[0];if(l.getContext){h=l.getContext("2d");try{a(l).attr({width:g,height:i});h.save();h.translate(0,f-1);h.scale(1,-1);h.drawImage(c,0,0,g,f);h.restore();h.globalCompositeOperation="destination-out";k=h.createLinearGradient(0,0,0,i);k.addColorStop(0,"rgba(255, 255, 255, "+(1-b.opacity)+")");k.addColorStop(1,"rgba(255, 255, 255, 1.0)");h.fillStyle=k;h.rect(0,0,g,i);h.fill()}catch(j){return}}else{if(!a.browser.msie){return}l=a("<img />").attr("src",c.src).css({width:g,height:f,marginBottom:i-f,filter:"FlipV progid:DXImageTransform.Microsoft.Alpha(Opacity="+(b.opacity*100)+", FinishOpacity=0, Style=1, StartX=0, StartY=0, FinishX=0, FinishY="+(i/f*100)+")"})[0]}a(l).css({display:"block",border:0});m=a(/^a$/i.test(c.parentNode.tagName)?"<span />":"<div />").insertAfter(c).append([c,l])[0];m.className=c.className;a.data(c,"reflected",m.style.cssText=c.style.cssText);a(m).css({width:g,height:f+i,overflow:"hidden"});c.style.cssText="display: block; border: 0px";c.className="reflected"}if(c.complete){d()}else{a(c).load(d)}}})},unreflect:function(){return this.unbind("load").each(function(){var c=this,b=a.data(this,"reflected"),d;if(b!==undefined){d=c.parentNode;c.className=d.className;c.style.cssText=b;a.removeData(c,"reflected");d.parentNode.replaceChild(c,d)}})}})})(jQuery);
/* Copyright (c) 2009 Brandon Aaron (http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 *
 * Version: 3.0.2
 * 
 * Requires: 1.2.2+
 */
(function(c){var a=["DOMMouseScroll","mousewheel"];c.event.special.mousewheel={setup:function(){if(this.addEventListener){for(var d=a.length;d;){this.addEventListener(a[--d],b,false)}}else{this.onmousewheel=b}},teardown:function(){if(this.removeEventListener){for(var d=a.length;d;){this.removeEventListener(a[--d],b,false)}}else{this.onmousewheel=null}}};c.fn.extend({mousewheel:function(d){return d?this.bind("mousewheel",d):this.trigger("mousewheel")},unmousewheel:function(d){return this.unbind("mousewheel",d)}});function b(f){var d=[].slice.call(arguments,1),g=0,e=true;f=c.event.fix(f||window.event);f.type="mousewheel";if(f.wheelDelta){g=f.wheelDelta/120}if(f.detail){g=-f.detail/3}d.unshift(f,g);return c.event.handle.apply(this,d)}})(jQuery);
