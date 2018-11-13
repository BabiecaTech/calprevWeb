<!DOCTYPE html>
<?php
//$id_finca=($_GET['id']);

  function cargar_fincas (){
    require("conexion.php");
    $sql = "SELECT * FROM fincas ";
    $respusta = mysqli_query($conn, $sql);
    while($fila = mysqli_fetch_assoc($respusta)){?>
        <option value="<?php echo utf8_encode($fila['id']) ?>"><?php echo utf8_encode($fila['nombre']) ?></option>
        <?php
    }
  }
?>
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
  <script src='js/jquery.min.js'></script>
</head>
<body>
  <input type="hidden" name="id" id="id_finca" value=<?php echo $_SESSION['id_finca'];?>>
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
    <form role="search">
      <div class="form-group">
        <select type="text" id="select" class="form-control" onchange="mostrar(this.value);">
        <?php
        cargar_fincas();
        ?></select>
      </div>
    </form>
    <ul class="nav menu">
      <li><a href="#" id="inicio" ><em class="fa fa-dashboard">&nbsp;</em> Inicio</a></li>
      <!-- widgets.html -->
      <li><a href="calendar.php" id="calendario" ><em class="fa fa-calendar">&nbsp;</em> Calendario</a></li>
      <li><a href="fincas.php" id="finca"><em class="fa fa-clone">&nbsp;</em> Fincas</a></li>
       <li><a href="contable.php" id="contable"><em class="fa fa-navicon">&nbsp;</em> Gestion Contable</a></li>
       <li><a href="lista_usuarios.php" id="finca"><em class="fa fa-user">&nbsp;</em> Usuarios</a></li>
      
      <li><a href="index.php"><em class="fa fa-power-off">&nbsp;</em> Salir</a></li>
    </ul>
    
  </div><!--/.sidebar-->
  
</body>
<script>
  
    $("#select").val($('#id_finca').val());
    $('#inicio').attr('href','inicio1.php?id='+$('#id_finca').val());

  function mostrar (value){
    //alert(value);
   // $('#inicio').attr('href','inicio.php?id='+value);
    window.location.replace("inicio1.php?id="+value);
  }
</script>
</html>