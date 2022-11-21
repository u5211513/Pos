<?php
    ob_start();
    session_start(); 
    @error_reporting(E_ALL ^ E_NOTICE);  
    include("../inc/fun_connect.php"); 
    $ipaddress 		    = $_SERVER["REMOTE_ADDR"]; 

    if(isset($_GET["actionMode"]) == "Delete"){ 
        $sql_pay        = " DELETE FROM PAYMENT_AMOUNT WHERE  ID = '".$_GET["id"]."'";  
        $query_del       = $conn_1->prepare($sql_pay);
        $query_del->execute(['ID']);
 
        echo "<script>alert('Delete Success.');</script>";
        echo "<script>location.replace('../operation_sys/frm_report_payment.php');</script>";
    } 
    
    if($_POST["mode"] == "APPROVE"){   
        $ID             = $_POST["ID"]; 
        $createdate     = date("Y-m-d H:i:s");
       
        for($ii=0; $ii<= count($_POST["ID"]); $ii++){
            $ID          = $_POST["ID"][$ii];  
            if($ID != ""){
                $id         = $ID;
                // echo $id;
                // echo "<br/>";
                $app_add    = " UPDATE  PAYMENT_AMOUNT SET  ACC_APPROVE = 'Y' , ACC_DATE = '".$createdate ."', ACC_BY = '".$_SESSION["USERID"]."'   WHERE  ID= '".$ID."'";
                $stmt_up    = $conn_1->prepare($app_add);
                $stmt_up->execute(['ACC_APPROVE','ACC_DATE', 'ACC_BY'] );
                $message    = 'Data Updated';   
            }
        } 
        echo "<script>alert('APPROVE Success.');</script>";
        echo "<script>location.replace('../account_sys/frm_report_payment.php');</script>";
    } 
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

    if(isset($_POST["acc"]) == "02"){
        $DEP        = "02";
    }else{
        $DEP        = "01";
    }
    if ($start != ""  &&  $stop != "") { 
        if($branch ==  ""){
            $query_amount       = " SELECT  * from PAYMENT_AMOUNT  WHERE PAY_DATEBILL  BETWEEN  '".$start."' AND   '".$stop."'"; 
        }else{
            $query_amount       = " SELECT  * from PAYMENT_AMOUNT  WHERE PAY_BTCODE = '".$branch."'  AND  PAY_DATEBILL  BETWEEN  '".$start."' AND   '".$stop."'"; 
        }
         
        $getRes = $conn_1->prepare($query_amount);
        $getRes->execute() ;
    } 
 
