<?php
require_once "lib/EasyRdf.php";

// set DBpedia sparql endpoint
$sparql = new EasyRdf_Sparql_Client('http://dbpedia.org/sparql');

//Menampilkan semua nama vendor
$query1= "
	PREFIX dbo: <http://dbpedia.org/ontology/>
	PREFIX foaf: <http://xmlns.com/foaf/0.1/>
	PREFIX dbr: <http://dbpedia.org/resource/>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
	select distinct ?namavendor ?vendor
	{
		?vendor dbo:industry dbr:Automotive_industry.
		?vendor rdfs:label ?namavendor.
		?car dbo:abstract ?abstract.
		?car dbo:manufacturer ?vendor.
		?car rdfs:label ?carName.
		?car dbo:productionStartYear ?produksi.
		?car foaf:depiction ?pic.
		?car dbo:wikiPageID ?wiki.
		FILTER (lang(?carName)='en').
		FILTER (lang(?namavendor)='en').
	} ORDER BY ?namavendor
";
$result1 = $sparql->query($query1);

$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
	$id='A';
}

$query2="
	PREFIX dbo: <http://dbpedia.org/ontology/>
	PREFIX foaf: <http://xmlns.com/foaf/0.1/>
	PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
	SELECT distinct ?carName ?wiki WHERE
	{
		?vendor dbo:industry dbr:Automotive_industry.
		?vendor rdfs:label ?namavendor.
		?car dbo:manufacturer ?vendor.
		?car rdfs:label ?carName.
		?car dbo:productionStartYear ?produksi.
		?car foaf:depiction ?pic.
		?car dbo:wikiPageID ?wiki.
		?car rdf:type dbo:Automobile.
		FILTER (lang(?carName)='en').
		FILTER (lang(?namavendor)='en').
		FILTER REGEX(?carName, '^".$id."(.*)$', 'i')
		OPTIONAL {
			?car dbo:height ?height.
			?car dbo:length ?length.
			?car dbo:width ?width.
			?car dbo:weight ?weight.
		}	
		OPTIONAL {
			?version dbo:relatedMeanOfTransportation ?car.
			?version dbo:height ?height.
			?version dbo:length ?length.
			?version dbo:width ?width.
			?version dbo:weight ?weight.
		}
		OPTIONAL {
			?car dbo:relatedMeanOfTransportation ?version.
			?version dbo:height ?height.
			?version dbo:length ?length.
			?version dbo:width ?width.
			?version dbo:weight ?weight.
		}
	}
	ORDER BY ?carName
";
$result2 = $sparql->query($query2);
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="ThemeStarz">

	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Varela+Round" rel="stylesheet">
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css" type="text/css">
	<link rel="stylesheet" href="assets/fonts/font-awesome.css" type="text/css">
	<link rel="stylesheet" href="assets/css/selectize.css" type="text/css">
	<link rel="stylesheet" href="assets/css/owl.carousel.min.css" type="text/css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/user.css">

	<title>Craigs - Easy Buy & Sell Listing HTML Template</title>
	<style type="text/css">
		.image {
			width:400px;
			height:400px;
			background-position: 50%;
		}
		.image img {
			width:100%;
			vertical-align:top;
		}
		.image:after {
			content:'\A';
			position:absolute;
			width:100%; height:100%;
			top:0; left:0;
			background:rgba(0,0,0,0.6);
			opacity:0.9;
			transition: all 0.5s;
			-webkit-transition: all 0.5s;
		}
		.image:hover:after {
			opacity:0;
		}
	</style>
