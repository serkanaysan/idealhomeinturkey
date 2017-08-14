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

if(isset($_POST['addNewProject'])){
	
	// Gelen bilgiler ile birlikte veritabanına kayıt edelim.			
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
	$description = $_POST['description'];
	$related_1 = $_POST['related_1'];
	$related_2 = $_POST['related_2'];
	$related_3 = $_POST['related_3'];
	$zingat_rent_url = $_POST['zingat_rent_url'];
	$zingat_sale_url = $_POST['zingat_sale_url'];
	//$type_id = $_POST['type_id'];
	$short_text = $_POST['short_text'];
	
	// Aynı dil ve proje kodunda proje zaten var.
	$CheckProject = $DB->prepare("SELECT * FROM ihit_projects WHERE project_id = :project_id AND lang_id = :lang_id");
	$CheckProject->execute(array(':project_id'=>$project_id,':lang_id'=>$lang_id));
	
	if($CheckProject->rowCount() > 0){
		
		$Msg = '
							
			<div class="alert alert-danger">
				<span><b> Mevcutluk Tespit Edildi - </b> Bu proje kodunda ve bu dilde zaten proje eklenmiş.</span>
			</div>
		
		';
		
	}else{
	
		$addProject = $DB->prepare("INSERT INTO ihit_projects (project_id,project_status,lang_id,city_id,price,price_range,youtube_id,map_lat,map_lng,slogan,description,related_1,related_2,related_3,zingat_rent_url,zingat_sale_url,status,short_text) VALUES (:project_id,:project_status,:lang_id,:city_id,:price,:price_range,:youtube_id,:map_lat,:map_lng,:slogan,:description,:related_1,:related_2,:related_3,:zingat_rent_url,:zingat_sale_url,:status,:short_text)");
		$addProject->execute(array(
		
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
			':description' => $description,
			':related_1' => $related_1,
			':related_2' => $related_2,
			':related_3' => $related_3,
			':zingat_sale_url' => $zingat_sale_url,
			':zingat_rent_url' => $zingat_rent_url,
			//':type_id' => $type_id,
			':short_text' => $short_text,
			':status' => '0'
		
		));
		
		if($addProject->rowCount() > 0){
			
			$Msg = '
								
				<div class="alert alert-success">
					<span><b> Başarılı - </b> Proje başarıyla eklendi.</span>
				</div>
			
			';
			
		}else{
			
			$Msg = '
								
				<div class="alert alert-danger">
					<span><b> Başarısız - </b> Proje eklenemedi.</span>
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
                                <h4 class="title">Proje Ekle</h4>
                            </div>
                            <div class="content">
							
							<?php echo $Msg; ?>
							
                                <form method="post" action="projeekle.php">
                                    <div class="row">
									
									
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Proje Kodu</label>
                                                <input type="text" name="project_id" class="form-control border-input" placeholder="ideal-913" />
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
                                                <label>Şehir</label>
												<select name="city_id" class="form-control border-input">
												<?php
												
													$getCitys = $DB->query("SELECT * FROM ihit_projects_citys");
													
													foreach($getCitys->fetchAll(PDO::FETCH_ASSOC) as $City){
												
												?>
													<option value="<?php echo $City['id']; ?>"><?php echo $City['title']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
										<!--
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Proje Tipi</label>
												<select name="type_id" class="form-control border-input">
												<?php
												
													$getTypes = $DB->query("SELECT * FROM ihit_projects_types");
													
													foreach($getTypes->fetchAll(PDO::FETCH_ASSOC) as $Type){
												
												?>
													<option value="<?php echo $Type['id']; ?>"><?php echo $Type['title']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>-->
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Proje Durumu</label>
												<select name="project_status" class="form-control border-input">
													<option value="1">Hazır</option>
													<option value="2">Devam Ediyor</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Fiyat</label>
                                                <input type="text" name="price" class="form-control border-input" placeholder="$ 120.000" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Fiyat Aralığı</label>
                                                <select name="price_range" class="form-control border-input">
													<option value="0">-- Seçim --</option>
													<option value="1">0 $ - 50.000 $</option>
													<option value="2">50.000 $ - 100.000 $</option>
													<option value="3">100.000 $ - 150.000 $</option>
													<option value="4">150.000 $ - 200.000 $</option>
													<option value="5">200.000 $ - 250.000 $</option>
													<option value="6">250.000 $ - 300.000 $</option>
													<option value="7">300.000 $ - 400.000 $</option>
													<option value="8">400.000 $ - 500.000 $</option>
													<option value="9">500.000 $ - 1.000.000 $</option>
													<option value="10">1.000.000 $ +</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Youtube ID</label>
                                                <input type="text" name="youtube_id" class="form-control border-input" placeholder="8uCMf1Apoww" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Map Lat</label>
                                                <input type="text" name="map_lat" class="form-control border-input" placeholder="41.056797" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Map Lng</label>
                                                <input type="text" name="map_lng" class="form-control border-input" placeholder="28.680040" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Slogan</label>
                                                <input type="text" name="slogan" class="form-control border-input" placeholder="Slogan" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Zingat Satılık Url</label>
                                                <input type="text" name="zingat_sale_url" class="form-control border-input" placeholder="Örn : http://www.zingat.com/widgets/embed/bolgeraporu?konum=601&type=sale" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Zingat Kiralık Url</label>
                                                <input type="text" name="zingat_rent_url" class="form-control border-input" placeholder="Örn : http://www.zingat.com/widgets/embed/bolgeraporu?konum=601&type=rent" />
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Kısa Açıklama</label>
												<textarea id="short_text" name="short_text" class="form-control border-input" cols="30" rows="10"><?php echo $PageInfo['short_text']; ?></textarea>
												
												 <script>
													CKEDITOR.replace( 'short_text' );
												</script>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Açıklama</label>
												<textarea id="description" name="description" class="form-control border-input" cols="30" rows="10"></textarea>
												
												 <script>
													CKEDITOR.replace( 'description' );
												</script>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Benzer 1</label>
												<select name="related_1" class="form-control border-input">
												<option value="0">-- Secilmedi --</option>
												<?php
												
													$getProjects = $DB->query("SELECT * FROM ihit_projects");
													
													foreach($getProjects->fetchAll(PDO::FETCH_ASSOC) as $Project){
												
												?>
													<option value="<?php echo $Project['id']; ?>"><?php echo $Project['project_id']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Benzer 2</label>
												<select name="related_2" class="form-control border-input">
												<option value="0">-- Secilmedi --</option>
												<?php
												
													$getProjects = $DB->query("SELECT * FROM ihit_projects");
													
													foreach($getProjects->fetchAll(PDO::FETCH_ASSOC) as $Project){
												
												?>
													<option value="<?php echo $Project['id']; ?>"><?php echo $Project['project_id']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Benzer 3</label>
												<select name="related_3" class="form-control border-input">
												<option value="0">-- Secilmedi --</option>
												<?php
												
													$getProjects = $DB->query("SELECT * FROM ihit_projects");
													
													foreach($getProjects->fetchAll(PDO::FETCH_ASSOC) as $Project){
												
												?>
													<option value="<?php echo $Project['id']; ?>"><?php echo $Project['project_id']; ?></option>
												<?php } ?>
												</select>
                                            </div>
                                        </div>
										

										<input type="hidden" name="addNewProject" value="<?php echo uniqId(); ?>" />
										
                                   <div class="text-center">
                                        <button type="submit" class="btn btn-info btn-fill btn-wd">Proje Ekle</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>