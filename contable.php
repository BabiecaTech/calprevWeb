<!DOCTYPE html>
<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }

  function cargarGastos(){
  	require("conexion.php");
  	$sql = "SELECT gastos.id, gastos.fecha, gastos.monto, gastos.descripcion, gastos.id_tipo, gastos.id_user, login.user, tipogastos.nombre FROM (gastos JOIN login on gastos.id_user = login.id JOIN tipogastos ON gastos.id_tipo = tipogastos.id) WHERE gastos.id_finca ='".$_SESSION['id_finca']."' ORDER BY fecha DESC";
  	$respuesta = mysqli_query($conn, $sql);
  
  	while($fila = mysqli_fetch_assoc($respuesta)){
  		?>
  		<tr id="g<?php echo $fila['id'];?>" onclick="mostrar(this.id);">
  			<td><?php echo $fila['fecha'];?></td>
  			<td><?php echo $fila['monto'];?></td>
  			<td id=<?php echo $fila['id_tipo'];?>><?php echo $fila['nombre'];?></td>
  			<td><?php echo $fila['descripcion'];?></td>
  			<td id=<?php echo $fila['id_user'];?>><?php echo $fila['user'];?></td>
  			<td hidden id="id_gasto"><?php echo $fila['id'];?></td>
  		</tr>
  		<?php
    }

 }
 function cargarTipo(){
  	require("conexion.php");
  	$sql = "SELECT * FROM tipogastos ORDER BY nombre ASC";
  	$respuesta = mysqli_query($conn, $sql);
  
  	while($fila = mysqli_fetch_assoc($respuesta)){
  		?>
  		<option value=<?php echo $fila['id']; ?>><?php echo $fila['nombre']; ?></option>
  		<?php
    }
 }

 function cargarIngresos(){
 	require("conexion.php");
  	$sql = "SELECT ingresos.id, ingresos.fecha, ingresos.monto, ingresos.descripcion, ingresos.id_finca, ingresos.id_user, login.user FROM (ingresos JOIN login ON ingresos.id_user = login.id) WHERE ingresos.id_finca='".$_SESSION['id_finca']."' ORDER BY fecha DESC";
  	$respuesta = mysqli_query($conn, $sql);
  
  	while($fila = mysqli_fetch_assoc($respuesta)){
  		?>
  		<tr id="i<?php echo $fila['id'];?>" onclick="mostrarI(this.id);">
  			<td><?php echo $fila['fecha'];?></td>
  			<td><?php echo $fila['monto'];?></td>
  			<td><?php echo $fila['descripcion'];?></td>
  			<td id=<?php echo $fila['id_user'];?>><?php echo $fila['user'];?></td>
  			<td hidden id="id_ingreso"><?php echo $fila['id'];?></td>
  		</tr>
  		<?php
    }
 }
 function fecha(){
 	return date('Y-m-d');
 }
  ?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gestion Contable</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="css/styles.css" rel="stylesheet">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<style>
  .seleccionada{
    background-color: #F1EFEE;
  
  }
