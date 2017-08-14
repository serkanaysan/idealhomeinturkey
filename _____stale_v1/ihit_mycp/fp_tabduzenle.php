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

$TabID = (int)$_GET['ID'];

// Fotoğraf bilgileri
$TabInfo = $DB->prepare("SELECT * FROM ihit_fp_tabs WHERE id = :id");
$TabInfo->execute(array(':id'=>$TabID));

if($TabInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$TabInfo = $TabInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editTab'])){
	
	$tab_name = $_POST['tab_name'];
	
	// Görsel belirlendi, gelen bilgileri güncelleyelim.
	$updateEnv = $DB->prepare("UPDATE ihit_fp_tabs SET tab_name = :tab_name WHERE id = :id");
	
	$updateEnv->execute(
	
		array(
		
			':id' => $TabID,
			':tab_name' => $tab_name
		
		)
	
	);
	
	if($updateEnv->rowCount() > 0){
	
		header("Location:fp_tabduzenle.php?success=true&ID=".$TabID);
		exit;
		
	}else{

		header("Location:fp_tabduzenle.php?error=true&ID=".$TabID);
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
                                <h4 class="title">Tab Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Tab başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Tab düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" action="fp_tabduzenle.php?ID=<?php echo $TabID; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Tab Baslik</label>
                                                <input type="text" name="tab_name" class="form-control border-input" value="<?php echo $TabInfo['tab_name']; ?>">
                                            </div>
                                        </div>
										<input type="hidden" name="editTab" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Tab Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>