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
if(userInfo('role_users')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

$UserID = (int)$_GET['ID'];

// Kullanıcı bilgileri
$UserInfo = $DB->prepare("SELECT * FROM ihit_users WHERE id = :id");
$UserInfo->execute(array(':id'=>$UserID));

if($UserInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); } // basit güvenlik önlemi, diğer düzenleme sayfalarına da uygulandı.)

$UserInfo = $UserInfo->fetch(PDO::FETCH_ASSOC);

if($UserInfo['id'] == 1){ die("Root hesabını düzenleme yetkiniz bulunmamakta."); }
if(userInfo('id') != 1){ die("Bu ozelligi kullanamazsiniz."); }

if(isset($_POST['editUser'])){
	
	$role_form = $_POST['role_form'];
	$role_formexport = $_POST['role_formexport'];
	$role_projects = $_POST['role_projects'];
	$role_users = $_POST['role_users'];
	$role_sliders = $_POST['role_sliders'];
	$role_languages = $_POST['role_languages'];
	$role_pages = $_POST['role_pages'];
	$role_settings = $_POST['role_settings'];
	$role_localphoto = $_POST['role_localphoto'];
		
	// Gelen bilgileri güncelliyoruz
	$userUpdate = $DB->prepare("UPDATE ihit_users SET role_form = :role_form, role_formexport = :role_formexport, role_projects = :role_projects, role_users = :role_users, role_sliders = :role_sliders, role_languages = :role_languages, role_pages = :role_pages, role_settings = :role_settings, role_localphoto = :role_localphoto WHERE id = :id");
	
	$userUpdate->execute(
	
		array(
		
			':id' => $UserID,
			':role_form' => $role_form,
			':role_formexport' => $role_formexport,
			':role_projects' => $role_projects,
			':role_users' => $role_users,
			':role_sliders' => $role_sliders,
			':role_languages' => $role_languages,
			':role_pages' => $role_pages,
			':role_localphoto' => $role_localphoto,
			':role_settings' => $role_settings
		
		)
	
	);
	
	if($userUpdate->rowCount() > 0){
	
		header("Location:kullaniciizinleri.php?success=true&ID=".$UserID);
		exit;
		
	}else{

		header("Location:kullaniciizinleri.php?error=true&ID=".$UserID);
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
                                <h4 class="title">Kullanıcı Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Kullanıcı izinleri başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error']) AND !isset($_GET['shortname'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Kullanıcı izinleri düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" action="kullaniciizinleri.php?ID=<?php echo $UserID; ?>">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Form Listesi</label>
												<select name="role_form" class="form-control">
													<option value="0"<?php if($UserInfo['role_form']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($UserInfo['role_form']==1){ echo ' SELECTED';} ?>>Aktif</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Form Export</label>
												<select name="role_formexport" class="form-control">
													<option value="0"<?php if($UserInfo['role_formexport']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($UserInfo['role_formexport']==1){ echo ' SELECTED';} ?>>Aktif</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Projeler</label>
												<select name="role_projects" class="form-control">
													<option value="0"<?php if($UserInfo['role_projects']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($UserInfo['role_projects']==1){ echo ' SELECTED';} ?>>Aktif</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Kullanıcılar</label>
												<select name="role_users" class="form-control">
													<option value="0"<?php if($UserInfo['role_users']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($UserInfo['role_users']==1){ echo ' SELECTED';} ?>>Aktif</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sliderlar</label>
												<select name="role_sliders" class="form-control">
													<option value="0"<?php if($UserInfo['role_sliders']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($UserInfo['role_sliders']==1){ echo ' SELECTED';} ?>>Aktif</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Dil Yönetimi</label>
												<select name="role_languages" class="form-control">
													<option value="0"<?php if($UserInfo['role_languages']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($UserInfo['role_languages']==1){ echo ' SELECTED';} ?>>Aktif</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sayfalar</label>
												<select name="role_pages" class="form-control">
													<option value="0"<?php if($UserInfo['role_pages']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($UserInfo['role_pages']==1){ echo ' SELECTED';} ?>>Aktif</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sistem Ayarları</label>
												<select name="role_settings" class="form-control">
													<option value="0"<?php if($UserInfo['role_settings']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($UserInfo['role_settings']==1){ echo ' SELECTED';} ?>>Aktif</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Fotoğraf Yükleme</label>
												<select name="role_localphoto" class="form-control">
													<option value="0"<?php if($UserInfo['role_localphoto']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($UserInfo['role_localphoto']==1){ echo ' SELECTED';} ?>>Aktif</option>
												</select>
                                            </div>
                                        </div>
										
										<input type="hidden" name="editUser" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Izinleri Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>