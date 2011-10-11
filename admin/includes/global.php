<?php
error_reporting(E_ALL);
if(file_exists("install.php"))@header("location:install.php");
@session_start();
@header("content-type:text/html;charset=utf-8");
if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}
@set_magic_quotes_runtime(0);
if(@get_magic_quotes_gpc()){
	function rs($s){
		if(is_array($s)){
			foreach ($s as $k=>$v)$s[$k]=rs($v);
		}else{
			$s=stripslashes($s);
		}
		return $s;
	}
    $_GET=rs($_GET);$_POST=rs($_POST);$_COOKIE=rs($_COOKIE);
}
if (!function_exists('file_get_contents')){
    function file_get_contents($file){
        if (($fp = @fopen($file, 'rb')) === false){
            return false;
        }else{
            $fsize = @filesize($file);
            if ($fsize){
                $contents = fread($fp, $fsize);
            }else{
                $contents = '';
            }
            fclose($fp);
            return $contents;
        }
    }
}
if(!function_exists('file_put_contents')){
	function file_put_contents($a,$b){
		$f=@fopen($a,"w");
		if (!$f){
			return false;
		}else{
			fwrite($f,$b);
			fclose($f);
			return true;
		}
	}
}
if (!function_exists('floatval')){
    function floatval($n){
        return (float)$n;
    }
}
if(!function_exists('json_encode')&&!function_exists('json_decode')){
	function json_encode($data) {   
		if (2==func_num_args()) {   
			$callee=__FUNCTION__;   
			return json_format_scalar(strval(func_get_arg(1))).":".$callee($data);   
		}   
		is_object($data) && $data=get_object_vars($data);   
		if (is_scalar($data)) { return json_format_scalar($data); }   
		if (empty($data)) { return '[]';}   
		$keys=array_keys($data);   
		if (is_numeric(join('',$keys))) {   
			$data=array_map(__FUNCTION__,$data);   
			return '['.join(',',$data).']';   
		} else {   
			$data=array_map(__FUNCTION__,array_values($data),$keys);   
			return '{'.join(',',$data).'}';   
		}   
	}
	function json_format_scalar($value) {   
		if (is_bool($value)) {   
			$value = $value?'true':'false';   
		} else if (is_int($value)) {   
			$value = (int)$value;   
		} else if (is_float($value)) {   
			$value = (float)$value;   
		} else if (is_string($value)) {   
			$value=addcslashes($value,"\n\r\"\/\\");   
			$value='"'.preg_replace_callback('|[^\x00-\x7F]+|','json_utf_slash_callback',$value).'"';   
		} else {   
			$value='null';   
		}   
		return $value;   
	}   
	function json_utf_slash_callback($data) {   
		if (is_array($data)) {   
			$chars=str_split(iconv("UTF-8","UCS-2",$data[0]),2);   
			$chars=array_map(__FUNCTION__,$chars);   
			return join("",$chars);   
		} else {   
			$char1=dechex(ord($data{0}));   
			$char2=dechex(ord($data{1}));   
			return sprintf("\u%02s%02s",$char1,$char2);   
		}   
	}   
	function json_utf_slash_strip($data) {   
		if (is_array($data)) {   
			return $data[1].iconv("UCS-2","UTF-8",chr(hexdec($data[2])).chr(hexdec($data[3])));   
		} else {   
			return preg_replace_callback('/(?<!\\\\)((?:\\\\\\\\)*)\\\\u([a-f0-9]{2})([a-f0-9]{2})/i',__FUNCTION__,$data);   
		}   
	}   
	function json_decode($data) {   
		static $strings,$count=0;   
		if (is_string($data)) {   
			$data=trim($data);   
			if ($data{0}!='{' && $data{0}!='[') return json_utf_slash_strip($data);   
			$strings=array();   
			$data=preg_replace_callback('/"([\s\S]*?(?<!\\\\)(?:\\\\\\\\)*)"/',__FUNCTION__,$data);   
			$cleanData=str_ireplace(array('true','false','undefined','null','{','}','[',']',',',':','#'),'',$data);   
			if (!is_numeric($cleanData)) {   
				throw new Exception('Dangerous!The JSONString is dangerous!');   
				return NULL;   
			}   
			$data=str_replace(   
				array('{','[',']','}',':','null'),   
				array('array(','array(',')',')','=>','NULL')   
				,$data);   
			$data=preg_replace_callback('/#\d+/',__FUNCTION__,$data);   
			@$data=eval("return $data;");   
			$strings=$count=0;   
			return $data;   
		} elseif (count($data)>1) {
			$strings[]=json_utf_slash_strip(str_replace(array('$','\\/'),array('\\$','/'),$data[0]));   
			return '#'.($count++);   
		} else { 
			$index=substr($data[0],1);   
			return $strings[$index];   
		}   
	}  
}
define('ROOT_PATH',str_replace('includes/global.php','',str_replace('\\', '/', __FILE__)));
require_once(ROOT_PATH.'includes/config.php');
require_once(ROOT_PATH.'includes/function.php');
require_once(ROOT_PATH.'includes/share.php');
require_once(ROOT_PATH.'includes/class_db.php');
require_once(ROOT_PATH.'includes/class_smarty.php');
$db=new db($db_host,$db_user,$db_password,$db_name);
$config=load_config();
$config['timezone']='PRC';
if (!empty($config['timezone'])){
    @date_default_timezone_set($config['timezone']);
}
?>