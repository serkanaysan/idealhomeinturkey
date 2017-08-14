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
if(userInfo('role_pages')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

if(isset($_GET['sayfaSil'])){
	
	$PageID = (int)$_GET['sayfaSil'];
	
	// Sayfa bilgisi
	$getPage = $DB->query("SELECT * FROM ihit_pages WHERE id = '".$PageID."'");
	
	if($getPage->rowCount() > 0){
	
	$getPage = $getPage->fetch(PDO::FETCH_ASSOC);
	
	$PageID = $getPage['id'];
	
	// Sayfa silme işlemi
	$DB->query("DELETE FROM ihit_pages WHERE id = '".$PageID."'");
	// Sayfaya ait resim silinecek
	header("Location:sayfalar.php");
	
	}else{
		
		die('Bu sayfa bulunamadı.');
		
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
	
    <!--  CSS for Demo Purpose, don't include it in your slider  -->
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
                                <h4 class="title">Sayfalar 
										<div class="buttons" style="float:right;">
											<a href="sayfaekle.php" class="btn btn-success various" data-fancybox-type="iframe">Yeni Sayfa Ekle</a>
										</div></h4>
                                <p class="category"></p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>Eslesme ID</th>
										<th>Baslik</th>
										<th>URL</th>
										<th>Dil</th>
										<th>Islem</th>
                                    </thead>
									   <tbody>
										<?php
											$getPages = $DB->query("SELECT * FROM ihit_pages ORDER BY id DESC");
										
											foreach($getPages->fetchAll(PDO::FETCH_ASSOC) as $Page){
										?>
										  <tr>
											<td><?php echo $Page['uniqueid']; ?></td>
											<td><?php echo $Page['title']; ?></td>
											<td><?php 
											
											if($Page['lang_id']=='4'){
												
													echo $SITE_URL."/ar-sa/الصفحات/".$Page['sef'];
												
												}elseif($Page['lang_id']=='2'){
													
													echo $SITE_URL."/en-us/pages/".$Page['sef'];
													
												}
											?></td>
											<td><?php echo getLanguageName($Page['lang_id']); ?></td>
											<td>
												<!-- Sil -->
												<a href="sayfalar.php?sayfaSil=<?php echo $Page['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="ti-trash"></i></a>
												<!-- Düzenleme -->
												<a class="various" data-fancybox-type="iframe" href="sayfaduzenle.php?ID=<?php echo $Page['id']; ?>"><i class="ti-pencil-alt2"></i></a>
											</td>
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