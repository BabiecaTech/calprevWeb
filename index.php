<?php
  include_once("conexion.php");
  $result_events = "SELECT id, title, color, start, end FROM events";
  $resultado = mysqli_query($conn, $result_events);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='css/calendar.min.css' rel='stylesheet' />
<link href='css/calendar.print.min.css' rel='stylesheet' media='print' />
<script src='js/moment.min.js'></script>
<script src='js/jquery.min.js'></script>
<script src='js/fullcalendar.min.js'></script>
<script src='idioma/es-us.js'></script>
<script src='js/jquery-ui.min.js'></script>
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
      
      defaultDate: new Date(),
      navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar
      events: [
              
      ]
    });

  });

</script>
<style>

  body {
    margin-top: 40px;
    text-align: center;
    font-size: 14px;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
  }

  #wrap {
    width: 1100px;
    margin: 0 auto;
  }

  #external-events {
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
    width: 900px;
  }

</style>
</head>
<body>

  <div id='wrap'>

  
    <div id='external-events'>
      <div>
      <h4>Tareas</h4>
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
