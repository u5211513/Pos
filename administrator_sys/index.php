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
    require("../inc/fun_main.php"); 
    include("frm_member.php");
    require("../frm_left_top.php"); 
    $date_backday   =  date("Y-m-d" ,strtotime("-1days"));
    $date_cur       =  date("Y-m-d");
    $USERID         = $_SESSION["USERID"];
    $USERNAME       = $_SESSION["USERNAME"];
 
    // QUERY//////
        $query = " SELECT   BRCODE,  FS_NAME, FS_ADDR1, COUNTRY, CITY,  STATENAME,  ZIPCODE, LATITUDE, LONGITUDE   
                    from SET_BRANCH    
                    where  (ISCLOSED = 'N' or ISCLOSED = '') 
                    and BRANCHTYPE <> 'F'";  
        $sql_branch = $conn->query( $query );
        
        $query_DEP      = " SELECT * from TB_DEPT";  
        $query_com      = " SELECT *  from  TB_COMPANY";  
        $query_position = " SELECT *  from  TB_POSITION";  
        $query_rbranch = " SELECT *  from  SET_BRANCH";

        if(isset($_GET["action"]) == "EDIT"){
            $USER_ID    = $_GET["USER_ID"];
            $query_USER = " SELECT * from TB_USER WHERE USERID = '".$USER_ID."'";   
            
            $getRes         = $conn_1->query($query_USER);
            $userData       = $getRes->fetch();
 
            $full_name      = $userData["FULLNAME"]; 
            $postion        = $userData["POSITION"]; 
            $button_text    = "MODIFY";
            $val_paa        = $userData["PASSWORD"];
            $val_user       = $userData["USERNAME"];  
            $readd          = "readonly";

            $data_id        = $_GET["USER_ID"];
            $date_start     = $userData["DATE_START"];
            $date_type      = "text";
            $email_d        = $userData["EMAIL"];
            $id_d           = $userData["IDNO"];
            $textAddMo      = " แก้ไขรายชื่อ "; 
            $mode_ac        = "MODIFYSTAFF";
        }else{
            $full_name      = "";
            $postion        = "";
            $val_user       = "";
            $button_text    = "SUBMIT";
            $val_paa        = "123456";
            $readd          = "";
            $data_id        = "";
            $id_d           = "";
            $email_d        = "";
            $date_start     = date("d/m/Y");
            $date_type      = "text";
            $textAddMo      = " เพิ่มรายชื่อ ";
            $mode_ac        = "INSTAFF";
            $userData       = array();
            $userData["COMPANYID"]  ="";
            $userData["DEPTID"]     ="";
            $userData["POSITIONID"] ="";
            $userData["FSCODE"]     ="";
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
                            <h3 class="card-title">  <?php echo $textAddMo;?>  </h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <label class="col-sm-2">ชื่อ - สกุล  (*)</label>
                                <div class="col-sm-3"> <input type="text" name="fullname" id="fullname"  placeholder="ชื่อ - สกุล"  value="<?php echo $full_name;?>" class="form-control form-control-border  border-width-2"  required ></div>
                                <label class="col-sm-2"> Company name (*)</label>
                                <div class="col-sm-3"> 
                                    <select class="form-control" style="width: 100%;"  id="COMPANYID" name="COMPANYID" required>
                                        <option value=""> -- เลือกบริษัท -- </option>   
                                        <?php foreach ($conn_1->query($query_com) as $company) {?> 
                                            <option value="<?php echo $company["COMPANYID"];?>" <?php echo selectedOption($company["COMPANYID"], $userData["COMPANYID"]);?>> <?php echo  $company["COMPANY_NAME"];?></option> 
                                        <?php }  ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group"> 
                                <label class="col-sm-2">UserName</label>
                                <div class="col-sm-3"><input type="text" name="username" id="username"   placeholder="Username  "  value="<?php echo $val_user;?>" <?php echo $readd;?> class="form-control form-control-border  border-width-2"  required></div>
                                <label class="col-sm-2">Dep   (*)</label>
                                <div class="col-sm-3"> 
                                    <select class="form-control" style="width: 100%;"  id="dep" name="dep" required>
                                        <option value=""> -- เลือกแผนก -- </option>   
                                        <?php   
                                            foreach ($conn_1->query($query_DEP) as $dep) { ?>  
                                            <option value="<?php echo $dep["DEPTID"];?>" <?php echo selectedOption($dep["DEPTID"], $userData["DEPTID"]);?>> <?php echo $dep["DEPT_NAME"];?></option> 
                                        <?php }  ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2">Password</label>
                                <div class="col-sm-3"> <input type="text" name="password" id="password"  placeholder="Password"  value="<?php echo $val_paa;?>" <?php echo $readd;?> class="form-control form-control-border  border-width-2"  required></div>
                                
                                <label class="col-sm-2">ตำแหน่งงาน  (*)</label>
                                <div class="col-sm-3">  
                                    <select class="form-control" style="width: 100%;"  id="POSITIONID" name="POSITIONID" required>
                                        <option value=""> -- เลือกตำแหน่ง -- </option>   
                                        <?php  
                                            foreach ($conn_1->query($query_position) as $position) { ?>  
                                            <option value="<?php echo $position["POSITIONID"];?>"  <?php echo selectedOption($position["POSITIONID"], $userData["POSITIONID"]);?>> <?php echo $position["POSITION_NAME"];?></option> 
                                        <?php }  ?>
                                    </select> 
                                </div> 
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2"> รหัสพนักงาน (*)  </label>
                                <div class="col-sm-3"> 
                                    <input type="text" name="IDNO" id="IDNO"  placeholder="รหัสพนักงาน"  value="<?php echo $id_d;?>"   class="form-control form-control-border  border-width-2"  required>
                                </div>
                                <label class="col-sm-2"> รหัสสาขา  </label> 
                                <div class="col-sm-3 text-right">
                                    <select class="form-control" style="width: 100%;"  id="branch" name="branch">
                                        <option value=""> -- เลือกสาขา -- </option>
                                        <?php  
                                            while ( $row = $sql_branch->fetch( PDO::FETCH_ASSOC ) ){ ?>  
                                            <option value="<?php echo $row["BRCODE"];?> " <?php echo selectedOption($row["BRCODE"], $userData["FSCODE"]);?> > <?php echo $row["BRCODE"]. " - " . $row["FS_NAME"];?></option> 
                                        <?php }  ?> 
                                    </select> 
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-sm-2">Email  : </label>
                                <div class="col-sm-3"> <input type="text" name="email" id="email"  placeholder="Email"    value="<?php echo $email_d;?>"     class="form-control form-control-border  border-width-2"> </div>
                                <label class="col-sm-2">วันที่เริ่มงาน</label>
                                <div class="col-sm-3"> <input type="date" name="DATE_START" id="DATE_START"  placeholder="วันที่เริ่มงาน"  value="<?php echo $date_start;?>" class="form-control form-control-border  border-width-2">  </div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12">
                                        
                                        <button type="submit"  name="bt_send" id="bt_send" class="btn btn-primary btn-block"><?php echo $button_text;?></button>
                                    
                                </div> 
                            </div> 
                        </div> 
                        
                    </div>  
                </section> 
            </div>  
            <div class="row" id="div-message">  </div> 
            </div>  
            <?php if($data_id != ""){  ?> 
                <div class="row">
                    <div class="col-md-auto w-50">
                        <form id="frm_img" name="frm_img" method="post" action="../processControl/frm_process_staff.php" enctype="multipart/form-data">
                            <section class="col">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">  เพิ่มสิทธิการใช้งาน  </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>เพิ่ม Department ที่ดู</label>
                                            </div>
                                            <div class="col-md-auto pt-1">
                                                <?php foreach ($conn_1->query($query_DEP) as $dep) {
                                                    $query_action   = " SELECT * from TB_ACTION  a  
                                                    inner join TB_DEPT b on a.A_DEP = b.DEPTID  where a.USERID = '".$USER_ID."'  and   a.A_DEP = '".$dep["DEPTID"]."'";  
                                                    $getDep         = $conn_1->query($query_action);
                                                    $DepData        = $getDep->fetch();
                                                    
                                                        if(isset($DepData["DEPTID"])){
                                                            $check  = "checked";
                                                        }else{  $check  = "";}
                                                    ?> 
                                                    <p><input type="checkbox" id="depview[]" name="depview[]" <?php echo $check;?>  value="<?php echo $dep["DEPTID"];?>" style="width:20px; height:20px;"> <?php echo  $dep["DEPT_NAME"]?> </p>
                                                <?php } ?> 
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-auto w-100">
                                                <input type="hidden" name="mode" id="mode" value="INDEPVIEW"> 
                                                <input type="hidden" name="USER_ID" id="USER_ID" value="<?php echo $_GET["USER_ID"]?>">  
                                                <button type="submit"  name="bt_sendDep" id="bt_sendDep" class="btn btn-primary btn-block">  ADD </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </form>
                    </div>
                    <div class="col-md-auto w-50">
                        <form id="frm_img" name="frm_img" method="post" action="../processControl/frm_process_staff.php" enctype="multipart/form-data">
                            <section class="col">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">  เพิ่มสิทธิการใช้งาน  </h3>
                                    </div> 
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>เพิ่มสาขาที่ดู</label>
                                            </div>
                                            <div class="col-md-auto pt-1">
                                                <?php foreach ($conn->query($query_rbranch) as $dep) {
                                                   $query_action   = " SELECT * from TB_USER_BRANCH where USERID = '".$USER_ID."' and FSCODE = '".$dep["BRCODE"]."'";  
                                                    $getDep         = $conn_1->query($query_action);
                                                    $DepData        = $getDep->fetch();
                                                    
                                                        if(isset($DepData["ID"])){
                                                            $check  = "checked";
                                                        }else{  $check  = "";}
                                                    ?> 
                                                    <p><input type="checkbox" id="breportview[]" name="breportview[]" <?php echo $check;?>  value="<?php echo $dep["BRCODE"];?>" style="width:20px; height:20px;"> <?php  echo $dep["BRCODE"]." ".$dep["FS_NAME"]?> </p>
                                                <?php } ?> 
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-auto w-100">
                                                <input type="hidden" name="mode" id="mode" value="BRANCHREPORT"> 
                                                <input type="hidden" name="USER_ID" id="USER_ID" value="<?php echo $_GET["USER_ID"]?>">  
                                                <button type="submit"  name="bt_sendDep" id="bt_sendDep" class="btn btn-primary btn-block">  ADD </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>
                
            <?php } ?>
        </section>
    
</div>
<?php require('frm_footer.php'); ?>
<script> 
    $("#div_frmkey").show();
    $("#div-message").show();
    $("#bt_send").click(function() {  
        let COMPANYID       = $('#COMPANYID').val().trim(); 
        let dep             = $('#dep').val().trim(); 
        let fullname        = $('#fullname').val().trim();
        let username        = $('#username').val().trim();
        let password        = $('#password').val().trim();
        let POSITIONID      = $('#POSITIONID').val().trim(); 
        let branch          = $('#branch').val().trim();  
        let email           = $('#email').val().trim(); 
        let DATE_START      = $('#DATE_START').val().trim();  
        let IDNO            = $('#IDNO').val().trim();
        let IDD             = '<?php echo $data_id;?>';
        
        if(fullname == ""){
            alert("กรุณาใส่ชื่อ - สกุล  "); 
        }else  if(dep == ""){
            alert("กรุณเลือกแผนก"); 
        }else  if(COMPANYID == ""){
            alert("กรุณเลือกบริษัท"); 
        }else  if(POSITIONID == ""){
            alert("กรุณใส่ตำแหน่งงาน"); 
        }else  if(IDNO == ""){
            alert("กรุณใส่รหัสพนักงาน"); 
        }else{ 
            var myData = '&fullname=' + fullname +
            '&username=' + username +
            '&password='+ password + 
            '&dep='+ dep + 
            '&branch='+ branch +
            '&email='+ email +
            '&DATE_START='+ DATE_START +
            '&POSITIONID='+ POSITIONID +  
            '&COMPANYID='+ COMPANYID +  
            '&IDNO='+ IDNO +  
            '&USERID='+ IDD +  
            '&mode=<?php echo $mode_ac;?>' 
            
            jQuery.ajax({
                url: "../processControl/frm_process_staff.php",
                data: myData,
                type: "POST",
                dataType: "text", 
                success: function(data) {  
                    alert("SUCCESS");
                    $("#div-message").html(data);
                    $("#div-message").show();
                    location.reload();
                 
                }
            });
        } 
    }); 
</script>