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
if(userInfo('role_localphoto')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

if(isset($_GET['LPSil'])){
	
	$LPID = (int)$_GET['LPSil'];
	
	// Dosya bilgisi
	$getFile = $DB->query("SELECT * FROM ihit_localphotos WHERE id = '".$LPID."'");
	
	if($getFile->rowCount() > 0){
	
	$getFile = $getFile->fetch(PDO::FETCH_ASSOC);
	
	$LPID = $getFile['id'];
	
	// Dosya silme işlemi
	$DB->query("DELETE FROM ihit_localphotos WHERE id = '".$LPID."'");
	@unlink('../uploads/'.$getFile['image']);
	header("Location:localphotos.php");
	
	}else{
		
		die('Bu dosya bulunamadı.');
		
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
                                <h4 class="title">Dosya/Fotograf Yönetimi
										<div class="buttons" style="float:right;">
											<a href="localphoto_ekle.php" class="btn btn-success various" data-fancybox-type="iframe">Yeni Dosya/Fotoğraf Ekle</a>
										</div></h4>
                                <p class="category"></p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>URL</th>
										<th>Islem</th>
                                    </thead>
									   <tbody>
										<?php
											$getFiles = $DB->query("SELECT * FROM ihit_localphotos ORDER BY id DESC");
										
											foreach($getFiles->fetchAll(PDO::FETCH_ASSOC) as $File){
										?>
										  <tr>
											<td><a href="<?php echo $SITE_URL.'/uploads/'.$File['image']; ?>" id="fancy-photo"><?php echo $SITE_URL.'/uploads/'.$File['image']; ?></td>
											<td><a href="localphotos.php?LPSil=<?php echo $File['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="ti-trash"></i></a>
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