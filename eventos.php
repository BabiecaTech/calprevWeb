<?php
header('Content-Type: application/json');
$con= new PDO("mysql:dbname=calendario;host=localhost","root","");

$accion = (isset($_GET['accion']))?$_GET['accion']:'leer';
$tempera = (isset($_GET['temp']))?$_GET['temp']:0;
switch ($accion) {
	case 'agregar':
		# code...
			$color = obtenerColor($con, $_POST['title']);
			$sql=$con->prepare("INSERT INTO events(title,descripcion,costo,color,start,end,asignar,id_user) VALUES (:title,:descripcion,:costo,:color,:start,:end,:asignar,:id_user)");

			$respuesta=$sql->execute(array(
				'title' =>$_POST['title'],
				'descripcion' =>$_POST['descripcion'],
				'costo' =>$_POST['costo'],
				'color' =>$color,
				'start' =>$_POST['start'],
				'end' =>$_POST['end'],
				'asignar' =>$_POST['asignar'],
				'id_user' =>$_POST['id_user']
			 ));
			echo json_encode($respuesta);
		break;

	case 'agregard':
		# code...
			$color = obtenerColor($con, $_POST['title']);
			$sql=$con->prepare("INSERT INTO events(title,descripcion,costo,color,start,end,asignar,id_user) VALUES (:title,:descripcion,:costo,:color,:start,:end,:asignar,:id_user)");

			$respuesta=$sql->execute(array(
				'title' =>$_POST['title'],
				'descripcion' =>$_POST['descripcion'],
				'costo' =>$_POST['costo'],
				'color' =>$color,
				'start' =>$_POST['start'],
				'end' =>$_POST['end'],
				'asignar'=>$_POST['asignar'],
				'id_user' =>$_POST['id_user']
			 ));
			echo json_encode($respuesta);
		break;
	
	case 'eliminar':
		# code...
			$respuesta=false;
			if(isset($_POST['id'])){
				$sql=$con->prepare("DELETE FROM events WHERE id =:ID");
				$respuesta = $sql->execute(array("ID"=>$_POST['id']));
			}
			echo json_encode($respuesta);
		break;
	case 'modificar':
		# code...
			$color = obtenerColor($con, $_POST['title']);
			$sql=$con->prepare("UPDATE events SET 
				title=:title, 
				descripcion=:descripcion,
				costo=:costo,
				color=:color,
				start=:start,
				end=:end,
				asignar=:asignar,
				id_user=:id_user
				WHERE id=:ID");
			$respuesta=$sql->execute(array(
				'ID' =>$_POST['id'],
				'title' =>$_POST['title'],
				'descripcion' =>$_POST['descripcion'],
				'costo' =>$_POST['costo'],
				'color' =>$color,
				'start' =>$_POST['start'],
				'end' =>$_POST['end'],
				'asignar' =>$_POST['asignar'],
				'id_user' =>$_POST['id_user']
			 ));
			echo json_encode($respuesta);
		break;

		case 'actualizar':
			# code...
			$sql = $con-> prepare("SELECT * FROM fincas WHERE id =1");
			$sql->execute();
			$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
			//echo json_encode($resultado);
			$coordenadas = $resultado[0]['latitud'].",".$resultado[0]['longitud'];
			actualizarDb($coordenadas,$con);

			break;

		case 'notificar':
			# code...
			$hoy = date('Y-m-d');
			actualizarNotificaciones($hoy,$con);

			break;

		case 'cargar':
			# code..
			$hoy = date('Y-m-d');
			$mes = date('m' , strtotime($hoy));
			$arreglo =[];
			//cargarTemperatura($mes,$con);
			//echo $mes;
			if ($mes < 5 || $mes == 8){
				$ano = date('Y' , strtotime($hoy));
				cargarTemperatura($ano,$mes,$con,$arreglo);
				//echo json_encode($resultado);
			}
			echo json_encode($arreglo);
			break;

		case 'prevencion':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
		cargarHumedad($con,$arreglo);
		cargarViento($con,$arreglo);
		lunaCreciente($con,$arreglo,3);
		echo json_encode($arreglo);
		break;

		case 'apliques':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
		cargarViento($con,$arreglo);
		lunaDecreciente($con,$arreglo,4);
		echo json_encode($arreglo);
		break;

		case 'plantas':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
		lunaDecreciente($con,$arreglo,5);
		echo json_encode($arreglo);
		break;

		case 'vendimia':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
		lunaCreciente($con,$arreglo,2);
		echo json_encode($arreglo);
		break;

		case 'mantenimiento':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
		echo json_encode($arreglo);
		break;

		case 'movimiento':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
			$hoy = date('Y-m-d');
			$mes = date('m' , strtotime($hoy));
			if ($mes < 5 || $mes > 8){
				//$ano = date('Y' , strtotime($hoy));
				cargarTemperatura($hoy,$con, $arreglo);
			}
		lunaCreciente($con,$arreglo,1);
		echo json_encode($arreglo);
		//echo $hoy;
		break;

		/*case 'creciente':
		$arreglo=[];
		lunaCreciente($con,$arreglo,2);
		echo json_encode($arreglo);
		break;*/

		case 'leer':
		# code..
			$sql=$con->prepare("SELECT * FROM events");
			$sql->execute();

			$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode($resultado);

		break;
}

function lunaCreciente($con, &$arreglo, $select){
	if ($select == 3){
		$condicion = "SELECT DISTINCT fecha FROM prohoras WHERE humedad < 30 OR humedad > 60 OR probLluvia >= 70 OR viento >10" ;
		}
	if ($select == 2){
		$condicion = "SELECT DISTINCT fecha FROM prohoras WHERE  probLluvia >= 70" ;
		}
	if ($select == 1){
		$condicion = "SELECT DISTINCT fecha FROM prohoras WHERE probLluvia >= 70 OR temperatura < 4" ;
		}
	$sql=$con->prepare("SELECT DISTINCT fecha FROM vista_cond WHERE fecha NOT IN (".$condicion.") AND faseLunar > 0.02 AND faseLunar <0.48");
	$sql->execute();
	$respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	$i= 0;
	while ( $i < $cant_fila) {
		# code...

			$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => true, 'rendering' => 'background', 'color' => '#00ff00'];

			$arreglo [] = $dato;
			$i++;
		}

	
}

