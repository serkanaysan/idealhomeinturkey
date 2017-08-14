<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Config.php');
require_once('includes/Database.php');
require_once('includes/Functions.php');
require_once('includes/upload/class.upload.php');

$Msg = NULL;

// Admin yetki kontrolü
UserControl();
// Role kontrolü
if(userInfo('role_projects')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

$CityId = (int)$_GET['ID'];

$CityInfo = $DB->prepare("SELECT * FROM ihit_projects_citys WHERE id = :id");
$CityInfo->execute(array(':id'=>$CityId));

if($CityInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$CityInfo = $CityInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editCity'])){
	
	$lang_id = $_POST['lang_id'];
	$title = $_POST['title'];
	$rank = $_POST['rank'];
	
	// Görsel belirlendi, gelen bilgileri güncelleyelim.
	$updateCity = $DB->prepare("UPDATE ihit_projects_citys SET title = :title, rank = :rank, sef = :sef WHERE id = :id");
	
	$updateCity->execute(
	
		array(
		
			':id' => $CityId,
			':rank' => $rank,
			':title' => $title,
			':sef' => permalink($title)
		
		)
	
	);
	
	if($updateCity->rowCount() > 0){
	
		header("Location:sehir_duzenle.php?success=true&ID=".$CityId);
		exit;
		
	}else{

		header("Location:sehir_duzenle.php?error=true&ID=".$CityId);
		exit;

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
	
    <!--  CSS for Demo Purpose, don't include it in your project -->
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
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Sehir Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Sehir başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Sehir düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" action="sehir_duzenle.php?ID=<?php echo $CityId; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="title" class="form-control border-input" value="<?php echo $CityInfo['title']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sıra</label>
                                                <input type="text" name="rank" class="form-control border-input" value="<?php echo $CityInfo['rank']; ?>">
                                            </div>
                                        </div>
										<input type="hidden" name="editCity" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Sehiri Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>