?>
    <div class="row">    
        <form id="frm_img" name="frm_img" method="post" action="../processControl/frm_process_payment.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-6"> <p class="alert alert-success h6"><?php echo  " ค้นหารายงานยอดขายโดย  : " .$name_branch . "   วันที่เริ่ม  :  " . date("d/m/Y", strtotime($start)) . "  ถึงวันที่  : " . date("d/m/Y", strtotime($stop)); ?> </p> </div>
                <div class="col-sm-6 text-center">
                <?php  if($DEP == "02" ){?>
                    <input type="submit" name="mode" id="mode" value="APPROVE" class="btn btn-danger" >
                <?php } ?>
                </div>
            </div>    
            <table  id="table1" class="table table-bordered table-striped table-responsive"> 
                <thead> 
                    <tr>
                        <th>#</th>
                        <th> </th>
                        <th>FSCODE</th>
                        <th>รหัสพนักงานที่ฝาก</th>
                        <th>วันที่ทำรายการ </th>
                        <th> วันที่ฝาก </th>
                        <th>จำนวนเงินที่ฝาก Kerry </th>
                        <th>ธนาคารที่ฝาก </th> 
                        <th>จำนวนเงินที่ฝาก </th>
                        <th>การนำเข้า</th>  
                        <th>รหัสอ้างอิง</th>   
                        <th>วันที่คีย์รายการ</th> 
                        <th>หัวข้อหมายเหตุ</th> 
                        <th width="200">เพิ่มเติม</th> 
                        <th>Account</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php $i_r   = 0;  while ($row = $getRes->fetch(PDO::FETCH_ASSOC)) { 
                            $query_bk = " SELECT   BANKNAME from TB_BANKOFSTATION  where   BANKID = '".$row["BANKID"]."' ";   
                            $getResB     = $conn_1->query($query_bk);
                            $bankdata  = $getResB->fetch(); 
                            if (empty($row['PAY_AMOUNT_KERRY'])) {
                                $bill_amountkerry = null; 
                            } else { 
                                $bill_amountkerry = number_format($row['PAY_AMOUNT_KERRY'],2);
                            }

                            if (empty($row["TOPICID"])) {
                                $topiccc  = null; 
                            } else { 
                                $query_topic    = " SELECT * from TB_TOPICREMARK  WHERE  TOPICID = '" . $row["TOPICID"] . "'";
                                $gettopic       = $conn_1->query($query_topic);
                                $topic_data     = $gettopic->fetch();
                                $topiccc        = $topic_data['TOPICREMARK'];
                            }
                            
                            if($row["ACC_APPROVE"] == "" ){
                                $approve    = "<a href=\"../processControl/frm_process_payment.php?id=".$row["ID"]."&actionMode=Delete\"><i class=\"fas fa-trash-alt\" style=\"color:#ff0000;\"></i></a>";
                                $approve_a  =  "รอการรับยอด";
                            }else{
                                $approve    = "<i class=\"fas fa-wallet\" style=\"color:#28a745; font-size:25px;\" title=\"บัญชีรับยอดแล้ว\"></i>";
                                $approve_a  =  "บัญชีรับยอดแล้ว";
                            } 
                        ?>
                        <tr>
                            <td> <?php echo  $i_r +  1 . "."; ?></td>
                            <?php  if($DEP == "02" ){?>
                                <td>   
                                    <?php  if($row["ACC_APPROVE"] == "" ){?>
                                        <input type="checkbox" name="ID[]" id="ID[]"  value="<?php echo $row['ID'];?>" style="width:30px; height: 30px;" checked> 
                                    <?php }elseif(isset($row["ACC_APPROVE"]) == 'Y' ){  echo " รับยอดแล้ว";}?>
                                </td> 
                            <?php }else{?>
                                <td> <?php  echo $approve ; ?>  </td> 
                            <?php } ?>
                            <td><?php echo $row['PAY_BTCODE']; ?></td>
                            <td><?php echo $row['USERNAME']; ?></td>
                            <td><?php echo date("d/m/Y", strtotime($row['PAY_DATEBILL'])); ?> </td>
                            <td><?php echo date("d/m/Y", strtotime($row['PAY_DATEPAYMENT'])); ?> </td>  
                            <td style="color:#ff0000; font-size:16px;"><?php echo $bill_amountkerry;?></td> 
                            <td style="color:#ff0000; font-size:16px;"><?php echo number_format($row['PAY_AMOUNT'],2);?></td>  
                            <td><?php echo  $bankdata['BANKNAME']; ?></td>  
                            <td><?php echo $row['PAY_TYPETRANFER']; ?></td>
                            <td><?php echo $row['PAY_CODETRANFER']; ?></td> 
                            <td><?php echo date("d/m/Y  H:i:s", strtotime($row['PAY_DATERE'])); ?></td> 
                            <td><?php echo $topiccc ; ?></td>  
                            <td><?php echo $row['PAY_NOTE']; ?></td> 
                            <?php  if($DEP == "02" ){?>
                                <td class="text-center"><?php echo "<a href=\"../processControl/frm_process_payment.php?id=".$row["ID"]."&actionMode=Delete\"><i class=\"fas fa-trash-alt\" style=\"color:#ff0000; font-size:20px;\"></i></a>";?></td> 
                            <?php }else{  ?>
                                <td> </td> 
                            <?php } ?>
                        </tr> 
                    <?php $i_r++;  } ?>  
                </tbody>  
            </table>
        </form>
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
 