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
   

    $query_assest   = " SELECT * from  TB_ASSEST  WHERE  STATUS  != '3' "; 
    $query = "  SELECT   BRCODE,  FS_NAME, FS_ADDR1, COUNTRY, CITY,  STATENAME,  ZIPCODE, LATITUDE, LONGITUDE   
                from SET_BRANCH    
                where  (ISCLOSED = 'N' or ISCLOSED = '') 
                and BRANCHTYPE <> 'F'";  
?>
<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?>
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">  คัดตัดจำหน่าย   </h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <label class="col-sm-2"></label> 
                                <div class="col-sm-8">  
                                    <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal" class="btn btn-warning"> เพิ่มรายการทรัพย์สินตัดจำหน่าย  </button>  
                                    <!-- <input type="hidden" name="USERID" id="USERID" value="<?php echo $user_data["USERID"]?>" > -->
                                </div>  
                            </div> 
                            <div class="row" id="employee_table1">  
                                <p>  เพิ่มเรียบร้อย</p>
                            </div>
                            <hr/>
                            <div class="row form-group">
                                <div class="col-sm-1">สาขา  </div> 
                                <div class="col-sm-2"> 
                                    <select class="form-control" style="width: 100%;" id="branch" name="branch">
                                        <option value=""> -- กรุณาเลือกสาขา -- </option> 
                                        <?php   foreach ($conn->query($query) as $bra) { ?>
                                            <option value="<?php echo $bra["BRCODE"]; ?>"> <?php echo $bra["BRCODE"] . " - " . $bra["FS_NAME"]; ?></option>
                                        <?php }  ?>
                                    </select>
                                </div>  
                                <div class="col-sm-5"> <input type="text"  id="location" name="location"  placeholder="สถานที" class="form-control rounded-0 form-control-border"> </div> 
                                 
                                <div class="col-sm-3"> <input type="text"  id="mobile" name="mobile"  placeholder="mobile : 08xxxxxxxx" class="form-control rounded-0 form-control-border"> </div> 
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12"> <input type="text"  id="note" name="note"  placeholder="หมายเหตุเพิ่มเติม" class="form-control rounded-0 form-control-border"></div> 
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12"><button type="submit"  name="bt_send" id="bt_send" class="btn btn-primary btn-block">  SEND  </button></div> 
                            </div> 
                        </div>   
                    </div>  
                
                <section class="col-lg-6 connectedSortable">   
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">  รูปภาพประกอบ  </h6>
                        </div> 
                        <div class="card-body">
                            <form>
                             
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
                <h4 class="modal-title"> ตัดจำหน่ายทรัพย์สิน </h4>  
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
                        <div class="col-sm-4"> 
                           
                        </div>
                    </div>    
                    <div class="row form-group">
                        <div class="col-sm-12"> 
                            <input type="text"  id="note" name="note"  placeholder="อธิบายเพิ่มเติม" class="form-control rounded-0 form-control-border">
                        </div> 
                    </div> 
                   
                    <input type="hidden" name="mode" id="mode"  value="InsertASSCUT"/>
                    <input type="hidden" name="STATUSID" id="STATUSID"  value="3"/>
                    <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-success" />  
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
        }  else   {  
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
                    $('#employee_table1').show(data);  
                    $('#employee_table1').html(data);  
                }  
            });  
        }  
    }); 
    

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
                $('#update_form')[0].reset();  
                $('#add_data_Modal_re').modal('hide');  
                $('#employee_table1').html(data);  
            }  
        });   
    }); 


    $("#div_frmkey").show();
    $("#employee_table1").hide();
    $("#bt_send").click(function() {   
        
        let branch          = $('#branch').val().trim();
        let location          = $('#location').val().trim();
        let mobile          = $('#mobile').val().trim();
        let note          = $('#note').val().trim();

        let myData          =  '&branch='+ branch +
        '&location='+ location +
        '&mobile='+ mobile +
        '&note='+ note +
        '&typee=3'+
        '&Ac=CUT'+ 
        '&mode=INFRDOCCUT' 
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
 

 