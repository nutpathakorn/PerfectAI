<?php
$access_token = 'nt1D33RgrPQI8u3h0IugdOafvlH2Um2UISC5rqsWeuTKw/oxWGQeTBNvsjSonOAB2oyFgdKASz5ZNDeZMejpv7Dg9eUTuWwJfILRIg1fyhZy/owcMjcnQbNV1DRwg/leJ5A0DMCTGuGWiw0OYIj80gdB04t89/1O/w1cDnyilFU=';

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
