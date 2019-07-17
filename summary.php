<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title></title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="DataTables/datatables.css">

    <?php
      require ('php/conexion.php');
      $con = connectDB_PDO();
    ?>

</head>
<body>
      <div class="container">

              <table id="dataTables" class="display" style="width:100%">
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
                      $consulta = $con->prepare("SELECT
                                                        desde,
                                                        hasta,
                                                        normal,
                                                        reducido,
                                                        nocturno
                                                FROM PRICE
                                                          ORDER BY desde ASC");
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



      </div>
      <script type="text/javascript" src="js/jQuery-3.3.1/jquery-3.3.1.min.js"></script>
      <script src="js/popper.min.js"> </script>
      <script src="js/bootstrap.min.js"> </script>
      <script src="js/agregarCampo.js"> </script>
      <script type="text/javascript" src="DataTables/datatables.js"></script>
      <script type="text/javascript" src="js/DataTable.js"></script>
</body>
</html>
