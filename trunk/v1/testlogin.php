<?PHP
	if(empty($_SERVER["HTTP_AUTHORIZATION"])){
		header('WWW-Authenticate: Basic realm="my title abc"'); 
		exit('HTTP/1.1 401 Unauthorized');
	}else{
		$v=str_replace('Basic','',$_SERVER["HTTP_AUTHORIZATION"]);
		$v=base64_decode($v);
		$vs=preg_split("/\:/",$v);
		if($vs[0]!="admin"||$vs[1]!="admin"){
			header('WWW-Authenticate: Basic realm="my title"'); 
			//header('HTTP/1.1 401 Unauthorized');
			exit('HTTP/1.1 401 Unauthorized');
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>需要授权的页面</title>
	</head>
<body>
	<h1>授权页面测试</h1>
	<p>你看到这个页面的时候，说明测试成功</p>
</body>
</html>