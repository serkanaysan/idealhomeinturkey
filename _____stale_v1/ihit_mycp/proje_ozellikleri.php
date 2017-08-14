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
if(userInfo('role_projects')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

$ProjectID = (int)$_GET['ProjectID'];
$ProjectInfo = $DB->query("SELECT * FROM ihit_projects WHERE id = '".$ProjectID."'");
if($ProjectInfo->rowCount() < 1){ die('Proje Bulunamadı'); }
$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_GET['featureID'])){
		
	$featureID = (int)$_GET['featureID'];
	
	// Ozellik bilgisi
	$getFeature = $DB->query("SELECT * FROM ihit_project_features WHERE id = '".$featureID."'");
	
	if($getFeature->rowCount() > 0){
	
	$getFeature = $getFeature->fetch(PDO::FETCH_ASSOC);
	
	$featureID = $getFeature['id'];
	
	// Feature silme işlemi
	$DB->query("DELETE FROM ihit_project_features WHERE id = '".$featureID."'");
	header("Location:proje_ozellikleri.php?ProjectID=".$ProjectID);
	
	}else{
		
		die('Bu ozellik bulunamadı.');
		
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
                                <h4 class="title">Proje Ozellikleri &rsaquo; <?php echo $ProjectInfo['project_id']; ?> / <?php echo getLanguageName($ProjectInfo['lang_id']); ?>
										<div class="buttons" style="float:right;">
											<a href="projeler.php" class="btn btn-success">Proje Sayfasına Dön</a>
											<a href="projeozellikekle.php?ProjectID=<?php echo $ProjectInfo['id']; ?>" class="btn btn-success various" data-fancybox-type="iframe">Yeni Ozellik Ekle</a>
										</div></h4>
                                <p class="category"></p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>Sıralama</th>
										<th>Kategori</th>
										<th>Ozellik</th>
										<th>Islem</th>
                                    </thead>
									   <tbody>
										<?php
										
											$FeatureCategorys = array(
											
													1 => 'Genel Özellikler',
													2 => 'Güvenlik Özellikleri',
													3 => 'Sosyal İmkanlar',
													4 => 'Spor Aktiviteleri',
													5 => 'Teknik Özellikler',
											
											);
										
											$getFeatures = $DB->query("SELECT * FROM ihit_project_features WHERE pid = '".$ProjectInfo['id']."' ORDER BY rank");
										
											foreach($getFeatures->fetchAll(PDO::FETCH_ASSOC) as $Features){
												
												$FeatureInfo = $DB->query("SELECT * FROM ihit_home_features WHERE id = '".$Features['feature_id']."'");
												
												$FeatureInfo = $FeatureInfo->fetch(PDO::FETCH_ASSOC);
										?>
										  <tr>
											<td><?php echo $Features['rank']; ?></td>
											<td><?php echo $FeatureCategorys[$Features['category']]; ?></td>
											<td><?php echo $FeatureInfo['name']; ?></td>
											<td><a href="proje_ozellikleri.php?featureID=<?php echo $Features['id']; ?>&ProjectID=<?php echo $ProjectInfo['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="ti-trash"></i></a></td>
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