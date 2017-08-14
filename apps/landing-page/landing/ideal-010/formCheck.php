<?php
require_once('Database.php');
require_once('Config.php');

define('CRM_HOST', 'idealhomeinturkey.bitrix24.com'); // your CRM domain name
define('CRM_PORT', '443'); // CRM server port
define('CRM_PATH', '/crm/configs/import/lead.php'); // CRM server REST service path
define('CRM_LOGIN', 'crm.form@idealhomeinturkey.com'); // login of a CRM user able to manage leads
define('CRM_PASSWORD', 'takingoff123'); // password of a CRM user

	if(strlen($_POST['fullname'])<3){
		
		echo 'الأسم الكالمل قصير جدا ! ';
		
	}elseif(strlen($_POST['phone'])<6){
		
		echo 'رقم هاتف غير صالح ';
		
	}elseif(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
		
		echo 'يرجى كتابة عنوان البريد الإلكتروني صالح للاستعمال . ';
		
	}else{
		
		$fullname = addslashes(htmlspecialchars($_POST['fullname']));
		$phone = addslashes(htmlspecialchars($_POST['phone']));
		$email = addslashes(htmlspecialchars($_POST['email']));
		$message = addslashes(htmlspecialchars($_POST['message']));
		$Country = addslashes(htmlspecialchars($_POST['country']));
		$dialCode = addslashes(htmlspecialchars($_POST['dialCode']));
		$iso2 = addslashes(htmlspecialchars($_POST['iso2']));
		$visitorInfo = base64_decode(base64_decode(base64_decode(base64_decode(base64_decode(base64_decode($_POST['visitorInfo']))))));
		$CRM_Users = array(1,6);//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$VisitorArray = unserialize($visitorInfo);
		
		// Form Notification
		$Veriables = base64_encode(serialize(array(
			'fullname'=>$fullname,
			'email'=>$email,
			'phone'=>$phone,
			'message'=>$message,
			'formname'=>$FormName,
			'projectname'=>$ProjectName,
			'created_at'=>addslashes(htmlspecialchars($VisitorArray['created_at']))
		)));

		file_get_contents('https://www.idealhomeinturkey.com/notification/sendnotification.php?veriables='.$Veriables);
		
		$InsertForm = $DB->prepare('INSERT INTO ihit_form (fullname,country,page,referral,ip_adress,browser,created_at,form_name,project_name,utm_source,utm_medium,utm_campaign,language,device,message,email,phone,status,project_id,country_id,dialCode,form_created_at) VALUES (:fullname,:country,:page,:referral,:ip_adress,:browser,:created_at,:form_name,:project_name,:utm_source,:utm_medium,:utm_campaign,:language,:device,:message,:email,:phone,:status,:project_id,:country_id,:dialCode,:form_created_at)');
		$InsertForm->execute(
		
			array(
				
				'fullname' => $fullname,
				'country' => $Country,
				'page' => addslashes(htmlspecialchars($VisitorArray['page'])),
				'referral' => addslashes(htmlspecialchars($VisitorArray['referral'])),
				'ip_adress' => addslashes(htmlspecialchars($VisitorArray['ip_adress'])),
				'browser' => addslashes(htmlspecialchars($VisitorArray['browser'])),
				'created_at' => addslashes(htmlspecialchars($VisitorArray['created_at'])),
				'form_name' => $FormName,
				'project_name' => $ProjectName,
				'utm_source' => addslashes(htmlspecialchars($VisitorArray['utm_source'])),
				'utm_medium' => addslashes(htmlspecialchars($VisitorArray['utm_medium'])),
				'utm_campaign' => addslashes(htmlspecialchars($VisitorArray['utm_campaign'])),
				'language' => 'Arabic',
				'device' => addslashes(htmlspecialchars($VisitorArray['device'])),
				'message' => $message,
				'email' => $email,
				'phone' => $phone,
				'status' => $Status,
				'project_id' => $ProjectCode,
				'country_id' => $iso2,
				'dialCode' => $dialCode,
				'form_created_at' => strtotime('now')
			
			)
		
		);
		
		echo 'success';
		
		// FORMU CRM'E POSTLUYORUZ.

		$postData = array(
			'TITLE' => $ProjectName,
			'COMPANY_TITLE' => $FormName,
			'ADDRESS' => $Country,
			'NAME' => $fullname,
			'COMMENTS' => $message,
			'PHONE_MOBILE' => $phone,
			'EMAIL_WORK' => $email,
			'ASSIGNED_BY_ID' => $AssignedUser
		);
		
		// append authorization data
		if (defined('CRM_AUTH'))
		{
			$postData['AUTH'] = CRM_AUTH;
		}
		else
		{
			$postData['LOGIN'] = CRM_LOGIN;
			$postData['PASSWORD'] = CRM_PASSWORD;
		}

		// open socket to CRM
		$fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
		if ($fp)
		{
			// prepare POST data
			$strPostData = '';
			foreach ($postData as $key => $value)
				$strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);

			// prepare POST headers
			$str = "POST ".CRM_PATH." HTTP/1.0\r\n";
			$str .= "Host: ".CRM_HOST."\r\n";
			$str .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$str .= "Content-Length: ".strlen($strPostData)."\r\n";
			$str .= "Connection: close\r\n\r\n";

			$str .= $strPostData;

			// send POST to CRM
			fwrite($fp, $str);

			// get CRM headers
			$result = '';
			while (!feof($fp))
			{
				$result .= fgets($fp, 128);
			}
			fclose($fp);

			// cut response headers
			$response = explode("\r\n\r\n", $result);

			$output = str_replace('\'','"',$response[1]);
			$output = json_decode($output);
			
			if($output->error == '201'){
				
				//echo 'success';
				
			}else{
				
				//echo $output->error_message;
				
			}
			
		}else{
			//echo 'Connection Failed! '.$errstr.' ('.$errno.')';
		}
		
	}
	
?>