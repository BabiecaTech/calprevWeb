<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Usuarios</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<link href="css/styles.css" rel="stylesheet">
</head>
<?php
  session_start();
  if (@!$_SESSION['user']) {
    header("Location:index.php");
  }
?>
<div class="modal fade" id="vistaUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="titulo">Modificar</h5>
        
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
        <input type="hidden" id="idUser" name="idUser" />
        Usuario: <input type="text" id="txtUser" name="txtUser" class="form-control" required>
        Email: <input type="email" id="txtEmail" name="txtEmail" class="form-control" disabled="true" >
        Rol: <SELECT id="selectRol" name="selectRol" class="form-control" required>
          <option value="1">Administrador</option>
          <option value="2">Encargado</option>
          
        </SELECT>
        Cambiar Contraseña: <input type="password" id="txtpass" name="txtpass" class="form-control" placeholder="Cambiar Contraseña" >
        Confirmar Contraseña: <input type="password" id="txtpass2" name="txtpass2" class="form-control" placeholder="Confirmar Contraseña" >
        </div>
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" id="btnModificar" class="btn btn-primary">Mofificar</button>
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="vistaUserNuevo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="titulo">Nuevo Usuario</h5>
       
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
        <input type="hidden" id="idUserN" name="idUserN" />
        Usuario: <input type="email" id="txtUserN" name="txtUserN" class="form-control" placeholder="usuario" required >
        Email: <input type="text" id="txtEmailN" name="txtEmailN" class="form-control" placeholder="ejemplo@ejemplo.com" required>
        Rol: <SELECT id="selectRolN" name="selectRolN" class="form-control" required>
          <option value="1">Administrador</option>
          <option value="2">Encargado</option>
          
        </SELECT>
        Contraseña: <input type="password" id="txtpassN" name="txtpassN" class="form-control" placeholder="Contraseña" required>
        ConfirmarContraseña: <input type="password" id="txtpass2N" name="txtpass2N" class="form-control" placeholder="Confirmar Contraseña" required>
        </div>
        </form>
      </div>
      <div class="modal-footer">

        <button type="button" id="btnUserNuevo" class="btn btn-success">Guardar</button>
        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
      </div>
    </div>
  </div>
</div>


<body>
<?php
include("plantilla1.php");
?>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#">
          <em class="fa fa-user"></em>
        </a></li>
        <li class="active">Usuarios</li>
      </ol>
    </div><!--/.row-->
     <div class="row">

      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <button type="button" class="btn btn-success" id="addUser"><span class='glyphicon glyphicon-plus'></span> Nuevo</button>
             <table class="table">
              <thead>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
              </thead>
  		
  		<?php
      require("conexion.php");
      $sql="SELECT * FROM login WHERE activo = 1 AND id_finca =".$_SESSION['id_finca'];
      $resultado=mysqli_query($conn,$sql);
      //echo ($resultado);
      while($filas=mysqli_fetch_assoc($resultado))
      {
        echo "<tr>";
          echo "<td id='user".$filas['id']."'>"; echo $filas['user']; echo "</td>";
          echo "<td id='email".$filas['id']."'>"; echo $filas['email']; echo "</td>";
          $rol=$filas['rol'];
          echo "<p hidden id='rol".$filas['id']."'>".$rol."</p>";
          if ($rol == 2){
            echo "<td>"; echo "Encargado"; echo "</td>";
          }else if ($rol == 1){
            echo "<td>"; echo "Administrador"; echo "</td>";
          }
          echo "<td><button type='button' class='btn btn-info' onclick='modificarUser(".$filas['id']. ")'><span class='glyphicon glyphicon-pencil'></span></button> </a> </td>";
          if($filas['id']==$_SESSION['id']){
            echo "<td> <button type='button' class='btn btn-danger' disabled='true'><span class='glyphicon glyphicon-trash'></span></button> </td>";
          }else{
          echo "<td> <button type='button' class='btn btn-danger' onclick='eliminarUser(".$filas['id'].")'><span class='glyphicon glyphicon-trash'></span></button> </td>";
        }
        echo "</tr>";
      }

      ?>
  	</table>
  </div>