</style>

	
</head>
<!--MODALS TABLA GASTOS-->
<!--MODAL NUEVO GASTO-->
<div class="modal fade" id="modal_gastos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="titulo">Nuevo Gasto</h5>
        
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
        <input type="hidden" id="idUser" name="idUser" />
        Fecha: <input type="date" id="fecha" name="fecha" class="form-control" value=<?php echo fecha();?> required>
        Tipo: <SELECT id="tipo" name="tipo" class="form-control" required>
          	<?php cargarTipo();?>
        	</SELECT>
        Monto $: <input type="number" id="costo" name="costo" value="0" class="form-control" min="0">
        Descripcion: <textarea id="desc" rows="3" class="form-control"> </textarea>
        </div>
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" id="btnGuardar" class="btn btn-primary">Guardar</button>
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>
<!--MODAL MODIFICAR GASTO-->
<div class="modal fade" id="modal_gastosM" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="titulo">Modificar Gasto</h5>
        
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
        <input type="hidden" id="idGasto" name="idGasto" />
        Fecha: <input type="date" id="fechaM" name="fechaM" class="form-control" value=<?php echo fecha();?> required>
        Tipo: <SELECT id="tipoM" name="tipoM" class="form-control" required>
          	<?php cargarTipo();?>
        	</SELECT>
        Monto $: <input type="number" id="costoM" name="costoM" value="0" class="form-control" min="0">
        Descripcion: <textarea id="descM" rows="3" class="form-control"> </textarea>
        </div>
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" id="btnModificar" class="btn btn-primary">Mofificar</button>
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>
<!--MODALS TABLA INGRESOS-->
<!--MODAL NUEVO INGRESO-->
<div class="modal fade" id="modal_ingreso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="titulo">Nuevo Ingreso</h5>
        
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
        Fecha: <input type="date" id="fechaI" name="fechaI" class="form-control" value=<?php echo fecha();?> required>
        Monto $: <input type="number" id="costoI" name="costoI" value="0" class="form-control" min="0">
        Descripcion: <textarea id="descI" rows="3" class="form-control"> </textarea>
        </div>
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" id="btnGuardarI" class="btn btn-primary">Guardar</button>
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>
<!--MODAL MODIFICAR INGRESO-->
<div class="modal fade" id="modal_ingresoM" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="tituloI">Modificar Ingreso</h5>
        
      </div>
      <div class="modal-body">
        <form>
        <div class="form-group">
        <input type="hidden" id="idIngreso" />
        Fecha: <input type="date" id="fechaIM" name="fechaIM" class="form-control" value=<?php echo fecha();?> required>
        Monto $: <input type="number" id="costoIM" name="costoIM" value="0" class="form-control" min="0">
        Descripcion: <textarea id="descIM" rows="3" class="form-control"> </textarea>
        </div>
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" id="btnModificarI" class="btn btn-primary">Mofificar</button>
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
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
          <em class="fa fa-navicon"></em>
        </a></li>
        <li class="active">Gestión Contable</li>
      </ol>
    </div><!--/.row-->

    <div class="row">

    <div class="col-lg-12">
      	<div class="panel panel-default chat">
      		<div class="panel-heading">
			Lista de Gastos
			<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
			
            </div>
        	<div class="panel-body">
        		<button type="button" class="btn btn-danger btn-sm" onclick="eliminar()"><span class="glyphicon glyphicon-trash"></span></button>
            <button type='button' class='btn btn-info btn-sm' onclick='modificar()'><span class='glyphicon glyphicon-pencil'></span></button>
            <button type='button' class='btn btn-success btn-sm' onclick='nuevo()'><span class='glyphicon glyphicon-plus'></span></button>
        	<div class="table-responsive">
             <table class="table table-hover">
              <thead class="thead-dark">
                <th>Fecha</th>
                <th>Monto $</th>
                <th>Tipo</th>
                <th>Descripcion</th>
                <th>Generado</th>
              </thead>

              <tbody>
              	<?php cargarGastos();?>
              </tbody>
          		<!--<div class="canvas-wrapper"> id="canvas-container" style="width:50%;"-->
					<!--<canvas class="chart" id="pie-chart"></canvas> width="500" height="350"-->
				<!--</div>-->
			</table>
		</div>
			</div>
		</div>
	</div>
	</div><!--/.row-->

    <div class="row">

    <div class="col-lg-12">
      	<div class="panel panel-default chat">
      		<div class="panel-heading">
			Lista de Ingresos
			<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
			
		</div>
        	<div class="panel-body">
        		<button type="button" class="btn btn-danger btn-sm" onclick="eliminarI()"><span class="glyphicon glyphicon-trash"></span></button>
            <button type='button' class='btn btn-info btn-sm' onclick='modificarI()'><span class='glyphicon glyphicon-pencil'></span></button>
            <button type='button' class='btn btn-success btn-sm' onclick='nuevoI()'><span class='glyphicon glyphicon-plus'></span></button>
        	<div class="table-responsive">
             <table class="table table-hover">
              <thead class="thead-dark">
                <th>Fecha</th>
                <th>Monto $</th>
                <th>Descripcion</th>
                <th>Generado</th>
              </thead>

              <tbody>
              	<?php cargarIngresos();?>
              </tbody>
          		<!--<div class="canvas-wrapper"> id="canvas-container" style="width:50%;"-->
					<!--<canvas class="chart" id="pie-chart"></canvas> width="500" height="350"-->
				<!--</div>-->
			</table>
		</div>
			</div>
		</div>
	</div>
	</div><!--/.row-->
