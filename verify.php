<?php
$access_token = 'P5Qde5iLJeTX9rFoXFoZXbqQX5EsGhUpeYF3Srzbaks051jOntZpiYO08movDIP1BCF6n4EbiPZSTlgtVNEuZcNG1CINvBp22vSWAx7UR+5kKbk1ymZfJS71CYd538jx1GEX/rIclRVfwaqXo59tjwdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
?>
