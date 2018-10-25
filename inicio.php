<!DOCTYPE html>
<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }

  function cargarNoti(){
  	require("conexion.php");
  	$hoy = date('Y-m-d');
  	$hasta = strtotime ( '+7 day' , strtotime ( $hoy ) ) ;
	$hasta = date ( 'Y-m-j' , $hasta );
  	//echo ($hasta);
  	$sql = "SELECT * FROM notificaciones WHERE fecha >='".$hoy."'AND fecha <= '".$hasta."'ORDER BY fecha ASC";
  	$respusta = mysqli_query($conn, $sql);
  	while($fila = mysqli_fetch_assoc($respusta)){
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
	include("plantilla.php");
	?>
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Inicio</li>
			</ol>
		</div><!--/.row-->
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default articles">
					<div class="panel-heading">
						Ultimas Notificaciones
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
						Barra de Costos
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<div class="col-md-12 no-padding">
							<div class="row progress-labels">
								<div class="col-sm-6">General</div>
								<div class="col-sm-6" style="text-align: right;">80%</div>
							</div>
							<div class="progress">
								<div data-percentage="0%" style="width: 80%;" class="progress-bar progress-bar-blue" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="row progress-labels">
								<div class="col-sm-6">Combustible</div>
								<div class="col-sm-6" style="text-align: right;">60%</div>
							</div>
							<div class="progress">
								<div data-percentage="0%" style="width: 60%;" class="progress-bar progress-bar-orange" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="row progress-labels">
								<div class="col-sm-6">Auxiliar</div>
								<div class="col-sm-6" style="text-align: right;">40%</div>
							</div>
							<div class="progress">
								<div data-percentage="0%" style="width: 40%;" class="progress-bar progress-bar-teal" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="row progress-labels">
								<div class="col-sm-6">impuestos</div>
								<div class="col-sm-6" style="text-align: right;">20%</div>
							</div>
							<div class="progress">
								<div data-percentage="0%" style="width: 20%;" class="progress-bar progress-bar-red" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
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
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src='js/fullcalendar.min.js'></script>
	<script src='idioma/es-us.js'></script>
	<script src='js/jquery-ui.min.js'></script>
	<script>

  	$(document).ready(function() {

  	$('.nav.menu li').eq(0).addClass('active');

    $('#calendar').fullCalendar({
      header: {
        //left: 'prev,next today',
        //center: 'title',
        //right: 'month,agendaWeek,agendaDay,listMonth'
      },
      defaultDate: new Date(),
      navLinks: false, // can click day/week names to navigate views
      businessHours: false, // display business hours

      eventSources:[{
      	url:'http://localhost:8080/Ingenieria/caleprevWeb/eventos.php?accion=leer',
      	id:'inicio',
      constraint: 'availableForMeeting'}],
      editable: false,
      droppable: false,

    });

  });

</script>

</body>
</html>
