<!DOCTYPE html>
<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }
  ?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gestion Contable</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="css/styles.css" rel="stylesheet">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/Chart.bundle.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.nav.menu li').eq(3).addClass('active');

		var chart = document.getElementById("pie-chart").getContext("2d");
		var myPie = new Chart(chart, {
					type: 'pie',
			data : {
				labels : [
					"Generales",
					"Combustible",
					"Auxiliar",
					"impuestos"
				],
				datasets :[{
					data : [
						40,
						30,
						20,
						10,
						
					],
					backgroundColor: [
						"#F7464A",
						"#4D5360",
						"#FDB45C",
						"#46BFBD"
					]
				}]
			},
			options : {
				responsive : true,
			}
	});

	});
	</script>
</head>
<body>
	<?php
	include("plantilla.php");
	?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#">
          <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Gestión Contable</li>
      </ol>
    </div><!--/.row-->

    <div class="row">

    <div class="col-lg-12">
      	<div class="panel panel-default">
        	<div class="panel-body">
          		<div class="canvas-wrapper"> <!-- id="canvas-container" style="width:50%;"-->
					<canvas class="chart" id="pie-chart"></canvas><!--width="500" height="350"-->
				</div>
			</div>
		</div>
	</div>
	</div><!--/.row-->
</div>	
</body>
</html>