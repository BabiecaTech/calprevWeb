<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Usuarios</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
</head>
<script src="jquery-3.3.1.min.js"></script>
<script type="text/javascript">
  function eliminarUser(id){
      if (confirm("¿Desea eliminar el usuario?")==true){
        location.href = "eliminar_user.php?id="+id;
      }
  }

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
include("menu.php");
?>
<body>
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
    </table>

<h3 align="center">Lista de Usuarios</h3>
<div class="usuarios">
  	<table style="margin: auto; width: 800px; border-collapse: separate; border-spacing: 10px 5px;">
  		<thead>
  			<th>Usuario</th>
  			<th>email</th>
  			<th>Rol</th>
  			<th> <a href="nuevo_user.php"> <button type="button" class="btn btn-info">Nuevo</button> </a> </th>
  		</thead>
  		
  		
  		<?php
      require("conexion.php");
      $sql="SELECT * FROM login WHERE rol<>1";
      $resultado=mysqli_query($conn,$sql);
      //echo ($resultado);
      while($filas=mysqli_fetch_assoc($resultado))
      {
        echo "<tr>";
          echo "<td>"; echo $filas['user']; echo "</td>";
          echo "<td>"; echo $filas['email']; echo "</td>";
          $rol=$filas['rol'];
          if ($rol == 2){
            echo "<td>"; echo "Encargado"; echo "</td>";
          }else if ($rol == 3){
            echo "<td>"; echo "Empleado"; echo "</td>";
          }
          echo "<td>  <a href='modif_user.php?id=".$filas['id']."'> <button type='button' class='btn btn-success'>Modificar</button> </a> </td>";
          echo "<td> <button type='button' class='btn btn-danger' onclick='eliminarUser(".$filas['id'].")''>Eliminar</button> </td>";
        echo "</tr>";
      }

      ?>
  	</table>
  </div>
</div>
</div>
</div>


</body>
</html>