<?php 

ob_start();
session_start();  
if($_SESSION["USERID"] == ""){
    echo "<script>alert('Please Log In.');</script>";
    echo "<script>location.replace('frm_login.php');</script>";
}

require("frm_heard.php"); 
include("../inc/fun_connect.php");
include("frm_member.php");
require("../frm_left_top.php");
@error_reporting(E_ALL ^ E_NOTICE); 
$date_start     = date("Y-m-d", strtotime("-7 days"));
$date_stop      = date("Y-m-d");
 
$sql = "SELECT * FROM TB_USER_BRANCH WHERE USERID = '" . $USERIDS . "'";
$exsql = $conn_1->prepare($sql);
$exsql->execute();
 
?>
<div class="content-wrapper" style="font-size:13px ;">
    <?php include("frm_top_menu.php"); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row" >
                <section class="col-lg-12  connectedSortable">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title h4"> รายงาน เปรียบเทียบการสั่ง PO PA  </h3>
                        </div>
                        <div class="card-body h6">
                            <div class="row form-group">
                                <div class="col-md-auto p-1">สาขา :
                                <select id="branch" name="branch">
                                    <?php while ($result = $exsql->fetch(PDO::FETCH_ASSOC)) { 
                                        $sqlbranch   = " SELECT * from SET_BRANCH where BRCODE = '".$result["FSCODE"]."'";  
                                        $getbranch         = $conn->query($sqlbranch);
                                        $branchdata        = $getbranch->fetch();
                                    ?>
                                        <option selected value='<?php echo $result["FSCODE"];?>'><?php echo $result["FSCODE"];?> <?php echo $branchdata["FS_NAME"];?></option>
                                    <?php } ?>
                                </select> 
                                </div>
                                <div class="col-md-auto p-1">ปี : 
                                    <select id="yearselected" name="yearselected">
                                        <option selected value='<?php echo date("Y"); ?>'><?php echo date("Y"); ?></option>
                                        <option value='<?php echo date("Y", strtotime(" - 1 years")); ?>'><?php echo date("Y", strtotime(" - 1 years")); ?></option>
                                    </select> 
                                </div>
                                <div class="col-md-auto p-1">เดือน : 
                                    <select id="monthselected" name="monthselected">
                                        <option selected value='1'>Janaury</option>
                                        <option value='2'>February</option>
                                        <option value='3'>March</option>
                                        <option value='4'>April</option>
                                        <option value='5'>May</option>
                                        <option value='6'>June</option>
                                        <option value='7'>July</option>
                                        <option value='8'>August</option>
                                        <option value='9'>September</option>
                                        <option value='10'>October</option>
                                        <option value='11'>November</option>
                                        <option value='12'>December</option>
                                    </select> 
                                </div>
                                <div class="col-md-auto"><button type="submit" name="bt_send" id="bt_send" class="btn btn-primary">Submit</button></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>                           
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
        if($('#branch').val() == null || $('#branch').val() == ""){
            alert("กรุณาเลือกสาขา");
        }else{
            $("#div_keyreport").hide();
            $("#load").show();
            let userID  = '<?php echo $USERIDS;?>';
            let username  = '<?php echo $USERNAMES;?>';
            let monthselected   = $('#monthselected').val();
            let branch    = $('#branch').val(); 
            let yearselected    = $('#yearselected').val(); 
                var myData = '&branch=' + branch +
                    '&monthselected=' + monthselected +
                    '&yearselected=' + yearselected +
                    '&userID=' + userID +
                    '&username=' + username
                //alert(myData); 
                jQuery.ajax({
                    url: "../processControl/frm_process_PO_PA.php",
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
 