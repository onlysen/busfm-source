///
///���û�ʹ��IE8���»���Win7/vista��ʹ��IE9�������ʱ����
///����ʱ����һ������ϵͳ��ʾ����ʾ���������û��������°汾IE
///�ر���ʾ��ˢ��ҳ�治���ٴ���ʾ�����ǹر�������ٴ�
///�ر���ʾ����Ҫ������ʾ��ر�IE�����Cookie
///��������Ϊ�˱���ͬһ��վ���ҳ��ÿҳ����Ҫȥ����ر�
///�Զ������������ƥ������ֵ���ʾ���Զ�ƥ����ͳ��ǰ�����ɫ�ʷ���
///Ŀǰ֧��Ӣ����ﷱ�������������������������������
///��������XP�û�ƥ����������ľ���
///Win95��98��ME�û�ƥ��Firefox
///WinNT��2K��XP�û�ƥ��IE8
///Win7��Vista�û�ƥ��IE9 Beta��
///
///���������ʹ�ã�������ؽ��ý�����ͽ����ύ���ҵĲ���,�ҽ���֮������http://hi.baidu.com/gucong/
///2010/09/29 04:26:29
///

//�ж��Ƿ�IE
if(window.ActiveXObject && document.cookie.indexOf("ieupdate") < 0){
	
	//���������ռ�
	window.ieup = {};
	//��¼��ǰ�ű�����path
	ieup.path = document.scripts[document.scripts.length - 1];

	//�û���ʾ��Ϣ
	ieup.msgStr = {};

	//�����԰汾����ʾ��Ϣ
	ieup.msgStr["en"] = "Internet Explorer is missing updates required to view this site. Click here to update";
	ieup.msgStr["zh-cn"] = "Internet Explorer ��⵽���ø��°汾��Ϊ�˱������İ�ȫ�����������������������˴��鿴��ϸ��Ϣ";
	ieup.msgStr["zh-tw"] = "Internet Explorer �z�y�����ø��°汾�����˱��o���İ�ȫ�����h�������������Γ���̎�鿴Ԕ����Ϣ";
	ieup.msgStr["es"] = "Usted est�� usando un navegador obsoleto.Para navegar mejor por este sitio, por favor, actualice su navegador";
	ieup.msgStr["fr"] = "Vous utilisez un navigateur d��pass��! Pour une meilleure exp��rience web, prenez le temps de mettre votre navigateur �� jour";
	ieup.msgStr["pt"] = "Voc�� est�� usando um navegador desatualizado.Para navegar melhor neste site, por favor, atualize seu navegador";
	ieup.msgStr["ja"] = "���ʤ��Ͼ�ʽ�֥饦���������ФǤ������Υ����֥����Ȥ���m����E����ˤϥ֥饦���򥢥åץ���`�ɤ��Ƥ���������";
	ieup.msgStr["it"] = "Stai usando un browser obsoleto.Per una migliore navigazione su questo sito, per cortesia passa ad un browser di ultima generazione";
	ieup.msgStr["sv"] = "Du anv?nder en f?r?ldrad webbl?sare.F?r en b?ttre upplevelse p? denna webbplats, v?nligen byt till en modern webbl?sare";

	window.attachEvent("onload", function() {
		eval(ieup.path.innerHTML);
		var lang = navigator.browserLanguage.toLowerCase();

		var path = ieup.path.src;
		path = path.substr(0, path.lastIndexOf("/") + 1);

		var msgStr = ieup.msgStr;
		msgStr = (msgStr[lang] || msgStr[lang.substr(0, 2)] || msgStr["en"] || msgStr) + "...";

		//��������������д
		var doc = document;
		var html = doc.documentElement;
		var body = doc.body;
		var cre = doc.createElement;

		//����html��body�������Ա�ر���ʾ��ʱ�ָ�
		var bScroll = body.scroll;
		var hOverflow = html.style.overflow;
		var bMargin = body.style.margin;
		//var bHeight = body.style.height;
		//var hMargin = html.style.margin;
		//var hHeight = html.style.height;

		//����������ҳ��Ķ���
		var msg = cre("div");
		var info = cre("div");
		var wrap = cre("div");
		var innerWrap = cre("div");
		var exit = cre("img");

		//ȡ��ҳ������Ԫ�����ӽ�innerWrap
		innerWrap.innerHTML = body.innerHTML;
		body.innerHTML = "";

		//��֯����Ƕ�׹�ϵ
		body.appendChild(msg);
		body.appendChild(wrap);
		wrap.appendChild(innerWrap);
		msg.appendChild(info);
		msg.appendChild(exit);

		//������ʾ��Ϣ��ʽ
		info.innerText = "M";
		with(info.style){
			cursor = "default";
			textAlign = "left";
			overflow = "visible";
			padding = "3px 1.8em";
			borderBottom = "1px solid threedshadow";
			font = '9pt "Microsoft YaHei", SimSun';
		}

		//�趨��ʾ��Ϣ
		info.innerText = msgStr;

		//���ùرհ�ť
		exit.src = path + "close.gif";
		with(exit.style){
			position = "absolute";
			pixelTop = pixelRight = 3;
		}

		//������ʾ����ʽ
		with(msg.style){
			background = "url(" + path + "security.gif) no-repeat 3px 3px";
			borderBottom = "1px solid threeddarkshadow";
			position = "absolute";
			top = right = 0;
			width = "100%";
		}

		//����ҳ�����ݵ�����������ʽ
		with(wrap.style){
			width = "100%";
			left = 0;
			overflow = "auto";
			position = "absolute";
		}

		//����ҳ����������
		with(innerWrap.style){
			margin = body.currentStyle.margin;
			padding = body.currentStyle.padding
		}

		//ȥ��IEԭ�����������
		html.style.overflow = "hidden";
		//html.style.height = body.style.height = "100%";

		//�û������ʾ���¼�
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

		//�û�����رհ�ť�¼�
		exit.onclick = function(){
			//�ر���ʾ��ʱ�ָ�html��body������
			body.scroll = bScroll;
			html.style.overflow = hOverflow;
			body.style.margin = bMargin;
			
			document.cookie = "ieupdate=closed; path=/";

			//��ҳ�������ƻ�body
			body.innerHTML = innerWrap.innerHTML;
			window.detachEvent("onresize", resize);
		}

		//��֯��ʾ����ҳ��������λ�á�������
		function resize(){
			//wrap.style.top = 0;
			//wrap.style.height = "100%";
			wrap.style.pixelTop = msg.offsetHeight;
			wrap.style.pixelHeight = html.offsetHeight - msg.offsetHeight;
		}

		//���ý���ʱ��ʽ
		function focus(){
			 with(msg.style){
				 backgroundColor = "highlight";
				 exit.style.filter = "Invert()";
				 color = "highlighttext";
			 }
		}

		//����ƽʱ��ʽ
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
			//ΪIE5ȥ��ԭ�й�����
			body.scroll = "no";
			//ΪIE5��IE6��<body>����ˮƽ��߾�0����������ʹ��ͼƬ����
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