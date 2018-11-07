<?php
header('Content-Type: application/json');
$con= new PDO("mysql:dbname=calendario;host=localhost","root","");

$accion = (isset($_GET['accion']))?$_GET['accion']:'';
switch ($accion) {
	case 'guardar':
		# code...
			 //echo ($_POST['0']['0']['select']);
			$sql=$con->prepare("INSERT INTO fincas(nombre,latitud,longitud) VALUES (:nombre,:latitud,:longitud)");

			$respuesta=$sql->execute(array(
				'nombre' =>$_POST['nombre'],
				'latitud' =>$_POST['latitud'],
				'longitud' =>$_POST['longitud']
			 ));
			$last_id = $con ->lastInsertId();
			$cant = $_POST['cantidad'];
			if ($cant > 0){
				//echo ($_POST[$i]['1']['hecta']);
				$i=0;
				while ($i < $cant) {
				# code...
				$sql1=$con->prepare("INSERT INTO finvin(id_finca,id_vinedo,cant_hectarea) VALUES (:id_finca,:id_vinedo,:cant_hectarea)");
				$respuesta1=$sql1->execute(array(
				'id_finca' =>$last_id,
				'id_vinedo' =>$_POST[$i]['0']['select'],
				'cant_hectarea' =>$_POST[$i]['1']['hecta']
			 	));
				$i++;
				}
				echo json_encode($respuesta1);
			}else{
				echo json_encode($respuesta);
			}
			
		break;
	case 'modificar':
			//echo ($_POST['id']);
		# code...
		$sql=$con->prepare("UPDATE fincas SET 
				nombre=:nombre 
				WHERE id=:ID");
		$respuesta=$sql->execute(array(
				'nombre' =>$_POST['nombre'],
				'ID' =>$_POST['id']
			 ));
		$sql1=$con->prepare("DELETE FROM finvin WHERE id_finca=:ID");
		$sql1->execute(array('ID' => $_POST['id']));

		$cant = $_POST['cantidad'];
		if ($cant > 0){
				//echo ($_POST[$i]['1']['hecta']);
				$i=0;
				while ($i < $cant) {
				# code...
				$sql1=$con->prepare("INSERT INTO finvin(id_finca,id_vinedo,cant_hectarea) VALUES (:id_finca,:id_vinedo,:cant_hectarea)");
				$respuesta1=$sql1->execute(array(
				'id_finca' =>$_POST['id'],
				'id_vinedo' =>$_POST[$i]['0']['select'],
				'cant_hectarea' =>$_POST[$i]['1']['hecta']
			 	));
				$i++;
				}
				echo json_encode($respuesta1);
			}else{
				echo json_encode($respuesta);
			}
		break;
}

?>