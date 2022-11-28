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
    include("frm_member.php");
    require("../frm_left_top.php"); 
    $date_backday   =  date("Y-m-d" ,strtotime("-1days"));
    $date_cur       =  date("Y-m-d");

    $USERID         = $_SESSION["USERID"];
    $USERNAME       = $_SESSION["USERNAME"];
    $username       = $USERNAMES;
    
    $query = " SELECT   BRCODE,  FS_NAME, FS_ADDR1, COUNTRY, CITY,  STATENAME,  ZIPCODE, LATITUDE, LONGITUDE   
                from SET_BRANCH    
                where  (ISCLOSED = 'N' or ISCLOSED = '') 
                and BRANCHTYPE <> 'F'";  
    $sql_branch = $conn->query( $query );  
    
    $query_B = " SELECT BANKID, BANKNAME   from TB_BANKOFSTATION     where  FSCODE =  '".$FSCODE."'   ORDER BY FSCODE ASC";  
    $sql_bank = $conn_1->query( $query_B ); 
     
    $query_T = " SELECT  TOPICREMARK, TOPICID from TB_TOPICREMARK   WHERE  STATUS = 'Y' ORDER BY TOPICID DESC";  
    $sql_topic = $conn_1->query( $query_T ); 

    $query_assest     = " SELECT  * from TB_ASSEST  ORDER BY ASSESTID DESC "; 

