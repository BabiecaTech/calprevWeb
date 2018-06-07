<?php
	eliminarUsuario($_GET['id']);

	function eliminarUsuario($id)
	{
		require("conexion.php");
		eliminarEventos($id);
		$sql="DELETE FROM login WHERE id='".$id."' ";
		mysqli_query($conn,$sql);
	}

	function eliminarEventos($id)
	{
		require("conexion.php");
		$sql="DELETE FROM events WHERE id_user='".$id."' ";
		mysqli_query($conn,$sql);
	}
?>

<script type="text/javascript">
	window.location.href='lista_usuarios.php';
	//alert("Usuario Registrado exitosamente");
</script>