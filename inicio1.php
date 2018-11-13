<!DOCTYPE html>
<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }
  $id_finca = (isset($_GET['id']))?$_GET['id']:0;

  $_SESSION['id_finca']=$id_finca;

  function cargarNoti(){
  	global $id_finca;
  	require("conexion.php");
  	$hoy = date('Y-m-d');
  	$hasta = strtotime ( '+7 day' , strtotime ( $hoy ) ) ;
	$hasta = date ( 'Y-m-j' , $hasta );
  	//echo ($hasta);
  	$sql = "SELECT * FROM notificaciones WHERE fecha >='".$hoy."'AND fecha <= '".$hasta."'AND id_finca = '".$id_finca."'ORDER BY fecha ASC";
  	$respuesta = mysqli_query($conn, $sql);
  	if ( $respuesta->num_rows > 0){
  	while($fila = mysqli_fetch_assoc($respuesta)){
  		?>
  		<div class="article border-bottom">
			<div class="col-xs-12">
				<div class="row">
					<div class="col-xs-2 col-md-2 date">
						<div class="large"><?php echo date('j',strtotime($fila['fecha'])); ?></div>
						<div class="text-muted"><?php echo date('M',strtotime($fila['fecha'])); ?></div>
					</div>
					<div class="col-xs-10 col-md-10">
						<h4><a href="#"><?php echo $fila['titulo']?></a></h4>
						<P><?php echo $fila['descripcion'] ?></P>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div><!--End .article-->
  		<?php
    }
	}else{
		echo "<div class='col-xs-10 col-md-10'>";
		echo "<p>Momentaneamente No se Registran Alertas Climatologicas para los proximos dias.</p>";
		echo "</div>";
	}
 }
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Calendario</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!--<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">-->
	
	<link href='css/calendar.min.css' rel='stylesheet' />
	<link href='css/calendar.print.min.css' rel='stylesheet' media='print' />
	<link href="css/styles.css" rel="stylesheet">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

</head>
<body>
  	<?php
	include("plantilla1.php");
	?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-dashboard"></em>
				</a></li>
				<li class="active">Inicio</li>
			</ol>
		</div><!--/.row-->
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default articles">
					<div class="panel-heading">
						Alertas y Notificaciones
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body articles-container">

					<?php cargarNoti();	?>
					
					</div>
				</div><!--End .articles-->
				
			</div><!--/.col-->
			
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						Calendario
						
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<div id="calendar"></div>
					</div>
				</div>

					<div class="panel panel-default ">
					<div class="panel-heading">
						Gastos
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							<canvas class="chart" id="pie-chart" ></canvas>
						</div>
	
					</div>
				</div>

				
			</div><!--/.col-->
			<!--<div class="col-sm-12">
				<p class="back-link">Lumino Theme by <a href="https://www.medialoot.com">Medialoot</a></p>
			</div>-->
		</div><!--/.row-->
	</div>	<!--/.main-->

	<script src='js/moment.min.js'></script>
	<script src='js/jquery.min.js'></script>
	<script src="js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="js/Chart.bundle.min.js"></script>
	<script src='js/fullcalendar.min.js'></script>
	<script src='idioma/es-us.js'></script>
	
	<script src="js/custom.js"></script>
	<script>

  	$(document).ready(function() {

	/*var chart4 = document.getElementById("pie-chart").getContext("2d");
	window.myPie = new Chart(chart4).Pie(pieData, {
	responsive: true,
	segmentShowStroke: false
	});*/
  	$('.nav.menu li').eq(0).addClass('active');

  	var idfinca=$("#select").val();
    $('#calendar').fullCalendar({
      header: {
        //left: 'prev,next today',
        //center: 'title',
        right: 'month, today, prev,next'
      },
      defaultDate: new Date(),
      navLinks: true, // can click day/week names to navigate views
      businessHours: false, // display business hours
      eventLimit: true,
      eventSources:[{
      	url:'http://localhost:8080/Ingenieria/caleprevWeb/eventos.php?accion=leer&id='+idfinca,
      	id:'inicio',
      constraint: 'availableForMeeting'}],
      editable: false,
      droppable: false,

    });

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

			options: {
				responsive : true,
        		legend: {
            	display: true,
            	position: 'bottom',
            	labels: {
                	
            	}
        		}
			}
	});

  });

</script>

</body>
</html>
