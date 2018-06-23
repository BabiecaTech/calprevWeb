<?php
header('Content-Type: application/json');
$con= new PDO("mysql:dbname=calendario;host=localhost","root","");

$accion = (isset($_GET['accion']))?$_GET['accion']:'leer';
switch ($accion) {
	case 'agregar':
		# code...
			$sql=$con->prepare("INSERT INTO events(title,descripcion,color,start,end,id_user) VALUES (:title,:descripcion,:color,:start,:end,:id_user)");

			$respuesta=$sql->execute(array(
				'title' =>$_POST['title'],
				'descripcion' =>$_POST['descripcion'],
				'color' =>'#167EAF',
				'start' =>$_POST['start'],
				'end' =>$_POST['end'],
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
				color=:color,
				start=:start,
				end=:end,
				id_user=:id_user
				WHERE id=:ID");
			$respuesta=$sql->execute(array(
				'ID' =>$_POST['id'],
				'title' =>$_POST['title'],
				'descripcion' =>$_POST['descripcion'],
				'color' =>'#167EAF',
				'start' =>$_POST['start'],
				'end' =>$_POST['end'],
				'id_user' =>$_POST['id_user']
			 ));
			echo json_encode($respuesta);
		break;

	default:
		# code..
			$sql=$con->prepare("SELECT * FROM events");
			$sql->execute();

			$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($resultado);
		break;
}


?>