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
if(userInfo('role_sliders')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

$SliderID = (int)$_GET['ID'];

// Slider bilgileri
$SliderInfo = $DB->prepare("SELECT * FROM ihit_sliders WHERE id = :id");
$SliderInfo->execute(array(':id'=>$SliderID));

if($SliderInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$SliderInfo = $SliderInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editSlider'])){
	
	// Değişkenler
	$Image = $_FILES['image'];
	$UploadDir = '../uploads/slider/';
	
	if($Image['error']==4){
		
		$SliderIMG = $SliderInfo['image'];
		
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
				$SliderIMG = $newFileName.'.'.$fileExt;
				
				// Ve eski dosyayı siliyoruz
				@unlink('../uploads/slider/'.$SliderInfo['image']);
				
			}
				
		}
		
	}
	
	$Rank = $_POST['rank'];
	$LanguageID = $_POST['language_id'];
	$Type = $_POST['type'];
	$URL = $_POST['url'];
	$Price = $_POST['price'];
	$Title = $_POST['title'];
	$Description = $_POST['description'];
				
	// Görsel belirlendi, gelen bilgileri güncelleyelim.
	$UpdateSlider = $DB->prepare("UPDATE ihit_sliders SET rank = :rank, language_id = :language_id, image = :image, type = :type, url = :url, price = :price, description = :description, title = :title WHERE id = :id");
	
	$UpdateSlider->execute(
	
		array(
		
			':id' => $SliderID,
			':rank' => $Rank,
			':language_id' => $LanguageID,
			':image' => $SliderIMG,
			':type' => $Type,
			':url' => $URL,
			':price' => $Price,
			':description' => $Description,
			':title' => $Title,
		
		)
	
	);
	
	if($UpdateSlider->rowCount() > 0){
	
		header("Location:sliderduzenle.php?success=true&ID=".$SliderID);
		exit;
		
	}else{

		header("Location:sliderduzenle.php?error=true&ID=".$SliderID);
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
                                <h4 class="title">Slider Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Slider başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Slider düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" enctype="multipart/form-data" action="sliderduzenle.php?ID=<?php echo $SliderID; ?>">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Sıralama</label>
                                                <input type="text" name="rank" class="form-control border-input" value="<?php echo $SliderInfo['rank']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Dil</label>
												<select name="language_id" class="form-control border-input">
												<?php
												
													$getLanguages = $DB->query("SELECT * FROM ihit_languages");
													
													foreach($getLanguages->fetchAll(PDO::FETCH_ASSOC) as $Language){
												
												?>
													<option value="<?php echo $Language['id']; ?>"<?php if($SliderInfo['language_id'] == $Language['id']){ echo "SELECTED"; }?>><?php echo $Language['lang_name']; ?></option>
												<?php } ?>
												</select>
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
                                                <label>Type</label>
												<select name="type" class="form-control border-input">
													<option value="1"<?php if($SliderInfo['type']==1){ echo " SELECTED"; } ?>>Proje</option>
													<option value="3"<?php if($SliderInfo['type']==3){ echo " SELECTED"; } ?>>Textdesc</option>
													<option value="0"<?php if($SliderInfo['type']==0){ echo " SELECTED"; } ?>>Form</option>
													<option value="2"<?php if($SliderInfo['type']==2){ echo " SELECTED"; } ?>>Özellik Yok</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>URL</label>
                                                <input type="text" name="url" class="form-control border-input" value="<?php echo $SliderInfo['url']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Fiyat</label>
                                                <input type="text" name="price" class="form-control border-input" value="<?php echo $SliderInfo['price']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Başlık</label>
                                                <input type="text" name="title" class="form-control border-input" value="<?php echo $SliderInfo['title']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Açıklama</label>
                                                <input type="text" name="description" class="form-control border-input" value="<?php echo $SliderInfo['description']; ?>">
                                            </div>
                                        </div>
										<input type="hidden" name="editSlider" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Sliderı Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>