<?php  
 
    ob_start();
    session_start();  
    error_reporting(E_ALL ^ E_NOTICE);
    include("../inc/fun_connect.php");  
    if(isset($_POST["STATUSID"]))  
    {   
        $query_status       = " SELECT  *  from  TB_STATUS  WHERE  STATUSID = '".$_POST["STATUSID"]."'";  
        $res_sattus         = $conn_1->query( $query_status ); 
        $status_data        = $res_sattus->fetch();
        echo json_encode($status_data); 
        
    }  

    if(isset($_POST["CATEGORIESID"]))  
    {   
        $query_cat       = " SELECT  *  from  TB_CATEGORIES  WHERE  CATEGORIESID = '".$_POST["CATEGORIESID"]."'";  
        $res_cat         = $conn_1->query( $query_cat ); 
        $cat_data        = $res_cat->fetch();
        echo json_encode($cat_data);  
    }  

    if(isset($_POST["COMPANYID"]))  
    {   
        $query_com       = " SELECT  *  from  TB_COMPANY  WHERE  COMPANYID = '".$_POST["COMPANYID"]."'";  
        $res_com         = $conn_1->query( $query_com ); 
        $com_data        = $res_com->fetch();
        echo json_encode($com_data);   
    }  

    if(isset($_POST["POSITIONID"]))  
    {   
        $query_position       = " SELECT  *  from  TB_POSITION  WHERE  POSITIONID = '".$_POST["POSITIONID"]."'";  
        $res_position         = $conn_1->query( $query_position ); 
        $position_data        = $res_position->fetch();
        echo json_encode($position_data);   
    }  

    ?>