<?php
    date_default_timezone_set('Asia/Bangkok'); 
    include("../../inc/fun_connect.php"); 
    include("../../inc/fun_main.php");
    
    header("Content-type:text/html; charset=UTF-8");
    header("Content-Disposition: attachment; filename=B.xls");
    header("Content-Type: application/vnd.ms-excel;");
    header("Pragma: no-cache");
    header("Expires: 0");



    $wh_code        = '001';
    $month          = date("m"); 
    $year           = date("Y");//'2022';//$_POST["year"]; 
   
    $query_on = "  SELECT  A.WHCODE, A.FSCODE, EE.APPBranchname, EE.M_BranchID_App, A.PRODCODE, D.M_SUKID_APP, C.PRODNAM1, C.SR_EXREMARKS AS 'SALESREMARK',
                    ISNULL((Case when A.ONHAND <= '0' Then '0' Else A.ONHAND End),'0')  AS 'ONHAND'
                    FROM  All_Fascino.dbo.STOCKBAL AS A   
                    INner JOIN Map_SKU AS D  ON D.M_SKUID_PRO = A.PRODCODE 
                    INner Join Map_Branch as EE ON EE.M_BranchID_Fas = A.FSCODE 
                    INNER JOIN  SET_BRANCH AS B ON B.BRCODE = A.FSCODE 
                    inner JOIN  PRODUCTMS AS C ON C.PRODCODE = A.PRODCODE
                    where A.MONTHS =  '".$month."'
                    AND A.YEARS ='".$year."'
                    AND  A.WHCODE = '".$wh_code."'  " ; 

    $dd_file    = ' <table border="1"> 
                        <thead>
                            <tr>
                                <th>WHCODE</th>
                                <th>FSCODE </th> 
                                <th>M_BranchID_App</th>
                                <th>APPBranchname</th>
                                <th>PRODCODE</th>
                                <th>M_SUKID_APP</th>
                                <th>PRODNAM1</th>
                                <th>ONHAND</th>
                            </tr>
                        </thead>
                    <tbody> ';    
                    foreach ($conn_hq->query($query_on) as $onhand) {
                        if($onhand["ONHAND"] == "0.00"){
                            $onhandd    = "0.00";
                        }else{
                            $onhandd    = $onhand["ONHAND"];
                        }
                        if($onhand["WHCODE"] = "001"){
                            $hand      = "'001";
                        } 
                        if($onhand["M_BranchID_App"] != ""){
                           $brandID     =  $onhand["M_BranchID_App"];
                        }
    $dd_file    .= '<tr>
                        <td>'.$hand.'</td>
                        <td>'.$onhand["FSCODE"].'</td>
                        <td>'.$brandID.'</td>
                        <td>'.$onhand["APPBranchname"].'</td>
                        <td>'.$onhand["PRODCODE"].'</td>
                        <td>'.$onhand["M_SUKID_APP"].'</td>
                        <td>'.$onhand["PRODNAM1"].'</td>
                        <td>'.$onhandd.'</td>
                    </tr>';
                    }  
    $dd_file    .= ' </tbody></table>';
    echo $dd_file;
    $conn_hq = null;
    // $srcFile        = "onhand_app_".date("Ymd")."_".date("His").'.xls';
    // $ourFileName    = "attachment/".$srcFile;
	// $ourFileHandle  = fopen($ourFileName, 'wb') or die("can't open file");
	// $filename_send  = fwrite($ourFileHandle, $dd_file); 
    
   
?>
