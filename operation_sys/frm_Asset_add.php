<?php  
    ob_start();
    session_start();  
    @error_reporting(E_ALL ^ E_NOTICE);
    if($_SESSION["USERID"] == ""){
		echo "<script>alert('Please Log In.');</script>";
		echo "<script>location.replace('../frm_login.php');</script>";
    }
  
    require("frm_heard.php"); 
    require("../inc/fun_connect.php"); 
    require("../inc/fun_main.php"); 
    include("frm_member.php");
    require("../frm_left_top.php"); 
    $date_backday   =  date("Y-m-d" ,strtotime("-1days"));
    $date_cur       =  date("Y-m-d");

    $USERID         = $_SESSION["USERID"];
    $USERNAME       = $_SESSION["USERNAME"];
    $username       = $USERNAMES;
    
    $query_com      = " SELECT  *  from  TB_COMPANY";  
    $query_cat      = " SELECT  *  from  TB_CATEGORIES";  
    $query_status   = " SELECT  *  from  TB_STATUS";  

    if(isset($_GET["item"])){
            $query_assestone   = " SELECT   a.RECEIVE_DATE, a.CREATE_DATE ,a.ASSEST_NUMBER,a.ASSESTID,a.ITEMNAME, a.MOBILE, a.DETAIL, a.ASSEST_NO, a.SERIAL_NUMBER, a.PO_ID, a.COMPANY_ID, a.STATUS, b.CATEGORIESID 
                        from TB_ASSEST a   
                        inner join TB_CATEGORIES b on a.CATEGORIESID = b.CATEGORIESID 
                        WHERE a.ASSESTID = '". $_GET["item"]."'"; 

        $getDetailone      = $conn_1->query($query_assestone);
        $detailone_data    = $getDetailone->fetch();

        $create_date        = date("Y-m-d", strtotime($detailone_data["CREATE_DATE"])); 
        $receive_date       = date("Y-m-d", strtotime($detailone_data["RECEIVE_DATE"]));
        $bt_submit          = "MODIFY";
        $mode_ac            = "MODIFYASSEST";
        $text_topic         = "แก้ไขรายการ : ". $detailone_data["ASSEST_NUMBER"];
        $ASSESTID           = $detailone_data["ASSESTID"];
        $ITEMNAME           = $detailone_data["ITEMNAME"];
        $mobile             = $detailone_data["MOBILE"];
        $detail             = $detailone_data["DETAIL"];
        $ASSEST_N           = $detailone_data["ASSEST_NO"];
        $SERIAL_NUMBER      = $detailone_data["SERIAL_NUMBER"];
        $PO_ID              = $detailone_data["PO_ID"];
        
    }else{
        $create_date        = "";
        $receive_date       =  "";
        $bt_submit          = "SUBMIT";
        $mode_ac            = "INFRMASSET";
        $text_topic         = "เพิ่มรายการทรัพย์สิน";
        $ITEMNAME           = "";
        $mobile             = "";
        $detail             = "";
        $ASSEST_N           = "";
        $SERIAL_NUMBER      = "";
        $PO_ID              = "";
        $ASSESTID           = "";
        $detailone_data["COMPANY_ID"] = "";
        $detailone_data["CATEGORIESID"] = "";
        $detailone_data["STATUS"]   ="";
        
    } 
?>

