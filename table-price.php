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


              <form class="needs-validation"   name="formulario" method='POST' id="table" enctype="multipart/form-data"  novalidate >


                    <div class="panel-body">
                          <table class="table table-bordered " id="tablaresponsive">

                            <thead>

                              <th colspan="2" class="text-center">Periodo</th>
                              <th colspan="3" class="text-center">Precios(H.NO H.RE H.NOC)</th>
                              <th colspan="1"><button type="button" id="nueva" class="btn btn-info">Nueva</button></th>

                            </thead>

                            <tbody id="tBody">

                              <tr id="Tr">
                                <td><input type="date" class="form-control"  name="desde[]" id="desde"  placeholder="Desde" required></td>
                                <td><input type="date" class="form-control"  name="hasta[]" id="hasta" placeholder="Hasta"  required></td>
                                <td><input type="number" class="form-control"  name="normal[]" id="normal" placeholder="NORMAL"required ></td>
                                <td><input type="number" class="form-control"  name="reducido[]" id="reducido" placeholder="REDUCIDO"  required></td>
                                <td><input type="number" class="form-control"  name="nocturno[]" id="nocturno" placeholder="NOCTURNO"  required></td>
                                <td><button type="button" class="btn btn-danger" id="eliminalinea">eliminar</button></td></td>
                             </tr>

                             </tbody>
                          </table>

                      </div>

                        <button class="btn btn-primary btn-lg " type="submit" name="mostrar" onclick=this.form.action="php/addTablePrice.php"> Guardar</button>


              </form>

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
