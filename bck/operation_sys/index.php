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

    $query = " SELECT   BRCODE,  FS_NAME, FS_ADDR1, COUNTRY, CITY,  STATENAME,  ZIPCODE, LATITUDE, LONGITUDE   
                from SET_BRANCH    
                where  (ISCLOSED = 'N' or ISCLOSED = '') 
                and BRANCHTYPE <> 'F'";  
    $sql_branch = $conn->query( $query );  

   
 ?>
<div class="content-wrapper" style="font-size:16px ;">
    <?php  require("frm_top_menu.php");?>
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable"> 
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> ใบแจ้งยอดเงินฝากประจำวัน    </h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <label class="col-sm-2" for="branch"> รหัสสาขา </label> 
                                <div class="col-sm-3 h4">   <?php echo $FSCODE;?> 
                                        <!-- <select class="form-control" style="width: 100%;"  id="branch_code" name="branch_code" required>
                                        <option value=""> -- กรุณาเลือกสาขา -- </option>
                                    <?php  
                                        while ( $row = $sql_branch->fetch( PDO::FETCH_ASSOC ) ){ ?>  
                                        <option value="<?php echo $row["BRCODE"];?>"> <?php echo $row["BRCODE"]. " - " . $row["FS_NAME"];?></option> 
                                    <?php }  ?> 
                                    </select>  -->
                                </div> 
                            </div>
                            <div class="row form-group"> 
                                <label class="col-md-2">วันที่เกิดรายการขาย   </label>
                                <div class="col-md-3"> <input type="date" class="form-control form-control-border" id="date_used" name="date_used" placeholder="<?php echo date("Y-m-d" , strtotime("-1days"));?>"  value="<?php echo $date_backday;?>"></div>
                                <label class="col-md-2"> วันที่นำเงินฝากธนาคาร </label> 
                                <div class="col-md-3"><input type="date" class="form-control form-control-border  border-width-2" id="date_pay" name="date_pay" value="<?php echo $date_cur;?>"> </div>
                            </div>
                            <div class="row form-group">
                                <label   class="col-sm-2"> ธนาคารที่ฝาก </label> 
                                <div class="col-sm-5">
                                    <select class="form-control select2" style="width: 100%;" id="bank_name" name="bank_name">
                                        <option value="">-- เลือกธนาคารที่ฝาก -- </option>
                                        <option value="1">ธ.ไทยพาณิชย์ เลขที่ 122-2-13598-8</option>
                                        <option value="2">ธ.กสิกรไทย เลขที่ 056-8-41214-1</option> 
                                    </select> 
                                </div> 
                            </div>  
                            <div class="row form-group">
                                <label class="col-md-2"> จำนวนเงินที่ฝาก    </label>

                                <div class="col-md-2"><input type="number" class="form-control rounded-0" id="amount" name="amount"  ></div>
                                <div class="col-md-2">
                                    <select class="form-control"   id="bank_type" name="bank_type" required>
                                        <option value="">- การนำเงินเข้า -</option>
                                        <option value="1">เครื่องฝากเงิน</option>
                                        <option value="2">โอนเงิน </option> 
                                    </select> 
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control rounded-0" id="refer_code" name="refer_code" placeholder="รหัสอ้างอิง รหัส PayIn  ที่นำฝาก"  required>
                                </div>
                                <div class="col-md-1"> 
                                    <input type="button" onclick="this.val()" name="bank_over" id="bank_over" class="btn btn-success" value="เพิ่ม  Pay-in  1 รายการ"/>       
                                </div>  
                            </div> 
                            <div id="pay_inover">
                                <div class="row form-group">
                                    <label class="col-md-2">    </label>
                                    <div class="col-md-2"><input type="text" class="form-control rounded-0" id="amount2" name="amount2" placeholder="0.00" ></div>
                                    <div class="col-md-2">
                                        <select class="form-control"   id="bank_type2" name="bank_type2" required>
                                            <option value="">- การนำเงินเข้า -</option>
                                            <option value="1">เครื่องฝากเงิน</option>
                                            <option value="2">โอนเงิน </option> 
                                        </select> 
                                    </div> 
                                    <div class="col-md-3">
                                        <input type="text" class="form-control rounded-0" id="refer_code" name="refer_code" placeholder="รหัสอ้างอิง รหัส PayIn  ที่นำฝาก"  required>
                                    </div>
                                </div>
                            </div>  
                            <div class="row form-group">
                                <label class="col-sm-2 text-left" for="exampleInputRounded0">ยอดเกินจากยอดขาย</label>
                                <div class="col-sm-4 text-right"><input type="number" class="form-control rounded-0" id="amont_fee" name="amont_fee" onkeyup="keyNumber.this()" placeholder="0.00"></div>
                                <div class="col-sm-4">  กรอกเป็นตัวเลข เท่านั้น  * </div>  
                                <p style="color:#cccccc;">  เฉพาะสาขาที่นำเงินสดฝากที่ 7-11 หรือ Big-C หรือ Lotus's เท่านั้น ใส่จำนวนเงินฝากของ Kerry </p>
                            </div>
                            <div class="form-group">
                                <label>คำอธิบายเพิ่มแติม</label>
                                <input type="text" class="form-control rounded-0" id="note" name="note" placeholder="คำอธิบายเพิ่มแติม">
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12"><button type="submit"  name="bt_send" id="bt_send" class="btn btn-primary btn-block">SUBMIT</button></div> 
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
        let branch_code     = '<?php echo $FSCODE;?>';//$('#branch_code').val().trim(); 
        let date_used       = $('#date_used').val().trim(); 
        let bank_name       = $('#bank_name').val().trim(); 
        let bank_type       = $('#bank_type').val().trim();  
        let bank_type2      = $('#bank_type2').val().trim();  
        let amount          = $('#amount').val().trim(); 
        let amount2         = $('#amount2').val().trim(); 
        let date_pay        = $('#date_pay').val().trim(); 
        let refer_code      = $('#refer_code').val().trim(); 
        let amont_fee       = $('#amont_fee').val().trim(); 
        let note            = $('#note').val().trim(); 
        
        if(date_used == ""){
            alert("กรุณาเลือกวันที่ทำรายการ ");
        }else if(amount == ""){
            alert("กรุณากรอกจำนวนเงินที่ฝาก");
        }else  if(date_pay == ""){
            alert("กรุณาเลือกวันที่ฝากธนาคาร");
        }else  if(refer_code == ""){
            alert("กรุณกรอกรหัสอ้างอิง"); 
        }else{ 
            var myData = '&branch_code=' + branch_code +
            '&date_used=' + date_used +
            '&bank_name='+ bank_name + 
            '&bank_type='+ bank_type + 
            '&amount='+ amount +
            '&bank_type2='+ bank_type2 + 
            '&amount2='+ amount2 +
            '&date_pay='+ date_pay +
            '&refer_code='+ refer_code +
            '&amont_fee='+ amont_fee +
            '&username='+ username +
            '&userid='+ userid +
            '&note='+ note +
            '&mode=INFRM' 
            //console.log(myData); 
           // alert(myData);
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