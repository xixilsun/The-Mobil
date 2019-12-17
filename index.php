<?php
require_once "lib/EasyRdf.php";

//Random Numbers
$random = rand(12,20);

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
		?car rdf:type dbo:Automobile.
		FILTER (lang(?carName)='en').
		FILTER (lang(?namavendor)='en').
	} ORDER BY ?namavendor
";
$result1 = $sparql->query($query1);

//menampilkan list mobil random
$query2="
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
	}
	ORDER BY rand()
	LIMIT ".$random."
";
$result2 = $sparql->query($query2);

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
	if($_POST['jenis']=='' && $_POST['vendor']=='' && $_POST['produksi'] =='')
	{
		//menampilkan list mobil random
		$query2="
			PREFIX dbr: <http://dbpedia.org/resource/>
			PREFIX dbo: <http://dbpedia.org/ontology/>
			PREFIX foaf: <http://xmlns.com/foaf/0.1/>
			PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			SELECT distinct ?namavendor ?car ?produksi ?abstrak ?carName ?pic ?wiki WHERE
			{
				?vendor dbo:industry dbr:Automotive_industry.
				?vendor rdfs:label ?namavendor.
				?car dbo:manufacturer ?vendor.
				?car dbo:abstract ?abstrak.
				?car rdfs:label ?carName.
				?car dbo:productionStartYear ?produksi.
				?car foaf:depiction ?pic.
				?car rdf:type dbo:Automobile.
				?car dbo:wikiPageID ?wiki.
				FILTER (lang(?abstrak)='en').
				FILTER (lang(?carName)='en').
				FILTER (lang(?namavendor)='en').
			}
			ORDER BY rand()
			LIMIT ".$random."
		";
	}
	else {
		$jenis = $_POST['jenis'];
		$vendor = $_POST['vendor'];
		$tahun = $_POST['produksi'];
		$query2="
			PREFIX dbr: <http://dbpedia.org/resource/>
			PREFIX dbo: <http://dbpedia.org/ontology/>
			PREFIX foaf: <http://xmlns.com/foaf/0.1/>
			PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
			SELECT distinct ?namavendor ?car ?produksi ?abstrak ?carName ?pic ?wiki WHERE
			{
				?vendor dbo:industry dbr:Automotive_industry.
				?vendor rdfs:label ?namavendor.
				?car dbo:manufacturer ?vendor.
				?car dbo:abstract ?abstrak.
				?car rdfs:label ?carName.
				?car rdf:type dbo:Automobile.
				?car dbo:productionStartYear ?produksi.
				?car foaf:depiction ?pic.
				?car dbo:wikiPageID ?wiki.
				FILTER (lang(?abstrak)='en').
				FILTER (lang(?carName)='en').
				FILTER (lang(?namavendor)='en').
				FILTER REGEX(?carName, '".$jenis."', 'i')
				FILTER REGEX(?produksi, '".$tahun."')
				FILTER REGEX(?vendor, '".$vendor."', 'i')
			}
			ORDER BY rand()
			LIMIT ".$random."
		";
		$result2 = $sparql->query($query2);
	}
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
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/user.css">

	<title>TheMobil</title>

