<?php $part  = "../";?>
<script src="<?php echo $part;?>plugins/jquery/jquery.min.js"></script> 
<?php  
ob_start();
session_start();  
 
@error_reporting(E_ALL ^ E_NOTICE);  
include("../inc/fun_connect.php"); 
$ipaddress 		    = $_SERVER["REMOTE_ADDR"];
$datetime_cur       = date("Y-m-d H:i:s");
$DEPCODE            = "";
    if (isset($_POST["mode"]) == "Login") {  
        $username   = $_POST["username"];
        $password   = $_POST["password"];
        $query_oper = " SELECT * from TB_USER where USERNAME ='".$username."' AND PASSWORD = '".$password."' AND STATUS = 'Y' ";   
        $getRes     = $conn_1->query($query_oper);
        $user_data  = $getRes->fetch();
        $userName   = $user_data["USERNAME"];
        $userID     = $user_data["USERID"];
        $FSCode     = $user_data["FSCODE"];
        $DEPCODE    = $user_data["DEPARTCODE"];
        if($userName != ""){
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
            }else{
                echo "<script>location.replace('../operation_sys/index.php');</script>"; 
            } 
        }else{
            $actions                = "Login  Fail";
            $sel_in                 = " INSERT INTO TB_USERLOG (USERID , USERNAME, FSCODE ,DATECUR ,ACTIONS , IPADDRESS ) ";
            $sel_in                 .= " VALUES('".$userID."' ,'".$_POST["username"]."' ,'".$fscode."' , '".$datetime_cur."' ,'".$actions ."' ,'". $ipaddress."')";
            $res_insert             = $conn_1->query($sel_in);

            echo "<script>alert('Please check UserName.');</script>";
            echo "<script>location.replace('../frm_login.php');</script>";
        }
    }else if(isset($_GET["mode"]) == "logoff"){
        $username   = $_GET["USERNAME"];
    
        $query_oper = " SELECT * from TB_USER where USERNAME ='".$username."' AND STATUS = 'Y' ";   
        $getRes     = $conn_1->query($query_oper);
        $user_data  = $getRes->fetch();
        $userName   = $user_data["USERNAME"];
        $userID     = $user_data["USERID"];
        $FSCode     = $user_data["FSCODE"];
        session_destroy();
        $actions        = "LogOff ";

        $sql_up         = " UPDATE TB_USER SET DATE_LAST = '".$datetime_cur."', IPADDRESS_LAST = '".$ipaddress."'  WHERE USERNAME = '".$userName."'";
        $stmt_up        = $conn_1->prepare($sql_up);
        $stmt_up->execute(['DATE_LAST', 'IPADDRESS_LAST','USERNAME']);


        $sel_in         = " INSERT INTO TB_USERLOG (USERID , USERNAME, FSCODE ,DATECUR ,ACTIONS , IPADDRESS ) ";
        $sel_in         .= " VALUES('".$userID."' ,'".$userName."' ,'".$FSCode."' , '".$datetime_cur."' ,'".$actions ."' ,'". $ipaddress."')";
        $res_insert     = $conn_1->query($sel_in);

        echo "<script>alert('ออกจากระบบ');</script>";
        echo "<script>location.replace('../frm_login.php');</script>";
 
    }else{
        echo "<script>alert('Please check UserName.');</script>";
        echo "<script>location.replace('../frm_login.php');</script>";
    }
?>
 
  