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

$ServiceID = (int)$_GET['ID'];

// Hizmet bilgileri
$ServiceInfo = $DB->prepare("SELECT * FROM ihit_services WHERE id = :id");
$ServiceInfo->execute(array(':id'=>$ServiceID));

if($ServiceInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$ServiceInfo = $ServiceInfo->fetch(PDO::FETCH_ASSOC);
if(isset($_POST['editService'])){
	
	$lang_id = $_POST['lang_id'];
	$rank = $_POST['rank'];
	$icon = $_POST['icon'];
	$color = $_POST['color'];
	$title = $_POST['title'];
	$description = $_POST['description'];
	$url = $_POST['url'];
				
	// Gelen bilgileri güncelleyelim.
	$updateService = $DB->prepare("UPDATE ihit_services SET lang_id = :lang_id, rank = :rank, icon = :icon, color = :color, title = :title, description = :description, url = :url WHERE id = :id");
	
	$updateService->execute(
	
		array(
		
			':id' => $ServiceID,
			':lang_id' => $lang_id,
			':rank' => $rank,
			':icon' => $icon,
			':color' => $color,
			':title' => $title,
			':description' => $description,
			':url' => $url
		
		)
	
	);
	
	if($updateService->rowCount() > 0){
	
		header("Location:hizmetduzenle.php?success=true&ID=".$ServiceID);
		exit;
		
	}else{

		header("Location:hizmetduzenle.php?error=true&ID=".$ServiceID);
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
                                <h4 class="title">Link Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Link başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Link düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" action="hizmetduzenle.php?ID=<?php echo $ServiceID; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Dil</label>
												<select name="lang_id" class="form-control border-input">
												<?php
												
													$getLanguages = $DB->query("SELECT * FROM ihit_languages");
													
													foreach($getLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){
												
												?>
													<option value="<?php echo $Language['id']; ?>"<?php if($ServiceInfo['lang_id'] == $Language['id']){ echo "SELECTED"; }?>><?php echo $Language['lang_name']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sıra</label>
                                                <input type="text" name="rank" class="form-control border-input"  value="<?php echo $ServiceInfo['rank']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Icon [ ICON Listesi : http://fontawesome.io/icons/ ]</label>
                                                <input type="text" name="icon" class="form-control border-input" value="<?php echo $ServiceInfo['icon']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Renk [ Hex Kodu Olarak Girin. Örn : #e1e1e1 ]</label>
                                                <input type="text" name="color" class="form-control border-input" value="<?php echo $ServiceInfo['color']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="title" class="form-control border-input" value="<?php echo $ServiceInfo['title']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Aciklama</label>
                                                <input type="text" name="description" class="form-control border-input" value="<?php echo $ServiceInfo['description']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>URL</label>
                                                <input type="text" name="url" class="form-control border-input" value="<?php echo $ServiceInfo['url']; ?>">
                                            </div>
                                        </div>
										<input type="hidden" name="editService" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Link Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>