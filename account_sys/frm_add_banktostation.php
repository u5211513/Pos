<?php  
    @error_reporting(E_ALL ^ E_NOTICE); 
    ob_start();
    session_start();  
   
    if($_SESSION["USERID"] == ""){
        echo "<script>alert('Please Log In.');</script>";
        echo "<script>location.replace('../frm_login.php');</script>";
    }
    $USERID     = $_SESSION["USERID"];
    $USERNAME   = $_SESSION["USERNAME"];
     
    require("frm_heard.php"); 
    include("../inc/fun_connect.php"); 
    include("../operation_sys/frm_member.php");
    require("../frm_left_top.php");

    $date_backday   =  date("Y-m-d" ,strtotime("-1days"));
    $date_cur       =  date("Y-m-d");
    $query = " SELECT   BRCODE,  FS_NAME, FS_ADDR1, COUNTRY, CITY,  STATENAME,  ZIPCODE, LATITUDE, LONGITUDE   
                from SET_BRANCH    
                where  (ISCLOSED = 'N' or ISCLOSED = '') 
                and BRANCHTYPE <> 'F'";  
    $sql_branch = $conn->query( $query ); 
    
    $query1 = " SELECT   BRCODE,  FS_NAME, FS_ADDR1, COUNTRY, CITY,  STATENAME,  ZIPCODE, LATITUDE, LONGITUDE   
                from SET_BRANCH    
                where  (ISCLOSED = 'N' or ISCLOSED = '') 
                and BRANCHTYPE <> 'F'";  
    $sql_branch1 = $conn->query( $query1 ); 


    $query_B = " SELECT *   
                from TB_BANKOFSTATION    
                ORDER BY FSCODE ASC";  
    $sql_bank = $conn_1->query( $query_B ); 
    
    if(isset($_GET["action"])){
        $actionn    =   $_GET["action"];
        if ($actionn == "suppend") {
            $STATUS         = 'N';
            $BANKID         = $_GET["BANKID"];
            $sql_bank       = " UPDATE TB_BANKOFSTATION SET STATUS = '" . $STATUS . "'   WHERE BANKID = '" . $BANKID . "'";
            $stmt_up        = $conn_1->prepare($sql_bank);
            $stmt_up->execute(['STATUS', 'BANKID']);
            echo "<script>location.replace('frm_add_banktostation.php');</script>";
        }   
        if($actionn  == "EDIT"){
            $BANKID         = $_GET["BANKID"];
            $query_BANK     = " SELECT * from TB_BANKOFSTATION WHERE BANKID = '".$BANKID."'";   
            
            $getbank        = $conn_1->query($query_BANK);
            $BankData       = $getbank->fetch(); 
            $bank_Id         = $BankData["BANKID"]; 
            $bankname       = $BankData["BANKNAME"];
            $fscode         = $BankData["FSCODE"];  
            $button_text    = "MODIFY";
            $moddd          = "modify"; 
            $text_adedit    = "แก้ไข";
        }

    }else{
        $button_text    = "SUBMIT";
        $moddd          = "INBANKOFSTATION";
        $text_adedit    =" เพิ่ม ";
    }

    // if ($actionn == "suppend") {
    //     $STATUS         = 'N';
    //     $BANKID         = $_GET["BANKID"];
    //     $sql_bank       = " UPDATE TB_BANKOFSTATION SET STATUS = '" . $STATUS . "'   WHERE BANKID = '" . $BANKID . "'";
    //     $stmt_up        = $conn_1->prepare($sql_bank);
    //     $stmt_up->execute(['STATUS', 'BANKID']);
    //     echo "<script>location.replace('frm_add_banktostation.php');</script>";
    // }   
    // if($actionn  == "EDIT"){
    //     $BANKID         = $_GET["BANKID"];
    //     $query_BANK     = " SELECT * from TB_BANKOFSTATION WHERE BANKID = '".$BANKID."'";   
        
    //     $getbank        = $conn_1->query($query_BANK);
    //     $BankData       = $getbank->fetch(); 
    //     $bank_Id         = $BankData["BANKID"]; 
    //     $bankname       = $BankData["BANKNAME"];
    //     $fscode         = $BankData["FSCODE"];  
    //     $button_text    = "MODIFY";
    //     $moddd          = "modify"; 
    //     $text_adedit    = "แก้ไข";
    // }else{
    //     $button_text    = "SUBMIT";
    //     $moddd          = "INBANKOFSTATION";
    //     $text_adedit    =" เพิ่ม ";
    // } 
   
