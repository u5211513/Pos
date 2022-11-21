<?php $part  = "../"; ?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
<link rel="stylesheet" href="<?php echo $part; ?>plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="<?php echo $part; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo $part; ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo $part; ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo $part; ?>dist/css/adminlte.min.css">
<?php
error_reporting(E_ALL ^ E_NOTICE);
// header("Content-type:text/html; charset=UTF-8");
// header("Content-Disposition: attachment; filename=B" . $_GET["branch_code"] . "_" . date("Ymd", strtotime($_GET["start"])) . "_" . date("Ymd", strtotime($_GET["stop"])) . "_" . date("Ymd") . ".xls");
// header("Content-Type: application/vnd.ms-excel;");
// header("Pragma: no-cache");
// header("Expires: 0");

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
<style>
    .tb_bg{
        
        border: 0px;
        padding: 0px;
        margin: 0px;

    }
</style>
<div class="table-responsive" > 
    <table id="table1" class="table table-bordered table-striped"><!-- <table class="table table-striped" border="1"> --> 
        <thead>
            <tr>
                <th colspan="30">
                    <div class="row">
                        <div class="col-sm-3">
                            รายงานรหัสสาขา : <?php echo $_GET["branch_code"]; ?>
                        </div>
                        <div class="col-sm-3">
                            วันที่ทำรายการ : <?php echo date("d/m/Y", strtotime($start)) . "  ถึงวันที่  : " . date("d/m/Y", strtotime($stop)); ?>
                        </div>
                        <div class="col-sm-3">
                            วันที่ปริ้น : <?php echo date("d/m/Y H:i:s"); ?>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <th>#</th>
                <th>FSCODE</th>
                <th>BILLDATE </th>
                <th>AMOUNT </th>
                <th> PAYMENT </th>
                <th> DIFF </th>
                <th>เงินสด</th> <!-- P01-->
                <th>บัตรเครดิต</th><!-- P02-->
                <th>คูปอง</th><!-- P03-->
                <th>เงินโอน</th><!-- P04-->
                <th>เช็ค</th><!--P05-->
                <th>Charge </th><!--P06-->
                <th>รับมัดจำ</th><!-- P07-->
                <th>ใช้มัดจำ</th><!-- P08-->
                <th>แต้ม </th><!--P09-->
                <th>เงินขาดเกิน </th><!--P10 -->
                <th>ค่าธรรมเนียมธนาคาร </th><!-- P11-->
                <th> QR Code</th><!--  P12-->
                <th>ดร.รักษา</th><!--  P13-->
                <th>ค่าขนส่ง </th><!-- P14-->
                <th>ยิ่งช๊อปยิ่งได้ </th><!--P15-->
                <th>Healt & Home</th><!-- P16-->
                <th>Good Doctor</th><!-- P17-->
                <th> Doctor A to Z </th><!--  P18-->
                <th>Pharmcare</th><!-- P19-->
                <th>Doctor เอนิแวร์ </th><!--P20-->
                <th>Food panda</th><!-- P21-->
                <th>SKIN X</th><!-- P22-->
                <th>Robinhood</th><!-- P23-->
                <th>Telepharmacy</th> <!-- P24-->
            </tr>
        </thead>
        <tbody>
            <?php
            $i_r   = 0;
            while ($row = $getRes->fetch(PDO::FETCH_ASSOC)) {
                $branch_q           = $row["FSCODE"];
                $billDate           = date("Y-m-d", strtotime($row["BILLDATE"]));
                $query_amount       = " SELECT  PAY_GRANTOTAL from PAYMENT_AMOUNT  WHERE   PAY_BTCODE = '" . $branch_q . "'  AND  PAY_DATEBILL =  '" . $billDate . "' ";
                $getResBill         = $conn_1->query($query_amount);
                $bill_data          = $getResBill->fetch();
                $bill_amount        = $bill_data["PAY_GRANTOTAL"];
                $bill_amountAll     += $bill_amount;
                //  Cal
                $P_01        =  $row['P01_CASH_AMOUNT'];
                $P_02        =  $row['P02_Credit'];
                $P_03        =  $row['P03_COUNPON'];
                $P_04        =  $row['P04_Transfer'];
                //  $P_05        =  "";  
                $P_06        =  $row['P06_Charge'];
                $P_07        =  $row['P07_BL_Deptamount'];
                $P_08        =  $row['P08_NO_BL_Deptmaount'];
                $P_09        =  $row['P09_DISCOUNT_1'];
                // $P_10        =  "";   
                // $P_11        = "";  
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
                $diff_amount    = $amount - $bill_amount;
                $diff_amountAll += $diff_amount;
                $sum_amount     += $amount;

                $sum_P01        += $P_01;
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
            ?>
                <tr>
                    <td> <?php echo  $i_r +  1 . "."; ?></td>
                    <td><?php echo $row['FSCODE']; ?></td>
                    <td><?php echo date("Y-m-d", strtotime($row['BILLDATE'])); ?> </td>
                    <td><?php echo number_format($amount, 2); ?></td>
                    <td><?php echo number_format($bill_amount, 2); ?></td>
                    <td style="color:#ff0000; font-size:18px;"><?php echo number_format($diff_amount, 2); ?></td>
                    <!--SUCCESS / ERROR  -->
                    <td><?php echo number_format($P_01, 2); ?> </td>
                    <td><?php echo number_format($P_02, 2); ?></td>
                    <td><?php echo number_format($P_03, 2); ?></td>
                    <td><?php echo number_format($P_04, 2); ?></td>
                    <td>0.00</td>
                    <td><?php echo round($P_06, 2); ?></td>
                    <td><?php echo number_format($P_07, 2); ?></td>
                    <td><?php echo number_format($P_08, 2); ?></td>
                    <td><?php echo number_format($P_09, 2); ?></td>
                    <td>0.00 P10</td>
                    <td>0.00 P11</td>
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
            <?php $i_r++;
            }  //}  
            ?>
        </tbody>
        <!-- <tfoot>
            <tr>
                <td colspan="3" class="text-center"><b> ยอดรวม </b></td>
                <td><?php echo number_format($sum_amount, 2) ?></td>
                <td><?php echo  number_format($bill_amountAll, 2); ?></td>
                <td style="color:#ff0000; font-size:18px;"><?php echo number_format($diff_amountAll, 2); ?></td>
                <td><?php echo number_format($sum_P01, 2) ?></td>
                <td><?php echo number_format($sum_P02, 2) ?></td>
                <td><?php echo number_format($sum_P03, 2) ?> </td>
                <td><?php echo number_format($sum_P04, 2) ?></td>
                <td>0.00</td>
                <td><?php echo number_format($sum_P06, 2) ?></td>
                <td><?php echo number_format($sum_P07, 2) ?> </td>
                <td><?php echo number_format($sum_P08, 2) ?></td>
                <td><?php echo number_format($sum_P09, 2) ?> </td>
                <td>0.00</td>
                <td>0.00 </td>
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
        </tfoot> -->
    </table>
