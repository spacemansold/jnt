<?php

function curl($url, $imei, $headers)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
	if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS,"method=app.findTrack&data%5Bbillcode%5D=$imei&data%5Blang%5D=en&data%5Bsource%5D=3&pId=c066f88655e6b3f9c7d1d0d89a09c96c&pst=0097657d16bbb7d72e87258d2bc3a47c");
	$result = curl_exec($ch);
	$header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	$body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
	$cookies = array()
;	foreach($matches[1] as $item) {
	  parse_str($item, $cookie);
	  $cookies = array_merge($cookies, $cookie);
	}
	return array (
	$header,
	$body,
	$cookies
	);
}

function get_between($string, $start, $end) 
    {
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }

function random($length,$a) 
	{
		$str = "";
		if ($a == 0) {
			$characters = array_merge(range('0','9'));
		}elseif ($a == 1) {
			$characters = array_merge(range('0','9'),range('a','z'));
		}
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		return $str;
	}

while(1){

$imeix = 'JD01145'.random(5,0);

$headers = array();
$headers[] = 'authority: www.jet.co.id';
$headers[] = 'sec-ch-ua: "Chromium";v="88", "Google Chrome";v="88", ";Not\\A\"Brand";v="99"';
$headers[] = 'x-simplypost-id: c066f88655e6b3f9c7d1d0d89a09c96c';
$headers[] = 'sec-ch-ua-mobile: ?1';
$headers[] = 'user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Mobile Safari/537.36';
$headers[] = 'content-type: application/x-www-form-urlencoded; charset=UTF-8';
$headers[] = 'accept: application/json, text/javascript, */*; q=0.01';
$headers[] = 'x-simplypost-signature: 0097657d16bbb7d72e87258d2bc3a47c';
$headers[] = 'x-requested-with: XMLHttpRequest';
$headers[] = 'origin: https://www.jet.co.id';
$headers[] = 'sec-fetch-site: same-origin';
$headers[] = 'sec-fetch-mode: cors';
$headers[] = 'sec-fetch-dest: empty';
$headers[] = 'referer: https://www.jet.co.id/track?bills=JP5072275836';
$headers[] = 'accept-language: en-US,en;q=0.9';
$headers[] = 'cookie: think_var=en-us; PHPSESSID=q82d2g92hh0nfj4oh7olktll93; _ga=GA1.3.148031728.1616301387; _gid=GA1.3.1444684183.1616301387';


$cek = curl('https://www.jet.co.id/index/router/index.html', $imeix, $headers);
$hasilx = $cek[1];
$hasilx = ltrim($hasilx, '"');
$hasilx = rtrim($hasilx, '"');

if (strpos($hasilx, 'scanstatus')) {
	
$json_string = stripslashes($hasilx);
$data = json_decode($json_string);
$resi = $data->data;
$data2 = json_decode($resi);
$resii = $data2->billcode;
$asal = $data2->details[0]->city;

$numItems = count($data2->details);
$i = 0;
foreach($data2->details as $mydata)

    {
    if(++$i === $numItems) {
    $tanggalsampai = $mydata->scantime;
    $status = $mydata->scanstatus;
    $desc = $mydata->desc;
    $tujuan = $mydata->city;
  }
}
$tt = "$status : $resii - $tujuan - $tanggalsampai - $desc";
$resultnya = "$resii - Asal: $asal - Tujuan: $tujuan - $tanggalsampai - $desc - $status";

            $log='logresi.txt';
            if(!file_exists( $log )) {
	        fopen($log,'a');
            }
	$alllog = "logresi.txt";
	$log_data2 = file($alllog, FILE_IGNORE_NEW_LINES);
	if(in_array($resii, $log_data2)) {
	
	echo 'SKIPPED [has been saved]'.PHP_EOL;
	
	} else {
	
	file_put_contents($alllog, $tt . "\n", FILE_APPEND);
    file_put_contents("$tujuan.txt", $resultnya.PHP_EOL, FILE_APPEND);
    echo $resultnya.PHP_EOL;
    
	}

	
} else {
    //echo 'RESI invalid'.PHP_EOL;
}
}




?>