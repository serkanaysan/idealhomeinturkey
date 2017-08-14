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

$PhotoID = (int)$_GET['ID'];

// Fotoğraf bilgileri
$PhotoInfo = $DB->prepare("SELECT * FROM ihit_project_photos WHERE id = :id");
$PhotoInfo->execute(array(':id'=>$PhotoID));

if($PhotoInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$PhotoInfo = $PhotoInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editPhoto'])){
	
	// Değişkenler
	$Image = $_FILES['image'];
	$UploadDir = '../uploads/project/';
	
	if($Image['error']==4){
		
		$PhotoIMG = $PhotoInfo['image'];
		
	}else{
		
		// Görseli Yükleme İşlemi
		$ImageUpload = new Upload($Image);
		
		if ($ImageUpload->uploaded) {
			
			$newFileName =  'ideal_'.uniqid().'_'.strtotime('-67 second');
			$path = $_FILES['image']['name'];
			$fileExt = strtolower(pathinfo($path, PATHINFO_EXTENSION));
			
			$ImageUpload->file_new_name_body = $newFileName;
			$ImageUpload->Process($UploadDir);
			if (!$ImageUpload->processed){
				
				echo 'error : ' . $ImageUpload->error;
				exit;
				
			}else{
				
				// Yüklenen yeni görsei değişkene atıyoruz
				$PhotoIMG = $newFileName.'.'.$fileExt;
				
			}
				
		}
		
	}
	
	$title = $_POST['title'];
	$description = $_POST['description'];
	$rank = $_POST['rank'];
	//$project_id = $_POST['project_id'];
	
	// Görsel belirlendi, gelen bilgileri güncelleyelim.
	$updatePhoto = $DB->prepare("UPDATE ihit_project_photos SET title = :title, description = :description, project_id = :project_id, image = :image, rank = :rank WHERE id = :id");
	
	$updatePhoto->execute(
	
		array(
		
			':id' => $PhotoID,
			':title' => $title,
			':description' => $description,
			':project_id' => $PhotoInfo['project_id'],
			':rank' => $rank,
			':image' => $PhotoIMG
		
		)
	
	);
	
	if($updatePhoto->rowCount() > 0){
	
		header("Location:fotografduzenle.php?success=true&ID=".$PhotoID);
		exit;
		
	}else{

		header("Location:fotografduzenle.php?error=true&ID=".$PhotoID);
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
                                <h4 class="title">Fotograf Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Fotoğraf başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Fotoğraf düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" enctype="multipart/form-data" action="fotografduzenle.php?ID=<?php echo $PhotoID; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sıra</label>
                                                <input type="text" name="rank" class="form-control border-input" value="<?php echo $PhotoInfo['rank']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="title" class="form-control border-input" value="<?php echo $PhotoInfo['title']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Aciklama</label>
                                                <input type="text" name="description" class="form-control border-input" value="<?php echo $PhotoInfo['description']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Görsel * Değiştirmek istemiyorsanız ellemeyin.</label>
                                                <input type="file" name="image" class="form-control border-input" />
                                            </div>
                                        </div>
										<!--
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Proje ID</label>
												<select name="project_id" class="form-control border-input">
												<?php
													$getProjects = $DB->query("SELECT * FROM ihit_projects ORDER BY id DESC");
												
													foreach($getProjects->fetchAll(PDO::FETCH_ASSOC) as $Project){
												?>
													<option value="<?php echo $Project['id']; ?>"<?php if($Project['id']==$PhotoInfo['project_id']){ echo ' SELECTED'; } ?>><?php echo $Project['project_id']; ?></option>
													<?php } ?>
												</select>
                                            </div>
                                        </div>
										-->
										<input type="hidden" name="editPhoto" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Fotoğrafı Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>