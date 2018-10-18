<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Calendario</title>
  <!--<link href="css/bootstrap.min1.css" rel="stylesheet">-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  <link href="css/datepicker3.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">

  <!--Custom Font-->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span></button>
        <a class="navbar-brand" href="#"><span>Calendario</span></a>
      </div>
    </div><!-- /.container-fluid -->
  </nav>
  <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
    <div class="profile-sidebar">
      <div class="profile-userpic">
        <img src="http://placehold.it/50/30a5ff/fff" class="img-responsive" alt="">
      </div>
      <div class="profile-usertitle">
        <div class="profile-usertitle-name"><?php echo $_SESSION['user'];?></div>
        <div class="profile-usertitle-status"><span class="indicator label-success"></span>conectado</div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="divider"></div>
   <form class="form-inline" method="post">
    <div class="form-group mx-auto my-5">
      <label class="sr-only" for="lacation"></label>
      <input type="text" class="form-control" id="location" name="location" placeholder="ingrese ubicacion"> <br>
      <button class="btn btn-primary" type="button" onclick="buscar()">Buscar</button><br>
      <button style="margin-left:10px " class="btn btn-secondary" type="button" onclick="actual()">Localizacion Actual</button>
    </div>
    
  </form>
    <ul class="nav menu">
      <li><a href="inicio.php"><em class="fa fa-dashboard">&nbsp;</em> Inicio</a></li>
      <!-- widgets.html -->
      <li><a href="calendar.php"><em class="fa fa-calendar">&nbsp;</em> Calendario</a></li>
      <li><a href="fincas.php"><em class="fa fa-clone">&nbsp;</em> Finca</a></li>
      
      <li><a href="index.php"><em class="fa fa-power-off">&nbsp;</em> Salir</a></li>
    </ul>
  </div><!--/.sidebar-->
  
</body>
</html>