</head>
<body>
	<div class="page home-page">
		<!--*********************************************************************************************************-->
		<!--************ HERO ***************************************************************************************-->
		<!--*********************************************************************************************************-->
		<header class="hero has-dark-background">
			<div class="hero-wrapper" style="height: 600px">
				<!--============ Main Navigation ====================================================================-->
				<div class="main-navigation">
					<div class="container">
						<nav class="navbar navbar-expand-lg navbar-light justify-content-between">
							<a class="navbar-brand" href="index.html">
								<img src="assets/img/logo-inverted.png" alt="">
							</a>
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>
							<div class="collapse navbar-collapse" id="navbar">
								<!--Main navigation list-->
								<ul class="navbar-nav">
									<li class="nav-item">
										<a  href="../websemantik2/" class="nav-link active" >Home</a>
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
					</div>
					<!--end container-->
				</div>
				<!--============ End Main Navigation ================================================================-->
				<!--============ Page Title =========================================================================-->
				<div class="page-title" style="padding-top: 7rem ">
					<div class="container">
						<h1 class="center">
							Find your MOST favourite car!
						</h1>
					</div>
					<!--end container-->
				</div>
				<!--============ End Page Title =====================================================================-->
				<!--============ Hero Form ==========================================================================-->
				<form class="hero-form form" method="post" style="padding-top: 12rem;">
					<div class="container">
						<!--Main Form-->
						<div class="main-search-form">
							<div class="form-row">
								<div class="col-md-3 col-sm-3">
									<div class="form-group">
										<label for="jenis" class="col-form-label">Car Type</label>
										<input name="jenis" type="text" autocomplete="off" class="form-control" id="jenis" placeholder="Example : Innova" value="<?php if(isset($_POST['jenis'])){ echo $_POST['jenis'];} ?>">
									</div>
									<!--end form-group-->
								</div>
								<!--end col-md-3-->
								<div class="col-md-3 col-sm-3">
									<div class="form-group">
										<label for="vendor" class="col-form-label">Vendor</label>
										<select name="vendor" id="vendor" data-placeholder="Choose Vendor">
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
										<select name="produksi" id="produksi" data-placeholder="Choose Year">
												<option value="" selected>Choose</option>
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
						<img src="assets/img/back.jpg" alt="">
					</div>
					<!--end background-image-->
				</div>
				<!--end background-->
			</div>
			<!--end hero-wrapper-->
		</header>
		<!--end hero-->
		<!--*********************************************************************************************************-->
		<!--************ CONTENT ************************************************************************************-->
		<!--*********************************************************************************************************-->
		<section class="content">
			<section class="block">
				<div class="container">
					<!--============ Section Title===================================================================-->
					<div class="section-title clearfix">
						<div class="float-xl-left float-md-left float-sm-none">
							<?php if (sizeof($result2) == 0){
								echo "<h2>Mobil tidak ditemukan</h2><br><br>Silahkan reset pencarian anda <a href='' style='color:blue;'><u>Reset</u></a>";
							} 
							else { ?>
								<h2>List Mobil</h2>
						<?php } ?>
						</div>

					</div>
					<!--============ Items ==========================================================================-->
					<div class="items masonry grid-xl-4-items grid-lg-3-items grid-md-2-items">
						<?php $result2 = $sparql->query($query2); ?>
						
						<?php $u=0; ?>
						<?php foreach($result2 as $key): ?>
						<div class="item">
							<div class="wrapper">
								<div class="image">
									<h3>
										<a href="detail.php?id=<?php echo $key->wiki; ?>" class="title"><?php echo $key->carName; ?></a>
									</h3>
									<a href="detail.php?id=<?php echo $key->wiki; ?>" class="image-wrapper background-image" style="background-image: url('<?php echo $key->pic; ?>');">
										<img src="<?php echo $key->pic; ?>">
									</a>
								</div>
								<h4>
									<i class="fa fa-calendar-o"></i> &nbsp; <?php echo $key->produksi; ?>
								</h4>
								<!--end image-->
								<div class="price"><?php echo $key->namavendor; ?></div>
								<div class="meta" style="padding: 0; padding-bottom: 8px;">
									<figure>

									</figure>
								</div>
								<div class="description">
									<p>
										<?php
											$a = explode(' ',$key->abstrak);       //explode untuk membatasi berapa kata yang muncul di tampilan
											if (sizeof($a)>10) $a = array_slice($a,0,15);   //disini hanya maksimal 10 kata
											echo implode(' ',$a);
											echo "...";
										?>
									</p>
								</div>
								<!--end meta-->
								<!--end description-->
								<a href="detail.php?id=<?php echo $key->wiki; ?>" class="detail text-caps underline">Read More!</a>
							</div>
						</div>
						<!--end item-->
						<?php $u++; ?>
						<?php if ($u==12): ?>
							<?php break; ?>
						<?php endif ?>
						<?php endforeach; ?>
					</div>
					<!--============ End Items ======================================================================-->

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
	<!--<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>-->
	<script src="assets/js/selectize.min.js"></script>
	<script src="assets/js/masonry.pkgd.min.js"></script>
	<script src="assets/js/icheck.min.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/custom.js"></script>

</body>
</html>
