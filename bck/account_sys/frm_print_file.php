<?php
error_reporting(E_ALL ^ E_NOTICE);
include("../inc/fun_connect.php");
if ($_GET["branch_code"]  != "") {
    $branch         = $_GET["branch_code"];
    $sql_br_ck      = "AND BT.FSCODE = '" . $branch . "'";
    $name_branch    = $branch;
} else {
    $branch         = "";
    $sql_br_ck      = "";
    $name_branch    = "ALL STATION";
}

if (isset($_GET["start"]) != "") {
    $start      = $_GET["start"];
} else {
    $start      =  $date_stop;
}

if (isset($_GET["stop"]) != "") {
    $stop      = $_GET["stop"];
} else {
    $stop      =  $date_stop;
}

if ($start != ""  && $stop != "") {
    $fs_code_nno    = "'555555555555','666666666666', '777777777777', '888888888888', '444444444444','333333333333','999999FD','999999SX','999999RH','999999TP'";

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
        GF.CASH_AMOUNT,AZ.CASH_AMOUNT,PM.CASH_AMOUNT,DA.CASH_AMOUNT,FD.CASH_AMOUNT,SX.CASH_AMOUNT,RB.CASH_AMOUNT,TP.CASH_AMOUNT order by BT.FSCODE ASC
    ";

    $getRes = $conn->prepare($sql);
    $getRes->execute();
}
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit"> 
<link rel="stylesheet" href="../dist/css/adminlte.min.css">
<style type="text/css" media="print">
	@media print {
		@page {
            size: landscape;
			margin-left: 0.10cm;
			margin-top: 0.10cm;
			margin-right: 0.10cm;
			padding-top: 0.50cm;
			margin-bottom: 0.01cm;
            font: 8px;
		}

		.page-break {
			display: block;
			page-break-before: always;
			margin: 0 auto;
			padding: 0px;

		}
        body{ 
            font-family: 'Kanit';
            font: 8px;
           
        }
	}
   
