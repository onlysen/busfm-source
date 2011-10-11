var globleInterval;//全局定时器
$(function(){
	$("#hidchannel").val(1);
	//判断登录状态
	if (getCookie("member_id") != "") {
		setProfile();
	}
	//全局ajax错误捕获
	$("body").ajaxError(function(e,x,o){popAlert("服务器错误"+o.url,2);if(true)$(".sidewidget").hide();});
	//收藏
	$("#fav").click(function(e){
		e.stopPropagation();
		var c=getCookie("member_id");
		if(c==""){/*popAlert("您还未登录，无法使用收藏功能<a href='#' id='notlogin'>没有账号</a>?",2);*/$("#actlogin").click();return;}
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
				sp.toggleClass("star");
				popAlert(f[1]);
			}});
		o.data("xmlhttp",x);
		return false;
	});
	//频道切换
	$(".channel-bar a").live("click",function(){
		i=$(this).index();
		$(".curchannel").removeClass("curchannel");
		$(this).addClass("curchannel");
		$("#channellist span").hide();
		if(i==0){$(".ch-public").show();$(".uparr").css({right:135});}
		else{ 
			if(getCookie("member_id") == ""){$("#actlogin").click();return;}
			$(".ch-private").show();$(".uparr").css({right:45});
		}
	});
	//历史歌曲信息浮动
	$("#alumn-wall img").live("mousemove",function(e){
		var i=$(this);
		var t=$("#his-tips");
		var o=$(this);//.css({opacity:1});
		var txt="曲目: "+o.data("title")+"<br/>歌手: "+o.data("artist")+"<br/>专辑: "+o.data("album");
		t.find(".ht-cont").html(txt).end().css({top:function(){return e.pageY-85;},left:function(){return e.pageX-$(this).width()/2;}}).show();
	})
	.live("mouseout",function(){$("#his-tips").hide();/*$(this).css({opacity:0.6});*/});;
	$("#song-history img").live("mousemove",function(e){
		var i=$(this);
		var t=$("#his-tips");
		var o=$(this).addClass("boxshadow");//.css({opacity:1});
		var txt="曲目: "+o.data("title")+"<br/>歌手: "+o.data("artist")+"<br/>专辑: "+o.data("album");
		t.find(".ht-cont").html(txt).end().css({top:function(){return i.offset().top-80;},left:function(){return e.pageX-$(this).width()/2;}}).show();
	})
	.live("mouseout",function(){$("#his-tips").hide();$(this).removeClass("boxshadow");/*$(this).css({opacity:0.6});*/});
	//导航栏登录按钮
	$("#actlogin").click(function(){$("#form1").show();logregDialog(true);$(".dg-c-title").text("登录");return false;});
	//信息栏中注册/登录按钮被点击[popAlert]
	$("#notlogin").live("click",function(){$("#form1").show();logregDialog(true);$(".dg-c-title").text("登录");return false;});
	//导航栏中的注册按钮被点击
	$("#actreg").click(function(){$("#form2").show();logregDialog(true);$(".dg-c-title").text("注册");return false;});
	//注册/登录窗体关闭按钮：
	$(".dg-c-close a").click(function(){logregDialog();});
	//注册
	$("#form2").bind("submit",function(e){
		e.preventDefault();
		if($("#txtemail_r").is(".onvali")||$("#txtnickname").is(".onvali")) return false; //有ajax验证，阻止提交
		$(".txtreg").blur();
		reg();
	});
	//登录
	$("#form1").bind("submit",function(e){
		e.preventDefault();
		var emailtxt=$("#txtemail").val();
		if(emailtxt==""||!em_p.test(emailtxt)||$("#txtpwd").val()=="") trierror("登录失败，请重试");
		else{
			var url=encodeURI($(this).attr("action")+"&t="+new Date().getMilliseconds());
			$.post(url,$(this).serialize(),function(d){
				var f=d.split('|');
				if(f[0]=="1"){
					location.reload();
					//~ $(".unlogin").hide();$(".logged").show();
					//~ $("#login-name").text(getCookie("member_nickname"));
					//~ logregDialog();
				}
				else trierror(f[1]);
			});
		}
	});
	//个人快捷菜单
	var pcontexttimer;
	$(".ucontext,#p-context-menu").mouseover(function(){clearTimeout(pcontexttimer);if($(this).is(".logged"))$("#p-context-menu").slideDown(100);});
	$(".ucontext,#p-context-menu").mouseout(function(){var o=$("#p-context-menu");pcontexttimer=setTimeout(function(){o.slideUp(100);},100);});
		
	//生成歌曲外链
	$("#share").click(function(e){
		e.stopPropagation();
		if($("#clipbord").is(":visible")){$("#clipbord .n-close").trigger("click");return;}
		if($("#hidchannel").val()=="100"){ popAlert("特殊频道不支持分享",2); return;}
		$("#clipbord").css({top:function(){return $("#player").offset().top+50;}}).animate({opacity:"show"},300).find(".mp3url");
		return false;
	});
	$("#clipbord .n-close").click(function(){
		$("#clipbord").animate({opacity:"hide"},300);//.animate({bottom:-30},200,function(){$(this).css("opacity",0).find(".mp3url").blur();})
	});//关闭外链窗口
	$(".mp3url").keyup(function(e){if(e.which==27)$("#clipbord .n-close").trigger("click");});
	//点击分享链接
	$("#clipbord .snsicon").click(function(){
		var o=$(this);
		var des=encodeURIComponent(myPlayList[playItem][1]+" - "+myPlayList[playItem][3]);
		var url=encodeURIComponent($(".mp3url").val());
		var img=$(".cover-img img").attr("src");
		img=encodeURIComponent(img=="img/default.gif"?"":img);
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
	
	//写日记
	$("#subdiary").live("click",function(){var o=$("#txtdiary");if(o.is(".diary-sub")) return; o.addClass("diary-sub");var s=encodeURIComponent(o.val()).replace(/\n+/g,'<br />'); 
			var p=$("#isprivate").is(":checked")?-1:0; 
			//本站日记
			$.get("/ajax/micblog",{action:"add_ok",isprivate:p,content:s,t:new Date().getMilliseconds()},
				function(d){
					o.removeClass("diary-sub");f=d.split('|');
					if(f[0]=="0")popAlert(f[1],2);
					else{
						//日记成功后：
						o.val("");
						popAlert("添加日记成功");
						$("#txtctr").text("200");
						li=setDiary(f[2],decodeURIComponent(s),f[3],$("#isprivate").is(":checked"));
						$(li).hide().prependTo($(".diary-list ul")).slideDown();
						$(".diary-list").animate({scrollTop:0},1000);
						
						//成功后才同步到微博
						if(!$("#synsns").prop("checked")) return;
						//腾讯
						var url=domain+"oauth/QQ/process.php";
						var linkedsns=$("#hidsns").val();
						if(linkedsns.indexOf('qq')>0){
							$.get(url,{t:new Date().getMilliseconds(),cont:s},function(d){
								var f=d.split('|');
								if(f[0]!=0) popAlert("同步到腾讯微博失败，原因："+f[1],2);
							});
						}
						//新浪
						if(linkedsns.indexOf('sina')>0){
							url=domain+"oauth/sina/process.php";
							$.get(url,{t:new Date().getMilliseconds(),cont:s},function(d){
								if(d!='ok') popAlert("同步到新浪微博失败，原因："+d,2);
							});
						}
					}
			});
	});
	//字数统计
	var tc;
	$("#txtdiary").live("focusin",function(){
		tc=setInterval(function(){var t=$("#txtdiary");checktxtctr(t);$("#txtctr").text(200-t.val().length);},200);
	}).live("focusout",function(){clearInterval(tc);}).keydown(function(){return checktxtctr($(this));}).val("").click(function(){$(this).blur().focus();});
	function checktxtctr(t){if(t.val().length>200){t.val(t.val().substring(0,200)); return false;}else return true;}
	//删除日记
	$(".diary-meta .diary-del").live("click",function(){
		var o=$(this);
		var li=o.parents("li");
		if(o.data("xmlhttp")!=undefined) return;
		var id=li.data("entry");
		var x=$.get("/ajax/micblog",{t:new Date().getMilliseconds(),"action":"del","id":id},function(d){
			o.removeData("xmlhttp");
			var f=d.split('|');
			if(f[0]=="0"){popAlert(f[1],2); return;}
			li.slideUp(500,function(){$(this).remove();});
			popAlert(f[1]);
		});
		o.data("xmlhttp",x);
	});
	//更改日记隐私状态
	$(".diary-status").live("click",function(){
		var o=$(this);
		if(o.data("xmlhttp")!=undefined) return;
		var id=o.parents("li").data("entry");
		var status=o.is(".icoprivate")?0:-1;//反置状态
		var statusclass=status>-1?"icopublic":"icoprivate";
		var title=status>-1?'公开':'私人';
		var x=$.get("/ajax/micblog",{t:new Date().getMilliseconds(),"action":"s","id":id,"isprivate":status},function(d){
			o.removeData("xmlhttp");
			var f=d.split('|');
			if(f[0]==0){popAlert(f[1],2);return;}
			o.removeClass("icopublic icoprivate").addClass(statusclass).attr("title",title);
			popAlert(f[1]);
		});
		o.data("xmlhttp",x);
	});
	//日记后翻页
	$(".diary-more").live("click",function(){
		var o=$(this);
		if(o.is(".diary-loading")) return;
		o.addClass("diary-loading");
		var id=$(".diary-list li:last").data("entry")||0;
		$.get("/ajax/micblog",{action:"next",id:id,t:new Date().getMilliseconds()},function(j){
			o.removeClass("diary-loading");
			if(j=="0"||j==0){
				$(".diary-more").remove();
				return;
			}
			try{
				var d=eval(j.replace(/\s/g,' '));
				s="";
				for(var i=0;i<d.length;i++){
					s+=setDiary(d[i][0],d[i][1],d[i][2],d[i][3]=="-1");
				}
				$(s).appendTo($(".diary-list ul"));
				sh=$(".diary-list")[0].scrollHeight;
				$(".diary-list").animate({scrollTop:sh},1000);
			}
			catch(err){popAlert(err.message);}
		});
	});
	//视图切换
	$("#v-handle").click(function(){
		var o=$(this);
		var m=o.is(".casemulti");
		var showel,hideel;
		$("#clipbord").animate({opacity:"hide"},500);
		if(m){//从专辑墙切到单专辑
			showel=$("#singleview");
			hideel=$("#multiview");
			setTimeout(function(){$("body").removeClass("highbody");},500);
		}else{//切到专辑墙
			hideel=$("#singleview");
			showel=$("#multiview");
			setTimeout(function(){$("body").addClass("highbody");},400);
		}
		o.toggleClass("casemulti");
		hideel.animate({opacity:"hide"},function(){showel.animate({opacity:"show"});});
	});
	//唱片墙
	getAlbumWall();
	//公共日记
	$("#publicdiary").data({diary:[],i:0});
	getPromoteDiary();
	//全局定时事件，每三分钟触发一次
	globleInterval=setInterval(function(){getPromoteDiary();},180000);
	//音量
	$("#jplayer_vmin").mouseenter(function(){
		var fs=$("#seekbar");
		if(fs.data("nofx")==true) return;
		if(fs.data("vf")!=undefined){clearTimeout(fs.data("vf")); return;}
		fs.data("nofx",true).animate({width:280},function(){$(this).removeData("nofx");$("#jplayer_vbar").show();});
		$("#jpalyer_v_wrap").animate({width:60});
	})
	.mouseleave(function(){
		var fs=$("#seekbar");
		var leavevolumeicon=setTimeout(function(){$("#jpalyer_v_wrap").trigger("mouseleave");},300);
		fs.data("lvi",leavevolumeicon);
	});
	$("#jpalyer_v_wrap").mouseleave(function(){
		var fs=$("#seekbar");
		var o=$(this);
		if(fs.data("nofx")==true){setTimeout(function(){$("#jpalyer_v_wrap").trigger("mouseleave");},1000);return;};
		var vflag=setTimeout(function(){
		$("#jplayer_vbar").hide();
		o.animate({width:0});
		fs.animate({width:340},function(){$(this).removeData("nofx").removeData("vf");});},300);
		fs.data("vf",vflag);
	})
	.mouseenter(function(){
		var fs=$("#seekbar");
		if(fs.data("lvi")!=undefined){clearTimeout(fs.data("lvi"));return;}
	});
});
//=======onload脚本结束 ========

