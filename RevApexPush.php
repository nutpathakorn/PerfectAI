<?php
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
			$findstaff    = '@i';
			$finddetail    = '@d';
			$findphone    = '@p';
			$findlocation    = '@s';
			$ctext = $event['message']['text'];
			$luserid = $event['source']['userid']
			$pos2 = stripos($ctext, $findstaff);
			$pos3 = stripos($ctext, $finddetail);
			$pos4 = stripos($ctext, $findphone);
			$pos5 = stripos($ctext, $findlocation);
			
			
			
			if ($pos2 !== false) {
			    //$text = 'อยากทำงานแล้วเหรอ';
			    $CStaffID = str_replace($findstaff, '', $ctext);
			    $url = 'http://helpdesk.pf.co.th/AISearchSTFByID/'.$CStaffID;
			    $getdetail = file_get_contents($url);
			    $events2 = json_decode($getdetail, true);

			    $empcode = $events2[0]['emp_code'];
			    $empname = $events2[0]['emp_name'];
			    $empdept = $events2[0]['line_name'];
			    $empmail = $events2[0]['emp_email'];

				if(!empty($empcode)){

					$url2 = 'http://helpdesk.pf.co.th/AIInsertJobs1/'.$empcode.'/'.$empname.'/'.$empdept.'/'.$empmail.'/'.$luserid;
			    	$getdetail2 = file_get_contents($url2);
			    	$ins1result = json_decode($getdetail2, true);
			    	$ins1msg = $ins1result[0]['MSG']

			    	if($ins1msg == 'OK'){

			    		$text = 'แจ้งปัญหามาได้เลยครับ โดยการพิมพ์"@d"ตามด้วยรายละเอียดปัญหาครับ เช่น(@dคอมพิวเตอร์เปิดไม่ติด)';
			    	}
			    	else{

			    		switch ($ins1msg) {
                            case "ST1":
                                $text = 'คุณยังอยู่ในขั้นตอนการแจ้งปัญหานะครับ กรุณาพิมพ์"@d"ตามด้วยรายละเอียดปัญหาครับ เช่น(@dคอมพิวเตอร์เปิดไม่ติด)';
                                break;
                            case "ST2":
                                $text = 'คุณอยู่ในขั้นตอนการแจ้งปัญหานะครับ กรุณาพิมพ์"@d"ตามด้วยรายละเอียดปัญหาครับ เช่น(@dคอมพิวเตอร์เปิดไม่ติด)';
                                break;
                            case "ST3":
                                $text = 'คุณอยู่ในขั้นตอนการแจ้งปัญหานะครับ กรุณาพิมพ์"@d"ตามด้วยรายละเอียดปัญหาครับ เช่น(@dคอมพิวเตอร์เปิดไม่ติด)';
                                break;     
                         
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
				
				if($ctext == 'สวัสดี'){
				$text = 'หวัดดีว่าไงสึส';
				}
				else if($ctext == 'แจ้งปัญหา'){
					$text = 'สวัสดีครับ แจ้งปัญหาเริ่มด้วยการพิมพ์ "@i"ตามด้วยรหัสพนักงานของคุณได้เลยครับ เช่น (@ixxxxx) ';
				}
				else if($ctext == 'นาวา'){
					$text = 'เด็กเทพ รู้จักด้วยเหรอ??';
				}
				else{
					$text = 'พิมพ์ไรมาวะกุไม่เข้าใจ..ห่า';
				}
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