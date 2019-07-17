<!DOCTYPE html>
<html>
<head>

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">

  <?php require("php/conexion.php") ;
        $con = connectDB_PDO();
  ?>

</head>
<body>


    <table class="table" class="display" >
          <thead>
              <tr>
                  <th>Desde</th>
                  <th>Hasta</th>
                  <th>H. Normal</th>
                  <th>H. Reducido</th>
                  <th>H. Nocturno</th>
              </tr>
          </thead>
          <tbody>
            <?php
             $id_date = intval($_GET['id']);

            $consulta = $con->prepare("SELECT
                                              desde,
                                              hasta,
                                              normal,
                                              reducido,
                                              nocturno
                                      FROM PRICE
                                                WHERE id_date=:IDDATE
                                                ORDER BY desde ASC");

            $consulta->bindParam(":IDDATE", $id_date);
            $consulta->execute();
             ?>
             <?php
                  while ($datos = $consulta->fetch()) {
            ?>
                    <tr>
                      <!-- DATOS  DE DESDE -->
                        <td><?php echo $datos[0] ;?></td>
                        <!-- DATOS  DE Hasta -->
                        <td><?php echo $datos[1] ;?></td>
                        <!-- DATOS  DE H. Normal -->
                        <td><?php echo $datos[2] ;?></td>
                        <!-- DATOS DE  H. Reducido -->
                        <td><?php echo $datos[3] ;?></td>
                        <!-- DATOS DE  H. Nocturno -->
                        <td><?php echo $datos[4] ;?></td>
                    </tr>

             <?php
                  }
              ?>
          </tbody>
    </table>

<script type="text/javascript" src="js/jQuery-3.3.1/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"> </script>
<script src="js/bootstrap.min.js"> </script>
<script src="js/showTablePrice.js"> </script>

</body>
</html>