</head>
<body>
	<div class="page sub-page">
		<!--*********************************************************************************************************-->
		<!--************ HERO ***************************************************************************************-->
		<!--*********************************************************************************************************-->
		<section class="hero">
			<div class="hero-wrapper">
				<!--============ Main Navigation ====================================================================-->
				<div class="main-navigation">
					<div class="container">
						<nav class="navbar navbar-expand-lg navbar-light justify-content-between">
							<a class="navbar-brand" href="index.html">
								<img src="assets/img/logo.png" alt="">
							</a>
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>
							<div class="collapse navbar-collapse" id="navbar">
								<!--Main navigation list-->
								<ul class="navbar-nav">
									<li class="nav-item">
										<a  href="../websemantik2/" class="nav-link" >Home</a>
									</li>
									<li class="nav-item">
										<a class="nav-link active" href="../websemantik2/vendor.php">Car</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="../websemantik2/compare.php">Compare</a>
									</li>
								</ul>

								<!--Main navigation list-->
							</div>
							<!--end navbar-collapse-->
							<a href="#collapseMainSearchForm" class="main-search-form-toggle" data-toggle="collapse"  aria-expanded="false" aria-controls="collapseMainSearchForm">
								<i class="fa fa-search"></i>
								<i class="fa fa-close"></i>
							</a>
							<!--end main-search-form-toggle-->
						</nav>
						<!--end navbar-->
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#">Home</a></li>
							<li class="breadcrumb-item"><a href="#">Library</a></li>
							<li class="breadcrumb-item active">Data</li>
						</ol>
						<!--end breadcrumb-->
					</div>
					<!--end container-->
				</div>
				<!--============ End Main Navigation ================================================================-->
				<!--============ Hero Form ==========================================================================-->
				<div class="collapse" id="collapseMainSearchForm">
				<form class="hero-form form" action="../websemantik2/" method="post">
					<div class="container">
						<!--Main Form-->
						<div class="main-search-form" style="background: none; padding: 0; padding-top: 4rem;">
							<div class="form-row">
								<div class="col-md-3 col-sm-3">
									<div class="form-group">
										<label for="jenis" class="col-form-label">Jenis Mobil</label>
										<input name="jenis" type="text" class="form-control" id="jenis" placeholder="Mis : Avanza" value="<?php if(isset($_POST['jenis'])){ echo $_POST['jenis'];} ?>">
									</div>
									<!--end form-group-->
								</div>
								<!--end col-md-3-->
								<div class="col-md-3 col-sm-3">
									<div class="form-group">
										<label for="vendor" class="col-form-label">Vendor</label>
										<select name="vendor" id="vendor" data-placeholder="Pilih Vendor">
											<option value="">Pilih</option>
											<?php foreach($result1 as $key): ?>
												<option value="<?php echo $key->vendor; ?>" 
													<?php if(isset($_POST['vendor']) && $_POST['vendor'] == $key->vendor) 
														echo 'selected= "selected"';
													?>
												><?php echo $key->namavendor; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<!--end form-group-->
								</div>
								<!--end col-md-3-->
								<div class="col-md-3 col-sm-3">
									<div class="form-group">
										<label for="produksi" class="col-form-label">Tahun Produksi</label>
										<select name="produksi" id="produksi" data-placeholder="Pilih Tahun">
												<option value="" selected>Pilih Tahun</option>
												<?php for($i=date("Y"); $i>=1800; $i--): ?>
													<option value="<?= $i;?>"
														<?php if(isset($_POST['produksi']) && $_POST['produksi'] == $i) 
															echo 'selected= "selected"';
														?>
													><?= $i;?></option>
												<?php endfor; ?>
										</select>
									</div>
									<!--end form-group-->
								</div>
								<!--end col-md-3-->
								<div class="col-md-3 col-sm-3">
									<input type="submit" class="btn btn-primary width-100" value="Cari">
								</div>
								<!--end col-md-3-->
							</div>
							<!--end form-row-->
						</div>
						<!--end main-search-form-->
					</div>
					<!--end container-->
				</form>
			</div>
				<!--============ End Hero Form ======================================================================-->
				<!--============ Page Title =========================================================================-->
				<div class="page-title">
					<div class="container">
						<h1>Cars</h1>
					</div>
					<!--end container-->
				</div>
				<!--============ End Page Title =====================================================================-->
				<div class="background">
					<div class="background-image">
						<img src="assets/img/hero-background-image-033.jpg" alt="">
					</div>
				</div>
				<!--end background-->
			</div>
			<!--end hero-wrapper-->
		</section>
		<!--end hero-->
		<!--*********************************************************************************************************-->
		<!--************ CONTENT ************************************************************************************-->
		<!--*********************************************************************************************************-->
		<section class="content">
			<section class="block">
				<div class="container">
					<section>
						<h2>Alphabetical of Cars</h2>
						<a href="vendor.php?id=A" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">A</a>
						<a href="vendor.php?id=B" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">B</a>
						<a href="vendor.php?id=C" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">C</a>
						<a href="vendor.php?id=D" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">D</a>
						<a href="vendor.php?id=E" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">E</a>
						<a href="vendor.php?id=F" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">F</a>
						<a href="vendor.php?id=G" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">G</a>
						<a href="vendor.php?id=H" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">H</a>
						<a href="vendor.php?id=I" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">I</a>
						<a href="vendor.php?id=J" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">J</a>
						<a href="vendor.php?id=K" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">K</a>
						<a href="vendor.php?id=L" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">L</a>
						<a href="vendor.php?id=M" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">M</a>
						<a href="vendor.php?id=N" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">N</a>
						<a href="vendor.php?id=O" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">O</a>
						<a href="vendor.php?id=P" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">P</a>
						<a href="vendor.php?id=Q" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">Q</a>
						<a href="vendor.php?id=R" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">R</a>
						<a href="vendor.php?id=S" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">S</a>
						<a href="vendor.php?id=T" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">T</a>
						<a href="vendor.php?id=U" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">U</a>
						<a href="vendor.php?id=V" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">V</a>
						<a href="vendor.php?id=W" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">W</a>
						<a href="vendor.php?id=X" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">X</a>
						<a href="vendor.php?id=Y" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">Y</a>
						<a href="vendor.php?id=Z" class="btn btn-primary btn-rounded mr-4 mb-4" style="border-radius: 50%; width: 55px;">Z</a>

					</section>

					<section>
						<h2>Letter : <?php echo "$id"; ?></h2>
						<section>
							<table style="width: 100%" cellpadding="10">
								<?php $u=0;?>
								<tr>
									<?php 
									if(sizeof($result2)!=0){ 
										foreach ($result2 as $key) { ?>
											<td><a href="../websemantik2/detail.php?id=<?php echo $key->wiki; ?>"><h4><?= $key->carName; ?></h4></a></td>
									<?php
										$u++;
										if ($u%6==0) {
											echo "</tr><tr>";
										}
									} }
									elseif (sizeof($result2) == 0) { ?>
										<td><h4>Sorry, It's not found</h4></td>
									<?php } ?>
								</tr>
							</table>
							
						</section>
					</section>

				</div>
				<!--end container-->
			</section>
			<!--end block-->
		</section>
		<!--end content-->
	</div>
	<!--end page-->

	<script src="assets/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="assets/js/popper.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyBEDfNcQRmKQEyulDN8nGWjLYPm8s4YB58&libraries=places"></script>
	<script src="assets/js/selectize.min.js"></script>
	<script src="assets/js/icheck.min.js"></script>
	<script src="assets/js/owl.carousel.min.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/custom.js"></script>

	<script>
		var latitude = 51.511971;
		var longitude = -0.137597;
		var markerImage = "assets/img/map-marker.png";
		var mapTheme = "light";
		var mapElement = "map-small";
		simpleMap(latitude, longitude, markerImage, mapTheme, mapElement);
	</script>

</body>
</html>