function lunaDecreciente($con, &$arreglo, $select){
	if ($select == 4){
		$condicion = "SELECT DISTINCT fecha FROM prohoras WHERE probLluvia >= 70 OR viento >10" ;
		}
	if ($select == 5){
		$condicion = "SELECT DISTINCT fecha FROM prohoras WHERE probLluvia >= 70" ;
		}
	$sql=$con->prepare("SELECT DISTINCT fecha FROM vista_cond WHERE fecha NOT IN (".$condicion.") AND faseLunar > 0.52 AND faseLunar <0.99");
	$sql->execute();
	$respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	$i = 0;
	while ( $i < $cant_fila) {
		# code...
			$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => true, 'rendering' => 'background', 'color' => '#00ff00'];
			$arreglo [] = $dato;
			$i++;
		}
}

function obtenerColor($con, $id){
	//echo ($id);
	$sql=$con->prepare("SELECT color FROM tareas WHERE nombre = '".$id."'");
	$sql->execute();
	$respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
	return $respuesta[0]['color'];
}
function cargarLluvia($con, &$arreglo){
	$sql=$con->prepare("SELECT DISTINCT fecha FROM prohoras WHERE probLluvia >= 70");
	$sql->execute();
	$respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	//echo $respuesta[0]['viento'];
	$i = 0;
	while ( $i < $cant_fila) {
		# code...
		//if($respuesta[$i]['probLluvia'] > 70){
			$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => false, 'rendering' => 'background', 'color' => '#ff0000'];
			$arreglo [] = $dato;
		//	$i = $i + (24-$respuesta[$i]['hora']);
		//}else {
			$i++;
		}
		
	//echo json_encode($arreglo);
}

function cargarTemperatura($hoy,$con,&$arreglo){
	
		$sql1=$con->prepare("SELECT DISTINCT fecha FROM prohoras where fecha >= ".$hoy." AND temperatura < 4");
		$sql1->execute();
		$respuesta = $sql1->fetchAll(PDO::FETCH_ASSOC);
		$cant_fila = count($respuesta);
		//echo json_encode($respuesta);
		$i = 0;
		while ( $i < $cant_fila) {
			# code...
			$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => false, 'rendering' => 'background', 'color' => '#ff0000']; 
					//color celeste regla tmperatura menor a 3 grados
			$arreglo [] = $dato;
			$i++;
		}
		//echo json_encode($arreglo);
	}

function cargarHumedad ($con, &$arreglo){
	$sql=$con->prepare("SELECT DISTINCT fecha FROM prohoras WHERE humedad < 30 OR  humedad > 60");
	$sql->execute();
	$respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	//$arreglo =[];
	$i=0;
	while ($i < $cant_fila) {
		$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => false, 'rendering' => 'background', 'color' => '#ff0000'];
		$arreglo [] = $dato;
		$i++;
	}
	/*$sql1=$con->prepare("SELECT DISTINCT fecha FROM prohoras WHERE humedad > 60");
	$sql1->execute();
	$respuesta = $sql1->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	$i =0;
	while ($i < $cant_fila) {
		$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => false, 'rendering' => 'background', 'color' => '#ff0000'];
		$arreglo [] = $dato;
		$i++;
	}*/
	//cargarViento($con,$arreglo);
	//echo json_encode($arreglo);
}

