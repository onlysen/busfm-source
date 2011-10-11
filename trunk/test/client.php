<?php
/*
作用：取得客户端信息
参数：
返回：指定的资料
使用：
        $code = new client;
        1、浏览器：$str = $code->getBrowse();
        2、IP地址：$str = $code->getIP();
        3、IP地址资料：$str = $code->getIPaddres();
        4、操作系统：$str = $code->getOS();
*/
class client
{
        function getBrowse()
        {
                $Agent = $_SERVER['HTTP_USER_AGENT'];
                $browser = '';
                $browserver = '';
                $Browser = array('Lynx', 'MOSAIC', 'AOL', 'Opera', 'JAVA', 'MacWeb', 'WebExplorer', 'OmniWeb');
                for($i = 0; $i <= 7; $i ++){
                        if(strpos($Agent, $Browsers[$i])){
                                $browser = $Browsers[$i];
                                $browserver = '';
                        }
                }
                if(ereg('Mozilla', $Agent) && !ereg('MSIE', $Agent)){
                        $temp = explode('(', $Agent);
                        $Part = $temp[0];
                        $temp = explode('/', $Part);
                        $browserver = $temp[1];
                        $temp = explode(' ', $browserver);
                        $browserver = $temp[0];
                        $browserver = preg_replace('/([\d\.]+)/', '\\1', $browserver);
                        $browserver = $browserver;
                        $browser = 'Netscape Navigator';
                }
                if(ereg('Mozilla', $Agent) && ereg('Opera', $Agent)) {
                        $temp = explode('(', $Agent);
                        $Part = $temp[1];
                        $temp = explode(')', $Part);
                        $browserver = $temp[1];
                        $temp = explode(' ', $browserver);
                        $browserver = $temp[2];
                        $browserver = preg_replace('/([\d\.]+)/', '\\1', $browserver);
                        $browserver = $browserver;
                        $browser = 'Opera';
                }
                if(ereg('Mozilla', $Agent) && ereg('MSIE', $Agent)){
                        $temp = explode('(', $Agent);
                        $Part = $temp[1];
                        $temp = explode(';', $Part);
                        $Part = $temp[1];
                        $temp = explode(' ', $Part);
                        $browserver = $temp[2];
                        $browserver = preg_replace('/([\d\.]+)/','\\1',$browserver);
                        $browserver = $browserver;
                        $browser = 'Internet Explorer';
                }
                if($browser != ''){
                        $browseinfo = $browser.' '.$browserver;
                } else {
                        $browseinfo = false;
                }
                return $browseinfo;
        }
        function getIP ()
        {
                if (getenv('HTTP_CLIENT_IP')) {
                        $ip = getenv('HTTP_CLIENT_IP');
                } else if (getenv('HTTP_X_FORWARDED_FOR')) {
                        $ip = getenv('HTTP_X_FORWARDED_FOR');
                } else if (getenv('REMOTE_ADDR')) {
                        $ip = getenv('REMOTE_ADDR');
                } else {
                        $ip = $_SERVER['REMOTE_ADDR'];
                }
                return $ip;
        }
        function getIPaddres ($ip = '')
        {
                if($ip == ''){
                        $ip = $this->getIP();
                }
                if($ip == '127.0.0.1'){
                        return $ip_addres = '本地机器';
                }
                $RECORDLENGTH = 17+22+13+47+12+1;
                $ret = ereg('^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)$', $ip, $IPSection);
                if($ret == false){$ip_addres = 'IP地址有错';}
                for($i = 1; $i <= 4; $i ++){
                        if($IPSection[$i] > 255){
                                $ip_addres = 'IP地址有错';
                        } else {
                                $thisip .= sprintf('%03.0f', $IPSection[$i]). (($i<4) ? '.' : '');
                        }
                }
                $fp= fopen($this->obj['rootDir'].'data/ip.txt', 'rb');
                if($fp == NULL){
                        $ip_addres = 'IP记录文件打开出错';
                }
                fseek($fp, 0, SEEK_END);
                $DATAFIELDBEGIN = 0xc2;
                $RecordCount = floor((ftell($fp)-$DATAFIELDBEGIN)/$RECORDLENGTH);
                if($RecordCount <= 1){
                        $ip_addres = 'IP记录文件打开出错';
                } else {
                        $RangB = 0;
                        $RangE = $RecordCount;
                        while($RangB<$RangE-1){
                                $RecNo = floor(($RangB+$RangE)/2);
                                fseek($fp,$RecNo*$RECORDLENGTH+$DATAFIELDBEGIN, SEEK_SET);
                                $buf = fread($fp, $RECORDLENGTH);
                                if(strlen($buf) == 0){
                                        return false;
                                }
                                $StartIP = (substr($buf, 0, 17));
                                $EndIP = trim(substr($buf, 17, 22));
                                $Country = trim(substr($buf, 17+22, 13));
                                $Local = trim(substr($buf, 17+22+13, 47));
                                if (strcmp($thisip, $StartIP) >= 0 && strcmp($thisip, $EndIP)<=0){
                                        break;
                                }
                                if(strcmp($thisip, $StartIP) > 0){
                                        $RangB = $RecNo;
                                } else {
                                        $RangE = $RecNo;
                                }
                        }
                        if(!($RangB < $RangE - 1)){
                                $ip_addres= '未知地址！';
                        } else {
                                $ip_addres = $Country;
                                $ip_addres .= $Local;
                        }
                }
                fclose($fp);
                return $ip_addres;
        }
        function getOS ()
        {
                $agent = $_SERVER['HTTP_USER_AGENT'];
                $os = false;
                if (eregi('win', $agent) && strpos($agent, '95')){
                        $os = 'Windows 95';
                }
                else if (eregi('win 9x', $agent) && strpos($agent, '4.90')){
                        $os = 'Windows ME';
                }
                else if (eregi('win', $agent) && ereg('98', $agent)){
                        $os = 'Windows 98';
                }
                else if (eregi('win', $agent) && eregi('nt 5.1', $agent)){
                        $os = 'Windows XP';
                }
                else if (eregi('win', $agent) && eregi('nt 5', $agent)){
                        $os = 'Windows 2000';
                }
                else if (eregi('win', $agent) && eregi('nt', $agent)){
                        $os = 'Windows NT';
                }
                else if (eregi('win', $agent) && ereg('32', $agent)){
                        $os = 'Windows 32';
                }
                else if (eregi('linux', $agent)){
                        $os = 'Linux';
                }
                else if (eregi('unix', $agent)){
                        $os = 'Unix';
                }
                else if (eregi('sun', $agent) && eregi('os', $agent)){
                        $os = 'SunOS';
                }
                else if (eregi('ibm', $agent) && eregi('os', $agent)){
                        $os = 'IBM OS/2';
                }
                else if (eregi('Mac', $agent) && eregi('PC', $agent)){
                        $os = 'Macintosh';
                }
                else if (eregi('PowerPC', $agent)){
                        $os = 'PowerPC';
                }
                else if (eregi('AIX', $agent)){
                        $os = 'AIX';
                }
                else if (eregi('HPUX', $agent)){
                        $os = 'HPUX';
                }
                else if (eregi('NetBSD', $agent)){
                        $os = 'NetBSD';
                }
                else if (eregi('BSD', $agent)){
                        $os = 'BSD';
                }
                else if (ereg('OSF1', $agent)){
                        $os = 'OSF1';
                }
                else if (ereg('IRIX', $agent)){
                        $os = 'IRIX';
                }
                else if (eregi('FreeBSD', $agent)){
                        $os = 'FreeBSD';
                }
                else if (eregi('teleport', $agent)){
                        $os = 'teleport';
                }
                else if (eregi('flashget', $agent)){
                        $os = 'flashget';
                }
                else if (eregi('webzip', $agent)){
                        $os = 'webzip';
                }
                else if (eregi('offline', $agent)){
                        $os = 'offline';
                }
                else {
                         $os = 'Unknown';
                }
                return $os;
        }
}

  $code = new client;
  $getBrowse  = $code->getBrowse();//1、浏览器：
  $getIP = $code->getIP();//      2、IP地址：
  $getIPaddres = $code->getIPaddres();//      3、IP地址资料：
  $getOS = $code->getOS();//      4、操作系统：
	
   //[浏览器,IP地址,IP地址资料,操作系统]
  echo "[[\"".$getBrowse."\",\"".$getIP."\",\"".$getIPaddres."\",\"".$getOS."\"]]"; 
?>