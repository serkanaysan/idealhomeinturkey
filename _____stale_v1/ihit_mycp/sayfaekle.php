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
if(userInfo('role_pages')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

if(isset($_POST['addNewPage'])){
	
	$title = $_POST['title'];
	$content = $_POST['content'];
	$lang_id = $_POST['lang_id'];
	$menu_id = $_POST['menu_id'];
	$uniqueid = $_POST['uniqueid'];
	$sef = $_POST['sef'];
	
	// Menü seçilmemiş ise
	if(trim($menu_id)==''){
		
		$menu_id = 0;
		
	}
	
	if(strlen($title)<3){
	
		$Msg = '
							
			<div class="alert alert-danger">
				<span><b> Başarısız - </b> Başlık en az 3 karakter olmalı.</span>
			</div>
		
		';
	
	}elseif(strlen($content)<10){
	
		$Msg = '
							
			<div class="alert alert-danger">
				<span><b> Başarısız - </b> Icerik en az 10 karakter olmalı.</span>
			</div>
		
		';
	
	}else{
	
		// Image Info
		$Image = $_FILES['image'];
		$UploadDir = '../uploads/page/';
		
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
				
				// Seo Friendly URL Tanımlama
				if(trim($_POST['sef'])!=''){
					
					$sef = $_POST['sef'];
					
				}else{
				
					$sef = permalink($title);
				
				}
				
				$addPage = $DB->prepare("INSERT INTO ihit_pages (uniqueid,title,content,image,lang_id,menu_id,sef) VALUES (:uniqueid,:title,:content,:image,:lang_id,:menu_id,:sef)");
				$addPage->execute(array(
				
					':title' => $title,
					':uniqueid' => $uniqueid,
					':content' => $content,
					':image' => $newFileName.'.'.$fileExt,
					':lang_id' => $lang_id,
					':menu_id' => $menu_id,
					':sef' => $sef
				
				));
				
				if($addPage->rowCount() > 0){
					
					$Msg = '
										
						<div class="alert alert-success">
							<span><b> Başarılı - </b> Sayfa başarıyla eklendi.</span>
						</div>
					
					';
					
				}else{
					
					$Msg = '
										
						<div class="alert alert-danger">
							<span><b> Başarısız - </b> Sayfa eklenemedi.</span>
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
	
	<!-- CKEDITOR -->
	<script src="includes/ckeditor/ckeditor.js"></script>
	
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
                                <h4 class="title">Sayfa Ekle</h4>
                            </div>
                            <div class="content">
							
							<?php echo $Msg; ?>
							
                                <form method="post" enctype="multipart/form-data" action="sayfaekle.php">
                                    <div class="row">
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="title" class="form-control border-input" placeholder="Baslik">
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
													<option value="<?php echo $Language['id']; ?>"><?php echo $Language['lang_name']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Menü (Opsiyonel)</label>
												<select name="menu_id" class="form-control border-input">
												<option value="0">-- Seçilmemiş --</option>
												<?php
												
													$getMenus = $DB->query("SELECT * FROM ihit_menu");
													
													foreach($getMenus->fetchAll(PDO::FETCH_ASSOC) as $Menu){
														
													$LeaderMenuCheck = $DB->query("SELECT * FROM ihit_menu WHERE menu_id = '".$Menu['id']."'");
													if($LeaderMenuCheck->rowCount() > 0){
												
												?>
													<option value="<?php echo $Menu['id']; ?>"><?php echo $Menu['title']; ?> / <?php echo getLanguageName($Menu['lang_id']); ?></option>
												<?php } } ?>
												</select>
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
                                                <label>Sef URL</label>
                                                <input type="text" name="sef" class="form-control border-input" placeholder="turkiye-de-yasamak">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Icerik</label>
												<textarea id="content" name="content" class="form-control border-input" cols="30" rows="10"></textarea>
												 <script>
													CKEDITOR.replace( 'content' );
												</script>
                                            </div>
                                        </div>
										<input type="hidden" name="addNewPage" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Sayfa Ekle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>