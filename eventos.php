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
			//$hoy = date('y-m-j'); //obtiene fcha actual
			//$nuevafecha = strtotime ( '+0 day' , strtotime ( $hoy ) ) ;
			//$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
			//echo $nuevafecha;
			//echo $coordenadas;
			actualizarDb($coordenadas,$con);

			break;

			/*case 'reglas':
			# code...
			$sql=$con->prepare("SELECT * FROM events");
			$sql->execute();

			$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
			cargarReglas($con,$resultado);

			break;*/

		case 'cargar':
			# code..
			$hoy = date('Y-m-d');
			$mes = date('m' , strtotime($hoy));
			$arreglo =[];
			//cargarTemperatura($mes,$con);
			//echo $mes;
			if ($mes < 5 || $mes == 8){
				$ano = date('Y' , strtotime($hoy));

			//$arreglo = $_POST['eve'];
			//echo $arreglo;
			//$sql=$con->prepare("SELECT * FROM events");
			//$sql->execute();

			//$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
			
			//if ($tempera == 1){
				//$sql=$con->prepare("SELECT * FROM events");
				//$sql->execute();
				//$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
				//cargarTemperatura($ano,$mes,$con,$resultado);
				cargarTemperatura($ano,$mes,$con,$arreglo);
				//echo json_encode($resultado);
			//}
			}
			echo json_encode($arreglo);
			break;

		case 'prevencion':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
		cargarHumedad($con,$arreglo);
		cargarViento($con,$arreglo);
		lunaCreciente($con,$arreglo);
		echo json_encode($arreglo);
		break;

		case 'apliques':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
		cargarViento($con,$arreglo);
		lunaDecreciente($con,$arreglo);
		echo json_encode($arreglo);
		break;

		case 'plantas':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
		lunaDecreciente($con,$arreglo);
		echo json_encode($arreglo);
		break;

		case 'vendimia':
		$arreglo=[];
		cargarLluvia($con,$arreglo);
		lunaCreciente($con,$arreglo);
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
		lunaCreciente($con,$arreglo);
		echo json_encode($arreglo);
		//echo $hoy;
		break;

		case 'leer':
		# code..
			$sql=$con->prepare("SELECT * FROM events");
			$sql->execute();

			$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode($resultado);

		break;
}

function lunaCreciente($con, &$arreglo){
	$sql=$con->prepare("SELECT fecha FROM `prodias` WHERE faseLunar > 0.02 AND faseLunar <0.48");
	$sql->execute();
	$respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	$i= 0;
	while ( $i < $cant_fila) {
		# code...
		//if($respuesta[$i]['probLluvia'] > 70){
			$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => true, 'rendering' => 'background', 'color' => '#00ff00'];
			$arreglo [] = $dato;
		//	$i = $i + (24-$respuesta[$i]['hora']);
		//}else {
			$i++;
		}
}

function lunaDecreciente($con, &$arreglo){
	$sql=$con->prepare("SELECT fecha FROM `prodias` WHERE faseLunar > 0.52 AND faseLunar <0.99");
	$sql->execute();
	$respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	$i = 0;
	while ( $i < $cant_fila) {
		# code...
		//if($respuesta[$i]['probLluvia'] > 70){
			$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => true, 'rendering' => 'background', 'color' => '#00ff00'];
			$arreglo [] = $dato;
		//	$i = $i + (24-$respuesta[$i]['hora']);
		//}else {
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
	$sql=$con->prepare("SELECT DISTINCT fecha FROM prohoras WHERE humedad < 30");
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
	$sql1=$con->prepare("SELECT DISTINCT fecha FROM prohoras WHERE humedad > 60");
	$sql1->execute();
	$respuesta = $sql1->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	$i =0;
	while ($i < $cant_fila) {
		$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => false, 'rendering' => 'background', 'color' => '#ff0000'];
		$arreglo [] = $dato;
		$i++;
	}
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

function actualizarDb($coordenadas,$con){
	$hoy = date('Y-m-d'); //obtiene fcha actual
	for ($contador_dia = 0; $contador_dia < 153; $contador_dia++){
		$nuevafecha = strtotime ('+'.$contador_dia.' day' , strtotime($hoy));
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
		//echo $nuevafecha;
		$api_url = 'https://api.darksky.net/forecast/376c2beb7497055b8abeed1596b9dd2f/'.$coordenadas.','.$nuevafecha.'T01:00:00?units=auto&exclude=flags,currently';
		$forecast = json_decode(file_get_contents($api_url));

		/*echo round($forecast->daily->data[$contador_dia]->temperatureMin);
		echo "-".round($forecast->daily->data[$contador_dia]->temperatureMax);
		echo "-".$forecast->daily->data[$contador_dia]->humidity*100;
		echo "-".round($forecast->daily->data[$contador_dia]->pressure);
		echo "-".round($forecast->daily->data[$contador_dia]->windSpeed);
		echo "-".$forecast->daily->data[$contador_dia]->precipProbability*100;*/

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

}
?>