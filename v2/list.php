<?PHP
$s=1;
for ($i=174;$i<234;$i++){
	$m="<a href='jump.php?url=http://ftp.luoo.com/radio/radio$i";
	for ($j=1;$j<30;$j++){
		if($j<10) echo $m."/0$j.mp3'>".$s++."</a>";
		else echo $m."/$j.mp3'>".$s++."</a>";
	}
}
?>