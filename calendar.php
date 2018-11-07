<!DOCTYPE html>
<?php
  function cargar_tareas (){
  	require("conexion.php");
  	$sql = "SELECT * FROM tareas ORDER BY nombre ASC";
  	$respusta = mysqli_query($conn, $sql);
  	while($fila = mysqli_fetch_assoc($respusta)){?>
        <option value="<?php echo utf8_encode($fila['nombre']) ?>"><?php echo utf8_encode($fila['nombre']) ?></option>
        <?php
    }

  }
  function cargar_usuarios (){
  	require("conexion.php");
  	$sql = "SELECT * FROM login ORDER BY user ASC";
  	$respusta = mysqli_query($conn, $sql);
  	while($fila = mysqli_fetch_assoc($respusta)){?>
        <option value="<?php echo utf8_encode($fila['user']) ?>"><?php echo utf8_encode($fila['user']) ?></option>
        <?php
    }
  }
?>

<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }
  ?>
<html>
<head>
<meta charset='utf-8' />
<!--<link href='css/estilo.css' rel='stylesheet' />-->
<link href='css/calendar.min.css' rel='stylesheet' />
<link href='css/calendar.print.min.css' rel='stylesheet' media='print' />
<!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
<!--<link rel="stylesheet" href="css/bootstrap.min1.css">-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

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
  
    border: 2px solid #ccc;
    background: #eee;
    text-align: center;
  }

  #external-events h4 {
    font-size: 16px;
    margin-top: 0;
    padding-top: 1em;
    color: #404040;
  }

  #external-events .fc-event {
    width: auto;
    margin: 10px 0;
    padding: 5px;
    cursor: pointer;
  }

  .fc-event {
    border: 1px solid #404040;
  }


  #tarea1{
    background: #008000;
  } 

  #tarea2{
    background: #b37700;
  } 

  #tarea3{
    background: #ff6600;
  } 

  #tarea4{
    background: #6600cc;
  } 

  #tarea5{
    background: #ff0000;
  } 

  #tarea6{
    background: #0066ff;
  } 

  #favorable{
    background: #00ff00;
    opacity: .5;
  }

  #noFavorable{
    background: #ff0000;
    opacity: .5;
  } 

  #external-events p {
    margin: 1.5em 0;
    font-size: 14px;
    color: #262626;
  }

  #external-events p input {
    margin: 0;
    vertical-align: middle;
  }

  #calendar {
    width: 100%;
  }

  .menu2 {
  	width: auto;
  	display: inline-block;
  }
  ul {
  	list-style: none;
  }
  .menu2 li a {
  	display: block;
  	text-decoration: none;
  	color: #fff;
  }

  .menu2 li a:hover {
  	background: #404040;
  	
  }
  .menu2 ul {
  	display: none;
  }

  .menu2 .seleccion .fc-event > a{
  	background: #404040;
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
              <?php cargar_tareas();?>             
          </select><!--<input type="text" id="txtTitulo" name="txtTitulo" />-->
          <table class="table">
            <tr>  
              <td> Hora Inicio: <input type="time" id="txtHora" name="txtHora" class="form-control" min="5:00" max="20:00" required/></td>
              <td> Hora Finalizacion: <input type="time" id="txtHoraFin" name="txtHoraFin" class="form-control" min="6:00" max="21:00" required/></td>
            </tr>
          </table>
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
        <table class="table">
          <tr>  
          <td> Hora Inicio: <input type="time" id="txtHoraA" name="txtHoraA" class="form-control" min="5:00" max="20:00" required/></td>
          <td> Hora Finalizacion: <input type="time" id="txtHoraAfin" name="txtHoraAfin" class="form-control"min="6:00" max="21:00" required/></td>
          </tr>
        </table>
        Descripcion: <textarea id="txtDescA" rows="3" placeholder="Escriba una descripcion de la tarea" class="form-control"> </textarea>
        Costo Auxiliar $: <input type="number" id="numCostoA" name="numCostoA" value="0" class="form-control">
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

<body>
  <?php
include("plantilla1.php");
?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#">
          <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Calendario</li>
      </ol>
    </div><!--/.row-->
    <div class="row">

      <div class="col-lg-9">
        <div class="panel panel-default">
          <div class="panel-body">
            <div id='calendar'></div>
          </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="panel panel-default">
          <div class="panel-body">
          <div id='wrap'>
            <div id='external-events'>
              <h4>Lista de Tareas</h4>
                <ul class="menu2">
        
                  <li id="1"><div class='fc-event' id="tarea1"><a href="#">Trabajos en Plantas</a></div></li>
                  <li id="2"><div class='fc-event' id="tarea2"><a href="#">Movimiento de Tierra</a></div></li>
                  <li id="3"><div class='fc-event' id="tarea3"><a href="#">Prevencion (Sanidad)</a></div></li>
                  <li id="4"><div class='fc-event' id="tarea4"><a href="#">Apliques y abonos</a></div></li>
                  <li id="5"><div class='fc-event' id="tarea5"><a href="#">Vendimia</a></div></li>
                  <li id="6"><div class='fc-event' id="tarea6"><a href="#">Mantenimiento</a></div></li>
                </ul>
            </div>
          </div>
        </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="panel panel-default">
          <div class="panel-body">
          <div id='wrap'>
            <div id='external-events'>
              <h4>Referencias</h4>
                <ul class="menu2">
                  <li id="1"><div class='fc-event' id="favorable"></div></li><h5>Día Recomendable</h5>
                  <li id="2"><div class='fc-event' id="noFavorable"></div></li><h5>Día No Recomendable</h5>
                </ul>
            </div>
          </div>
        </div>
        </div>
      </div>
      
    </div><!--/.row-->

  </div>

  <script src='js/moment.min.js'></script>
<script src='js/jquery.min.js'></script>
<script src='js/fullcalendar.min.js'></script>
<script src='idioma/es-us.js'></script>
<script src='js/jquery-ui.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<script>

   $(document).ready(function() {

    var idfinca = $("#id_finca").val();

    $('.nav.menu li').eq(1).addClass('active');

    $('.menu2 li:has(a)').click(function(e){
      e.preventDefault();

      if ($(this).hasClass('selecion')){
        $(this).removeClass('seleccion');
        //$(this).children('ul').slideUp();
        $('#calendar').fullCalendar('removeEventSource', $('#calendar').fullCalendar('getEventSources')[1]);
      }else{
        $('.menu2 li ul').slideUp();
        $('.menu2 li').removeClass('seleccion');
        if($('#calendar').fullCalendar ('getEventSources').length >1){
          $('#calendar').fullCalendar('removeEventSource', $('#calendar').fullCalendar('getEventSources')[1]);
        }
        $(this).addClass('seleccion');
        
        //$(this).children('ul').slideDown();
        if ($(this).attr('id') ==1){
          actualizarRegla('plantas');
          }
        if ($(this).attr('id') ==2){
          //t=2;
          actualizarRegla('movimiento');
          }
        if ($(this).attr('id') ==3){
          actualizarRegla('prevencion');
        }
        if ($(this).attr('id') ==4){
          actualizarRegla('apliques');
        }
        if ($(this).attr('id') ==5){
          actualizarRegla('vendimia');
        }
        if ($(this).attr('id') ==6){
          actualizarRegla('mantenimiento');
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
        //alert($.trim($(this).text()));
        if (fechaHora >= moment[0]){
       var t = {title:$.trim($(this).text()),
                descripcion:'',
                costo:0,
                start:fechaHora+" "+"05:00:00",
                end:fechaHora+" "+"06:00:00",
                editable:1,
                asignar:<?php echo $_SESSION['id']; ?>,
                id_user:<?php echo $_SESSION['id']; ?>
              }
      enviarDatos('agregard',t,true);
  }else{
          alert("Fecha no permitida para asignar la tarea");
          //$('#calendar').fullCalendar('refetchEvents');
          location.reload();
        }
      },
    
      eventSources:[{
        url:'http://localhost:8080/Ingenieria/caleprevWeb/eventos.php?accion=leer&id='+idfinca,
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
      id_user:<?php echo $_SESSION['id']?>,
      id_finca:<?php echo $_SESSION['id_finca']?>
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
      id_user:<?php echo $_SESSION['id'] ?>,
      id_finca:<?php echo $_SESSION['id_finca']?>
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

</body>
</html>
