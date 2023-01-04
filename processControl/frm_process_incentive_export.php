<?php

    ob_start();
    @error_reporting(E_ALL ^ E_NOTICE);  
    $serverName 	= '192.168.2.106';
	$userName 		= 'sa';
	$userPassword 	= 'saf2007';
	$dbName 		= 'All_Fascino';
	
	try{
		$conn = new PDO("sqlsrv:server=$serverName ; Database = $dbName", $userName, $userPassword);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "OK Connect" .$dbName;
	}
	catch(Exception $e){
		die(print_r($e->getMessage()));
		//echo "No Connect"  .$dbName;
	} 
    $ipaddress 		    = $_SERVER["REMOTE_ADDR"]; 

// include composer autoload
require '../vendor/autoload.php';
$typereport = $_POST["report"];
if($typereport == 'Detail'){
    $sql = @"SELECT FSCODE,
                    FS_NAME,
                    PRODCODE1,
                    PRODDESC,
                    Product_Type,
                    STDUNIT,
                    SALE_NEW,
                    SALENAME,
                    cast( SUM ( QTYSTK ) as FLOAT) AS QTY,
                    cast(INCENTIVE as FLOAT) as INCENTIVE,
                    cast(SUM ( INC_QTY ) as FLOAT) AS  INC_QTY 
             FROM ViewBI_INCENTIVEOPTF1_CALINCENTEIVE_BY_NORN WHERE FSCODE = LTRIM(RTRIM('" . $_POST["branch"] . "')) AND MONTH_NAME = '" . $_POST["monthselected"] . @"'
             AND Status_type = '" . $_POST["statusselected"] . @"' AND YEARS = '" . $_POST["yearselected"] . @"'  
             GROUP BY
                FSCODE,
                FS_NAME,
                PRODCODE1,
                PRODDESC,
                Product_Type,
                STDUNIT,
                SALE_NEW,
                SALENAME,
                INCENTIVE
            ORDER BY
                PRODCODE1";
    $exsql = $conn->prepare($sql);
    $exsql->execute();
}else{
    $sql = @"SELECT
                a.SALE_NEW,
                (SELECT cast(ISNULL(SUM(b.INC_QTY), 0) as FLOAT)  FROM ViewBI_INCENTIVEOPTF1_CALINCENTEIVE_BY_NORN b WHERE b.FSCODE = a.FSCODE AND b.MONTH_NAME = a.MONTH_NAME AND b.SALE_NEW = a.SALE_NEW AND b.YEARS = a.YEARS AND b.Product_Type = 'HB' AND b.Status_type = a.Status_type) AS 'HB',
                (SELECT cast(ISNULL(SUM(b.INC_QTY), 0) as FLOAT) FROM ViewBI_INCENTIVEOPTF1_CALINCENTEIVE_BY_NORN b WHERE b.FSCODE = a.FSCODE AND b.MONTH_NAME = a.MONTH_NAME AND b.SALE_NEW = a.SALE_NEW AND b.YEARS = a.YEARS AND b.Product_Type = 'YP' AND b.Status_type = a.Status_type) AS 'YP'
              FROM
                ViewBI_INCENTIVEOPTF1_CALINCENTEIVE_BY_NORN a
              WHERE
                a.FSCODE = LTRIM(RTRIM('" . $_POST["branch"] . @"')) 
                AND a.MONTH_NAME = '" . $_POST["monthselected"] . @"' 
                AND a.YEARS = '" . $_POST["yearselected"] . @"' 
                AND a.Status_type = '" . $_POST["statusselected"] . @"'
              GROUP BY a.SALE_NEW,a.FSCODE,a.Status_type,a.YEARS,a.MONTH_NAME
              ORDER BY a.SALE_NEW";
    $exsql = $conn->prepare($sql);
    $exsql->execute();
}




// import the PhpSpreadsheet Class
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadSheet = new Spreadsheet();
$spreadSheet->removeSheetByIndex(0);

