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

$LanguageID = (int)$_GET['ID'];

// Dil bilgileri
$LanguageInfo = $DB->prepare("SELECT * FROM ihit_languages WHERE id = :id");
$LanguageInfo->execute(array(':id'=>$LanguageID));

if($LanguageInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$LanguageInfo = $LanguageInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editLanguage'])){
	
	// Değişkenler
	$Image = $_FILES['image'];
	$UploadDir = '../uploads/languages/';
	
	if($Image['error']==4){
		
		$LanguageIMG = $LanguageInfo['image'];
		
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
				$LanguageIMG = $newFileName.'.'.$fileExt;
				
			}
			
		}
		
	}
	
	$lang_name = $_POST['lang_name'];
	$lang_id = $_POST['lang_id'];
	$rank = $_POST['rank'];
	$status = $_POST['status'];
	
	// Görsel belirlendi, gelen bilgileri güncelleyelim.
	$updateLang = $DB->prepare("UPDATE ihit_languages SET image = :image, lang_name = :lang_name, lang_id = :lang_id, rank = :rank, status = :status WHERE id = :id");
	
	$updateLang->execute(
	
		array(
		
			':id' => $LanguageID,
			':lang_name' => $lang_name,
			':lang_id' => $lang_id,
			':rank' => $rank,
			':image' => $LanguageIMG,
			':status' => $status,
		
		)
	
	);
	
	if($updateLang->rowCount() > 0){
	
		header("Location:dilduzenle.php?success=true&ID=".$LanguageID);
		exit;
		
	}else{

		header("Location:dilduzenle.php?error=true&ID=".$LanguageID);
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
                                <h4 class="title">Dil Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Dil başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Dil düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" enctype="multipart/form-data" action="dilduzenle.php?ID=<?php echo $LanguageID; ?>">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sira</label>
                                                <input type="text" name="rank" class="form-control border-input" value="<?php echo $LanguageInfo['rank']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="lang_name" class="form-control border-input" value="<?php echo $LanguageInfo['lang_name']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Kisa Baslik</label>
                                                <input type="text" name="lang_id" class="form-control border-input" value="<?php echo $LanguageInfo['lang_id']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Görsel ( Değiştirmek istemiyorsanız ellemeyin. )</label>
                                                <input type="file" name="image" class="form-control border-input" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Durum</label>
												<select name="status" class="form-control border-input">
													
													<option value="0"<?php if($LanguageInfo['status']==0){ echo ' SELECTED';} ?>>Pasif</option>
													<option value="1"<?php if($LanguageInfo['status']==1){ echo ' SELECTED';} ?>>Aktif</option>
													
												</select>
                                            </div>
                                        </div>
										<input type="hidden" name="editLanguage" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Dil Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>