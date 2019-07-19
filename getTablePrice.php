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
                  <th>Tipo</th>
                  <th>Desde</th>
                  <th>Hasta</th>
                  <th>H. Normal</th>
                  <th>H. Reducido</th>
                  <th>H. Nocturno</th>
              </tr>
          </thead>
          <tbody>
            <?php
             $id = $_GET['id'];
             $id_date=substr($id,-6);
             $tipo=substr($id,0,-6);

            $consulta = $con->prepare("SELECT
                                              tipo,
                                              desde,
                                              hasta,
                                              normal,
                                              reducido,
                                              nocturno
                                      FROM PRICE
                                                WHERE id_date=:IDDATE and tipo=:TIPO
                                                ORDER BY desde ASC");

            $consulta->bindParam(":IDDATE", $id_date);
            $consulta->bindParam(":TIPO", $tipo);

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

                        <td><?php echo $datos[5] ;?></td>
                    </tr>

                    <input type="hidden" class="form-control"  name="desde[]"     value=<?php echo $datos[1] ;?> ></td>
                    <input type="hidden" class="form-control"  name="hasta[]"     value=<?php echo $datos[2] ;?> ></td>
                    <input type="hidden" class="form-control"  name="normal[]"    value=<?php echo $datos[3] ;?> ></td>
                    <input type="hidden" class="form-control"  name="reducido[]"  value=<?php echo $datos[4] ;?> ></td>
                    <input type="hidden" class="form-control"  name="nocturno[]"  value=<?php echo $datos[5] ;?> ></td>

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
