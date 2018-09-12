<!DOCTYPE html>
<?php
  //include("conexion.php");
  //$result_events = "SELECT id, title, color, start, end FROM events";
  //$resultado = mysqli_query($conn, $result_events);
  //$result_login = "SELECT * FROM login";
  //$resultado = mysqli_query($conn, $result_login);
  //$resultado1 = mysqli_query($conn, $result_login);
  function cargar_tareas (){
  	require("conexion.php");
  	$sql = "SELECT * FROM tareas ORDER BY nombre ASC";
  	$respusta = mysqli_query($conn, $sql);
  	while($fila = mysqli_fetch_assoc($respusta)){?>
        <option value=<?php echo utf8_encode($fila['nombre']) ?>><?php echo utf8_encode($fila['nombre']) ?></option>
        <?php
    }

  }
  function cargar_usuarios (){
  	require("conexion.php");
  	$sql = "SELECT * FROM login ORDER BY user ASC";
  	$respusta = mysqli_query($conn, $sql);
  	while($fila = mysqli_fetch_assoc($respusta)){?>
        <option value=<?php echo utf8_encode($fila['user']) ?>><?php echo utf8_encode($fila['user']) ?></option>
        <?php
    }
  }
?>

<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }//elseif ($_SESSION['rol']==1) {
   // header("Location:admin.php");
  //}
  ?>