//切换到注册面板
function getaccount(){
	$("#form1").hide(500);
	$("#form2").show(500);
	$(".dgcinfo-body").empty().slideUp();
	$(".dg-c-title").text("注册");
}
//切换到登录面板
function goLogin(){
	$("#form2").hide(500);
	$("#form1").show(500);
	$(".dgcinfo-body").empty().slideUp();
	$(".dg-c-title").text("登录");
}
//注册逻辑
var vali=true;
var em_p=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
var em_o=false;//标志ajax查询邮件地址是否被注册已结束
var nn_o=false;//昵称ajax查询
$("#txtemail_r").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o); return;} if(o.val()==""||!(em_p.test(o.val()))) ifvali(false,o,'邮件地址不正确'); else{ if(o.is(".onvali")) return;em_o=false; $.get(encodeURI("/ajax/member?action=validate_mail&mail="+o.val()+"&t="+new Date().getMilliseconds()),function(d){ em_o=true;o.removeClass("onvali"); var f=d.split('|'); if(f[0]=="0") ifvali(false,o,f[1]); else ifvali(true,o);});o.addClass("onvali");}});
$("#txtpwd_r").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o); return;} if(o.val()==""||!(/^[0-9a-zA-Z]{6,20}$/.test(o.val()))) ifvali(false,o,'密码6-20位数字和字母'); else ifvali(true,o);});
$("#txtpwd_r2").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o); return;} if(o.val()!=$("#txtpwd_r").val()) ifvali(false,o,'密码输入不一致'); else ifvali(true,o);});
$("#txtnickname").blur(function(){var o=$(this);if(o.val()==o.data("otxt")){ifvali(true,o);return;} if(o.val()==""){ifvali(false,o,'请输入您的昵称');}else{if(o.is(".onvali")) return; nn_o=false; $.get(encodeURI("/ajax/member?action=validate_nickname&name="+o.val()+"&t="+new Date().getMilliseconds()),function(d){o.removeClass("onvali");nn_o=true;var f=d.split('|');if(f[0]=="0"){ifvali(false,o,f[1]);}else ifvali(true,o);});o.addClass("onvali");}});

