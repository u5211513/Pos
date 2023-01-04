<?php  
    ob_start();
    session_start();  
    error_reporting(E_ALL ^ E_NOTICE);
    if($_SESSION["USERID"] == ""){
		echo "<script>alert('Please Log In.');</script>";
		echo "<script>location.replace('../frm_login.php');</script>";
    }
  
    require("frm_heard.php"); 
    require("../inc/fun_connect.php"); 
    include("frm_member.php");
    require("../frm_left_top.php"); 
    $date_backday   =  date("Y-m-d" ,strtotime("-1days"));
    $date_cur       =  date("Y-m-d");

    $USERID         = $_SESSION["USERID"];
    $USERNAME       = $_SESSION["USERNAME"];
    $username       = $USERNAMES;
    $IDD            = $_GET["IDD"];

    $query_user       = " SELECT  *  from  TB_USER  WHERE   USERID = '".$IDD."'";  
    $getUser          = $conn_1->query($query_user);
    $user_data        = $getUser->fetch();

    $query_assest   = " SELECT * from  TB_ASSEST  WHERE  STATUS in('2') "; 
    
    $query_used     = " SELECT  * from TB_ASSESTUSED a inner join TB_ASSEST b on a.ASSESTID = b.ASSESTID
                        WHERE  a.USERID = '".$IDD."'
                        ORDER BY ASSEST_USEDID DESC"; 

    $query_img     = " SELECT  * from TB_IMAGES a inner join TB_USER b on a.USERID = b.USERID
                        WHERE  a.USERID = '".$IDD."'
                        ORDER BY IMAGE_ID DESC"; 
     
 
    $query_status   = " SELECT  *  from  TB_STATUS";  
