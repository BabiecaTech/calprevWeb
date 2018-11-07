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