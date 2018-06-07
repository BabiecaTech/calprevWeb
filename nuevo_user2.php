<?php

	$user = $_POST['nombre'];
	$email = $_POST['email'];
	$contraseña = $_POST['contraseña'];
	$contraseña1 = $_POST['contraseña1'];
	$rol = $_POST['rol'];

	//echo ($rol);
	if ($rol == "administrador"){
		NuevoUsuario($user,$email,$contraseña,1);
	}else if ($rol == "encargado"){
		NuevoUsuario($user,$email,$contraseña,2);
	}else if ($rol == "empleado"){
		NuevoUsuario($user,$email,$contraseña,3);
	}
	function NuevoUsuario($usuario,$email,$contraseña,$rol)
	{
		require("conexion.php");
		$sql="INSERT INTO login (user, password, email, pasadmin, rol) VALUES ('$usuario', '$contraseña', '$email','',$rol)";
		mysqli_query($conn,$sql)or die (mysql_error());
	}
?>

<script type="text/javascript">
	alert("Usuario Registrado exitosamente");
	window.location.href='lista_usuarios.php';
</script>