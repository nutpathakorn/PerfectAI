ser<?php
$access_token = 'P5Qde5iLJeTX9rFoXFoZXbqQX5EsGhUpeYF3Srzbaks051jOntZpiYO08movDIP1BCF6n4EbiPZSTlgtVNEuZcNG1CINvBp22vSWAx7UR+5kKbk1ymZfJS71CYd538jx1GEX/rIclRVfwaqXo59tjwdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
$getgoldprice = file_get_contents('http://122.155.10.6/wisdomsbe/LineGoldPrice');
// Parse JSON
$events = json_decode($content, true);
$events2 = json_decode($getgoldprice, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text' && $event['message']['text'] == 'userid') {
			// Get text sent
			//$text = "\n".$events2['text']."\nทองแท่งขาย : ".$events2['sale1']."\nทองแท่งซื้อ : ".$events2['buy1']."\nทองรูปประพรรณขาย : ".$events2['sale2']."\nทองรูปประพรรณซื้อ : ".$events2['buy2'];
			// Get replyToken
			
			$replyToken = $event['replyToken'];
      $userid = $event['source']['userId'];
			$text = $userid;
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
		}
	}
}
echo "OK";
?>
