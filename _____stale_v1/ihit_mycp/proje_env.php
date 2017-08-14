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

if(isset($_GET['envID'])){
		
	$envID = (int)$_GET['envID'];
	
	// Ozellik bilgisi
	$getFeature = $DB->query("SELECT * FROM ihit_project_social_areas WHERE id = '".$envID."'");
	
	if($getFeature->rowCount() > 0){
	
		$getFeature = $getFeature->fetch(PDO::FETCH_ASSOC);
		
		$envID = $getFeature['id'];
		
		// ENV silme işlemi
		$DB->query("DELETE FROM ihit_project_social_areas WHERE id = '".$envID."'");
		header("Location:proje_env.php?ProjectID=".$ProjectID);
	
	}else{
		
		die('Bu env bulunamadı.');
		
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
                                <h4 class="title">ENV &rsaquo; <?php echo $ProjectInfo['project_id']; ?> / <?php echo getLanguageName($ProjectInfo['lang_id']); ?>
										<div class="buttons" style="float:right;">
											<a href="projeler.php" class="btn btn-success">Proje Sayfasına Dön</a>
											<a href="projeenvekle.php?ProjectID=<?php echo $ProjectInfo['id']; ?>" class="btn btn-success various" data-fancybox-type="iframe">Yeni ENV Ekle</a>
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
										
											$getENV = $DB->query("SELECT * FROM ihit_project_social_areas WHERE project_id = '".$ProjectInfo['id']."' ORDER BY rank");
										
											foreach($getENV->fetchAll(PDO::FETCH_ASSOC) as $ENV){
												
												$ENVInfo = $DB->query("SELECT * FROM ihit_social_areas WHERE id = '".$ENV['socialarea_id']."'");
												
												$ENVInfo = $ENVInfo->fetch(PDO::FETCH_ASSOC);
										?>
										  <tr>
											<td><?php echo $ENV['rank']; ?></td>
											<td><?php echo $ENVInfo['name']; ?></td>
											<td>
											<?php
												if(trim($ENV['proximity'])!=''){ echo $ENV['proximity'].' / '; }
												
												if(trim($ENV['time'])!=''){ echo $ENV['time']; }
											?>
											</td>
											<td>
											<a href="proje_env.php?envID=<?php echo $ENV['id']; ?>&ProjectID=<?php echo $ProjectInfo['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')">
												<i class="ti-trash"></i>
											</a>
											<!-- Düzenleme -->
											<a class="various" data-fancybox-type="iframe" href="proje_envduzenle.php?ID=<?php echo $ENV['id']; ?>">
												<i class="ti-pencil-alt2"></i>
											</a>
											
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