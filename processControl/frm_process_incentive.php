<?php
    ob_start();
    session_start(); 
    @error_reporting(E_ALL ^ E_NOTICE);  
    include("../inc/fun_connect.php"); 
    $ipaddress 		    = $_SERVER["REMOTE_ADDR"]; 

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

    $sql2 = @"SELECT
                a.SALE_NEW,
                (SELECT cast(ISNULL(SUM(b.INC_QTY), 0) as FLOAT)  FROM ViewBI_INCENTIVEOPTF1_CALINCENTEIVE_BY_NORN b WHERE b.FSCODE = a.FSCODE AND b.MONTH_NAME = a.MONTH_NAME AND b.SALE_NEW = a.SALE_NEW AND b.Product_Type = 'HB' AND b.Status_type = a.Status_type) AS 'HB',
                (SELECT cast(ISNULL(SUM(b.INC_QTY), 0) as FLOAT) FROM ViewBI_INCENTIVEOPTF1_CALINCENTEIVE_BY_NORN b WHERE b.FSCODE = a.FSCODE AND b.MONTH_NAME = a.MONTH_NAME AND b.SALE_NEW = a.SALE_NEW AND b.Product_Type = 'YP' AND b.Status_type = a.Status_type) AS 'YP'
              FROM
                ViewBI_INCENTIVEOPTF1_CALINCENTEIVE_BY_NORN a
              WHERE
                a.FSCODE = LTRIM(RTRIM('" . $_POST["branch"] . @"')) 
                AND a.MONTH_NAME = '" . $_POST["monthselected"] . @"' 
                AND a.YEARS = '" . $_POST["yearselected"] . @"' 
                AND a.Status_type = '" . $_POST["statusselected"] . @"'
              GROUP BY a.SALE_NEW,a.FSCODE,a.Status_type,a.MONTH_NAME
              ORDER BY a.SALE_NEW";
    $exsql2 = $conn->prepare($sql2);
    $exsql2->execute();


?>
     
    <nav class="navbar navbar-expand-lg flex-md-nowrap">
        <div class="collapse navbar-collapse">
        <ul class="nav nav-pills" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
            <button class="nav-link active" id="Detail-tab" data-bs-toggle="tab" data-bs-target="#Detail" type="button" role="tab" aria-controls="Detail" aria-selected="true" onclick="clicktab('Detail')">Picking Detail</button>
            </li>
            <li class="nav-item" role="presentation">
            <button class="nav-link" id="Summary-tab" data-bs-toggle="tab" data-bs-target="#Summary" type="button" role="tab" aria-controls="Summary" aria-selected="false" onclick="clicktab('Summary')">Picking Summary</button>
            </li>
        </ul>
        </div>

        <form name="frmMain" method="post" target="_blank" action="../processControl/frm_process_incentive_export.php">
              <span class="navbar-text pe-4 ps-2">
                        <div class="btn-toolbar ">
                            <div class="btn-group me-2">
                              <input name="Export"  type="submit" class="btn btn-sm btn-outline-secondary" value="Export">
                            </div>
                        </div>
              </span>
                          <input type="hidden" name="branch" id="branch" value="<?php echo $_POST["branch"];?>">
                          <input type="hidden" name="monthselected" id="monthselected" value="<?php echo $_POST["monthselected"];?>">
                          <input type="hidden" name="statusselected" id="statusselected" value="<?php echo $_POST["statusselected"];?>">
                          <input type="hidden" name="report" id="report" value="Detail">
              </form>
    </nav>  
    


    <div class="tab-pane show active" id="Detail" role="tabpanel" aria-labelledby="Detail-tab">
      <table class="table table-striped table-sm">
                <thead>
                  <tr>
                    <th scope="col">รหัสสาขา</th>
                    <th scope="col">สาขา</th>
                    <th scope="col">Product Code</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Type</th>
                    <th scope="col">Std Unit</th>
                    <th scope="col">Sale Code</th>
                    <th scope="col">Sale Name</th>
                    <th scope="col" class="text-right">Qty</th>
                    <th scope="col" class="text-right">Incentive Per Unit</th>
                    <th scope="col" class="text-right">Incentive Qty</th>
                  </tr>
                </thead>
                <tbody>
                <?php while ($result = $exsql->fetch(PDO::FETCH_ASSOC)) { ?>
                  <tr>
                    <td><?php echo $result["FSCODE"]; ?></td>
                    <td><?php echo $result["FS_NAME"]; ?></td>
                    <td><?php echo $result["PRODCODE1"]; ?></td>
                    <td><?php echo $result["PRODDESC"]; ?></td>
                    <td><?php echo $result["Product_Type"]; ?></td>
                    <td><?php echo $result["STDUNIT"]; ?></td>
                    <td><?php echo $result["SALE_NEW"]; ?></td>
                    <td><?php echo $result["SALENAME"]; ?></td>
                    <td class="text-right"><?php echo number_format($result["QTY"],2); ?></td>
                    <td class="text-right"><?php echo number_format($result["INCENTIVE"],2); ?></td>
                    <td class="text-right"><?php echo number_format($result["INC_QTY"],2); ?></td>
                  </tr>
                <?php } ?>
                </tbody>
        </table>
    </div>

    <div class="tab-pane" id="Summary" role="tabpanel" aria-labelledby="Summary-tab" hidden>
      <table class="table table-striped table-sm">
                  <thead>
                    <tr>
                      <th scope="col">Sale Code</th>
                      <th scope="col" class="text-right">HB</th>
                      <th scope="col" class="text-right">YP</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php while ($result2 = $exsql2->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                    <td><?php echo $result2["SALE_NEW"]; ?></td>
                    <td class="text-right"><?php echo number_format($result2["HB"],2); ?></td>
                    <td class="text-right"><?php echo number_format($result2["YP"],2); ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
      </table>

    </div>



<script>
    function clicktab(str){
        var tab = document.getElementById(str);
        document.getElementById("Detail-tab").classList.remove("active");
        document.getElementById("Summary-tab").classList.remove("active");
        document.getElementById("Detail").hidden = true;
        document.getElementById("Summary").hidden = true;
        if(tab.hidden){
            tab.hidden = false;
            document.getElementById(str+"-tab").classList.add("active");
            document.getElementById("report").value = str;
        }
    }
</script>
 