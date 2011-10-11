///
///在用户使用IE8以下或在Win7/vista下使用IE9以下浏览时运行
///运行时弹出一个精仿系统提示的提示条，引导用户下载最新版本IE
///关闭提示后刷新页面不会再次提示，除非关闭浏览器再打开
///关闭提示后若要重新显示请关闭IE后清空Cookie
///这样做是为了避免同一网站多个页面每页都需要去点击关闭
///自动弹出和浏览器匹配的语种的提示，自动匹配用统当前主题的色彩方案
///目前支持英语、汉语繁简、西班牙语、法语、波兰语、日语、意大利语、瑞典语
///简体中文XP用户匹配下载雨林木风版
///Win95、98、ME用户匹配Firefox
///WinNT、2K、XP用户匹配IE8
///Win7、Vista用户匹配IE9 Beta版
///
///代码可随意使用，但请务必将该进意见和建议提交到我的博客,我将感之不尽。http://hi.baidu.com/gucong/
///2010/09/29 04:26:29
///

//判断是否IE
if(window.ActiveXObject && document.cookie.indexOf("ieupdate") < 0){
	
	//申请命名空间
	window.ieup = {};
	//记录当前脚本所在path
	ieup.path = document.scripts[document.scripts.length - 1];

	//用户提示信息
	ieup.msgStr = {};

	//各语言版本的提示信息
	ieup.msgStr["en"] = "Internet Explorer is missing updates required to view this site. Click here to update";
	ieup.msgStr["zh-cn"] = "Internet Explorer 检测到可用更新版本，为了保护您的安全，建议您立即升级。单击此处查看详细信息";
	ieup.msgStr["zh-tw"] = "Internet Explorer 檢測到可用更新版本，為了保護您的安全，建議您立即升級。單擊此處查看詳細信息";
	ieup.msgStr["es"] = "Usted está usando un navegador obsoleto.Para navegar mejor por este sitio, por favor, actualice su navegador";
	ieup.msgStr["fr"] = "Vous utilisez un navigateur dépassé! Pour une meilleure expérience web, prenez le temps de mettre votre navigateur à jour";
	ieup.msgStr["pt"] = "Você está usando um navegador desatualizado.Para navegar melhor neste site, por favor, atualize seu navegador";
	ieup.msgStr["ja"] = "あなたは旧式ブラウザをご利用中です。このウェブサイトを快適に閲覧するにはブラウザをアップグレードしてください。";
	ieup.msgStr["it"] = "Stai usando un browser obsoleto.Per una migliore navigazione su questo sito, per cortesia passa ad un browser di ultima generazione";
	ieup.msgStr["sv"] = "Du anv?nder en f?r?ldrad webbl?sare.F?r en b?ttre upplevelse p? denna webbplats, v?nligen byt till en modern webbl?sare";

	window.attachEvent("onload", function() {
		eval(ieup.path.innerHTML);
		var lang = navigator.browserLanguage.toLowerCase();

		var path = ieup.path.src;
		path = path.substr(0, path.lastIndexOf("/") + 1);

		var msgStr = ieup.msgStr;
		msgStr = (msgStr[lang] || msgStr[lang.substr(0, 2)] || msgStr["en"] || msgStr) + "...";

		//建立所需对象的缩写
		var doc = document;
		var html = doc.documentElement;
		var body = doc.body;
		var cre = doc.createElement;

		//保存html、body的设置以便关闭提示条时恢复
		var bScroll = body.scroll;
		var hOverflow = html.style.overflow;
		var bMargin = body.style.margin;
		//var bHeight = body.style.height;
		//var hMargin = html.style.margin;
		//var hHeight = html.style.height;

		//创建需添进页面的对象
		var msg = cre("div");
		var info = cre("div");
		var wrap = cre("div");
		var innerWrap = cre("div");
		var exit = cre("img");

		//取出页面所有元素添加进innerWrap
		innerWrap.innerHTML = body.innerHTML;
		body.innerHTML = "";

		//组织对象嵌套关系
		body.appendChild(msg);
		body.appendChild(wrap);
		wrap.appendChild(innerWrap);
		msg.appendChild(info);
		msg.appendChild(exit);

		//设置提示信息样式
		info.innerText = "M";
		with(info.style){
			cursor = "default";
			textAlign = "left";
			overflow = "visible";
			padding = "3px 1.8em";
			borderBottom = "1px solid threedshadow";
			font = '9pt "Microsoft YaHei", SimSun';
		}

		//设定提示信息
		info.innerText = msgStr;

		//设置关闭按钮
		exit.src = path + "close.gif";
		with(exit.style){
			position = "absolute";
			pixelTop = pixelRight = 3;
		}

		//设置提示条样式
		with(msg.style){
			background = "url(" + path + "security.gif) no-repeat 3px 3px";
			borderBottom = "1px solid threeddarkshadow";
			position = "absolute";
			top = right = 0;
			width = "100%";
		}

		//设置页面内容的新容器的样式
		with(wrap.style){
			width = "100%";
			left = 0;
			overflow = "auto";
			position = "absolute";
		}

		//设置页面内容留白
		with(innerWrap.style){
			margin = body.currentStyle.margin;
			padding = body.currentStyle.padding
		}

		//去处IE原有纵向滚动条
		html.style.overflow = "hidden";
		//html.style.height = body.style.height = "100%";

		//用户点击提示条事件
		info.onclick = function(){
			//window.open(url);
			var w, h;
			if(document.body.style.maxWidth == undefined){
				w = 1288;
				h = 560;
			}else{
				w = 1280;
				h = 500;
			}
			var args = "help=0;scroll=0;center=1;status=0;location=0;dialogWidth=" + w + "px;dialogheight=" + h + "px";
			var showDialog = window.showModelessDialog || window.showModalDialog || window.open;
			showDialog(path + "choice.html", ieup, args);
		}

		//用户点击关闭按钮事件
		exit.onclick = function(){
			//关闭提示条时恢复html、body的设置
			body.scroll = bScroll;
			html.style.overflow = hOverflow;
			body.style.margin = bMargin;
			
			document.cookie = "ieupdate=closed; path=/";

			//将页面内容移回body
			body.innerHTML = innerWrap.innerHTML;
			window.detachEvent("onresize", resize);
		}

		//组织提示条、页面新容器位置、宽、高
		function resize(){
			//wrap.style.top = 0;
			//wrap.style.height = "100%";
			wrap.style.pixelTop = msg.offsetHeight;
			wrap.style.pixelHeight = html.offsetHeight - msg.offsetHeight;
		}

		//设置焦点时样式
		function focus(){
			 with(msg.style){
				 backgroundColor = "highlight";
				 exit.style.filter = "Invert()";
				 color = "highlighttext";
			 }
		}

		//设置平时样式
		function blur(){
			 with(msg.style){
				 backgroundColor = "infobackground";
				 exit.style.filter = "";
				 color = "infotext";
			 }
		}

		msg.onfocus = msg.onmouseover = focus;
		msg.onblur = msg.onmouseout = blur;
		window.attachEvent("onresize", resize);
		blur();
		resize();

		if(window.XMLHttpRequest){
		/*@cc_on
        @if (@_jscript_version >5.7)
			exit.src = "data:image/gif;base64,R0lGODlhEAAQAJEAAAAAAP///////wAAACH5BAEAAAIALAAAAAAQABAAAAIalI+py+0PFQBoNivwnRRqx3Wb8SVfGaXqmhQAOw==";
        @end
        @*/
		}else{
			//为IE5去除原有滚动条
			body.scroll = "no";
			//为IE5、IE6的<body>设置水平外边距0，设置优先使用图片缓存
			body.style.marginLeft = body.style.marginRight = 0;
			document.execCommand("BackgroundImageCache", false, true);
		}

		new Image().src = path + "ie.png";
		new Image().src = path + "opera.png";
		new Image().src = path + "safari.png";
		new Image().src = path + "chrome.png";
		new Image().src = path + "firefox.png";

	});
}
