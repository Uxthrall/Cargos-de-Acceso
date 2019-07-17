<?php
require_once 'excel.php';

activeErrorReporting();
noCli();

require_once '../PHPExcel/PHPExcel.php';
require('conexion.php');
  $con = connectDB();

$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Developero")
               ->setLastModifiedBy("Maarten Balliauw")
               ->setTitle("Office 2007 XLSX Test Document")
               ->setSubject("Office 2007 XLSX Test Document")
               ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
               ->setKeywords("office 2007 openxml php")
               ->setCategory("Test result file");

$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')
                                          ->setSize(12);
$center = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );
$bordes = array(
      'borders' => array(
        'outline' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        )
      )
    );
$todoBordes = array(
          'borders' => array(
            'allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );
$verticalBordes = array(
          'borders' => array(
            'vertical' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
            )
          )
        );

// variables del form descargar
$since=$_POST['since'];
$until=$_POST['until'];
$normal=$_POST['normale'];
$reducido=$_POST['reduced'];
$nocturno=$_POST['night'];
$ido=$_POST['id_ido'];

$s_normal=$_POST['s_normal'];
$s_reducido=$_POST['s_reducido'];
$s_nocturno=$_POST['s_nocturno'];
$l_normal=$_POST['l_normal'];
$l_reducido=$_POST['l_reducido'];
$l_nocturno=$_POST['l_nocturno'];
$m_normal=$_POST['m_normal'];
$m_reducido=$_POST['m_reducido'];
$m_nocturno=$_POST['m_nocturno'];

$t_normal=$_POST['t_normal'];
$t_reducido=$_POST['t_reducido'];
$t_nocturno=$_POST['t_nocturno'];
$t_segundo=$_POST['t_segundo'];

$t_l_normal=$_POST['t_l_normal'];
$t_l_reducido=$_POST['t_l_reducido'];
$t_l_nocturno=$_POST['t_l_nocturno'];
$t_llamadas=$_POST['t_llamadas'];

$m_t_normal=$_POST['m_t_normal'];
$m_t_reducido=$_POST['m_t_reducido'];
$m_t_nocturno=$_POST['m_t_nocturno'];
$m_total=$_POST['m_total'];

// pequeña consulta el nombre del operador

$sql="SELECT nom_op FROM operador WHERE ido=".$ido." ;";

$sql_resul=mysqli_query($con, $sql);

while ($name=mysqli_fetch_assoc($sql_resul))
{
  $nom=utf8_encode($name['nom_op']);
}

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
              "December"  => "Diciembre	");

for ($i=0; $i < 1 ; $i++){
  $fecha =  date_create($since[$i]);
  $MES = date_format($fecha, "F");
  $Periodo= $meses[$MES]." del ".date_format($fecha, "o");
  //ido_mes_año
  $nom_excel=$ido."_".$meses[$MES]."_".date_format($fecha, "o");
}


$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
$objPHPExcel->getActiveSheet()->mergeCells('A4:C4');

// primera parte de la impreciond el excel
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Liquidación de cargo de acceso tráfico recibido en red Net Uno')
            ->setCellValue('A2', 'Operador: '.$nom.' ('.$ido.')')
            ->setCellValue('A3', 'Periodo: '.$Periodo.' ')
            ->setCellValue('A4', 'Servicios: CCAA ');

// se convinan celdas
$objPHPExcel->getActiveSheet()->mergeCells('B6:D6');
$objPHPExcel->getActiveSheet()->getStyle('B6:D6')->applyFromArray($center);
$objPHPExcel->getActiveSheet()->getStyle('B6:D6')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('A7:D7')->applyFromArray($todoBordes);


// encabezado de la segunda parte detalles del input
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A7', 'Tramo Tarifario')
            ->setCellValue('B6', 'Horario')
            ->setCellValue('B7', 'Normal')
            ->setCellValue('C7', 'Reducido')
            ->setCellValue('D7', 'Nocturno');
// datos de la segunda parte
$f=8;
$a=$f;
for ($i=0; $i < count($since) ; $i++) {
  $fecha1 =  date_create($since[$i]);
  $fecha2 =  date_create($until[$i]);
   $desde = date_format($fecha1, "m-d");
   $hasta = date_format($fecha2, "m-d");

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue("A$f", $desde." al ".$hasta)
              ->setCellValue("B$f", $normal[$i])
              ->setCellValue("C$f", $reducido[$i])
              ->setCellValue("D$f", $nocturno[$i]);
  $objPHPExcel->getActiveSheet()->getStyle('B'.$f.':D'.$f.'')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);

  $b=$f;

  $f++;

}

