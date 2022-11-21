<?php 
    ob_start();
    session_start(); 
    @error_reporting(E_ALL ^ E_NOTICE);  
    include("../inc/fun_connect.php"); 

    if($_POST["mode"] == "SAVE"){
        for($ii=0; $ii<= count($_POST["mail"]); $ii++){
            $mail           = $_POST["mail"][$ii];  
            $status         ="Y";
            if($mail != ""){  
                $add__mail       = " INSERT INTO TB_MAILSEND (MAIL_NAME ,MAIL_STATUS) ";
                $add__mail       .= " VALUES('".$mail."','".$status."')";
                $insert__mail     = $conn_1->query($add__mail); 
            }
        } 
        echo "<script>alert('ADD  Email Success.');</script>";
        echo "<script>location.replace('../administrator_sys/frm_maillist.php');</script>";
    }    



    if($_GET["actionMode"] == "Delete"){
        
        $sql_mail        = " DELETE FROM TB_MAILSEND WHERE  MAILID = '".$_GET["MAILID"]."'"; 
        
        $query_delMail       = $conn_1->prepare($sql_mail);
        $query_delMail->execute(['MAILID']); 
        
        echo "<script>alert('Delete Success.');</script>";
        echo "<script>location.replace('../administrator_sys/frm_maillist.php');</script>";
    }



    if($_GET["actionMode"] == "Suspend"){
        
        $sql_mail        = " UPDATE TB_MAILSEND  SET MAIL_STATUS = '".$_GET["status"]."'  WHERE  MAILID = '".$_GET["MAILID"]."'"; 
        
        $query_upMail       = $conn_1->prepare($sql_mail);
        $query_upMail->execute(['MAIL_STATUS','MAILID']); 

 
        echo "<script>alert('Suspend Success.');</script>";
        echo "<script>location.replace('../administrator_sys/frm_maillist.php');</script>";
    }
?>