<html>
<head>
<meta charset='utf-8' />
<link href='css/estilo.css' rel='stylesheet' />
<link href='css/calendar.min.css' rel='stylesheet' />
<link href='css/calendar.print.min.css' rel='stylesheet' media='print' />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<script src='js/moment.min.js'></script>
<script src='js/jquery.min.js'></script>
<script src='js/fullcalendar.min.js'></script>
<script src='idioma/es-us.js'></script>
<script src='js/jquery-ui.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script>

   $(document).ready(function() {
   		//var t =0;

   	$('.menu2 li:has(ul)').click(function(e){
   		e.preventDefault();

   		if ($(this).hasClass('activado')){
   			$(this).removeClass('activado');
   			$(this).children('ul').slideUp();
   			$('#calendar').fullCalendar('removeEventSource', $('#calendar').fullCalendar('getEventSources')[1]);
   		}else{
   			$('.menu2 li ul').slideUp();
   			$('.menu2 li').removeClass('activado');
   			if($('#calendar').fullCalendar ('getEventSources').length >1){
   				$('#calendar').fullCalendar('removeEventSource', $('#calendar').fullCalendar('getEventSources')[1]);
   			}
   			$(this).addClass('activado');
   			
   			$(this).children('ul').slideDown();
   			if ($(this).attr('id') ==2){
   				//t=2;
   				actualizarRegla('cargar');
   				}
   			if ($(this).attr('id') ==3){
   				actualizarRegla('humedad');
   			}
   			if ($(this).attr('id') ==7){
   				actualizarRegla('viento');
   			}
   		}

   	});

     $('#external-events .fc-event').each(function() {

      // store data so the calendar knows to render an event upon drop
      $(this).data('event', {
        title: $.trim($(this).text()), // use the element's text as the event title
        stick: true // maintain when user navigates (see docs on the renderEvent method)
      });

      // make the event draggable using jQuery UI
      $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
      });

    });

    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek'
      },
      
      defaultDate: new Date(),
      editable: true,
      navLinks: true, // can click day/week names to navigate views
      businessHours: true,
      eventLimit: true,
      droppable: true,
      drop: function(date, jsEvent , ui , resourceId ) {
      	var moment = $('#calendar').fullCalendar('getDate').format().split("T");
      	var fechaHora = date.format();
      	if (fechaHora >= moment[0]){
       var t = {title:$.trim($(this).text()),
                descripcion:'',
                costo:0,
                start:date.format(),
                end:date.format(),
                asignar:<?php echo $_SESSION['id'] ?>,
                id_user:<?php echo $_SESSION['id'] ?>
              }
      enviarDatos('agregard',t,true);
  }else{
        	alert("Fecha no permitida para asignar la tarea");
        	//$('#calendar').fullCalendar('refetchEvents');
        	location.reload();
        }
      },
      
       // this allows things to be dropped onto the calendar
    	//'http://localhost:8080/Ingenieria/caleprevWeb/eventos.php'
      //events: a,
      eventSources:[{
      	url:'http://localhost:8080/Ingenieria/caleprevWeb/eventos.php?accion=leer',
      	id:'inicio'}],

      dayClick:function(date,jsEvent,view){
        //alert(date.format());
        var moment = $('#calendar').fullCalendar('getDate').format().split("T");
      	var fechaHora = date.format();
      	if (fechaHora >= moment[0]){
        $('#txtFechaA').val(date.format());
        $('#txtDescA').val("");
        $('#txtTituloA').val("tareaSelect");
        $('#txtHoraA').val("05:00");
        $('#txtHoraAfin').val("06:00");
        $('#numCostoA').val("0");
        $('#asignadaA').val("userSelect");
        $('#agregarModal').modal();
    }else{
        	alert("Fecha no permitida para asignar la tarea");
        	$('#calendar').fullCalendar('refetchEvents');
        }

      },

      eventClick:function(calEvent,jsEvent,view){
      	var moment = $('#calendar').fullCalendar('getDate').format().split("T");
      	var fechaHora = calEvent.start.format().split("T");
      	var fechaHoraFin = calEvent.end.format().split("T");
      	if (fechaHora[0] >= moment[0]){
        $('#tituloEvento').html(calEvent.title);
        $('#txtDesc').val(calEvent.descripcion);
        $('#numCosto').val(calEvent.costo);
        $('#asignada').val(calEvent.asignar);
        $('#txtId').val(calEvent.id);
        $('#txtTitulo').val(calEvent.title);

        $('#txtFecha').val(fechaHora[0]);
        $('#txtHora').val(fechaHora[1]);
        $('#txtHoraFin').val(fechaHoraFin[1]);

        $('#vistaModal').modal();
    	}else{
        	alert("La Tarea ya no puede ser Modificada");
        	//$('#calendar').fullCalendar('refetchEvents');
        }
      },
     // editable:true,
      eventDrop:function(calEvent){

      	var moment = $('#calendar').fullCalendar('getDate').format().split("T");
      	var fechaHora = calEvent.start.format().split("T");
      	var fechaHoraFin = calEvent.end.format().split("T");

      	if (fechaHora[0] >= moment[0]){
  			
        	$('#txtId').val(calEvent.id);
        	$('#txtTitulo').val(calEvent.title);
        	$('#txtDesc').val(calEvent.descripcion);
        	$('#numCosto').val(calEvent.costo);
        	$('#asignada').val(calEvent.asignar);
        
        	$('#txtFecha').val(fechaHora[0]);
        	$('#txtHora').val(fechaHora[1]);
        	$('#txtHoraFin').val(fechaHoraFin[1]);

        recolectarDatosM();
        enviarDatos('modificar',tarea,true);
        }else{
        	alert("Fecha no permitida para asignar la tarea");
        	$('#calendar').fullCalendar('refetchEvents');
        }

      },
      eventResize: function/*(event, delta, revertFunc)*/(calEvent) {
        //alert(calEvent.end.format());
        var fechaHora = calEvent.start.format().split("T");
        var fechaHoraFin = calEvent.end.format().split("T");
        $('#txtId').val(calEvent.id);
        $('#txtTitulo').val(calEvent.title);
        $('#txtDesc').val(calEvent.descripcion);
        $('#numCosto').val(calEvent.costo);
        $('#asignada').val(calEvent.asignar);
        
        $('#txtFecha').val(fechaHora[0]);
        $('#txtHora').val(fechaHora[1]);
        $('#txtHoraFin').val(fechaHoraFin[1]);

        recolectarDatosM();
        enviarDatos('modificar',tarea,true);
    //alert(event.title + " end is now " + event.end.format());

    /*if (confirm("desea relizar la modificacion?")) {
  
        $('#txtId').val(calEvent.id);
        $('#txtTitulo').val(calEvent.title);
        $('#txtDesc').val(calEvent.descripcion);

        var fechaHora = calEvent.start.format().split("T");
        $('#txtFecha').val(fechaHora[0]);
        $('#txtHora').val(fechaHora[1]);

        recolectarDatosM();
        enviarDatos('modificar',tarea,true);
    }*/

  },
  eventMouseover: function(event, jsEvent, view) {
    //$('.fc-event-inner', this).append('<div id=\"'+event.id+'\" //class=\"hover-end\">'+'</div>');
},

eventMouseout: function(event, jsEvent, view) {
    //$('#'+event.id).remove();
}


    });

  });

</script>

<style>


 * {
 	margin: 0;
 	padding: 0;
 	box-sizing: border-box;
 }	
  #wrap {
    width: 100%;
    margin: auto;
  }

  #external-events {
  	float: left;
    width: 20%;
  }

  #external-events .menu2 {
    margin-top: 10px;
    margin-left: 10px;
    padding: 5px;
    border: 1px solid #ccc;
    background: #eee;
  }

  #external-events h4 {
    font-size: 16px;
    margin-top: 0;
    padding-top: 1em;
  }

  #external-events .fc-event {
    margin: 5px 0;
    cursor: pointer;
  }

  #external-events p {
    margin: 1.5em 0;
    font-size: 11px;
    color: #666;
  }

  #external-events p input {
    margin: 0;
    vertical-align: middle;
  }

  #calendar {
    float: right;
    margin: 10px;
    width: 75%;
  }

  .menu2 {
  	width: 20%;
  	min-width: 300px;
  	display: inline-block;
  }
  ul {
  	list-style: none;
  }
  .menu2 li a {
  	display: block;
  	text-decoration: none;
  	background: #e9e9e9;
  	color: #494949;
  }

  .menu2 li a:hover {
  	background: #1a95d5;
  	color: #fff;
  }
  .menu2 ul {
  	display: none;
  }

  .menu2 .activado > a{
  	background: #1a95d5;
  	color: #fff;
  }



