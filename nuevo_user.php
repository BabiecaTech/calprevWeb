<?php
  require 'conexion.php';
  include 'funciones.php';

  $errors = array();
  
  if(!empty($_POST))
  {

    $usuario = $conn->real_escape_string($_POST['usuario']);
    $password = $conn->real_escape_string($_POST['password']);
    $con_password = $conn->real_escape_string($_POST['con_password']);
    $email = $conn->real_escape_string($_POST['email']);
    $id_finca = 0;
    $rol = 1;
      
  
    if(isNull($usuario, $password, $con_password, $email))
    {
      $errors[] = "Debe llenar todos los campos";
    }
    
    if(!isEmail($email))
    {
      $errors[] = "Dirección de correo inválida";
    }
    
    if(!validaPassword($password, $con_password))
    {
      $errors[] = "Las contraseñas no coinciden";
    }   
    
    if(usuarioExiste($usuario))
    {
      $errors[] = "El nombre de usuario $usuario ya existe";
    }
    
    if(emailExiste($email))
    {
      $errors[] = "El correo electronico $email ya existe";
    }

    
    if(count($errors) == 0)
    {
      
        $registro = registraUsuario($usuario,"", $email, $password, $rol, $id_finca);
        if($registro > 0){ 

            echo "<h2>Ha sido Registrado Correctamente</h2>";
            echo "<a href='index.php' >Iniciar Sesion</a>";
          
        }else {
          echo "<p>Error al Registar</p>";
        }
  }
}
?>
<html>
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro</title>
  <link href="css/datepicker3.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="css/styles.css" rel="stylesheet">
  <script src="js/jquery.min.js"></script>
  </head>
  <body>
    <div class="container">
      <div id="signupbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
          <div class="panel-heading">Registrate</div>  
          
          <div class="panel-body" >
            <form id="signupform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
              
              <div id="signupalert" style="display:none" class="alert alert-danger">
                <p>Error:</p>
                <span></span>
              </div>
              
              <div class="form-group">
                <label for="usuario" class="col-md-3 control-label">Usuario</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="usuario" placeholder="Usuario" value="<?php if(isset($usuario)) echo $usuario; ?>" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="password" class="col-md-3 control-label">Password</label>
                <div class="col-md-9">
                  <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="con_password" class="col-md-3 control-label">Confirmar Password</label>
                <div class="col-md-9">
                  <input type="password" class="form-control" name="con_password" placeholder="Confirmar Password" required>
                </div>
              </div>
              
              <div class="form-group">
                <label for="email" class="col-md-3 control-label">Email</label>
                <div class="col-md-9">
                  <input type="email" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>" required>
                </div>
              </div>
              
              <div class="form-group">                             
                <div class="col-md-9" style="float: right;">
                  <button id="btn-signup" type="submit" class="btn btn-primary">REGISTRAR</button>
                  <a id="signinlink" href="index.php">Iniciar Sesi&oacute;n</a>
                </div>
              </div>
            </form>
            <?php echo resultBlock($errors); ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>