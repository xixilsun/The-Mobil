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
$id = $_GET['id'];

//Melihat Detail
$query2 = "
	PREFIX dbr: <http://dbpedia.org/resource/>
	PREFIX dbo: <http://dbpedia.org/ontology/>
	PREFIX foaf: <http://xmlns.com/foaf/0.1/>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

	SELECT distinct ?namavendor ?car ?abstrak ?produksi ?carName ?length ?height ?width ?pic ?wiki ?wheelbase WHERE
	{
		?vendor dbo:industry dbr:Automotive_industry.
		?vendor rdfs:label ?namavendor.
		?car dbo:manufacturer ?vendor.
		?car dbo:abstract ?abstrak.
		?car rdfs:label ?carName.
		?car dbo:productionStartYear ?produksi.
		?car foaf:depiction ?pic.
		?car dbo:wikiPageID ?wiki.
		FILTER (lang(?abstrak)='en').
		FILTER (?wiki=".$id.").
		FILTER (lang(?carName)='en').
		FILTER (lang(?namavendor)='en').
		OPTIONAL {
			?car dbo:height ?height.
			?car dbo:length ?length.
			?car dbo:width ?width.
			?car dbo:wheelbase ?wheelbase
		}	
		OPTIONAL {
			?version dbo:relatedMeanOfTransportation ?car.
			?version dbo:height ?height.
			?version dbo:length ?length.
			?version dbo:width ?width.
			?car dbo:wheelbase ?wheelbase
		}
		OPTIONAL {
			?car dbo:relatedMeanOfTransportation ?version.
			?version dbo:height ?height.
			?version dbo:length ?length.
			?version dbo:width ?width.
			?car dbo:wheelbase ?wheelbase
		}
	} 
	LIMIT 1
";
$result2 = $sparql->query($query2);