?>
<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?>
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">  ส่งมอบ/รับคืน ทรัพย์สิน  คุณ  <?php echo $user_data["FULLNAME"];?> </h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <label class="col-sm-2"></label> 
                                <div class="col-sm-8">  
                                    <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal" class="btn btn-warning"> เพิ่มรายการทรัพย์สิน  </button>  
                                    <input type="hidden" name="USERID" id="USERID" value="<?php echo $user_data["USERID"]?>" >
                                </div>  
                            </div> 
                            <div class="row" id="employee_table">  
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> Doc </th>
                                            <th>Categorites</th> 
                                            <th>รายการทรัพย์สิน</th> 
                                            <th> Detail </th>
                                            <th>วันที่เพิ่มทรัพย์สิน</th>
                                            <th>วันที่ส่งมอบทรัพย์สิน</th>
                                            <th>วันที่คืนทรัพย์สิน</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $r = 1;
                                            foreach ($conn_1->query($query_used) as $assest) {
                                                if(isset($assest["ASSESTDOC_ID"])){
                                                    $query_doc       = " SELECT  *  from  TB_ASSESTDOC  WHERE   ASSESTDOC_ID = '".$assest["ASSESTDOC_ID"]."'";  
                                                    $getDoc          = $conn_1->query($query_doc);
                                                    $doc_data        = $getDoc->fetch(); 
                                                    if(isset($doc_data["DATE_EM_STOP"])){ 
                                                        $dateclose  = date("d/m/Y H:i:s" , strtotime($doc_data["DATE_EM_STOP"]));
                                                    }else{
                                                        $dateclose  = " <button type=\"button\" name=\"add\" id=\"add\" data-toggle=\"modal\" data-target=\"#add_data_Modal_re\" class=\"btn btn-warning\" onClick=\"clickchange(".$assest["ASSESTDOC_ID"].")\"> 
                                                        <i class=\"fas fa-outdent\" title=\"รับคืนทรัพย์สิน\" style=\"color:#FF0000; font-size:20px;\"></i></button> ";
                                                    }
                                                    if(isset($doc_data["DATE_OUT"])){
                                                        $doc_out        = date("d/m/Y H:i:s" , strtotime($doc_data["DATE_OUT"]));
                                                    }else{ $doc_out     = "";} 
                                                }else{ 
                                                    $doc_out     = "";
                                                    $dateclose   = "";
                                                } 
                                                $cata       = " SELECT b.* FROM TB_ASSEST a
                                                                INNER JOIN TB_CATEGORIES b ON a.CATEGORIESID = b.CATEGORIESID
                                                                WHERE a.ASSESTID = '".$assest["ASSESTID"]."'";  
                                                $getcata          = $conn_1->query($cata);
                                                $cata_data        = $getcata->fetch(); 
                                                ?>
                                            <tr>
                                                <td><?php echo $r;?></td>
                                                <td><?php echo $assest["ASSESTDOC_NO"];?></td>
                                                <td><?php echo $cata_data["CATEGORIESNAME"];?></td>
                                                <td><?php echo $assest["ITEMNAME"];?></td> 
                                                <td><?php echo $assest["DETAIL"];?></td>
                                                <td><?php echo date("d/m/Y H:i:s" , strtotime($assest["CREATE_DATE"]));?></td>
                                                <td><?php echo $doc_out;?></td>
                                                <td><?php echo $dateclose;?></td>
                                                <td> <a href="frm_Assest_doc.php?user=<?php echo $assest["USERID"];?>&docccc=<?php echo $assest["ASSESTDOC_ID"];?>"><i class="fas fa-print" title="ปริ้นใบทรัพย์สิน" style="color:#17a2b8; font-size:20px;"></i> </a> </td>
                                            </tr>
                                        <?php  $r++;} ?>
                                    </tbody>
                                </table> 
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12"><button type="submit"  name="bt_send" id="bt_send" class="btn btn-primary btn-block">  SEND </button></div> 
                            </div> 
                        </div>   
                    </div> 

                    <!-- <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">  FILE </h6>
                        </div> 
                        <div class="card-body">
                            <form>
                                <div class="row form-group">
                                    <div class="col-sm-6"></div>
                                    <div class="col-sm-6"></div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-12 text-center"><input type="submit" name="" id="" value="UPLOAD" class="btn btn-danger"> </div> 
                                </div>              
                            </form>
                        </div>
                    </div> -->
                </section>
                <section class="col-lg-6 connectedSortable">   
                    
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">  อัพโหลดรูปภาพ </h6>
                        </div> 
                        <div class="card-body">
                            <form id="frm_img" name="frm_img" method="post" action="../processControl/frm_process_upload.php" enctype="multipart/form-data">
                                <?php for($im=0; $im<=5; $im++){?>
                                    <div class="row form-group"> 
                                        <div class="col-sm-3"><input type="file" name="uploadfile[]" id="uploadfile[]" class="form-control"/> </div>
                                        <div class="col-sm-9"><input type="text" name="detail[]" id="detail[]"  class="form-control" placeholder="รายละเอียดรายการ"/> 
                                     </div>
                                    </div> 
                                <?php } ?>   
                                    <div class="row form-group">
                                        <div class="col-sm-12 text-center">
                                            <input type="submit" name="mode" id="mode" value="UPLOAD" class="btn btn-danger">
                                            <input type="hidden" name="USER_UID" id="USER_UID" value="<?php echo $user_data["USERID"]?>" class="btn btn-danger">
                                        </div> 
                                    </div>          
                            </form>
                        </div>
                    </div> 
                </section>
                <section class="col-lg-6 connectedSortable">   
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">  รูปภาพประกอบ  </h6>
                        </div> 
                        <div class="card-body">
                            <form>
                            <?php  foreach ($conn_1->query($query_img) as $img) {  ?>
                                <div class="row form-group">  
                                    <div class="col-sm-3"><a href="../upload/<?php echo $img["IMAGE_NAME"];?>"  target="_blank"><img src="../upload/<?php echo $img["IMAGE_NAME"];?>" class="img-thumbnail img-bordered-sm"> </a></div> 
                                    <div class="col-sm-9">
                                        <p><?php echo $img["IMAGE_DETAIL"];?></p>
                                        <p class="text-right"><?php echo date("d/m/Y H:i:s", strtotime($img["CREATEDATE"]));?></p>
                                    </div>   
                                </div> 
                                <?php } ?> 
                                <div class="row form-group">
                                    <div class="col-sm-12 text-center"></div> 
                                </div>              
                            </form>
                        </div>
                    </div> 
                </section>
            </div>
            <div class="row" id="div-message"></div>  
            <div class="row" id="employee_table1">  </div>
    </section>
