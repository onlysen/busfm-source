var simar;
// var prediary=nextdiary=$.parseJSON('[]');
var diarys=[];
var curindex=0;
var curpage=1;
var itflag=0;//定时器时间标识
var globleInterval;//全局定时器
$(function(){
	//判断登录状态
	if (getCookie("member_id") != "") {
		$("#u-login").hide();
		$("#u-info").show();
		setProfile();
	}
	//全局ajax错误捕获
	$("body").ajaxError(function(e,x,o){popAlert("服务器错误"+o.url,2);if(true)$(".sidewidget").hide();});
	//展开、收起侧栏
	$("#sidebar-switch").click(function(){
		var o=$(this);
		var s=$("#sidebar");
		if(s.css("right")=="0px")
			s.animate({right:-190},1000,function(){o.css({"background-position":"-14px -44px"});});
		else
			s.animate({right:0},1000,function(){o.css({"background-position":"4px -44px"});});
	});
	//收藏
	$("#fav").click(function(e){
		e.stopPropagation();
		var c=getCookie("member_id");
		if(c==""){popAlert("您还未登录，无法使用收藏功能<a href='#' id='notlogin'>没有账号</a>?",2);return;}
		var o=$(this);
		if(o.data("xmlhttp")!=undefined) return;
		var sid=$(this).data("cursongid");
		var sp=$("span",this);
		var url=encodeURI("/ajax/sc?id="+sid+"&t="+new Date().getMilliseconds());
		if (isie9){if(window.external.msIsSiteMode())setFavBtnState(!$("#fav span").is(".star"));}
		var x=$.get(url,function(d){
			o.removeData("xmlhttp");
			var f=d.split('|');
			if(f[0]=="0"){
				popAlert(f[1],2);
			}else{
				sp.toggleClass("star");//发布时改成ajax
				popAlert(f[1]);
			}});
		o.data("xmlhttp",x);
		return false;
	});
	//频道栏
	$("#channel_ico").click(function(){
		var o=$("#channel-list");
		checkSideBar();
		if(o.css("opacity")==0){//频道拉下来
			o.show().animate({"opacity":1,top:"30%"},1000);
			$("#user-panel").animate({"opacity":0,top:"80%"},1000,false,function(){$(this).hide();});
		}
		return false;
	});
	//个人栏
	$("#notlogin").live("click",function(){$("#user_ico").trigger("click");return false;});
	$("#user_ico").click(function(){
		var o=$("#user-panel");
		checkSideBar();
		if(o.css("opacity")==0){//把个人区拉上来
			$("#channel-list").animate({"opacity":0,top:"10%"},1000,function(){$(this).hide();});
			o.show().animate({"opacity":1,top:"30%"},1000,false);
		}
		return false;
	});
	/*
	//收藏列表加渐淡效果
	var listheight=300;//列表高度
	var fontsize=26;
	var c=$("#favlist").css({height:listheight,fontSize:fontsize});
	var nheight=120;//绝对透明区域高度
	var forcount=1-((listheight-nheight)/4/100);//计算透明度中止值
	for(var i=1;i>forcount;i-=0.01){
		$("<div/>",{
			css:{height:2,opacity:i,backgroundColor:"#f4f4f4"}
		}).appendTo(c);
	}
	$("<div/>",{
		css:{height:nheight,opacity:0,backgroundColor:"#fff"}
	}).appendTo(c);
	for(var i=forcount;i<=1;i+=0.01){
		$("<div/>",{
			css:{height:2,opacity:i,backgroundColor:"#f4f4f4",width:"100%"}
		}).appendTo(c);
	}
	// 列表滚动
	var fu=$("ul",c);
	var heightall=fu.height();
	var heidiff=heightall-listheight+fontsize;
	var ltta=$("#list-top");
	var ltba=$("#list-bottom");
	var lock=false;
	var speed=300;
	var forie7=true;//ie7bug
	$("#list-top").live("click",function(){if(lock) return; if(heightall==0){heightall=fu.height(); heidiff=heightall-listheight+fontsize;} if(heidiff<=0) return; lock=true; fu.animate({top:"-=50px"},speed,function(){lock=false; heidiff-=50;if(heidiff>0){ltba.show();if(forie7&&$.browser.msie&&$.browser.version=="7.0"){$("body").hide().show();}}else ltta.hide();});});
	$("#list-bottom").live("click",function(){
		if(lock) return;
		var t=Number(fu.css("top").replace(/px/,''));
		if(t>=fontsize) return; 
		lock=true;
		fu.animate({top:"+=50px"},speed,function(){
			lock=false; 
			heidiff+=50;
			t=Number(fu.css("top").replace(/px/,''));
			if(t<fontsize) ltta.show();
			else{
				//TODO:这里ajax取出下一页信息，干脆把分页信息返回来
				if(true)//没有下一页，则隐藏箭头
				ltba.hide();
			}
		});
	});
	$(c).mousewheel(function(e,d){if(!lock){ speed=30; if(d<0) ltta.trigger("click"); else ltba.trigger("click"); speed=300;}});
		
	$("#favlist")[0].onselectstart=function(){event.returnValue=false;return false;}*/
	
	//当前播放
	simar=setInterval(songMarquee,8000);	
	$("#SongInfo ul").hover(function(){clearInterval(simar);},function(){simar=setInterval(songMarquee,8000);});
	
	//收藏列表
	// $("#viewfav").click(function(){$("#favlist").toggle();});
	//日记列表
	// $("#viewnote").click(function(){});
		
	//升级IE提示栏
	$("#ieupgrade").hover(function(){$(this).addClass("iehover");},function(){$(this).removeClass("iehover");})
	.click(function(){window.open("choice.html");$(this).slideUp(600);})
	.find(".ieclose").click(function(e){$(this).parent("div").slideUp(600);e.stopPropagation();});
	
	//日记盒子
	$("#diarybox").click(function(){var o=$("#box-main"); setbox(o,o.is(":hidden"));});
	$("#boxclose").click(function(){setbox($("#box-main"),false);});
	function setbox(o,b){if(b){if(diarys.length==0)getDiaryList();o.fadeIn(1000);}else o.fadeOut(1000);}
	$("#subdiary").click(function(){var o=$("#txtdiary");var s=encodeURIComponent(o.val()).replace(/\n+/g,'<br />'); var p=$("#isprivate").is(":checked")?-1:0; $.get("/ajax/micblog",{action:"add_ok",isprivate:p,content:s,t:new Date().getMilliseconds()},function(d){f=d.split('|');if(f[0]=="0")popAlert(f[1],2);else{
			//日记成功后：
			popAlert("添加日记成功");
			$("#txtctr").text("200");
			diarys.unshift([f[2],s,f[3],p])
			o.val("");
			curindex=0;
			curpage=1;
			setDiary(diarys[curindex][0],diarys[curindex][1],diarys[curindex][2],diarys[curindex][3]);
		}
	});});
	
	//得到日记列表,参数当前页
	function getDiaryList(pageindex){
		var page=pageindex||1;
		var od=$(".diarybody");
		var ot=od.html();//请求新数据前保存当前日记
		od.html("<img src='image/loading.gif' alt='' />").addClass("center");
		$.get("/ajax/micblog",{action:"list",t:new Date().getMilliseconds()},function(j){
			f=j.split('|');
			if(f[0]=="0"){
				popAlert(f[1]);
				od.html(ot).removeClass("center");
				return;
			}
			try{
				curpage++;
				var d=eval(j.replace(/\s/g,' '));
				diarys=diarys.concat(d);
				setDiary(d[0][0],d[0][1],d[0][2],d[0][3]);
			}
			catch(err){popAlert(err.message);}
		});
	}
	
	//读取日记
	function setDiary(id,txt,time,status){
			$("#diarylist .diarybody").fadeOut(500,function(){$(this).html(decodeURIComponent(txt)).removeClass("center").data({id:id});}).fadeIn(500);
			$("#diarylist .diarytime").html(time);
			setDiaryStatus(status);
	}
	function setDiaryStatus(status){
		var statusclass=status>-1?"icopublic":"icoprivate";//-1:private,0:public,1:recomond
		var title=status>-1?'公开':'私人';
		$("#diarystatus").removeClass("icopublic icoprivate").addClass(statusclass).attr("title",title);
	}
	
	$("#diarylist .leftarr").click(function(){
		if(--curindex<0){popAlert("喔唷，没有了");curindex=0;return;}
		setDiary(diarys[curindex][0],diarys[curindex][1],diarys[curindex][2],diarys[curindex][3]);
	});
	$("#diarylist .rightarr").click(function(){
		if(diarys.length>++curindex){
			setDiary(diarys[curindex][0],diarys[curindex][1],diarys[curindex][2],diarys[curindex][3]);
			return;
		}
		//到最后一条，则取下十条
		getDiaryList(curpage);//目前还未分页
		//todo:判断最后一条
	});
	
	//删除日记
	$("#diarydel").click(function(){
		if(diarys.length==0) return;//无日志
		// if(!confirm('确认删除？')) return;
		var o=$(this);
		if(o.data("xmlhttp")!=undefined) return;
		var id=$("#diarylist .diarybody").data("id");
		var x=$.get("/ajax/micblog",{t:new Date().getMilliseconds(),"action":"del","id":id},function(d){
			o.removeData("xmlhttp");
			var f=d.split('|');
			if(f[0]=="0"){popAlert(f[1],2); return;}
			diarys.splice(curindex,1);
			popAlert(f[1]);
			if(diarys.length==0) $("#diarylist").find(".diarybody").html("你随便的说，<br />我却认真地难过。");
			else setDiary(diarys[curindex][0],diarys[curindex][1],diarys[curindex][2],diarys[curindex][3]);
		});
		o.data("xmlhttp",x);
	});
	//更改日记隐私状态
	$("#diarystatus").click(function(){
		var o=$("#diarystatus");
		if(o.data("xmlhttp")!=undefined) return;
		var status=o.is(".icopublic")?-1:0;
		var id=$("#diarylist .diarybody").data("id");
		var x=$.get("/ajax/micblog",{t:new Date().getMilliseconds(),"action":"s","id":id,"isprivate":status},function(d){
			o.removeData("xmlhttp");
			var f=d.split('|');
			if(f[0]==0){popAlert(f[1],2);return;}
			setDiaryStatus(status);
			popAlert(f[1]);
		});
		o.data("xmlhttp",x);
	});
	
	//日记窗口拖动
	var box=$("#box-main");
	$("#b-head").mousedown(function(e){
		var o=$(this);
		var p=box.position();
		o.data({ox:e.pageX,oy:e.pageY,onmove:true,left:p.left,top:p.top});
	});
	$("*").mousemove(function(e){
		var o=$("#b-head");
		if(o.data("onmove")){
			var x=e.pageX-o.data("ox");
			var y=e.pageY-o.data("oy");
			var fx=o.data("left")+x;
			var fy=o.data("top")+y;
			//设置不允许超出窗体范围
			if(fx<0) fx=0;else{
				fx=Math.min(fx,document.body.clientWidth-box.width()-5);
			}
			if(fy<0) fy=6;else{
				fy=Math.min(fy,document.body.clientHeight-box.height()-20);
			}
			box.css({left:Math.max(fx,0),top:Math.max(fy,6)});
			this.onselectstart=function(){event.returnValue=false;return false;}
		}
		else this.onselectstart=function(){event.returnValue=true;return true;}
	});
	$("*").mouseup(function(e){$("#b-head").data("onmove",false);});
	//字数统计
	var tc;
	$("#txtdiary").focus(function(){
		tc=setInterval(function(){var t=$("#txtdiary");checktxtctr(t);$("#txtctr").text(200-t.val().length);},200);
	}).blur(function(){clearInterval(tc);}).keydown(function(){return checktxtctr($(this));}).val("").click(function(){$(this).blur().focus();});
	function checktxtctr(t){if(t.val().length>200){t.val(t.val().substring(0,200)); return false;}else return true;}
	
	//公共日志
	$("#publicdiary").data({diary:[],i:0});
	getPromoteDiary();
	
	//全局定时事件，每分钟触发一次,每十分钟置零
	globleInterval=setInterval(function(){
		itflag=(itflag++)%10;
		if(itflag%3==0) getPromoteDiary();//切换公共日记
		setProfile();
	},60000);
	
	//打开公告
	if($("#notice n-body").html()!="") siteNotice();
	//关闭公告
	$("#notice .n-close").click(function(){$("#notice").animate({opacity:0},2000,function(){$(this).hide();setCookie("sitenotice"+$("#noticeid").val(),"true",30);});});
	
	//全局暂停
	$(".statusbar,#coverimg").click(function(){
		if($("#jplayer_pause").is(":hidden")) $("#jplayer_play").trigger("click");
		else $("#jplayer_pause").trigger("click");
	});
	
	//生成歌曲外链
	$("#recom").click(function(e){
		e.stopPropagation();
		if($("#hidchannel").val()=="100"){ popAlert("特殊频道不支持分享",2); return;}
		var o=$(this);
		if(o.data("xmlhttp")!=undefined) return;
		var url="/ajax/public?action=url&id="+$("#fav").data("cursongid")+"&t="+new Date().getMilliseconds();
		var x=$.get(encodeURI(url),function(d){
			o.removeData("xmlhttp");
			if(Number(d)==0){popAlert('操作失败，请稍候再试',2);return;}
			$("#clipbord").stop(true,true).css({"margin-left":function(){return -($(this).width()/2);},opacity:1}).animate({"bottom":80},200).find(".mp3url").val(d).select();
		});
		o.data("xmlhttp",x);
		return false;
	});
	$("#clipbord .n-close").click(function(){
		$("#clipbord").animate({bottom:-30},200,function(){$(this).css("opacity",0).find(".mp3url").blur();})
	});//关闭外链窗口
	$(".mp3url").keyup(function(e){if(e.which==27)$("#clipbord .n-close").trigger("click");});
	//点击分享链接
	$("#clipbord .snsicon").click(function(){
		var o=$(this);
		var des=encodeURIComponent(myPlayList[playItem][1]+" - "+myPlayList[playItem][3]);
		var url=encodeURIComponent($(".mp3url").val());
		var img=$("#coverimg img").attr("src");
		img=encodeURIComponent(img=="/image/default.gif"?"":img);
		var snsurl="";
		switch(o.attr("name")){
			case "xl":
			snsurl="http://v.t.sina.com.cn/share/share.php?title="+des+"&url="+url+'&appkey=4071314818&pic='+img+'&ralateUid=';//1071696872';
			break;
			case "rr":
			snsurl='http://share.renren.com/share/buttonshare.do?link='+url+'&title='+des;
			break;
			case "db":
			snsurl='http://www.douban.com/recommend/?url='+url+'&title='+des;
			break;
			case "ff":
			snsurl='http://fanfou.com/sharer?u='+url+'&t='+des+'&d=&s=';
			break;
			case "tx":
			snsurl='http://v.t.qq.com/share/share.php?title='+des+'&url='+url+'&site=&pic='+img+'&appkey=a3f50d5a65d949a1997f5f5dce42fb67';
			break;
			case "bz":
			snsurl='http://www.google.com/buzz/post?url='+url+"&message="+des+'&imageurl='+img;
			break;
			case "tt":
			snsurl="http://twitter.com/home?status="+url;
			break;
			case "fb":
			snsurl='http://www.facebook.com/share.php?u='+url+'&t='+des;
			break;
		}
		window.open(snsurl,'favit','width=720,height=500,left=50,top=50,toolbar=no,menubar=no,location=no,scrollbars=yes,status=yes,resizable=yes');
	});
	
	//历史歌曲信息浮动
	$("#historys img").live("mousemove",function(e){
		var t=$("#his-tips");
		var o=$(this).css({opacity:1});
		var txt="曲目: "+o.data("title")+"<br/>歌手: "+o.data("artist")+"<br/>专辑: "+o.data("album");
		t.find(".ht-cont").html(txt).end().css({top:function(){return e.clientY-$(this).height()-20;},left:function(){return e.clientX-$(this).width()/2;}}).show();
	})
	.live("mouseout",function(){$("#his-tips").hide();$(this).css({opacity:0.6});});
	
	//end
});
//歌曲信息滚动
function songMarquee(){$("#SongInfo ul").animate({"margin-top":-28},1000,function(){$(this).css({"margin-top":0}).find("li:first").appendTo(this);});}
//把侧栏抽出
function checkSideBar(){var s=$("#sidebar");if(s.css("right")!="0px") s.animate({right:0},1000,function(){$("#sidebar-switch").css({"background-position":"4px -44px"});});}
function getaccount(b){if(!b){$("#u-login").slideUp(1000);$("#u-reg").slideDown(1000);}else{$("#u-login").slideDown(1000);$("#u-reg").slideUp(1000);}}
function goLogin(){$("#u-login").slideDown(1000);$("#u-reg").slideUp(1000);}
//忘记密码
function getpwd(){$.get(encodeURI("/ajax/member?action=forget_ok&member_mail="+$("#txtemail").val()+"&t="+new Date().getMilliseconds()), function(d){var f=d.split('|'); if(f[0]=="0") popAlert(f[1],2);else popAlert(f[1]);});}
//注册逻辑
var vali=true;
var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
var em_o=false;//标志ajax查询邮件地址是否被注册已结束
var nn_o=false;//昵称ajax查询
var an_o=true;//邀请码ajax查询
$("#txtemail_r").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o); return;} if(o.val()==""||!(em_p.test(o.val()))) ifvali(false,o,'邮件地址不正确'); else{ em_o=false; $.get(encodeURI("/ajax/member?action=validate_mail&mail="+o.val()+"&t="+new Date().getMilliseconds()),function(d){ em_o=true; var f=d.split('|'); if(f[0]=="0") ifvali(false,o,f[1]); else ifvali(true,o);});}});
$("#txtpwd_r").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o); return;} if(o.val()==""||!(/^[0-9a-zA-Z]{6,20}$/.test(o.val()))) ifvali(false,o,'密码6-20位数字和字母'); else ifvali(true,o);});
$("#txtpwd_r2").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o); return;} if(o.val()!=$("#txtpwd_r").val()) ifvali(false,o,'密码输入不一致'); else ifvali(true,o);});
$("#txtnickname").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o);return;} if(o.val()==""){ifvali(false,o,'请输入您的昵称');}else{ nn_o=false;$.get(encodeURI("/ajax/member?action=validate_nickname&name="+o.val()+"&t="+new Date().getMilliseconds()),function(d){nn_o=true;var f=d.split('|');if(f[0]=="0"){ifvali(false,o,f[1]);}else ifvali(true,o);});}});
//$("#txtapply").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o);return;} if(o.val()==""){ifvali(false,o,'请输入邀请码');}else{ an_o=false;$.get(encodeURI("/ajax/member?action=validate_safecode&safecode="+o.val()+"&t="+new Date().getMilliseconds()),function(d){an_o=true;var f=d.split('|');if(f[0]=="0"){ifvali(false,o,f[1]);}else ifvali(true,o);});}});
//注册
$("#form2").bind("submit",function(e){
	$(".txtreg").blur();
	reg();
	e.preventDefault();
});
function reg(){
	popAlert("提交中...");
	if(!em_o||!nn_o||!an_o){ setTimeout(function(){reg();},333); }//有验证还在ajax的交互中，不允许提交
	else{
		if(vali){
			var fo=$("#form2");
			if(fo.data("xmlhttp")!=undefined){popAlert("请求正在处理，请勿重复提交",2); return;}
			var url=encodeURI(fo.attr("action")+"&t="+new Date().getMilliseconds())
			var x=$.post(url,fo.serialize(),function(d){
				fo.removeData("xmlhttp");
				var f=d.split('|');
				if(f[0]=="0") popAlert(f[1],2);
				else{popAlert(f[1]);getaccount(true);}
			});
			fo.data("xmlhttp",x);
		}
		else vali=true;
	}
}
//登录
$("#form1").bind("submit",function(e){
	e.preventDefault();
	var emailtxt=$("#txtemail").val();
	if(emailtxt==""||!em_p.test(emailtxt)||$("#txtpwd").val()=="") loginmsg("登录信息无效");
	else{
		var url=encodeURI($(this).attr("action")+"&t="+new Date().getMilliseconds());
		$.post(url,$(this).serialize(),function(d){
			var f=d.split('|');
			if(f[0]=="0") loginmsg(f[1]);
			else{
				$("#u-login").slideUp(1000);$("#u-info").slideDown(1000);
				setProfile();
			}
		});
	}
});
function loginmsg(msg){$("#loginmsg").css({opacity:1}).text(msg).animate({opacity:0},5000,false);}
//退出登录
function logout(){
	//TODO:清除cookie
	$.get("/ajax/member?action=logout&t="+new Date().getMilliseconds(),function(){
		$("#txtemail").val("");
		$("#txtpwd").val("");
		$("#u-login").slideDown(1000);$("#u-info").slideUp(1000);$("#box-main").fadeOut(1000);
	});
}
//写入个人信息到侧栏
function setProfile(time){
	var te=$("#u-info .u-info-regtime");
	var t=te.text();
	if(!te.is(".havetime")) t=time||new Date(Number(getCookie("join_time"))*1000).toLocaleString().replace(/^(\S+)\s(\d+):(\d+):(\d+)$/,'$1$2时$3分$4秒');
	$("#u-info").find(".u-info-name").text(getCookie("member_nickname")).end().find(".u-info-lasttime").text(checkLastTime()).end().find(".u-info-regtime").text(t);
}
/*
*b是否验证通过，o，对应的文本框对象，t，错误提示
*/
function ifvali(b,o,t){
	var m=$("#lblmsg");	
	var i=o.parent("li").index();//根据索引确定相应的提示框的索引
	var p=$("p",m).eq(i);
	t=t||"";
	if(b){//通过验证
		o.removeClass("onerror");
		o.data({"otxt":o.val()});
	}else{// 未通过验证
		vali=false;
		m.show();
		o.addClass("onerror");
	}
	p.text(t);
}

