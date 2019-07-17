<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <script src="js/jquery-3.3.1.min.js"> </script>
  <script src="js/popper.min.js"> </script>
  <script src="js/bootstrap.min.js"> </script>
  <script src="js/agregarCampo.js"> </script>

  <?php require("php/conexion.php") ;
  $con = connectDB();
  ?>

</head>
<body>

<?php
$q = intval($_GET['q']);
if ($q==1) {
  $nom="ido";
}
else {
  $nom="nom_op";
}

?>
<select class="custom-select d-block w-100" id="ido"  name="ido" >

  <?php

    $sql="SELECT * FROM operador ORDER by ".$nom." asc";
    $resultado=mysqli_query($con,$sql);

    if ($resultado)
    {
      ?><option value="" selected disabled>Seleccione Operadores</option><?php

      while ($value=mysqli_fetch_assoc($resultado))
      {
            $nom=utf8_encode($value['nom_op']);
            $ido=$value['ido'];

  ?>
              <option value=<?php echo $ido ?>> <?php echo $ido; ?> - <?php echo $nom  ?></option>
  <?php
      }
    }
    else
    {
      ?><option value="" selected disabled> Error de conexion</option><?php
    }
   ?>

</select>
</body>
</html>
