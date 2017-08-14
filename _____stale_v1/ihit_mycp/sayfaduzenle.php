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

$PageID = (int)$_GET['ID'];

// Sayfa bilgileri
$PageInfo = $DB->prepare("SELECT * FROM ihit_pages WHERE id = :id");
$PageInfo->execute(array(':id'=>$PageID));

if($PageInfo->rowCount() < 1){ die("Bu sayfaya bulunamadi."); }

$PageInfo = $PageInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editPage'])){
	
	// Değişkenler
	$Image = $_FILES['image'];
	$UploadDir = '../uploads/page/';
	
	if($Image['error']==4){
		
		$PageIMG = $PageInfo['image'];
		
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
				$PageIMG = $newFileName.'.'.$fileExt;
				
				// Ve eski resimi siliyoruz
				@unlink('../uploads/page/'.$PageInfo['image']);
				
			}
				
		}
		
	}
	
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
				
	// Görsel belirlendi, gelen bilgileri güncelleyelim.
	$UpdatePage = $DB->prepare("UPDATE ihit_pages SET uniqueid = :uniqueid, title = :title, content = :content, image = :image, lang_id = :lang_id, menu_id = :menu_id, sef = :sef WHERE id = :id");
	
	$UpdatePage->execute(
	
		array(
		
			':id' => $PageID,
			':title' => $title,
			':uniqueid' => $uniqueid,
			':content' => $content,
			':image' => $PageIMG,
			':lang_id' => $lang_id,
			':menu_id' => $menu_id,
			':sef' => $sef
		
		)
	
	);
	
	if($UpdatePage->rowCount() > 0){
	
		header("Location:sayfaduzenle.php?success=true&ID=".$PageID);
		exit;
		
	}else{

		header("Location:sayfaduzenle.php?error=true&ID=".$PageID);
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
                                <h4 class="title">Sayfa Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Sayfa başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Sayfa düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" enctype="multipart/form-data" action="sayfaduzenle.php?ID=<?php echo $PageID; ?>">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Eslesme ID</label>
                                                <input type="text" name="uniqueid" class="form-control border-input" value="<?php echo $PageInfo['uniqueid']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Baslik</label>
                                                <input type="text" name="title" class="form-control border-input" value="<?php echo $PageInfo['title']; ?>">
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
													<option value="<?php echo $Language['id']; ?>"<?php if($PageInfo['lang_id'] == $Language['id']){ echo "SELECTED"; }?>><?php echo $Language['lang_name']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Menü</label>
												<select name="menu_id" class="form-control border-input">
												<option value="0">-- Seçilmemiş --</option>
												<?php
												
													$getMenus = $DB->query("SELECT * FROM ihit_menu");
													
													foreach($getMenus->fetchAll(PDO::FETCH_ASSOC) as $Menu){
														
													$LeaderMenuCheck = $DB->query("SELECT * FROM ihit_menu WHERE menu_id = '".$Menu['id']."'");
													if($LeaderMenuCheck->rowCount() > 0){
												
												?>
													<option value="<?php echo $Menu['id']; ?>"<?php if($Menu['id']==$PageInfo['menu_id']){ echo ' SELECTED'; }?>><?php echo $Menu['title']; ?> / <?php echo getLanguageName($Menu['lang_id']); ?></option>
												<?php } } ?>
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
                                                <label>Sef URL</label>
                                                <input type="text" name="sef" class="form-control border-input" value="<?php echo $PageInfo['sef']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Açıklama</label>
												<textarea id="content" name="content" class="form-control border-input" cols="30" rows="10"><?php echo $PageInfo['content']; ?></textarea>
												
												 <script>
													CKEDITOR.replace( 'content' );
												</script>
                                            </div>
                                        </div>
										<input type="hidden" name="editPage" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Sayfayı Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>