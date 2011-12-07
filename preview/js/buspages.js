//本文件专用于各个子页面的调用
//所有页面切换分成两部分：切换页头导航，和切换页面内容
//同时还要处理箭头位置
$(function(){
	var navihtml='<a href="javascript:void(0);" class="cb-public btnchannel">公共频道</a><a href="javascript:void(0);" class="cb-private btnchannel">私人频道</a>';//用于保存首页的页头
	var navicp='<a id="page-navi-home" href="#">电台首页</a><a class="page-cpanel" href="#cpanel">个人中心</a><a class="page-diary" href="#diary">个人日记</a>';//个人中心页头
	var naviabout='<a id="page-navi-home" href="#">电台首页</a><a class="page-about" href="#about">关于巴士</a><a class="page-links" href="#android">巴士应用</a>';//关于页面页头
	var navireset='<a id="page-navi-home" href="#">电台首页</a><a class="page-reset" href="#reset">找回密码</a>';//找回密码页面页头
	var subpage=$("#subpage");//子页面
	var mainpage=$("#mainpage");//主页面
	var navicontainer=$(".channel-bar").eq(0);//导航容器
	var ajaximg=$("#ajaxload");//ajax加载中图标
	showHashPage();
	//绑定hash切换事件
	$(window).bind('hashchange', function(e){showHashPage();});
	//全站唯一需要动态计算高度的地方
	$(window).resize(function(){setPageSize();});
	
	function setPageSize(){
		var d=$(".diary-list");
		if(d.length==0)return;
		//得到日志列表的可用高度
		$("#mainpage").hide();//计算起点位置时，把mainpage元素隐藏，否则会把mainpage的高度计算进来
		var h=$(window).height()-d.offset().top-150;
		d.css({height:h});
	}
	
	//根据hash显示页面
	function showHashPage(){
		$("#clipbord").slideUp();//浮层bug
		hash=window.location.hash;
		switch(hash){
			case "#about":
			loadContent("about.php",hash,naviabout,175);
			break;
			case "#android":
			loadContent("about.php",hash,naviabout,45);
			break;
			case "#diary":
			loadContent("diary.php",hash,navicp,45,setPageSize);
			break;
			case "#cpanel":
			loadContent("cpanel.php",0,navicp,175);
			break;
			case "#reset":
			logregDialog();
			loadContent("reset.php",0,navireset,45);
			break;
			case "#":
			default:
			goHome();
			break;
		}
	}
	
	/*
	*加载导航和页面内容
	*@url:提供内容的页面地址
	*@hash:提供内容的容器ID和当前URL的hash值，请保持两者一致，传0表示加载整个页面（如果需要执行页面js，请传0）
	*@navi:页头
	*@arrposition:手动计算的当前页箭头的位置
	*@callback:回调函数
	*/
	function loadContent(url,hash,navi,arrposition,callback){
		//为了简洁，请保持加载页面的hash与对应页面容器的ID一致，比如请求#about,那么目标页面提供内容的DIV的ID也为about
		if($(hash).length>0)return;
		//找到用于消失的页面，比如首页和子页的切换，消失的页面是首页，子页间的切换，先把子页消失再重新显示
		var h=mainpage;
		var slidepage=$(".slidepage");//找到用于左滑动消失的页面
		if(mainpage.is(":hidden")){ h=subpage;slidepage=subpage;}
		slidepage.animate({"margin-left":-1000},300,function(){h.hide();$(this).css({"margin-left":0});});
		//动画完成后才开始加载，真实网站中其实不需要，因为很少情况下加载一个页面会小于300毫秒。这是为了确保不会在动画过程中网页已经加载完毕
		ajaximg.show();
		url+="?t="+new Date().getMilliseconds();
		navicontainer.html(navi);
		$("body").removeClass("highbody");
		$("#footer-index").addClass("nodiary");
		$(".uparr").css({right:arrposition});
		if(hash!='0') url=url+" "+hash;
		subpage.load(url,function(d){
			if(d=="请先登录"){
				$("#actlogin").click();
			}
			subpage.animate({opacity:'show'},800);
			ajaximg.hide();
			if(callback!=undefined) callback();
		});
	}

	//回到首页的方法
	function goHome(){
		subpage.animate({"margin-left":-1000},300,function(){$(this).hide().html("").css({"margin-left":0});
			ajaximg.show();
			mainpage.css({"margin-left":0}).animate({opacity:'show'},500);
			navicontainer.html(navihtml);
			ajaximg.hide();
		});
		//复原频道
		var c=$("#hidchannel").val();
		if(c!="99"){$(".uparr").css({right:200});$(".channel-bar a").eq(0).click();}
		else{ $(".uparr").css({right:45});$(".channel-bar a").eq(1).click();}
		if($(".curchannel").is(".cb-private")) $(".uparr").css({right:45});//解决hash从无到有会引发一起change事件，结果导致箭头错位的bug
		//高度fix
		if($("#v-handle").is(".casemulti")) $("body").addClass("highbody");
		else $("body").removeClass("highbody");
		$("#footer-index").removeClass("nodiary");
	}
});
