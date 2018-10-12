<!DOCTYPE html>
<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Fincas</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">

</head>
<script src="jquery-3.3.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.nav.menu li').eq(2).addClass('active');
      });
  </script>
<script type="text/javascript">
  function eliminarFinca(id){
      if (confirm("¿Desea eliminar la Finca?")==true){
        location.href = "eliminar_finca.php?id="+id;
      }
  }
  
</script>
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
        <li class="active">Finca</li>
      </ol>
    </div><!--/.row-->
    
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Datos de Finca</h1>
      </div>
    </div><!--/.row-->

    <div class="row">

      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
             <table style="margin: auto; width: 800px; border-collapse: separate; border-spacing: 10px 5px;">
      <thead>
        <th>Nombre</th>
        <th>Hetareas</th>
        <th>Tipo viñedo</th>
      </thead>
      
      <?php
      require("conexion.php");
      $sql="SELECT * FROM fincas";
      $resultado=mysqli_query($conn,$sql);
      //echo ($resultado);
      while($filas=mysqli_fetch_assoc($resultado))
      {
        echo "<tr>";
          echo "<td>"; echo $filas['nombre']; echo "</td>";
          echo "<td>"; echo $filas['hectareas']; echo "</td>";
          $id_tipo=$filas['id_tipo'];
          $consulta= "SELECT * FROM vinedos WHERE id = $id_tipo";
          $resultado1=mysqli_query($conn,$consulta);
          if ($row=mysqli_fetch_assoc($resultado1)){
            echo "<td>"; echo $row['nombre']; echo "</td>";
          }

          echo "<td><a href='modif_finca.php?id=".$filas['id']."'> <button type='button' class='btn btn-info'><span class='glyphicon glyphicon-pencil'></span></button> </a> </td>";
          echo "<td><button type='button' class='btn btn-danger' onclick='eliminarFinca(".$filas['id'].")'><span class='glyphicon glyphicon-trash'></span></button> </td>";
        echo "</tr>";
      }

      ?>
      </table>
       <a href="nueva_finca.php"> <button type="button" class="btn btn-success"><span class='glyphicon glyphicon-plus'></span></button> </a>
          </div>
        </div>
      </div>
      
    </div><!--/.row-->

  </div>

</body>
</html>