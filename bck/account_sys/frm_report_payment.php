<?php 
    ob_start();
    session_start();  
    @error_reporting(E_ALL ^ E_NOTICE); 
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

   
    $date_start     = date("Y-m-d", strtotime("-1 days"));
    $date_stop      = date("Y-m-d");
    
    


    $query = " SELECT   BRCODE,  FS_NAME, FS_ADDR1, COUNTRY, CITY,  STATENAME,  ZIPCODE, LATITUDE, LONGITUDE   
            from SET_BRANCH    
            where  (ISCLOSED = 'N' or ISCLOSED = '') 
            and BRANCHTYPE <> 'F'";
    $sql_branch = $conn->query($query);
    
?>
<div class="content-wrapper" style="font-size:13px ;">
    <?php include("frm_top_menu.php"); ?>
    <section class="content">
        <div class="container-fluid">
            <!-- <form id="Search" name="Search" method="post" action="#" enctype="multipart/form-data"> -->
            <div class="row" >
                <section class="col-lg-12  connectedSortable">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title h4"> รายงานการส่งยอดขาย    </h3>
                        </div>
                        <div class="card-body h6">
                            <div class="row form-group">
                                <div class="col-sm-1"> สาขา : </div>
                                <div class="col-sm-2 text-left">
                                    <select class="form-control" style="width: 100%;" id="branch" name="branch">
                                        <option value=""> -- กรุณาเลือกสาขา -- </option>
                                        <option value=""> ALL </option>
                                        <?php
                                        while ($row = $sql_branch->fetch(PDO::FETCH_ASSOC)) { ?>
                                            <option value="<?php echo $row["BRCODE"]; ?>"> <?php echo $row["BRCODE"] . " - " . $row["FS_NAME"]; ?></option>
                                        <?php }  ?>
                                    </select>
                                </div>
                                <div class="col-sm-2 text-right"> จากวันที่ : </div>
                                <div class="col-sm-2"> <input type="date" class="form-control form-control-border  border-width-2" id="start" name="start" value="<?php echo $date_start; ?>"></div>
                                <div class="col-sm-1 text-right"> ถึงวันที่ : </div>
                                <div class="col-sm-2">
                                    <input type="date" class="form-control form-control-border  border-width-2" id="stop" name="stop" value="<?php echo $date_stop; ?>">
                                </div>
                                <div class="col-sm-2 text-right"><button type="submit" name="bt_send" id="bt_send" class="btn btn-primary">Submit</button></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- </form>  -->                                  
        </div> 
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-6">
                    <div id="load" class="spinner-border text-warning" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div> 
            </div>
        </div>                              
        <div class="container-fluid" id="div_keyreport">
            <div class="row">
                <div class="col-sm-12">            
                </div>
            </div> 
        </div> 
    </section> 
    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a> 
</div>
<?php require('frm_footer.php'); ?>
<script>
    $("#div_keyin").show();
    $("#div_keyreport").hide();
    $("#load").hide();
    $("#bt_send").click(function() {
        $("#load").show();
        let branch = $('#branch').val();
        let start = $('#start').val();
        let stop = $('#stop').val();

        if (start == "") {
            alert("กรุณาเลือกวันทีเริ่มต้น ");
        } else if (stop == "") {
            alert("กรุณาเลือกวันที่สิ้นสุด");
        } else {
            var myData = '&branch=' + branch +
                '&start=' + start +
                '&stop=' + stop +
                '&mode=REPORTPAYMENT'
            //alert(myData); 
            jQuery.ajax({
                url: "../processControl/frm_process_payment.php",
                data: myData,
                type: "POST",
                dataType: "text",
                success: function(data) { 
                    $("#div_keyreport").html(data);
                    $("#div_keyreport").show();
                    $("#div_keyin").hide();
                    $("#load").hide();
                }
            });
        }
    }); 
</script>
 