<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Config.php');
require_once('includes/Database.php');
require_once('includes/Functions.php');

// Admin yetki kontrolü
UserControl();

// Role kontrolü
if(userInfo('role_form')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

$FormID = (int)$_GET['ID'];

if(!is_int($FormID)){

	die('Form ID Girilmemiş.');

}
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Form Detay</h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>Full Name</th>
										<th>E-Mail</th>
										<th>Phone</th>
										<th>Message</th>
										<th>Project ID</th>
										<th>Dial Code</th>
										<th>Country ID</th>
										<th>Country</th>
										<th>Referer Page</th>
										<th>Page</th>
										<th>IP Address</th>
										<th>Browser</th>
										<th>WLT.</th>
										<th>FCT.</th>
										<th>Form Name</th>
										<th>Project Name</th>
										<th>UTM Source</th>
										<th>UTM Medium</th>
										<th>UTM Campaign</th>
										<th>Language</th>
										<th>Device</th>
										<th>Status</th>
                                    </thead>
									   <tbody>
										<?php
											$formDetail = $DB->prepare("SELECT * FROM ihit_form WHERE id = :id");
											$formDetail->execute(array(':id'=>$FormID));
										
											$Form = $formDetail->fetch(PDO::FETCH_ASSOC);
										?>
										  <tr>
											<td><?php echo $Form['fullname']; ?></td>
											<td><?php echo $Form['email']; ?></td>
											<td><?php echo $Form['phone']; ?></td>
											<td><?php echo $Form['message']; ?></td>
											<td><?php echo $Form['project_id']; ?></td>
											<td><?php echo $Form['dialCode']; ?></td>
											<td><?php echo $Form['country_id']; ?></td>
											<td><?php echo $Form['country']; ?></td>
											<td><?php echo $Form['referral']; ?></td>
											<td><?php echo $Form['page']; ?></td>
											<td><?php echo $Form['ip_adress']; ?></td>
											<td><?php echo $Form['browser']; ?></td>
											<td><?php echo date('d/m/Y H:i:s',$Form['created_at']); ?></td>
											<td><?php echo date('d/m/Y H:i:s',$Form['form_created_at']); ?></td>
											<td><?php echo $Form['form_name']; ?></td>
											<td><?php echo $Form['project_name']; ?></td>
											<td><?php echo $Form['utm_source']; ?></td>
											<td><?php echo $Form['utm_medium']; ?></td>
											<td><?php echo $Form['utm_campaign']; ?></td>
											<td><?php echo $Form['language']; ?></td>
											<td><?php echo $Form['device']; ?></td>
											<td><?php echo $Form['status']; ?></td>
										  </tr>
										</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require_once('footer.php'); ?>