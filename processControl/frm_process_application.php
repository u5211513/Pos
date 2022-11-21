<?php
    ob_start();
    session_start();  
    error_reporting(E_ALL ^ E_NOTICE);
    include("../inc/fun_connect.php");  
    $USERID         = $_SESSION["USERID"];
    $USERNAME       = $_SESSION["USERNAME"];
    $username       = $USERNAME;
    $ipaddress      = $_SERVER["REMOTE_ADDR"]; 

    if($_POST["mode"] == "SAVE"){  
        $POS_ID         = $_POST["POS_ID"]; 
        $namee          = $_POST["name"]; 
        $createdate     = date("Y-m-d H:i:s");
       
        for($ii=0; $ii<= count($_POST["APP_ID"]); $ii++){
           $app_id         = $_POST["APP_ID"][$ii];
            
            if($app_id != ""){
                $app_add         = " INSERT INTO TB_APP_POSITION (POSITIONID , APP_ID ) ";
                $app_add         .= " VALUES('".$POS_ID."','".$app_id."')"; 
                $app_insert      = $conn_1->query($app_add); 
            }
        } 
        echo "<script>location.replace('../operation_sys/frm_Assest_addApplication.php?position=".$POS_ID."&Name=".$name."');</script>";
    }
?>