$objPHPExcel->getActiveSheet()->getStyle('A'.$a.':A'.$b.'')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('B'.$a.':B'.$b.'')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('C'.$a.':C'.$b.'')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('D'.$a.':D'.$b.'')->applyFromArray($bordes);
$f++;
$objPHPExcel->getActiveSheet()->mergeCells('B'.$f.':D'.$f.'');
$objPHPExcel->getActiveSheet()->mergeCells('E'.$f.':G'.$f.'');
$objPHPExcel->getActiveSheet()->mergeCells('H'.$f.':J'.$f.'');
// se convinan celdas y se encuentran los encabezado de la 3ra parte del excel
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B$f", 'Segundos')
            ->setCellValue("E$f", 'Llamadas')
            ->setCellValue("H$f", 'Monto $');
$objPHPExcel->getActiveSheet()->getStyle('B'.$f.':J'.$f.'')->applyFromArray($center);
$objPHPExcel->getActiveSheet()->getStyle('B'.$f.':J'.$f.'')->applyFromArray($todoBordes);
$f++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$f", 'Tramo Tarifario')
            ->setCellValue("B$f", 'Normal')
            ->setCellValue("C$f", 'Reducido')
            ->setCellValue("D$f", 'Nocturno')
            ->setCellValue("E$f", 'Normal')
            ->setCellValue("F$f", 'Reducido')
            ->setCellValue("G$f", 'Nocturno')
            ->setCellValue("H$f", 'Normal')
            ->setCellValue("I$f", 'Reducido')
            ->setCellValue("J$f", 'Nocturno');
$objPHPExcel->getActiveSheet()->getStyle('A'.$f.':J'.$f.'')->applyFromArray($todoBordes);
$f++;
// datos del la 3ra parte del excel
$a=$f;
for ($i=0; $i < count($since); $i++) {
  $fecha1 =  date_create($since[$i]);
  $fecha2 =  date_create($until[$i]);
   $desde = date_format($fecha1, "m-d");
   $hasta = date_format($fecha2, "m-d");
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue("A$f", $desde." al ".$hasta)
              ->setCellValue("B$f", $s_normal[$i])
              ->setCellValue("C$f", $s_reducido[$i])
              ->setCellValue("D$f", $s_nocturno[$i])
              ->setCellValue("E$f", $l_normal[$i])
              ->setCellValue("F$f", $l_reducido[$i])
              ->setCellValue("G$f", $l_nocturno[$i])
              ->setCellValue("H$f", $m_normal[$i])
              ->setCellValue("I$f", $m_reducido[$i])
              ->setCellValue("J$f", $m_nocturno[$i]);
  $objPHPExcel->getActiveSheet()->getStyle('B'.$f.':G'.$f.'')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
  $objPHPExcel->getActiveSheet()->getStyle('H'.$f.':J'.$f.'')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
 // son el formato que debe tener de la ubicacion B HASTA LA G Y LA VARIABLE $f contiene la fila por eje:(B6 HASTA G6 Y H7 HASTA J7)

$f++;

}

// SE DETALLA LA 3RA PARTE DEL EXCEL LOS TOTALES, MONTOS
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$f", 'Total Periodo')

            ->setCellValue("B$f", $t_normal)
            ->setCellValue("C$f", $t_reducido)
            ->setCellValue("D$f", $t_nocturno)

            ->setCellValue("E$f", $t_l_normal)
            ->setCellValue("F$f", $t_l_reducido)
            ->setCellValue("G$f", $t_l_nocturno)

            ->setCellValue("H$f", $m_t_normal)
            ->setCellValue("I$f", $m_t_reducido)
            ->setCellValue("J$f", $m_t_nocturno);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$f.':G'.$f.'')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$f.':J'.$f.'')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
$b=$f;
$objPHPExcel->getActiveSheet()->getStyle('A'.$a.':J'.$b.'')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('B'.$a.':I'.$b.'')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('C'.$a.':H'.$b.'')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('D'.$a.':G'.$b.'')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('E'.$a.':F'.$b.'')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('F'.$a.':F'.$b.'')->applyFromArray($bordes);
$objPHPExcel->getActiveSheet()->getStyle('A'.$b.':J'.$b.'')->applyFromArray($bordes);
$f++;
$objPHPExcel->getActiveSheet()->mergeCells('B'.$f.':D'.$f.'');
$objPHPExcel->getActiveSheet()->mergeCells('E'.$f.':G'.$f.'');
$objPHPExcel->getActiveSheet()->mergeCells('H'.$f.':J'.$f.'');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("B$f", $t_segundo)
            ->setCellValue("E$f", $t_llamadas)
            ->setCellValue("H$f", $m_total)
            ->setCellValue("A$f", 'TOTAL');
$objPHPExcel->getActiveSheet()->getStyle('B'.$f.':G'.$f.'')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
$objPHPExcel->getActiveSheet()->getStyle('H'.$f.':J'.$f.'')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);

$objPHPExcel->getActiveSheet()->getStyle('B'.$f.':J'.$f.'')->applyFromArray($center);
$objPHPExcel->getActiveSheet()->getStyle('B'.$f.':J'.$f.'')->applyFromArray($todoBordes);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->setTitle('Informe Cargo Acceso');

$objPHPExcel->setActiveSheetIndex(0);

getHeaders($nom_excel);
//getHeaders();
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
// FIN ;)
