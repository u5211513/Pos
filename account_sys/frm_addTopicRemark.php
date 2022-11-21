<?php 
  
    error_reporting(E_ALL ^ E_NOTICE); 
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
    
    $query_T = " SELECT *  from TB_TOPICREMARK  ORDER BY TOPICID DESC";  
    $sql_topics = $conn_1->query( $query_T ); 
    
    if(isset($_GET["action"])){ 
        $actionn    =   $_GET["action"];
        if ($actionn == "suppend") {
            $STATUS         = 'N';
            $TOPICID         = $_GET["TOPICID"];
            $sql_topic       = " UPDATE TB_TOPICREMARK SET STATUS = '" .$STATUS. "'   WHERE TOPICID = '" . $TOPICID . "'";
            $stmt_up        = $conn_1->prepare($sql_topic);
            $stmt_up->execute(['STATUS', 'TOPICID']);
            echo "<script>location.replace('frm_addTopicRemark.php');</script>";
        } 
        if ($actionn == "open") { 
            $STATUS         = 'Y';
            $TOPICID         = $_GET["TOPICID"];
            $sql_topic       = " UPDATE TB_TOPICREMARK SET STATUS = '" .$STATUS. "'   WHERE TOPICID = '" . $TOPICID . "'";
            $stmt_up        = $conn_1->prepare($sql_topic);
            $stmt_up->execute(['STATUS', 'TOPICID']);
            echo "<script>location.replace('frm_addTopicRemark.php');</script>";
        }

        if($actionn  == "EDIT"){
            $TOPICID         = $_GET["TOPICID"];
            $query_TOPIC     = " SELECT * from TB_TOPICREMARK WHERE TOPICID = '".$TOPICID."'";   
            
            $gettopic       = $conn_1->query($query_TOPIC);
            $TOPICData      = $gettopic->fetch(); 
            $topic_Id       = $TOPICData["TOPICID"]; 
            $topic_detail   = $TOPICData["TOPICREMARK"];
         
            $button_text    = "MODIFY";
            $moddd          = "modifyTopic"; 
            $text_adedit    = "แก้ไข";
        }
    }else{
        
        $button_text    = "SUBMIT";
        $moddd          = "ADDREMARK";
        $text_adedit    =" เพิ่ม ";
    }
   
?>

<div class="content-wrapper" style="font-size:16px ;">
    <?php  require("frm_top_menu.php");?>
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable"> 
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> <?php echo  $text_adedit;?>  หัวข้อ หมายเหตุ    </h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <label class="col-sm-1" for="branch">   รายละเอียด   </label>   
                                <div class="col-sm-7"> <input type="text" name="topic" id="topic" value="<?php if(isset($_GET["action"]) != ""){ echo $topic_detail;}?>" placeholder="ใส่หัวข้อ หมายเหตุ  "  class="form-control form-control-border"></div> 
                                <div class="col-sm-2">
                                <?php  if(isset($_GET["action"]) == ""){  ?> 
                                    <button type="submit"  name="bt_send" id="bt_send" class="btn btn-primary btn-block"><?php echo $button_text;?></button>
                                <?php }else{?>
                                    <button type="submit"  name="bt_edit" id="bt_edit" class="btn btn-primary btn-block"><?php echo $button_text;?></button>
                                    <input type="hidden" name="TOPICID" id="TOPICID" value="<?php echo $topic_Id;?>"> 
                                <?php } ?>
                                </div> 
                            </div>  
                        </div>   
                    </div>  
                </section> 
                <section class="col-lg-12 connectedSortable"> 
                    <div class="card card-success">   
                        <div class="card-header">
                            <h3 class="card-title">  รายการ  </h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <table  id="table1" class="table table-bordered table-striped table-responsive"> 
                                    <thead>
                                        <tr>
                                            <th width="150">รายละเอียด </th> 
                                            <th width="300"> User </th>
                                            <th>วันที่ทำรายการ</th>
                                            <th>สถานะ </th> 
                                            <th class="text-center">  การจัดการ </th> 
                                        </tr> 
                                    </thead>
                                    <tbody>
                                    <?php   while ( $row = $sql_topics->fetch( PDO::FETCH_ASSOC ) ){ 
                                            if($row["STATUS"] ==  "Y"){
                                                $color      = "btn btn-success";
                                                $action_ck   = "suppend";
                                            }else{
                                                $color      = "btn btn-danger";
                                                $action_ck   = "open";
                                            }
                                        ?>  
                                        <tr>
                                            <td><?php echo $row["TOPICREMARK"];?></td> 
                                            <td><?php echo $row["ACCOUNT"];?></td>
                                            <td><?php echo date("d/m/Y H:i:s", strtotime($row["DATEREGISTER"]));?></td>
                                            <td class="text-center h6"><?php echo $row["STATUS"];?></td>
                                            <td width="300">
                                                <div class="row form-group">
                                                    <div class="col-lg-6">
                                                        <a href="frm_addTopicRemark.php?action=<?php echo $action_ck;?>&TOPICID=<?php echo $row["TOPICID"] ?>">
                                                            <i class="fas fa-times" title="ระงับ"></i> ระงับ 
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <a href="frm_addTopicRemark.php?action=EDIT&TOPICID=<?php echo $row["TOPICID"] ?>">
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
    $("#bt_send").click(function() {   
        let topic           = $('#topic').val().trim();  
        let modee           ='<?php echo $moddd;?>';
        
        if(topic == ""){
            alert("กรุณาคีย์รายละเอียด "); 
        }else{ 
            var myData = '&topic=' + topic + 
            '&mode=' + modee  
            
            jQuery.ajax({
                url: "../processControl/frm_process_staff.php",
                data: myData,
                type: "POST",
                dataType: "text", 
                success: function(data) {   
                    alert("เพิ่มเรียบร้อย"); 
                    location.reload();
                }
            });
        } 
    }); 


    $("#bt_edit").click(function() {    
        let topic_Id        = '<?php echo $topic_Id;?>';// $('#TOPICID').val().trim(); 
        let topic           = $('#topic').val().trim(); 
        let modee           ='<?php echo $moddd;?>'; 
       
        let myData = '&topic=' + topic + 
            '&topic_Id=' + topic_Id +  
            '&mode=' + modee   
        jQuery.ajax({
            url: "../processControl/frm_process_staff.php",
            data: myData,
            type: "POST",
            dataType: "text", 
            success: function(data) {   
                alert("เปลี่ยนหัวข้อเรียบร้อย"); 
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