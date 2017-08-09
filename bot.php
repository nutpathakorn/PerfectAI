ser<?php
$access_token = 'nt1D33RgrPQI8u3h0IugdOafvlH2Um2UISC5rqsWeuTKw/oxWGQeTBNvsjSonOAB2oyFgdKASz5ZNDeZMejpv7Dg9eUTuWwJfILRIg1fyhZy/owcMjcnQbNV1DRwg/leJ5A0DMCTGuGWiw0OYIj80gdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			$replyToken = $event['replyToken'];
			$findstaff    = '@';
			$ctext = $event['message']['text'];
			$pos2 = stripos($ctext, $findstaff);
			
			if ($pos2 !== false) {
			    //$text = 'อยากทำงานแล้วเหรอ';
			    $CStaffID = str_replace($findstaff, '', $ctext);
			    $url = 'http://helpdesk.pf.co.th/AISearchSTFByID/'.$CStaffID;
			    $getdetail = file_get_contents($url);
			    $events2 = json_decode($getdetail, true);
				
			    //$text = $events2['emp_code'].'/n'.$events2['emp_name'].'/n'.$events2['emp_email'];
			    $text = " - ".$events2;
			}
			else if($ctext == 'สวัสดี'){
				$text = 'หวัดดีว่าไงสึส';
			}
			else if($ctext == 'นาวา'){
				$text = 'เด็กเทพ รู้จักด้วยเหรอ??';
			}
			else{
				$text = 'พิมพ์ไรมาวะกุไม่เข้าใจ..ห่า';
			}
			
			
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
