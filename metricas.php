<!DOCTYPE html>
<?php
  session_start();
  if (@!$_SESSION['user']) {

    header("Location:index.php");
  }
 ?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Metricas Satelitales</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="css/styles.css" rel="stylesheet">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

	
</head>
<body>
	<?php
	include("plantilla1.php");
	?>
</body>
<script src="jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.nav.menu li').eq(3).addClass('active');
      });

  </script>
</html>