//Random Numbers
$random = rand(3,10);
//menampilkan list mobil random
$query3="
	PREFIX dbr: <http://dbpedia.org/resource/>
	PREFIX dbo: <http://dbpedia.org/ontology/>
	PREFIX foaf: <http://xmlns.com/foaf/0.1/>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

	SELECT distinct ?namavendor ?car ?abstrak ?produksi ?carName ?pic ?wiki WHERE
	{
		?vendor dbo:industry dbr:Automotive_industry.
		?vendor rdfs:label ?namavendor.
		?car dbo:manufacturer ?vendor.
		?car dbo:abstract ?abstrak.
		?car rdfs:label ?carName.
		?car dbo:productionStartYear ?produksi.
		?car foaf:depiction ?pic.
		?car dbo:wikiPageID ?wiki.
		?car rdf:type dbo:Automobile.
		FILTER (lang(?abstrak)='en').
		FILTER (lang(?carName)='en').
		FILTER (lang(?namavendor)='en').
		OPTIONAL {
			?car dbo:height ?height.
			?car dbo:length ?length.
			?car dbo:width ?width.
			?car dbo:wheelbase ?wheelbase
		}	
		OPTIONAL {
			?version dbo:relatedMeanOfTransportation ?car.
			?version dbo:height ?height.
			?version dbo:length ?length.
			?version dbo:width ?width.
			?car dbo:wheelbase ?wheelbase
		}
		OPTIONAL {
			?car dbo:relatedMeanOfTransportation ?version.
			?version dbo:height ?height.
			?version dbo:length ?length.
			?version dbo:width ?width.
			?car dbo:wheelbase ?wheelbase
		}
		FILTER (?produksi > \"1995\"^^xsd:gYear).
	}
	ORDER BY rand()
	LIMIT ".$random."
";
$result3 = $sparql->query($query3);

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

	<title>TheMobil</title>
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
										<a class="nav-link" href="../websemantik2/car.php">Car</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="../websemantik2/compare.php">Compare</a>
									</li>
								</ul>

								<!--Main navigation list-->
							</div>
							<!--end navbar-collapse-->
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
				<form class="hero-form form" action="../websemantik2/" method="post">
					<div class="container">
						<!--Main Form-->
						<div class="main-search-form" style="background: none; padding: 0; padding-top: 4rem;">
							<div class="form-row">
								<div class="col-md-3 col-sm-3">
									<div class="form-group">
										<label for="jenis" class="col-form-label">Car Type</label>
										<input name="jenis" type="text" class="form-control" id="jenis" placeholder="Example : Innova" value="<?php if(isset($_POST['jenis'])){ echo $_POST['jenis'];} ?>">
									</div>
									<!--end form-group-->
								</div>
								<!--end col-md-3-->
								<div class="col-md-3 col-sm-3">
									<div class="form-group">
										<label for="vendor" class="col-form-label">Vendor</label>
										<select name="vendor" id="vendor" data-placeholder="Pilih Vendor">
											<option value="">Choose Vendor</option>
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
										<label for="produksi" class="col-form-label">Year of Production</label>
										<select name="produksi" id="produksi" data-placeholder="Pilih Tahun">
												<option value="" selected>Choose Year</option>
												<?php for($i=date("Y"); $i>=1; $i--): ?>
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
									<input type="submit" class="btn btn-primary width-100" value="Search!">
								</div>
								<!--end col-md-3-->
							</div>
							<!--end form-row-->
						</div>
						<!--end main-search-form-->
					</div>
					<!--end container-->
				</form>
				<!--============ End Hero Form ======================================================================-->
				
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
					<div class="row">
						<!--============ Listing Detail =============================================================-->
						<?php foreach ($result2 as $key) { ?>
						<div class="col-md-9">
							<!--Gallery Carousel-->
							<section class="hero" style="border-radius: 8px; z-index: 0;" >
								<!--============ Page Title =========================================================================-->
								<div class="page-title hero-wrapper">
									<div class="container clearfix">
										<div class="float-left float-xs-none">
											<h1><?php echo $key->carName; ?>
												<span class="tag">Details</span>
											</h1>
										</div>
										<div class="float-right float-xs-none price">
											<div class="number" style="font-size: 2.7rem;"><i class="fa fa-calendar-o"></i> <?php echo $key->produksi; ?></div>
											<div class="id opacity-50">
												<strong>ID: </strong><?= $id ?>
											</div>
										</div>
									</div>
									<!--end container-->
								</div>
								<!--============ End Page Title =====================================================================-->
								<img src="<?= $key->pic; ?>" alt="" data-hash="1" style="border-radius: 8px;width: 100%;">
							</section>
							<!--end Gallery Carousel-->
							<!--Details & Location-->
							<section>
								<div class="row">
									<div class="col-md-4">
										<h2>Details</h2>
										<dl>
											<dt>Name</dt>
											<dd><?= $key->carName; ?></dd>
											<dt>Vendor</dt>
											<dd><?= $key->namavendor; ?></dd>
											<dt>Production Year</dt>
											<dd><?= $key->produksi; ?></dd>
											<dt>Length</dt>
											<dd>
											<?php if (empty($key->length))
												echo "-";
											else
												echo $key->length." m";
											?></dd>
											<dt>Height</dt>
											<dd>
											<?php if (empty($key->height))
												echo "-";
											else
												echo $key->height." m";
											?>
											</dd>
											<dt>Width</dt>
											<dd>
											<?php if (empty($key->width))
												echo "-";
											else
												echo $key->width." m";
											?>
											</dd>
											<dt>Wheelbase</dt>
											<dd>
												<?php if (empty($key->wheelbase))
													echo "-";
												else
													echo $key->wheelbase." m";
												?>
											</dd>
										</dl>
									</div>
									<div class="col-md-8">
										<h2>Description</h2>
										<p style="opacity: 80;">
											<?= $key->abstrak; ?>
										</p>
									</div>
								</div>
							</section>
							<!--end Details and Locations-->
						</div>
						<?php } ?>

						<!--============ End Listing Detail =========================================================-->
						<!--============ Sidebar ====================================================================-->
						<div class="col-md-3">
							<aside class="sidebar">
								<section>
									<h2>Other Cars</h2>
									<div class="items compact">
										<?php $u=0; ?>
										<?php foreach ($result3 as $key) { 
											if (empty($key->pic)) {
												$u--;
											}
											else{
											?>
												<!-- Start Item -->
												<div class="item">
													<?php if ($u==0) { ?>
														<div class="ribbon-featured">Featured</div>
													<?php } ?>
													<!--end ribbon-->
													<div class="wrapper">
														<div class="image">
															<h3>
																<a href="#" class="tag category">Car</a>
																<a href="../websemantik2/detail.php?id=<?php echo $key->wiki; ?>" class="title"><?php echo $key->carName; ?></a>
																<span class="tag">Detail</span>
															</h3>
															<a href="detail.php?id=<?php echo $key->wiki; ?>" class="image-wrapper background-image" style="background-image: url('<?php echo $key->pic; ?>');">
																<img src="<?php echo $key->pic; ?>">
															</a>
														</div>
														<!--end image-->
														<h4>
															<i class="fa fa-calendar-o"></i> &nbsp; <?php echo $key->produksi; ?>
														</h4>
														<div class="price"><?php echo $key->namavendor; ?></div>
														<div class="meta" style="padding: 0; padding-bottom: 8px;">
															<figure>
															</figure>
														</div>
														<!--end meta-->
													</div>
													<!--end wrapper-->
												</div>
												<!--end item-->
											<?php $u++; ?>
											<?php if ($u==3): ?>
												<?php break; ?>
											<?php endif ?>

										<?php } } ?>
									</div>
								</section>
							</aside>
						</div>
						<!--============ End Sidebar ================================================================-->
					</div>
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
