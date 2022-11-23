<?php
    ob_start();
    session_start(); 
    @error_reporting(E_ALL ^ E_NOTICE);  
    include("../inc/fun_connect.php"); 
    $ipaddress 		    = $_SERVER["REMOTE_ADDR"]; 

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
    /*
    if(isset($jdedata->{'PO-651100003000612'})){
      echo $jdedata->{'PO-651100003000612'}->SALESID;
    }else{
      echo "notset";
    }
    die();*/
?>
     
    <nav class="navbar navbar-expand-lg flex-md-nowrap">
        <form name="frmMain" method="post" target="_blank" action="../processControl/frm_process_PO_PA_export.php">
              <span class="navbar-text pe-4 ps-2">
                        <div class="btn-toolbar ">
                            <div class="btn-group me-2">
                              <input name="Export"  type="submit" class="btn btn-sm btn-outline-secondary" value="Export">
                            </div>
                        </div>
              </span>
                          <input type="hidden" name="branch" id="branch" value="<?php echo $_POST["branch"];?>">
                          <input type="hidden" name="monthselected" id="monthselected" value="<?php echo $_POST["monthselected"];?>">
                          <input type="hidden" name="yearselected" id="yearselected" value="<?php echo $_POST["yearselected"];?>">
                          <input type="hidden" name="report" id="report" value="Detail">
              </form>
    </nav>  
    


    <div class="tab-pane show active" id="Detail" role="tabpanel" aria-labelledby="Detail-tab">
      <table class="table table-striped table-sm">
                <thead>
                  <tr>
                    <th scope="col">Branch Code</th>
                    <th scope="col">Branch Name</th>
                    <th scope="col">PO Date</th>
                    <th scope="col">Sales ID</th>
                    <th scope="col">IVZ HQREFID</th>
                    <th scope="col">PO</th>
                    <th scope="col">Invoice ID</th>
                    <th scope="col">Item ID</th>
                    <th scope="col">Item Name</th>
                    <th scope="col">Sale Unit</th>
                    <th scope="col" class="text-right">POS QTY</th>
                    <th scope="col" class="text-right">AX QTY</th>
                  </tr>
                </thead>
                <tbody>
                <?php while ($result = $exsql->fetch(PDO::FETCH_ASSOC)) {
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
                ?>
                  <tr>
                    <td><?php echo $result["BRANCH_CODE"]; ?></td>
                    <td><?php echo $result["BRANCH_NAME"]; ?></td>
                    <td><?php echo date_format(date_create($result["PODATE"]),"d/m/Y"); ?></td>
                    <td><?php echo $SALESID; ?></td>
                    <td><?php echo $IVZ_HQREFID; ?></td>
                    <td><?php echo $result["PONO"]; ?></td>
                    <td><?php echo $INVOICEID; ?></td>
                    <td><?php echo $result["PRODCODE"]; ?></td>
                    <td><?php echo $result["PRODNAM1"]; ?></td>
                    <td><?php echo $SALESUNIT; ?></td>
                    <td class="text-right"><?php echo number_format($result["QTY_POS"],2); ?></td>
                    <td class="text-right"><?php echo number_format($AX_QTY,2); ?></td>
                  </tr>
                <?php } ?>
                </tbody>
        </table>
    </div>


 