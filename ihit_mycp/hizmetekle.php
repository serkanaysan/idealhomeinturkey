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
if(userInfo('role_settings')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

if(isset($_POST['addNewService'])){
	
	$lang_id = $_POST['lang_id'];
	$rank = $_POST['rank'];
	$icon = $_POST['icon'];
	$color = $_POST['color'];
	$title = $_POST['title'];
	$description = $_POST['description'];
	$url = $_POST['url'];

	$addService = $DB->prepare("INSERT INTO ihit_services (lang_id,rank,icon,color,title,description,url) VALUES (:lang_id,:rank,:icon,:color,:title,:description,:url)");
	$addService->execute(array(

		':lang_id' => $lang_id,
		':rank' => $rank,
		':icon' => $icon,
		':color' => $color,
		':title' => $title,
		':description' => $description,
		':url' => $url

	));

	if($addService->rowCount() > 0){
		
		$Msg = '
							
			<div class="alert alert-success">
				<span><b> Başarılı - </b> Hizmet başarıyla eklendi.</span>
			</div>
		
		';
		
	}else{
		
		$Msg = '
							
			<div class="alert alert-danger">
				<span><b> Başarısız - </b> Hizmet eklenemedi.</span>
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
                                <h4 class="title">Hizmet Ekle</h4>
                            </div>
                            <div class="content">
							
							<?php echo $Msg; ?>
							
                                <form method="post" action="hizmetekle.php">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Dil</label>
												<select name="lang_id" class="form-control border-input">
												<?php
												
													$getLanguages = $DB->query("SELECT * FROM ihit_languages");
													
													foreach($getLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){
												
												?>
													<option value="<?php echo $Language['id']; ?>"><?php echo $Language['lang_name']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sıra</label>
                                                <input type="text" name="rank" class="form-control border-input" placeholder="Sıra">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Icon [ ICON Listesi : http://fontawesome.io/icons/ ]</label>
                                                <input type="text" name="icon" class="form-control border-input" placeholder="Icon">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Hover Renk [ Hex Kodu Olarak Girin. Örn : #e1e1e1 ]</label>
                                                <input type="text" name="color" class="form-control border-input" placeholder="#e1e1e1">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="title" class="form-control border-input" placeholder="Baslik">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Aciklama</label>
                                                <input type="text" name="description" class="form-control border-input" placeholder="description">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>URL</label>
                                                <input type="text" name="url" class="form-control border-input" placeholder="URL">
                                            </div>
                                        </div>
                                        
										<input type="hidden" name="addNewService" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Hizmet Ekle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>