</div>
<script src="<?php echo $part; ?>plugins/jquery/jquery.min.js"></script>
<script src="<?php echo $part; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $part; ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $part; ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo $part; ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo $part; ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo $part; ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo $part; ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo $part; ?>plugins/jszip/jszip.min.js"></script>
<script src="<?php echo $part; ?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo $part; ?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo $part; ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo $part; ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo $part; ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="<?php echo $part; ?>dist/js/adminlte.min.js"></script>
<script>
    // $(function() {
    //     $("#table1").DataTable({
    //         "responsive": true,
    //         "lengthChange": true,
    //         "autoWidth": true,
    //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    //     }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)');

    //     // $('#table2').DataTable({
    //     //     "paging": true,
    //     //     "lengthChange": false,
    //     //     "searching": false,
    //     //     "ordering": true,
    //     //     "info": true,
    //     //     "autoWidth": false,
    //     //     "responsive": true,
    //     // });
    // });

    $(function() { 
            $("#table1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "buttons": 
            [  
                {
                    extend: 'copy',   
                },
                {
                    extend: 'csv',     
                },
                {
                    extend: 'excel',   
                },
                {
                    extend: 'print',
                    
                    title: '<?php echo  $title_heard_file;?>',
                    text: 'Print ',   
                    messageTop: '<?php echo $text_bodyyy." " . $text_print;?> \r\n', 
                    messageBottom: null ,
                    pageSize: 'LEGAL',
                    customize: function(win)
                    {
                        var last = null;
                        var current = null;
                        var bod = [];
        
                        var css = '@page { size: landscape; font-size:11px; }',
                            head = win.document.head || win.document.getElementsByTagName('head')[0],
                            style = win.document.createElement('style');
        
                        style.type = 'text/css';
                        style.media = 'print';
        
                        if (style.styleSheet) {
                            style.styleSheet.cssText = css;
                        } else {
                            style.appendChild(win.document.createTextNode(css));
                        } 
                        head.appendChild(style);  
                        $(win.document.body)
                            .css( 
                                    {
                                        'font-size': '8pt', 
                                        'margin': '0',
                                        'left': '0',
                                        'padding': '0',
                                        'top': '0'
                                    }
                                ) 
                        $(win.document.body).find( 'table' ) 
                            .addClass( 'tb_bg' )
                            .css( 
                                    {
                                        'font-size':'8pt',
                                        'border': '0px',
                                        'border-top':'0px',  
                                    } 
                                );  
                        $(win.document.messageTop)
                            .css('font-size', '10pt') 
                    }  
                },
                 
            ]
        }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)');
    }); 

    // $(function() {
        //     $("#example1").DataTable({
        //         "responsive": true,
        //         "lengthChange": false,
        //         "autoWidth": false,
        //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        //     $('#example2').DataTable({
        //         "paging": true,
        //         "lengthChange": false,
        //         "searching": false,
        //         "ordering": true,
        //         "info": true,
        //         "autoWidth": false,
        //         "responsive": true,
        //     });
    // });
</script>