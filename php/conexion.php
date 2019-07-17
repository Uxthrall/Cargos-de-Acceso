<?php

    function connectDB_PDO()
    {
        $servername="127.0.0.1";
        $username="root";
        $password="";
        $database='PSTN';

        $sql = "mysql:host=$servername;dbname=$database;charset=utf8";
        $dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        // Create a new connection to the MySQL database using PDO, $my_Db_Connection is an object
        try {
           $con = new PDO($sql, $username, $password, $dsn_Options);
          echo "Connected successfully";
        } catch (PDOException $error) {
          echo 'Connection error: ' . $error->getMessage();
        }
        return $con;
    }

    function connectDB(){
      // //$server="10.200.125.244";
      // //$user="pstn";
      // //$pass="h273h9hj73er";
      // //$db="PSTN";
        $server = "127.0.0.1";
        $user = "root";
        $pass = "";
        $bd = "PSTN";

        $conexion = mysqli_connect($server, $user, $pass,$bd)
            or die("Ha sucedido un error inexperado en la conexion de la base de datos");

        return $conexion;

    }

 ?>