</div> 
<div id="add_data_Modal" class="modal fade ">  
    <div class="modal-dialog modal-lg">  
        <div class="modal-content">  
            <div class="modal-header">  
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>   -->
                    <h4 class="modal-title"> เพิ่มทรัพย์สิน </h4>  
            </div>  
            <div class="modal-body">  
                <form method="post" id="insert_form"> 
                    <div class="row form-group">
                        <div class="col-sm-7"> 
                            <select class="form-control select2" style="width: 100%;" id="ASSESTID" name="ASSESTID">
                                <option value="">-- เลือกรายการ -- </option>
                                <?php foreach ($conn_1->query($query_assest) as $assest) {?>
                                    <option value="<?php echo $assest["ASSESTID"]?>"><?php echo $assest["ITEMNAME"]?></option>
                                <?php } ?>
                            </select>
                        </div> <br/>
                        <div class="col-sm-3"> 
                            <select class="form-control select2" style="width: 100%;" id="STATUSID" name="STATUSID">
                                <option value="">-- สถานะรายการ  -- </option>
                                <?php foreach ($conn_1->query($query_status) as $status) {?>
                                    <option value="<?php echo $status["STATUSID"]?>"><?php echo $status["STATUS_NAME"]?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>    
                    <div class="row form-group">
                        <div class="col-sm-12"> 
                            <input type="text"  id="note" name="note"  placeholder="อธิบายเพิ่มเติม" class="form-control rounded-0 form-control-border">
                        </div> 
                    </div> 
                    <input type="hidden" name="USERID" id="USERID"  value="<?php echo $IDD;?>"/>  
                    <input type="hidden" name="mode" id="mode"  value="InsertASS"/>  
                    <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-success" />  
                </form>  
            </div>  
            <div class="modal-footer">  
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>  
        </div>  
    </div>  
</div> 

<div id="add_data_Modal_re" class="modal fade ">  
    <div class="modal-dialog modal-lg">  
        <div class="modal-content">  
            <div class="modal-header">  
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>   -->
                    <h4 class="modal-title"> รับคืนทรัพย์สิน </h4>  
            </div>  
            <div class="modal-body">  
                <form method="post" id="update_form"> 
                    <div class="row form-group"> 
                        <div class="col-sm-3"> 
                            <select class="form-control select2" style="width: 100%;" id="STATUSID" name="STATUSID">
                                <option value="">-- สถานะรายการ  -- </option>
                                <?php foreach ($conn_1->query($query_status) as $status) {?>
                                    <option value="<?php echo $status["STATUSID"]?>"><?php echo $status["STATUS_NAME"]?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>  
                    <div class="row form-group">
                        <div class="col-sm-12">  
                            <input type="date"  id="dateStop" name="dateStop"  placeholder="วันสนสุดการปฏิบัติงาน" class="form-control rounded-0 form-control-border">
                        </div> 
                    </div>   
                    <!-- <div class="row form-group">
                        <div class="col-sm-12"> 
                            <input type="text"  id="noteStop" name="noteStop"  placeholder="อธิบายเพิ่มเติม" class="form-control rounded-0 form-control-border">
                        </div> 
                    </div>  -->
                    <input type="hidden" name="USERID" id="USERID"  value="<?php echo $IDD;?>"/> 
                    <input type="hidden" name="mode" id="mode"  value="ReASS"/>
                    <input type="hidden" name="ASSESTID_UPDATE" id="ASSESTID_UPDATE" value="0">
                    <input type="submit" name="update" id="update" value="update" class="btn btn-success" />  
                </form>  
            </div>  
            <div class="modal-footer">  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>  
        </div>  
    </div>  
</div> 
<?php require('frm_footer.php'); ?> 
<script>  
    $('#insert_form').on("submit", function(event){  
        event.preventDefault();  
        if($('#ASSESTID').val() == ""){  
            alert("เลือกทรัพย์สิน");  
        }else if($('#STATUSID').val() == ""){
            alert("เลือกสถานะ"); 
        } else   {  
            $.ajax({  
                url:"../processControl/frm_process_asset.php",  
                method:"POST",  
                data:$('#insert_form').serialize(),  
                beforeSend:function(){  
                    $('#insert').val("Inserting");  
                },  
                success:function(data){  
                     
                    $('#insert_form')[0].reset();  
                    $('#add_data_Modal').modal('hide');  
                    $('#employee_table').html(data);  
                }  
            });  
        }  
    }); 
    
    function clickchange(str){
        document.getElementById('ASSESTID_UPDATE').value = str;
    }

    $('#update_form').on("submit", function(event){  
        event.preventDefault();  
        $.ajax({  
            url:"../processControl/frm_process_asset.php",  
            method:"POST",  
            data:$('#update_form').serialize(),  
            beforeSend:function(){  
                $('#update').val("Updating");  
            },  
            success:function(data){   
                //alert(data);
                $('#update_form')[0].reset();  
                $('#add_data_Modal_re').modal('hide');  
                $('#employee_table1').html(data);   
                
            }  
        });  
          
    }); 

    $("#div_frmkey").show();
    $("#div-message").hide();
    $("#bt_send").click(function() {   
        let USERID          = $('#USERID').val().trim();  
        var myData = '&USERID=' + USERID + 
        '&STATUSID='+ STATUSID +
        '&mode=INFRDOC' 
        //console.log(myData); 
        //alert(myData); 
        jQuery.ajax({
            url: "../processControl/frm_process_asset.php",
            data: myData,
            type: "POST",
            dataType: "text", 
            success: function(data) {   
                $("#div-message").html(data);
                $("#div-message").show();
                $("#div_frmkey").hide();     
            }
        });
           
    }); 
</script>
 

 