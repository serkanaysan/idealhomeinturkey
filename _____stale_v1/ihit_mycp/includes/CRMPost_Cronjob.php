<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8");
session_start();
require_once('Database.php');
/* CRONJOB TEST
$fopen = fopen('cronjobtest.txt','a+');
fwrite($fopen,'Testtt');
*/

define('CRM_HOST', 'idealhomeinturkey.bitrix24.com'); // your CRM domain name
define('CRM_PORT', '443'); // CRM server port
define('CRM_PATH', '/crm/configs/import/lead.php'); // CRM server REST service path
define('CRM_LOGIN', 'crm.form@idealhomeinturkey.com'); // login of a CRM user able to manage leads
define('CRM_PASSWORD', 'takingoff123'); // password of a CRM user

//Formları Listele
$FormList = $DB->query("SELECT * FROM ihit_form WHERE crm_posted = '0'");

foreach($FormList->fetchAll(PDO::FETCH_ASSOC) as $Form){
	
	// FORMU CRM'E POSTLUYORUZ.
	$postData = array(
		'TITLE' => $Form['form_name'],
		'COMPANY_TITLE' => $Form['project_name'],
		'ADDRESS' => $Form['country'],
		'NAME' => $Form['fullname'],
		'COMMENTS' => $Form['message'],
		'PHONE_MOBILE' => $Form['phone'],
		'EMAIL_WORK' => $Form['email'],
		//'SOURCE_ID' => '',
		//'ASSIGNED_BY_ID' => $AssignedUser
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
			
			echo 'success';
			
		}else{
			
			echo $output->error_message;
			
		}
		
	}else{
		//echo 'Connection Failed! '.$errstr.' ('.$errno.')';
	}

	// crm_posted 1 ile güncelle
	$DB->query("UPDATE ihit_form SET crm_posted = '1' WHERE id = '{$Form['id']}'");
	
}

?>