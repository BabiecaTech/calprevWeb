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
<link rel="stylesheet" type="text/css" href="css/styles.css">
<!--<link rel="stylesheet" type="text/css" href="css/estilo_tabla.css">-->
<style>
  .selected{
    cursor: pointer;
  }
  .selected:hover{
    background-color: #0585C0;
  }
  .seleccionada{
    background-color: #0585C0;
  
  }
</style>
</head>

<div class="modal fade" id="vistaModificar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloFinca"></h5>
        <button type="button" class="close" onclick="cerrar();" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
        <input type="hidden" id="txtId" name="txtId" />
        <input type="hidden" id="idFinca" name="idFinca" value="" />
         <input type="hidden" id="cantVino" name="cantVino" value=0 />
        Nombre: <input type="text" id="txtNameFinca" name="txtNameFinca" class="form-control" required/>
        <button type="button" class="btn btn-success" id="btnAdd" style="float: right; margin-top: 12px"><span class='glyphicon glyphicon-plus'></span></button>
        <button type='button' class='btn btn-danger' id="btnDel" style="float: right; margin-top: 12px"><span class='glyphicon glyphicon-trash'></span></button>
        <div class="table-responsive">
        <table id="tabla_modal" class="table" >
          <thead>
            <tr>
              <td>#</td>
              <td>Tipo Viñedo</td>
              <td>Hectareas</td>
            </tr>
          </thead>
          <tbody></tbody>
          
        </table>
      </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" id="btnModificar" class="btn btn-primary">Mofificar</button>
        
        <button type="button" onclick="cerrar();" id="btnCancelar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>

<body>
<?php
include("plantilla1.php");
?>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#">
          <em class="fa fa-clone"></em>
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
            <a href="map.php"> <button type="button" class="btn btn-success"><span class='glyphicon glyphicon-plus'></span> Agregar</button> </a>
            <div class="table-responsive">
             <table class="table" >
              <thead>
                <th>Nombre</th>
                <th>Tipo viñedo</th>
                <th>#Hectaria</th>
              </thead>
              <tbody>
              <?php
              include("conexion.php");
              $sql="SELECT * FROM fincas";
              $resultado=mysqli_query($conn,$sql);
              while($filas=mysqli_fetch_assoc($resultado))
              {
                echo "<tr>";

                  echo "<td id = 'nombre".$filas['id']."'> "; echo $filas['nombre']; echo "</td>";
                  //echo "<td>"; echo $filas['hectareas']; echo "</td>";
                  $id_tipo=$filas['id'];
                 //echo $id_tipo;
                  $consulta= "SELECT * FROM finvin WHERE id_finca =".$id_tipo;
                  $resultado1=mysqli_query($conn,$consulta);
                    echo "<td>";
                    echo "<table>";
                    //echo ($resultado1 -> num_rows);
                    $cont = 0;
                  while ($vino=mysqli_fetch_assoc($resultado1)){ 
                    $cont++;                   
                    echo "<tr>";
                    echo "<td><p hidden id = 'vino".$filas['id'].$cont."'>".$vino['id_vinedo']."</p>"; cargarVino($vino['id_vinedo'],$conn); echo "</td>";
                    echo "</tr>";
                    
                  }
                  
                   echo "</table>";
                    echo "</td>";
                    echo "<p id ='cantVino".$filas['id']."' hidden>"; echo $cont; echo" </p>";
                     echo "<td>";
                    echo "<table style='text-align:center;'>";
                  $resultado1=mysqli_query($conn,$consulta);
                  $cont = 0;
                  while ($vino=mysqli_fetch_assoc($resultado1)){  
                  $cont++;                   
                    echo "<tr>";
                    echo "<td id = 'hecta".$filas['id'].$cont."'>"; echo $vino['cant_hectarea']; echo "</td>";
                    echo "</tr>";
                  }
                   echo "</table>";
                    echo "</td>";
                  //if ($row=mysqli_fetch_assoc($resultado1)){
                    //echo "<td>"; echo $row['id_vinedo']; echo "</td>";
                  //}

                  echo "<td><button type='button' class='btn btn-info' onclick='modificarFinca(".$filas['id']. ")'><span class='glyphicon glyphicon-pencil'></span></button> </a>";
                  echo "<button type='button' class='btn btn-danger' onclick='eliminarFinca(".$filas['id'].")'><span class='glyphicon glyphicon-trash'></span></button> </td>";
                echo "</tr>";
              }

              function cargarVino($id, $conn){
                  $consulta= "SELECT * FROM vinedos WHERE id =".$id;
                  $resultado1=mysqli_query($conn,$consulta);
                  if ($row=mysqli_fetch_assoc($resultado1)){
                    echo $row['nombre'];
                  }
              }

              ?>
              </tbody>
      </table>
    </div>
       <!--<button type="button" class="btn btn-success" id="btnAdd"><span class='glyphicon glyphicon-plus'></span> Agregar</button>-->
          </div>
        </div>
      </div>
      
    </div><!--/.row-->

  </div>

