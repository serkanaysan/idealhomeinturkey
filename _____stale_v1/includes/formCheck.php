<?php
ob_start();
session_start();
require_once('Database.php');

/*
	if(isset($_SESSION['lfstime']) AND $_SESSION['lfstime']>10000){
	
		$LFSDiff = (strtotime('now')-$_SESSION['lfstime']);
	
	}else{
		
		$LFSDiff = 67; // Daha öncesinde form göndermemiş, o yüzden 60 dan büyük bir rakam veriyoruz ki yapıyoruz ki engele takılmasın.
		
	}
	
	if($LFSDiff<150){
		
			echo 'You\'re faster than Alfa Romeo QV';

	}else
	*/	
	
	if(strlen($_POST['fullname'])<3){
		
		echo 'Name Too Short.' . $_POST['fullname'];
		
	}elseif(strlen($_POST['phone'])<6){
		
		echo 'Phone Number Too Short.';
		
	}elseif(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
		
		echo 'E-Mail Address Not Valid.';
		
	}else{
		
		$ProjectName = 'Ideal Web';
		$fullname = addslashes(htmlspecialchars($_POST['fullname']));
		$Page = addslashes(htmlspecialchars($_POST['page']));
		$FormName = addslashes(htmlspecialchars($_POST['formname']));
		$phone = addslashes(htmlspecialchars($_POST['phone']));
		$email = addslashes(htmlspecialchars($_POST['email']));
		$message = addslashes(htmlspecialchars($_POST['message']));
		$Country = addslashes(htmlspecialchars($_POST['country']));
		$dialCode = addslashes(htmlspecialchars($_POST['dialCode']));
		$iso2 = addslashes(htmlspecialchars($_POST['iso2']));
		$visitorInfo = base64_decode($_SESSION['ihit_visitorInfo_serialize']);
		$CRM_Users = array(1,6);
		
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
				'page' => $Page,
				'referral' => addslashes(htmlspecialchars($VisitorArray['referral'])),
				'ip_adress' => addslashes(htmlspecialchars($VisitorArray['ip_adress'])),
				'browser' => addslashes(htmlspecialchars($VisitorArray['browser'])),
				'created_at' => addslashes(htmlspecialchars($VisitorArray['created_at'])),
				'form_name' => $FormName,
				'project_name' => 'Ideal Web',
				'utm_source' => addslashes(htmlspecialchars($VisitorArray['utm_source'])),
				'utm_medium' => addslashes(htmlspecialchars($VisitorArray['utm_medium'])),
				'utm_campaign' => addslashes(htmlspecialchars($VisitorArray['utm_campaign'])),
				'language' => (int)$_COOKIE['ihit_clang'],
				'device' => addslashes(htmlspecialchars($VisitorArray['device'])),
				'message' => $message,
				'email' => $email,
				'phone' => $phone,
				'status' => '1',
				'project_id' => '',
				'country_id' => $iso2,
				'dialCode' => $dialCode,
				'form_created_at' => strtotime('now')
			
			)
		
		);
		
		echo 'success';
		
	}
	
?>