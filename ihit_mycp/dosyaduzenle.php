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

$FileID = (int)$_GET['ID'];

// Dosya bilgileri
$FileInfo = $DB->prepare("SELECT * FROM ihit_project_files WHERE id = :id");
$FileInfo->execute(array(':id'=>$FileID));

if($FileInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$FileInfo = $FileInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editFile'])){
	
	// Değişkenler
	$File = $_FILES['file'];
	$UploadDir = '../uploads/project/files/';
	
	if($File['error']==4){
		
		$FileURL = $FileInfo['file_url'];
		
	}else{
		
		// Görseli Yükleme İşlemi
		$ImageUpload = new Upload($File);
		
		if ($ImageUpload->uploaded) {
			
			$newFileName =  'ideal_'.uniqid().'_'.strtotime('-67 second');
			$path = $_FILES['file']['name'];
			$fileExt = pathinfo($path, PATHINFO_EXTENSION);
			
			$ImageUpload->file_new_name_body = $newFileName;
			$ImageUpload->Process($UploadDir);
			if (!$ImageUpload->processed){
				
				echo 'error : ' . $ImageUpload->error;
				exit;
				
			}else{
				
				// Yüklenen yeni görsei değişkene atıyoruz
				$FileURL = $newFileName.'.'.$fileExt;
				
				// Ve eski dosyayı siliyoruz
				@unlink('../uploads/project/files/'.$FileInfo['file_url']);
				
			}
				
		}
		
	}
	
	$file_name = $_POST['file_name'];
	$rank = $_POST['rank'];
	
	// Görsel belirlendi, gelen bilgileri güncelleyelim.
	$UpdateFile = $DB->prepare("UPDATE ihit_project_files SET file_name = :file_name, rank = :rank, file_url = :file_url WHERE id = :id");
	
	$UpdateFile->execute(
	
		array(
		
			':id' => $FileID,
			':file_name' => $file_name,
			':rank' => $rank,
			':file_url' => $FileURL
		
		)
	
	);
	
	if($UpdateFile->rowCount() > 0){
	
		header("Location:dosyaduzenle.php?success=true&ID=".$FileID);
		exit;
		
	}else{

		header("Location:dosyaduzenle.php?error=true&ID=".$FileID);
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
                                <h4 class="title">Dosya Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Dosya başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Dosya düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" enctype="multipart/form-data" action="dosyaduzenle.php?ID=<?php echo $FileID; ?>">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sıralama</label>
                                                <input type="text" name="rank" class="form-control border-input" value="<?php echo $FileInfo['rank']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="file_name" class="form-control border-input" value="<?php echo $FileInfo['file_name']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Dosya ( Değiştirmek istemiyorsanız ellemeyin. )</label>
                                                <input type="file" name="file" class="form-control border-input" />
                                            </div>
                                        </div>
										<input type="hidden" name="editFile" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Dosyayı Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>