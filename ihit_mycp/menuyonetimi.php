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

if(isset($_GET['menuSil'])){
	
	$MenuID = (int)$_GET['menuSil'];
	
	// Menü bilgisi
	$getMenu = $DB->query("SELECT * FROM ihit_menu WHERE id = '".$MenuID."'");
	
	if($getMenu->rowCount() > 0){
	
	$getMenu = $getMenu->fetch(PDO::FETCH_ASSOC);
	
	$MenuID = $getMenu['id'];
	
	// Menü silme işlemi
	$DB->query("DELETE FROM ihit_menu WHERE id = '".$MenuID."'");
	header("Location:menuyonetimi.php");
	
	}else{
		
		die('Bu menü bulunamadı.');
		
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
                                <h4 class="title">Menü Yönetimi <a style="float:right;text-align:right;" class="various" data-fancybox-type="iframe" href="menuekle.php"><i class="ti-plus"></i> Menü Ekle</a></h4>
                                <p class="category"></p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>Sıra</th>
										<th>Üst Menü</th>
										<th>Dil</th>
										<th>Baslık</th>
										<th>URL</th>
                                    </thead>
									   <tbody>
										<?php
											$getMenus = $DB->query("SELECT * FROM ihit_menu WHERE menu_id != '0' ORDER BY id ASC");
										
											foreach($getMenus->fetchAll(PDO::FETCH_ASSOC) as $Menu){
										?>
										  <tr>
											<td><?php echo $Menu['rank']; ?></td>
											<td><?php echo getMenuInfo($Menu['menu_id'])['title']; ?></td>
											<td><?php echo getLanguageName($Menu['lang_id']); ?></td>
											<td><?php echo $Menu['title']; ?></td>
											<td><?php echo $Menu['url']; ?></td>
											<td><a href="menuyonetimi.php?menuSil=<?php echo $Menu['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="ti-trash"></i></a> <a class="various" data-fancybox-type="iframe" href="menuduzenle.php?ID=<?php echo $Menu['id']; ?>"><i class="ti-pencil-alt2"></i></a></td>
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