</div>
</div>
<!--</div>-->
</body>

<script src="jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.nav.menu li').eq(4).addClass('active');
      });

  </script>
<script>
  $(document).ready(function(){
  
    $('#addUser').click(function(){
      agregarUser();
    });
    $('#btnDel').click(function(){
      eliminar(id_fila_selected);
    });
    
    $('#btnModificar').click(function(){
      if (checkdatosM()){
        if (confirm("¿Desea actualizar el usuario..?")==true){ 
        recolectarDatosM();
        enviar('modificar');
        }
      }      
    });

     $('#btnUserNuevo').click(function(){
      if (checkdatos()){
        if (confirm("¿Desea crear el nuevo usuario..?")==true){
        recolectarDatos();
        enviar('guardar');
        }
      }      
    });


  });

  var dato;

   function eliminarUser(id){
      if (confirm("¿Desea eliminar el usuario?")==true){
      recolectarDatoDel(id);
      enviar('eliminar');
      }
  }

  function modificarUser(id){

    $('#txtUser').val($('#user'+id).text());
    $('#txtEmail').val($('#email'+id).text());
    $('#selectRol').val($('#rol'+id).text());
    $('#idUser').val(id);
    $('#txtpass').val("");
    $('#txtpass2').val("");
    $('#vistaUser').modal();

  }

  function limpiarCampos(){
    $('#txtUserN').val("");
    $('#txtEmailN').val("");
    $('#selectRolN').val("");
    $('#txtpassN').val("");
    $('#txtpass2N').val("");
  }

  function agregarUser(){
    limpiarCampos();
    $('#vistaUserNuevo').modal();
  };

  function checkdatosM(){
    var result = true;
    if ($('#txtUser').val() == ""){
        alert("Falta nombre de usuario");
        result = false;
    }
    if ($('#txtpass').val() != "" || $('#txtpass2').val() !=""){
      if ($('#txtpass').val() != $('#txtpass2').val()){
        alert("Las contraseñas no coinciden");
        result = false;
      }
    }
    return result;
  }

  function checkdatos(){
    var result = true;
    if ($('#txtUserN').val() == ""|| $('#txtEmailN').val() == ""|| $('#selectRolN').val()==null || $('#txtpass2N').val() =="" || $('#txtpassN').val() ==""){
        alert("Complete todos los campos");
        result = false;
    }
    if ($('#txtpassN').val() != "" || $('#txtpass2N').val() !=""){
      if ($('#txtpassN').val() != $('#txtpass2N').val()){
        alert("Las contraseñas no coinciden");
        result = false;
      }
    }
    return result;
  }


   function recolectarDatosM(){
    dato= {
      id:$('#idUser').val(),
      user:$('#txtUser').val(),
      rol:$('#selectRol').val(),
      password:$('#txtpass').val(),
      repassword:$('#txtpass2').val()
    };
  };
   

   function recolectarDatos(){
    dato= {
      user:$('#txtUserN').val(),
      email:$('#txtEmailN').val(),
      rol:$('#selectRolN').val(),
      password:$('#txtpassN').val(),
      repassword:$('#txtpass2N').val(),
      id_finca:$("#id_finca").val()
    };
  };

  function recolectarDatoDel(idUser){
    dato={
      id:idUser
    };
  };

  function enviar(accion){
    $.ajax({
    type:'POST',
        url:'guardar_user.php?accion='+accion,
        data: dato,
        success:function(msg){
          $('#vistaModal').modal('toggle');
          if (accion == "guardar"){
             alert("Usuario se creo exitosamente");
          }else if (accion == "modificar"){
            alert("Usuario se actualizo exitosamente");
          }else{
            alert("Usuario se elimino exitosamente");
          }
        location.reload();
          
        },
        error:function(msg){
        alert("Error conexion base de datos..."); 
      }
  });
  };
  </script>
</html>