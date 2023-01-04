<?php $part  = "../";?>
<script src="<?php echo $part;?>plugins/jquery/jquery.min.js"></script> 
<?php  
@error_reporting(E_ALL ^ E_NOTICE);  
ob_start();
session_start();  
 

include("../inc/fun_connect.php"); 
$ipaddress 		    = $_SERVER["REMOTE_ADDR"];
$datetime_cur       = date("Y-m-d H:i:s");
$DEPCODE            = "";
    
    if (isset($_GET["mode"])) {
        $mode   =  $_GET["mode"];
    }elseif(isset($_POST["mode"])){
        $mode   =  $_POST["mode"];
    } 


    if ($mode == "Login") {  
        $username   = $_POST["username"];
        $password   = $_POST["password"];
        $query_oper = " SELECT * from TB_USER where USERNAME ='".$username."' AND PASSWORD = '".$password."' AND STATUS = 'Y' ";   
        $getRes     = $conn_1->query($query_oper);
        $user_data  = $getRes->fetch();
 
        if(isset($user_data["USERNAME"])){ 
        $userName   = $user_data["USERNAME"];
        $userID     = $user_data["USERID"];
        $FSCode     = $user_data["FSCODE"];
        $DEPCODE    = $user_data["DEPARTCODE"];
       
            $actions                = "Login Success ";
            $_SESSION["USERID"]     = $userID;
            $_SESSION["USERNAME"]   = $userName; 
            $fscode                 = $FSCode;

            $sql_up         = " UPDATE TB_USER SET DATE_LAST = '".$datetime_cur."', IPADDRESS_LAST = '".$ipaddress."'  WHERE USERNAME = '".$userName ."'";
            $stmt_up        = $conn_1->prepare($sql_up);
            $stmt_up->execute(['DATE_LAST', 'IPADDRESS_LAST','USERNAME']);
 
            $sel_in                 = " INSERT INTO TB_USERLOG (USERID , USERNAME, FSCODE ,DATECUR ,ACTIONS , IPADDRESS ) ";
            $sel_in                 .= " VALUES('".$userID."' ,'".$userName."' ,'".$fscode."' , '".$datetime_cur."' ,'".$actions ."' ,'". $ipaddress."')";
            $res_insert             = $conn_1->query($sel_in);
             
            if($DEPCODE == 02){   
                echo "<script>location.replace('../account_sys/index.php');</script>";  
            }else  if($DEPCODE  ==  03){ 
                echo "<script>location.replace('../administrator_sys/index.php');</script>";  
            }else  if($DEPCODE  ==  "AM"){ 
                echo "<script>location.replace('../Report_PO_PA/index.php');</script>";  
            }else{
                echo "<script>location.replace('../operation_sys/index.php');</script>"; 
            } 
        }else{
            if (isset($_POST["username"])) {
                $username           = $_POST["username"];
            }
            
            $actions                = "Login  Fail";
            $sel_in                 = " INSERT INTO TB_USERLOG (USERID , USERNAME, FSCODE ,DATECUR ,ACTIONS , IPADDRESS ) ";
            $sel_in                 .= " VALUES('0' ,'".$username."' ,'0' , '".$datetime_cur."' ,'".$actions ."' ,'". $ipaddress."')";
            $res_insert             = $conn_1->query($sel_in);

            echo "<script>alert('Please check UserName and password.');</script>";
            echo "<script>location.replace('../frm_login.php');</script>";
        }
    }elseif($mode == "logoff"){
        
        $username   = $_GET["USERNAME"];
    
        $query_oper = " SELECT * from TB_USER where USERNAME ='".$username."' AND STATUS = 'Y' ";   
        $getRes     = $conn_1->query($query_oper);
        $user_data  = $getRes->fetch();
         
        $userID     = $user_data["USERID"];
        $FSCode     = $user_data["FSCODE"];
        
        $actions        = "LogOff ";

        $sql_up         = " UPDATE TB_USER SET DATE_LAST = '".$datetime_cur."', IPADDRESS_LAST = '".$ipaddress."'  WHERE USERNAME = '".$userName."'";
        $stmt_up        = $conn_1->prepare($sql_up);
        $stmt_up->execute(['DATE_LAST', 'IPADDRESS_LAST','USERNAME']);


        $sel_in         = " INSERT INTO TB_USERLOG (USERID , USERNAME, FSCODE ,DATECUR ,ACTIONS , IPADDRESS ) ";
        $sel_in         .= " VALUES('".$userID."' ,'".$username."' ,'".$FSCode."' , '".$datetime_cur."' ,'".$actions ."' ,'". $ipaddress."')";
        $res_insert     = $conn_1->query($sel_in);
   
        session_destroy();
      
        echo "<script>alert('ออกจากระบบ');</script>";
        echo "<script>location.replace('../frm_login.php');</script>";
    }elseif($mode == "Ch_password"){
        
        $password   = $_POST["password"];
        $Con_pass   = $_POST["con_password"];
        if($password  !=  $Con_pass){
            echo "<script>alert('กรุณายืนยัน   Password  ให้ถูกต้อง');</script>";
            echo "<script>location.replace('../frm_password.php');</script>";
        }else{
            $username   = $_POST["username"];
            $userid     = $_POST["userid"];
            $password   = $_POST["password"];
            $query_oper = " SELECT * from TB_USER where USERNAME ='".$username."' AND STATUS = 'Y' ";   
            $getRes     = $conn_1->query($query_oper);
            $user_data  = $getRes->fetch(); 
            $user       = $user_data["USERNAME"];
            $userID     = $user_data["USERID"];
            $fscode     = $user_data["FSCODE"];
            $DEPCODE    = $user_data["DEPARTCODE"];


            $sql_up         = " UPDATE TB_USER SET PASSWORD = '".$password."'   WHERE USERNAME = '".$username ."'";
            $stmt_up        = $conn_1->prepare($sql_up);
            $stmt_up->execute(['PASSWORD','USERNAME']);


            $actions                = "เปลี่ยน password ";
            $sel_in                 = " INSERT INTO TB_USERLOG (USERID , USERNAME, FSCODE ,DATECUR ,ACTIONS , IPADDRESS ) ";
            $sel_in                 .= " VALUES('".$userID."' ,'".$user."' ,'".$fscode."' , '".$datetime_cur."' ,'".$actions ."' ,'". $ipaddress."')";
            $res_insert             = $conn_1->query($sel_in);
 
            echo "<script>alert('เปลี่ยน Password  สำเร็จ กรุณาเข้าระบบอีกครั้ง');</script>";
            echo "<script>location.replace('../frm_login.php');</script>";
        } 
    }else{
        echo "<script>alert('Please check UserName.');</script>";
        echo "<script>location.replace('../frm_login.php');</script>";
    }
?>
 
  