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

//menampilkan semua nama mobil
$query2="
	PREFIX dbr: <http://dbpedia.org/resource/>
	PREFIX dbo: <http://dbpedia.org/ontology/>
	PREFIX foaf: <http://xmlns.com/foaf/0.1/>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
	SELECT distinct ?carName WHERE
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
	}
	ORDER BY (?carName)
";
$result2= $sparql->query($query2);

//jika submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
	$jenis1=$_POST['jenis1'];
	$jenis2=$_POST['jenis2'];
	//Random Numbers
	//menampilkan list mobil random
	$query3="
		PREFIX dbr: <http://dbpedia.org/resource/>
		PREFIX dbo: <http://dbpedia.org/ontology/>
		PREFIX foaf: <http://xmlns.com/foaf/0.1/>
		PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
		SELECT distinct ?namavendor ?car ?abstrak ?produksi ?carName ?pic ?wiki ?length ?height ?width ?wheelbase WHERE
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
			FILTER REGEX(?carName, '".$jenis1."', 'i')
		}
		ORDER BY rand()
		LIMIT 1
	";
	$result3 = $sparql->query($query3);

	//menampilkan list mobil random
	$query4="
		PREFIX dbr: <http://dbpedia.org/resource/>
		PREFIX dbo: <http://dbpedia.org/ontology/>
		PREFIX foaf: <http://xmlns.com/foaf/0.1/>
		PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
		SELECT distinct ?namavendor ?car ?abstrak ?produksi ?carName ?pic ?wiki ?length ?height ?width ?wheelbase WHERE
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
			FILTER (lang(?carName)='en').
			FILTER (lang(?namavendor)='en').
			FILTER (lang(?abstrak)='en').
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
			FILTER REGEX(?carName, '".$jenis2."', 'i')
		}
		ORDER BY rand()
		LIMIT 1
	";
	$result4 = $sparql->query($query4);
}
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
										<a class="nav-link active" href="../websemantik2/compare.php">Compare</a>
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
										<label for="jenis" class="col-form-label">Car Type</label>
										<input name="jenis" type="text" class="form-control" id="jenis" placeholder="Example : Innova" value="<?php if(isset($_POST['jenis'])){ echo $_POST['jenis'];} ?>">
									</div>
									<!--end form-group-->
								</div>
								<!--end col-md-3-->
								<div class="col-md-3 col-sm-3">
									<div class="form-group">
										<label for="vendor" class="col-form-label">Vendor</label>
										<select name="vendor" id="vendor" data-placeholder="Choose a Vendor">
											<option value="">Choose</option>
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
			</div>
				<!--============ End Hero Form ======================================================================-->
				<!--============ Page Title =========================================================================-->
				<div class="page-title">
					<div class="container">
						<h1>Car Comparation</h1>
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
						<div class="row">
							<div class="col-lg-12 container">
								<div class="main-search-form" style="background: none; padding: 0; padding-top: 4rem;">
								<form method="post">
								<div class="form-row">
										<div class="col-md-5">
											<div class="form-group">
												<label for="jenis1" class="col-form-label"><h4>Car 1 Type</h4></label>
												<input name="jenis1" autocomplete="off" list="jenis1-1" type="text" class="form-control" id="jenis1" placeholder="Example : Audi A2" value="<?php if(isset($_POST['jenis1'])){ echo $_POST['jenis1'];} ?>" required>
												<datalist id="jenis1-1">
													<?php foreach ($result2 as $key) { ?>
														<option value="<?php echo $key->carName; ?>"></option>
													<?php } ?>
												</datalist>
											</div>
											<!--end form-group-->
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label for="jenis2" class="col-form-label"><h4>Car 2 Type</h4></label>
												<input name="jenis2" list="jenis2-2" type="text" class="form-control" id="jenis2" placeholder="Example : Audi Q2" autocomplete="off" value="<?php if(isset($_POST['jenis2'])){ echo $_POST['jenis2'];} ?>" required>
												<datalist id="jenis2-2">
													<?php foreach ($result2 as $key) { ?>
														<option value="<?php echo $key->carName; ?>"></option>
													<?php } ?>
												</datalist>
											</div>
											<!--end form-group-->
										</div>
										<!--end col-md-3-->
										<div class="col-md-2" style="padding-top: 4rem">
											<input type="submit" class="btn btn-primary width-100" value="Search!">
										</div>
										<!--end col-md-3-->
									
								</div>
									</form>
								<!--end form-row-->
							</div>
							<!--end main-search-form-->
							</div>
							
						</div>
						<?php if (!empty($result3)) { ?>
						<div class="row" style="background: #f8f8f8; border-radius: 5px; padding: 30px;">
							<div class="col-md-6 col-sm-6 col-lg-3">
								<div class="pricing box description" style="padding-top: 340px;">
									<h1 class="opacity-0">Compare</h1>
									<ul>
										<li>Production Year</li>
										<li>Length</li>
										<li>Height</li>
										<li>Width</li>
										<li>Wheelbase</li>
									</ul>
								</div>
								<!--end pricing-box description-->
							</div>
							<!--end col-md-4-->
							<div class="col-md-9">
								<div class="row">
									<!--Main Form-->
									
								</div>
								<div class="row">
									<div class="col-md-6 col-sm-6 col-lg-6">
										<?php  
										foreach ($result3 as $key) { ?>
											<article class="blog-post clearfix">
												<div class="background-image" style="height: 220px">
													<img src="<?php echo $key->pic; ?>" alt="" style="width: 100%;height: 100%; margin: auto;">
												</div>
												    <div class="article-title">
											        <h2><a href="../websemantik2/detail.php?id=<?php echo $key->wiki; ?>"><?php echo $key->carName; ?></a></h2>
											        <div class="tags framed">
											            <a href="#" class="tag"><?php echo $key->namavendor; ?></a>
											        </div>
											    </div>
											    <div class="pricing box">

											<ul>
												<li class="not-available"><?php echo $key->produksi; ?></li>
												<li class="not-available">
													<?php if (empty($key->length))
														echo "-";
													else
														echo $key->length." m";
													?>
												</li>
												<li class="not-available">
													<?php if (empty($key->height))
														echo "-";
													else
														echo $key->height." m";
													?>
												</li>
												<li class="not-available">
													<?php if (empty($key->width))
														echo "-";
													else
														echo $key->width." m";
													?>
												</li>
												<li class="not-available">
													<?php if (empty($key->wheelbase))
														echo "-";
													else
														echo $key->wheelbase." m";
													?>
												</li>
											</ul>
										</div>
											</article>
										
									<?php } ?>
										<!--end pricing-box-->
									</div>
									<div class="col-md-6 col-sm-6 col-lg-6">
										<?php  
										foreach ($result4 as $key) { ?>
											<article class="blog-post clearfix">
											    <div class="background-image" style="height: 220px">
													<img src="<?php echo $key->pic; ?>" alt="" style="width: 100%;height: 100%; margin: auto;">
												</div>
											    <div class="article-title">
											        <h2><a href="../websemantik2/detail.php?id=<?php echo $key->wiki; ?>"><?php echo $key->carName; ?></a></h2>
											        <div class="tags framed">
											            <a href="#" class="tag"><?php echo $key->namavendor; ?></a>
											        </div>
											    </div>
											    <div class="pricing box">

											<ul>
												<li class="not-available"><?php echo $key->produksi; ?></li>
												<li class="not-available">
													<?php if (empty($key->length))
														echo "-";
													else
														echo $key->length." m";
													?>
												</li>
												<li class="not-available">
													<?php if (empty($key->height))
														echo "-";
													else
														echo $key->height." m";
													?>
												</li>
												<li class="not-available">
													<?php if (empty($key->width))
														echo "-";
													else
														echo $key->width." m";
													?>
												</li>
												<li class="not-available">
													<?php if (empty($key->wheelbase))
														echo "-";
													else
														echo $key->wheelbase." m";
													?>
												</li>
											</ul>
										</div>
											</article>
										
									<?php } ?>
									</div>
								</div>
								<!--end col-md-3-->
							</div>
						<?php } ?>
							<!-- end -->
						</div>
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
