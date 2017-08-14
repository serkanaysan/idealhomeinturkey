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
if(userInfo('role_sliders')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

if(isset($_GET['SliderSil'])){
	
	$SliderID = (int)$_GET['SliderSil'];
	
	// Slider bilgisi
	$getSlider = $DB->query("SELECT * FROM ihit_sliders WHERE id = '".$SliderID."'");
	
	if($getSlider->rowCount() > 0){
	
	$getSlider = $getSlider->fetch(PDO::FETCH_ASSOC);
	
	$SliderID = $getSlider['id'];
	
	// Slider silme işlemi
	$DB->query("DELETE FROM ihit_sliders WHERE id = '".$SliderID."'");
	@unlink('../uploads/slider/'.$getSlider['image']);
	header("Location:sliderlar.php");
	
	}else{
		
		die('Bu slider bulunamadı.');
		
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
                                <h4 class="title">Slider Yönetimi 
										<div class="buttons" style="float:right;">
											<a href="sliderekle.php" class="btn btn-success various" data-fancybox-type="iframe">Yeni Ekle</a>
										</div></h4>
                                <p class="category"></p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>Sıra</th>
										<th>Dil</th>
										<th>Görsel</th>
										<th>Baslık</th>
										<th>URL</th>
										<th>Type</th>
										<th>Islem</th>
                                    </thead>
									   <tbody>
										<?php
											$getSliders = $DB->query("SELECT * FROM ihit_sliders ORDER BY rank DESC");
										
											foreach($getSliders->fetchAll(PDO::FETCH_ASSOC) as $Slider){
										?>
										  <tr>
											<td><?php echo $Slider['rank']; ?></td>
											<td><?php echo getLanguageName($Slider['language_id']); ?></td>
											<td><a href="<?php echo $SITE_URL.'/uploads/slider/'.$Slider['image']; ?>" id="fancy-photo"><img src="<?php echo $SITE_URL.'/uploads/slider/'.$Slider['image']; ?>" width="150" height="150" style="border-radius:4px;" alt=""/></a></td>
											<td><?php echo $Slider['title']; ?></td>
											<td><?php echo $Slider['url']; ?></td>
											<td><?php echo $FormTypes[$Slider['type']]; ?></td>
											<td><a href="sliderlar.php?SliderSil=<?php echo $Slider['id']; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="ti-trash"></i></a> <a class="various" data-fancybox-type="iframe" href="sliderduzenle.php?ID=<?php echo $Slider['id']; ?>"><i class="ti-pencil-alt2"></i></a></td>
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