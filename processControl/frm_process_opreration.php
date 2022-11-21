<?php
ob_start();
session_start();  
@error_reporting(E_ALL ^ E_NOTICE);
include("../inc/fun_connect.php");  
$USERID         = $_SESSION["USERID"];
$USERNAME       = $_SESSION["USERNAME"];
$username       = $USERNAME;
$ipaddress      = $_SERVER["REMOTE_ADDR"]; 

if ($_POST["mode"] == "INFRM") {

    $branch_code    = $_POST["branch_code"];     
    $date_used      = $_POST["date_used"];          
    $bank_type      = $_POST["bank_type"];     
    $amount         = str_replace(",","" ,$_POST["amount"]);    
    $date_pay       = $_POST["date_pay"];       
    $refer_code     = $_POST["refer_code"];     
    //$note           = $_POST["note"];   
    $userId         = $USERID;
    $userName       = $_POST["username"];
    $BankID         = $_POST["bank_name"]; 
    $amount_kerry   = str_replace(",","" ,$_POST["amount_kerry"]);    
    $TOPICID        = $_POST["topicID"];
   
    if($bank_type  == '1'){
        $bankType       = "เครื่องฝากเงิน";
    }else  if($bank_type  == '2'){
        $bankType       = "หน้าสาาขา";
    }else  if($bank_type  == '3'){
        $bankType       = "7-11";
    }else  if($bank_type  == '4'){
        $bankType       = "Big-C";
    }else  if($bank_type  == '5'){
        $bankType       = "Lotus";
    }else{
        $bankType       = "อื่น(Agent)";
    }

    $ym_c           = "PY".date("ym");   
    $query_code     = " SELECT  PAY_CODENO from PAYMENT_AMOUNT  WHERE PAY_CODENO Like '".$ym_c."%'  ORDER BY  PAY_CODENO  DESC ";     
   $res_code        = $conn_1->query( $query_code ); 
   $code_data       = $res_code->fetch();
  
    if (empty($code_data["PAY_CODENO"])) {
        $run_num     = "001";
    } else { 
        $code_run       = substr($code_data["PAY_CODENO"],6,9);    
        $run_num        = @str_pad($code_run+1 ,3, "0", STR_PAD_LEFT); 
    } 
 
    $codeBill           = $ym_c.$run_num;  
      
    $date_cur       = date("Y-m-d H:i:s");
    $sel_in         = " INSERT INTO PAYMENT_AMOUNT (PAY_CODENO , PAY_BTCODE ,PAY_DATEBILL , PAY_GRANTOTAL , PAY_TYPETRANFER,PAY_AMOUNT , PAY_DATEPAYMENT,PAY_CODETRANFER ,PAY_AMOUNTFREE, PAY_DATERE,USERID, USERNAME, BANKID ,PAY_AMOUNT_KERRY , TOPICID) ";
    $sel_in         .= " VALUES('".$codeBill."','".$branch_code."','".$date_used."','".$amount."','". $bankType."','".$amount."','".$date_pay."','".$refer_code."','0','".$date_cur."','".$userId."','".$userName."','".$BankID."' ,'".$amount_kerry."' ,'".$TOPICID."')";
    $res_insert      = $conn_1->query($sel_in); 
    $data            =  $codeBill; 
    $aaction         = " เพิ่มยอดขาย"; 


    include("frm_memberLog.php");
 ?>
    <section class="col-lg-6 connectedSortable"> 
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">  DOC :   <?php echo $codeBill;?> </h3>
            </div>  
            <div class="card-body">  
                <div class="form-group">
                    <label>  เพิ่มข้อมูลเรียบร้อย  </label>  
                </div>  
                <div class="form-group">
                    <a href="javascript:location.reload(true)" class="btn btn-primary"> HOME</a></button>
                </div>
            </div> 
        </div>
    </section> 
 <?php }?>
<?php  //echo json_encode($data); ?>