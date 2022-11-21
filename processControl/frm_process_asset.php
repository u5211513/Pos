<?php

ob_start();
session_start();  
error_reporting(E_ALL ^ E_NOTICE);
include("../inc/fun_connect.php");  
$USERID         = $_SESSION["USERID"];
$USERNAME       = $_SESSION["USERNAME"];
$username       = $USERNAME;
$ipaddress      = $_SERVER["REMOTE_ADDR"]; 

if ($_POST["mode"] == "INFRMASSET") { 
   
    $COMPANYID          = $_POST["COMPANYID"];     
    $CATEGORIESID       = $_POST["CATEGORIESID"];          
    $ITEMNAME           = $_POST["ITEMNAME"];     
    $STATUSID           = $_POST["STATUSID"];    
    $DETAIL             = $_POST["DETAIL"];       
    $SERIAL_NUMBER      = $_POST["SERIAL_NUMBER"];     
    $PO_ID              = $_POST["PO_ID"]; 
    $createdate         = date("Y-m-d H:i:s");//$_POST["createdate"];
    $receivedate        = $_POST["receivedate"]; 
    $mobile             = $_POST["MOBILE"];
    $issest_no          = $_POST["ASSEST_NO"];
    

    $ym_c               = "AN".date("ym");   
    $query_codeAN     = " SELECT  ASSEST_NUMBER from TB_ASSEST  WHERE ASSEST_NUMBER Like '".$ym_c."%'  ORDER BY  ASSEST_NUMBER  DESC ";     
    $res_codeAN        = $conn_1->query( $query_codeAN ); 
    $codeAN_data       = $res_codeAN->fetch();
    if (empty($codeAN_data["ASSEST_NUMBER"])) {
        $run_num     = "0001";
    } else { 
        $code_run       = substr($codeAN_data["ASSEST_NUMBER"],6,10);    
        $run_num        = str_pad($code_run+1 ,4, "0", STR_PAD_LEFT); 
    }
    $codeAN            = $ym_c.$run_num; 

    $date_cur       = date("Y-m-d H:i:s");
    $assest         = " INSERT INTO TB_ASSEST (COMPANY_ID , CATEGORIESID ,ITEMNAME , STATUS , DETAIL ,SERIAL_NUMBER , CREATE_DATE,PO_ID ,RECEIVE_DATE, CREATE_BY, MOBILE, ASSEST_NO , ASSEST_NUMBER ) ";
    $assest         .= " VALUES('".$COMPANYID."','".$CATEGORIESID."','".$ITEMNAME."','".$STATUSID."','". $DETAIL."','".$SERIAL_NUMBER."','".$createdate."','".$PO_ID."' ,'".$receivedate."','".$USERNAME."' , '".$mobile."' ,'".$issest_no."','".$codeAN."')";
    $assest_insert  = $conn_1->query($assest); 
   
     
    $aaction         = " เพิ่ม Assest". $ITEMNAME; 
    include("frm_memberLog.php");
}
?>
<?php
if($_POST["mode"] == "InsertASS"){
    $ASSESTID       = $_POST["ASSESTID"];   
    $note           = $_POST["note"];
    $status         = $_POST["STATUSID"];
    $resin          = $_POST["USERID"];
    $createdate     = date("Y-m-d H:i:s");
    $assestused         = " INSERT INTO TB_ASSESTUSED (USERID , ASSESTID ,CREATE_DATE, CREATE_BY ,STATUS, NOTE) ";
    $assestused         .= " VALUES('".$resin."','".$ASSESTID."','".$createdate."','".$USERNAME."','".$status."' ,'".$note."')";

    $assestused_insert  = $conn_1->query($assestused); 
    $query_used     = " SELECT  * from TB_ASSESTUSED a inner join TB_ASSEST b on a.ASSESTID = b.ASSESTID   
                        WHERE  a.USERID = '".$resin."'
                        ORDER BY ASSEST_USEDID DESC";  

    $sql_usedassest = $conn_1->query( $query_used ); 

    $sql_asseststatus       = " UPDATE TB_ASSEST SET  STATUS  = '".$status."' WHERE ASSESTID = '".$ASSESTID."'  ";
    $stmt_upassest          = $conn_1->prepare($sql_asseststatus);
    $stmt_upassest->execute(['STATUS'] );

?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>รายการทรัพย์สิน</th>
                <th> Doc </th>
                <th>  Note </th>
                <th>วันที่เพิ่มทรัพย์สิน</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $r = 1;
                foreach ($conn_1->query($query_used) as $assest) {?>
                <tr>
                    <td><?php echo $r;?></td>
                    <td><?php echo $assest["ITEMNAME"];?></td>
                    <td><?php echo $assest["ASSESTDOC_NO"];?></td>
                    <td><?php echo $assest["NOTE"];?></td>
                    <td><?php echo date("d/m/Y H:i:s" , strtotime($assest["CREATE_DATE"]));?></td>
                </tr>
            <?php  $r++;} ?>
        </tbody>
    </table> 
<?php } ?>