</style>
</head>
<body onLoad="window.print()">
    <div class="table-responsive">
        <div class="row" style="font-size:11px;">
            <div class="col-sm-3">
                รายงานรหัสสาขา : <?php echo $name_branch; ?>
            </div>
            <div class="col-sm-3">
                วันที่ทำรายการ : <?php echo date("d/m/Y", strtotime($start)) . "  ถึงวันที่  : " . date("d/m/Y", strtotime($stop)) ?>
            </div>
            <div class="col-sm-3">
                วันที่ปริ้น : <?php echo date("d/m/Y  H:i:s") ?>
            </div>
        </div>
        <br />
        <table border="1" style="font-size:9px; padding: 0 px; margin-bottom: 0 px;">
            <thead>
                <tr>
                    <th style="width: 10px;">#</th>
                    <th> FSCODE</th>
                    <th> AMOUNT </th>
                    <th style="width: 30px;"> BILLDATE </th>
                    <th>เงินสด</th>
                    <th>บัตรเครดิต</th>
                    <th>คูปอง </th>
                    <th>เช็ค </th>
                    <th>Charge</th>
                    <th>รับมัดจำ</th>
                    <th>ใช้มัดจำ</th>
                    <th>แต้ม </th>
                    <!-- <th>เงินขาดเกิน  </th> 
                    <th>ค่าธรรมเนียมธนาคาร </th> -->
                    <th> QR Code</th>
                    <th>ดร.รักษา</th>
                    <th>ค่าขนส่ง </th>
                    <th>ยิ่งช๊อปยิ่งได้</th>
                    <th>Healt & Home</th>
                    <th>Good Doctor</th>
                    <th> Doctor A to Z </th>
                    <th>Pharmcare</th>
                    <th>Doctor เอนิแวร์</th>
                    <th>Food panda </th>
                    <th>SKIN X</th>
                    <th>Robinhood</th>
                    <th>Telepharmacy</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i_r   = '0';
                while ($row = $getRes->fetch(PDO::FETCH_ASSOC)) {
               
                    $P_01        =  $row['P01_CASH_AMOUNT'];
                    $P_02        =  $row['P02_Credit'];
                    $P_03        =  $row['P03_COUNPON'];
                    $P_04        =  $row['P04_Transfer'];
                    //  $P_05        =  $row['P01_CASH_AMOUNT'];   
                    $P_06        =  $row['P06_Charge'];
                    $P_07        =  $row['P07_BL_Deptamount'];
                    $P_08        =  $row['P08_NO_BL_Deptmaount'];
                    $P_09        =  $row['P09_DISCOUNT_1'];
                    // $P_10        =  $row['P01_CASH_AMOUNT'];   
                    // $P_11        =  $row['P01_CASH_AMOUNT'];   
                    $P_12        =  $row['P12_QR_PAYMENT'];
                    $P_13        =  $row['P13_DR_AMOUNT'];
                    $P_14        =  $row['P14_SHIPPINGFREE'];
                    $P_15        =  $row['P15_Yingchaiyingdai'];
                    $P_16        =  $row['P16_Health_AMOUNT'];
                    $P_17        =  $row['P17_GoodDoctor'];
                    $P_18        =  $row['P18_Doctor_A_Z'];
                    $P_19        =  $row['P19_Pharmcare'];
                    $P_20        =  $row['P20_DoctorAnyware'];
                    $P_21        =  $row['P21_FOODPANDA'];
                    $P_22        =  $row['P22_SKINX'];
                    $P_23        =  $row['P23_Robinhood'];
                    $P_24        =  $row['P24_Telephamacy'];

                    $amount         = $P_01 + $P_02 + $P_03 + $P_04 + $P_06 + $P_07 + $P_08 + $P_09 + $P_12 + $P_13 + $P_14 + $P_15 + $P_16 + $P_17 + $P_18 + $P_19 + $P_20 + $P_21 + $P_22 + $P_23 + $P_24;
                    $diff_amount    = "";

                    $sum_P01        += $P_01;
                    $sum_P02        +=  $P_02;
                    $sum_P03        += isset($P_03);
                    $sum_P04        += isset($P_04);
                    $sum_P06        += isset($P_06);
                    $sum_P07        += isset($P_07);
                    $sum_P08        += isset($P_08);
                    $sum_P09        += isset($P_09);
                    $sum_P12        += isset($P_12);
                    $sum_P13        += isset($P_13);
                    $sum_P14        += isset($P_14);
                    $sum_P15        += isset($P_15);
                    $sum_P16        += isset($P_16);
                    $sum_P17        += isset($P_17);
                    $sum_P18        += isset($P_18);
                    $sum_P19        += isset($P_19);
                    $sum_P20        += isset($P_20);
                    $sum_P21        += isset($P_21);
                    $sum_P22        += isset($P_22);
                    $sum_P23        += isset($P_23);
                    $sum_P24        += isset($P_24); 
                ?>
                    <tr>
                        <td> <?php echo  $i_r +1 . "."; ?></td>
                        <td><?php echo $row['FSCODE']; ?></td>
                        <td><?php echo number_format($amount, 2); ?></td> <!-- [(01-09)+(12-13)+(15-24)]-[(14)] -->
                        <td><?php echo date("Y-m-d", strtotime($row['BILLDATE'])); ?> </td> 
                        <td><?php echo number_format($P_01, 2); ?> </td>
                        <td><?php echo number_format($P_02, 2); ?></td>
                        <td><?php echo number_format($P_03, 2); ?></td>
                        <td><?php echo number_format($P_04, 2); ?></td>
                        <td><?php echo number_format($P_06, 2); ?></td>
                        <td><?php echo number_format($P_07, 2); ?></td>
                        <td><?php echo number_format($P_08, 2); ?></td>
                        <td><?php echo number_format($P_09, 2); ?></td> 
                        <!-- <td></td>
                        <td></td> -->
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
                <?php $i_r++; } ?>
            </tbody>
            <tfoot>
                <tr> 
                    <td colspan="4" class="text-right"> TOTAL </td>
                    <td><?php echo  $sum_P01;?></td>
                    <td><?php echo number_format($sum_P02, 2) ?></td>
                    <td><?php echo number_format($sum_P03, 2) ?> </td>
                    <td><?php echo number_format($sum_P04, 2) ?></td>
                    <td><?php echo number_format($sum_P06, 2) ?></td>
                    <td><?php echo number_format($sum_P07, 2) ?> </td>
                    <td><?php echo number_format($sum_P08, 2) ?></td>
                    <td><?php echo number_format($sum_P09, 2) ?> </td>
                    <!-- <td></td> 
                    <td></td>  -->
                    <td><?php echo number_format($sum_P12, 2) ?></td>
                    <td><?php echo number_format($sum_P13, 2) ?></td>
                    <td><?php echo number_format($sum_P14, 2) ?></td>
                    <td><?php echo number_format($sum_P15, 2) ?></td>
                    <td><?php echo number_format($sum_P16, 2) ?></td>
                    <td><?php echo number_format($sum_P17, 2) ?></td>
                    <td><?php echo number_format($sum_P18, 2) ?></td>
                    <td><?php echo number_format($sum_P19, 2) ?></td>
                    <td><?php echo number_format($sum_P20, 2) ?></td>
                    <td><?php echo number_format($sum_P21, 2) ?></td>
                    <td><?php echo number_format($sum_P22, 2) ?></td>
                    <td><?php echo number_format($sum_P23, 2) ?></td>
                    <td><?php echo number_format($sum_P24, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
<script>
    window.addEventListener("load", window.print());
</script>