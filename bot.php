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
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') 
		{
			$replyToken = $event['replyToken'];
			$findstaff    = '@';
			
			$ctext = $event['message']['text'];
			$luserid = $event['source']['userId'];
			$pos2 = stripos($ctext, $findstaff);
			
			
			
			if ($pos2 !== false) {
			    //$text = 'อยากทำงานแล้วเหรอ';
			    $CStaffID = explode($findstaff, $ctext)[0];
			    $CTextCase = explode($findstaff, $ctext)[1];
			    $CTextCase = str_replace(' ', '%20', $CTextCase);
				
			    
			    $url = 'http://helpdesk.pf.co.th/AISearchSTFByID/'.$CStaffID;
			    $getdetail = file_get_contents($url);
			    $events2 = json_decode($getdetail, true);

			    $empcode = $events2[0]['emp_code'];
			    $empname = $events2[0]['emp_name'];
			    $empname = str_replace(' ', '%20', $empname);
			    $empdept = $events2[0]['line_name'];
			    $empmail = $events2[0]['emp_email'];

		    if(!empty($empcode))
		    {
				    
			    if(!empty($CTextCase))
			    {
					    
			         $url2 = 'http://helpdesk.pf.co.th/AIInsertJobs1/'.$empcode.'/'.$empname.'/'.$empdept.'/'.$empmail.'/'.$luserid.'/'.$CTextCase;
				 
				 $getdetail2 = file_get_contents($url2);
			    	 $ins1result = json_decode($getdetail2, true);
			    	 $ins1msg = $ins1result[0]['MSG'];
				 $ins1sname = $ins1result[0]['StaffName'];

					if($ins1msg == 'OK'){

						$text = 'ขอบคุณคุณ '.$ins1sname.' มากครับเราจะแจ้งเจ้าหน้าที่ให้รีบดำเนินการให้ทันทีครับ';
					}
					else{

						$text = 'ขออภัยครับเกิดปัญหาบางประการขณะดำเนินการแจ้งปัญหา รบกวนให้ทำการแจ้งปัญหาอีกครั้งครับ';
						//$text = $url2;
					}
			    }
			    else
			    {
				     $text = 'ขออภัยครับคุณไม่ได้ใส่รายละเอียดปัญหามาให้ครับ รบกวนให้ทำการแจ้งปัญหาอีกครั้งครับ';
			    
			    }
		       }
			else
			{
			    $text = "ขออภัยครับรหัสพนักงานของคุณไม่พบอยู่ในระบบครับกรุณาตรวจสอบอีกครั้งครับ";
			}
			   
		}
		else {

			if($ctext == 'สวัสดี'){
			$text = 'สวัสดีครับ ผม PerfectAI เป็นระบบรับแจ้งปัญหาอัตโนมัติครับผม :)';
			}
			else if($ctext == 'แจ้งปัญหา'){
				$text = 'สวัสดีครับ '."\xF0\x9F\x98\x81"."\n".'แจ้งปัญหาเริ่มด้วยการพิมพ์ รหัสพนักงาน+"@"+รายละเอียดปัญหา,สถานที่แจ้ง,เบอร์ติดต่อกลับของคุณได้เลยครับ'."\n\n"."\xE2\x98\x9D".' ตัวอย่างเช่น(รหัสพนักงานคือ12345)'."\n\n"."\xE2\x9C\x85".' 12345@คอมพิวเตอร์เปิดไม่ติดครับ,แผนกบัญชีชั้น17,เบอร์โทรศัพท์1888';
			}
			else{
				$text = 'สวัสดีครับ '."\xF0\x9F\x98\x81"."\n".'แจ้งปัญหาเริ่มด้วยการพิมพ์ รหัสพนักงาน+"@"+รายละเอียดปัญหา,สถานที่แจ้ง,เบอร์ติดต่อกลับของคุณได้เลยครับ'."\n\n"."\xE2\x98\x9D".' ตัวอย่างเช่น(รหัสพนักงานคือ12345)'."\n\n"."\xE2\x9C\x85".' 12345@คอมพิวเตอร์เปิดไม่ติดครับ,แผนกบัญชีชั้น17,เบอร์โทรศัพท์1888';
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

