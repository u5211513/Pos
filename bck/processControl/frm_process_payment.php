<?php
    ob_start();
    session_start(); 
    @error_reporting(E_ALL ^ E_NOTICE);  
    include("../inc/fun_connect.php"); 
    $ipaddress 		    = $_SERVER["REMOTE_ADDR"]; 
if ($_POST["mode"] == "REPORTPAYMENT") {
    if ($_POST["branch"] != "") {
        $branch         = $_POST["branch"];
        $sql_br_ck      = "AND BT.FSCODE = '" . $branch . "'";
        $name_branch    = $branch;
    } else {
        $branch         = "";
        $sql_br_ck      = "";
        $name_branch    = "ALL STATION";
    }

    if (isset($_POST["start"]) != "") {
        $start      = $_POST["start"];
    } else {
        $start      =  $date_stop;
    }

    if (isset($_POST["stop"]) != "") {
        $stop      = $_POST["stop"];
    } else {
        $stop      =  $date_stop;
    }

    if ($start != ""  &&  $stop != "") { 
        if($branch ==  ""){
            $query_amount       = " SELECT  * from PAYMENT_AMOUNT  WHERE     PAY_DATEBILL  BETWEEN  '".$start."' AND   '".$stop."'"; 
        }else{
            $query_amount       = " SELECT  * from PAYMENT_AMOUNT  WHERE   PAY_BTCODE = '".$branch."'  AND  PAY_DATEBILL  BETWEEN  '".$start."' AND   '".$stop."'"; 
        }
         
        $getRes = $conn_1->prepare($query_amount);
        $getRes->execute() ;
    }
    // $aaction        = "ดึงรายงานวันที่ " . $start . " ถึง ". $stop ;
    // $branch_code    = $name_branch;
    // $date_cur       = date("Y-m-d H:i:s"); 
    // $userId         = $_SESSION["userID"];
    // $username       = $_SESSION["username"]; 
    // include("frm_memberLog.php"); 
?>
    <div style="border:1px;">  
        <p class="alert alert-success h6"><?php echo  " ค้นหารายงานยอดขายโดย  : " .$name_branch . "   วันที่เริ่ม  :  " . date("d/m/Y", strtotime($start)) . "  ถึงวันที่  : " . date("d/m/Y", strtotime($stop)); ?> </p> 
        <table  id="table1" class="table table-bordered table-striped table-responsive"> 
            <thead> 
                <tr>
                    <th>#</th>
                    <th>FSCODE</th>
                    <th>รหัสพนักงานที่ฝาก</th>
                    <th>วันที่ทำรายการ </th>
                    <th> วันที่ฝาก </th>
                    <th>ธนาคารที่ฝาก </th>
                    <th>ยอดรวมทั้งหมด </th>
                    <th>จำนวนเงินที่ฝาก </th>
                    <th>การนำเข้า</th>  
                    <th>รหัสอ้างอิง</th>  
                    <th>ยอดเกิน</th> 
                    <th>วันที่คีย์รายการ</th> 
                    <th>เพิ่มเติม</th> 
                </tr>
            </thead>
            <tbody>
                <?php $i_r   = 0;  while ($row = $getRes->fetch(PDO::FETCH_ASSOC)) { 
                    if($row["PAY_AMOUNT_1"] != ""){
                        $payamount_1    = $row["PAY_AMOUNT_1"];
                        $text_amount_1  = "/".$payamount_1;
                    }
                    ?>
                    <tr>
                        <td> <?php echo  $i_r +  1 . "."; ?></td>
                        <td><?php echo $row['PAY_BTCODE']; ?></td>
                        <td><?php echo $row['USERNAME']; ?></td>
                        <td><?php echo date("d/m/Y", strtotime($row['PAY_DATEBILL'])); ?> </td>
                        <td><?php echo date("d/m/Y", strtotime($row['PAY_DATEPAYMENT'])); ?> </td>  
                        <td> <?php echo  $row['PAY_BANKNAME']; ?> </td> 
                        <td style="color:#ff0000; font-size:16px;"><?php echo number_format($row['PAY_GRANTOTAL'],2);?></td>  
                        <td style="color:#ff0000; font-size:16px;"><?php echo number_format($row['PAY_AMOUNT']+$payamount_1,2);?></td> 
                        <td><?php echo $row['PAY_TYPETRANFER']; ?> </td>
                        <td><?php echo $row['PAY_CODETRANFER']; ?></td>
                        <td><?php echo number_format($row['PAY_AMOUNTFREE'],2); ?></td>
                        <td><?php echo date("d/m/Y  H:i:s", strtotime($row['PAY_DATERE'])); ?> </td>  
                        <td><?php echo $row['PAY_NOTE']; ?></td>
                         
                    </tr> 
                <?php $i_r++;  } ?>  
            </tbody> 
            
        </table>
    </div>
<?php } ?>
<script>
    $(function() { 
        $("#table1").DataTable({   
            "lengthMenu": [[10, 20, 50, 100,200, -1], [10,20,50, 100,200, "All"]], 
            "lengthChange": true, 
            "autoWidth": false, 
            "paging": true, 
            "ordering": true,
            "info": false,  
            "searching": true,
            "buttons": 
            [  
                {
                    extend: 'excelHtml5',
                    text : ' <i class="fas fa-download"></i>   Export Excel  ',
                    title: '<?php echo  $title_heard_file;?>',
                    autoFilter: false,
                    messageTop: '<?php echo $text_bodyyy." " . $text_print;?>  ',  
                    filename: '<?php echo $fileName;?>'   
                }, 
            ], 
        }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)'); 
    });  
</script>
<?php  //echo json_encode($data); ?>