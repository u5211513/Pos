<?php
ob_start();
session_start();  
error_reporting(E_ALL ^ E_NOTICE);
include("../inc/fun_connect.php");  
$USERID         = $_SESSION["USERID"];
$USERNAME       = $_SESSION["USERNAME"];
$username       = $USERNAME;
$ipaddress      = $_SERVER["REMOTE_ADDR"]; 

if ($_POST["mode"] == "ADDCate") { 
    $cat          = $_POST["cat"];      
    $date_cur       = date("Y-m-d H:i:s");
    if($_POST["CATEGORIESID"] != '')   {  
        $sql_assest       = " UPDATE TB_CATEGORIES SET  CATEGORIESNAME  = '".$_POST["cat"]."' WHERE CATEGORIESID = '".$_POST["CATEGORIESID"]."'  ";
        $stmt_up         = $conn_1->prepare($sql_assest);
        $stmt_up->execute(['CATEGORIESNAME', 'CATEGORIESID'] );
        $message    = 'Data Updated';  
        
    }else{

        $cate         = " INSERT INTO TB_CATEGORIES (CATEGORIESNAME , CATEGORIES_STATUS , CREATE_DATE, CREATE_BY) ";
        $cate         .= " VALUES('".$cat."','Y','".$date_cur."','".$USERID ."')";
        $insert__cat  = $conn_1->query($cate); 
    }
     
}

if ($_POST["mode"] == "ADDCOMPANY") { 
    $com          = $_POST["com"];      
    $date_cur       = date("Y-m-d H:i:s");

    if($_POST["COMPANYID"] != '')   {  
        $sql_assest       = " UPDATE TB_COMPANY SET  COMPANY_NAME  = '".$_POST["com"]."' WHERE COMPANYID = '".$_POST["COMPANYID"]."'  ";
        $stmt_up         = $conn_1->prepare($sql_assest);
        $stmt_up->execute(['COMPANY_NAME', 'COMPANYID'] );
        $message    = 'Data Updated';  
        
    }else{
        $company         = " INSERT INTO TB_COMPANY (COMPANY_NAME , COMPANY_STATUS , CREATE_DATE, CREATE_BY) ";
        $company         .= " VALUES('".$com."','Y','".$date_cur."','".$USERID ."')";
        $insert__com  = $conn_1->query($company); 
    }
}

if ($_POST["mode"] == "ADDSTATUS") { 
    $status             = $_POST["status"];  
    $status_id          = $_POST["id"];    
    $date_cur           = date("Y-m-d H:i:s");
    
    if($_POST["STATUSID"] != '')   {  
        $sql_assest       = " UPDATE TB_STATUS SET  STATUS_NAME  = '".$_POST["status"]."' WHERE STATUSID = '".$_POST["STATUSID"]."'  ";
        $stmt_up         = $conn_1->prepare($sql_assest);
        $stmt_up->execute(['STATUS_NAME', 'STATUSID'] );
        $message    = 'Data Updated';  
        
    }else{
        $add__status        = " INSERT INTO TB_STATUS (STATUS_NAME , STATUS_STATUS , CREATE_DATE, CREATE_BY) ";
        $add__status        .= " VALUES('".$status."','Y','".$date_cur."','".$USERID ."')";
        $insert__status     = $conn_1->query($add__status); 
    }     
}


if ($_POST["mode"] == "ADDDEP") { 
    $depname             = $_POST["depname"];  
    $DEPTID             = $_POST["id"];    
    $date_cur           = date("Y-m-d H:i:s");
    
    if($_POST["DEPTID"] != '')   {  
        $sql_dep       = " UPDATE TB_DEPT SET  DEPT_NAME  = '".$_POST["depname"]."' WHERE DEPTID = '".$_POST["DEPTID"]."'  ";
        $stmt_dep         = $conn_1->prepare($sql_dep);
        $stmt_dep->execute(['DEPT_NAME', 'DEPTID'] );
        $message    = 'Data Updated';  
        
    }else{
        $add__dep        = " INSERT INTO TB_DEPT (DEPT_NAME ,DEP_STATUS , CREATE_DATE, CREATE_BY) ";
        $add__dep        .= " VALUES('".$depname."','Y','".$date_cur."','".$USERID ."')";
        $insert__dep     = $conn_1->query($add__dep); 
    }     
}


if ($_POST["mode"] == "ADDPOSITION") { 
    $positionname               = $_POST["positionname"];  
    $POSITIONID                 = $_POST["POSITIONID"];    
    $date_cur                   = date("Y-m-d H:i:s");
    
    if($_POST["POSITIONID"] != '')   {  
        $sql_position       = " UPDATE TB_POSITION SET  POSITION_NAME  = '".$_POST["positionname"]."' WHERE POSITIONID = '".$_POST["POSITIONID"]."'  ";
        $stmt_position      = $conn_1->prepare($sql_position);
        $stmt_position->execute(['POSITION_NAME', 'POSITIONID'] );
        $message    = 'Data Updated';  
        
    }else{
        $add__position        = " INSERT INTO TB_POSITION (POSITION_NAME ,POSITION_STATUS , CREATE_DATE, CREATE_BY) ";
        $add__position        .= " VALUES('".$positionname."','Y','".$date_cur."','".$USERID ."')";
        $insert__position     = $conn_1->query($add__position); 
    }     
}





?>