</style>
</head>
<!-- Modal -->
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
              <!--<option value="Arar" selected >Arar</option>
              <option value="Poda">Poda</option>
              <option value="Curacion">Curacion</option>
              <option value="Riego">Riego</option>
              <option value="Desorrillar">Desorrillar</option>
              <option value="Desbrote">Desbrote</option> -->
              <?php cargar_tareas();?>             
          </select><!--<input type="text" id="txtTitulo" name="txtTitulo" />-->
        Hora Inicio: <input type="time" id="txtHora" name="txtHora" class="form-control" min="5:00" max="20:00" required/>
        Hora Finalizacion: <input type="time" id="txtHoraFin" name="txtHoraFin" class="form-control" min="6:00" max="21:00" required/>
        Descripcion: <textarea id="txtDesc" rows="3" placeholder="Escriba una descripcion de la tarea" class="form-control"> </textarea>
        Costo $: <input type="number" id="numCosto" name="numCosto" value="0" class="form-control">
        Asignar a: <select id = "asignada" name ="asignada" class="form-control" required>
          		<?php cargar_usuarios();?>
            </select>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        
        <button type="button" id="btnModificar" class="btn btn-primary">Modificar</button>
        <button type="button" id="btnEliminar" class="btn btn-danger">Eliminar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>

  <!-- Modal agregar -->
<div class="modal fade" id="agregarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloEvento"> Nueva Tarea</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!--<div id="descripcionEvento"></div>-->
        <form>
        	<div class="form-group">
        <input type="hidden" id="txtIdA" name="txtId"/>
        Fecha: <input type="date" id="txtFechaA" name="txtFecha" class="form-control" required/>
        Tarea: <select id = "txtTituloA" name ="txtTituloA" class="form-control" required>
        		<option value="tareaSelect">Seleccione Tarea</option>
              <?php cargar_tareas();?>
            </select>
        Hora Inicio: <input type="time" id="txtHoraA" name="txtHoraA" class="form-control" min="5:00" max="20:00" required/>
        Hora Finalizacion: <input type="time" id="txtHoraAfin" name="txtHoraAfin" class="form-control"min="6:00" max="21:00" required/>
        Descripcion: <textarea id="txtDescA" rows="3" placeholder="Escriba una descripcion de la tarea" class="form-control"> </textarea>
        Costo $: <input type="number" id="numCostoA" name="numCostoA" value="0" class="form-control">
        Asignar a: <select id = "asignadaA" name ="asignadaA" class="form-control" required>
        			<option value="userSelect">Seleccione Usuario</option>
          			<?php cargar_usuarios();?>
            	</select>
        <input type="hidden" id="idUser" name="idUser" value=<?php echo $_SESSION['id'] ?> />
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnAgregar" class="btn btn-success">Agregar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  var nuevo, tarea, datos, id_finca;
  $('#btnAgregar').click(function(){
  	if ($('#txtTituloA').val() == "tareaSelect"){
  		alert ("Debe seleccionar una tarea");
  		$('#txtTituloA').focus();
  	}else if ($('#asignadaA').val() == "userSelect") {
  		alert ("Debe asignar la tarea a un usuario");
  		$('#asignadaA').focus();

  	}else{
    recolectarDatos();
    /*$('#calendar').fullCalendar('renderEvent',nuevo);
    $('#agregarModal').modal('toggle');*/
    enviarDatos('agregar',nuevo);
}

  });

  $('#btnEliminar').click(function(){

    recolectarDatosM();
    enviarDatos('eliminar',tarea);

  });

  $('#btnModificar').click(function(){

    recolectarDatosM();
    enviarDatos('modificar',tarea);

  });

  $('#btnActualizar').click(function(){

    enviarDatos('actualizar',id_finca);

  });

  $('#btnCargar').click(function(){

  });

  function obtenerFinca(){
  	id_finca = {
  		id_finca:1
  	}
  }

  function recolectarDatos(){
  	
    nuevo = {
      id:$('#txtIdA').val(),
      title:$('#txtTituloA').val(),
      descripcion:$('#txtDescA').val(),
      costo:$('#numCostoA').val(),
      asignar:$('#asignadaA').val(),
      start:$('#txtFechaA').val()+" "+$('#txtHoraA').val(),
      end:$('#txtFechaA').val()+" "+$('#txtHoraAfin').val(),
      asignar:$('#asignadaA').val(),
      id_user:<?php echo $_SESSION['id'] ?>
  };
}

