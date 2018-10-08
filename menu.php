
<!--<div class="navbar">
  <div class="navbar-inner">
	<div class="container">
	  <div class="nav-collapse">
		<ul class="nav pull-right">
				<p>Bienvenido <strong><?php echo $_SESSION['user'];?></strong></p>
			  <p><a href="desconectar.php"> Cerrar Cesión </a></p>			 
		</ul>
	  </div>
	</div>
  </div>
</div>-->

<header class="main-header" >
	<div class="contenedor1">	
	<h3 class="titulo">Bienvenido <?php echo $_SESSION['user'];?></h3>
	<a href="desconectar.php"> Cerrar Sesión </a>
</div>
<div class="contenedor">
	<nav class="menu">
		<ul>
			<li><a href="lista_usuarios.php">Usuarios</a></li>
			<li><a href="calendario.php">Calendario</a></li>
			<li><a href="lista_fincas.php">Fincas</a></li>
		</ul>
	</nav>
	</div>
</header>