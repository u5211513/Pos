<?php 
    ob_start();
    session_start(); 
    date_default_timezone_set('Asia/Bangkok'); 
    error_reporting(E_ALL ^ E_NOTICE);
    include("../inc/fun_connect.php"); 
    include("../account_sys/phpmail/phpmailer/PHPMailerAutoload.php"); 

    $ipaddress 		    = $_SERVER["REMOTE_ADDR"]; 
    $date_today           = date("Y-m-d H:i:s");
    if (isset($_GET["mode"])) {
        $mode   =  $_GET["mode"];
    }elseif(isset($_POST["mode"])){
        $mode   =  $_POST["mode"];
    }else{
        $mode   = "";
    }

    if ($mode == "INSTAFF") {
        $fullname   = $_POST["fullname"];
        $username   = $_POST["username"];
        $password   = $_POST["password"];
        $email      = $_POST["email"];
        $IDNO       = $_POST["IDNO"];
        $dep        = $_POST["dep"]; 
        $branch     = $_POST["branch"];
        $POSITIONID = $_POST["POSITIONID"];  
        $COMPANYID  = $_POST["COMPANYID"]; 
        $dateStart  = $_POST["DATE_START"];

        if (isset($_POST["Action_No"])) {
            $a_no    = "Y"; 
        }else{
            $a_no   = "N";
        }

        if (isset($_POST["Action_Edit"])) {
            $a_edit    = "Y"; 
        }else{
            $a_edit   = "N";
        }

        if (isset($_POST["Action_Create"])) {
            $a_create    = "Y"; 
        }else{
            $a_create   = "N";
        }
    
        if(isset($_POST["viewyes"])){
            $view       = "Y";
        } else if(isset($_POST["viewno"]) ){
            $view       = "N";
        }

        $date_cur       = date("Y-m-d H:i:s");
        $sel_in         = " INSERT INTO TB_USER (USERNAME ,PASSWORD , FULLNAME ,FSCODE  , POSITIONID , DATEREGISTER ,DATE_LAST ,IPADDRESS_LAST ,STATUS, COMPANYID,DEPTID , IDNO , DATE_START) ";
        $sel_in         .= " VALUES('".$username."' ,'".$password."' , '".$fullname."' ,'".$branch."'  ,'". $POSITIONID."', '".$date_cur."','".$date_cur."','".$ipaddress."','Y' , '".$COMPANYID."', '".$dep."','".$username."', '".$dateStart."')";
        $res_insert      = $conn_1->query($sel_in); 
         
        // $query_maxused = " SELECT MAX(USERID) AS USERM from TB_USER ";   
        // $getUsers     = $conn_1->query($query_maxused);
        // $user_data  = $getUsers->fetch();
 
        // $action_in         = " INSERT INTO TB_ACTION (A_Create,A_Edit,A_No,A_Report,USERID,DATEREGISTER) ";
        // $action_in         .= " VALUES('".$a_create."','".$a_edit."','".$a_no."','".$view."','".$user_data["USERM"]."','".$date_cur."')";
        // $action_insert      = $conn_1->query($action_in); 
   
        $aaction         = " เพิ่มพนักงาน"; 
        include("frm_memberLog.php");      

      
        $subject    = "  New Employee   ";
        $text_mess = "<p>  Please   Check  Your  system.  </p>";
        $text_mess .= "<p> </p>";
        $text_mess .= "<p> </p>";
        $text_mess .= "<p> </p>"; 
    
        $body_mess      = $text_mess;

        $mail               = new PHPMailer();
        $mail->Host         = "mail.win03-mailpro.zth.netdesignhost.com";
        $mail->isSMTP();
        $mail->SMTPDebug    = false;
        $mail->SMTPAuth     = true;
        $mail->sender_name  = "FACINO";  
        $mail->Username     = "IT_CENTER@fascino.co.th";
        $mail->Password     = "fW&6TR6q";
        $mail->Port         = 587; 
        $mail->Subject      = $subject ;
        $mail->isHTML(true);  
        $mail->Body         = $body_mess;
            

        $mail->setFrom('IT_CENTER@fascino.co.th', 'FACINO');   
        $mail->addAddress('programmer2@fashofgroup.com');


        if ($mail->send()){ 
            echo "<script>alert('mail is sent success!');</script>";
            echo "<script>location.replace('../administrator_sys/index.php');</script>";

        }else{
            echo $mail->ErrorInfo;
        }    
       
?>
    
<?php }   

    if($mode == "INDEPVIEW"){
       
        $userIDD            = $_POST["USER_ID"]; 
        if(isset($_POST["depview"])){
            for($ii=0; $ii < count($_POST["depview"]); $ii++){
                $id_dep             = $_POST["depview"][$ii]; 
                $action_in          = " INSERT INTO TB_ACTION (A_DEP,USERID,DATEREGISTER) ";
                $action_in         .= " VALUES('".$id_dep."','".$userIDD."','".$date_today."')";
                $action_insert      = $conn_1->query($action_in); 
            }
        }
        echo "<script>alert('Add Department success!');</script>";
        echo "<script>location.replace('../administrator_sys/index.php?action=EDIT&USER_ID=".$userIDD."');</script>";
    }

    if($mode == "BRANCHREPORT"){
       
        $userIDD            = $_POST["USER_ID"]; 
        $deletequery = "DELETE TB_USER_BRANCH WHERE USERID = '" .$userIDD. "'";
        $actiondelete = $conn_1->query($deletequery);
        if(isset($_POST["breportview"])){
            for($ii=0; $ii < count($_POST["breportview"]); $ii++){
                $id_dep             = $_POST["breportview"][$ii]; 
                $action_in          = " INSERT INTO TB_USER_BRANCH (USERID,FSCODE,INS_DATE,INS_USER) ";
                $action_in         .= " VALUES('".$userIDD."','".$id_dep."',GETDATE(),'".$_SESSION["USERID"]."')";
                $action_insert      = $conn_1->query($action_in); 
            }
        }
        echo "<script>alert('Add Branch success!');</script>";
        echo "<script>location.replace('../administrator_sys/index.php?action=EDIT&USER_ID=".$userIDD."');</script>";
    }

    if ($mode == "INBANKOFSTATION") { 
        
        $branch_code    = $_POST["branch_code"];
        $bank_name      = $_POST["bank_name"]; 
        $userId         = $_SESSION["USERID"];
        $username       = $_SESSION["USERNAME"]; 
        
        $date_cur           = date("Y-m-d H:i:s");
        $bank_in            = " INSERT INTO TB_BANKOFSTATION (FSCODE ,BANKNAME  , DATEREGISTER ,STATUS) ";
        $bank_in            .= " VALUES('".$branch_code."' ,'".$bank_name."' , '".$date_cur."' ,'Y')";
        $bank_insert        = $conn_1->query($bank_in); 
         
        $aaction         = " เพิ่มชื่อธนาคาร ".$bank_name." สำหรับสาขา ".$branch_code."  "; 
        include("frm_memberLog.php"); 
    } 

    if($mode == "modify"){
        $BANKNAME       = $_POST["bank_name"];
        $BANKID         = $_POST["bankkid"];
        $sql_up         = " UPDATE TB_BANKOFSTATION SET BANKNAME = '".$BANKNAME."'   WHERE BANKID = '".$BANKID ."'";
        $stmt_up        = $conn_1->prepare($sql_up);
        $stmt_up->execute(['BANKNAME','BANKID']);
    }

    if($mode == "CANCELBANKOFSTATION"){
        $STATUS         = $_POST["STATUS"];
        $BANKID         = $_POST["BANKID"];
        $sql_up         = " UPDATE TB_BANKOFSTATION SET STATUS = '".$STATUS."'   WHERE BANKID = '".$BANKID ."'";
        $stmt_up        = $conn_1->prepare($sql_up);
        $stmt_up->execute(['PASSWORD','USERNAME']);

    }  
    
    if($mode == "deactive"){
        $STATUS         = $_POST["STATUS"];
        $BANKID         = $_POST["USERID"];
        $sql_up         = " UPDATE TB_USER SET STATUS = '".$STATUS."'   WHERE USERID = '".$USERID ."'";
        $stmt_up        = $conn_1->prepare($sql_up);
        $stmt_up->execute(['STATUS','USERID']);
    }

    if($mode == "ADDREMARK"){
        $topic          = $_POST["topic"]; 
        $userId         = $_SESSION["USERID"];
        $username       = $_SESSION["USERNAME"]; 
        
        $date_cur           = date("Y-m-d H:i:s");
        $remark_in          = " INSERT INTO TB_TOPICREMARK (TOPICREMARK , ACCOUNT  , DATEREGISTER ,STATUS) ";
        $remark_in          .= " VALUES('".$topic."' ,'".$username."' , '".$date_cur."' ,'Y')";
        $topic_insert        = $conn_1->query($remark_in); 
         
        $aaction         = " เพิ่มชื่อหัวข้อ ".$topic; 
        include("frm_memberLog.php"); 
    }

    if($mode == "modifyTopic"){
        $topic          = $_POST["topic"];
        $topic_Id         = $_POST["topic_Id"];
        $sql_up         = " UPDATE TB_TOPICREMARK SET TOPICREMARK = '".$topic."'   WHERE TOPICID = '".$topic_Id ."'";
        $stmt_up        = $conn_1->prepare($sql_up);
        $stmt_up->execute(['BANKNAME','BANKID']);
    }


    if($mode == "MODIFYSTAFF"){
        $fullname   = $_POST["fullname"]; 
        $email      = $_POST["email"];
        $IDNO       = $_POST["IDNO"];
        $COMPANYID  = $_POST["COMPANYID"]; 
        $dep        = $_POST["dep"]; 
        $POSITIONID = $_POST["POSITIONID"];
        $branch     = $_POST["branch"]; 
        $dateStart  = $_POST["DATE_START"];
        $USERID     = $_POST["USERID"];

        $sql_up         = " UPDATE TB_USER SET FULLNAME = '".$fullname."', IDNO = '".$IDNO."' , EMAIL = '".$email."' , COMPANYID = '".$COMPANYID."' , DEPTID = '".$dep."', POSITIONID = '".$POSITIONID."' , FSCODE = '".$branch."'  WHERE USERID = '".$USERID ."'";
        $stmt_up        = $conn_1->prepare($sql_up);
        $stmt_up->execute(['FULLNAME','IDNO','EMAIL','COMPANYID','DEPTID','POSITIONID','FSCODE']);
    }




?>