<?php
	$id = $_POST['id'];
	$user = $_POST['user'];
	$email = $_POST['email'];
	$rol = $_POST['rol'];

	//echo ($user);
	if ($rol == "Administrador"){
		modificarUsuario($id,$user,$email,1);
	}else if ($rol == "Encargado"){
		modificarUsuario($id,$user,$email,2);
	}else if ($rol == "Empleado"){
		modificarUsuario($id,$user,$email,3);
	}

	function modificarUsuario($id, $usuario, $email, $rol)
	{
		require("conexion.php");
		$consulta="SELECT password FROM login WHERE id=$id";
    	$resultado=mysqli_query($conn,$consulta);
    	$fila=mysqli_fetch_assoc($resultado);
    	$key=$fila['password'];
    	//echo ($key);
		$sql="UPDATE login SET user= '$usuario', password='$key', email='$email', pasadmin='', rol=$rol WHERE id=$id";
		mysqli_query($conn,$sql); //or die (mysql_error());
	}
?>

<script type="text/javascript">
	alert("Usuario Modificado exitosamente");
	window.location.href='lista_usuarios.php';
</script>