if($typereport == 'Detail'){
    $spreadSheet->createSheet();
    $customcell = $spreadSheet->setActiveSheetIndex(0);
    $customcell->setTitle('Picking Detail');
    $customcell->getDefaultColumnDimension();
    
    $customcell->setCellValue('A1','รหัสสาขา');
    $customcell->setCellValue('B1','สาขา');
    $customcell->setCellValue('C1','Product Code');
    $customcell->setCellValue('D1','Product Name');
    $customcell->setCellValue('E1','Product Type');
    $customcell->setCellValue('F1','Std Unit');
    $customcell->setCellValue('G1','Sale Code');
    $customcell->setCellValue('H1','Sale Name');
    $customcell->setCellValue('I1','QTY');
    $customcell->setCellValue('J1','Incen Per Unit');
    $customcell->setCellValue('K1','Total Incentive');
    $customcell->getStyle('A1:K1')->getAlignment()->setHorizontal('center');

    $customcell->getColumnDimension('A')->setAutoSize(true);
    $customcell->getColumnDimension('B')->setAutoSize(true);
    $customcell->getColumnDimension('C')->setAutoSize(true);
    $customcell->getColumnDimension('D')->setAutoSize(true);
    $customcell->getColumnDimension('E')->setAutoSize(true);
    $customcell->getColumnDimension('F')->setAutoSize(true);
    $customcell->getColumnDimension('G')->setAutoSize(true);
    $customcell->getColumnDimension('H')->setAutoSize(true);
    $customcell->getColumnDimension('I')->setAutoSize(true);
    $customcell->getColumnDimension('J')->setAutoSize(true);
    $customcell->getColumnDimension('K')->setAutoSize(true);

    $i=2;
    while($result = $exsql->fetch( PDO::FETCH_ASSOC ))
    {
        $customcell->setCellValue('A' . $i,$result["FSCODE"]);
        $customcell->getStyle('A' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('B' . $i,$result["FS_NAME"]);
        $customcell->getStyle('B' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('C' . $i,$result["PRODCODE1"]);
        $customcell->getStyle('C' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('D' . $i,$result["PRODDESC"]);
        $customcell->getStyle('D' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('E' . $i,$result["Product_Type"]);
        $customcell->getStyle('E' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('F' . $i,$result["STDUNIT"]);
        $customcell->getStyle('F' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('G' . $i,$result["SALE_NEW"]);
        $customcell->getStyle('G' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('H' . $i,$result["SALENAME"]);
        $customcell->getStyle('H' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('I' . $i,$result["QTY"]);
        $customcell->getStyle('I' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $customcell->getStyle('I' . $i)->getAlignment()->setHorizontal('right');

        $customcell->setCellValue('J' . $i,$result["INCENTIVE"]);
        $customcell->getStyle('J' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $customcell->getStyle('J' . $i)->getAlignment()->setHorizontal('right');

        $customcell->setCellValue('K' . $i,$result["INC_QTY"]);
        $customcell->getStyle('K' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $customcell->getStyle('K' . $i)->getAlignment()->setHorizontal('right');

        $i++;
    }
    $customcell->getStyle('A1:K'.$i-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
}else{
    $spreadSheet->createSheet();
    $customcell = $spreadSheet->setActiveSheetIndex(0);
    $customcell->setTitle('Picking Summary');
    $customcell->getDefaultColumnDimension();
    
    $customcell->setCellValue('A1','Sale Code');
    $customcell->setCellValue('B1','HB');
    $customcell->setCellValue('C1','YP');
    $customcell->getStyle('A1:C1')->getAlignment()->setHorizontal('center');

    $customcell->getColumnDimension('A')->setAutoSize(true);
    $customcell->getColumnDimension('B')->setAutoSize(true);
    $customcell->getColumnDimension('C')->setAutoSize(true);

    $i=2;
    while($result = $exsql->fetch( PDO::FETCH_ASSOC ))
    {
        $customcell->setCellValue('A' . $i,$result["SALE_NEW"]);
        $customcell->getStyle('A' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('B' . $i,$result["HB"]);
        $customcell->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $customcell->getStyle('B' . $i)->getAlignment()->setHorizontal('right');

        $customcell->setCellValue('C' . $i,$result["YP"]);
        $customcell->getStyle('C' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $customcell->getStyle('C' . $i)->getAlignment()->setHorizontal('right');

        $i++;
    }
    $customcell->getStyle('A1:C'.$i-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
}



  
$writer = new Xlsx($spreadSheet);
 
// ชื่อไฟล์
$file_export= "SALE_INCENTIVE_".date("dmY_Hs");
 
 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file_export.'.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit(); 

?>