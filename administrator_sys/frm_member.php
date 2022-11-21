<?php 
@error_reporting(E_ALL ^ E_NOTICE);
    $USERID     = $_SESSION["USERID"];
    $USERNAME   = $_SESSION["USERNAME"];
    
    $query_oper = " SELECT * from TB_USER where USERNAME ='".$USERNAME."'  AND   USERID = '".$USERID."'";   
    $getRes     = $conn_1->query($query_oper);
    $user_data  = $getRes->fetch();
    $USERNAMES  = $user_data["USERNAME"];
    $FULLNAME   = $user_data["FULLNAME"];
    $USERIDS    = $user_data["USERID"];
    $FSCODE     = $user_data["FSCODE"];
    $DEPARTCODE = $user_data["DEPARTCODE"];
    
?>