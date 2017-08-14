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

$BoxID = (int)$_GET['ID'];

// Kutu bilgileri
$BoxInfo = $DB->prepare("SELECT * FROM ihit_homepage_projects WHERE id = :id");
$BoxInfo->execute(array(':id'=>$BoxID));

if($BoxInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$BoxInfo = $BoxInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editBox'])){
	
	// Değişkenler
	$Image = $_FILES['image'];
	$UploadDir = '../uploads/homepage_projects/';
	
	if($Image['error']==4){
		
		$BoxIMG = $BoxInfo['image'];
		
	}else{
		
		// Görseli Yükleme İşlemi
		$ImageUpload = new Upload($Image);
		
		if ($ImageUpload->uploaded) {
			
			$newFileName =  'ideal_'.uniqid().'_'.strtotime('-67 second');
			$path = $_FILES['image']['name'];
			$fileExt = pathinfo($path, PATHINFO_EXTENSION);
			
			$ImageUpload->file_new_name_body = $newFileName;
			$ImageUpload->Process($UploadDir);
			if (!$ImageUpload->processed){
				
				echo 'error : ' . $ImageUpload->error;
				exit;
				
			}else{
				
				// Yüklenen yeni görsei değişkene atıyoruz
				$BoxIMG = $newFileName.'.'.$fileExt;
				
			}
				
		}
		
	}
	
	$city = $_POST['city'];
	$color = $_POST['color'];
	$price = $_POST['price'];
	$project_id = $_POST['project_id'];
	
	// Görsel belirlendi, gelen bilgileri güncelleyelim.
	$updatePhoto = $DB->prepare("UPDATE ihit_homepage_projects SET city = :city, color = :color, price = :price, project_id = :project_id, image = :image WHERE id = :id");
	
	$updatePhoto->execute(
	
		array(
		
			':id' => $BoxID,
			':city' => $city,
			':color' => $color,
			':price' => $price,
			':project_id' => $project_id,
			':image' => $BoxIMG
		
		)
	
	);
	
	if($updatePhoto->rowCount() > 0){
	
		header("Location:anasayfaprojeleri_duzenle.php?success=true&ID=".$BoxID);
		exit;
		
	}else{

		header("Location:anasayfaprojeleri_duzenle.php?error=true&ID=".$BoxID);
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
									<span><b> Başarılı - </b> Başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" enctype="multipart/form-data" action="anasayfaprojeleri_duzenle.php?ID=<?php echo $BoxID; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sehir</label>
                                                <input type="text" name="city" class="form-control border-input" value="<?php echo $BoxInfo['city']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Proje ID</label>
                                                <input type="text" name="project_id" class="form-control border-input" value="<?php echo $BoxInfo['project_id']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Fiyat</label>
                                                <input type="text" name="price" class="form-control border-input" value="<?php echo $BoxInfo['price']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Renk</label>
                                                <input type="text" name="color" class="form-control border-input" value="<?php echo $BoxInfo['color']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Görsel * Değiştirmek istemiyorsanız ellemeyin.</label>
                                                <input type="file" name="image" class="form-control border-input" />
                                            </div>
                                        </div>
										
										<input type="hidden" name="editBox" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>