<?php if($_POST["mode"]  == "INFRDOC"){ 
    $ym_c           = "AS".date("ym");   
    $query_code     = " SELECT  ASSESTDOC_NO from TB_ASSESTDOC  WHERE ASSESTDOC_NO Like '".$ym_c."%'  ORDER BY  ASSESTDOC_NO  DESC ";     
    $res_code        = $conn_1->query( $query_code ); 
    $code_data       = $res_code->fetch();
    if (empty($code_data["ASSESTDOC_NO"])) {
        $run_num     = "001";
    } else { 
        $code_run       = substr($code_data["ASSESTDOC_NO"],6,9);    
        $run_num        = @str_pad($code_run+1 ,3, "0", STR_PAD_LEFT); 
    }
    $codeAss            = $ym_c.$run_num; 
    $USERIDD           = $_POST["USERID"]; 
    $createdate         = date("Y-m-d H:i:s");
    if(isset($_POST["Ac"]) == "CUT"){
        $cut        = "Y";
    }else{
        $cut        = "N";
    }
    $assestused         = " INSERT INTO TB_ASSESTDOC (ASSESTDOC_NO , USERID ,DATE_OUT, USEROUT_ID,CUT) ";
    $assestused         .= " VALUES('".$codeAss."','".$USERIDD."','".$createdate."','".$USERID."','".$cut."')";
   
    $assest_insert      = $conn_1->query($assestused); 

    $query_ass          = " SELECT * from TB_ASSESTDOC   ORDER BY  ASSESTDOC_ID  DESC ";  

    $ass_res        = $conn_1->query( $query_ass ); 
    $res_data       = $ass_res->fetch();
    $ASSESTDOC_ID    = $res_data["ASSESTDOC_ID"];
    $ASSESTDOC_NO    = $res_data["ASSESTDOC_NO"];
    $query_assused      = " SELECT * from TB_ASSESTUSED  where USERID = '".$USERIDD."' "; 
    foreach ($conn_1->query($query_assused) as $assest) {
        
        if($assest["ASSESTDOC_ID"] == ""){
            $sql_assest       = " UPDATE TB_ASSESTUSED SET ASSESTDOC_ID = '".$ASSESTDOC_ID."' , ASSESTDOC_NO = '".$ASSESTDOC_NO."'   WHERE USERID = '".$USERIDD."'   and   ASSEST_USEDID = '".$assest["ASSEST_USEDID"]."' ";
            $stmt_up         = $conn_1->prepare($sql_assest);
            $stmt_up->execute(['ASSESTDOC_ID', 'ASSESTDOC_NO'] );

            $sql_assest       = " UPDATE TB_ASSEST SET  STATUS  = '' WHERE ASSESTID = '".$assest["ASSEST_USEDID"]."'  ";
            $stmt_up         = $conn_1->prepare($sql_assest);
            $stmt_up->execute(['ASSESTDOC_ID', 'ASSESTDOC_NO'] );
        }
    }
    $data  = array();
    $data["doc_id"]     = $ASSESTDOC_ID ;
    //echo $data["doc_id"] ;
    //echo "<script>location.replace('../operation_sys/frm_assest_doc.php?doc=".$res_data["ASSESTDOC_ID"]."');</script>";

?>
     <section class="col-lg-6 connectedSortable"> 
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">    </h3>
            </div>  
            <div class="card-body">  
                <div class="form-group">
                    <label>    ปริ้นใบทรัพย์สิน    </label>  
                </div>  
                <div class="form-group"> 
                    <a href="../operation_sys/frm_assest_doc.php?docccc=<?php echo $res_data["ASSESTDOC_ID"];?>&user=<?php echo $USERIDD?>" class="btn btn-primary"> PRINT </a></button>  
                </div>
            </div> 
        </div>
    </section> 
<?php }?>

