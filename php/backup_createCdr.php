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
$ido=$_POST['id_ido'];

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Calldate (Fecha)')
            ->setCellValue('B1', 'Src (Origen)')
            ->setCellValue('C1', 'Dst (Destino)')
            ->setCellValue('D1', 'Billsec (Segundos)');
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($center);

$f=2;
for ($i=0; $i < count($since) ; $i++) {

     $fecha1 =  date_create($since[$i]);
     $fecha2 =  date_create($until[$i]);
     $desde = date_format($fecha1, "Y-m-d");
     $hasta = date_format($fecha2, "Y-m-d");

    $sql="select calldate,
          		 ido,
          		 dst,
          		 billsec
          FROM cdr
          			WHERE 	(calldate BETWEEN '".$desde." 00:00:00' and '".$hasta." 23:59:59') and
          						  (ido=".$ido.") ;";
    $result= mysqli_query($con,$sql);


    while ($valor=mysqli_fetch_assoc($result)) {

      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue("A$f", $valor['calldate'])
                  ->setCellValue("B$f", $valor['ido'])
                  ->setCellValue("C$f", $valor['dst'])
                  ->setCellValue("D$f", $valor['billsec']);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$f.':D'.$f.'')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_BIG_NUMBER);
      $objPHPExcel->getActiveSheet()->getStyle('A'.$f.':D'.$f.'')->applyFromArray($todoBordes);
      $f++;

    }
}


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


$objPHPExcel->getActiveSheet()->setTitle('Informe CDR');

$objPHPExcel->setActiveSheetIndex(0);

getHeadersCDR();

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
// FIN ;)
