<?php
header('Content-Type: application/json');
$url1 = "http://webcam.hotelastoriafano.it/hotelastoria.jpg";

$curl = curl_init($url1);
curl_setopt($curl, CURLOPT_NOBODY, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FILETIME, true);
$result = curl_exec($curl);
if ($result === false) {
    die (curl_error($curl)); 
}
$timestamp = curl_getinfo($curl, CURLINFO_FILETIME);
if ($timestamp != -1) {
    	$filedate=date("Y-m-d", $timestamp);
}

if($filedate == date("Y-m-d")){
	$src = imagecreatefromjpeg($url1);
	$dest = imagecreatetruecolor(500, 80);

	imagecopy($dest, $src, 0, 0, 300, 20, 800, 100);
	imagefilter($dest, IMG_FILTER_GRAYSCALE);

	$n=0;
	$v=0;
	for($i=0; $i<imagesx($dest); $i++){
		for($j=0; $j<imagesy($dest); $j++){
			$color_index = imagecolorat($dest, $i, $j);
			$r = ($color_index >> 16) & 0xFF;
			//echo $i . "-" . $j . "\n";
			$v=$v+$r;
			$n++;
		}
	}
	$tempo = intval($v / $n);
	if($tempo < 70){
		$previsione = "piov";
	}else if($tempo < 100){
		$previsione = "piovrà";
	}else if($tempo < 150){
		$previsione = "c'è modi che piov";
	}else if($tempo < 200){
		$previsione = "en'avria da piova";
	}else{
		$previsione = "en piov";
	}
?>
{
	"nome" : "Meteo Fano",
	"previsione" : "<?php echo $previsione; ?>",
	"valore" : "<?php echo $tempo; ?>"
}
<?php
	imagedestroy($dest);
	imagedestroy($src);
}else{
	echo "dati troppo old";
}
?>