//提取公共日志
function getPromoteDiary(){
	var c=$("#publicdiary");
	var i=Number(c.data("i"));
	var e=eval(c.data().diary);
	if(++i>=e.length){
		$.get("/ajax/pblog",function(d){
			var s=eval(d);
			if(s.length>0){
				c.data({diary:d,i:0}).find(".pbmain").html(decodeURIComponent(s[0][1])).nextAll(".pbauthor").html(s[0][3]);
			}
		});
	}else{
		c.data("i",i).find(".pbmain").html(decodeURIComponent(e[i][1])).nextAll(".pbauthor").html(e[i][3]);
	}
}
//计算注册时到现在的时长
function checkLastTime(){
	var j=getCookie("join_time");
	if(j=="") return NaN;
	var n=Math.round(new Date().getTime()/1000);
	n=Math.round((n-j)/60);//时间差,单位分
	var l=n%60;
	n=Math.round(n/60);
	return n+'小时'+l+'分';
}
//弹出提示信息
/*msg:文本内容
 *msgtype:消息类型 1:普通提示，2：警告、错误
*/
function popAlert(msg,msgtype){
	var mt=msgtype||1;
	var mdiv=$("#msgs");
	var stay=mt==1?3000:15000;
	mdiv.html(msg).stop(true,true).css("margin-left",function(){return -($(this).width()/2);}).animate({opacity:1},1000).delay(stay).animate({opacity:0},2000);
	if(mt==1) mdiv.removeClass("errdiv").addClass("alertdiv");
	else mdiv.removeClass("alertdiv").addClass("errdiv");
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
//公告区
function siteNotice(){
	if(getCookie("sitenotice"+$("#noticeid").val())!="true"){
		$("#notice").show().animate({opacity:1},2000);
		setTimeout(function(){$("#notice").animate({opacity:0},2000,function(){$(this).hide();});},30000);
	}
}