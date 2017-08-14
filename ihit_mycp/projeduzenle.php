<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Config.php');
require_once('includes/Database.php');
require_once('includes/Functions.php');

$Msg = NULL;

// Admin yetki kontrolü
UserControl();
// Role kontrolü
if(userInfo('role_projects')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

$ProjectID = (int)$_GET['ID'];

// Slider bilgileri
$ProjectInfo = $DB->prepare("SELECT * FROM ihit_projects WHERE id = :id");
$ProjectInfo->execute(array(':id'=>$ProjectID));

if($ProjectInfo->rowCount() < 1){ die("Bu bilgiye ulasilamadi."); }

$ProjectInfo = $ProjectInfo->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['editProject'])){
	
	// Projeye ait tüm proje tiplerini tablodan temizle.
	$DB->query("DELETE FROM ihit_project_types_id WHERE project_id = '".$ProjectInfo['id']."'");
	
	// Gelen proje tiplerini tabloya kayıt et.
	foreach($_POST['project_types'] as $type_id){
		
		$InsertProjectType = $DB->prepare("INSERT INTO ihit_project_types_id (project_id,type_id) VALUES (:project_id,:type_id)");
		$InsertProjectType->execute(array(':project_id'=>$ProjectInfo['id'],':type_id'=>$type_id));
		
	}
	
	$project_id = $_POST['project_id'];
	$project_status = $_POST['project_status'];
	$lang_id = $_POST['lang_id'];
	$city_id = $_POST['city_id'];
	$price = $_POST['price'];
	$price_range = $_POST['price_range'];
	$youtube_id = $_POST['youtube_id'];
	$map_lat = $_POST['map_lat'];
	$map_lng = $_POST['map_lng'];
	$slogan = $_POST['slogan'];
	$project_page_image = $_POST['project_page_image'];
	$description = $_POST['description'];
	$related_1 = $_POST['related_1'];
	$related_2 = $_POST['related_2'];
	$related_3 = $_POST['related_3'];
	$zingat_sale_url = $_POST['zingat_sale_url'];
	$zingat_rent_url = $_POST['zingat_rent_url'];
	//$type_id = $_POST['type_id'];
	$short_text = $_POST['short_text'];
				
	// gelen bilgileri güncelleyelim.
	$updateProject = $DB->prepare("UPDATE ihit_projects SET zingat_rent_url = :zingat_rent_url, zingat_sale_url = :zingat_sale_url, project_id = :project_id, project_status = :project_status, lang_id = :lang_id, city_id = :city_id, price = :price, price_range = :price_range, youtube_id = :youtube_id, map_lat = :map_lat, map_lng = :map_lng, slogan = :slogan, project_page_image = :project_page_image, description = :description, related_1 = :related_1, related_2 = :related_2, related_3 = :related_3, short_text = :short_text WHERE id = :id");
	
	$updateProject->execute(
	
		array(
		
			':id' => $ProjectInfo['id'],
			':project_id' => $project_id,
			':project_status' => $project_status,
			':lang_id' => $lang_id,
			':city_id' => $city_id,
			':price' => $price,
			':price_range' => $price_range,
			':youtube_id' => $youtube_id,
			':map_lat' => $map_lat,
			':map_lng' => $map_lng,
			':slogan' => $slogan,
			':project_page_image' => $project_page_image,
			':description' => $description,
			':related_1' => $related_1,
			':related_2' => $related_2,
			':related_3' => $related_3,
			':zingat_sale_url' => $zingat_sale_url,
			':zingat_rent_url' => $zingat_rent_url,
			':short_text' => $short_text,
			//':type_id' => $type_id
		
		)
	
	);
	
	if($updateProject->rowCount() > 0){
	
		header("Location:projeduzenle.php?success=true&ID=".$ProjectID);
		exit;
		
	}else{

		header("Location:projeduzenle.php?error=true&ID=".$ProjectID);
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
	
    <!-- Fonts and icons -->
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
                                <h4 class="title">Proje Düzenle</h4>
                            </div>
                            <div class="content">
							
							<?php
								if(isset($_GET['success'])){
							?>
							
								<div class="alert alert-success">
									<span><b> Başarılı - </b> Proje başarıyla düzenlendi.</span>
								</div>
							
							<?php
								}elseif(isset($_GET['error'])){
							?>
							
								<div class="alert alert-danger">
									<span><b> Başarısız - </b> Proje düzenlenemedi.</span>
								</div>
							
							<?php } ?>
							
                                <form method="post" action="projeduzenle.php?ID=<?php echo $ProjectID; ?>">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Proje Kodu</label>
                                                <input type="text" name="project_id" class="form-control border-input" value="<?php echo $ProjectInfo['project_id']; ?>">
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
													<option value="<?php echo $Language['id']; ?>"<?php if($ProjectInfo['lang_id'] == $Language['id']){ echo "SELECTED"; }?>><?php echo $Language['lang_name']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Şehir</label>
												<select name="city_id" class="form-control border-input">
												<?php
												
													$getCitys = $DB->query("SELECT * FROM ihit_projects_citys");
													
													foreach($getCitys->fetchAll(PDO::FETCH_ASSOC) as $City){
												
												?>
													<option value="<?php echo $City['id']; ?>"<?php if($ProjectInfo['city_id'] == $City['id']){ echo "SELECTED"; }?>><?php echo $City['title']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Proje Tipi Multiple</label>
												<select name="project_types[]" class="form-control border-input" multiple>
												<?php
												
													$getTypes = $DB->query("SELECT * FROM ihit_projects_types");
													
													foreach($getTypes->fetchAll(PDO::FETCH_ASSOC) as $Type){
														
														$checkProjectType = $DB->prepare("SELECT * FROM ihit_project_types_id WHERE project_id = :project_id AND type_id = :type_id");
														$checkProjectType->execute(array(':project_id'=>$ProjectInfo['id'],':type_id'=>$Type['id']));
												
												?>
													<option value="<?php echo $Type['id']; ?>"<?php if($checkProjectType->rowCount() > 0){ echo "SELECTED"; }?>><?php echo $Type['title']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Proje Durumu</label>
												<select name="project_status" class="form-control border-input">
													<option value="1"<?php if($ProjectInfo['project_status']==1){ echo ' SELECTED'; }?>>Hazır</option>
													<option value="2"<?php if($ProjectInfo['project_status']==2){ echo ' SELECTED'; }?>>Devam Ediyor</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Fiyat</label>
                                                <input type="text" name="price" class="form-control border-input" value="<?php echo $ProjectInfo['price']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Fiyat Aralığı</label>
                                                <select name="price_range" class="form-control border-input">
													<option value="0">-- Seçim --</option>
													<option value="1"<?php if($ProjectInfo['price_range']==1){ echo ' SELECTED'; } ?>>0 $ - 50.000 $</option>
													<option value="2"<?php if($ProjectInfo['price_range']==2){ echo ' SELECTED'; } ?>>50.000 $ - 100.000 $</option>
													<option value="3"<?php if($ProjectInfo['price_range']==3){ echo ' SELECTED'; } ?>>100.000 $ - 150.000 $</option>
													<option value="4"<?php if($ProjectInfo['price_range']==4){ echo ' SELECTED'; } ?>>150.000 $ - 200.000 $</option>
													<option value="5"<?php if($ProjectInfo['price_range']==5){ echo ' SELECTED'; } ?>>200.000 $ - 250.000 $</option>
													<option value="6"<?php if($ProjectInfo['price_range']==6){ echo ' SELECTED'; } ?>>250.000 $ - 300.000 $</option>
													<option value="7"<?php if($ProjectInfo['price_range']==7){ echo ' SELECTED'; } ?>>300.000 $ - 400.000 $</option>
													<option value="8"<?php if($ProjectInfo['price_range']==8){ echo ' SELECTED'; } ?>>400.000 $ - 500.000 $</option>
													<option value="9"<?php if($ProjectInfo['price_range']==9){ echo ' SELECTED'; } ?>>500.000 $ - 1.000.000 $</option>
													<option value="10"<?php if($ProjectInfo['price_range']==10){ echo ' SELECTED'; } ?>>1.000.000 $ +</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Youtube ID</label>
                                                <input type="text" name="youtube_id" class="form-control border-input" value="<?php echo $ProjectInfo['youtube_id']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Map Lat</label>
                                                <input type="text" name="map_lat" class="form-control border-input" value="<?php echo $ProjectInfo['map_lat']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Map Lng</label>
                                                <input type="text" name="map_lng" class="form-control border-input" value="<?php echo $ProjectInfo['map_lng']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Slogan</label>
                                                <input type="text" name="slogan" class="form-control border-input" value="<?php echo $ProjectInfo['slogan']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Proje Sayfası Görseli</label>
                                                <input type="text" name="project_page_image" class="form-control border-input" value="<?php echo $ProjectInfo['project_page_image']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Zingat Kiralık URL</label>
                                                <input type="text" name="zingat_rent_url" class="form-control border-input" value="<?php echo htmlspecialchars($ProjectInfo['zingat_rent_url']); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Zingat Satılık URL</label>
                                                <input type="text" name="zingat_sale_url" class="form-control border-input" value="<?php echo htmlspecialchars($ProjectInfo['zingat_sale_url']); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Kısa Açıklama</label>
												<textarea id="short_text" name="short_text" class="form-control border-input" cols="30" rows="10"><?php echo $ProjectInfo['short_text']; ?></textarea>
												
												 <script>
													CKEDITOR.replace( 'short_text' );
												</script>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Açıklama</label>
												<textarea id="description" name="description" class="form-control border-input" cols="30" rows="10"><?php echo $ProjectInfo['description']; ?></textarea>
												
												 <script>
													CKEDITOR.replace( 'description' );
												</script>
                                            </div>
                                        </div>
										
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Benzer 1</label>
												<select name="related_1" class="form-control border-input">
												<option value="0"<?php if($ProjectInfo['related_1']==0){ echo ' SELECTED'; }?>>-- Seçilmemiş --</option>
												<?php
												
													$getProjects = $DB->query("SELECT * FROM ihit_projects ORDER BY id DESC");
													
													foreach($getProjects->fetchAll(PDO::FETCH_ASSOC) as $Project){
												
												?>
													<option value="<?php echo $Project['id']; ?>"<?php if($ProjectInfo['related_1']==$Project['id']){ echo ' SELECTED'; }?>><?php echo $Project['project_id']; ?> - <?php echo getLanguageName($Project['lang_id']); ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Benzer 2</label>
												<select name="related_2" class="form-control border-input">
												<option value="0"<?php if($ProjectInfo['related_2']==0){ echo ' SELECTED'; }?>>-- Seçilmemiş --</option>
												<?php
												
													$getProjects = $DB->query("SELECT * FROM ihit_projects ORDER BY id DESC");
													
													foreach($getProjects->fetchAll(PDO::FETCH_ASSOC) as $Project){
												
												?>
													<option value="<?php echo $Project['id']; ?>"<?php if($ProjectInfo['related_2']==$Project['id']){ echo ' SELECTED'; }?>><?php echo $Project['project_id']; ?> - <?php echo getLanguageName($Project['lang_id']); ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Benzer 3</label>
												<select name="related_3" class="form-control border-input">
												<option value="0"<?php if($ProjectInfo['related_3']==0){ echo ' SELECTED'; }?>>-- Seçilmemiş --</option>
												<?php
												
													$getProjects = $DB->query("SELECT * FROM ihit_projects ORDER BY id DESC");
													
													foreach($getProjects->fetchAll(PDO::FETCH_ASSOC) as $Project){
												
												?>
													<option value="<?php echo $Project['id']; ?>"<?php if($ProjectInfo['related_3']==$Project['id']){ echo ' SELECTED'; }?>><?php echo $Project['project_id']; ?> - <?php echo getLanguageName($Project['lang_id']); ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
										<input type="hidden" name="editProject" value="<?php echo uniqId(); ?>" />
										
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Projeyi Düzenle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>