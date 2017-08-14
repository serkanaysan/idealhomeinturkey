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

$EnvID = (int)$_GET['ID'];

// Fotoğraf bilgileri
$EnvInfo = $DB->prepare("SELECT * FROM ihit_social_areas WHERE id = :id");
$EnvInfo->execute(array(':id'=>$EnvID));

if($EnvInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$EnvInfo = $EnvInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editEnv'])){
	
	$lang_id = $_POST['lang_id'];
	$name = $_POST['name'];
	
	// Görsel belirlendi, gelen bilgileri güncelleyelim.
	$updateEnv = $DB->prepare("UPDATE ihit_social_areas SET lang_id = :lang_id, name = :name WHERE id = :id");
	
	$updateEnv->execute(
	
		array(
		
			':id' => $EnvID,
			':lang_id' => $lang_id,
			':name' => $name
		
		)
	
	);
	
	if($updateEnv->rowCount() > 0){
	
		header("Location:envduzenle.php?success=true&ID=".$EnvID);
		exit;
		
	}else{

		header("Location:envduzenle.php?error=true&ID=".$EnvID);
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
                                <h4 class="title">ENV Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> ENV başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> ENV düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" action="envduzenle.php?ID=<?php echo $EnvID; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="name" class="form-control border-input" value="<?php echo $EnvInfo['name']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Dil</label>
												<select name="lang_id" class="form-control border-input">
												<?php
												
													$getLanguages = $DB->query("SELECT * FROM ihit_languages");
													
													foreach($getLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){
												
												?>
													<option value="<?php echo $Language['id']; ?>"<?php if($Language['id']==$EnvInfo['lang_id']){ echo ' SELECTED';} ?>><?php echo $Language['lang_name']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
										<input type="hidden" name="editEnv" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">ENV Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>