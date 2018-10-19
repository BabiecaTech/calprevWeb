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
<!--<link href="css/bootstrap.css" rel="stylesheet" type="text/css">-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<script src="jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

<script type="text/javascript">
  $(document).ready(function() {
  $('#btnAdd').on("click", function(){
    $('#vistaModal').modal();
  });
});
</script>

<div class="modal fade" id="vistaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloEvento"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
        <input type="hidden" id="txtId" name="txtId" />
        Fecha: <input type="date" id="txtFecha" name="txtFecha" class="form-control" required/>
        Tarea: <select id = "txtTitulo" name ="txtTitulo" class="form-control" required>       
          </select><!--<input type="text" id="txtTitulo" name="txtTitulo" />-->
        Hora Inicio: <input type="time" id="txtHora" name="txtHora" class="form-control" min="5:00" max="20:00" required/>
        Hora Finalizacion: <input type="time" id="txtHoraFin" name="txtHoraFin" class="form-control" min="6:00" max="21:00" required/>
        Descripcion: <textarea id="txtDesc" rows="3" placeholder="Escriba una descripcion de la tarea" class="form-control"> </textarea>
        Costo $: <input type="number" id="numCosto" name="numCosto" value="0" class="form-control">
        </div>
        </form>
      </div>
      <div class="modal-footer">
        
        <button type="button" id="btnModificar" class="btn btn-primary">Guardar</button>
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>

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
        <li class="active">Fincas</li>
      </ol>
    </div><!--/.row-->
    
    <!--<div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Fincas Registradas</h1>
      </div>
    </div>/.row-->

    <div class="row">

      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <a href="nueva_finca.php"> <button type="button" class="btn btn-success"><span class='glyphicon glyphicon-plus'></span> Agregar</button> </a>
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
       <!--<button type="button" class="btn btn-success" id="btnAdd"><span class='glyphicon glyphicon-plus'></span> Agregar</button>-->
          </div>
        </div>
      </div>
      
    </div><!--/.row-->

  </div>

</body>
</html>