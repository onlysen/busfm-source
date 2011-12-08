$(function(){
var playItem = 0;
var myPlayList;
var curVolume=80;
var autoStart=true;
var skiplock=true;//跳曲开关

$("#jp").jPlayer({
	ready:function(){
		// displayPlayList(); 
		getPlayList();
	},
	oggSupport:false,
	customCssIds:false,
	nativeSupport:true,
	preload:"auto",
	swfPath: "js/bak"
})
// .jPlayer("cssId", "play", "jplayer_play")
// .jPlayer("cssId", "pause", "jplayer_pause")
// .jPlayer("cssId", "stop", "jplayer_stop")
//.jPlayer("cssId", "volumeMin", "jplayer_vmin")
//.jPlayer("cssId", "volumeMax", "jplayer_vmax")
.jPlayer("cssId", "volumeBar", "jplayer_vbar")
.jPlayer("cssId","volumeBarValue","vamount")
// .jPlayer("cssId", "loadBar", "jplayer_load_bar")
// .jPlayer("cssId", "playBar", "jplayer_play_bar")
.jPlayer("onProgressChange", function(loadPercent, playedPercentRelative, playedPercentAbsolute, playedTime, totalTime) {
	$("#pt").text($.jPlayer.convertTime(playedTime));
	$("#tt").text($.jPlayer.convertTime(totalTime));
	// $("#lp").text(parseInt(loadPercent)+"%");
	// $("#ppr").text(parseInt(playedPercentRelative)+"%");
	// $("#ppa").text(parseInt(playedPercentAbsolute)+"%");
	// $("#jplayer_play_bar").width(parseInt(playedPercentAbsolute)+"%");
	// $("#jplayer_load_bar").width(parseInt(loadPercent)+"%");
	$("#jplayer_load_bar").text(parseInt(loadPercent)+"%");
})
.jPlayer("onSoundComplete",function(){
	playListNext();
});

//volume bar click
$("#jplayer_vbar,#vamount").click(function(e){
	var oft=$(this).offset();
	var v=e.clientX-oft.left;
	curVolume=(v/($("#jplayer_vbar").width()))*100;
	if(curVolume>0){
		var vi=$("#jplayer_vmin span");
		if(vi.is(".mute")){
			vi.removeClass("mute");
		}
	}
});
//volume ico click
$("#jplayer_vmin").click(function(){
	var o=$("span",this);
	if(o.is(".mute")){
		$("#jp").jPlayer("volume",curVolume);
		o.removeClass("mute");
	}else{
		$("#jp").jPlayer("volume",0);
		o.addClass("mute");
	}
	return false;
});

function getPlayList(s){
	var url="/ajax/content";
	$.get(url,{id:$("#hidchannel").val(),t:new Date().getMilliseconds()},function(d){
		skiplock=false;
		myPlayList=eval(d);
		setDefaultSong();
		if(myPlayList.length>1) playListInit(autoStart); // Parameter is a boolean for autoplay. 
		if(typeof(s)!='undefined') s.hide();
	});
}

// function displayPlayList() {
	// $("#jplayer_playlist ul").empty();
	// for (i=0; i < myPlayList.length; i++) {
		// var listItem = (i == myPlayList.length-1) ? "<li class='jplayer_playlist_item_last'>" : "<li>";
		// listItem += "<a href='#' id='jplayer_playlist_item_"+i+"' tabindex='1'>"+ myPlayList[i].name +"</a></li>";
		// $("#jplayer_playlist ul").append(listItem);
		// $("#jplayer_playlist_item_"+i).data( "index", i ).click( function() {
			// var index = $(this).data("index");
			// if (playItem != index) {
				// playListChange(index );
			// } else {
				// $("#jp").jPlayer("play");
			// }
			// $(this).blur();
			// return false;
		// });
		// }
// }

function playListInit(autoplay) {
	if(autoplay) {
		playListChange( playItem );
	} else {
		playListConfig( playItem );
	}
}

function playListConfig( index ) {
	// $("#jplayer_playlist_item_"+playItem).removeClass("jplayer_playlist_current").parent().removeClass("jplayer_playlist_current");
	// $("#jplayer_playlist_item_"+index).addClass("jplayer_playlist_current").parent().addClass("jplayer_playlist_current");
	playItem = index;
	try{
		$("#jp").jPlayer("setFile", myPlayList[playItem][2]);
	}catch(err){
		alert(err.message);
	}
	// $("#nowplaying").text($("a.jplayer_playlist_current").text());
	var songinfos=$("#SongInfo ul li");
	var title=myPlayList[playItem][1];
	var id=myPlayList[playItem][0];
	songinfos.eq(0).text("曲目: "+title);
	songinfos.eq(1).text("歌手: "+myPlayList[playItem][3]);
	songinfos.eq(2).text("专辑: "+myPlayList[playItem][4]);
	$("#fav").data({cursongid:id});
	document.title=title+" - 巴士电台";
	var imgurl=myPlayList[playItem][5];
	var o=$("#coverimg");
	var img=new Image();
	$("img",o).addClass("loading").attr("src","image/loading.gif");
	$(img).load(function(){var t=$(this).attr("title",title).hide();o.find("img").remove().end().append(this);t.fadeIn(1000);}).error(function(){$("img",o).attr({src:"image/default.gif",title:title}).removeClass("loading");}).attr({src:imgurl});
	$("#clipbord").css({bottom:-30}); //clear url clip box
	//light up the fav heart
	var s=$("#fav span").removeClass("star");
	if(getCookie("member_id")=="") return;
	if($("#hidchannel").val()=="99") s.addClass("star");//收藏频道内的歌曲直接加星标
	else $.get("/ajax/ifsc?id="+id+"&t="+new Date().getMilliseconds(),function(d){;if(d=="1") s.addClass("star");});
} 
function playListChange( index ) {
	playListConfig( index );
	try{
		$("#jp").jPlayer("play");
	}catch(err){
		alert(err.message);
	}
} 

$("#jplayer_previous").click( function() {
	playListPrev();
	return false;
});

$("#jplayer_next").click( function() {
	if(skiplock) return;
	playListNext();
	return false;
});
function playListNext() {
	//var index = (playItem+1 < myPlayList.length) ? playItem+1 : 0;//循环的逻辑，电台不循环
	var index;
	if(playItem+1<myPlayList.length){
		index=playItem+1;
		playListChange( index );
	}
	else{
		autoStart=true;//设置在获取新列表后自动播放
		skiplock=true;
		playItem=0;
		getPlayList();
	}
}

function playListPrev() {
	var index = (playItem-1 >= 0) ? playItem-1 : myPlayList.length-1;
	playListChange( index );
}


// function j(m){return $("#jp").jplayer("getData",m);}
// var debug=false;
// $("#toggleDebug").click(function(){debug=!debug;});
// var c=$("#jp").data("jPlayer.config");
// setInterval(function(){
	// var d="";
	// if(debug){
		// d="<br/>support mp3:"+c.canPlayMP3;
		// d+="<br/>support ogg:"+c.canPlayOGG;
		// d+="<br/>html5:"+c.html5;
		// d+="<br/>usingFlash:"+c.usingFlash;
		// d+="<br/>usingMP3:"+c.usingMP3;
		// d+="<br/>swf path:"+c.swf;
		// d+="<br/>swfVersionRequired:"+c.swfVersionRequired;
		// d+="<br/>swfVersion:"+c.swfVersion;
		// d+="<br/>loadPercentage:"+c.diag.loadPercent+"%";
		// d+="<br/>playedTime:"+c.diag.playedTime;
		// d+="<br/>src:"+c.diag.src;
	// }
	// $("#debugdiv span").html(d);
// },100);
	//因为要用到getplaylist函数，为了避免变量全部移出来，干脆把切换频道控制方法写到这里面来
	$("#channel-list li").click(function(){
		var o=$(this);
		if(o.is(".cur")) return;//不响应当前频道的点击操作
		var cc=o.attr("cid");
		if(cc=="99"){//私人频道逻辑
			if(getCookie("member_id")==""){
				$("#user_ico").trigger("click");
				return;
			}
		}
		$("#hidchannel").val(cc);
		$(".cur").removeClass("cur");
		o.addClass("cur");
		var s=$(".sidewidget").show();
		autoStart=true;
		getPlayList(s);
		});
	//设定外链歌曲播放
	function setDefaultSong(){
		var o=$("#hidPriPlay");
		if(o.length>0){
			//从页面得到外链歌曲信息：
			var s=[];
			s.push(o.attr('sid'));
			s.push($(".hpp_title",o).text());
			s.push($(".hpp_url",o).text());
			s.push($(".hpp_artist",o).text());
			s.push($(".hpp_album",o).text());
			s.push($(".hpp_thumb",o).text());
			myPlayList.unshift(s);
			o.remove();
		}
		//设定频道
		var cid=$("#hidchannel").val();
		$("#channel-list li[cid="+cid+"]").addClass("cur");
	}
});