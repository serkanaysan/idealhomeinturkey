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

if(isset($_GET['status']) AND isset($_GET['ProjectID'])){
	
	$ProjectID = (int)$_GET['ProjectID'];
	
	// Eğer status 1 ise aktif yap.
	if($_GET['status']==1){
		
		$UpdateProjectStatus = $DB->query("UPDATE ihit_projects SET status = '1' WHERE id = '".$ProjectID."'");
		header("Location:projeler.php");
		exit;
		
	// Eğer status 0 ise pasif yap
	}elseif($_GET['status']==0){
		
		$UpdateProjectStatus = $DB->query("UPDATE ihit_projects SET status = '0' WHERE id = '".$ProjectID."'");
		header("Location:projeler.php");
		exit;
		
	}
	
}

if(isset($_GET['projeSil'])){
	
	// Proje silme işlemi sadece 1 numaralı admin tarafından yapılabilir.
	// Güvenlik Kontrolü
	
	$projeSil = (int)$_GET['projeSil'];
	
	// Proje bilgisi
	$getProject = $DB->query("SELECT * FROM ihit_projects WHERE id = '".$projeSil."'");
	
	if($getProject->rowCount() > 0){
	
	$getProject = $getProject->fetch(PDO::FETCH_ASSOC);
	
	$projectId = $getProject['id'];
	
	// Proje silme işlemi
	$DB->query("DELETE FROM ihit_projects WHERE id = '".$projectId."'");
	// Projenin tüm resimleri döngüye sokulacak
	header("Location:projeler.php");
	
	}else{
		
		die('Bu proje bulunamadı.');
		
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
                                <h4 class="title">Projelerimiz
										<div class="buttons" style="float:right;">
											<a href="sehir_yonetimi.php" class="btn btn-success">Şehirler</a>
											<a href="projetipi_yonetimi.php" class="btn btn-success">Proje Tipleri</a>
											<a href="env_yonetimi.php" class="btn btn-success">ENV Yönetimi</a>
											<a href="fp_tabs.php" class="btn btn-success">Floor Plans Tabs</a>
											<a href="daireozellikleri.php" class="btn btn-success">Daire Özellikleri</a>
											<a href="projeekle.php" class="btn btn-success various" data-fancybox-type="iframe">Yeni Proje Ekle</a>
										</div></h4>
                                <p class="category"></p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>Proje ID</th>
										<th>Dil</th>
										<th>URL</th>
										<th>Durum</th>
										<th>Islem</th>
                                    </thead>
									   <tbody>
										<?php
											$getProjects = $DB->query("SELECT * FROM ihit_projects ORDER BY id DESC");
										
											foreach($getProjects->fetchAll(PDO::FETCH_ASSOC) as $Project){
										?>
										  <tr>
											<td><?php echo $Project['project_id']; ?></td>
											<td><?php echo getLanguageName($Project['lang_id']); ?></td>
											<td><?php
										
										if($Project['lang_id']==4){
											
											echo $SITE_URL; ?>/ar-sa/تركيا-العقارات/<?php echo getCityInfo($Project['city_id'],'sef'); ?>/<?php echo $Project['project_id'];
										
										}elseif($Project['lang_id']==2){
											
											echo $SITE_URL; ?>/en-us/turkey-real-estate/<?php echo getCityInfo($Project['city_id'],'sef'); ?>/<?php echo $Project['project_id'];
											
										}
										
										?>/</td>
											<td><?php if($Project['status']==0){ echo 'Pasif'; }else{ echo 'Aktif'; } ?></td>
											<td>
												<!-- Sil -->
												<a title="Projeyi Sil !" data-toggle="tooltip" href="projeler.php?projeSil=<?php echo $Project['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="ti-trash"></i></a>
												<!-- Düzenleme -->
												<a class="various" data-fancybox-type="iframe" title="Projeyi Düzenle" data-toggle="tooltip" href="projeduzenle.php?ID=<?php echo $Project['id']; ?>">
													<i class="ti-pencil-alt2"></i>
												</a>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<!-- Fotoğraflar -->
												<a title="Fotoğraflar" data-toggle="tooltip" href="proje_fotograflari.php?ProjectID=<?php echo $Project['id']; ?>"><i class="ti-gallery"></i></a>
												<!-- Etrafında Ne Var -->
												<a title="Etrafında Ne Var" data-toggle="tooltip" href="proje_env.php?ProjectID=<?php echo $Project['id']; ?>"><i class="ti-direction-alt"></i></a>
												<!-- Daire Özellikleri -->
												<a title="Daire Özellikleri" data-toggle="tooltip" href="proje_ozellikleri.php?ProjectID=<?php echo $Project['id']; ?>"><i class="ti-align-right"></i></a>
												<!-- Dosyalar -->
												<a title="Dosyalar" data-toggle="tooltip" href="dosyalar.php?ProjectID=<?php echo $Project['id']; ?>"><i class="ti-files"></i></a>
												<!-- Floor Plans -->
												<a title="Floor Plans" data-toggle="tooltip" href="proje_fp.php?ProjectID=<?php echo $Project['id']; ?>"><i class="ti-layout-tab-window"></i></a>
												<!-- Aktif & Pasif -->
												<?php
													if($Project['status']==0){
												?>
												<a title="Aktif Yap" data-toggle="tooltip" href="projeler.php?ProjectID=<?php echo $Project['id']; ?>&status=1"><i class="ti-control-play"></i></a>
												<?php }else{ ?>
												<a title="Pasif Yap" data-toggle="tooltip" href="projeler.php?ProjectID=<?php echo $Project['id']; ?>&status=0"><i class="ti-control-pause"></i></a>
												<?php } ?>
												
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