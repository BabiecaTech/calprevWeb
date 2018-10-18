<?php
  
  $consulta=ConsultarLogin($_GET['id']);

  function ConsultarLogin($id)
  {
    require("conexion.php");
    //echo ($id);
    $sql="SELECT * FROM fincas WHERE id=$id";
    $resultado=mysqli_query($conn,$sql);
    $fila=mysqli_fetch_assoc($resultado);
    return [
      $fila['nombre'],
      $fila['hectareas'],
      $fila['id_tipo']
    ];

  }function cargar_vinedos($id){
    require("conexion.php");
    //echo ($id);
    $sql="SELECT * FROM vinedos";
    $resultado=mysqli_query($conn,$sql);
    while ($fila=mysqli_fetch_assoc($resultado)) {
      ?>
      <option value="<?php echo $fila['id']?>"><?php echo $fila['nombre']?></option>
      <?php
      
    }
  }

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modificar Finca</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
</head>
<script type="text/javascript">
  function regresar(){
    location.href = "eliminar_user.php?id="+id;
  }
</script>
<body>
<div>
  
  <div id="contenido">
  	<div style="margin: auto; width: 800px; border-collapse: separate; border-spacing: 10px 5px;">
  		<span> <h2 align="rigth">Modificar Finca</h2> </span>
	  <form action="modif_user2.php" method="POST" style="border-collapse: separate; border-spacing: 10px 5px;">
      <input type="hidden" name="id" value="<?php echo $_GET['id']?> ">
      <table style="margin: auto; width: 800px; border-collapse: separate; border-spacing: 5px 0px;">
        <tr>
          <td><label>Nombre: </label></td>
          <td><input type="text" id="nombre" name="nombre"; value="<?php echo $consulta[0] ?>" ></td>
        </tr>
        <tr>
          <td><label>Hectareas: </label></td>
          <td><input type="text" id="hectareas" name="hectareas" value="<?php echo $consulta[1] ?>"></td>
        </tr>
        <tr>
          <td><label>Tipo Viñedo: </label></td>
          
          <td><select name ="tipo_vinedo">
            <?php $tipo=$consulta[2];
            cargar_vinedos($tipo);?>           
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