<?php if($_POST["mode"] == "ReASS"){
    $ASSESTID       = $_POST["ASSESTID_UPDATE"];  
    $status         = $_POST["STATUSID"];
    $createdate     = date("Y-m-d H:i:s");
    $dateStop       = $_POST["dateStop"];
    $resin          = $_POST["USERID"];

    $sql_asseststatus       = " UPDATE TB_ASSESTDOC SET  USERIN_ID  = '".$USERID ."' , DATE_IN = '".$createdate  ."' , DATE_EM_STOP = '".$dateStop."' WHERE USERID = '".$resin."'  AND ASSESTDOC_ID = '".$ASSESTID."' ";
    $stmt_upassest          = $conn_1->prepare($sql_asseststatus);
    $stmt_upassest->execute(['USERIN_ID','DATE_IN','DATE_EM_STOP'] );
 

    /*$query_assused      = " SELECT * from TB_ASSESTUSED  where USERID = '".$resin."' "; 
    foreach ($conn_1->query($query_assused) as $assest) { 
        $sql_assest       = " UPDATE TB_ASSEST SET  STATUS  = '".$status."' WHERE ASSESTID = '".$assest["ASSESTID"]."'  ";
        $stmt_up         = $conn_1->prepare($sql_assest);
        $stmt_up->execute(['STATUS', 'ASSESTID'] );
        
    }*/

    $query_ass          = " SELECT * from TB_ASSESTDOC  where USERID = '".$resin."'  ORDER BY  ASSESTDOC_ID  DESC ";  
    $ass_res            = $conn_1->query( $query_ass ); 
    $res_data           = $ass_res->fetch();
    $ASSESTDOC_ID       = $res_data["ASSESTDOC_ID"];
    

    echo "<script>location.replace('../operation_sys/frm_Assest_doc.php?user=".$resin."&docccc=".$ASSESTID."');</script>";
} ?>
<?php if($_POST["mode"]  == "INFRDOCCUT"){ 
    
    $ym_c           = "AS".date("ym");   
    $query_code     = " SELECT  ASSESTDOC_NO from TB_ASSESTDOC  WHERE ASSESTDOC_NO Like '".$ym_c."%'  ORDER BY  ASSESTDOC_NO  DESC ";     
    $res_code        = $conn_1->query( $query_code ); 
    $code_data       = $res_code->fetch();
    if (empty($code_data["ASSESTDOC_NO"])) {
        $run_num     = "001";
    } else { 
        $code_run       = substr($code_data["ASSESTDOC_NO"],6,9);    
        $run_num        = @str_pad($code_run+1 ,3, "0", STR_PAD_LEFT); 
    }
    $codeAss            = $ym_c.$run_num; 
    $fscode             = $_POST["branch"];
    $location           = $_POST["location"];
    $mobile             = $_POST["mobile"];
    $note               = $_POST["note"];
    $createdate         = date("Y-m-d H:i:s");
    $type               = $_POST["typee"];

    if(isset($_POST["branch_stop"])){
        $fscode_stop        = $_POST["branch_stop"];  
    }else{ $fscode_stop        =  "";}
    if(isset($_POST["Ac"]) == "CUT"){
        $cut        = "Y";
    }else{
        $cut        = "N";
    }
    $assestused         = " INSERT INTO TB_ASSESTDOC (ASSESTDOC_NO , USERID ,DATE_OUT, USEROUT_ID,CUT, FSCODE, LOCATION, MOBILE, NOTE ,FSCODE_STOP) ";
    $assestused         .= " VALUES('".$codeAss."','0','".$createdate."','".$USERID."','".$cut."','".$fscode."','".$location."','".$mobile."','".$note."','".$fscode_stop."')";
    $assest_insert      = $conn_1->query($assestused);  
     
    $query_ass          = " SELECT * from TB_ASSESTDOC   ORDER BY  ASSESTDOC_ID  DESC ";   
    $ass_res        = $conn_1->query( $query_ass ); 
    $res_data       = $ass_res->fetch();
    $ASSESTDOC_ID    = $res_data["ASSESTDOC_ID"];
    $ASSESTDOC_NO    = $res_data["ASSESTDOC_NO"];
     
    $query_assused      = " SELECT * from TB_ASSESTUSED "; 
    foreach ($conn_1->query($query_assused) as $assest) {
        if($assest["ASSESTDOC_ID"] == ""){
            $sql_assest       = " UPDATE TB_ASSESTUSED SET ASSESTDOC_ID = '".$ASSESTDOC_ID."' , ASSESTDOC_NO = '".$ASSESTDOC_NO."', STATUS = '".$type."'   WHERE ASSEST_USEDID = '".$assest["ASSEST_USEDID"]."' ";
            $stmt_up         = $conn_1->prepare($sql_assest);
            $stmt_up->execute(['ASSESTDOC_ID', 'ASSESTDOC_NO'] );

            $sql_assest1       = " UPDATE TB_ASSEST SET  STATUS  = '".$type."' WHERE ASSESTID = '".$assest["ASSEST_USEDID"]."'  ";
            $stmt_up1         = $conn_1->prepare($sql_assest1);
            $stmt_up1->execute(['STATUS', 'ASSESTID'] );
        }
    }
?>
   <section class="col-lg-6 connectedSortable"> 
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">    </h3>
            </div>  
            <div class="card-body">  
                <div class="form-group">
                    <label>    ปริ้นใบทรัพย์สิน    </label>  
                </div>  
                <div class="form-group"> 
                    <a href="../operation_sys/frm_assest_cutdoc.php?docccc=<?php echo $res_data["ASSESTDOC_ID"];?>" class="btn btn-primary"> PRINT </a></button>  
                </div>
            </div> 
        </div>
    </section> 
<?php }?>

