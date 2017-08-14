<?php
ob_start();
header("content-type:text/html;charset=utf-8");
session_start();
require_once('includes/Database.php');
require_once('includes/Functions.php');
require_once('includes/Config.php');
require_once 'parsecsv.lib.php';
require_once('includes/upload/class.upload.php');

// Admin yetki kontrolü
UserControl();

if(isset($_POST['newImport'])){

	// Yükleme İşlemi
	$Image = $_FILES['csvfile'];
	$UploadDir = '_csvforms/';
	
	// Görseli Yükleme İşlemi
	$ImageUpload = new Upload($Image);
	
	if ($ImageUpload->uploaded) {
		
		$newFileName =  'ideal_'.uniqid().'_'.strtotime('-67 second');
		$path = $_FILES['csvfile']['name'];
		$fileExt = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		
		$ImageUpload->file_new_name_body = $newFileName;
		$ImageUpload->Process($UploadDir);
		if (!$ImageUpload->processed){
			
			echo 'error : ' . $ImageUpload->error;
			exit;
			
		}else{

$csv = new parseCSV();
$csv->encoding('UTF-16', 'UTF-8');
$csv->delimiter = "\t";
$csv->parse($UploadDir.$newFileName.'.'.$fileExt);
//var_dump($UploadDir.$newFileName.'.'.$fileExt);

define('CRM_HOST', 'idealhomeinturkey.bitrix24.com'); // your CRM domain name
define('CRM_PORT', '443'); // CRM server port
define('CRM_PATH', '/crm/configs/import/lead.php'); // CRM server REST service path
define('CRM_LOGIN', 'crm.form@idealhomeinturkey.com'); // login of a CRM user able to manage leads
define('CRM_PASSWORD', 'takingoff123'); // password of a CRM user

$Msg = "Başarıyla içeri aktarıldı.";
	
foreach($csv->data as $row){
	
	$Email = $row['email'];
	$Fullname = $row['full_name'];
	$Phone = str_replace('p:',null,$row['phone_number']);
	$Country = $row['country'];
	$CampaignName = $row['campaign_name'];
	$CreatedAt = strtotime($row['created_time']);
	//Extras
	$status_id = $row['status_id'];
	$assigned_by_id = (int)$row['assigned_by_id'];
	$source_id = $row['source_id'];
	
	/*
	var_dump(

		array(
			
			'fullname' => $Fullname,
			'country' => $Country,
			'created_at' => strtotime('now'),
			'form_name' => $CampaignName,
			'email' => $Email,
			'phone' => $Phone,
			'status' => '1',
			'form_created_at' => $CreatedAt
		
		));
		*/
		
		
	// Insert Form Table
	$InsertForm = $DB->prepare('INSERT INTO ihit_form (fullname,country,created_at,form_name,email,phone,status,form_created_at) VALUES (:fullname,:country,:created_at,:form_name,:email,:phone,:status,:form_created_at)');
	$InsertForm->execute(

		array(
			
			'fullname' => $Fullname,
			'country' => $Country,
			'created_at' => strtotime('now'),
			'form_name' => $CampaignName,
			'email' => $Email,
			'phone' => $Phone,
			'status' => '1',
			'form_created_at' => $CreatedAt
		
		)

	);
	
	
	// FORMU CRM'E POSTLUYORUZ.

	$postData = array(
		'TITLE' => $CampaignName,
		'COMPANY_TITLE' => '-',
		'ADDRESS' => $Country,
		'NAME' => $Fullname,
		//'COMMENTS' => 'No Message',
		'PHONE_MOBILE' => $Phone,
		'EMAIL_WORK' => $Email,
		'ASSIGNED_BY_ID' => $assigned_by_id,
		'STATUS_ID' => $status_id,
		'SOURCE_ID' => $source_id
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
	
	
} //foreach

} // If upload csv

}

} // If newImport
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Kontrol Paneli</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	
    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css" rel="stylesheet"/>
	
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />
	
	<!-- CKEDITOR -->
	<script src="includes/ckeditor/ckeditor.js"></script>
	
    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">

</head>
<body>

<div class="wrapper">
    <div class="main-panel">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Form Import</h4>
                            </div>
                            <div class="content">
							
							<?php echo $Msg; ?>
							
                                <form method="post" enctype="multipart/form-data" action="formimport.php">
                                    <div class="row">
									
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>.CSV Dosyası</label>
                                                <input type="file" name="csvfile" class="form-control border-input" />
                                            </div>
                                        </div>

										<input type="hidden" name="newImport" value="<?php echo uniqId(); ?>" />
										
                                   <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Iceri Aktar</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
		