?>
<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?>
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable"> 
                    <?php if($DEPARTCODE != "DP01"){?>
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"> ใบแจ้งยอดเงินฝากประจำวัน    </h3>
                            </div> 
                            <div class="card-body"> 
                                <div class="row form-group">
                                    <label class="col-sm-2" for="branch"> รหัสสาขา </label> 
                                    <div class="col-sm-3 h4">   <?php echo $FSCODE;?>   </div> 
                                </div>
                                <div class="row form-group"> 
                                    <label class="col-md-2">วันที่เกิดรายการขาย   </label>
                                    <div class="col-md-3"> 
                                    <div class="input-group date" id="reservationdate"   data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate"  id="date_used" name="date_used" value="<?php echo $date_backday;?>" readonly/>
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>

                                        <!-- <input type="text"  class="form-control form-control-border" id="date_used" name="date_used" placeholder="<?php echo date("Y-m-d" , strtotime("-1days"));?>"  value="<?php echo $date_backday;?>" > -->
                                    
                                    </div>
                                    <label class="col-md-2"> วันที่นำเงินฝากธนาคาร </label> 
                                    <div class="col-md-3">
                                        <div class="input-group date" id="reservationdatedd"  data-target-input="nearest" >
                                            <input type="text" class="form-control datetimepicker-input" data-target="#reservationdatedd"   id="date_pay" name="date_pay" value="<?php echo $date_cur;?>" readonly/>
                                            <div class="input-group-append" data-target="#reservationdatedd" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    
                                        <!-- <input type="date" class="form-control form-control-border  border-width-2" id="date_pay" name="date_pay" value="<?php echo $date_cur;?>"> -->
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label   class="col-sm-2"> ธนาคารที่ฝาก </label> 
                                    <div class="col-sm-5">
                                        <select class="form-control select2" style="width: 100%;" id="bank_name" name="bank_name">
                                            <option value="">-- เลือกธนาคารที่ฝาก -- </option>
                                            <?php  while ( $row = $sql_bank->fetch( PDO::FETCH_ASSOC ) ){?>
                                                <option value="<?php echo $row["BANKID"];?>"><?php echo  $row["BANKNAME"] ?></option>
                                            <?php } ?>    
                                        </select> 
                                    </div> 
                                </div>  
                                <div class="row form-group">
                                    <label class="col-md-2"> จำนวนเงินที่ฝาก    </label> 
                                    <div class="col-md-2"> 
                                        <input type="text" class="form-control rounded-0" id="amount" name="amount"  placeholder="0.00"   onchange="dokeyupf(this);"   onKeyUp="dokeyupf(this)" >
                                    </div>
                                    <label class="col-md-2 text-right"> จำนวนเงินที่ฝาก kerry    </label> 
                                    <div class="col-md-3"> 
                                        <input type="text" class="form-control rounded-0" id="amount_kerry" name="amount_kerry"  placeholder="0.00" onchange="dokeyupf(this);"  onKeyUp="if(isNaN(this.value)){ alert('กรุณากรอกตัวเลข'); this.value='';} dokeyupf(this)" >
                                    </div> 
                                </div>  
                                <div class="row form-group">
                                    <div class="col-md-2"> </div>
                                    <div class="col-md-2">
                                        <select class="form-control"   id="bank_type" name="bank_type" required>
                                            <option value="">- การนำเงินเข้า -</option>
                                            <option value="2">เคาน์เตอร์ธนาคาร</option> 
                                            <option value="1">เครื่องฝากเงิน</option> 
                                            <option value="3">7-11</option> 
                                            <option value="4">Big-C</option> 
                                            <option value="5">Lotus's</option> 
                                            <!-- <option value="6">อื่น(Agent)</option>  -->
                                        </select> 
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control rounded-0" id="refer_code" name="refer_code" placeholder="รหัสอ้างอิง รหัส PayIn  ที่นำฝาก"  required>
                                    </div>
                                    <div class="col-md-1"  style="color:#cccccc;">  ต่อ 1 ใบนำฝาก  </div>  
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2"> หมายเหตุ    </label> 
                                    <div class="col-md-6">
                                        <select class="form-control"   id="topicID" name="topicID" d>
                                            <option value="">-- หัวข้อหมายเหตุ -- </option>
                                                <?php  while ( $row = $sql_topic->fetch( PDO::FETCH_ASSOC ) ){?>
                                                    <option value="<?php echo $row["TOPICID"];?>"><?php echo  $row["TOPICREMARK"] ?> </option>
                                            <?php } ?>  
                                        </select> 
                                    </div>
                                    
                                </div>
                                <!-- <div class="form-group">
                                    <label>คำอธิบายเพิ่มแติม</label>
                                    <input type="text" class="form-control rounded-0" id="note" name="note" placeholder="คำอธิบายเพิ่มแติม">
                                </div>  -->
                                <div class="row form-group">
                                    <div class="col-sm-12"><button type="submit"  name="bt_send" id="bt_send" class="btn btn-primary btn-block">SUBMIT</button></div> 
                                </div> 
                            </div>  
                            <div class="row form-group">
                                <la class="col-sm-1"></la>
                                <label class="col-sm-10 text-left" style="color:#cccccc;">**  หมายเหตุ  กรณีที่ติดปัญหารบกวนติดฝ่ายบัญชี </label>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"> ASSEST  </h3>
                            </div> 
                            <div class="card-body">  
                            </div>   
                        </div>
                    <?php }?>
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
        let branch_code     = '<?php echo $FSCODE;?>';//$('#branch_code').val().trim(); 
        let date_used       = $('#date_used').val().trim(); 
        let bank_name       = $('#bank_name').val().trim(); 
        let bank_type       = $('#bank_type').val().trim();   
        let amount          = $('#amount').val().trim();  
        let date_pay        = $('#date_pay').val().trim(); 
        let refer_code      = $('#refer_code').val().trim();  
        // let note            = $('#note').val().trim(); 
        let amount_kerry    = $('#amount_kerry').val().trim();
        let topicID         = $('#topicID').val().trim();

        if(date_used == ""){
            alert("กรุณาเลือกวันที่ทำรายการ ");
        }else if(amount == ""){
            alert("กรุณากรอกจำนวนเงินที่ฝาก");
        }else  if(date_pay == ""){
            alert("กรุณาเลือกวันที่ฝากธนาคาร");
        }else  if(bank_name == ""){
            alert("กรุณาเลือกธนาคารที่ฝาก");
        }else  if(refer_code == ""){
            alert("กรุณกรอกรหัสอ้างอิง"); 
        }else{ 
            var myData = '&branch_code=' + branch_code +
            '&date_used=' + date_used +
            '&bank_name='+ bank_name + 
            '&bank_type='+ bank_type + 
            '&amount='+ amount + 
            '&date_pay='+ date_pay +
            '&refer_code='+ refer_code + 
            '&username='+ username +
            '&userid='+ userid + 
            '&amount_kerry='+ amount_kerry +
            '&topicID='+ topicID + 
            '&mode=INFRM' 
            //console.log(myData); 
            //alert(myData);
            //'&note='+ note +
            jQuery.ajax({
                url: "../processControl/frm_process_opreration.php",
                data: myData,
                type: "POST",
                dataType: "text", 
                success: function(data) {   
                    //alert(data);
                    $("#div-message").html(data);
                    $("#div-message").show();
                    $("#div_frmkey").hide();    
                }
            });
        } 
    });
    
    $("#pay_inover").hide();
    $("#bank_over").click(function () {  
        let pay = $('#bank_over').val(); 
        if(pay != ""){
            $("#pay_inover").html();
            $("#pay_inover").show();
        }
    }); 
</script>
<script>

// $('#arrival_date_div').datetimepicker({
//     format: "YYYY-MM-DD",
//     ignoreReadonly: true
// });

// $('#reservationdate').datetimepicker({
//     format: 'L',
//     ignoreReadonly: true
// });

// $('#reservationdatedd').datetimepicker({
//     format: 'L',
//     ignoreReadonly: true
// });

$(function() {
    $('#reservationdate').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: "2022-09-28",
        ignoreReadonly:true,
        focusOnShow: true,
    });

    $('#date_used').prop('readonly',true);

    $('#reservationdatedd').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: "2022-09-28",
        ignoreReadonly:true,
        focusOnShow: true,
    });

    $('#date_pay').prop('readonly',true);
});

</script>

 