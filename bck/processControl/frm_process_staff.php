<?php 
    include("../inc/fun_connect.php"); 
    $ipaddress 		    = $_SERVER["REMOTE_ADDR"]; 
    if ($_POST["mode"] == "INSTAFF") {
        $fullname   = $_POST["fullname"];
        $username   = $_POST["username"];
        $password   = $_POST["password"];
        $dep        = $_POST["dep"]; 
        $branch     = $_POST["branch"];
        $position   = $_POST["position"];  
        

        $date_cur       = date("Y-m-d H:i:s");
        $sel_in         = " INSERT INTO TB_USER (USERID, USERNAME ,PASSWORD , FULLNAME,FSCODE ,DEPARTCODE, POSITION , DATEREGISTER ,DATE_LAST ,IPADDRESS_LAST ,STATUS) ";
        $sel_in         .= " VALUES('','".$username."' ,'".$password."' , '".$fullname."' ,'".$branch."'  , '".$dep."' ,'". $position."', '".$date_cur."','".$date_cur."','".$ipaddress."','Y')";
        $res_insert      = $conn_1->query($sel_in); 
        
        $aaction         = " เพิ่มหนักงาน"; 
        include("frm_memberLog.php");      
     
?>
    <section class="col-lg-6 connectedSortable"> 
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">    </h3>
            </div>  
            <div class="card-body">  
                <div class="form-group">
                    <label>  เพิ่มรายชื่อ เรียบร้อย  </label>  
                </div>  
                <div class="form-group">
                    <a href="javascript:location.reload(true)" class="btn btn-primary"> HOME</a></button>
                </div>
            </div> 
        </div>
    </section> 
<?php } ?>