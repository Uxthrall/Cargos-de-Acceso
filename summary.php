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
      $connection=connectDB();
    ?>

</head>
<body>
      <div class="container">

              <table id="dataTables" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Ido</th>
                            <th>Compañía</th>
                            <th>Periodo</th>
                            <th>Segundo( a minuto)</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $consulta = $con->prepare("SELECT
                                                		  c.ido,
                                                		  o.nom_op,
                                                		  DATE_FORMAT(calldate,'%Y-%m') AS periodo,
                                                      DATE_FORMAT(calldate,'%Y%m') AS mes_anho,
                                                      DATE_FORMAT(calldate,'%Y%m') AS mes_anho,
                                                      sum(billsec) as segundo

                                                  FROM cdr AS c JOIN operador AS o
                                                  ON c.ido=o.ido
                                                                              GROUP BY periodo");
                      $consulta->execute();
                       ?>
                       <?php
                            while ($datos = $consulta->fetch())
                            {
                      ?>
                              <tr>

                                  <td><?php echo $datos[0] ;?></td>

                                  <td><?php echo $datos[1] ;?></td>

                                  <td><?php echo $datos[2] ;?></td>

                                  <td><?php echo $datos[5] ;?></td>




                                                  <?php

                                                          $sql= $con->prepare("SELECT  desde, hasta,  normal,  reducido,  nocturno  FROM price WHERE id_date=:IDDATE AND tipo=:TIPO");

                                                          // realizar algun if para la seleccion de precio entre entel, movistar y ccaa

                                                          $tipo="CCAA";

                                                          $sql->bindParam(':IDDATE', $datos[3]);
                                                          $sql->bindParam(':TIPO', $tipo);

                                                          $sql->execute();
                                                          $monto_total_cobrar=0;

                                                          while($valor = $sql -> fetch())
                                                          {
                                                              $ido=$datos[0];
                                                              $desde=$valor[0];
                                                              $hasta=$valor[1];
                                                              $normal=$valor[2];
                                                              $reducido=$valor[3];
                                                              $nocturno=$valor[4];

                                                              // $fecha1 =  date_create($since[$i]);
                                                              // $fecha2 =  date_create($until[$i]);
                                                              //  $desde =  date_format($fecha1, "o-m-d");
                                                              //  $hasta =  date_format($fecha2, "o-m-d");

                                                              $sqli="select
                                                                       sum(billsec) as segundo
                                                                    from cdr
                                                                           where
                                                                               (calldate BETWEEN '".$desde." 09:00:00' and '".$hasta." 23:59:59') and
                                                                               (DATE_FORMAT(calldate,'%Y-%m-%d') not in (select fecha from feriado)) and
                                                                               ((DATE_FORMAT(calldate, '%H:%i:%s')) between '09:00:00' and '23:59:59') and
                                                                               (ido=".$ido.")  AND
                                                                               (disposition='ANSWERED') and
                                                                               (dayname(calldate) ='Monday' or
                                                                               dayname(calldate) ='Tuesday' or
                                                                               dayname(calldate) ='Wednesday' or
                                                                               dayname(calldate) ='Thursday' or
                                                                               dayname(calldate) ='Friday') ";
                                                               $result=mysqli_query($connection,$sqli);

                                                               while ($no=mysqli_fetch_assoc($result))
                                                               {
                                                                 $s_no=$no['segundo'];
                                                               }
                                                               // se almacena las variables en s_no (suma de segundos) l_no(cant de llamadas);

                                                               // consulta para horario reducido
                                                               $sqlii= " select
                                                                             sum(billsec) as segundo
                                                                       from cdr
                                                                                 WHERE
                                                                                        (calldate between '".$desde." 09:00:00' and '".$hasta." 23:59:59') and
                                                                                        ((DATE_FORMAT(calldate, '%H:%i:%s')) between '09:00:00' and '23:59:59') and
                                                                                        (ido=".$ido.")  and
                                                                                        (disposition='ANSWERED') and
                                                                                        (dayname(calldate) = 'Saturday' or
                                                                                        dayname(calldate) = 'Sunday' or
                                                                                        DATE_FORMAT(calldate,'%Y-%m-%d') in (select fecha from feriado))" ;

                                                                 $result_sqli=mysqli_query($connection,$sqlii);

                                                                 while ($re= mysqli_fetch_assoc($result_sqli))
                                                                 {
                                                                     $s_re=$re['segundo'];
                                                                 }
                                                                 // se almacena las variables en s_re (suma de segundos) l_re (cant de llamadas);

                                                                 // consulta para horario nocturno
                                                                 $mysqli="select
                                                                              sum(billsec) as segundo
                                                                         from cdr
                                                                                  WHERE
                                                                                         (calldate between '".$desde." 00:00:00' and '".$hasta." 08:59:59') AND
                                                                                         ((DATE_FORMAT(calldate, '%H:%i:%s')) BETWEEN '00:00:00' and '08:59:59') and
                                                                                         (ido=".$ido.")  and
                                                                                         (disposition='ANSWERED')" ;

                                                               $resultado_sql=mysqli_query($connection,$mysqli);

                                                               while ($noc=mysqli_fetch_assoc($resultado_sql))
                                                               {
                                                                 $s_noc=$noc['segundo'];
                                                               }

                                                               $monto_no  = $s_no * $normal;
                                                               $monto_re  = $s_re * $reducido;
                                                               $monto_noc = $s_noc * $nocturno;

                                                               $monto_total=0;

                                                               $monto_total=$monto_no+$monto_re+$monto_noc;
                                                               $monto_total_cobrar=$monto_total_cobrar+$monto_total;
                                                          }
                                                   ?>

                                    <td><?php echo $monto_total_cobrar ;?></td>

                              </tr>
                       <?php
                            }
                        ?>
                    </tbody>
              </table>



      </div>
      <script type="text/javascript" src="js/jQuery-3.3.1/jquery-3.3.1.min.js"></script>
      <script src="js/popper.min.js"> </script>
      <script type="text/javascript" src="js/agregarCampo.js"> </script>
      <script src="js/bootstrap.min.js"> </script>

      <script src="js/showUser.js"></script>

      <script type="text/javascript" src="DataTables/datatables.js"></script>
      <script type="text/javascript" src="js/DataTable.js"></script>
</body>
</html>
