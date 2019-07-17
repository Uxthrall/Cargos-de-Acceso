<?php

 require ('conexion.php');

 $con = connectDB_PDO();
//se prepara la consulta
$my_Insert_Statement = $con->prepare("INSERT INTO price (id_date,desde,hasta,normal,reducido,nocturno) VALUES (:id_date,:desde,:hasta,:normal,:reducido,:nocturno)");

// Ahora las variable declaradas en la insercion son remplazadas por las varaibles locales. Los Datos son iguales
$my_Insert_Statement->bindParam(':id_date', $id_date);
$my_Insert_Statement->bindParam(':desde', $desde);
$my_Insert_Statement->bindParam(':hasta', $hasta);
$my_Insert_Statement->bindParam(':normal', $h_normal);
$my_Insert_Statement->bindParam(':reducido', $h_reducido);
$my_Insert_Statement->bindParam(':nocturno', $h_nocturno);


if (isset($_POST['mostrar'])) {
// //Captura datos enviado por POST y se modifica con variables locales

      $since=$_POST['desde'];
      $until=$_POST['hasta'];
      $normal=$_POST['normal'];
      $reducido=$_POST['reducido'];
      $nocturno=$_POST['nocturno'];

    for ($i=0; $i < count($since); $i++)
    {
      $date=date_create($since[$i]);
      $id_date= date_format($date,"Ym");
    
      $desde=$since[$i];
      $hasta=$until[$i];
      $h_normal=$normal[$i];
      $h_reducido=$reducido[$i];
      $h_nocturno=$nocturno[$i];

      $consulta = $con->prepare( "
                              SELECT
                              			*
                              FROM price
                              			WHERE 	(DATE_FORMAT(desde,'%Y-%m')=:ANNOMES) and
                              						  (desde<=:desde AND hasta>=:hasta)" );
      $ANNOMES= date_format($date,"Y-m");

      $consulta->bindParam(':ANNOMES', $ANNOMES);
      $consulta->bindParam(':desde',   $desde);
      $consulta->bindParam(':hasta',   $hasta);


      //comando para ejecutar el query
      $consulta->execute();

      $datos = $consulta->fetch();

      if($datos!=null)
      {
          echo "Los valores se encuentran Registrado";
      }
      else
      {
        //comando para ejecutar el query
          if ($my_Insert_Statement->execute())
          {
            echo "Registrado";
          }
          else
          {
            echo "No Registrado";
          }
      }


    }
}






?>
