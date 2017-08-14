<?php
ob_start();
header("Content-Type:text/html; Charset=UTF-8;");
session_start();
require_once('includes/Config.php');
require_once('includes/Database.php');
require_once('includes/Functions.php');

// Admin yetki kontrolü
UserControl();

// Role kontrolü
if(userInfo('role_form')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

if(isset($_GET['deleteForm'])){
	
	// Form ID
	$FormID = (int)$_GET['deleteForm'];
	
	$FormUpdate = $DB->query("UPDATE ihit_form SET formdelete = '0' WHERE id = '".$FormID."'");
	
	header("Location:formlistesi.php?sayfa=".$_GET['sayfa']);
	
}

if(isset($_GET['exportList'])){

	// Role kontrolü
	if(userInfo('role_formexport')==0){ header("Location:controlpanel.php"); die('Bu sayfa icin yetkili degilsiniz.'); }

	function cleanData(&$str)
	{
		$str = preg_replace("/\t/", "\\t", $str);
		$str = preg_replace("/\r?\n/", "\\n", $str);
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}

	// filename for download
	$filename = "website_data_" . date('Y_m_d') . ".xls";
	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
	
	$ExportArray = array();
	
	$getForms = $DB->query("SELECT * FROM ihit_form WHERE formdelete = '1' ORDER BY form_created_at DESC");

	foreach($getForms->fetchAll(PDO::FETCH_ASSOC) as $Form){
		
		$Form['created_at'] = date('d/m/Y',$Form['created_at']);
		$Form['form_created_at'] = date('d/m/Y',$Form['form_created_at']);
		
		$ExportArray[] = $Form;
		
	}
	
	header("Content-Type: text/plain");

	$flag = false;
	foreach($ExportArray as $row) {
	if(!$flag) {
	  // display field/column names as first row
	  $csv_output = implode("\t", array_keys($row)) . "\r\n";
	  echo chr(255) . chr(254) . mb_convert_encoding($csv_output, 'UTF-16LE', 'UTF-8');
	  $flag = true;
	}
	array_walk($row, __NAMESPACE__ . '\cleanData');
		$csv_output = implode("\t", array_values($row)) . "\r\n";
	  echo chr(255) . chr(254) . mb_convert_encoding($csv_output, 'UTF-16LE', 'UTF-8');
	}
	exit;
	
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
	
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css" rel="stylesheet"/>
	
    <!--  CSS for Demo Purpose, don't include it in your project -->
    <link href="assets/css/demo.css" rel="stylesheet" />

    <!--  Fonts and icons -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">
	
	<style type="text/css">
	
	.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
		background-color: #EB5E28;
		border-color: #EB5E28;
	}
	
	.pagination>li>a:focus, .pagination>li>a:hover, .pagination>li>span:focus, .pagination>li>span:hover {
		z-index: 3;
		color: #EB5E28;
		background-color: #eee;
		border-color: #eee;
	}
	
	.pagination>li>a, .pagination>li>span {
		position: relative;
		float: left;
		padding: 6px 12px;
		margin-left: -1px;
		line-height: 1.42857143;
		color: #EB5E28;
		text-decoration: none;
		background-color: #fff;
		border: 1px solid #eee;
	}

	</style>

</head>
<body>

<div class="wrapper">
<?php require_once('sidebar.php'); ?>
    <div class="main-panel">
<?php require_once('header.php'); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Form Listesi
								<?php
									if(userinfo('role_formexport')!=0){
								?>
										<div class="buttons" style="float:right;">
											<a href="formimport.php" class="btn btn-info various" data-fancybox-type="iframe"><i class="fa fa-arrow-up"></i> Import CSV</a>
											<a href="?exportList=true" target="_blank" class="btn btn-success"><i class="fa fa-arrow-up"></i> Export CSV</a>
										</div>
										<?php } ?>
										</h4>
                                <p class="category">Öncelikli olarak son gönderilen form gözükür.</p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                    <thead>
										<th>ID</th>
										<th>Full Name</th>
										<th>E-Mail</th>
										<th>Phone</th>
										<th>Message</th>
										<th>Form Name</th>
										<th>Project Name</th>
										<th>Created At</th>
										<th>Action</th>
                                    </thead>
									   <tbody>
										<?php
											
											// Sayfada kaç içerik gösterilecek
											$Sayfada = 20;
											
											// Toplam içerik ve sayfa sayısı
											$ToplamForm = $DB->query("SELECT * FROM ihit_form WHERE formdelete = '1'");
											$ToplamForm = $ToplamForm->rowCount();
											
											$ToplamSayfa = ceil($ToplamForm / $Sayfada);
										
											// Sayfa default olarak 1.sayfa da kalsın
											$Sayfa = 1;
											
											// Eğer sayfa değeri geldiyse $Sayfa değişkenini güncelleyelim.
											if(isset($_GET['sayfa'])){ $Sayfa = (int)$_GET['sayfa']; }
											
											// Eğer gelen sayfa 1 den küçük ise 1.sayfa da kalsın.
											if($Sayfa<1){ $Sayfa = 1; }
											
											// Eğer gelen sayfa bizim sayfa sayımızdan fazlaysa son sayfayı olacak şekilde güncelleyelim.
											if($Sayfa > $ToplamSayfa){ $Sayfa = $ToplamSayfa; }
											
											$Basla = ($Sayfa-1) * $Sayfada;
											
											$getForms = $DB->query("SELECT * FROM ihit_form WHERE formdelete = '1' ORDER BY form_created_at DESC LIMIT $Basla,$Sayfada");
											
											$SonSayfada = ($Sayfada-(($Sayfada*$ToplamSayfa)-$ToplamForm));
											$Hesapla = ((($ToplamSayfa-$Sayfa)*$Sayfada)-$Sayfada)+$SonSayfada;
											
											$Hesapla = $Hesapla+$Sayfada+1;
											foreach($getForms->fetchAll(PDO::FETCH_ASSOC) as $Form){
											$Hesapla--;
										?>
										  <tr>
											<td><?php echo $Hesapla; ?></td>
											<td><?php echo $Form['fullname']; ?></td>
											<td><?php echo $Form['email']; ?></td>
											<td><?php echo $Form['phone']; ?></td>
											<td><?php echo $Form['message']; ?></td>
											<td><?php echo $Form['form_name']; ?></td>
											<td><?php echo $Form['project_name']; ?></td>
											<td><?php echo date('d/m/Y H:i:s',$Form['form_created_at']); ?></td>
											<td>
											<!-- Detay -->
											<a class="various" data-fancybox-type="iframe" href="formdetay.php?ID=<?php echo $Form['id']; ?>"><i class="ti-eye"></i></a>
											
											<!-- Sil -->
											<a href="formlistesi.php?deleteForm=<?php echo $Form['id']; ?>&sayfa=<?php echo $Sayfa; ?>" onclick="return confirm('Silmek istediğinizden emin misiniz?')"><i class="ti-trash"></i></a>
											
											</a>
											</td>
										  </tr>
										<?php } ?>
										</tbody>
                                </table>
								<ul style="margin-left:15px;" class="pagination">
								<?php
								
									for($i=1; $i<=$ToplamSayfa; $i++){
										
								?>
								  <li<?php if($i==$Sayfa){ echo ' class="active"'; } ?>><a href="?sayfa=<?php echo $i; ?>"><?php echo $i; ?></a></li>
								  <?php } ?>
								</ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php require_once('footer.php'); ?>