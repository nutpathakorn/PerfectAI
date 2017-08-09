<?php

$access_token = 'P5Qde5iLJeTX9rFoXFoZXbqQX5EsGhUpeYF3Srzbaks051jOntZpiYO08movDIP1BCF6n4EbiPZSTlgtVNEuZcNG1CINvBp22vSWAx7UR+5kKbk1ymZfJS71CYd538jx1GEX/rIclRVfwaqXo59tjwdB04t89/1O/w1cDnyilFU=';
$secret = '523f22dc4e06479ee26d2521b9c8bb04';
$sendto = 'U441309369d8f04c7e3b595aecb015fcf';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $secret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello');
$response = $bot->pushMessage('<to>', $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();

?>
