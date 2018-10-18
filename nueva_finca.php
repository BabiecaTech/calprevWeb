<!DOCTYPE html>
<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }

  function cargar_vinedos(){
    require("conexion.php");
    //echo ($id);
    $sql="SELECT * FROM vinedos ORDER by nombre";
    $resultado=mysqli_query($conn,$sql);
    while ($fila=mysqli_fetch_assoc($resultado)) {
    ?>
      <option value="<?php echo $fila['id']?>"><?php echo $fila['nombre']?></option>
      <?php
    }
  }
?>
<html lang='es'>
<head>
	<neta charset="UTF-8">
	<title></title>
  <link href="css/styles.css" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
  <script src="jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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

</head>

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
        Nombre: <input type="text" id="txtName" name="txtName" class="form-control" required/>
        <table id="tabla" class="table">
          <thead>
            <tr>
              <!--<td>Numero</td>-->
              <td>Tipo Viñedo</td>
              <td>Hectareas</td>
              <td><button type="button" class="btn btn-success" id="btnAdd"><span class='glyphicon glyphicon-plus'></span></button></td>
              <td><button type='button' class='btn btn-danger' id="btnDel"><span class='glyphicon glyphicon-trash'></span></button></td>
            </tr>
          </thead>
          
        </table>
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
  include ("plantilla.php"); 
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
        <h1 class="page-header">Ingresar Datos Nueva Finca</h1>
      </div>
    </div><!--/.row-->

    <div class="row">

      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
	<main>

	<form class="form-inline" method="post">
		<div class="form-group mx-auto my-5">
			<label class="sr-only" for="lacation"></label>
			<input type="text" class="form-control" id="location" name="location" placeholder="ingrese ubicacion">
			<button class="btn btn-primary" type="button" onclick="buscar()">Buscar</button>
			<button style="margin-left:10px " class="btn btn-secondary" type="button" onclick="actual()">Localizacion Actual</button>
		</div>
		
	</form>

	<?php 
		
		/*if (!empty($_POST['location'])) {
			require 'pronostico.php';
			echo '<h3 class="display-4">'.$localidad.'</h3>';
			echo $latitud;
			echo $longitud;?>
			<div class="card p=4" style="margin: 0 auto; max-width: 320px;">
			<h2>Temperatura Actual</h2>
			<h3 class="display-2"><?php echo $temperature_current; ?>&deg; </h3>
			<p class="lead">Humedad <?php echo $humidity_current; ?>%</p>
			<p class="lead">Precipitacion <?php echo $precip_current; ?>%</p>
			<p class="lead">Vientos <?php echo $windSpeed_current; ?>m/s</p>
			<p class="lead">Presion <?php echo $pressure_current; ?>HPA</p>
		</div>
		<?php
		}
*/
	?>

</main>
	

   </div>
        </div>
      </div>
      
    </div><!--/.row-->
    </div>
  <div id="map"></div>
	<input type="hidden" id="longitud" name="longitud" />
	<input type="hidden" id="latitud" name="latitud" />
  
</body>
    <script>
      var map;
      var marker;
      var markers = [];
      function initMap() {

      	map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -35.9610609, lng: -69.3280204},
          zoom: 5
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

      	function fn_code(datos){
      		var coordenadas = datos[0].geometry.location;
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
    $('#bt_delall').click(function(){
      eliminarTodasFilas();
    });
    

  });
  var cont=0;
  var id_fila_selected=[];
  function agregar(){
    cont++;
    
    var fila='<tr class="selected" id="fila'+cont+'" onclick="seleccionar(this.id);"><td><select id = "select'+cont+'" name ="select'+cont+'"class="form-control" style="height:45px" required><option value="1">bonarda</option><option value="2">cabernet</option><option value="3">chardonnay</option><option value="4">malbec</option><option value="5">soivignon</option></select></td><td><input type="number" id="hectareas" name="hectareas'+cont+'" value=0 class="form-control" required></td></tr>';
    $('#tabla').append(fila);
    reordenar();
  }

  function seleccionar(id_fila){
    if($('#'+id_fila).hasClass('seleccionada')){
      $('#'+id_fila).removeClass('seleccionada');
    }
    else{
      $('#'+id_fila).addClass('seleccionada');
    }
    //2702id_fila_selected=id_fila;
    id_fila_selected.push(id_fila);
  }

  function eliminar(id_fila){
    /*$('#'+id_fila).remove();
    reordenar();*/
    for(var i=0; i<id_fila.length; i++){
      $('#'+id_fila[i]).remove();
    }
    reordenar();
  }

  function reordenar(){
    var num=1;
    $('#tabla tbody tr').each(function(){
      $(this).find('td').eq(0).text(num);
      num++;
    });
  }
  function eliminarTodasFilas(){
$('#tabla tbody tr').each(function(){
      $(this).remove();
    });

  }


</script>


	<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApOJsPlmxLMPX7dkF9Se0fS_72CFgLwP8&callback=initMap">
    </script>


</html>