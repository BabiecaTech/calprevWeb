<?php
  include_once("conexion.php");
  $result_events = "SELECT id, title, color, start, end FROM events";
  $resultado = mysqli_query($conn, $result_events);
?>
<!DOCTYPE html>
<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }elseif ($_SESSION['rol']==1) {
    header("Location:admin.php");
  }
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
        right: 'month,agendaWeek,agendaDay'
      },

      dayClick:function(date,jsEvent,view){
        //alert(date.format());
        $('#txtFechaA').val(date.format());
        $('#txtDescA').val("");
        $('#txtTituloA').val("");
        $('#txtHoraA').val("06:00");
        $('#agregarModal').modal();
      },
      
      defaultDate: new Date(),
      navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar
    
      events: 'http://localhost:8080/Ingenieria/caleprevWeb/eventos.php',

      eventClick:function(calEvent,jsEvent,view){
        $('#tituloEvento').html(calEvent.title);
        $('#txtDesc').val(calEvent.descripcion);
        $('#txtId').val(calEvent.id);
        $('#txtTitulo').val(calEvent.title);

        var fechaHora = calEvent.start._i.split(" ");
        $('#txtFecha').val(fechaHora[0]);
        $('#txtHora').val(fechaHora[1]);

        $('#vistaModal').modal();
      },
      editable:true,
      eventDrop:function(calEvent){
        $('#txtId').val(calEvent.id);
        $('#txtTitulo').val(calEvent.title);
        $('#txtDesc').val(calEvent.descripcion);

        var fechaHora = calEvent.start.format().split("T");
        $('#txtFecha').val(fechaHora[0]);
        $('#txtHora').val(fechaHora[1]);

        recolectarDatosM();
        enviarDatos('modificar',tarea,true);

      }
    });

  });

</script>

<style>
 
  #wrap {
    width: 1100px;
    margin: 0,auto;
  }

  #external-events {
    margin-top: 10px;
    margin-bottom: 10px;
    float: left;
    width: 150px;
    padding: 0 10px;
    border: 1px solid #ccc;
    background: #eee;
    text-align: left;
  }

  #external-events h4 {
    font-size: 16px;
    margin-top: 0;
    padding-top: 1em;
  }

  #external-events .fc-event {
    margin: 10px 0;
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
    margin-top: 10px;
    width: 900px;
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
        
        <input type="hidden" id="txtId" name="txtId" /><br/>
        Fecha: <input type="text" id="txtFecha" name="txtFecha" /><br/>
        Tarea: <select id = "txtTitulo" name ="txtTitulo">
              <option value="seleccione">Seleccione</option>
              <option value="Arar">Arar</option>
              <option value="Poda">Poda</option>
              <option value="Curacion">Curacion</option>
              <option value="Riego">Riego</option>              
          </select><!--<input type="text" id="txtTitulo" name="txtTitulo" />--><br/>
        Hora: <input type="text" id="txtHora" name="txtHora" value="06:00" /><br/>
        Descripcion: <textarea id="txtDesc" rows="3"> </textarea><br/>
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
        <input type="hidden" id="txtIdA" name="txtId" /><br/>
        Fecha: <input type="text" id="txtFechaA" name="txtFecha" /><br/>
        Tarea: <select id = "txtTituloA" name ="txtTituloA">
              <option value="Arar">Arar</option>
              <option value="Poda">Poda</option>
              <option value="Curacion">Curacion</option>
              <option value="Riego">Riego</option>
            </select><br/>
        Hora: <input type="text" id="txtHoraA" name="txtHora" value="06:00" /><br/>
        Descripcion: <textarea id="txtDescA" rows="3"> </textarea><br/>
        <input type="hidden" id="idUser" name="idUser" value=<?php echo $_SESSION['id'] ?> />
      </div>
      <div class="modal-footer">
        <button type="button" id="btnAgregar" class="btn btn-success">Agregar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  var nuevo, tarea;
  $('#btnAgregar').click(function(){
    recolectarDatos();
    /*$('#calendar').fullCalendar('renderEvent',nuevo);
    $('#agregarModal').modal('toggle');*/
    enviarDatos('agregar',nuevo);

  });

  $('#btnEliminar').click(function(){

    recolectarDatosM();
    enviarDatos('eliminar',tarea);

  });

  $('#btnModificar').click(function(){

    recolectarDatosM();
    enviarDatos('modificar',tarea);

  });

  function recolectarDatos(){
    nuevo = {
      id:$('#txtIdA').val(),
      title:$('#txtTituloA').val(),
      descripcion:$('#txtDescA').val(),
      start:$('#txtFechaA').val()+" "+$('#txtHoraA').val(),
      end:$('#txtFechaA').val()+" "+$('#txtHoraA').val(),
      id_user:<?php echo $_SESSION['id'] ?>
  };
}

function recolectarDatosM(){
    tarea = {
      id:$('#txtId').val(),
      title:$('#txtTitulo').val(),
      descripcion:$('#txtDesc').val(),
      start:$('#txtFecha').val()+" "+$('#txtHora').val(),
      end:$('#txtFecha').val()+" "+$('#txtHora').val(),
      id_user:<?php echo $_SESSION['id'] ?>
  };
}

function enviarDatos(accion,objEvento,modal){
  $.ajax({
      type:'POST',
      url:'eventos.php?accion='+accion,
      data:objEvento,
      success:function(msg){
        if(msg){
          $('#calendar').fullCalendar('refetchEvents');
          if(accion=='agregar'){
            $('#agregarModal').modal('toggle');
          }else{
            if(!modal){
              $('#vistaModal').modal('toggle');
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
      <div>
      <h4>TAREAS</h4>
      <div class='fc-event'>Arar</div>
      <div class='fc-event'>Desorrillar</div>
      <div class='fc-event'>Curaciones</div>
      <div class='fc-event'>Poda</div>
      <div class='fc-event'>Desbrote</div>
      <div class='fc-event'>Riego</div>
      </div>
    </div>
    <div id='calendar'></div>
    <div style='clear:both'></div>
  </div>
</body>
</html>
