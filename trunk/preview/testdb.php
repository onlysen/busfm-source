<form id="form1" name="form1" action="" method="post">
	act:<input type="text" name="act" id="act" value="" />
	par:<input type="text" name="par" id="par" value="" />
	<input type="submit" name="sub" id="sub" value="submit" />
</form>
<?PHP
if(isset($_POST['act']	)&&isset($_POST['par'])){
	require_once('../admin/includes/config.php');
	require_once('../admin/includes/class_db.php');
	$db=new db($db_host,$db_user,$db_password,$db_name);
	$act=$_POST['act'];
	$cmd=$_POST['par'];
	switch($act){
		case 'getone':
		$ua=$db->getone($cmd);
		var_dump($ua);
		break;
		case 'getall':
		$ua=$db->getall($cmd);
		var_dump($ua);
		break;
		default;
		echo 'not recognized';
		break;
	}
}
?>