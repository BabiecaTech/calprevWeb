<?php
header('Content-Type: application/json');
$con= new PDO("mysql:dbname=calendario;host=localhost","root","");

$accion = (isset($_GET['accion']))?$_GET['accion']:'';
switch ($accion) {
	case 'guardar':
		# code..

			$sql=$con->prepare("INSERT INTO login(user,password,email,pasadmin,rol,activo,id_finca) VALUES (:user,:password,:email,:pasadmin,:rol,:activo,:id_finca)");
			$rol = $_POST['rol'];
			if ($rol == 1){
				$respuesta=$sql->execute(array(
				'user' =>$_POST['user'],
				'password' =>"",
				'email' =>$_POST['email'],
				'pasadmin' =>$_POST['password'],
				'rol' =>$rol,
				'activo' =>1,
				'id_finca' =>$_POST['id_finca']
			 ));
			}else{
				$respuesta=$sql->execute(array(
				'user' =>$_POST['user'],
				'password' =>$_POST['password'],
				'email' =>$_POST['email'],
				'pasadmin' =>"",
				'rol' =>$rol,
				'activo' =>1,
				'id_finca' =>$_POST['id_finca']
			 ));
			}

			echo json_encode($respuesta);
		break;
	case 'modificar':
			//echo ($_POST['id']);
		# code...
		$pass=$_POST['password'];
		$rol=$_POST['rol'];

		if($pass <> ""){
			$sql=$con->prepare("UPDATE login SET 
				user=:user,
				password=:password,
				pasadmin=:pasadmin,
				rol=:rol
				WHERE id=:ID");
			if ($rol == 1){
				$respuesta=$sql->execute(array(
				'user' =>$_POST['user'],
				'password' =>"",
				'pasadmin' =>$pass,
				'rol' =>$rol,
				'ID' =>$_POST['id']
			 ));
			}else{
				$respuesta=$sql->execute(array(
				'user' =>$_POST['user'],
				'password' =>$pass,
				'pasadmin' =>"",
				'rol' =>$rol,
				'ID' =>$_POST['id']
			 ));
			}
		}else{
			$sql=$con->prepare("UPDATE login SET 
				user=:user,
				rol=:rol
				WHERE id=:ID");
			$respuesta=$sql->execute(array(
				'user' =>$_POST['user'],
				'rol' =>$rol,
				'ID' =>$_POST['id']
			 ));

		}

		echo json_encode($respuesta); 
		break;

	case 'eliminar':
		# code...
		$sql=$con->prepare("DELETE FROM login WHERE id =:ID");
		$respuesta=$sql->execute(array(
			'ID' =>$_POST['id']
		));
	
		echo json_encode($respuesta); 
		break;
}

?>