</div>	
</body>
<script src="jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script type="text/javascript" src="js/Chart.bundle.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){

		$('.nav.menu li').eq(4).addClass('active');

    // botones modal gastos
	    $('#btnModificar').click(function(){
	      if (checkdatosM()){
	        if (confirm("¿Desea actualizar gasto seleccionado..?")==true){ 
	        recolectarModificar();
	        enviar('modificar');
	        }
	      }
	    });

	     $('#btnGuardar').click(function(){
	      if (checkdatos()){
	        if (confirm("¿Desea registrar nuevo gasto..?")==true){
	        recolectarNuevo();
	        enviar('guardar');
	        }
	      }      
	    });

	// botones modals ingresos
		$('#btnModificarI').click(function(){
	      if (checkdatosMI()){
	        if (confirm("¿Desea actualizar ingreso seleccionado..?")==true){ 
	        recolectarModificarI();
	        enviar('modificari');
	        }
	      }
	    });

	     $('#btnGuardarI').click(function(){
	      if (checkdatosI()){
	        if (confirm("¿Desea registrar nuevo ingreso..?")==true){
	        recolectarNuevoI();
	        enviar('guardari');
	        }
	      }      
	    });
  	});
		// funciones de gastos
		var id_fila_selected=[];
		var id_fila_selectedI=[];
		var dato;
		function mostrar(fila){
			//alert(fila);
			if (id_fila_selected.length == 1){
				var select = id_fila_selected[0];
				ocultar(id_fila_selected);
				if (select != fila){
					$('#'+fila).addClass('seleccionada');
			 		id_fila_selected.push(fila);
				}
				
			}else { 
			 $('#'+fila).addClass('seleccionada');
			 id_fila_selected.push(fila);
			}
		}

		function ocultar(unafila){
			var fila=unafila[0];
			$('#'+fila).removeClass('seleccionada');
			id_fila_selected=[];
		}

		function nuevo(){
			$('#fecha').val('');
			$('#costo').val(0);
			$('#tipo').val('');
			$('#desc').val('');
			$('#modal_gastos').modal();
		}

		function modificar(){
			if (id_fila_selected.length == 1){ 
			var id = id_fila_selected[0];
			$('#fechaM').val($('#'+id).eq(0).children().eq(0).text());
			$('#costoM').val($('#'+id).eq(0).children().eq(1).text());
			$('#tipoM').val($('#'+id).eq(0).children().eq(2).attr('id'));
			$('#descM').val($('#'+id).eq(0).children().eq(3).text());
			$('#modal_gastosM').modal();
			}else{
			alert("Debe seleccionar un gasto de la list para Modificar");
			}
		}

		function eliminar(){
			if (id_fila_selected.length == 1){ 
				 if (confirm("¿Desea eliminar gasto ..?")==true){
      				recolectarEliminar();
					enviar('eliminar');
      			}

			}else{
				alert("Debe seleccionar un gasto de la lista para Eliminar");
			}
		}

		function recolectarNuevo(){
			dato= {
      		fecha:$('#fecha').val(),
      		costo:$('#costo').val(),
      		tipo:$('#tipo').val(),
      		descripcion:$('#desc').val(),
      		id_finca:$("#id_finca").val()
    		};
		}

		function recolectarModificar(){
			dato= {
			id: $('#'+id_fila_selected[0]).eq(0).children().eq(5).text(),
      		fecha:$('#fechaM').val(),
      		costo:$('#costoM').val(),
      		tipo:$('#tipoM').val(),
      		descripcion:$('#descM').val()
    		};
		}
		function recolectarEliminar(){
			dato ={
			id: $('#'+id_fila_selected[0]).eq(0).children().eq(5).text()
			};
		}

		function checkdatosM(){
		    var result = true;
		    if ($('#fechaM').val() == ""){
		        alert("Introduzca una Fecha");
		        result = false;
		    } else if ($('#costoM').val() == 0){
		    	alert("Introduzca un Monto");
		    	result = false;
		    }
		    return result;
		  }

		  function checkdatos(){
		    var result = true;
		    if ($('#fecha').val() == ""){
		        alert("Introduzca una Fecha");
		        result = false;
		    }else if($('#tipo').val() == null){
		    	alert("Introduzca tipo de Gasto");
		        result = false;
		    }else if ($('#costo').val() == 0){
		        alert("Introduzca un Monto");
		        result = false;
		    }
		    return result;
		  }

		//funciones ingresos
		var id_fila_selectedI=[];
		//var datoI;
		function mostrarI(fila){
			//alert(fila);
			if (id_fila_selectedI.length == 1){
				var select = id_fila_selectedI[0];
				ocultarI(id_fila_selectedI);
				if (select != fila){
					$('#'+fila).addClass('seleccionada');
			 		id_fila_selectedI.push(fila);
				}
				
			}else { 
			 $('#'+fila).addClass('seleccionada');
			 id_fila_selectedI.push(fila);
			}
		}

		function ocultarI(unafila){
			var fila=unafila[0];
			$('#'+fila).removeClass('seleccionada');
			id_fila_selectedI=[];
		}

		function nuevoI(){
			$('#fechaI').val('');
			$('#costoI').val(0);
			$('#descI').val('');
			$('#modal_ingreso').modal();
		}

		function modificarI(){
			if (id_fila_selectedI.length == 1){ 
			var id = id_fila_selectedI[0];
			$('#fechaIM').val($('#'+id).eq(0).children().eq(0).text());
			$('#costoIM').val($('#'+id).eq(0).children().eq(1).text());
			$('#descIM').val($('#'+id).eq(0).children().eq(2).text());
			$('#modal_ingresoM').modal();
			}else{
			alert("Debe seleccionar un ingreso de la lista para Modificar");
			}
		}

		function eliminarI(){
			if (id_fila_selectedI.length == 1){ 
				 if (confirm("¿Desea eliminar ingreso ..?")==true){
      				recolectarEliminarI();
					enviar('eliminari');
      			}

			}else{
				alert("Debe seleccionar un ingreso de la lista para Eliminar");
			}
		}

		function recolectarNuevoI(){
			dato= {
      		fecha:$('#fechaI').val(),
      		costo:$('#costoI').val(),
      		descripcion:$('#descI').val(),
      		id_finca:$("#id_finca").val()
    		};
		}

		function recolectarModificarI(){
			dato= {
			id: $('#'+id_fila_selectedI[0]).eq(0).children().eq(4).text(),
      		fecha:$('#fechaIM').val(),
      		costo:$('#costoIM').val(),
      		descripcion:$('#descIM').val()
    		};
		}
		function recolectarEliminarI(){
			dato ={
			id: $('#'+id_fila_selectedI[0]).eq(0).children().eq(4).text()
			};
		}

		function checkdatosMI(){
		    var result = true;
		    if ($('#fechaIM').val() == ""){
		        alert("Introduzca una Fecha");
		        result = false;
		    } else if ($('#costoIM').val() == 0){
		    	alert("Introduzca un Monto");
		    	result = false;
		    }
		    return result;
		  }

		  function checkdatosI(){
		    var result = true;
		    if ($('#fechaI').val() == ""){
		        alert("Introduzca una Fecha");
		        result = false;
		    }else if ($('#costoI').val() == 0){
		        alert("Introduzca un Monto");
		        result = false;
		    }else if ($('#descI').val() == ""){
		        alert("Introduzca una descripcion");
		        result = false;
		    }
		    return result;
		  }

		function enviar(accion){
		$.ajax({
    	type:'POST',
        url:'guardar_contable.php?accion='+accion,
        data: dato,
        success:function(msg){
          if (accion == "guardar"){
          	$('#modal_gastos').modal('toggle');
            alert("Gasto registrado exitosamente");
          }else if (accion == "modificar"){
          	$('#modal_gastosM').modal('toggle');
            alert("Gasto actualizado exitosamente");
          }else if (accion == "eliminar"){
            alert("Gasto eliminado exitosamente");
          }else if(accion == "guardari"){
          	alert("Ingreso registrado exitosamente");
          }else if (accion == "modificari") {
          	alert("Ingreso actualizado exitosamente");
          }else {
          	alert("Ingreso eliminado exitosamente");
          }
        location.reload();
          
        },
        error:function(msg){
        alert("Error conexion base de datos..."); 
      		}
  		});
		}
	</script>
</html>