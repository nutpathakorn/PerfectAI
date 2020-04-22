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
			$findstaff    = '&';
			
			$ctext = $event['message']['text'];
			$luserid = $event['source']['userId'];
			$luseridg = $event['source']['groupId'];
			$luseridr = $event['source']['room'];
			
			//รับ id ว่ามาจากไหน
			/*
			   if(isset($event['source']['userId']){
			      $luserid = $event['source']['userId'];
			   }
			   else if(isset($event['source']['groupId'])){
			      $luserid = $event['source']['groupId'];
			   }
			   else if(isset($event['source']['room'])){
			      $luserid = $event['source']['room'];
			   }
			   */
			   
			      
			$pos2 = stripos($ctext, $findstaff);
			
			
			
			if ($pos2 !== false) {
			    //$text = 'อยากทำงานแล้วเหรอ';
			    
			    $CStaffID = explode($findstaff, $ctext)[0];
			    $CTextCase = explode($findstaff, $ctext)[1];
			    $CTextCase = str_replace(' ', '%20', $CTextCase);
			    $CTextCase = str_replace('/', '-', $CTextCase);
				
			    //$CTextCasetest = $CTextCase;
				
			    
				
		            if($CStaffID == 'สถานะ'){
				    
			    $url = 'http://helpdesk.pf.co.th/AISearchJobs1/'.$CTextCase;
			    $getdetail = file_get_contents($url);
			    $events2 = json_decode($getdetail, true);

			    $JobsID = $events2[0]['JobsID'];
			    $JobsDetails = $events2[0]['JobsDetails'];
			    $JobsStatus = $events2[0]['JobsStatus'];
			    $STAFF = $events2[0]['STAFF'];
				    
			    $text = 'หมายเลขงาน : '.$JobsID."\n".'รายละเอียด : '.$JobsDetails."\n".'สถานะงาน : '.$JobsStatus."\n".'ผู้ดูแล : '.$STAFF."\n".'สอบถามเพิ่มเติม : 022477500 ต่อ 1840';
				$messages = [
				'type' => 'text',
				'text' => $text
				];
			    
			    }
			    else if($CStaffID == 'Vote'){
				    $findVote    = ',';
				    
				    $VJobsID = explode($findVote, $CTextCase)[0];
				    $VResult = explode($findVote, $CTextCase)[1];
				    
				    $Vurl = 'http://helpdesk.pf.co.th/AIVoteResult/'.$VJobsID.'/'.$VResult;
				    $Vgetdetail = file_get_contents($Vurl);
				    $Vevents = json_decode($Vgetdetail, true);
				    
				    $VMSG = $Vevents[0]['MSG'];
				    
				    if($VMSG == 'OK'){
					    
					    $text = 'ขอบคุณสำหรับความคิดเห็นครับ';
						$messages = [
						'type' => 'text',
						'text' => $text
						];
				    
				    }
				    else if($VMSG == 'DUP'){
					    $text = 'ขออภัยครับคุณได้แสดงความคิดเห็นนี้ไปแล้วครับ';
						$messages = [
						'type' => 'text',
						'text' => $text
						];
				    }
				    else{
					    $text = 'ขออภัยครับเกิดปัญหาบางประการกรุณาติด่อผู้ดูแลระบบครับ';
						$messages = [
						'type' => 'text',
						'text' => $text
						];			    
				    }  
			    }
			    else{
				    
		            $url = 'http://helpdesk.pf.co.th/AISearchSTFByID/'.$CStaffID;
			    $getdetail = file_get_contents($url);
			    $events2 = json_decode($getdetail, true);

			    $empcode = $events2[0]['emp_code'];
			    $empnametest = $events2[0]['emp_name'];
			    $empname = $events2[0]['emp_name'];
			    $empname = str_replace(' ', '%20', $empname);
			    $empdept = $events2[0]['line_name'];
		            $empdept = str_replace(' ', '%20', $empdept);
			    $empmail = $events2[0]['emp_email'];
				    
			    if(empty($empmail))
			    {
				    $empmail = 'nomail';
			    }

			    if(!empty($empcode))
			    {
				    
			    if(!empty($CTextCase))
			    {
					    
			         $url2 = 'http://helpdesk.pf.co.th/AIInsertJobs1/'.$empcode.'/'.$empname.'/'.$empdept.'/'.$empmail.'/'.$luserid.'/'.$CTextCase;
				 
				 $getdetail2 = file_get_contents($url2);
			    	 $ins1result = json_decode($getdetail2, true);
			    	 $ins1msg = $ins1result[0]['MSG'];
				 $ins1sname = $ins1result[0]['StaffName'];
				 $ins1sid = $ins1result[0]['JobsID'];

					if($ins1msg == 'OK'){

						$text = 'ขอบคุณคุณ '.$ins1sname.' มากครับเราจะแจ้งเจ้าหน้าที่ให้รีบดำเนินการให้ทันทีครับ'."\n".'หมายเลขงานคุณคือ : '.$ins1sid;
						$messages = [
						'type' => 'text',
						'text' => $text
						];
					}
					else{
//$text = 'ขออภัยครับเกิดปัญหาบางประการขณะดำเนินการแจ้งปัญหา รบกวนให้ทำการแจ้งปัญหาอีกครั้งครับ : $empcode='.$empcode.' emp_name='.$empname.' empdept='.$empdept.' empmail='.$empmail.' luserid='.$luserid.' ins1msg='.$ins1msg.' ins1sname='.$ins1sname.' $CTextCase='.$CTextCase;
						$text = 'ขออภัยครับเกิดปัญหาบางประการขณะดำเนินการแจ้งปัญหา รบกวนให้ทำการแจ้งปัญหาอีกครั้งครับ';
						$messages = [
						'type' => 'text',
						'text' => $text
						];
						//$text = $url2;
					}
			    }
			    else
			    {
				     $text = 'ขออภัยครับคุณไม่ได้ใส่รายละเอียดปัญหามาให้ครับ รบกวนให้ทำการแจ้งปัญหาอีกครั้งครับ';
				    $messages = [
				'type' => 'text',
				'text' => $text
				];
			    
			    }
		       }
			else
			{
			    $text = "ขออภัยครับรหัสพนักงานของคุณไม่พบอยู่ในระบบครับกรุณาตรวจสอบอีกครั้งครับ";
			    $messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			    
		   } 
			   
		}
		else {
			
			$tgreed = stripos($ctext, 'ไอที');
			$tgreed2 = stripos($ctext, 'ทำไรได้');
			$tgreed3 = stripos($ctext, 'ช่วยด้วย');
			$tgreed4 = stripos($ctext, 'wfh');
			$tgreed5 = stripos($ctext, 'ตารางงาน');
			$tgreed6 = stripos($ctext, '5555');
			$tgreed7 = stripos($ctext, 'โควิด19');

			if($ctext == 'สวัสดี'){
				$text = 'สวัสดีครับ ผม PerfectAI เป็นระบบรับแจ้งปัญหาอัตโนมัติครับผม :)';
			$messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			else if($tgreed !== false){
				$text = 'หวัดดีครับพี่ มีไรให้ผมช่วยครับ';
				$messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			else if($tgreed2 !== false){
				$text = 'ผมทำไรได้เหรอ พิมพ์ "ช่วยด้วย" เอาละกัน';
				$messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			else if($tgreed3 !== false){
				$text = 'เฮ้อว่าแล้ว...ตามนี้ดูเอาครับ'."\n".'"แจ้งปัญหา"=ขั้นตอนการแจ้ง Helpdesk'."\n".'"สถานะงาน"=เรียกดูสถานะงานที่เปิดไปแล้ว'."\n".'"wfh"=เรียกไฟล์ลงเวลา Work from home'."\n".'"ตารางงาน"=เรียกดูตารางงาน IT'."\n".'"menu"=เรียกดูmenu';
				$messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			else if($tgreed4 !== false){
				$text = 'https://docs.google.com/forms/d/e/1FAIpQLSfAVVwtDtRCFVGq9BqsnKc8Hq3HEj5CHgmSUNLsssQ9D049jA/viewform';
				$messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			else if($tgreed5 !== false){
				$messages = [
				'type' => 'image',
				'originalContentUrl' => 'https://raw.githubusercontent.com/nutpathakorn/RevonesQ/master/shift_it.jpg',
    				'previewImageUrl' => 'https://raw.githubusercontent.com/nutpathakorn/RevonesQ/master/shift_it.jpg',
				];
			}
			else if($tgreed6 !== false){
				$text = 'เหอะๆ ขำครับขำ';
				$messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			else if($tgreed7 !== false){
				
				$url_covid = 'https://covid19.th-stat.com/api/open/today';
			    	$getdetail_covid = file_get_contents($url_covid);
			    	$events_covid = json_decode($getdetail_covid, true);

			    	$covid_Confirmed = $getdetail_covid[0]['Confirmed'];
				$covid_Recovered = $getdetail_covid[0]['Recovered'];
				$covid_Hospitalized = $getdetail_covid[0]['Hospitalized'];
				$covid_Deaths = $getdetail_covid[0]['Deaths'];
				$covid_NewConfirmed = $getdetail_covid[0]['NewConfirmed'];
				$covid_NewRecovered = $getdetail_covid[0]['NewRecovered'];
				$covid_NewDeaths = $getdetail_covid[0]['NewDeaths'];
				$covid_UpdateDate = $getdetail_covid[0]['UpdateDate'];
				
	$text = 'อัพเดตวันที่ : '.$covid_UpdateDate."\n".'ยอดติดเชื้อรวม : '.$covid_Confirmed."\n".'ยอดรักษาหายรวม : '.$covid_Recovered."\n".'รักษาอยู่รวม : '.$covid_Hospitalized."\n".'เสียชีวิตรวม : '.$covid_Deaths."\n".'ติดเชื้อใหม่ : '.$covid_NewConfirmed."\n".'เสียชีวิตเพิ่ม : '.$covid_NewDeaths;
				$messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			else if ($ctext == 'ขอid') {
			    //$text = 'อยากทำงานแล้วเหรอ';	
				$text = 'Line UserID ของคุณคือ: '.$event['source']['userId'].' '.$event['source']['groupId'].' '.$event['source']['room'];
				$messages = [
				'type' => 'text',
				'text' => $text
				];

                        }
			else if($ctext == 'แจ้งปัญหา'){
				$text = 'สวัสดีครับ '."\xF0\x9F\x98\x81"."\n\n".'แจ้งปัญหาเริ่มด้วยการพิมพ์ รหัสพนักงาน+"&"+รายละเอียดปัญหา,สถานที่แจ้ง,เบอร์ติดต่อกลับของคุณได้เลยครับ'."\n\n"."\xE2\x98\x9D".' ตัวอย่างเช่น(รหัสพนักงานคือ12345)'."\n\n"."\xE2\x9C\x85".' 12345&คอมพิวเตอร์เปิดไม่ติดครับ,แผนกบัญชีชั้น17,เบอร์โทรศัพท์1888';
				$messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			else if($ctext == 'สถานะงาน'){
				$text = 'สวัสดีครับ '."\xF0\x9F\x98\x81"."\n\n".'เช็คสถานะงานเริ่มด้วยการพิมพ์ สถานะ+"&"+รหัสงานของคุณได้เลยครับ'."\n\n"."\xE2\x98\x9D".' ตัวอย่างเช่น(รหัสงานคือ10031)'."\n\n"."\xE2\x9C\x85".' สถานะ&10031';
				$messages = [
				'type' => 'text',
				'text' => $text
				];
			}
			else if($ctext == 'menu'){		
				
				$baction = [
				[
					'type' => 'message',
					'label' => 'แจ้งปัญหา',
					'text' => 'แจ้งปัญหา'
				],
				[
					'type' => 'message',
					'label' => 'สถานะงาน',
					'text' => 'สถานะงาน'
				],
				[
					'type' => 'uri',
					'label' => 'แจ้งผ่านเว็ป Helpdesk',
					'uri' => 'http://helpdesk.pf.co.th'
				]	
				];
				$btemplate = [
					'type' => 'buttons',
					"title" => 'น้องไอทีมีทางเลือกให้..',
					'text' => 'ขี้เกียจพิมพ์ก็กดเลยครับ',
					'actions' => $baction
				];
				$messages = [
					'type' => 'template',
					'altText' => 'this is a confirm template',
					'template' => $btemplate
				];
			}
		}
				
				
				
			
			
			// Build message to reply back
			
			
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

