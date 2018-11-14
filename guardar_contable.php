<?php
header('Content-Type: application/json');
$con= new PDO("mysql:dbname=calendario;host=localhost","root","");
session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }

$accion = (isset($_GET['accion']))?$_GET['accion']:'';
switch ($accion) {
	case 'guardar':
		# code..

			$sql=$con->prepare("INSERT INTO gastos(fecha,monto,descripcion,id_tipo,id_finca,id_user) VALUES (:fecha,:monto,:descripcion,:id_tipo,:id_finca,:id_user)");
				$respuesta=$sql->execute(array(
				'fecha' =>$_POST['fecha'],
				'monto' =>$_POST['costo'],
				'descripcion' =>$_POST['descripcion'],
				'id_tipo' =>$_POST['tipo'],
				'id_finca' =>$_POST['id_finca'],
				'id_user' => $_SESSION['id']
			 ));
			echo json_encode($respuesta);
		break;
	case 'modificar':
			//echo ($_POST['id']);
		# code..
			$sql=$con->prepare("UPDATE gastos SET 
				fecha=:fecha,
				monto=:monto,
				descripcion=:descripcion,
				id_tipo=:id_tipo
				WHERE id=:ID");
				$respuesta=$sql->execute(array(
				'fecha' =>$_POST['fecha'],
				'monto' =>$_POST['costo'],
				'descripcion' =>$_POST['descripcion'],
				'id_tipo' =>$_POST['tipo'],
				'ID' =>$_POST['id']
			));

		echo json_encode($respuesta); 
		break;

	case 'eliminar':
		# code...
		$sql=$con->prepare("DELETE FROM gastos WHERE id =:ID");
		$respuesta=$sql->execute(array(
			'ID' =>$_POST['id']
		));
	
		echo json_encode($respuesta); 
		break;
		// Registro de Ingresos
	case 'guardari':
		# code..

			$sql=$con->prepare("INSERT INTO ingresos(fecha,monto,descripcion,id_finca,id_user) VALUES (:fecha,:monto,:descripcion,:id_finca,:id_user)");
				$respuesta=$sql->execute(array(
				'fecha' =>$_POST['fecha'],
				'monto' =>$_POST['costo'],
				'descripcion' =>$_POST['descripcion'],
				'id_finca' =>$_POST['id_finca'],
				'id_user' => $_SESSION['id']
			 ));
			echo json_encode($respuesta);
		break;
	case 'modificari':
			//echo ($_POST['id']);
		# code..
			$sql=$con->prepare("UPDATE ingresos SET 
				fecha=:fecha,
				monto=:monto,
				descripcion=:descripcion
				WHERE id=:ID");
				$respuesta=$sql->execute(array(
				'fecha' =>$_POST['fecha'],
				'monto' =>$_POST['costo'],
				'descripcion' =>$_POST['descripcion'],
				'ID' =>$_POST['id']
			));

		echo json_encode($respuesta); 
		break;

	case 'eliminari':
		# code...
		$sql=$con->prepare("DELETE FROM ingresos WHERE id =:ID");
		$respuesta=$sql->execute(array(
			'ID' =>$_POST['id']
		));
	
		echo json_encode($respuesta); 
		break;
}

?>