function recolectarDatosM(){
    tarea = {
      id:$('#txtId').val(),
      title:$('#txtTitulo').val(),
      descripcion:$('#txtDesc').val(),
      costo:$('#numCosto').val(),
      start:$('#txtFecha').val()+" "+$('#txtHora').val(),
      end:$('#txtFecha').val()+" "+$('#txtHoraFin').val(),
      asignar:$('#asignada').val(),
      id_user:<?php echo $_SESSION['id'] ?>
  };
}

function actualizarRegla(accion){
		$.ajax({
		type:'POST',
      	url:'eventos.php?accion='+accion,
      	success:function(msg){
      		
      		$('#calendar').fullCalendar ('addEventSource', msg);
      		$('#calendar').fullCalendar('refetchEvents');
      	
      	},
      	error:function(msg){
        alert("Error conexion base de datos..."); 
      }
	});
}

function enviarDatos(accion,objEvento,modal){
  $.ajax({
      type:'POST',
      url:'eventos.php?accion='+accion,
      data:objEvento,
      success:function(msg){
    
        if (accion=='cargar'){
        	console.log(msg);
        }
        if(msg){
          if (accion=='agregard'){
            location.reload();
            //$('#calendar').fullCalendar('refetchEvents');
          }else{
          $('#calendar').fullCalendar('refetchEvents');
          if(accion=='agregar'){
            $('#agregarModal').modal('toggle');
          }else{
            if(!modal){
              $('#vistaModal').modal('toggle');
            }
          }
        }
     
        }
      },
      error:function(msg){
        alert("Error conexion base de datos...");
      }

  });
}
</script>
<body>
  <?php
include("menu.php");
?>

  <div id='wrap'>

    <div id='external-events'>
     
      <ul class="menu2">
      	<li id="1"><a href="">Tareas sobre la planta</a>
      		<ul>
      			<li><div class='fc-event'>Desbrotar</div></li>
      			<li><div class='fc-event'>Despegar Malla Antigranizo</div></li>
      			<li><div class='fc-event'>Despuntar</div></li>
      			<li><div class='fc-event'>Levantar Malla para Cosecha</div></li>
      			<li><div class='fc-event'>Poda</div></li>
      			<li><div class='fc-event'>Atada</div></li>
      			<li><div class='fc-event'>Armado de Barbechos</div></li>
      			<li><div class='fc-event'>Injertos</div></li>

      		</ul>

      	</li>
      	<li id="2"><a href="">Tareas de movimiento de tierra</a>
      		<ul>
      			<li><div class='fc-event'>Desorillar</div>
      			<div class='fc-event'>Rastrear</div>
      			<div class='fc-event'>Tapar</div>
      			<div class='fc-event'>Replantar y hacer Mugrones</div></li>
      		</ul>

      	</li>
      	<li id="3"><a href="">Tareas de Sanidad</a>
      		<ul>
      			<li><div class='fc-event'>Curación Azufre (prevencion Quintal)</div>
      			<div class='fc-event'>Herbicidas</div>
      			<div class='fc-event'>Curación a base de sulfato (prevención de Poronospera)</div>
      			<div class='fc-event'>Curación preventiva para Polilla de la Vid</div></li>
      		</ul>
      	</li>
      	<li id="4"><a href="">Tareas de Riego</a>
      		<ul><li>
      			<div class='fc-event'>Riego</div>
      			<div class='fc-event'>Acentar</div></li>
      		</ul>
      	</li>
      	<li id="5"><a href="">Vendimia</a>
      		<ul><li>
      			<div class='fc-event'>Cosecha</div></li>
      		</ul>
      	</li>
      	<li id="6"><a href="">Mantenimiento</a>
      		<ul><li>
      			<div class='fc-event'>Canales y Acequias</div>
      			<div class='fc-event'>Cambio maderas</div>
      			<div class='fc-event'>Tensado y Colocación de alámbres</div></li>
      		</ul>
      	</li>
      	<li id="7"><a href="">Abonos</a>
      		<ul><li>
      			<div class='fc-event'>Abonos Naturales de alta duración</div>
      			<div class='fc-event'>Abonos Naturales de baja duración</div>
      			<div class='fc-event'>Fertilizantes líquidos</div>
      			<div class='fc-event'>Fertilizantes sólidos</div></li>
      		</ul>
      	</li>
  
      </ul>
    
    </div>

    <div id='calendar'></div>
   <!--<button type="button" id="btnActualizar" class="btn btn-success">Actualizar DB</button>
    <button type="button" id="btnCargar" class="btn btn-primary">Cargar datos</button>-->
  </div>
</body>
</html>
