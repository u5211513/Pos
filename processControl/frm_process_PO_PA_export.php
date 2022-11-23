<?php

    ob_start();
    @error_reporting(E_ALL ^ E_NOTICE);  
    $serverName_hq 		= '192.168.2.106';
	$userName_hq 		= 'sa';
	$userPassword_hq 	= 'saf2007';
	$dbName_hq 			= 'HQ_Data';
	
	try{
		$conn_hq = new PDO("sqlsrv:server=$serverName_hq ; Database = $dbName_hq", $userName_hq, $userPassword_hq);
		$conn_hq->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "OK Connect" .$dbName;
	}
	catch(Exception $e){
		die(print_r($e->getMessage()));
		//echo "No Connect"  .$dbName;
	}
    
    $serverName_AX 		= '192.168.2.196';
	$userName_AX 		= 'sa';
	$userPassword_AX 	= 'P@ssw0rd1';
	$dbName_AX 			= 'AX50SP1_FAS';
	
	try{
		$conn_AX = new PDO("sqlsrv:server=$serverName_AX ; Database = $dbName_AX", $userName_AX, $userPassword_AX);
		$conn_AX->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

$sql = @"select 
              A.PODATE,
              MONTH(A.PODATE) AS MONTHS,
              YEAR(A.PODATE) AS YEARS,
              A.PONO,
              A.PRODCODE, 
              B.PRODNAM1,
              A.FSCODE AS 'BRANCH_CODE', 
              C.FS_NAME AS 'BRANCH_NAME',
              A.UNITPUR, 
              SUM(A.QTYORD) AS 'QTY_POS' 
              from [XMLPO_TRANS] AS A
              INNER JOIN [PRODUCTMS] AS B ON B.PRODCODE = A.PRODCODE
              INNER JOIN [SET_BRANCH] AS C ON C.BRCODE = A.FSCODE 
              where A.FSCODE = LTRIM(RTRIM('" . $_POST["branch"] . @"')) 
              AND MONTH(A.PODATE) = '" . $_POST["monthselected"] . @"' 
              and YEAR(A.PODATE) = '" . $_POST["yearselected"] . @"' 
              and a.DELETE_ID is null
              GROUP BY 	C.FS_NAME,B.PRODNAM1, A.PRODCODE, A.FSCODE, A.UNITPUR, MONTH(A.PODATE),YEAR(A.PODATE),A.PONO,A.PODATE";
    //echo $sql;
    //die();
    $exsql = $conn_hq->prepare($sql);
    $exsql->execute();

    $sql1   = @"select 
        C.INVOICEACCOUNT AS FSCODE,
        D.NAME,
        A.SALESID,
        B.IVZ_HQREFID,
        RIGHT(B.IVZ_HQREFID,12) AS 'PO',
        A.INVOICEID,
        A.ITEMID,
        A.NAME AS 'ITEMNAME',
        A.SALESUNIT,
        SUM(A.QTY) AS 'AX_QTY'
        from CUSTINVOICETRANS  AS A
        INNER JOIN CUSTINVOICEJOUR AS C ON C.DATAAREAID = A.DATAAREAID AND C.INVOICEID = A.INVOICEID
        INNER JOIN SALESTABLE AS B ON B.DATAAREAID = A.DATAAREAID AND B.SALESID = A.SALESID
        INNER JOIN CUSTTABLE AS D ON D.ACCOUNTNUM = C.INVOICEACCOUNT AND D.DATAAREAID = C.DATAAREAID
        where 
        C.INVOICEACCOUNT = LTRIM(RTRIM('" . $_POST["branch"] . @"'))  
        AND MONTH ( B.DELIVERYDATE ) = '" . $_POST["monthselected"] . @"' 
        AND YEAR ( B.DELIVERYDATE ) = '" . $_POST["yearselected"] . @"' 
        AND A.DATAAREAID in ('PMH','MTW')
        GROUP BY 
        D.NAME,
        C.INVOICEACCOUNT,
        RIGHT(B.IVZ_HQREFID,12),
        B.IVZ_HQREFID,
        A.SALESUNIT,
        A.SALESID,
        A.INVOICEID,
        A.ITEMID,
        A.NAME
        ORDER BY A.ITEMID";  
    $getax = $conn_AX->query($sql1);
    while($axdata = $getax->fetch()){
      $dataarray[$axdata["PO"].$axdata["ITEMID"]] = array(
        'SALESID'=>$axdata["SALESID"],
        'IVZ_HQREFID'=>$axdata["IVZ_HQREFID"],
        'INVOICEID'=>$axdata["INVOICEID"],
        'SALESUNIT'=>$axdata["SALESUNIT"],
        'AX_QTY'=>$axdata["AX_QTY"],
      );
    }
    $jendata = json_encode($dataarray);
    $jdedata = json_decode($jendata);



// import the PhpSpreadsheet Class
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadSheet = new Spreadsheet();
$spreadSheet->removeSheetByIndex(0);


    $spreadSheet->createSheet();
    $customcell = $spreadSheet->setActiveSheetIndex(0);
    $customcell->setTitle('POPA');
    $customcell->getDefaultColumnDimension();
    
    $customcell->setCellValue('A1','Branch Code');
    $customcell->setCellValue('B1','Branch Name');
    $customcell->setCellValue('C1','PO Date');
    $customcell->setCellValue('D1','Sales ID');
    $customcell->setCellValue('E1','IVZ HQREFID');
    $customcell->setCellValue('F1','PO');
    $customcell->setCellValue('G1','Invoice ID');
    $customcell->setCellValue('H1','Item ID');
    $customcell->setCellValue('I1','Item Name');
    $customcell->setCellValue('J1','Sale Unit');
    $customcell->setCellValue('K1','POS QTY');
    $customcell->setCellValue('L1','AX QTY');
    $customcell->getStyle('A1:L1')->getAlignment()->setHorizontal('center');

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
    $customcell->getColumnDimension('L')->setAutoSize(true);

    $i=2;
    while($result = $exsql->fetch( PDO::FETCH_ASSOC ))
    {
        $SALESID = "";
        $IVZ_HQREFID = "";
        $INVOICEID = "";
        $SALESUNIT = "";
        $AX_QTY = 0.00;
        if(isset($jdedata->{$result["PONO"].$result["PRODCODE"]})){
          $SALESID = $jdedata->{$result["PONO"].$result["PRODCODE"]}->SALESID;
          $IVZ_HQREFID = $jdedata->{$result["PONO"].$result["PRODCODE"]}->IVZ_HQREFID;
          $INVOICEID = $jdedata->{$result["PONO"].$result["PRODCODE"]}->INVOICEID;
          $SALESUNIT = $jdedata->{$result["PONO"].$result["PRODCODE"]}->SALESUNIT;
          $AX_QTY = $jdedata->{$result["PONO"].$result["PRODCODE"]}->AX_QTY;
        }
        $customcell->setCellValue('A' . $i,$result["BRANCH_CODE"]);
        $customcell->getStyle('A' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('B' . $i,$result["BRANCH_NAME"]);
        $customcell->getStyle('B' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('C' . $i,date_format(date_create($result["PODATE"]),"d/m/Y"));
        $customcell->getStyle('C' . $i)->getAlignment()->setHorizontal('right');

        $customcell->setCellValue('D' . $i,$SALESID);
        $customcell->getStyle('D' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('E' . $i,$IVZ_HQREFID);
        $customcell->getStyle('E' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('F' . $i,$result["PONO"]);
        $customcell->getStyle('F' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('G' . $i,$INVOICEID);
        $customcell->getStyle('G' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('H' . $i,$result["PRODCODE"]);
        $customcell->getStyle('H' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('I' . $i,$result["PRODNAM1"]);
        $customcell->getStyle('I' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('J' . $i,$SALESUNIT);
        $customcell->getStyle('J' . $i)->getAlignment()->setHorizontal('left');

        $customcell->setCellValue('K' . $i,$result["QTY_POS"]);
        $customcell->getStyle('K' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $customcell->getStyle('K' . $i)->getAlignment()->setHorizontal('right');

        $customcell->setCellValue('L' . $i,$AX_QTY);
        $customcell->getStyle('L' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        $customcell->getStyle('L' . $i)->getAlignment()->setHorizontal('right');

        $i++;
    }
    $customcell->getStyle('A1:L'.$i-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);


  
$writer = new Xlsx($spreadSheet);
 
// ชื่อไฟล์
$file_export= "รายงานเปรียบเทียบการสั่งPOPA".date("dmY_Hs");
 
 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file_export.'.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit(); 

?>