<?php
  
  $consulta=ConsultarLogin($_GET['id']);

  function ConsultarLogin($id)
  {
    require("conexion.php");
    //echo ($id);
    $sql="SELECT * FROM login WHERE id='".$id."'";
    $resultado=mysqli_query($conn,$sql);
    $fila=mysqli_fetch_assoc($resultado);
    return [
      $fila['user'],
      $fila['email'],
      $fila['rol']
    ];

  }


?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modificar Usuario</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
</head>
<body>
<div>
  
  <div id="contenido">
  	<div style="margin: auto; width: 800px; border-collapse: separate; border-spacing: 10px 5px;">
  		<span> <h2 align="rigth">Modificar Usuario</h2> </span>
	  <form action="modif_user2.php" method="POST" style="border-collapse: separate; border-spacing: 10px 5px;">
      <input type="hidden" name="id" value="<?php echo $_GET['id']?> ">
      <table style="margin: auto; width: 800px; border-collapse: separate; border-spacing: 5px 0px;">
        <tr>
          <td><label>Usuario: </label></td>
          <td><input type="text" id="user" name="user"; value="<?php echo $consulta[0] ?>" ></td>
        </tr>
        <tr>
          <td><label>Email: </label></td>
          <td><input type="text" id="email" name="email" value="<?php echo $consulta[1] ?>"></td>
        </tr>
        <tr>
          <td><label>Rol: </label></td>
          <td><select name ="rol">
            <?php $rol=$consulta[2];
            if ($rol == 2){?>
              <option value="Encargado" selected>Encargado</option>
              <option value="Empleado">Empleado</option>
            <?php
            }else if ($rol==3){?>
              <option value="Encargado">Encargado</option>
              <option value="Empleado" selected>Empleado</option>
            <?php
            }?>
            
          </select></td>
        </tr>
    </table>
  		<button type="submit" class="btn btn-success">Guardar</button>
      <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
     </form>
  	</div>
  	
  </div>
</div>


</body>
</html>