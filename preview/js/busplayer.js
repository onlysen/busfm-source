﻿var myPlayList, playItem = 0, isie9=$.browser.msie&&$.browser.version=="9.0";
$(function () {
    var curVolume = 0.8, autoStart = true, skiplock = true; //跳曲开关
    // localStorage.clear();
    $("#jp").jPlayer({
        ready: function () {
            // displayPlayList(); 
            getPlayList();
        },
        ended: function () {
            playListNext();
        },
        timeupdate: function (e) {
            $(".j-seek-bar").width(e.jPlayer.status.seekPercent + "%");
            $(".j-play-bar").width(e.jPlayer.status.currentPercentAbsolute + "%");
        },
        pause: function () { setWave(); },
        play: function () { setWave(true); },
        error: function (e) {
            if (e.jPlayer.error.type == $.jPlayer.error.URL) {
                playListNext();
            }
        },
        preload: "auto",
        solution: "html,flash",
        backgroundColor: "transparent",
        supplied: "mp3",
        warningAlerts: true,
        errorAlerts: false,
        cssSelector: { play: "#jplayer_play", pause: "#jplayer_pause", stop: "#jplayer_stop", currentTime: "#pt", duration: "#tt", volumeBar: "#jplayer_vbar", volumeBarValue: "#vamount" },
        cssSelectorAncestor: "",
        swfPath: "js"
    });
    //volume bar click
    $("#jplayer_vbar,#jpalyer_v_wrap").click(function (e) {
        var o=$(this);
        var oft = o.offset();
        var v = e.clientX - oft.left;
        curVolume = v / (o.width());
        if(o.is("#jpalyer_v_wrap")) $("#jp").jPlayer("volume",curVolume);
        if (curVolume > 0) {
            var vi = $("#jplayer_vmin span");
            if (vi.is(".mute")) {
                vi.removeClass("mute");
            }
        }
    });
    //volume ico click
    $("#jplayer_vmin").click(function () {
        var o = $("span", this);
        if (o.is(".mute")) {
            $("#jp").jPlayer("volume", curVolume);
            o.removeClass("mute");
        } else {
            $("#jp").jPlayer("volume", 0);
            o.addClass("mute");
        }
        return false;
    });
    //volume mousewheel
    $("#player").mousewheel(function(e,d){
        curVolume=curVolume+d*0.05;
        var vicon=$("#jplayer_vmin span");
        if(curVolume<0){curVolume=0;$("#jp").jPlayer("mute");vicon.addClass("mute");return;}
        if(vicon.is(".mute")){$("#jp").jPlayer("unmute");vicon.removeClass("mute");}
        if(curVolume>1) curVolume=1;
        $("#jp").jPlayer("volume",curVolume);
        return false;
    });
    function getPlayList(isnotinit) {
        var url = "/ajax/content";
        $.get(url, { id: $("#hidchannel").val(), t: new Date().getMilliseconds() }, function (d) {
            skiplock = false;
            myPlayList = eval(d);
            setDefaultSong();
			var isinit=true;
			if(isnotinit) isinit=false;
            if (myPlayList.length > 1) playListInit(autoStart,isinit); // Parameter is a boolean for autoplay. 
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

    function playListInit(autoplay,isinit) {
        if (autoplay) {
            playListChange(playItem, isinit);
        } else {
            playListConfig(playItem, isinit);
        }
    }

    function playListConfig(index, isinit) {
        // $("#jplayer_playlist_item_"+playItem).removeClass("jplayer_playlist_current").parent().removeClass("jplayer_playlist_current");
        // $("#jplayer_playlist_item_"+index).addClass("jplayer_playlist_current").parent().addClass("jplayer_playlist_current");
        playItem = index;
        try {
            $("#jp").jPlayer("setMedia", { mp3: myPlayList[playItem][2] });
        } catch (err) {
            alert(err.message);
        }
        var title = myPlayList[playItem][1];
        var id = myPlayList[playItem][0];
        $("#s-title").text(title);
        $("#s-artist").text(myPlayList[playItem][3]);
        $("#s-album").text(myPlayList[playItem][4]);
		$("#clipbord .mp3url").val(myPlayList[playItem][6]);
        $("#fav").data({ cursongid: id });
		setSongMeta(title);
        document.title = title + " - 巴士电台";
        var imgurl = myPlayList[playItem][5];
        var o = $("#rightcover .cover-img").eq(0).empty().append($("<img/>",{src:"img/loading.gif",class:"loading"}));
        var img = new Image();
        $(img).load(function () { var t = $(this).attr("title", title).hide(); o.empty().append(this); t.fadeIn(1000,function(){try{$(this).reflect({height:.1,opacity:.2});}catch(e){}}); }).error(function () { $("img", o).attr({ src: "img/default_l.png", title: title }).removeClass("loading"); }).attr({ src: imgurl });
        $("#clipbord").animate({opacity:"hide"},500);//clear url clip box
        songHistory(isinit); //添加到历史
        //light up the fav heart
        var s = $("#fav span").removeClass("star");
        if (getCookie("member_id") != "") {
            if ($("#hidchannel").val() == "99") { s.addClass("star"); if (isie9) { if (window.external.msIsSiteMode()) setFavBtnState(true); } } //收藏频道内的歌曲直接加星标
            else $.get("/ajax/ifsc?id=" + id + "&t=" + new Date().getMilliseconds(), function (d) { if (d == "1") s.addClass("star"); if (isie9) setFavBtnState(d == "1"); });
        }
        getAPIdata(title, myPlayList[playItem][3], myPlayList[playItem][4]); //获取歌曲站外数据
    }
    //记录播放历史
    function songHistory(isinit) {
        if (typeof localStorage == "undefined") return;
        var sh = [];
        if (localStorage.songlist != null) {
            sh = localStorage.songlist;
            try{
                sh = eval(sh);
            }catch(err){
                sh=[];
            }
        }
        if (isinit && sh.length > 0) {//初次加载有一首歌或以上，就全部显示到页面，有别于播放过程中只推入当前歌曲数据
            arrayUnique(sh);
            $.each(sh, function (i, m) { setLocalStorage(m.split(',')); });
        }
        var newsong = [];
        $.each(myPlayList[playItem], function (i, m) { newsong.push(m.replace(/,/g, '-')); });
        sh.push(newsong.toString());
        if (sh.length > 24) sh.shift(); //数据只保留24首
        localStorage.songlist="";
        for (var i = 0; i < sh.length; ) {
            localStorage.songlist += '"' + sh[i].toString() + '"';
            if (++i < sh.length) localStorage.songlist += ',';
        }
        localStorage.songlist = '[' + localStorage.songlist + ']';
        if (!isinit && sh.length > 1) {//播放过程中，把上一首推入页面
            var el = sh[sh.length - 2].split(',');
            setLocalStorage(el);
        }
    }
    function setLocalStorage(el) {
        var img = new Image();
        var ms = $("#song-history div");
        $(img).load(function () { ms.filter(function (i) { return i <= ms.length - 4; }).remove();if($("#alumn-wall img").length==24) $("#alumn-wall li:first").html("").appendTo($("#alumn-wall")); t = $(this).css("opacity", 0.6).appendTo($("#song-history")).reflect({opacity:.2}).clone().data({ id: el[0], title: el[1], artist: el[3], album: el[4] }).appendTo($("#alumn-wall li").filter(function(){return $("img",this).length==0;}).eq(0)); }).error(function () { $(img).attr("src", "img/default_m.png"); }).attr("src", el[5])
	.data({ id: el[0], title: el[1], artist: el[3], album: el[4] });
    }
    function playListChange(index, isinit) {
        playListConfig(index, isinit);
        try {
            $("#jp").jPlayer("play");
        } catch (err) {
            alert(err.message);
        }
    }

    // $("#jplayer_previous").click( function() {
    // playListPrev();
    // return false;
    // });
    $("#jplayer_pause,#jplayer_play").click(function () { if (isie9) { if (window.external.msIsSiteMode()) setPlayBtnState($("#jplayer_pause").is(":hidden")); } });
    $("#jplayer_next").click(function () {
        if (skiplock) return;
        playListNext();
        return false;
    });
    function playListNext() {
        //var index = (playItem+1 < myPlayList.length) ? playItem+1 : 0;//循环的逻辑，电台不循环
        var index;
        if (playItem + 1 < myPlayList.length) {
            index = playItem + 1;
            playListChange(index);
        }
        else {
            autoStart = true; //设置在获取新列表后自动播放
            skiplock = true;
            playItem = 0;
            getPlayList(true);//送入true都表示不是第一次调用
        }
    }

    function playListPrev() {
        var index = (playItem - 1 >= 0) ? playItem - 1 : myPlayList.length - 1;
        playListChange(index);
    }

    //切换频道
    $("#channellist span").live("click", function () {
        var o = $(this);
        var cc = o.attr("cid");
        if (o.is(".cur")) return; //不响应当前频道的点击操作
        if (cc == "99"&&getCookie("member_id") == "") return;//私人频道逻辑
        $(".wavestatic").removeClass("wavestatic");
        $("#hidchannel").val(cc);
        $(".cur").removeClass("cur");
        o.addClass("cur");
        autoStart = true;
        getPlayList(true);
    });
    //设定外链歌曲播放
    function setDefaultSong() {
        var o = $("#hidPriPlay");
        if (o.length > 0) {
            //从页面得到外链歌曲信息：
            var s = [];
            s.push(o.attr('sid'));
            s.push($(".hpp_title", o).text());
            s.push($(".hpp_url", o).text());
            s.push($(".hpp_artist", o).text());
            s.push($(".hpp_album", o).text());
            s.push($(".hpp_thumb", o).text());
            myPlayList.unshift(s);
            o.remove();
        }
        //设定频道
        var cid = $("#hidchannel").val();
        $("#channellist span[cid=" + cid + "]").addClass("cur wavestatic");
    }
    $("#khclose").click(function () { KeyHelper(); });
    $("#keyhelp").click(function (e) { e.stopPropagation(); });
    $("body").not("#keyhelp").not("#openkeydialog").click(function () { KeyHelper(); });
    $("#openkeydialog").click(function (e) { KeyHelper($("#keyhelp").is(":hidden")); e.stopPropagation(); }); //页面上的快捷键按钮
    //快捷键
    $("*").live("keydown",function (e) {
        e.stopPropagation();
        var o = $(this);
        var k = e.which; //37-40,左上右下, 32space, 76l, 83s,78n,80p,27esc,77m,70f,191/,85u,68d,87w,65a,66b
        if ((o.is(":input") || o.is("textarea"))) return; //输入的时候禁用键盘操作
        //painteggs
        if (k == 38 && $.isEmptyObject($("body").data("egg"))) {
            $("body").data({ egg: "|" });
            setTimeout(function () { $("body").removeData("egg"); }, 2000);
        }
        if (!$.isEmptyObject($("body").data("egg"))) {
            var ks = $("body").data("egg") + "|" + k;
            if (ks == "||38|38|40|40|37|39|37|39|66|65") {//trigger eggspaint
                triggerEggs();
                $("body").removeData("egg");
            } else $("body").data({ egg: ks });
        }
        var hidtxt = $("<input/>", { type: "text", style: "width:0;border:none;color:transparent;" }).appendTo("body").focus();
        setTimeout(function () { hidtxt.remove(); }, 300); //修复与firefox的quick search功能的冲突
        if (k == 38 || k == 39 || k == 40 || k == 80) {
            if (!e.shiftKey) return; //这几个键需要shift配合
        }
        switch (k) {
            case 32:
            case 80: //播放、暂停
                setPlay();
                break;
            case 70: //收藏
                $("#fav").trigger("click");
                keyTip("收藏");
                break;
            case 83: //分享
                if($("#clipbord").is(":hidden")) keyTip("分享");
				$("#share").trigger("click");
                break;
            case 39:
            case 78: //跳曲
                playListNext();//$("#jplayer_next").trigger("click");
                keyTip("下一曲");
                break;
            case 85:
            case 38: //音量增
                setVolume(true);
                keyTip("音量增");
                break;
            case 68:
            case 40: //音量减
                setVolume();
                keyTip("音量减");
                break;
            case 77: //静音
                $("#jplayer_vmin").trigger("click");
                if ($("#jplayer_vmin span").is(".mute")) keyTip("静音开");
                else keyTip("静音关");
                break;
            case 191: //toggle提示窗口
                KeyHelper($("#keyhelp").is(":hidden"));
                break;
            case 87:
                jumpDiary();
                break;
            case 27:
                KeyHelper(false);
                break;
            default:
                break;
        }
    });
    function setPlay() {
        if ($("#jplayer_play").css("display")=="none") { $("#jp").jPlayer("pause"); keyTip("暂停"); }
        else { $("#jp").jPlayer("play"); keyTip("播放"); }
    }
    //音量增减，dir=true增
    function setVolume(dir) {
        if (dir) {//增
            curVolume += 0.1; //
            if (curVolume > 1) curVolume = 1;
        } else {
            curVolume -= 0.1;
            if (curVolume < 0) curVolume = 0;
        }
        $("#jp").jPlayer("volume", curVolume);
    }
    function jumpDiary(){
        if($("#actlogin").is(":visible")) $("#actlogin").trigger("click");
        else location.href="#diary";
    }
    //键盘动作屏幕提示
    function keyTip(text) {
        $("#keyaction").text(text).show().stop(true, true).animate({ opacity: 1 }, 1000, function () { $(this).animate({ opacity: 0 }, 500, function () { $(this).hide(); }); });
    }

    //得到外站音乐API数据
    function getAPIdata(title, artist, album) {
        var q = encodeURIComponent(title + ", " + artist + ", " + album);
        var m = ""; //"<b>"+album+"</b><br/>";
        var o = $("#metas").html(m);
        var yahoonodata = false;
        //雅虎
        var url = 'http://query.yahooapis.com/v1/public/yql?q=select * from music.track.search where keyword="' + q + '"';
        $.get(url, { format: "json", diagnostics: "false" }, function (d) {
            if (d.query.count > 0) {
                var s = d.query.results.Track;
                if ($.isArray(s)) s = s[0];
                m += "&copy;&nbsp;" + s.releaseYear + "&nbsp;" + (s.label || "");
                if (s.Category != undefined) {
                    m += "<br/>" + parseGenres(s.Category);
                }
                m += "<br/>Album: ";
                if (s.Album != null) m += "<a href='" + s.Album.Release.url + "' target='_blank'>Yahoo</a>";
            }
            else {
                yahoonodata = true;
                songdebug(1, title);
            }
            //豆瓣
            url = 'http://api.douban.com/music/subjects?q=' + q;
            $.get(url, { alt: "xd", "max-results": "1" }, function (d) {
                if (d.entry.length > 0 && checkDouban(d.entry[0], artist, album)) {
                    if (yahoonodata) {//雅虎没查到数据，则由豆瓣读出来
                        m += parseDoubanAttr(d.entry[0]["db:attribute"]) + "<br/>Album: ";
                    }
                    m += "<a href='" + d.entry[0].link[1]["@href"] + "' target='_blank'>Douban</a>"
                }
                o.html(m);
				setSongMeta();
            }, "jsonp");
            o.html(m);
        }, "jsonp");
    }
    //处理流派
    function parseGenres(Category) {
        var r = "Categories: ";
        if ($.isArray(Category)) {
            for (var i = 0; i < Category.length; ) {
                r += Category[i].name;
                if (++i < Category.length) r += ", ";
            }
        } else {
            r += Category.name;
        }
        return r;
    }
    //解析豆瓣专辑属性API
    function parseDoubanAttr(attribute) {
        var y = p = "";
        $.each(attribute, function (i, m) {
            if (m["@name"] == "pubdate") y = m.$t.replace(/-.*/g, '');
            if (m["@name"] == "publisher") p = m.$t;
        });
        return "&copy;&nbsp;" + y + "&nbsp;" + p;
    }
    //检查豆瓣数据有效性
    function checkDouban(entry, artist, album) {
        if (entry.title.$t.toLowerCase() != album.toLowerCase()) { songdebug(2, album, entry.title.$t); return false; } //专辑名不匹配
        var author = entry.author;
        var f = false;
        $.each(author, function (i, m) {
            var d = m.name.$t.toLowerCase();
            var t = artist.toLowerCase();
            if (d.search(t) > -1 || t.search(d) > -1) f = true;
        });
        if (!f) { songdebug(2, artist, author[0].name.$t); return false; } //乐队名不匹配
        return true;
    }
    //开发阶段把数据记到本地以便定时上传到服务器
    function songdebug(type, title, dou) {
        if (typeof localStorage != "undefined") {
            localStorage.removeItem("yahoo");
            localStorage.removeItem("douban");
            // if(type==1){//yahoo无匹配
            // if(localStorage.yahoo==null) localStorage.yahoo="";
            // localStorage.yahoo+=title+"|";
            // }
            // else{//豆瓣无匹配
            // if(localStorage.douban==null) localStorage.douban="";
            // localStorage.douban+=title+"##"+dou+"|";
            // }
        }
    }
    //IE8Fix
    if (!Array.prototype.lastIndexOf) {
        Array.prototype.lastIndexOf = function (elt /*, from*/) {
            var len = this.length;

            var from = Number(arguments[1]);
            if (isNaN(from)) {
                from = len - 1;
            }
            else {
                from = (from < 0)
           ? Math.ceil(from)
           : Math.floor(from);
                if (from < 0)
                    from += len;
                else if (from >= len)
                    from = len - 1;
            }

            for (; from > -1; from--) {
                if (from in this &&
          this[from] === elt)
                    return from;
            }
            return -1;
        };
    }

});
//关闭快捷键窗口
function KeyHelper(isopen){
	var k=$("#keyhelp");
	if(isopen) k.show().animate({"top":-10},300);
	else k.animate({"top":-290},300,function(){$(this).hide();});
}
function triggerEggs(){alert('彩蛋');}
//音波动画
function setWave(animate){var a=animate||false; if(!animate) $("span.cur").addClass("wavestatic"); else $(".wavestatic").removeClass("wavestatic");}
//倒影
function myReflect(el,flag){var f=flag||false;if(flag)$(el).reflect();else $(el).reflect({height:1.3});}
//把当前歌曲信息同步到唱片墙页面
function setSongMeta(title){
	//#s-artist,#s-album
	var sm=$("#m-song-meta");
	if(title){
		$(".msmeta-title",sm).html(title);
		$(".msmeta-detail",sm).html("");
		return;
	}
	//$(".msmeta-title",sm).html($("#s-title").html());
	$(".msmeta-detail",sm).html($("#metas").html());
}