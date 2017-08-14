<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Config.php');
require_once('includes/Database.php');
require_once('includes/Functions.php');

// Admin yetki kontrolü
UserControl();

if(isset($_GET['dilSil'])){
	
	$LanguageID = (int)$_GET['dilSil'];
	
	// Slider bilgisi
	$getLanguage = $DB->query("SELECT * FROM ihit_languages WHERE id = '".$LanguageID."'");
	
	if($getLanguage->rowCount() > 0){
	
	$getLanguage = $getLanguage->fetch(PDO::FETCH_ASSOC);
	
	$LanguageID = $getLanguage['id'];
	
	// Slider silme işlemi
	$DB->query("DELETE FROM ihit_languages WHERE id = '".$LanguageID."'");
	header("Location:dilyonetimi.php");
	
	}else{
		
		die('Bu dil bulunamadı.');
		
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
                                <h4 class="title">Dil Yönetimi
										<div class="buttons" style="float:right;">
											<a href="dilekle.php" class="btn btn-success various" data-fancybox-type="iframe">Yeni Dil Ekle</a>
										</div></h4>
                                <p class="category"></p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>Sıra</th>
										<th>Görsel</th>
										<th>Baslık</th>
										<th>Kısa isim</th>
										<th>Durum</th>
                                    </thead>
									   <tbody>
										<?php
											$getLanguages = $DB->query("SELECT * FROM ihit_languages ORDER BY rank ASC");
										
											foreach($getLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){
										?>
										  <tr>
											<td><?php echo $Language['rank']; ?></td>
											<td><a href="<?php echo $SITE_URL.'/uploads/languages/'.$Language['image']; ?>" id="fancy-photo"><img src="<?php echo $SITE_URL.'/uploads/languages/'.$Language['image']; ?>" width="24" height="24" style="border-radius:4px;" alt=""/></a></td>
											<td><?php echo $Language['lang_name']; ?></td>
											<td><?php echo $Language['lang_id']; ?></td>
											<td><?php if($Language['status']==1){ echo 'Aktif'; }else{ echo 'Pasif'; } ?></td>
											<td><a href="dilyonetimi.php?dilSil=<?php echo $Language['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="ti-trash"></i></a> <a class="various" data-fancybox-type="iframe" href="dilduzenle.php?ID=<?php echo $Language['id']; ?>"><i class="ti-pencil-alt2"></i></a></td>
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