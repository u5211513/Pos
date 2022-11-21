<?php
@error_reporting(E_ALL ^ E_NOTICE);

include("../inc/fun_connect.php");
if ($_POST["mode"] == "CK_REPORT" || $_GET["mode"] == "CK_REPORT") {
    if ($_POST["branch"] != "") {
        $branch         = $_POST["branch"];
        $sql_br_ck      = "AND BT.FSCODE = '" . $branch . "'";
        $name_branch    = $branch;
    } else {
        $branch         = "";
        $sql_br_ck      = "";
        $name_branch    = "ALL STATION";
    }

    if (isset($_POST["start"]) != "") {
        $start      = $_POST["start"];
    } else {
        $start      =  $date_stop;
    }

    if (isset($_POST["stop"]) != "") {
        $stop      = $_POST["stop"];
    } else {
        $stop      =  $date_stop;
    }

    $fs_code_nno    = "'555555555555','666666666666', '777777777777', '888888888888', '444444444444','333333333333','999999FD','999999SX','999999RH','999999TP'";
    if ($start != ""  &&  $stop != "") {
        $sql = " SELECT     
                BT.FSCODE, 
                BT.BILLDATE, 
                SUM(BT.CASHAMT) AS P01_CASH_AMOUNT, 
                ISNULL(CN.TOTRECV, '0') + ISNULL(y.TOTPRC, '0') AS CN, 
                ISNULL(d.Amount_DP, '0') AS Yingchaiyingdai, 
                SUM(BT.VISAAMT) AS P02_Credit, 
                ISNULL(SR.TOTRECV, '0') AS CN_VISA, 
                SUM(BT.COUPONAMT) AS P03_COUNPON, 
                ISNULL(a.P04_Transfer_amount, '0') AS P04_Transfer, 
                SUM(BT.CHARGEAMT) AS P06_Charge, 
                ISNULL(ib.P07_DEPAMT, '0') AS P07_BL_Deptamount, 
                SUM(BT.DEPAMT) AS P08_NO_BL_Deptmaount, 
                SUM(BT.BL_DISCBILL4POINT) AS P09_DISCOUNT_1, 
                SUM(BT.QRCODE_AMT) AS P12_QR_PAYMENT, 
                ISNULL(DR.CASH_AMOUNT, '0')  AS P13_DR_AMOUNT, 
                SUM(BT.SHIPPING_FEE) AS P14_SHIPPINGFREE, 
                ISNULL(d.Amount_DP, '0') AS P15_Yingchaiyingdai, 
                SUM(BT.DISCAMT) AS DISCOUNT,
                ISNULL(HR.CASH_AMOUNT, '0')  AS P16_Health_AMOUNT,
                ISNULL(GF.CASH_AMOUNT, '0')  AS P17_GoodDoctor,
                ISNULL(AZ.CASH_AMOUNT, '0')  AS P18_Doctor_A_Z,
                ISNULL(PM.CASH_AMOUNT, '0')  AS P19_Pharmcare,
                ISNULL(DA.CASH_AMOUNT, '0')  AS P20_DoctorAnyware ,
                ISNULL(FD.CASH_AMOUNT, '0')  AS P21_FOODPANDA,
                ISNULL(SX.CASH_AMOUNT, '0')  AS P22_SKINX,
                ISNULL(RB.CASH_AMOUNT, '0')  AS P23_Robinhood,
                ISNULL(TP.CASH_AMOUNT, '0')  AS P24_Telephamacy
                
                FROM            dbo.BILLMAST AS BT 
                LEFT OUTER JOIN   dbo.View_CN AS CN ON CN.DOCMDATE = BT.BILLDATE AND CN.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.View_Interledger_SR AS SR ON SR.DOCMDATE = BT.BILLDATE AND SR.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.View_DRRAKAS AS DR ON DR.DOCMDATE = BT.BILLDATE AND DR.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.View_InterfaceHQ_Deptamt AS ib ON ib.BILLDATE = BT.BILLDATE AND ib.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.View_Interface_rd AS y ON y.DEPDATE = BT.BILLDATE AND y.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.View_interface_amounttransfer AS a ON a.BILLDATE = BT.BILLDATE AND a.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.View_Interface_yinchaiyingdia AS d ON d.FSCODE = BT.FSCODE AND d.BILLDATE = BT.BILLDATE
                LEFT OUTER JOIN  dbo.VIEW_HealtHome AS HR ON HR.DOCMDATE = BT.BILLDATE AND HR.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.VIEW_GoodDoctor AS GF ON GF.DOCMDATE = BT.BILLDATE AND GF.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.VIEW_Doctor_A_Z AS AZ ON AZ.DOCMDATE = BT.BILLDATE AND AZ.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.Pharmcare AS PM ON PM.DOCMDATE = BT.BILLDATE AND PM.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.DoctorAnyware AS DA ON DA.DOCMDATE = BT.BILLDATE AND DA.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.FOODPANDA AS FD ON FD.DOCMDATE = BT.BILLDATE AND FD.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.VIEW_SKINX AS SX ON SX.DOCMDATE = BT.BILLDATE AND SX.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.View_Robinhood AS RB ON RB.DOCMDATE = BT.BILLDATE AND RB.FSCODE = BT.FSCODE 
                LEFT OUTER JOIN  dbo.View_Telephamacy AS TP ON TP.DOCMDATE = BT.BILLDATE AND TP.FSCODE = BT.FSCODE 
                
                WHERE  
                (LEFT(BT.BILLNO, 2) IN ('T1'))
                $sql_br_ck
                AND (BT.CUSTCOD Not in ($fs_code_nno)) 
                and convert(date,bt.BILLDATE,103) between  '" . $start . "' and '" . $stop . "'  
                GROUP BY d.Amount_DP, BT.FSCODE, BT.BILLDATE, y.TOTPRC, CN.TOTRECV, SR.TOTRECV, a.P04_Transfer_amount, ib.P07_DEPAMT, DR.CASH_AMOUNT,HR.CASH_AMOUNT,
                GF.CASH_AMOUNT,AZ.CASH_AMOUNT,PM.CASH_AMOUNT,DA.CASH_AMOUNT,FD.CASH_AMOUNT,SX.CASH_AMOUNT,RB.CASH_AMOUNT,TP.CASH_AMOUNT 
                order by BT.FSCODE ASC , BT.BILLDATE ASC
            ";

        $getRes = $conn->prepare($sql);
        $getRes->execute();
    }

    $title_heard_file       = 'รายงานรหัสสาขา ' . $branch . ' ';
    $text_bodyyy            = 'วันที่ทำรายการ  : ' . date("d/m/Y", strtotime($start)) . "  ถึงวันที่  : " . date("d/m/Y", strtotime($stop));
    $text_print             = 'วันที่ปริ้น ' . date("d/m/Y H:i:s");
    $fileName               = 'รายงานรหัสสาขา ' . $branch . " " . $text_bodyyy;
    // pub
    $sum_p01    =  0;
    $sum_P02    =  0;
    $sum_P03    =  0;
    $sum_P04    =  0;
    $sum_P06    =  0;
    $sum_P07    =  0;
    $sum_P08    =  0;
    $sum_P09    =  0;
    $sum_P12    =  0;
    $sum_P13    =  0;
    $sum_P14    =  0;
    $sum_P15    =  0;
    $sum_P16    =  0;
    $sum_P17    =  0;
    $sum_P18    =  0;
    $sum_P19    =  0;
    $sum_P20    =  0;
    $sum_P21    =  0;
    $sum_P22    =  0;
    $sum_P23    =  0;
    $sum_P24    =  0;
    $bill_amount = 0;
    $diff_amount    = 0;
    $diff_amountAll = 0;
    $sum_amount     = 0;
    $bill_amountAll = 0;
    $bill_amountKerryAll = 0;
    $output ="";
    // end
?>
    <div style="border:1px;">
        <p class="alert alert-success h6"><?php echo  " ค้นหารายงานโดย  : " . $name_branch . "   วันที่เริ่ม  :  " . date("d/m/Y", strtotime($start)) . "  ถึงวันที่  : " . date("d/m/Y", strtotime($stop)); ?> </p>
        <table id="table1" class="table table-bordered table-striped table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>FSCODE</th>
                    <th>BILLDATE </th>
                    <th>DATEPAY </th>
                    <th style="width: 500px;">REMARK </th>
                    <th>AMOUNT </th>
                    <th>KERRY </th>
                    <th>PAYMENT </th>
                    <th>DIFF </th>
                    <th>เงินสด</th> <!-- P01-->
                    <th>บัตรเครดิต</th><!-- P02-->
                    <th>คูปอง</th><!-- P03-->
                    <th>เงินโอน</th><!-- P04-->
                    <!-- <th>เช็ค</th>P05-->
                    <th>Charge </th>
                    <!--P06-->
                    <th>รับมัดจำ</th><!-- P07-->
                    <th>ใช้มัดจำ</th><!-- P08-->
                    <th>แต้ม </th>
                    <!--P09-->
                    <!--<th>เงินขาดเกิน </th>P10 -->
                    <!--<th>ค่าธรรมเนียมธนาคาร </th>P11-->
                    <th>QR Code</th><!--  P12-->
                    <th>ดร.รักษา</th><!--  P13-->
                    <th>ค่าขนส่ง </th><!-- P14-->
                    <th>ยิ่งช๊อปยิ่งได้ </th>
                    <!--P15-->
                    <th>Healt & Home</th><!-- P16-->
                    <th>Good Doctor</th><!-- P17-->
                    <th>Doctor A to Z </th>
                    <!--P18-->
                    <th>Pharmcare</th><!-- P19-->
                    <th>Doctor เอนิแวร์ </th>
                    <!--P20-->
                    <th>Food panda</th><!-- P21-->
                    <th>SKIN X</th><!-- P22-->
                    <th>Robinhood</th><!-- P23-->
                    <th>Telepharmacy</th> <!-- P24-->
                </tr>
            </thead>
            <tbody>
                <?php
                    $i_r   = 0;
                    $rr = 1; 
                    while ($row = $getRes->fetch(PDO::FETCH_ASSOC)) { 
                    $branch_q           = $row["FSCODE"];
                    $billDate           = date("Y-m-d", strtotime($row["BILLDATE"]));

                    $query_amount       = " SELECT  SUM(PAY_GRANTOTAL) AS COSTTOTAL, SUM(PAY_AMOUNT_KERRY) AS COSTKerry   from PAYMENT_AMOUNT  WHERE   PAY_BTCODE = '" . $branch_q . "'  AND  PAY_DATEBILL =  '" . $billDate . "' ";
                    $getResBill         = $conn_1->query($query_amount);
                    $bill_data          = $getResBill->fetch();

                    $query_amountD       = " SELECT ID, PAY_CODENO, PAY_BTCODE  ,PAY_DATEPAYMENT ,PAY_NOTE, PAY_GRANTOTAL , PAY_AMOUNT_KERRY from PAYMENT_AMOUNT  WHERE   PAY_BTCODE = '" . $branch_q . "'  AND  PAY_DATEBILL =  '" . $billDate . "' ";
                    $getResBillD         = $conn_1->query($query_amountD);
                    $billD_data          = $getResBillD->fetch();

                   
                    if (empty($bill_data["COSTTOTAL"])) {
                        $bill_amount = null;
                        $bill_amountp = null;
                    } else {
                        $bill_amount = $bill_data["COSTTOTAL"];//$bill_data["PAY_GRANTOTAL"];
                        $bill_amountp = number_format($bill_data["COSTTOTAL"]);
                    }

                    if (empty($bill_data["COSTKerry"])) {
                        $bill_amountkerry = null;
                        $bill_amountkerryp = null;
                    } else {
                        $bill_amountkerry = $bill_data["COSTKerry"];//$bill_data["PAY_GRANTOTAL"];
                        $bill_amountkerryp = number_format($bill_data["COSTKerry"]);
                    }

                    

                    if (empty($billD_data["PAY_DATEPAYMENT"])) {
                        $bill_date = null;
                    } else {
                        $bill_date =  date("d/m/Y", strtotime($billD_data["PAY_DATEPAYMENT"]));
                    }

                     
 
                    $bill_amountAll     += $bill_amount;
                    $bill_amountKerryAll += $bill_amountkerry;
                    //  Cal
                        if ($row['CN'] != "") {
                            $P_CN = $row['CN'];
                        } else {
                            $P_CN = "0";
                        }

                        if ($row['P01_CASH_AMOUNT'] != "") {
                            $P_01 = $row['P01_CASH_AMOUNT']-$P_CN;
                        } else {
                            $P_01 = "0";
                        }
                        if ($row['P02_Credit'] != "") {
                            $P_02 = $row['P02_Credit'];
                        } else {
                            $P_02 = "0";
                        }
                        if ($row['P03_COUNPON'] != "") {
                            $P_03 = $row['P03_COUNPON'];
                        } else {
                            $P_03 = "0";
                        }
                        if ($row['P04_Transfer'] != "") {
                            $P_04 = $row['P04_Transfer'];
                        } else {
                            $P_04 = "0";
                        }
                        if ($row['P06_Charge'] != "") {
                            $P_06 = $row['P06_Charge'];
                        } else {
                            $P_06 = "0";
                        }
                        if ($row['P07_BL_Deptamount'] != "") {
                            $P_07 = $row['P07_BL_Deptamount'];
                        } else {
                            $P_07 = "0";
                        }
                        if ($row['P08_NO_BL_Deptmaount'] != "") {
                            $P_08 = $row['P08_NO_BL_Deptmaount'];
                        } else {
                            $P_08 = "0";
                        }
                        if ($row['P09_DISCOUNT_1'] != "") {
                            $P_09 = $row['P09_DISCOUNT_1'];
                        } else {
                            $P_09 = "0";
                        }
                        if ($row['P12_QR_PAYMENT'] != "") {
                            $P_12 = $row['P12_QR_PAYMENT'];
                        } else {
                            $P_12 = "0";
                        }
                        if ($row['P13_DR_AMOUNT'] != "") {
                            $P_13 = $row['P13_DR_AMOUNT'];
                        } else {
                            $P_13 = "0";
                        }
                        if ($row['P14_SHIPPINGFREE'] != "") {
                            $P_14 = $row['P14_SHIPPINGFREE'];
                        } else {
                            $P_14 = "0";
                        }
                        if ($row['P15_Yingchaiyingdai'] != "") {
                            $P_15 = $row['P15_Yingchaiyingdai'];
                        } else {
                            $P_15 = "0";
                        }
                        if ($row['P16_Health_AMOUNT'] != "") {
                            $P_16 = $row['P16_Health_AMOUNT'];
                        } else {
                            $P_16 = "0";
                        }
                        if ($row['P17_GoodDoctor'] != "") {
                            $P_17 = $row['P17_GoodDoctor'];
                        } else {
                            $P_17 = "0";
                        }
                        if ($row['P18_Doctor_A_Z'] != "") {
                            $P_18 = $row['P18_Doctor_A_Z'];
                        } else {
                            $P_18 = "0";
                        }
                        if ($row['P19_Pharmcare'] != "") {
                            $P_19 = $row['P19_Pharmcare'];
                        } else {
                            $P_19 = "0";
                        }
                        if ($row['P20_DoctorAnyware'] != "") {
                            $P_20 = $row['P20_DoctorAnyware'];
                        } else {
                            $P_20 = "0";
                        }
                        if ($row['P21_FOODPANDA'] != "") {
                            $P_21 = $row['P21_FOODPANDA'];
                        } else {
                            $P_21 = "0";
                        }
                        if ($row['P22_SKINX'] != "") {
                            $P_22 = $row['P22_SKINX'];
                        } else {
                            $P_22 = "0";
                        }
                        if ($row['P23_Robinhood'] != "") {
                            $P_23 = $row['P23_Robinhood'];
                        } else {
                            $P_23 = "0";
                        }
                        if ($row['P24_Telephamacy'] != "") {
                            $P_24 = $row['P24_Telephamacy'];
                        } else {
                            $P_24 = "0";
                        }

                        $amount         = $P_01 + $P_02 + $P_03 + $P_04 + $P_06 + $P_07 + $P_08 + $P_09 + $P_12 + $P_13 + $P_14 + $P_15 + $P_16 + $P_17 + $P_18 + $P_19 + $P_20 + $P_21 + $P_22 + $P_23 + $P_24;
                        $diff_amount    = $P_01 - $bill_amount;
                        $diff_amountAll += $diff_amount;
                        $sum_amount     += $amount;

                        $sum_p01        += $P_01;
                        $sum_P02        += $P_02;
                        $sum_P03        += $P_03;
                        $sum_P04        += $P_04;
                        $sum_P06        += $P_06;
                        $sum_P07        += $P_07;
                        $sum_P08        += $P_08;
                        $sum_P09        += $P_09;
                        $sum_P12        += $P_12;
                        $sum_P13        += $P_13;
                        $sum_P14        += $P_14;
                        $sum_P15        += $P_15;
                        $sum_P16        += $P_16;
                        $sum_P17        += $P_17;
                        $sum_P18        += $P_18;
                        $sum_P19        += $P_19;
                        $sum_P20        += $P_20;
                        $sum_P21        += $P_21;
                        $sum_P22        += $P_22;
                        $sum_P23        += $P_23;
                        $sum_P24        += $P_24;
                        
                

                        $query_remark       = " SELECT PAY_NOTE, PAY_CODENO, PAY_GRANTOTAL , PAY_DATEPAYMENT , PAY_DATEBILL , TOPICID ,PAY_AMOUNT_KERRY from PAYMENT_AMOUNT  WHERE  PAY_BTCODE = '" . $row["FSCODE"] . "'  AND  PAY_DATEBILL =  '" . $row["BILLDATE"] . "' ";
                        $getRemark  = $conn_1->prepare($query_remark);
                        $getRemark->execute();
                        $output ="";
                ?>
                    <tr>
                        <td> <?php echo  $i_r +  1 . "."; ?></td>
                        <td><?php echo $row['FSCODE']; ?></td>
                        <td><?php echo date("d/m/Y", strtotime($row['BILLDATE'])); ?> </td>
                        <td><?php echo $bill_date; ?> </td>
                        <td style="min-width: 500px;"> 
                            <?php 
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
                    
                                $output     .= "<p> เลขที่: ".$remark["PAY_CODENO"] ." ยอดเงิน  :  ".number_format($remark["PAY_GRANTOTAL"],2)." ยอดเงิน  Kerry  :  ".$bill_amountkerry . " วันทีฝาก " .date("d/m/Y", strtotime($remark["PAY_DATEPAYMENT"])) ." วันที่ขาย :" .  date("d/m/Y", strtotime($remark["PAY_DATEBILL"]))." <br/>";
                                $output     .= $topic_note;   
                                $output     .= "<br/>";
                            }
                            if(isset($output)){
                                echo  $output;
                            }else{ echo "-";}
                           
                            ?>  
                        </td>
                        <td><b><?php echo number_format($amount, 2); ?></b></td>
                        <td><b><?php echo  $bill_amountkerryp; ?></b></td>
                        <td><b><?php echo  $bill_amountp; ?></b></td>
                        <td style="color:#ff0000; font-size:16px;"><?php echo number_format($diff_amount, 2); ?></td>
                        <!--SUCCESS / ERROR   view</a>    data-toggle="modal" data-target="#modal-xl" -->
                        <td><?php echo number_format($P_01, 2); ?> </td>
                        <td><?php echo number_format($P_02, 2); ?></td>
                        <td><?php echo number_format($P_03, 2); ?></td>
                        <td><?php echo number_format($P_04, 2); ?></td>
                        <!-- <td>0.00</td> -->
                        <td><?php echo number_format($P_06, 2); ?></td>
                        <td><?php echo number_format($P_07, 2); ?></td>
                        <td><?php echo number_format($P_08, 2); ?></td>
                        <td><?php echo number_format($P_09, 2); ?></td>
                        <!-- <td>0.00  </td>
                        <td>0.00  </td> -->
                        <td><?php echo number_format($P_12, 2); ?></td>
                        <td><?php echo number_format($P_13, 2); ?></td>
                        <td><?php echo number_format($P_14, 2); ?></td>
                        <td><?php echo number_format($P_15, 2); ?></td>
                        <td><?php echo number_format($P_16, 2); ?></td>
                        <td><?php echo number_format($P_17, 2); ?></td>
                        <td><?php echo number_format($P_18, 2); ?></td>
                        <td><?php echo number_format($P_19, 2); ?></td>
                        <td><?php echo number_format($P_20, 2); ?></td>
                        <td><?php echo number_format($P_21, 2); ?></td>
                        <td><?php echo number_format($P_22, 2); ?></td>
                        <td><?php echo number_format($P_23, 2); ?></td>
                        <td><?php echo number_format($P_24, 2); ?></td>
                    </tr> 
                <?php  
                    $i_r++; 
                } 
                echo "<tr>";
                echo "<td>Total</td>"; 
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td>";
                echo "<td></td> ";                 
                echo "<td>".number_format($sum_amount, 2)."</td>"; 
                echo "<td>".number_format($bill_amountKerryAll,2)."</td>";
                echo "<td>".number_format($bill_amountAll,2)."</td>";
                echo " <td>".number_format($diff_amountAll, 2)."</td>
                    <td>".number_format($sum_p01,2)."</td>
                    <td>".number_format($sum_P02, 2)."</td>                
                    <td>".number_format($sum_P03, 2)."</td>
                    <td>".number_format($sum_P04, 2)."</td> 
                    <td>".number_format($sum_P06, 2)."</td>
                    <td>".number_format($sum_P07, 2)."</td>
                    <td>".number_format($sum_P08, 2)."</td>
                    <td>".number_format($sum_P09, 2)."</td>
                    <td>".number_format($sum_P12, 2)."</td>
                    <td>".number_format($sum_P13, 2)."</td>
                    <td>".number_format($sum_P14, 2)."</td>
                    <td>".number_format($sum_P15, 2)."</td>
                    <td>".number_format($sum_P16, 2)."</td>
                    <td>".number_format($sum_P17, 2)."</td>
                    <td>".number_format($sum_P18, 2)."</td>
                    <td>".number_format($sum_P19, 2)."</td>
                    <td>".number_format($sum_P20, 2)."</td>
                    <td>".number_format($sum_P21, 2)."</td>
                    <td>".number_format($sum_P22, 2)."</td>
                    <td>".number_format($sum_P23, 2)."</td>
                    <td>".number_format($sum_P24, 2)."</td>
                </tr>"; 
                ?>  
            </tbody> 
            
        </table> 
        <div class="modal fade" id="modal-xl"> 
            <div class="modal-dialog modal-xl" > 
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> <?php echo $branch_q ;?> </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalDetail">
                        
                    </div>
                    <div class="modal-footer justify-content-between"> 
                        <button type="button" class="btn btn-primary" data-dismiss="modal">  Close </button>
                    </div>
                </div> 
            </div> 
        </div> 
    </div>
