<?PHP
//http://ftp.luoo.net/radio/radio1/2.mp3
//http://bus.tv/v2/jump.php?title=234_Refugees&url=http://ftp.luoo.net/radio/radio188/01.mp3
$server = 'ftp.luoo.net';
$host = 'ftp.luoo.net';
$target = $_GET['url'];
$referer = 'http://www.luoo.net'; // Referer
$port = 80;
$fp = fsockopen($server, $port, $errno, $errstr, 30);
if (!$fp) 
{
echo "$errstr ($errno)<br />\n";
} 
else 
{
$out = "GET $target HTTP/1.1\r\n";
$out .= "Host: $host\r\n";
$out .= "Cookie: PHPSESSIONIDSQTBQSDA=DFCAPKLBBFICDAFMHNKIGKEG\r\n";
$out .= "Referer: $referer\r\n";
$out .= "Connection: Close\r\n\r\n";
fwrite($fp, $out);

header ( "Pragma: public" );
header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header ( "Cache-Control: private", false );
header ( "Content-Transfer-Encoding: binary" );
header ( "Content-Type:audio/mpeg MP3");
// header ( "Content-Length: " );
header ( "Content-Disposition: attachment; filename=".$_GET['title']);

while (!feof($fp)) 
{
echo fgets($fp, 128);
}
fclose($fp);
}
?>