<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Fincas</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="css/estilo.css" rel="stylesheet" type="text/css">
</head>
<script src="jquery-3.3.1.min.js"></script>
<script type="text/javascript">
  function eliminarFinca(id){
      if (confirm("¿Desea eliminar la Finca?")==true){
        location.href = "eliminar_finca.php?id="+id;
      }
  }
  
</script>
<?php
  session_start();
  if (@!$_SESSION['user']) {
    header("Location:index.php");
  }
?>
<body>
<?php
include("menu.php");
?>
<div>
<h3 align="center">Datos Finca</h3>
<div id="contenido">
<div class="fincas">
    <table style="margin: auto; width: 800px; border-collapse: separate; border-spacing: 10px 5px;">
      <thead>
        <th>Nombre</th>
        <th>Hetareas</th>
        <th>Tipo viñedo</th>
        <th> <a href="nueva_finca.php"> <button type="button" class="btn btn-info">Nueva Finca</button> </a> </th>
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

          echo "<td><a href='modif_finca.php?id=".$filas['id']."'> <button type='button' class='btn btn-success'>Modificar</button> </a> </td>";
          echo "<td><button type='button' class='btn btn-danger' onclick='eliminarFinca(".$filas['id'].")'>Eliminar</button> </td>";
        echo "</tr>";
      }

      ?>
  </div>
</div>
</div>
</div>


</body>
</html>