<?PHP
	//insert PHP code here.
	if($_GET["id"]=="100"){
		$eggstring=fopen_url("http://api.bus.fm/pt/getshengqu");
		if (isset($_GET['callback']))$c=$_GET['callback'];
		if(isset($c))echo $c."(".$eggstring.")";else echo $eggstring;
	//}else	echo '[["338","等待","http://ftp.luoo.net/radio/radio32/02.mp3","阿修罗乐队","唤醒沉睡的你","http://img3.douban.com/lpic/s1460783.jpg","http://quickaccess.com"],["114","The Way We Get By","http://ftp.luoo.net/radio/radio9/3.mp3","Kill the Moonlight","Kill the Moonlight","http://img3.douban.com/lpic/s1435558.jpg","http://quickaccess.com"],["271","Train","http://ftp.luoo.net/radio/radio25/07.mp3","3 Doors Down","3 Doors Down","http://img3.douban.com/lpic/s3310549.jpg","http://quickaccess.com"],["161","All Of Yours","http://ftp.luoo.net/radio/radio16/03.mp3","Making April","Runaway World","http://img5.douban.com/lpic/s3707515.jpg","http://quickaccess.com"],["281","Somewhere Else","http://ftp.luoo.net/radio/radio26/03.mp3","Razorlight","Somewhere Else, Pt. 2","http://img3.douban.com/lpic/s1483874.jpg","http://quickaccess.com"],["141","Smile [2005 mix]","http://ftp.luoo.net/radio/radio13/03.mp3","Flat7","Lost in Blue","http://img3.douban.com/lpic/s3076728.jpg","http://quickaccess.com"],["165","Free Loop","http://ftp.luoo.net/radio/radio16/08.mp3","Daniel Powter","dp","http://img3.douban.com/lpic/s4485293.jpg","http://quickaccess.com"],["87","尘世尘埃","http://ftp.luoo.net/radio/radio7/01.mp3","纹子&凸古堂乐队","幕舞会","http: //img3.douban.com/lpic/s3138987.jpg","http://quickaccess.com"],["416","Nine Million Bicycles","http://ftp.luoo.net/radio/radio39/07.mp3","Katie Melua","Piece By Piece","http://otho.douban.com/lpic/s2658447.jpg","http://quickaccess.com"],["339","化学心情下的爱情反应","http://ftp.luoo.net/radio/radio32/03.mp3","达达乐队","天使","http: //img3.douban.com/lpic/s3185598.jpg","http://quickaccess.com"]]';
	}else	echo '[["1831","Resistance (Radio Edit)","http://ftp.luoo.net/radio/radio179/05.mp3","Muse","Resistance","http://t.douban.com/lpic/s3914557.jpg","http://bus.fm/share/rngcMTgzMQeie"],["2970","The Words We Say","http://ftp.luoo.net/radio/radio250/05.mp3","Straylight Run","The Needles the Space","http://img3.douban.com/lpic/s2548737.jpg","http://bus.fm/share/rZuoMjk3MAI8e"],["3050","October & April (feat Anette Olzon)","http://ftp.luoo.net/radio/radio254/10.mp3","The Rasmus","Best Of Rasmus 2001-2009","http://img5.douban.com/lpic/s4077433.jpg","http://bus.fm/share/ciBEMzA1MA4bl"],["2226","Cue the Sun!","http://ftp.luoo.net/radio/radio208/08.mp3","Daphne Loves Derby","Goodnight, Witness Light","http://img3.douban.com/lpic/s2159266.jpg","http://bus.fm/share/endkMjIyNgRZk"],["1124","I Know I Know I Know","http://ftp.luoo.net/radio/radio101/08.mp3","Tegan And Sara","So Jealous","http://t.douban.com/lpic/s1680953.jpg","http://bus.fm/share/O6ckMTEyNAGIP"],["1951","How Far We’ve Come","http://ftp.luoo.net/radio/radio179/05.mp3","Matchbox Twenty","Exile On Mainstream","http://t.douban.com/lpic/s2654179.jpg","http://bus.fm/share/jJ6AMTk1MQjep"],["1083","Lovesong","http://ftp.luoo.net/radio/radio97/10.mp3","Jack Off Jill","Clear Hearts Grey Flowers","http://t.douban.com/lpic/s1462241.jpg","http://bus.fm/share/X2BiMTA4MwP11"],["3073","不要告别","http://ftp.luoo.net/radio/radio257/01.mp3","杨乃文","Silence","http://www.luoo.net/wp-content/uploads/Silence.jpg","http://bus.fm/share/Ln6iMzA3MwYaj"],["1126","Listen To The Radio","http://ftp.luoo.net/radio/radio101/10.mp3","Sloan","Never Hear The End Of It","http://t.douban.com/lpic/s2725529.jpg","http://bus.fm/share/kpbBMTEyNgNT0"],["1441","Life Goes On","http://ftp.luoo.net/radio/radio127/10.mp3","2Pac","All Eyez on Me","http://img3.douban.com/lpic/s3690349.jpg","http://bus.fm/share/erobMTQ0MQzZr"]]';
	// }else	echo '[["1831","Resistance (Radio Edit)","http://bus.tv/preview/mp3/1.ogg","Muse","Resistance","http://t.douban.com/lpic/s3914557.jpg","http://bus.fm/share/rngcMTgzMQeie"],["1831","Resistance (Radio Edit)","http://bus.tv/preview/mp3/1.ogg","Muse","Resistance","http://t.douban.com/lpic/s3914557.jpg","http://bus.fm/share/rngcMTgzMQeie"]]';
	
/** 
    获取远程文件内容 
    @param $url 文件http地址 
*/ 
function fopen_url($url) 
{ 
    if (function_exists('file_get_contents')) { 
        $file_content = @file_get_contents($url); 
    } elseif (ini_get('allow_url_fopen') && ($file = @fopen($url, 'rb'))){ 
        $i = 0; 
        while (!feof($file) && $i++ < 1000) { 
            $file_content .= strtolower(fread($file, 4096)); 
        } 
        fclose($file); 
    } elseif (function_exists('curl_init')) { 
        $curl_handle = curl_init(); 
        curl_setopt($curl_handle, CURLOPT_URL, $url); 
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2); 
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($curl_handle, CURLOPT_FAILONERROR,1); 
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Trackback Spam Check'); //引用垃圾邮件检查
        $file_content = curl_exec($curl_handle); 
        curl_close($curl_handle); 
    } else { 
        $file_content = ''; 
    } 
    return $file_content; 
}
?>