function reg(){
	if(!em_o||!nn_o){ setTimeout(function(){reg();},333); }//有验证还在ajax的交互中，等待
	else{
		if(vali){
			popAlert("提交中...");
			var fo=$("#form2");
			if(fo.data("xmlhttp")!=undefined){popAlert("请求正在处理，请勿重复提交",2); return;}
			var url=encodeURI(fo.attr("action")+"&t="+new Date().getMilliseconds())
			var x=$.post(url,fo.serialize(),function(d){
				fo.removeData("xmlhttp");
				var f=d.split('|');
				if(f[0]=="1"){popAlert("注册成功");goLogin();}
				else trierror(f[1]);
			});
			fo.data("xmlhttp",x);
		}
		else vali=true;
	}
}
//退出登录
function logout(){
	//TODO:清除cookie
	$.get("/ajax/member?action=logout&t="+new Date().getMilliseconds(),function(){
		$("#txtemail").val("");
		$("#txtpwd").val("");
		$(".unlogin").show()
		$(".logged").hide();
		//TODO:与个人有关的一切界面的清空
		$("#p-context-menu").slideUp();
	});
}
//写入个人信息到页面
function setProfile(time){
	//~ var te=$("#u-info .u-info-regtime");
	//~ var t=te.text();
	//~ if(!te.is(".havetime")) t=time||new Date(Number(getCookie("join_time"))*1000).toLocaleString().replace(/^(\S+)\s(\d+):(\d+):(\d+)$/,'$1$2时$3分$4秒');
	//~ $("#u-info").find(".u-info-name").text(getCookie("member_nickname")).end().find(".u-info-lasttime").text(checkLastTime()).end().find(".u-info-regtime").text(t);
	$("#login-name").text(getCookie("member_nickname"));
	$(".unlogin").hide()
	$(".logged").show();
}
/*
*b是否验证通过，o，对应的文本框对象，t，错误提示
*/
function ifvali(b,o,t){
	v=$(".dgcinfo-body");
	sid="fva-"+($(o).attr("id")||(Math.floor(Math.random()*Math.random()*100+1)));
	$("#"+sid).remove();
	t=t||"";
	if(b){//通过验证
		o.removeClass("onerror");
		if(v.find("span").length==0) v.slideUp();
		o.data({"otxt":o.val()});
	}else{// 未通过验证
		vali=false;
		m=$("<span/>",{id:sid,html:"&bull;"+t}).appendTo(v);
		v.slideDown();
		if(o) o.addClass("onerror");
	}
}
//提取上面ifvali的提示功能
function trierror(msg){
	$(".dgcinfo-body span").remove();
	ifvali(false,null,msg);
}
//推入日记到列表
function setDiary(id,cont,time,isprivate){
	var status="icopublic";
	var stxt="公开";
	if(isprivate){status="icoprivate";stxt="私人";}
	s='<li data-entry="'+id+'">';
	s+='<div class="diary-entry">'+cont+'</div>';
	s+='<div class="diary-meta">';
	s+='<span class="diarytime">'+time+'</span>';
	s+='<span class="diary-del icodel diary-icos" title="删除">删除</span>';
	s+='<span class="diary-status diary-icos '+status+'" title="'+stxt+'">状态</span></div></li>';
	return s;
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
				c.data({diary:s,i:0}).find(".pbmain").html(decodeURIComponent(s[0][1])).prevAll(".pbauthor").html("@"+s[0][3]);
			}
		});
	}else{
		c.data("i",i).find(".pbmain").html(decodeURIComponent(e[i][1])).nextAll(".pbauthor").html(e[i][3]);
	}
}
//唱片墙
function getAlbumWall(){
	var colors=["#bddeef","#f6f3c0","#f9c1da","#b2eff4","#d7dfe2","#e6c9f5","#d5ecfa","#b2eff4"];
	var wallbase=$("#alumn-wall");
	for(var i=0;i<24;i++){
		var seq=Math.floor(Math.random()*10+1)%8;
		$("<li/>",{css:{'background-color':colors[seq]}}).appendTo(wallbase);
	}	
}
//true:open, false:close
function logregDialog(flag){
	f=flag||false;
	if(f){$("#dg-container").show();$("#srccover").show();}
	else{$("#dg-container").hide();$("#srccover").hide();$("#dg-container form").hide();}
}