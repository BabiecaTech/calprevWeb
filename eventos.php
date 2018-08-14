<?php
header('Content-Type: application/json');
$con= new PDO("mysql:dbname=calendario;host=localhost","root","");

$accion = (isset($_GET['accion']))?$_GET['accion']:'leer';
switch ($accion) {
	case 'agregar':
		# code...
			$sql=$con->prepare("INSERT INTO events(title,descripcion,costo,color,start,end,asignar,id_user) VALUES (:title,:descripcion,:costo,:color,:start,:end,:asignar,:id_user)");

			$respuesta=$sql->execute(array(
				'title' =>$_POST['title'],
				'descripcion' =>$_POST['descripcion'],
				'costo' =>$_POST['costo'],
				'color' =>'#167EAF',
				'start' =>$_POST['start'],
				'end' =>$_POST['end'],
				'asignar' =>$_POST['asignar'],
				'id_user' =>$_POST['id_user']
			 ));
			echo json_encode($respuesta);
		break;

	case 'agregard':
		# code...
			$sql=$con->prepare("INSERT INTO events(title,descripcion,costo,color,start,end,asignar,id_user) VALUES (:title,:descripcion,:costo,:color,:start,:end,:asignar,:id_user)");

			$respuesta=$sql->execute(array(
				'title' =>$_POST['title'],
				'descripcion' =>$_POST['descripcion'],
				'costo' =>$_POST['costo'],
				'color' =>'#167EAF',
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
				'color' =>'#167EAF',
				'start' =>$_POST['start'],
				'end' =>$_POST['end'],
				'asignar' =>$_POST['asignar'],
				'id_user' =>$_POST['id_user']
			 ));
			echo json_encode($respuesta);
		break;

		/*case 'actualizar':
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

			break;*/

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
			//cargarTemperatura($mes,$con);
			if ($mes < 5 || $mes == 8){
			$ano = date('Y' , strtotime($hoy));

			//$arreglo = $_POST['eve'];
			//echo $arreglo;
			//$sql=$con->prepare("SELECT * FROM events");
			//$sql->execute();

			//$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
			$a = json_decode(file_get_contents($_POST['eve']));
			cargarTemperatura($ano,$mes,$con,$a);
			}
			break;

		case 'leer':
		# code..
			$sql=$con->prepare("SELECT * FROM events");
			$sql->execute();

			$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

			/*$dato = (object) ['start' => '2018-08-15', 'overlap' => false, 'rendering' => 'background', 'color' => '#ff9f89'];
			$resultado [] = $dato;

			echo json_encode($resultado);*/
			cargarReglas($con,$resultado);
			$temp = isset($_POST['eve']);
			if ($temp){
				$hoy = date('Y-m-d');
				$mes = date('m' , strtotime($hoy));
				if ($mes < 5 || $mes == 8){
					$ano = date('Y' , strtotime($hoy));
					cargarTemperatura($ano,$mes,$con, $resultado);
				}
			}

			echo json_encode($resultado);

		break;
}

function cargarReglas($con, &$arreglo){
	$sql=$con->prepare("SELECT * FROM prohoras");
	$sql->execute();
	$respuesta = $sql->fetchAll(PDO::FETCH_ASSOC);
	$cant_fila = count($respuesta);
	//echo $respuesta[0]['viento'];
	$i = 0;
	while ( $i < 240) {
		# code...
		if($respuesta[$i]['probLluvia'] > 70){
			$dato = (object) ['start' => $respuesta[$i]['fecha'], 'overlap' => true, 'rendering' => 'background', 'color' => '#ff9f89'];
			$arreglo [] = $dato;
			$i = $i + (24-$respuesta[$i]['hora']);
		}else {
			$i++;
		}
		
	}
	//echo json_encode($arreglo);
}

function cargarTemperatura($ano,$mes,$con, &$arreglo){
	
		$sql1=$con->prepare("SELECT * FROM prohoras where MONTH(Fecha)>=:MES and YEAR(Fecha)=:ANO");
		$sql1->execute(array("MES"=>$mes,
							"ANO"=>$ano));
		$respuesta = $sql1->fetchAll(PDO::FETCH_ASSOC);
		$cant_fila = count($respuesta);
		//echo json_encode($respuesta);
		$i = 0;
		while ( $i < $cant_fila) {
			# code...
			if ($respuesta[$i]['temperatura'] <3) {
				# code...
				$j = 0;
				while ($j<4) {
					# code...
					$fecha = strtotime ('+'.$j.' day' , strtotime( $respuesta[$i]['fecha']));
					//echo date('Y-m-d',$fecha);
					$dato = (object) ['start' => date('Y-m-d',$fecha), 'overlap' => true, 'rendering' => 'background', 'color' => '#A0CEEF'];
					$arreglo [] = $dato;
					$j++;
				}
				
				$i = $i + (24-$respuesta[$i]['hora']) +72;
			}else{
				$i++;
			}
		}
		//echo json_encode($arreglo);
	}

function actualizarDb($coordenadas,$con){
	$hoy = date('Y-m-d'); //obtiene fcha actual
	for ($contador_dia = 0; $contador_dia < 60; $contador_dia++){
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