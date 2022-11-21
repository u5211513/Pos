<?php
// header('Content-Type: application/json');  
include("../inc/fun_connect.php"); 
$ipaddress 		    = $_SERVER["REMOTE_ADDR"]; 
if ($_POST["mode"] == "INFRM") {

    $branch_code    = $_POST["branch_code"];     
    $date_used      = $_POST["date_used"];     
    $bank_name      = $_POST["bank_name"];     
    $bank_type      = $_POST["bank_type"];     
    $amount         = $_POST["amount"];         
    $bank_type2     = $_POST["bank_type2"];     
    $date_pay       = $_POST["date_pay"];       
    $refer_code     = $_POST["refer_code"];     
    $note           = $_POST["note"];         
    $amount2_ck     = $_POST["amount2"];         
    $amont_fee_ck   = $_POST["amont_fee"];   
    $userId         = $_POST["userid"];
    $userName       = $_POST["username"];

    if($amount2_ck != ""){
        $amount2    =  $amount2_ck;
    }else{
        $amount2    = '0';
    }  

    if($amont_fee_ck != ""){
        $amont_fee    =  $amont_fee_ck;
    }else{
        $amont_fee    = '0';
    } 
    $amount_grantotal    =   number_format(($amount+$amount2)-$amont_fee, 2, '.', '');
   
    if($bank_name == '1'){
        $bankName       = "ธ.ไทยพาณิชย์ เลขที่ 122-2-13598-8";
    }else{
        $bankName       = "ธ.กสิกรไทย เลขที่ 056-8-41214-1";
    }

    if($bank_type  == '1'){
        $bankType       = "เครื่องฝากเงิน";
    }else{
        $bankType       = "โอนเงิน";
    }

    if($bank_type2  == '1'){
        $bankType2       = "เครื่องฝากเงิน";
    }else{
        $bankType2       = "โอนเงิน";
    }

    $ym_c           = "PY".date("ym");   
    $query_code     = " SELECT TOP 1  PAY_CODENO from PAYMENT_AMOUNT  WHERE PAY_CODENO Like '".$ym_c."%'  ORDER BY  PAY_CODENO  DESC ";     
    $res_code       = $conn_1->query( $query_code );   
    while ( $row = $res_code->fetch( PDO::FETCH_ASSOC ) ){ 
        $code_run       = substr($row["PAY_CODENO"],6,9);    
        $run_num        = @str_pad($code_run+1 ,3, "0", STR_PAD_LEFT); 
    }
    $codeBill           = $ym_c.$run_num;  
    $query_codebill     = " SELECT  PAY_CODENO from PAYMENT_AMOUNT  WHERE PAY_CODENO = '".$codeBill."' "; 
    $res_codeck         = $conn_1->query( $query_codebill );   
    while ( $row_ck = $res_codeck->fetch( PDO::FETCH_ASSOC ) ){ 
        $code_run_ck       =  $row_ck["PAY_CODENO"];
        if($code_run_ck != "" ){
            $code_run       = substr($row_ck["PAY_CODENO"],6,9);   
            $run_num        = @str_pad($code_run+1 ,3, "0", STR_PAD_LEFT); 
        }
    }
    $codeBill           = $ym_c.$run_num;  
    
    $date_cur       = date("Y-m-d H:i:s");
    $sel_in         = " INSERT INTO PAYMENT_AMOUNT (PAY_CODENO , PAY_BTCODE ,PAY_DATEBILL ,PAY_BANKNAME, PAY_GRANTOTAL , PAY_TYPETRANFER,PAY_AMOUNT ,PAY_TYPETRANFER_1,PAY_AMOUNT_1, PAY_DATEPAYMENT,PAY_CODETRANFER ,PAY_AMOUNTFREE, PAY_NOTE, PAY_DATERE,USERID, USERNAME) ";
    $sel_in         .= " VALUES('".$codeBill."' ,'".$branch_code."' , '".$date_used."' ,'".$bankName."'  , '".$amount_grantotal."' ,'". $bankType."', '".$amount."','".$bank_type2."','".$amount2."','".$date_pay."','".$refer_code."','".$amont_fee."','".$note."','".$date_cur."','".$userId."','".$userName."')";
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