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

if(isset($_POST['addNewENV'])){
	
	// Gelen bilgiler ile birlikte veritabanına kayıt edelim.
	$socialarea_id = $_POST['socialarea_id'];
	$proximity = $_POST['proximity'];
	$time = $_POST['time'];
	$rank = $_POST['rank'];
	
	$addENV = $DB->prepare("INSERT INTO ihit_project_social_areas (project_id,socialarea_id,proximity,time,rank) VALUES (:project_id,:socialarea_id,:proximity,:time,:rank)");
	$addENV->execute(array(
	
		':project_id' => $ProjectInfo['id'],
		':socialarea_id' => $socialarea_id,
		':proximity' => $proximity,
		':rank' => $rank,
		':time' => $time
	
	));
	
	if($addENV->rowCount() > 0){
		
		$Msg = '
							
			<div class="alert alert-success">
				<span><b> Başarılı - </b> ENV başarıyla eklendi.</span>
			</div>
		
		';
		
	}else{
		
		$Msg = '
							
			<div class="alert alert-danger">
				<span><b> Başarısız - </b> ENV eklenemedi.</span>
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
                                <h4 class="title">ENV Ekle</h4>
                            </div>
                            <div class="content">
							
							<?php echo $Msg; ?>
							
                                <form method="post" action="projeenvekle.php?ProjectID=<?php echo $ProjectInfo['id']; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>ENV Secin</label>
												<select name="socialarea_id" class="form-control border-input">
												<?php
												
													$getENV = $DB->query("SELECT * FROM ihit_social_areas WHERE lang_id = '".$ProjectInfo['lang_id']."'");
													
													foreach($getENV->fetchAll(PDO::FETCH_ASSOC) as $ENV){
												
												?>
													<option value="<?php echo $ENV['id']; ?>"><?php echo $ENV['name']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Mesafe / Uzaklık</label>
												<input type="text" class="form-control border-input" name="proximity" />
                                            </div>
                                        </div>
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sıralama</label>
												<input type="text" class="form-control border-input" name="rank" />
                                            </div>
                                        </div>
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Süre</label>
												<input type="text" class="form-control border-input" name="time" />
                                            </div>
                                        </div>
										
										
										
										
										<input type="hidden" name="addNewENV" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">ENV Ekle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>