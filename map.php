<!DOCTYPE html>
<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }
?>
<html>
<link href="css/styles.css" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
  <link href="css/styles.css" rel="stylesheet">
  <!--<link rel="stylesheet" type="text/css" href="css/estilo_tabla.css">-->

	<style>
  #map {
    height: 60%;
    width: 60%;
    margin: auto;
  }
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }

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
<div class="modal fade" id="vistaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tituloEvento"></h5>
        <button type="button" class="close" onclick="cerrar();" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
        <input type="hidden" id="txtId" name="txtId" />
        Nombre: <input type="text" id="txtName" name="txtName" class="form-control" required/>
        <div class="table-responsive">
        <table id="tabla" class="table">
          <thead>
            <tr>
              <td>Numero</td>
              <td>Tipo Viñedo</td>
              <td>Hectareas</td>
              <button type="button" class="btn btn-success" id="btnAdd"><span class='glyphicon glyphicon-plus'></span></button>
              <button type='button' class='btn btn-danger' id="btnDel"><span class='glyphicon glyphicon-trash'></span></button>
            </tr>
          </thead>
          <tbody></tbody>
          
        </table>
      </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" id="btnGuardar" class="btn btn-success">Guardar</button>
        
        <button type="button" onclick="cerrar();" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>

</head>
<body>

<?php
  include ("plantilla1.php"); 
  ?>	
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#">
          <em class="fa fa-home"></em>
        </a></li>
        <li class="active">Nueva Finca</li>
      </ol>
    </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <form class="form-inline" method="post">
            
              <label class="sr-only" for="lacation"></label>
              <input type="text" class="form-control" id="location" name="location" placeholder="ingrese ubicacion">
              <button class="btn btn-primary" type="button" onclick="buscar();">Buscar</button>
              <button style="margin:10px " class="btn btn-secondary" type="button" onclick="actual();">Localizacion Actual</button>
          
            <input type="hidden" id="latitud" name="latitud" />
            <input type="hidden" id="longitud" name="longitud" />
            <input type="hidden" id="cantVino" name="cantVino" value=0 />
            </form>          

            <div class="mapa" style="margin-top: 10px">

            <div id="map" style="width:100%;height:400px;"></div>

            </div>
          </div>

        </div>
    </div>
  </div>

</div>

</body>
<script src="jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
  var map;
  var marker;
  var markers = [];
function myMap() {
var mapProp= {
    center:new google.maps.LatLng(-35.9610609,-69.3280204),
    zoom:5,
};
  map=new google.maps.Map(document.getElementById("map"),mapProp);


  var geocoder = new google.maps.Geocoder();
  document.getElementById('submit').addEventListener('click', function() {
    geocodeAddress(geocoder, map);
  });

}

function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('location').value;
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            });
            document.getElementById("latitud").value = results[0].geometry.location.lat();
            document.getElementById("longitud").value = results[0].geometry.location.lng();
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
 function actual(){
         navigator.geolocation.getCurrentPosition(
          function (position){
            coords =  {
              lng: position.coords.longitude,
              lat: position.coords.latitude
            };
            document.getElementById("latitud").value = coords.lat;
            document.getElementById("longitud").value = coords.lng;

            setMapa(coords);  //pasamos las coordenadas al metodo para crear el mapa      
           
          },function(error){console.log(error);});
    
}

function setMapa (coords){   
      map = new google.maps.Map(document.getElementById('map'),
      {
        zoom: 12,
        center:new google.maps.LatLng(coords.lat,coords.lng)

      });

      marker = new google.maps.Marker({
        map: map,
        draggable: true,
        //title: "mi ubicacion",
        animation: google.maps.Animation.DROP,
        position: new google.maps.LatLng(coords.lat,coords.lng)

      });
      //agregamos un evento al marcador junto con la funcion callback al igual que el evento dragend que indica 
      //cuando el usuario a soltado el marcador
      marker.addListener('click', toggleBounce);
      /*var objHTML = {
        content: "<div id='form'><table><tr><td>Nombre:</td> <td><input type='text' id='name'/></td> </tr><tr><td>Hectareas:</td> <td><input type='text' id='address'/> </td> </tr><tr><td></td><td><input type='button' value='Save' onclick='saveData()'/></td></tr></table></div>"
      }
      var gIW = new google.maps.InfoWindow(objHTML);*/

      google.maps.event.addListener(marker,'click',function(){
          gIW.open(map,marker);
      });

      marker.addListener( 'dragend', function (event)
      {
        //escribimos las coordenadas de la posicion actual del marcador dentro del input #coords
        document.getElementById("latitud").value = this.getPosition().lat();
        document.getElementById("longitud").value = this.getPosition().lng();

      });
    
}

