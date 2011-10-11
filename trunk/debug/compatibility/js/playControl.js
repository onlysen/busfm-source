$(function(){
$("#jp").jPlayer({
	ready:function(){
		// displayPlayList(); 
		getPlayList();
		// $(this).jPlayer("setMedia", {mp3:"http://www.jplayer.org/audio/mp3/Miaow-07-Bubble.mp3"}).jPlayer("play");
	},
	ended:function(){
		$(this).jPlayer("play");
		//playListNext();
	},
	timeupdate:function(e){
		$("#msg").text(e.jPlayer.status.seekPercent);
	},
	error:function(e){
		//if(e.jPlayer.error.type==$.jPlayer.error.URL){
			//// console.log("wrong mp3 url");
			//// playListNext();
		//}
		var l="";
		l+="<span class='red'>err type: </span>"+e.jPlayer.error.type+"<br/>";
		l+="<span class='red'>err cause: </span>"+e.jPlayer.error.context.substring(0,20) +"...(hide)<br/>";
		l+="<span class='red'>err msg: </span>"+e.jPlayer.error.message+"<br/>";
		l+="<span class='red'>suggest: </span>"+e.jPlayer.error.hint+"<br/>";
		$("#errlog").html(l);

	},
	preload:"auto",
	supplied: "mp3",
	warningAlerts:true,
	errorAlerts:false,
	cssSelector:{play:"#jplayer_play",pause:"#jplayer_pause",stop:"#jplayer_stop",volumeBar:"#jplayer_vbar",volumeBarValue:"#vamount",currentTime:"#pt",duration:"#tt"},
	cssSelectorAncestor:"",
	swfPath: "/debug/compatibility/js"
});
var playItem = 0;
var myPlayList=[];
var myPlayList_luo=[["2220","Like Steps in a Dance","http://ftp.luoo.net/radio/radio208/02.mp3","Anchor & Braille","Felt","http://img3.douban.com/lpic/s3891661.jpg"],["1790","Kissing You Goodbye","http://ftp.luoo.net/radio/radio163/04.mp3","The Used","Artwork","http://img2.douban.com/lpic/s3867383.jpg"],["1127","Last Stand","http://ftp.luoo.net/radio/radio101/11.mp3","Adelitas Way","Adelitas Way","http://t.douban.com/lpic/s3879973.jpg"],["1724","Ode to L.A.","http://ftp.luoo.net/radio/radio156/05.mp3","The Raveonettes","Pretty in Black","http://t.douban.com/lpic/s3327446.jpg"],["61","Gravity","http://ftp.luoo.net/radio/radio5/01.mp3","Embrace","Out Of Nothing","http://img3.douban.com/lpic/s1417096.jpg"],["8","The Slow Build","http://ftp.luoo.net/radio/radio1/1.mp3","Duels","The Bright Lights And What I Should Have Learned","http://bus.fm/admin/uploads/20101109234044_hunoct.jpg"],["1897","I Don’t Love You","http://ftp.luoo.net/radio/radio174/05.mp3","My Chemical Romance","The Black Parade","http://img2.douban.com/lpic/s2871182.jpg"],["1355","万花筒","http://ftp.luoo.net/radio/radio123/07.mp3","节日乐队","万花筒","http://img5.douban.com/lpic/s3404735.jpg"],["736","Young Folks","http://ftp.luoo.net/radio/radio65/13.mp3","Peter Bjorn And John","Writer’s Block","http://otho.douban.com/lpic/s2361553.jpg"],["2593","你那好冷的小手","http://ftp.luoo.net/radio/radio999/03.mp3","南方二重唱","有些话不能说","http://img3.douban.com/lpic/s1428600.jpg"]];
var myPlayList_out=[["2220","she","http://www.yxbhh.org/cd/%C1%F7%D0%D0/%D2%F4%C0%D6%CC%EC%CC%C3/17/She.mp3","suede","live at denmark","http://img3.douban.com/lpic/s3891661.jpg"],["1790","parachutes","http://arts.uwaterloo.ca/~crcproj/PPDB/PHPFiles/coldplay_beautiful_world.mp3","coldplay","parachutes","http://img2.douban.com/lpic/s3867383.jpg"],["1127","shade and honey","http://thepassingstatic.com/audio/Sparklehorse%20-%20Shade%20and%20Honey.mp3","sparklehorse","dreamt for light years in the belly of a ","http://t.douban.com/lpic/s3879973.jpg"]];
var curVolume=80;
var autoStart=true;
var skiplock=false;//跳曲开关

function getPlayList(){
	// var url="/ajax/content";
	// $.get(url,{id:$("#hidchannel").val(),t:new Date().getMilliseconds()},function(d){
		// skiplock=false;
		// myPlayList=eval(d);
		// setDefaultSong();
		if(myPlayList.length>1) playListInit(autoStart); // Parameter is a boolean for autoplay. 
		else $("#c1").trigger("click");
	// });
}


function playListInit(autoplay) {
	if(autoplay) {
		playListChange( playItem );
	} else {
		playListConfig( playItem );
	}
}

function playListConfig( index ) {
	playItem = index;
	try{
		$("#jp").jPlayer("setMedia", {mp3:myPlayList[playItem][2]});
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
} 
function playListChange( index ) {
	playListConfig( index );
	try{
		$("#jp").jPlayer("play");
	}catch(err){
		alert(err.message);
	}
	$("#errlog").html("");
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
	$("#debug").jPlayerInspector({jPlayer:$("#jp")});

$("#c1").click(function(){myPlayList=myPlayList_luo;playListChange(0);$("#source span").hide().eq(0).show();return false;});
$("#c2").click(function(){myPlayList=myPlayList_out;playListChange(0);$("#source span").hide().eq(1).show();return false;});
});