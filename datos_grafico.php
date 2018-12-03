<?php
$con= new PDO("mysql:dbname=calendario;host=localhost","root","");

$accion = (isset($_GET['accion']))?$_GET['accion']:'';

$arreglo1=[];
$arregloE=[];
$arregloI=[];
datosEgreso($_POST['id_finca'], $con, $arreglo1, $arregloE);
datosIngresos($_POST['id_finca'], $con, $arreglo1, $arregloI);
datosResult($arregloE, $arregloI, $arreglo1);
echo json_encode($arreglo1);

function datosEgreso($id, $con, &$arreglo1, &$arregloE){
	if ($id<> 0){
		
		
		$i=1;
		while ($i <= 11) {
			# code...
			$sql=$con->prepare("SELECT SUM(monto) as monto FROM gastos WHERE MONTH(fecha) =".$i." AND YEAR(fecha) = 2018 AND id_finca =".$id."");
			$sql->execute();
			$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
			if ($resultado[0]['monto'] <>null){
				$arregloE[]=$resultado[0]['monto'];
			}else{
				$arregloE[]=0;
			}
			
			$i++;
		}
		
			//SELECT SUM(monto) FROM gastos WHERE MONTH(fecha) = 11 AND YEAR(fecha) = 2018
		
		$dato = (object) ['label' => "Egresos", 'backgroundColor' => 'rgb(255, 99, 132)', 'borderColor' => 'rgb(255, 99, 132)', 'data' => $arregloE, 'fill' => false];

		$arreglo1 [] = $dato;
	}
}

function datosIngresos($id, $con, &$arreglo1, &$arregloI){
	if ($id<> 0){
		
		$i=1;
		while ($i <= 11) {
			# code...
			$sql=$con->prepare("SELECT SUM(monto) as monto FROM ingresos WHERE MONTH(fecha) =".$i." AND YEAR(fecha) = 2018 AND id_finca =".$id."");
			$sql->execute();
			$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
			if ($resultado[0]['monto'] <>null){
				$arregloI[]=$resultado[0]['monto'];
			}else{
				$arregloI[]=0;
			}
			
			$i++;
		}
		
			//SELECT SUM(monto) FROM gastos WHERE MONTH(fecha) = 11 AND YEAR(fecha) = 2018
		
		$dato = (object) ['label' => "Ingresos", 'backgroundColor' => 'rgb(201, 203, 207)', 'borderColor' => 'rgb(201, 203, 207)', 'data' => $arregloI, 'fill' => false];

		$arreglo1 [] = $dato;
	}
}

function datosResult($arregloE, $arregloI, &$arreglo1){
	$i = 0;
	$arreglo = [];
	while ($i < count($arregloI)) {
		# code...
		$arreglo[]= $arregloI[$i] - $arregloE[$i];
		$i++;
	}
	$dato = (object) ['label' => "Saldo", 'backgroundColor' => 'rgb(75, 192, 192)', 'borderColor' => 'rgb(75, 192, 192)', 'data' => $arreglo, 'fill' => false];

		$arreglo1 [] = $dato;
}

?>