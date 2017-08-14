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

if(isset($_POST['addNewPhoto'])){
	
	$Images = array();
	foreach ($_FILES['image'] as $k => $l) {
	  foreach ($l as $i => $v) {
	   if (!array_key_exists($i, $Images))
		 $Images[$i] = array();
	   $Images[$i][$k] = $v;
	  }
	}

	foreach ($Images as $Image){
		
		// Değişkenler
		//$Image = $_FILES['image'];
		$UploadDir = '../uploads/project/';
		
		// Görseli Yükleme İşlemi
		$ImageUpload = new Upload($Image);
		
		if ($ImageUpload->uploaded) {
			var_dump($Image);
			$newFileName =  'ideal_'.uniqid().'_'.strtotime('-67 second');
			$path = $Image['name'];
			$fileExt = strtolower(pathinfo($path, PATHINFO_EXTENSION));
			
			$ImageUpload->file_new_name_body = $newFileName;
			$ImageUpload->Process($UploadDir);
			if (!$ImageUpload->processed){
				
				echo 'error : ' . $ImageUpload->error;
				exit;
				
			}else{
				
				// Görsel yükleme işlemi başarılı olduysa gelen bilgiler ile birlikte veritabanına kayıt edelim.			
				//$title = $_POST['title'];
				//$description = $_POST['description'];
				//$rank = $_POST['rank'];
				//$project_id = $_POST['project_id'];
				
				$addPhoto = $DB->prepare("INSERT INTO ihit_project_photos (image,project_id) VALUES (:image,:project_id)");
				$addPhoto->execute(array(
				
					':image' => $newFileName.'.'.$fileExt,
					':project_id' => $ProjectID
				
				));
				
				if($addPhoto->rowCount() > 0){
					
					$Msg = '
										
						<div class="alert alert-success">
							<span><b> Başarılı - </b> Fotoğraflar başarıyla eklendi.</span>
						</div>
					
					';
					
				}else{
					
					$Msg = '
										
						<div class="alert alert-danger">
							<span><b> Başarısız - </b> Fotoğraflar eklenemedi.</span>
						</div>
					
					';
					
				}
				
			}
			
		}else{
			
		
				$Msg = '
									
					<div class="alert alert-danger">
						<span><b> Başarısız - </b> Görsel yüklenemedi.</span>
					</div>
				
				';
			
		}
		
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
                                <h4 class="title">Toplu Fotograf Ekle</h4>
                            </div>
                            <div class="content">
							
							<?php echo $Msg; ?>
							
                                <form method="post" enctype="multipart/form-data" action="toplufotografekle.php?ProjectID=<?php echo $ProjectID; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Fotoğrafları seçin</label>
                                                <input type="file" name="image[]" class="form-control border-input" multiple />
                                            </div>
                                        </div>
										<input type="hidden" name="addNewPhoto" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Fotoğrafları Ekle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>