<?php } ?>
<script>
    $(function() {
        $("#table1").DataTable({
            "lengthMenu": [
                [10, 20, 50, 100, 200, -1],
                [10, 20, 50, 100, 200, "All"]
            ],
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "ordering": true,
            "info": false,
            "searching": true,  
            "footer": false, 
            "buttons": [{
                    extend: 'excelHtml5',
                    text: ' <i class="fas fa-download"></i>   Export Excel  ',
                    title: '<?php echo  $title_heard_file; ?>',
                    autoFilter: false,
                    messageTop: '<?php echo $text_bodyyy . " " . $text_print; ?>  ',
                    filename: '<?php echo $fileName; ?>',   
                    
                },
             
                // {
                //     extend: 'print', 
                //     title: '<?php echo  $title_heard_file; ?>',
                //     text: 'Print ',   
                //     messageTop: '<?php echo $text_bodyyy . " " . $text_print; ?> \r\n', 
                //     messageBottom: null ,
                //     pageSize: 'LEGAL',
                //     customize: function(win)
                //     {
                //         var last = null;
                //         var current = null;
                //         var bod = [];

                //         var css = '@page { size: landscape; font-size:11px; }',
                //             head = win.document.head || win.document.getElementsByTagName('head')[0],
                //             style = win.document.createElement('style');

                //         style.type = 'text/css';
                //         style.media = 'print';

                //         if (style.styleSheet) {
                //             style.styleSheet.cssText = css;
                //         } else {
                //             style.appendChild(win.document.createTextNode(css));
                //         } 
                //         head.appendChild(style);  
                //         $(win.document.body)
                //             .css( 
                //                     {
                //                         'font-size': '8pt', 
                //                         'margin': '0',
                //                         'left': '0',
                //                         'padding': '0'
                //                     }
                //                 ) 
                //         $(win.document.body).find( 'table' )
                //             .addClass( 'white-bg' )
                //             .css( 
                //                     {
                //                         'font-size':'8pt',
                //                         'border': '0px'

                //                     } 
                //                 );  
                //         $(win.document.messageTop)
                //             .css('font-size', '10pt') 
                //     }      
                // } 
                ], 
            
        }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)');
    });

    // $(document).on('click', '.view_data', function(){  
    //     let id = $(this).prop("id");   
    //     if(id != '')  
    //     {  
    //         $.ajax({  
    //                 url:"../processControl/frm_viewData.php",  
    //                 method:"POST",  
    //                 data:{id:id},  
    //                 success:function(data){   
    //                     $('#modalDetail').html(data);  
    //                     $('#modal-xl').modal('show');  
    //                 }  
    //         });  
    //     }            
    //   });  

</script>