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
if(userInfo('role_settings')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

if(isset($_GET['linkSil'])){
	
	$linkID = (int)$_GET['linkSil'];
	
	// Link bilgisi
	$getLink = $DB->query("SELECT * FROM ihit_footerlink WHERE id = '".$linkID."'");
	
	if($getLink->rowCount() > 0){
	
	$getLink = $getLink->fetch(PDO::FETCH_ASSOC);
	
	$linkID = $getLink['id'];
	
	// Link silme işlemi
	$DB->query("DELETE FROM ihit_footerlink WHERE id = '".$linkID."'");
	header("Location:footerlinkleri.php");
	
	}else{
		
		die('Bu link bulunamadı.');
		
	}
	
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
<?php require_once('sidebar.php'); ?>
    <div class="main-panel">
<?php require_once('header.php'); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Footer Linkleri Yönetimi <a style="float:right;text-align:right;" class="various" data-fancybox-type="iframe" href="footerlinkekle.php"><i class="ti-plus"></i> Link Ekle</a></h4>
                                <p class="category"></p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>ID</th>
										<th>Dil</th>
										<th>Baslık</th>
										<th>URL</th>
                                    </thead>
									   <tbody>
										<?php
											$getFooterLinks = $DB->query("SELECT * FROM ihit_footerlink ORDER BY id ASC");
										
											foreach($getFooterLinks->fetchAll(PDO::FETCH_ASSOC) as $Footer){
										?>
										  <tr>
											<td><?php echo $Footer['id']; ?></td>
											<td><?php echo getLanguageName($Footer['lang_id']); ?></td>
											<td><?php echo $Footer['title']; ?></td>
											<td><?php echo $Footer['url']; ?></td>
											<td><a href="footerlinkleri.php?linkSil=<?php echo $Footer['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="ti-trash"></i></a> <a class="various" data-fancybox-type="iframe" href="footerlinkduzenle.php?ID=<?php echo $Footer['id']; ?>"><i class="ti-pencil-alt2"></i></a></td>
										  </tr>
										<?php } ?>
										</tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require_once('footer.php'); ?>