function cargarViento ($con,&$arreglo){
	//SELECT DISTINCT fecha,hora,viento FROM prohoras WHERE viento >10
	$sql1=$con->prepare("SELECT DISTINCT fecha FROM prohoras WHERE viento >10");
	$sql1->execute();
	$respuesta = $sql1->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	$i =0;
	while ($i < $cant_fila) {
		$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => false, 'rendering' => 'background', 'color' => '#ff0000'];
		$arreglo [] = $dato;
		$i++;
	}
}

function actualizarNotificaciones ($actual, $con){
	$sql=$con->prepare("DELETE FROM notificaciones WHERE fecha >='".$actual."'");
	$sql->execute();
	for ($cont_dia = 0; $cont_dia < 7; $cont_dia++){
		$proxfecha = strtotime ('+'.$cont_dia.' day' , strtotime($actual));
		$proxfecha = date ( 'Y-m-d' , $proxfecha );
		$sql1=$con->prepare("SELECT DISTINCT fecha FROM prohoras WHERE fecha = '".$proxfecha."' AND humedad > 60");
		$sql1->execute();
		$resp = $sql1->fetchAll(PDO::FETCH_ASSOC);
		if (count($resp) > 0) {
			$sql2=$con->prepare("INSERT INTO notificaciones(fecha,titulo,descripcion,id_finca) VALUES (:fecha,:titulo,:descripcion,:id_finca)");

			$sql2->execute(array(
				'fecha' =>$proxfecha,
				'titulo' =>"Alta Humedad",
				'descripcion' =>"La humedad estara por arriba del 60%",
				'id_finca' =>1
			 ));
		}

	}

}

function actualizarDb($coordenadas,$con){

	$hoy = date('Y-m-d'); //obtiene fecha actual
	$sql=$con->prepare("DELETE FROM prohoras WHERE fecha >='".$hoy."'");
	$sql->execute();
	$sql=$con->prepare("DELETE FROM prodias WHERE fecha >='".$hoy."'");
	$sql->execute();
	for ($contador_dia = 0; $contador_dia < 153; $contador_dia++){
		$nuevafecha = strtotime ('+'.$contador_dia.' day' , strtotime($hoy));
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
		//echo $nuevafecha;
		$api_url = 'https://api.darksky.net/forecast/376c2beb7497055b8abeed1596b9dd2f/'.$coordenadas.','.$nuevafecha.'T01:00:00?units=auto&exclude=flags,currently';
		$forecast = json_decode(file_get_contents($api_url));

		$sql1=$con->prepare("INSERT INTO prodias(fecha,temperaturaMin,temperaturaMax,humedad,presion,viento,probLluvia,faseLunar,id_finca) VALUES (:fecha,:temperaturaMin,:temperaturaMax,:humedad,:presion,:viento,:probLluvia,:faseLunar,:id_finca)");

			$respuesta=$sql1->execute(array(
				'fecha' =>$nuevafecha,
				'temperaturaMin' =>round($forecast->daily->data[0]->temperatureMin),
				'temperaturaMax' =>round($forecast->daily->data[0]->temperatureMax),
				'humedad' =>$forecast->daily->data[0]->humidity*100,
				'presion' =>round($forecast->daily->data[0]->pressure),
				'viento' =>round($forecast->daily->data[0]->windSpeed*3.6),
				'probLluvia'=>$forecast->daily->data[0]->precipProbability*100,
				'faseLunar' =>$forecast->daily->data[0]->moonPhase,
				'id_finca' =>1
			 ));
		for ($contador_hora=0; $contador_hora < 24; $contador_hora++){
			$sql2=$con->prepare("INSERT INTO prohoras(fecha,hora,temperatura,humedad,presion,viento,probLluvia) VALUES (:fecha,:hora,:temperatura,:humedad,:presion,:viento,:probLluvia)");
			$respuesta=$sql2->execute(array(
				'fecha' =>$nuevafecha,
				'hora' =>$contador_hora,
				'temperatura' =>round($forecast->hourly->data[$contador_hora]->temperature),
				'humedad' =>$forecast->hourly->data[$contador_hora]->humidity*100,
				'presion' =>round($forecast->hourly->data[$contador_hora]->pressure),
				'viento' =>round($forecast->hourly->data[$contador_hora]->windSpeed*3.6),
				'probLluvia'=>$forecast->hourly->data[$contador_hora]->precipProbability*100
			 ));

		}
		
	}
	echo json_encode($respuesta);
	actualizarNotificaciones ($hoy, $con);

}
?>