</body>

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
       // location.href = "eliminar_finca.php?id="+id;
      // eliminar(id);
      }
  }

  function modificarFinca(id){
    var nombre = $("#nombre"+id+"").text();
    $('#txtNameFinca').val(nombre);
    $('#idFinca').val(id);
    var cant = $("#cantVino"+id+"").text();
    var i=1;
    while (i<=cant){
      $('#btnAdd').click();
      $("#select"+i+"").val($("#vino"+id+i+"").text());
      $("#hecta"+i+"").val($("#hecta"+id+i+"").text());
      i++;
    }

    $('#vistaModificar').modal();
  }
  

</script>

<script type="text/javascript">
  $(document).ready(function() {
  $('#btnAdd').on("click", function(){
    $('#vistaModal').modal();
  });
});
</script>

 <script>
  $(document).ready(function(){
  
    $('#btnAdd').click(function(){

      agregar();
    });
    $('#btnDel').click(function(){
      eliminar(id_fila_selected);
    });
    
    $('#btnModificar').click(function(){
      if (confirm("¿Desea actualizar la Finca..?")==true){
        recolectarDatos();
        enviar('modificar');
      }      
    });

  });
  var cont=0;

  var id_fila_selected=[];
  function agregar(){
    cont++;
   //alert(cont); 
   /* var fila='<tr class="selected" id="fila'+cont+'" onclick="seleccionar(this.id);"> <td><select id = "select'+cont+'" name ="select'+cont+'" class="form-control" style="height:45px" required><option value="bonarda">bonarda</option><option value="cabernet">cabernet</option><option value="chardonnay">chardonnay</option><option value="malbec">malbec</option><option value="soivignon">soivignon</option><option value="syrah">syrah</option></select></td><td><input type="number" id="hectareas'+cont+'" name="hectareas'+cont+'" value=0 class="form-control" required></td></tr>';
    $('#tabla_modal').append(fila);*/
   var fila='<tr class="selected" id="fila'+cont+'" onclick="seleccionar(this.id);"><td>'+cont+'</td><td><select id = "select'+cont+'" name ="select'+cont+'"class="form-control" style="height:45px" required><option value="1">Malbec</option><option value="2">Cabernet</option><option value="3">Bonarda</option><option value="4">Syrah</option><option value="5">Tempranillo</option><option value="7">Chardonnay</option><option value="8">Sauvignon</option></select></td><td><input type="number" id="hecta'+cont+'" name="hecta'+cont+'" value=0 class="form-control" required></td></tr>';
    $('#tabla_modal').append(fila);
    reordenar();
  }

   var dato;
 function recolectarDatos(){
    dato= {
      id:$('#idFinca').val(),
      nombre: $('#txtNameFinca').val(),
      cantidad:$('#cantVino').val()
    };
    var cant = $('#cantVino').val();
    var i =1;
    var arreglo= [];
    while (i <= cant){
     arreglo.push([
      {select:$('#select'+i).val()},
      {hecta:$('#hecta'+i).val()}
     ]);
     i++;
    }
    dato = Object.assign({},dato,arreglo);
  };
   

  function enviar(accion){
    //alert(dato);
    $.ajax({
    type:'POST',
        url:'guardar_finca.php?accion='+accion,
        data: dato,
        success:function(msg){
          $('#vistaModal').modal('toggle');

          location.reload();
          alert("Finca se actualizo exitosamente");
        },
        error:function(msg){
        alert("Error conexion base de datos..."); 
      }
  });
  };


  function cerrar(){
    eliminarTodasFilas();
    $('#cantVino').val(0);
    $('#idFinca').val("");
    cont =0;
  }

  function seleccionar(id_fila){
    if($('#'+id_fila).hasClass('seleccionada')){
      $('#'+id_fila).removeClass('seleccionada');
    }
    else{
      $('#'+id_fila).addClass('seleccionada');
      id_fila_selected.push(id_fila);
    }
    //2702id_fila_selected=id_fila;
    //id_fila_selected.push(id_fila);
  }

  function eliminar(id_fila){
    /*$('#'+id_fila).remove();
    reordenar();*/
    for(var i=0; i<id_fila.length; i++){
      $('#'+id_fila[i]).remove();
    }

    reordenar();
    id_fila_selected=[];
  }

  function reordenar(){
    var num=0;
    $('#tabla_modal tbody tr').each(function(){
      num++;
      $(this).find('td').eq(0).text(num);
      $(this).eq(0).attr('id','fila'+num);
      $('#fila'+num).find('select').attr('name','select'+num);
      $('#fila'+num).find('select').attr('id','select'+num);
      $('#fila'+num).find('input').attr('name','hecta'+num);
      $('#fila'+num).find('input').attr('id','hecta'+num);
    });
    $('#cantVino').val(num--);
   // alert(num);
  }
  function eliminarTodasFilas(){
$('#tabla_modal tbody tr').each(function(){
      $(this).remove();
    });

  }


</script>

</html>