<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?>
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> <?php echo $text_topic ;?></h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <label class="col-sm-2">ชื่อทรัพย์สิน </label>
                                <div class="col-sm-4">  <input type="text" class="form-control rounded-0 form-control-border" id="ITEMNAME" name="ITEMNAME"  placeholder="ชื่อทรัพย์สิน"  value="<?php echo  $ITEMNAME;?>"  ></div>
                                <label class="col-sm-2  text-right" for="branch">  Company Name  </label> 
                                <div class="col-sm-3">   
                                    <select class="form-control select2" style="width: 100%;" id="COMPANYID" name="COMPANYID">
                                    <option value="">-- Company Name -- </option>
                                    <?php foreach ($conn_1->query($query_com) as $company) {?>
                                        <option value="<?php echo $company["COMPANYID"]?>"  <?php echo selectedOption($company["COMPANYID"], $detailone_data["COMPANY_ID"]);?>><?php echo $company["COMPANY_NAME"]?></option>
                                    <?php } ?>
                                    </select>
                                </div> 
                                
                            </div> 
                            <div class="row form-group">
                                <label class="col-sm-2">มือถือ </label>
                                <div class="col-sm-4">  <input type="text" class="form-control rounded-0 form-control-border" id="MOBILE" name="MOBILE"  placeholder="มือถือ"   value="<?php echo $mobile;?>" ></div>
                                <label class="col-sm-2 text-right" for="branch">  CATEGORIES </label> 
                                <div class="col-sm-3">   
                                    <select class="form-control select2" style="width: 100%;" id="CATEGORIESID" name="CATEGORIESID">
                                    <option value="">-- CATEGORIES -- </option>
                                    <?php foreach ($conn_1->query($query_cat) as $cat) {?>
                                        <option value="<?php echo $cat["CATEGORIESID"]?>" <?php echo selectedOption($cat["CATEGORIESID"], $detailone_data["CATEGORIESID"]);?>><?php echo $cat["CATEGORIESNAME"]?></option>
                                    <?php } ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2">รายละเอียดทรัพย์สิน  </label>
                                <div class="col-sm-10">  <input type="text" class="form-control rounded-0 form-control-border" id="DETAIL" name="DETAIL"  placeholder="รายละเอียดทรัพย์สิน"  value="<?php echo $detail;?>"   ></div> 
                                 
                            </div>
                            <div class="row form-group">
                            <label class="col-sm-2  ">  FIXED NUMBER  </label>
                                <div class="col-sm-4">  <input type="text" class="form-control rounded-0 form-control-border" id="ASSEST_NO" name="ASSEST_NO"  placeholder=" ASSEST NO  "  value="<?php echo $ASSEST_N;?>"  ></div> 
                                <label class="col-sm-2 text-right" for="branch"> STATUS </label> 
                                <div class="col-sm-3">   
                                    <select class="form-control select2" style="width: 100%;" id="STATUSID" name="STATUSID">
                                    <option value="">-- STATUS -- </option>
                                    <?php foreach ($conn_1->query($query_status) as $status) {?>
                                        <option value="<?php echo $status["STATUSID"]?>"<?php echo selectedOption($status["STATUSID"], $detailone_data["STATUS"]);?>>  <?php echo $status["STATUS_NAME"]?></option>
                                    <?php } ?>
                                    </select>
                                </div>      
                            </div> 
                            <div class="row form-group">
                                <label class="col-sm-2">SERIAL NUMBER </label>
                                <div class="col-sm-4">  <input type="text" class="form-control rounded-0 form-control-border" id="SERIAL_NUMBER" name="SERIAL_NUMBER"  placeholder="SERIAL NUMBER"   value="<?php echo $SERIAL_NUMBER;?>"  ></div> 
                                <label class="col-sm-2  text-right"> PO  NUMBER  </label>
                                <div class="col-sm-3">  <input type="text" class="form-control rounded-0 form-control-border" id="PO_ID" name="PO_ID"  placeholder="  PO  NUMBER   "  value="<?php echo $PO_ID;?>"  ></div> 
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2"> CREATE DATE </label>
                                <div class="col-sm-4"> 
                                    <input type="date" class="form-control form-control-border  border-width-2" id="createdate" name="createdate" value="<?php echo $create_date; ?>">
                                </div> 
                                <label class="col-sm-2  text-right"> RECEIVE DATE </label>
                                <div class="col-sm-3"><input type="date" class="form-control form-control-border  border-width-2" id="receivedate" name="receivedate" value="<?php echo $receive_date; ?>"></div> 
                            </div>
                             
                            <div class="row form-group">
                                <div class="col-sm-12"><button type="submit"  name="bt_send" id="bt_send" class="btn btn-primary btn-block"><?php echo $bt_submit;?></button></div> 
                            </div> 
                        </div>  
                         
                    </div> 
                </section>
            </div>  
            <div class="row" id="div-message">  </div>
        </div>
    </section>
</div>

<?php require('frm_footer.php'); ?>
<script> 
    $("#div_frmkey").show();
    $("#div-message").hide();
    $("#bt_send").click(function() {
        
        let username        = '<?php echo $USERNAMES;?>';
        let userid          = '<?php echo $USERIDS;?>';
        let COMPANYID       = $('#COMPANYID').val().trim(); 
        let CATEGORIESID    = $('#CATEGORIESID').val().trim(); 
        let ITEMNAME        = $('#ITEMNAME').val().trim(); 
        let STATUSID        = $('#STATUSID').val().trim();   
        let DETAIL          = $('#DETAIL').val().trim();  
        let SERIAL_NUMBER   = $('#SERIAL_NUMBER').val().trim(); 
        let PO_ID           = $('#PO_ID').val().trim(); 
        let createdate      = $('#createdate').val().trim();   
        let receivedate     = $('#receivedate').val().trim();
        let ASSEST_NO       = $('#ASSEST_NO').val().trim();
        let MOBILE          = $('#MOBILE').val().trim();
          
        if(COMPANYID == ""){
            alert("กรุณาเลือกบริษัท ");
        }else if(CATEGORIESID == ""){
            alert("กรุณาเลือกประเภทของทรัพย์สิน");
        }else  if(ITEMNAME == ""){
            alert("กรุณาคีย์รายการทรัพย์สิน");
        }else  if(STATUSID == ""){
            alert("กรุณเลือกสถาานะของทรัพย์สิน"); 
        }else  if(DETAIL == ""){
            alert("กรุณคีย์รายละเอียดทรัพย์สิน"); 
        }else  if(SERIAL_NUMBER == ""){
            alert("กรุณคีย์ SERIAL NUMBER"); 
        }else{ 
            var myData = '&COMPANYID=' + COMPANYID +
            '&CATEGORIESID=' + CATEGORIESID +
            '&ITEMNAME='+ ITEMNAME + 
            '&STATUSID='+ STATUSID + 
            '&DETAIL='+ DETAIL + 
            '&SERIAL_NUMBER='+ SERIAL_NUMBER +
            '&PO_ID='+ PO_ID +
            '&ASSEST_NO='+ ASSEST_NO +
            '&MOBILE='+ MOBILE +
            '&createdate='+ createdate + 
            '&receivedate='+ receivedate + 
            '&ASSESTID=<?php echo $ASSESTID;?>'+
            '&mode=<?php echo $mode_ac;?>' 
            //console.log(myData); 
           // alert(myData); 
            jQuery.ajax({
                url: "../processControl/frm_process_asset.php",
                data: myData,
                type: "POST",
                dataType: "text", 
                success: function(data) {  
                    //alert("Success");
                   location.replace("frm_Assest_list.php"); 
                }
            });
        } 
    });
    
     
</script>
 

 