<?php
$access_token = 'AwHRBx5uVcw2NRLELm9R2oxun8RG082jlDNyXA2yXn+I/aAjqnvC4seHuqHDbbnKxG00hjhI4wJIxPzeaIh32M3wNULjW2QK82C+Bq01yf2Le3ihamUkSKYj16wXKvbvOGrn2SEg7zHdU4DNvY9gQAdB04t89/1O/w1cDnyilFU=';
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
			$findstaff    = '@i';
			$finddetail    = '@d';
			$findphone    = '@p';
			$findlocation    = '@s';
			$ctext = $event['message']['text'];
			$luserid = $event['source']['userid']
			$pos2 = $ctext;
			
			
			
			if ($ctext == 'ขอid') {
			    //$text = 'อยากทำงานแล้วเหรอ';	
				$text = 'Line UserID ของคุณคือ: '.$luserid;

                        }
			    	}

				    //$text = $events2[0]['emp_code']."\n ".$events2[0]['emp_name']."\n ".$events2[0]['emp_email'];

				}
				else{
				    $text = "มั่วมาป่าววะ ไปดูรหัสตัวเองมาใหม่!!";
				}
			   
			}
			/*
			else if($pos3 !== false){


			}
			else if($pos4 !== false){

				
			}
			else if($pos5 !== false){

				
			}
			*/
			else {
				$text = 'สวัสดีครับ ขอดูรหัสของคุณเริ่มด้วยการพิมพ์ "@i"';
				
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
