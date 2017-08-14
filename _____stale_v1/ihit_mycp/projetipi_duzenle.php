<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Config.php');
require_once('includes/Database.php');
require_once('includes/Functions.php');

$Msg = NULL;

// Admin yetki kontrolü
UserControl();
// Role kontrolü
if(userInfo('role_projects')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

$pTypeId = (int)$_GET['ID'];

$PTypeInfo = $DB->prepare("SELECT * FROM ihit_projects_types WHERE id = :id");
$PTypeInfo->execute(array(':id'=>$pTypeId));

if($PTypeInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$PTypeInfo = $PTypeInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editPType'])){
	
	$lang_id = $_POST['lang_id'];
	$title = $_POST['title'];
	$rank = $_POST['rank'];
	
	$updatePType = $DB->prepare("UPDATE ihit_projects_types SET lang_id = :lang_id, title = :title, rank = :rank WHERE id = :id");
	
	$updatePType->execute(
	
		array(
		
			':id' => $pTypeId,
			':lang_id' => $lang_id,
			':title' => $title,
			':rank' => $rank
		
		)
	
	);
	
	if($updatePType->rowCount() > 0){
	
		header("Location:projetipi_duzenle.php?success=true&ID=".$pTypeId);
		exit;
		
	}else{

		header("Location:projetipi_duzenle.php?error=true&ID=".$pTypeId);
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
                                <h4 class="title">Proje TipiDüzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Proje Tipi başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Proje Tipi düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" action="projetipi_duzenle.php?ID=<?php echo $pTypeId; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="title" class="form-control border-input" value="<?php echo $PTypeInfo['title']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sıra</label>
                                                <input type="text" name="rank" class="form-control border-input" value="<?php echo $PTypeInfo['rank']; ?>">
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
													<option value="<?php echo $Language['id']; ?>"<?php if($Language['id']==$PTypeInfo['lang_id']){ echo ' SELECTED';} ?>><?php echo $Language['lang_name']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
										<input type="hidden" name="editPType" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Ozellik Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>