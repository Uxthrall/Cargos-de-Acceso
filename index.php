<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8">
    <title>CCAA</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="js/jquery-3.3.1.min.js"> </script>
    <script src="js/popper.min.js"> </script>
    <script src="js/bootstrap.min.js"> </script>
    <script src="js/agregarCampo.js"> </script>
    <script src="js/showUser.js"></script>
    <script src="js/showTablePrice.js"></script>
    <?php
      require ('php/conexion.php');
      $con = connectDB();
    ?>

</head>
<body>
  <div class="pos-f-t">
      <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-light p-4">
              <div class="container">


              <div class="col-md-16 order-md-1 bg-light">
                <!-- formulario a ingresa -->
                <h4 class="mb-3">Liquidación de Cargos de Acceso:</h4>
                <form class="needs-validation"   onsubmit="return Validar();" name='formulario' method='POST' id="table" enctype="multipart/form-data"  novalidate >

                      <!-- ===================================================== -->
                      <div class="col-md-6">

                          <label for="company">Compañia Origen</label>
                          <?php  ?>
                          <select class="custom-select d-block w-100" name="users" id="users" value="1" onchange="showUser(this.value)">
                            <option value="" selected disabled>Orden Ascendente:</option>
                            <option value="1">Por IDO</option>
                            <option value="2">Por Nombre</option>
                          </select>
                            <br>
                            <!-- aqui es gracias a la magia de showUSer.js  -->
                            <div id="txtHint"><b>Listado de Operadores</b></div>
                            <br>
                            <!-- con js muestra lo contenido en getuser.php  -->
                      </div>
                      <!-- ====================================================== -->



                      <!-- <div class="col-md-4"> -->
                      <div class="col-md-6">
                        <select id="mySelect" class="custom-select d-block w-100" onchange="showTablePrice(this.value);">

                          <?php

                          $meses= Array("January"  => "Enero",
                                        "February"  => "Febrero",
                                        "March"  => "Marzo",
                                        "April"  => "Abril",
                                        "May"  => "Mayo",
                                        "June"  => "Junio",
                                        "July"  => "Julio",
                                        "August"  => "Agosto",
                                        "September"  => "Septiembre",
                                        "October" => "Octubre ",
                                        "November"  => "Noviembre",
                                        "December"  => "Diciembre");

                            $sql=" SELECT DISTINCT  DATE_FORMAT(desde,'%M') as MES, DATE_FORMAT(desde,'%Y') as ANHO, id_date,tipo FROM PRICE ORDER BY desde ASC";

                            $resultado=mysqli_query($con,$sql);

                            if ($resultado)
                            {
                              ?><option value="" selected disabled>Seleccione Periodo</option><?php

                              while ($value=mysqli_fetch_assoc($resultado))
                              {
                                    $MES=utf8_encode($value['MES']);
                                    $ANHO=$value['ANHO'];
                                    $ID_DATE=$value['id_date'];
                                    $tipo=$value['tipo'];
                          ?>
                                <p><?php echo $ID_DATE; ?></p>
                                      <option value="<?php echo $tipo.$ID_DATE; ?>"> <?php echo $tipo." - ".$meses[$MES]." del ".$ANHO ?></option>


                          <?php


                              }
                            }
                            else
                            {
                              ?><option value="" selected disabled> Error de conexion</option><?php
                            }
                           ?>
                           </select>

                      </div>

                        <!-- =================================================================   -->
                        <br>
                          <div id="txtTable"><b>Periodo Seleccionado</b></div>
                          <br>

                        <button class="btn btn-primary btn-lg " type="submit" name="mostrar" onclick=this.form.action="index.php"   > Mostrar</button>



                    <!-- =========================================================================== -->
                </form>
              </div>
              </div>
              <br>

        </div>
      </div>

      <nav class="navbar navbar-dark bg-dark">
        <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>
      </nav>
  </div>

      <!-- **************************************************************************************** -->


      <div class="container">
        <br>
          <?php

          // se mostrara hasta que se "click" en btn mostrar
          if (isset($_POST['mostrar'])) {



            // se captura los datos ingresado en form
            $since=$_POST['desde'];
            $until=$_POST['hasta'];
            $normal=$_POST['normal'];
            $reducido=$_POST['reducido'];
            $nocturno=$_POST['nocturno'];

            $ido=$_POST['ido'];


            $query="SELECT nom_op FROM operador WHERE ido=".$ido." ;";
            $resu=mysqli_query($con,$query);
            $nom=0;
            while ($name=mysqli_fetch_assoc($resu))
            {
              $nom=utf8_encode($name['nom_op']);
            }



          ?>

          <h3>Liquidación de cargo de acceso tráfico recibido en red Net Uno</h1>


            <!-- es el detalle del encabezado -->
          <h5>Operador: <?php echo $nom; ?> (<?php echo $ido ?>)</h5>
          <h5>Periodo: <?php  for ($i=0; $i < 1 ; $i++){ $fecha =  date_create($since[$i]);   $MES = date_format($fecha, "F");  echo $meses[$MES]." del ".date_format($fecha, "o"); } ?></h5>
          <h5>Servicios: CCAA </h5>
            <br>


            <!-- PRIMERA TABLA A MOSTRAR -->
          <?php
            if (isset($_POST['mostrar']))
            {


          ?>
              <table class="table table-striped">
              <thead>

                <tr>
                  <th scope="col">Tramo Tarifario  </th>
                  <th scope="col">Horario Normal</th>
                  <th scope="col">Horario Reducido</th>
                  <th scope="col">Horario Nocturno</th>
                </tr>
              </thead>
              <tbody>
                <?php

                  $r=0;
                  for ($i=0; $i < count($since) ; $i++)
                  {
                      $r++;
                      $fecha =  date_create($since[$i]);
                      $desde1 = date_format($fecha, "m-d");
                      $fecha =  date_create($until[$i]);
                      $hasta1 = date_format($fecha, "m-d");
                ?>
<!-- OBTIENE LO INGRESADO EN EL FORMULARIO CAPTURA CON POST[] E IMPRIME EN ESTA TABLA -->
                <tr>
                  <td><?php echo $desde1; ?> al <?php  echo $hasta1; ?></td>
                  <td><?php echo $normal[$i]; ?></td>
                  <td><?php echo $reducido[$i]; ?></td>
                  <td><?php echo $nocturno[$i]; ?></td>
                </tr>
            <?php
                }
             ?>
              </tbody>
            </table>
          <?php
            }
           ?>
           <!-- SEGUNDA TABLA CON 3 CONSULTAS NORMAL REDUCIDO NOCTURNO -->
           <br>
           <table class="table table-striped">
             <thead>
               <tr>
                 <th scope="col" rowspan="2">Tramo Tarifario</th>
                 <th scope="col" colspan="3" class="text-center" >Segundos</th>
                 <th scope="col" colspan="3" class="text-center" >Llamadas</th>
                 <th scope="col" colspan="3" class="text-center" >Monto $</th>
              </tr>
              <tr>
                <th>Normal</th>
                <th>Reducido</th>
                <th>Nocturno</th>
                <th>Normal</th>
                <th>Reducido</th>
                <th>Nocturno</th>
                <th>Normal</th>
                <th>Reducido</th>
                <th>Nocturno</th>
              </tr>
             </thead>
             <tbody>
               <?php
               // SE INICIALIZA
                    $s_no=0;
                    $s_re=0;
                    $s_noc=0;
                    $l_no=0;
                    $l_re=0;
                    $l_noc=0;
                 ?>
               <?php

                 for ($i=0; $i < count($since) ; $i++)
                 {
                   $fecha1 =  date_create($since[$i]);
                   $fecha2 =  date_create($until[$i]);
                    $desde = date_format($fecha1, "o-m-d");
                    $hasta = date_format($fecha2, "o-m-d");

                    // consulta de horario normal
                   $sql="select
                         		sum(billsec) as segundo,
                            COUNT(calldate) as llamadas
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
                    $result=mysqli_query($con,$sql);

                    while ($no=mysqli_fetch_assoc($result))
                    {
                      $s_no=$no['segundo'];
                      $l_no=$no['llamadas'];
                    }
                    // se almacena las variables en s_no (suma de segundos) l_no(cant de llamadas);

                    // consulta para horario reducido
                    $sqli= " select
                                  sum(billsec) as segundo,
                                  COUNT(calldate) as llamadas
                            from cdr
                                      WHERE
                                             (calldate between '".$desde." 09:00:00' and '".$hasta." 23:59:59') and
                                             ((DATE_FORMAT(calldate, '%H:%i:%s')) between '09:00:00' and '23:59:59') and
                                             (ido=".$ido.")  and
                                  					 (disposition='ANSWERED') and
                                						 (dayname(calldate) = 'Saturday' or
                                						 dayname(calldate) = 'Sunday' or
                                						 DATE_FORMAT(calldate,'%Y-%m-%d') in (select fecha from feriado));" ;

                      $result_sqli=mysqli_query($con,$sqli);

                      while ($re= mysqli_fetch_assoc($result_sqli))
                      {
                          $s_re=$re['segundo'];
                          $l_re=$re['llamadas'];
                      }
                      // se almacena las variables en s_re (suma de segundos) l_re (cant de llamadas);

                      // consulta para horario nocturno
                      $mysqli="select
                                   sum(billsec) as segundo,
                                   COUNT(calldate) as llamadas
                              from cdr
                                       WHERE
                                              (calldate between '".$desde." 00:00:00' and '".$hasta." 08:59:59') AND
                                              ((DATE_FORMAT(calldate, '%H:%i:%s')) BETWEEN '00:00:00' and '08:59:59') and
                                              (ido=".$ido.")  and
                                              (disposition='ANSWERED');" ;

                    $resultado_sql=mysqli_query($con,$mysqli);

                    while ($noc=mysqli_fetch_assoc($resultado_sql))
                    {
                      $s_noc=$noc['segundo'];
                      $l_noc=$noc['llamadas'];
                    }
                    // se almacena las variables en s_noc (suma de segundos) l_noc (cant de llamadas);

                    $desde = date_format($fecha1, "m-d");
                    $hasta = date_format($fecha2, "m-d");
               ?>
               <!-- TODOH LO CAPTURADO POR LAS CONSULTAS Y POST ES IMPRESO AQUI (SON DATOS DE LA 2 TABLA)-->
                     <tr>
                          <td><?php echo $desde; ?> al <?php  echo $hasta; ?></td></td>
                          <!-- segundos -->
                          <td><?php echo $s_no; ?></td>
                          <td><?php echo $s_re; ?></td>
                          <td><?php echo $s_noc; ?></td>
                          <!-- Llamadas -->
                          <td><?php echo $l_no; ?></td>
                          <td><?php echo $l_re; ?></td>
                          <td><?php echo $l_noc; ?></td>
                          <!-- Monto $ -->
                          <td>$ <?php $monto_no  = $s_no * $normal[$i];    echo round($monto_no); ?> </td>
                          <td>$ <?php $monto_re  = $s_re * $reducido[$i];  echo round($monto_re); ?></td>
                          <td>$ <?php $monto_noc = $s_noc * $nocturno[$i]; echo round($monto_noc);?></td>
                          <!-- total -->
                          <?php
                          ?>
                     </tr>

              <?php
              // PARA EVITAR ERRORES. EL CICLO SET LAS VARIABLES IMPRESA EN 0. ADEMAS se deja GUARDADOS EN OTRA VARIABLE POR EJ:($NOR[]) para uqe calcule los totales y mandar en form de descargar
              // que se detalla mas abajo
                    $nor[]=$s_no;
                    $red[]=$s_re;
                    $noct[]=$s_noc;

                    $l_normal[]=$l_no;
                    $l_reducido[]=$l_re;
                    $l_nocturno[]=$l_noc;

                    $montoNormal[]=$monto_no;
                    $montoReducido[]=$monto_re;
                    $montoNocturno[]=$monto_noc;

                    $s_no=0;
                    $l_no=0;
                    $s_re=0;
                    $l_re=0;
                    $s_noc=0;
                    $l_noc=0;

                }

                // SE REALIZA SUMADORES EN LAS VARIABLES GUARDAS PARA CAPTURAR LOS TOTALES DE CADA HORARIO Y UN TOTAL

                // SEGUNDOS !!
                $total_normal=0;
                foreach ($nor as $no )
                {
                  $total_normal=$total_normal+$no;
                }

                $total_reducido=0;
                foreach ($red as $re)
                {
                  $total_reducido=$total_reducido+$re;
                }

                $total_noctur=0;
                foreach ($noct as $no )
                {
                  $total_noctur=$total_noctur+$no;
                }
                $total_seg=$total_normal+$total_reducido+$total_noctur;
                // TOTAL Y FIN DE SEGUNDOS AHORA COMIENZA CANT DE LLAMADAS

                // LLAMADAS!!
                $total_L_normal=0;
                foreach ($l_normal as $l_nor)
                {

                  $total_L_normal=$total_L_normal+$l_nor;
                }

                $total_L_reducido=0;
                foreach ($l_reducido as $l_re)
                {
                  $total_L_reducido=$total_L_reducido+$l_re;
                }

                $total_L_nocturno=0;
                foreach ($l_nocturno as $l_noct)
                {
                  $total_L_nocturno=$total_L_nocturno+$l_noct;
                }
                $totaLlamadas=$total_L_normal+$total_L_reducido+$total_L_nocturno;
                // TOTAL DE LLAMDAS Y FIN DE LLAMADAS AHORA COMIENZA TOTAL MONTO

                // MONTO!!
                $monto_total_normal=0;
                foreach ($montoNormal as $monto_normal ) {
                  $monto_total_normal=$monto_total_normal+$monto_normal;
                }
                $monto_total_reducido =0;
                foreach ($montoReducido as $monto_reducido ) {
                  $monto_total_reducido=$monto_total_reducido+$monto_reducido;
                }
                $monto_total_nocturno=0;
                foreach ($montoNocturno as $monto_nocturno ) {
                  $monto_total_nocturno=$monto_total_nocturno+$monto_nocturno;
                }
                $total_monto=$monto_total_normal+$monto_total_reducido+$monto_total_nocturno;
                // FIN DE MONTO, TOTAL MONTO  Y FIN DE LA CAPTURA DE LOS TOTALES Y COMIENZA LA 3 TABLA
               ?>
             </tbody>

           </table>
           <br>
           <table class="table table-striped">
              <thead>
               <th>Total Periodo</th>
               <th>Normal</th>
               <th>Reducido</th>
               <th>Nocturno</th>
               <th>Total</th>
             </thead>

             <!-- SE IMPRIME LO CAPTURADO POR LOS foreach y se muestran los datos en la 3ra tabla -->
             <tr>
               <th>Segundos</th>
               <td><?php echo $total_normal ?></td>
               <td><?php echo $total_reducido; ?></td>
               <td><?php echo $total_noctur; ?></td>
               <td><?php echo $total_seg; ?></td>
             </tr>

             <tr>
               <th>Llamadas</th>
               <td><?php echo $total_L_normal; ?></td>
               <td><?php echo $total_L_reducido; ?></td>
               <td><?php echo $total_L_nocturno; ?></td>
               <td><?php echo $totaLlamadas; ?></td>
              </tr>

             <tr>
               <th>Monto $</th>
               <td>$ <?php echo round($monto_total_normal); ?></td>
               <td>$ <?php echo round($monto_total_reducido); ?></td>
               <td>$ <?php echo round($monto_total_nocturno); ?></td>
               <td>$ <?php echo round($total_monto); ?></td>
             </tr>

           </table>



           <!-- aqui guardo los datos para mandar a la pagina donde SE CREA el excel  LA CUAL SE LLAMA "createExcel.php" ubicada en la carpeta php"-->
            <form class="" action="" method="post">
                  <?php  if(isset($_POST['mostrar'])){?>
                  <input type="hidden" name="id_ido" value=<?php if(isset($_POST['mostrar'])){ echo $_POST['ido']; } ?>>


                  <?php $since=$_POST['desde'];
                      for ($i=0; $i < count($since) ; $i++){

                      ?>
                      <!-- tabla de los input generados -->
                      <input type="hidden" name="since[]" value=<?php  echo $since[$i]; ?>>
                      <input type="hidden" name="until[]" value=<?php echo $until[$i];  ?>>
                      <input type="hidden" name="normale[]" value=<?php  echo $normal[$i];  ?>>
                      <input type="hidden" name="reduced[]" value=<?php  echo $reducido[$i];  ?>>
                      <input type="hidden" name="night[]" value=<?php  echo $nocturno[$i];  ?>>
                      <!-- segunda tabla  -->
                      <input type="hidden" name="s_normal[]" value=<?php echo $nor[$i]; ?>>
                      <input type="hidden" name="s_reducido[]" value=<?php echo $red[$i]; ?>>
                      <input type="hidden" name="s_nocturno[]" value=<?php echo $noct[$i]; ?>>
                      <input type="hidden" name="l_normal[]" value=<?php echo $l_normal[$i]; ?>>
                      <input type="hidden" name="l_reducido[]" value=<?php echo $l_reducido[$i]; ?>>
                      <input type="hidden" name="l_nocturno[]" value=<?php echo $l_nocturno[$i]; ?>>
                      <input type="hidden" name="m_normal[]" value=<?php echo $montoNormal[$i]; ?>>
                      <input type="hidden" name="m_reducido[]" value=<?php echo $montoReducido[$i]; ?>>
                      <input type="hidden" name="m_nocturno[]" value=<?php echo $montoNocturno[$i]; ?>>
                      <!-- tercera tabla -->
                      <input type="hidden" name="t_normal" value=<?php echo $total_normal ;?>>
                      <input type="hidden" name="t_reducido" value=<?php echo$total_reducido  ;?>>
                      <input type="hidden" name="t_nocturno" value=<?php echo $total_noctur ;?>>
                      <input type="hidden" name="t_segundo" value=<?php echo $total_seg ;?>>

                      <input type="hidden" name="t_l_normal" value=<?php echo  $total_L_normal;?>>
                      <input type="hidden" name="t_l_reducido" value=<?php echo $total_L_reducido ; ?>>
                      <input type="hidden" name="t_l_nocturno" value=<?php echo $total_L_nocturno; ?>>
                      <input type="hidden" name="t_llamadas" value=<?php echo  $totaLlamadas;?>>

                      <input type="hidden" name="m_t_normal" value=<?php  echo $monto_total_normal;?>>
                      <input type="hidden" name="m_t_reducido" value=<?php echo $monto_total_reducido; ?>>
                      <input type="hidden" name="m_t_nocturno" value=<?php echo $monto_total_nocturno ;?>>
                      <input type="hidden" name="m_total" value=<?php echo $total_monto; ?>>

                <?php }

                  }
                 ?>
                 <!-- boton que manda a php/createExcel se detalla en onclick.. -->
                 <h3>Descargar:</h3>
                <button class="btn btn-success btn-lg " type="submit" name="descargar"  onclick=this.form.action="php/createExcel.php">CCAA</button>
                <button class="btn btn-success btn-lg " type="submit" name="descargar"  onclick=this.form.action="php/createCdr.php">CDR</button>
            </form>
            <br>
                <h3>Resumen de Trafico:</h3>
            <!-- ================================despues del resumen mostrar nuevamen los resumenes===================================== -->
            <div class="col-md-6 ">
            <form class="" onsubmit="return Validar_Mes();" method="post" enctype="multipart/form-data"  novalidate >
              <div class="row">
              <div class="col">
              <input type="month" id="mes" class="form-control" name="mes"  placeholder="YYYY-MM">
              </div>
                <div class="col">
              <button type="submit" class="btn btn-primary btn-lg " name="button" onclick=this.form.action="index.php">Mostrar</button>
                </div>
                  </div>
            </form>

            </div>
            <br>
            <?php

              if (isset($_POST['button'])) {

             ?>
             <h4><?php   echo $_POST['mes'];?>  </h5>
             <br>
            <table class="table">
              <thead>
                    <tr>
                      <th scope="col">ido</th>
                      <th scope="col">Nombre Cia</th>
                      <th scope="col">Total Segundo</th>
                    </tr>
              </thead>
              <tbody>

            <?php

                $fecha= $_POST['mes'];

                $sql="SELECT ido,
                             sum(billsec) as segundo
                      FROM cdr
                            where date_format(calldate,'%Y-%m')='".$fecha."'
                            GROUP BY ido
                            ORDER BY segundo desc;";

                $result=mysqli_query($con,$sql);

                while ($valor=mysqli_fetch_assoc($result)) {
                  $ido=$valor['ido'];
                  $segundos=$valor['segundo'];

                  $sqli="SELECT nom_op FROM operador WHERE ido= ".$ido.";";
                  // echo $sqli;
                  $resultado=mysqli_query($con,$sqli);

                  while ($value=mysqli_fetch_assoc($resultado)) {
                    $nom_op=utf8_encode($value['nom_op']);


                  }
                  ?>
                    <tr>
                      <td><?php echo $ido ;?></td>
                      <td><?php echo $nom_op ;?></td>
                      <td><?php echo $segundos ;?></td>
                    </tr>

                      <?php
                }
                    }
             ?>


               </tbody>
             </table>
             <!-- ============================================ -->
            <?php
            // aqui es el fin de if(isset($_POST['MOSTRAR'])) POR LO QUE TODOH LO MOSTRADO ANTERIORMENTE NO SE EJECUTARÁ HASTA QUE SE 'CLICK' EL BOTON MOSTRAR
          }
          else{
          // resumen de los ido/nom/totalsegundo por año y fecha
          ?>
          <h3>Resumen de Trafico:</h3>
          <div class="col-md-6 ">
            <form class="" onsubmit="return Validar_Mes();" method="post" enctype="multipart/form-data"  novalidate >
              <div class="row">
              <div class="col">
              <input type="month" id="mes" class="form-control" name="mes"  placeholder="YYYY-MM">
              </div>
                <div class="col">
              <button type="submit" class="btn btn-primary btn-lg " name="button" onclick=this.form.action="index.php">Mostrar</button>
                </div>
                  </div>
            </form>

          </div>
          <br>
          <?php

            if (isset($_POST['button'])) {

           ?>
           <h4><?php   echo $_POST['mes'];?>  </h5>
           <br>
          <table class="table">
            <thead>
                  <tr>
                    <th scope="col">ido</th>
                    <th scope="col">Nombre Cia</th>
                    <th scope="col">Total Segundo</th>
                  </tr>
            </thead>
            <tbody>

          <?php

              $fecha= $_POST['mes'];

              $sql="SELECT ido,
                           sum(billsec) as segundo
                    FROM cdr
                          where date_format(calldate,'%Y-%m')='".$fecha."'
                          GROUP BY ido
                          ORDER BY segundo desc;";

              $result=mysqli_query($con,$sql);

              while ($valor=mysqli_fetch_assoc($result)) {
                $ido=$valor['ido'];
                $segundos=$valor['segundo'];

                $sqli="SELECT nom_op FROM operador WHERE ido= ".$ido.";";
                // echo $sqli;
                $resultado=mysqli_query($con,$sqli);

                while ($value=mysqli_fetch_assoc($resultado)) {
                  $nom_op=utf8_encode($value['nom_op']);


                }
                ?>
                  <tr>
                    <td><?php echo $ido ;?></td>
                    <td><?php echo $nom_op ;?></td>
                    <td><?php echo $segundos ;?></td>
                  </tr>

                    <?php
              }
                  }
           ?>


             </tbody>
           </table>
             <?php
          }
             ?>
      </div>
      <!-- VALIDACIONES  DEL FORMULARIO-->
      <script type="text/javascript">
      function Validar(){
        // SE CAPTURA LOS DATOS CON DOCUMENT.GET...
        var users=document.getElementById('users').value;
        var desde=document.getElementById('desde').value;
        var hasta=document.getElementById('hasta').value;
        var normal=document.getElementById('normal').value;
        var reducido=document.getElementById('reducido').value;
        var nocturno=document.getElementById('nocturno').value;

        // SE MUESTRA LOS DIAS MESE Y AÑOS PARA QUE SEA SOLO UN MES LA CONSULTA
        var fechaDesde=new Date(desde);
        fechaDesde.setDate(fechaDesde.getDate() + 1);
        var dd1 = fechaDesde.getDate();
        var mm1= parseInt(fechaDesde.getMonth())+1;
        var yyyy1=fechaDesde.getFullYear();

        var fechaHasta=new Date(hasta);
        fechaHasta.setDate(fechaHasta.getDate() + 1);
        var dd2 = fechaHasta.getDate();
        var mm2= parseInt(fechaHasta.getMonth())+1;
        var yyyy2=fechaHasta.getFullYear();


        // OBLIGATORIAMENTE TODOS LOS CAMPOS NO DEBEN SER NULOS
        if (users == "" || desde=="" ||hasta=="" || normal== ""||reducido== "" ||nocturno== "" ) {

	           swal("Campos Vacios!", "Todos los campos son Obligatorio", "error");
          return false;
          // EN EL CASO DE QUE ESTEN INGRESOS AQUI VERIFICADA EL LISTADO DE OPERADORES
        }else if (users != "" || desde!="" ||hasta!="" || normal!= ""||reducido!= "" ||nocturno!= "") {

          var ido =document.getElementById('ido').value;

          if(ido=="") {
	             swal("Operador", "Ingrese algun Operador", "error");

            return false;
          }
          else if (mm1 != mm2   ) {

            swal("Error con el Mes", "Ingrese el mismo mes en los campos ", "error");
            return false;
          }
          else if ( yyyy1 != yyyy2) {

            swal("Error con el Año", "Ingrese el mismo año en los campos ", "error");
            return false;
          }
          // SE VERIFICARA QUE EL AÑO Y EL MES SEAN IGUALES PARA QUE EL PERSONAL QUE UTILIZE EL PROYECTO NO CONSULTE MAS DE UN MES Y LA CONSULTA SEA MUY LARGA
        }



      }
      function Validar_Mes(){
          var Fecha=document.getElementById('mes').value;

          if(Fecha=="") {
	             swal("Fecha Nula", "Ingrese 'Año-Mes' ", "error");

               return false;
          }
      }
      </script>
</body>
</html>
