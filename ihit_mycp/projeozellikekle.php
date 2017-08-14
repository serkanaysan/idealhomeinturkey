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

$ProjectID = (int)$_GET['ProjectID'];
$ProjectInfo = $DB->query("SELECT * FROM ihit_projects WHERE id = '".$ProjectID."'");
if($ProjectInfo->rowCount() < 1){ die('Proje Bulunamadı'); }
$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['addNewFeature'])){
	
	// Gelen bilgiler ile birlikte veritabanına kayıt edelim.			
	$category = $_POST['category'];
	$feature_id = $_POST['feature_id'];
	$rank = $_POST['rank'];
	
	$addFeature = $DB->prepare("INSERT INTO ihit_project_features (pid,category,feature_id,rank) VALUES (:pid,:category,:feature_id,:rank)");
	$addFeature->execute(array(
	
		':pid' => $ProjectInfo['id'],
		':category' => $category,
		':rank' => $rank,
		':feature_id' => $feature_id
	
	));
	
	if($addFeature->rowCount() > 0){
		
		$Msg = '
							
			<div class="alert alert-success">
				<span><b> Başarılı - </b> Ozellik başarıyla eklendi.</span>
			</div>
		
		';
		
	}else{
		
		$Msg = '
							
			<div class="alert alert-danger">
				<span><b> Başarısız - </b> Ozellik eklenemedi.</span>
			</div>
		
		';
		
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
    <div class="main-panel">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Ozellik Ekle</h4>
                            </div>
                            <div class="content">
							
							<?php echo $Msg; ?>
							
                                <form method="post" action="projeozellikekle.php?ProjectID=<?php echo $ProjectInfo['id']; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sıralama</label>
												<input type="text" class="form-control border-input" name="rank" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Kategori</label>
												<select name="category" class="form-control border-input">
													<option value="1">Genel Özellikler</option>
													<option value="2">Güvenlik Özellikleri</option>
													<option value="3">Sosyal İmkanlar</option>
													<option value="4">Spor Aktiviteleri</option>
													<option value="5">Teknik Özellikler</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Ozellik</label>
												<select name="feature_id" class="form-control border-input">
												<?php
												
													$getFeatures = $DB->query("SELECT * FROM ihit_home_features WHERE lang_id = '".$ProjectInfo['lang_id']."'");
													
													foreach($getFeatures->fetchAll(PDO::FETCH_ASSOC) as $Feature){
												
												?>
													<option value="<?php echo $Feature['id']; ?>"><?php echo $Feature['name']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
										<input type="hidden" name="addNewFeature" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Ozellik Ekle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>