 <?php
    include("../inc/fun_connect.php");
    $output = "";
    if (isset($_POST["id"])) {

        $query_oper = " SELECT * from PAYMENT_AMOUNT  WHERE  ID = '" . $_POST["id"] . "'";
        $getRes     = $conn_1->query($query_oper);
        $pay_data   = $getRes->fetch();


        $query_remark       = " SELECT PAY_NOTE, PAY_CODENO, PAY_GRANTOTAL , PAY_DATEPAYMENT , PAY_DATEBILL , TOPICID ,PAY_AMOUNT_KERRY from PAYMENT_AMOUNT  WHERE  PAY_BTCODE = '" . $pay_data["PAY_BTCODE"] . "'  AND  PAY_DATEBILL =  '" . $pay_data["PAY_DATEBILL"] . "' ";
        $getRemark  = $conn_1->prepare($query_remark);
        $getRemark->execute();
         
        while ($remark = $getRemark->fetch(PDO::FETCH_ASSOC)) {   
            if(empty($remark["TOPICID"]) != ""){
                $topic_note = null; 
            }else{
                $query_topic = " SELECT * from TB_TOPICREMARK  WHERE  TOPICID = '" . $remark["TOPICID"] . "'";
                $gettopic     = $conn_1->query($query_topic);
                $topic_data   = $gettopic->fetch();
                $topic_note   = "หัวข้อหมายเหตุ   : ".$topic_data["TOPICREMARK"]." <br/>";

            }
             

            if (empty($remark['PAY_AMOUNT_KERRY'])) {
                $bill_amountkerry = null; 
            } else { 
                $bill_amountkerry = number_format($remark['PAY_AMOUNT_KERRY'],2);
            }

            $output     .= "<p> เลขที่  : ". $remark["PAY_CODENO"] ." ยอดเงิน  :  ".number_format($remark["PAY_GRANTOTAL"],2)." ยอดเงิน  Kerry  :  ".$bill_amountkerry . " วันทีฝาก " .date("d/m/Y", strtotime($remark["PAY_DATEPAYMENT"])) ." วันที่ขาย :" .  date("d/m/Y", strtotime($remark["PAY_DATEBILL"]))." <br/>";
            $output     .= $topic_note;   
            $output     .= "<br/>";
        } 
 
        echo $output  ;
    }
    ?>