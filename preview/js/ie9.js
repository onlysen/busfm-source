//IE9 api
var ibtnPlay,btnFav,btnNext,styleFav,styleUnfav,stylePlay,stylePause;
function AddJumpList() {
	window.external.msSiteModeCreateJumplist('相关网站');
	window.external.msSiteModeAddJumpListItem('落网电台', 'http://luoo.net', 'http://luoo.net/favicon.ico');
	window.external.msSiteModeShowJumplist();
}
function addFuncBtn(){	
	document.addEventListener('msthumbnailclick', onButtonClicked, false); 
	btnPlay = window.external.msSiteModeAddThumbBarButton('http://v1.bus.fm/image/pin/playback_pause.ico', '暂停'); 
	btnFav = window.external.msSiteModeAddThumbBarButton('http://v1.bus.fm/image/pin/star_fav_empty.ico', '收藏'); 
	btnNext = window.external.msSiteModeAddThumbBarButton('http://v1.bus.fm/image/pin/playback_next.ico', '跳过'); 
	window.external.msSiteModeShowThumbBar(); 
}
function addButtonStyles(){
	styleFav = window.external.msSiteModeAddButtonStyle(btnFav,'http://v1.bus.fm/image/pin/star_fav.ico', '收藏');
	styleUnfav = window.external.msSiteModeAddButtonStyle(btnFav,'http://v1.bus.fm/image/pin/star_fav_empty.ico', '取消收藏');
	stylePlay = window.external.msSiteModeAddButtonStyle(btnPlay,'http://v1.bus.fm/image/pin/playback_play.ico', '播放');
	stylePause = window.external.msSiteModeAddButtonStyle(btnPlay,'http://v1.bus.fm/image/pin/playback_pause.ico', '暂停');
} 
function setFavBtnState(isFav){
	// if (!isie9||!window.external.msIsSiteMode())  return;
	if(isFav) window.external.msSiteModeShowButtonStyle(btnFav, styleFav);
	else window.external.msSiteModeShowButtonStyle(btnFav, styleUnfav);
}
function setPlayBtnState(isPlay){
	// if (!isie9||!window.external.msIsSiteMode())  return;
	if(isPlay) window.external.msSiteModeShowButtonStyle(btnPlay, stylePause);
	else window.external.msSiteModeShowButtonStyle(btnPlay, stylePlay);
}
function hideButtons(isPlay) {
	window.external.msSiteModeUpdateThumbBarButton(btnPlay, true, !isPlay);
	window.external.msSiteModeUpdateThumbBarButton(btnPause, true, isPlay);
}
function onButtonClicked(e){
	switch(e.buttonID){
		case btnPlay:
		if($("#jplayer_pause").is(":hidden"))$("#jplayer_play").trigger("click");
		else $("#jplayer_pause").trigger("click");
		break;
		case btnFav:
		$("#fav").trigger("click");
		break;
		case btnNext:
		$("#jplayer_next").trigger("click");
		break;
	}
}
if (isie9 && window.external.msIsSiteMode()) {
		AddJumpList();
		addFuncBtn();
		addButtonStyles();
}