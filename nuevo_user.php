<?php
  include "conexion.php";
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alta de Usuario</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
</head>
<body>
<div >
  <div id="contenido">
  	<div style="margin: auto; width: 800px; border-collapse: separate; border-spacing: 10px 5px;">
  		<span> <h2>Nuevo usuario</h2> </span>
  		<br>
	  <form action="nuevo_user2.php" method="POST" style="border-collapse: separate; border-spacing: 10px 5px;">
  		<table style="margin: auto; width: 800px; border-collapse: separate; border-spacing: 5px 0px;">
        <tr>
          <td><label>Nombre: </label></td>
          <td><input type="text" id="nombre" name="nombre"; value="" required></td>
        </tr>
        <tr>
          <td><label>Email: </label></td>
          <td><input type="text" id="email" name="email" value="" required></td>
        </tr>
        <tr>
          <td><label>Contraseña: </label></td>
          <td><input type="password" id="contraseña" name="contraseña" value="" required></td>
        </tr>
        <tr>
          <td><label>Repetir Contraseña: </label></td>
          <td><input type="password" id="contraseña1" name="contraseña1" value="" required></td>
        </tr>
        <tr>
          <td><label>Rol: </label></td>
          <td><select name ="rol">
              <option value="seleccione">Seleccione</option>
              <option value="administrador">Administrador</option>
              <option value="encargado">Encargado</option>
              <option value="empleado">Empleado</option>            
          </select></td>
        </tr>
    </table>
  		<br>
  		<button type="submit" class="btn btn-success">Guardar</button>
      <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
     </form>
  	</div>
  	
  </div>
</div>


</body>
</html>