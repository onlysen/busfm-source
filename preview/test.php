<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title>test load page</title>
	</head>
<body>
	<?PHP
		//insert PHP code here.
		//~ sleep(2);
		if(!empty($_POST["aa"])){
			echo $_POST["aa"]." at ".strftime("%Y/%m/%d %H:%M:%S",time());
		}else echo 'no post data';
	?>
	<form id="form1" name="form1" action="test.php" method="post">
		<input type="text" name="aa" id="aa" value="aabb mytxt" />
		<input type="submit" name="sub" id="sub" value="submit" />
	</form>
	<div id="p1">aaabbb
		<script type="text/javascript">
			function ab(){console.log("from id p2");}
		</script>
	</div>
	<div id="p2">1aaabbb
		<script type="text/javascript">
			function ab(){console.log("from id p2");}
		</script>
	</div>
	<div id="p3">2aaabbb</div>
	<div id="p4">3aaabbb</div>
</body>
</html>