function deleteMarkers() {
        clearMarkers();
        markers = [];
      }

function clearMarkers() {
        setMapOnAll(null);
      }

function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

function buscar(){

  if (document.getElementById("location").value != "") {
    var valor = document.getElementById("location").value;
    var gCoder = new google.maps.Geocoder();
        var objInfo = {
        address: valor
        }
        gCoder.geocode(objInfo,fn_code);
        function fn_code(datos,status){
            if (status === 'OK') {
          var coordenadas = datos[0].geometry.location;
          document.getElementById("latitud").value = coordenadas.lat();
          document.getElementById("longitud").value = coordenadas.lng();

          map = new google.maps.Map(document.getElementById('map'),
          {
          zoom: 12,
          center: coordenadas
          });
          var config = {
          map: map,
          position: coordenadas,
          //title: 'rio cuarto',
          draggable: true,
          animation: google.maps.Animation.DROP
          }
        deleteMarkers();
        marker = new google.maps.Marker(config);
        markers.push(marker);
        marker.addListener('click', toggleBounce);
        /*var objHTML = {
        content: "<div id='form'><table><tr><td>Nombre:</td> <td><input type='text' id='name'/></td> </tr><tr><td>Hectareas:</td> <td><input type='text' id='address'/> </td> </tr><tr><td></td><td><input type='button' value='Save' onclick='saveData()'/></td></tr></table></div>"
      }
      var gIW = new google.maps.InfoWindow(objHTML);*/

      google.maps.event.addListener(marker,'click',function(){
          gIW.open(map,marker);
      });

      marker.addListener( 'dragend', function (event)
      {
        //escribimos las coordenadas de la posicion actual del marcador dentro del input #coords
        document.getElementById("latitud").value = this.getPosition().lat();
        document.getElementById("longitud").value = this.getPosition().lng();

      });
    } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
      }
  }
}

function toggleBounce() {
  if (marker.getAnimation() !== null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
     $('#vistaModal').modal();

  }
}
</script>

<script>
  $(document).ready(function(){
    
  
    $('#btnAdd').click(function(){
      agregar();
    });
    $('#btnDel').click(function(){
      eliminar(id_fila_selected);
    });
    $('#btnGuardar').click(function(){
       if (confirm("¿Desea Guardar la Finca..?")==true){
      recolectarDatos();
      enviar('guardar');
      }
     
    });

  });
  var cont=0;
  var id_fila_selected=[];
  function agregar(){
    cont++;
    var fila='<tr class="selected" id="fila'+cont+'" onclick="seleccionar(this.id);"><td>'+cont+'</td><td><select id = "select'+cont+'" name ="select'+cont+'"class="form-control" style="height:45px" required><option value="1">Malbec</option><option value="2">Cabernet</option><option value="3">Bonarda</option><option value="4">Syrah</option><option value="5">Tempranillo</option><option value="7">Chardonnay</option><option value="8">Sauvignon</option></select></td><td><input type="number" id="hecta'+cont+'" name="hecta'+cont+'" value=0 class="form-control" required></td></tr>';
    $('#tabla').append(fila);
    
    reordenar();

  }
  var dato;
 function recolectarDatos(){
    dato= {
      nombre:$('#txtName').val(),
      latitud:$('#latitud').val(),
      longitud:$('#longitud').val(),
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
          alert("Finca registrada exitosamente");
          window.location.replace("fincas.php");
        },
        error:function(msg){
        alert("Error conexion base de datos..."); 
      }
  });
  };

  function cerrar(){
    $('#txtName').val("");
    $('#cantVino').val(0);
    eliminarTodasFilas();
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
    $('#tabla tbody tr').each(function(){
      num++;
      $(this).find('td').eq(0).text(num);
      $(this).eq(0).attr('id','fila'+num);
      $('#fila'+num).find('select').attr('name','select'+num);
      $('#fila'+num).find('select').attr('id','select'+num);
      $('#fila'+num).find('input').attr('name','hecta'+num);
      $('#fila'+num).find('input').attr('id','hecta'+num);

      
    });
    $('#cantVino').val(num--);
  }
  function eliminarTodasFilas(){
$('#tabla tbody tr').each(function(){
      $(this).remove();
    });

  }


</script>

<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAW8mutKggZ1eEprGijlk96M2Fw761agpM&callback=myMap">
    </script>
</html>