?>
<div class="content-wrapper" style="font-size:16px ;">
    <?php  require("frm_top_menu.php");?>
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable"> 
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> <?php echo  $text_adedit;?>ธนาคารสำหรับสาขา   </h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <label class="col-sm-2" for="branch"> รหัสสาขา </label> 
                                <div class="col-sm-3 h4 text-left">  
                                    <?php  if(isset($_GET["action"]) == ""){  ?> 
                                    <select class="form-control" style="width: 100%;"  id="branch_code" name="branch_code" required>
                                        <option value=""> -- กรุณาเลือกสาขา -- </option>
                                        <?php    while ( $row = $sql_branch->fetch( PDO::FETCH_ASSOC ) ){ ?>  
                                            <option value="<?php echo $row["BRCODE"];?>"> <?php echo $row["BRCODE"]. " - " . $row["FS_NAME"];?></option> 
                                        <?php }  ?> 
                                    </select> 
                                    <?php }else{ echo $fscode; }?>

                                </div> 
                                <div class="col-sm-5"> <input type="text" name="NAMEBANK" id="NAMEBANK" value="<?php if(isset($_GET["action"]) != ""){ echo $bankname;}?>" placeholder="ใส่ชื่อ ธนาคาร  ตัวอย่าง  ธ.ไทยพาณิชย์ เลขที่ 122-2-1xxxx-8 "  class="form-control form-control-border"></div> 
                                <div class="col-sm-2">
                                <?php  if(isset($_GET["action"]) == ""){  ?> 
                                    <button type="submit"  name="bt_send" id="bt_send" class="btn btn-primary btn-block"><?php echo $button_text;?></button>
                                <?php }else{?>
                                    <button type="submit"  name="bt_edit" id="bt_edit" class="btn btn-primary btn-block"><?php echo $button_text;?></button>
                                    <input type="hidden" name="BANKID" id="BANKID" value="<?php echo $bank_Id;?>">
                                <?php } ?>
                                </div> 
                            </div>  
                        </div>   
                    </div>  
                </section> 
                <section class="col-lg-12 connectedSortable"> 
                    <div class="card card-success">   
                        <div class="card-header">
                            <h3 class="card-title">  รายการสาขา   </h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <table  id="table1" class="table table-bordered table-striped table-responsive"> 
                                    <thead>
                                        <tr>
                                            <th width="150">รหัสสาขา</th>
                                            <th width="300">ชื่อสาขา</th>
                                            <th width="300">ธนาคาร</th>
                                            <th>วันที่ทำรายการ</th>
                                            <th>สถานะ </th> 
                                            <th class="text-center">  การจัดการ </th> 
                                        </tr> 
                                    </thead>
                                    <tbody>
                                    <?php    while ( $row = $sql_bank->fetch( PDO::FETCH_ASSOC ) ){
                                            $query = " SELECT   BRCODE,  FS_NAME from SET_BRANCH  where   BRCODE = '".$row["FSCODE"]."' ";   
                                            $getRes     = $conn->query($query);
                                            $branchdata  = $getRes->fetch(); 
                                        ?>  
                                        <tr>
                                            <td><?php echo $row["FSCODE"];?></td>
                                            <td><?php echo $branchdata["FS_NAME"];?></td>
                                            <td><?php echo $row["BANKNAME"];?></td>
                                            <td><?php echo date("d/m/Y H:i:s", strtotime($row["DATEREGISTER"]));?></td>
                                            <td class="text-center"><?php echo $row["STATUS"];?>  </td>
                                            <td width="300">
                                                <div class="row form-group">
                                                    <div class="col-lg-6">
                                                        <a href="frm_add_banktostation.php?action=suppend&BANKID=<?php echo $row["BANKID"] ?>">
                                                            <i class="fas fa-times" title="ระงับ"></i> ระงับ
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <a href="frm_add_banktostation.php?action=EDIT&BANKID=<?php echo $row["BANKID"] ?>">
                                                            <i class="fas fa-edit" title="แก้ไข" ></i> แก้ไข
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr> 
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>  
                        </div>   
                    </div>  
                </section>
            </div>   
        </div>
    </section> 
</div>
<?php require('frm_footer.php'); ?>
<script> 
    $("#div_frmkey").show();
    $("#div-message").hide();        
    $("#bt_send").click(function() {   
        let branch_code     = $('#branch_code').val().trim(); 
        let bank_name       = $('#NAMEBANK').val().trim(); 
        let modee           ='<?php echo $moddd;?>';
        
        if(branch_code == ""){
            alert("กรุณาเลือกสาขา ");
        }else if(bank_name == ""){
            alert("กรุณากรอกชื่อธนาคาร"); 
        }else{ 
            var myData = '&branch_code=' + branch_code +
            '&bank_name=' + bank_name + 
            '&mode=' + modee  
            
            jQuery.ajax({
                url: "../processControl/frm_process_staff.php",
                data: myData,
                type: "POST",
                dataType: "text", 
                success: function(data) {   
                    alert("เพิ่มธนาคารเรียบร้อย"); 
                    location.reload();
                }
            });
        } 
    }); 


    $("#bt_edit").click(function() {   
        let bankkid         = $('#BANKID').val().trim(); 
        let bank_name       = $('#NAMEBANK').val().trim(); 
        let modee           ='<?php echo $moddd;?>'; 

        let myData =  '&bank_name=' + bank_name + 
            '&bankkid=' + bankkid +  
            '&mode=' + modee  
           // alert(myData);
        jQuery.ajax({
            url: "../processControl/frm_process_staff.php",
            data: myData,
            type: "POST",
            dataType: "text", 
            success: function(data) {   
                alert("เปลี่ยนข้อมูลธนาคารเรียบร้อย"); 
                location.reload();
            }
        });  
    }); 
    
    $(function() { 
        $("#table1").DataTable({    
            "lengthMenu": [[10, 20, 50, 100,200, -1], [10,20,50, 100,200, "All"]], 
            "lengthChange": true, 
            "autoWidth": false, 
            "paging": true, 
            "ordering": true,
            "info": false,  
            "searching": true,
             
        }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)'); 
    });  
</script>