<?php  if($_POST["mode"] == "MODIFYASSEST"){ 

    $COMPANYID          = $_POST["COMPANYID"];     
    $CATEGORIESID       = $_POST["CATEGORIESID"];          
    $ITEMNAME           = $_POST["ITEMNAME"];     
    $STATUSID           = $_POST["STATUSID"];    
    $DETAIL             = $_POST["DETAIL"];       
    $SERIAL_NUMBER      = $_POST["SERIAL_NUMBER"];     
    $PO_ID              = $_POST["PO_ID"]; 
    $createdate         = $_POST["createdate"];
    $receivedate        = $_POST["receivedate"]; 
    $mobile             = $_POST["MOBILE"];
    $issest_no          = $_POST["ASSEST_NO"];
    $ASSESTID           = $_POST["ASSESTID"];


    $sql_asseststatus       = " UPDATE TB_ASSEST SET  COMPANY_ID  = '".$COMPANYID ."' , CATEGORIESID = '".$CATEGORIESID  ."' , ITEMNAME = '".$ITEMNAME."' 
                                ,STATUS  = '".$STATUSID ."' , DETAIL = '".$DETAIL  ."' , SERIAL_NUMBER = '".$SERIAL_NUMBER."'
                                ,PO_ID  = '".$PO_ID ."' , CREATE_DATE = '".$createdate  ."' , RECEIVE_DATE = '".$receivedate."'
                                ,MOBILE  = '".$mobile ."' , ASSEST_NO = '".$issest_no  ."' 
                                WHERE ASSESTID = '".$ASSESTID."'  ";
    $stmt_upassest          = $conn_1->prepare($sql_asseststatus);
    $stmt_upassest->execute(['COMPANY_ID','CATEGORIESID','ITEMNAME','STATUS','DETAIL','SERIAL_NUMBER','PO_ID','CREATE_DATE','RECEIVE_DATE','MOBILE','ASSEST_NO'] );


} ?>

<?php
if($_POST["mode"] == "InsertASSCUT"){
    $ASSESTID       = $_POST["ASSESTID"];   
    $note           = $_POST["note"];
    $status         = $_POST["STATUSID"];
    $resin          ='0';
    $createdate     = date("Y-m-d H:i:s");
    $assestused         = " INSERT INTO TB_ASSESTUSED (USERID , ASSESTID ,CREATE_DATE, CREATE_BY ,STATUS, NOTE) ";
    $assestused         .= " VALUES('".$resin."','".$ASSESTID."','".$createdate."','".$USERNAME."','".$status."' ,'".$note."')";

    $assestused_insert  = $conn_1->query($assestused); 

    $query_used     = " SELECT  * from TB_ASSESTUSED a inner join TB_ASSEST b on a.ASSESTID = b.ASSESTID  where  ASSESTDOC_ID  is null  ORDER BY ASSEST_USEDID DESC";  

    $sql_asseststatus       = " UPDATE TB_ASSEST SET  STATUS  = '".$status."' WHERE ASSESTID = '".$ASSESTID."'  ";
    $stmt_upassest          = $conn_1->prepare($sql_asseststatus);
    $stmt_upassest->execute(['STATUS'] );

?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>รายการทรัพย์สิน</th>
                <th> Doc </th>
                <th>  Note </th>
                <th>วันที่เพิ่มทรัพย์สิน</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $r = 1;
                foreach ($conn_1->query($query_used) as $assest) {?>
                <tr>
                    <td><?php echo $r;?></td>
                    <td><?php echo $assest["ITEMNAME"];?></td>
                    <td><?php echo $assest["ASSESTDOC_NO"];?></td>
                    <td><?php echo $assest["NOTE"];?></td>
                    <td><?php echo date("d/m/Y H:i:s" , strtotime($assest["CREATE_DATE"]));?></td>
                </tr>
            <?php  $r++;} ?>
        </tbody>
    </table> 
<?php } ?>
