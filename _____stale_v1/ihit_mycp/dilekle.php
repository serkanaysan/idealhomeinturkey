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

if(isset($_POST['addNewLanguage'])){
	
	// Değişkenler
	$Image = $_FILES['image'];
	$UploadDir = '../uploads/languages/';
	
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
			
			// Görsel yükleme işlemi başarılı olduysa gelen bilgiler ile birlikte veritabanına kayıt edelim.			
			$lang_id = $_POST['lang_id'];
			$lang_name = $_POST['lang_name'];
			$rank = $_POST['rank'];
			$status = $_POST['status'];
			
			$addLanguage = $DB->prepare("INSERT INTO ihit_languages (rank,image,lang_id,lang_name,status) VALUES (:rank,:image,:lang_id,:lang_name,:status)");
			$addLanguage->execute(array(
			
				':image' => $newFileName.'.'.$fileExt,
				':lang_id' => $lang_id,
				':lang_name' => $lang_name,
				':rank' => $rank,
				':status' => $status
			
			));
			
			if($addLanguage->rowCount() > 0){
				
				$Msg = '
									
					<div class="alert alert-success">
						<span><b> Başarılı - </b> Dil başarıyla eklendi.</span>
					</div>
				
				';
				
			}else{
				
				$Msg = '
									
					<div class="alert alert-danger">
						<span><b> Başarısız - </b> Dil eklenemedi.</span>
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
                                <h4 class="title">Dil Ekle</h4>
                            </div>
                            <div class="content">
							
							<?php echo $Msg; ?>
							
                                <form method="post" enctype="multipart/form-data" action="dilekle.php">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sira</label>
                                                <input type="text" name="rank" class="form-control border-input" placeholder="Sira">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="lang_name" class="form-control border-input" placeholder="Baslik">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Kisa Baslik</label>
                                                <input type="text" name="lang_id" class="form-control border-input" placeholder="Kisa Baslik">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Görsel</label>
                                                <input type="file" name="image" class="form-control border-input" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Durum</label>
												<select name="status" class="form-control border-input">
													
													<option value="0">Pasif</option>
													<option value="1">Aktif</option>
													
												</select>
                                            </div>
                                        </div>
                                        
										<input type="hidden" name="addNewLanguage" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Dil Ekle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>