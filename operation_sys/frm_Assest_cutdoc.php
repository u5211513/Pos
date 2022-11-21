<?php 
    ob_start();
    session_start();  
    error_reporting(E_ALL ^ E_NOTICE);
    include("../inc/fun_connect.php"); 
    include("../inc/fun_main.php");
    $USERID         = $_SESSION["USERID"];
    $USERNAME       = $_SESSION["USERNAME"];

    
    $doccc_d        = $_GET["docccc"];
    // $query_user       = " SELECT  *  from  TB_USER a  
    //                         inner join TB_COMPANY b on a.COMPANYID = b.COMPANYID 
    //                         inner join TB_DEPT c on a.DEPTID = c.DEPTID
    //                         WHERE   a.USERID = '".$userrr__d."'";  
    // $getUser          = $conn_1->query($query_user);
    // $user_data        = $getUser->fetch();
 

    $query_docc     = " SELECT * from TB_ASSESTDOC  WHERE ASSESTDOC_ID = '".$doccc_d."' ";
    $getDoc          = $conn_1->query($query_docc);
    $doc_data        = $getDoc->fetch();
    
    if($doc_data["CUT"] == "Y"){ 
        $doc    = " เอกสารสงคืนทรัพย์สิน / อุปกรณ์สานักงาน  ";
    }else{ 
        $doc    =  " ใบตัดจำหน่าย / โอนย้ายทรัพย์สิน";
    }


    $query_used     = " SELECT  * from TB_ASSESTUSED a inner join TB_ASSESTDOC b on a.ASSESTDOC_ID = b.ASSESTDOC_ID  
                        WHERE    a.ASSESTDOC_ID = '".$doccc_d."'  
                        ORDER BY ASSEST_USEDID DESC";   
    $getDoctype     = $conn_1->query($query_used);
    $doct_data      = $getDoctype->fetch(); 
    if($doct_data["STATUS"] == "3"){
        $typeName       = "ใบตัดจำหน่าย";
    }elseif($doct_data["STATUS"] == "4"){
        $typeName       = "โอนย้ายทรัพย์สิน";
    }
    
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit"> 
<style media="print">
	@media print {
		@page{
			size:  A4; 
			width: 21cm; 
			margin-left:0.50cm;
			margin-top:0.50cm;
			margin-right:0.50cm;
			padding-top:0cm;
			margin-bottom:0cm;
            font-family: 'Kanit';
			 
		} 
		.page-break  { 
			display: block; 
			page-break-before: always; 
			margin:0 auto;
			padding:0px;
		} 
        .tableall {
            border: 0px;
            border-width: 1px 0 0 1px !important;
        }
        .tableall td {
            border: solid #000 !important;
            border-width: 0 0 1px 0 !important;
        }
    }
	.noShow{
		display:none;
	}
	body{  
		font-family: 'Kanit';
 		margin:0 10px 0 10px;
		padding:0px; 
	}
	.pad{
		padding-left :10px;
		padding-right :10px;
	}
	.fontall{
        font-family: 'Kanit';
		font-size: 15px; 
	}

    .fonttop{
        font-family: 'Kanit';
		font-size: 20px;

	}

    .btn {
        display: inline-block;
        font-weight: 400;
        color: #212529;
        text-align: center;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
        box-shadow: none;
    } 
</style>   
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body  onLoad="window.print()"  style="padding:10px 10px 10px 10px; margin:10px auto;"  >
    <div style="text-align:center;">
        <a href="../operation_sys/index.php"><input type="submit" name="bt_check" id="btn btn-success btn-lg btn-block" value="Home"   class="noShow"   style="height:40px; width:100px; font-size:16px; background:#007bff; color:#FFFFFF; font-weight:bold; border:dotted 1px #000000; border-radius: 0.55rem; " /></a>
        <input type="submit" name="bt_check" id="btn btn-success btn-lg btn-block" value="Print" onClick="print();"  class="noShow"   style="height:40px; width:100px; font-size:16px; background:#007bff; color:#FFFFFF; font-weight:bold; border:dotted 1px #000000; border-radius: 0.55rem;" />
    </div>
    <table width="900px" align="center" style="line-height:30px;" class="fontall">
        <tr>
            <td> <img src="../dist/img/fashof.jpg" width="180"></td>
            <td  colspan="2" align="center">
                <?php   echo   $typeName;  ?> 
            </td>
            <td align="right" > <span class="fonttop"> Doc No. : <?php echo $doc_data["ASSESTDOC_NO"];?></span></td>
        </tr>
        <tr>
            <td colspan="4" style="padding-top:20px;"> </td>
        </tr>  
        <tr>
            <td> Branch (Only Operation)  : </td>
            <td><u><?php  echo $doc_data["FSCODE"];?></u></td>
            <td>วันที่แจ้ง  :  </td>
            <td><u><?php  echo  date("d/m/Y", strtotime($doc_data["DATE_OUT"]));?></u></td>
        </tr> 
        <tr>
            <td colspan="2">  สถานที่  :  <u><?php  echo $doc_data["LOCATION"];?></u></td> 
            <td>MOBILE  :  </td>
            <td><u><?php  echo $doc_data["MOBILE"];;?></u></td>
        </tr>
        <tr>
            <td  colspan="4"> หมายเหตุ  : <u><?php  echo $doc_data["NOTE"];?></u></td>  
        </tr>
        <tr>
            <td colspan="4"  style="padding-top:20px;"><u>   ทรัพย์สินที่ตัดจำหน่าย / โอนย้าย </u></td>
        </tr> 
        <tr>
            <td colspan="4" align="left">
                <table width="100%" class="fontall tableall"> 
                    <tr>
                        <td>  #</td> 
                        <td>Name </td> 
                        <td>Serial Number</td>
                        <td>Asset Number</td>
                        <td>Detail</td>
                    </tr>
                    <?php   
                    $no = 1;
                    foreach ($conn_1->query($query_used) as $assest) { 
                        $query_assest     = " SELECT * from TB_ASSEST  WHERE ASSESTID = '".$assest["ASSESTID"]."' ";
                        $getAssest          = $conn_1->query($query_assest);
                        $assest_data        = $getAssest->fetch();
                    ?>
                    <tr>
                        <td> <?php echo $no;?></td> 
                        <td> <?php echo $assest_data["ITEMNAME"];?></td> 
                        <td><?php echo $assest_data["SERIAL_NUMBER"];?></td>
                        <td><?php echo $assest_data["ASSEST_NO"];?></td>
                        <td><?php echo $assest_data["DETAIL"];?></td>
                    </tr>
                    <?php  $no++;} ?> 
                </table> 
            </td>
        </tr> 
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr> 
        <tr>
            <td colspan="2" align="left" style="padding-top:30px; font-size:13px;"> 
                <p>ส่วนนี้กรอกข้อมูลโดย หน่วยงานร้องขอ</p> 
                <p> ลงชื่อ  ______________________  หน่วยงานร้องขอ 
                    <br/> วันที่  ______ /_____ / _______      
                </p>  
                <p> ลงชื่อ  ______________________  ผู้อนุมัติ (Manager) 
                    <br/> วันที่  ______ /_____ / _______   
                </p>  
            </td>
            <td colspan="2" align="left" style="padding-top:30px; font-size:13px; padding-left:150px;" > 
                <p>ส่วนนี้กรอกข้อมูลโดย หน่วยงานผู้รับโอนทรัพย์สิน</p> 
                <p> ลงชื่อ  ______________________  หน่วยงานรับโอนทรัพย์สิน 
                    <br/>วันที่  ______ /_____ / _______   
                </p>  
                <p> ลงชื่อ  ______________________ ผู้อนุมัติรับโอน (Manager)
                    <br/> วันที่  ______ /_____ / _______
                </p> 
                    
            </td>
        </tr>
        <tr>
            <td colspan="4"  align="center" style="font-size:13px;">ส่วนนี้กรอกข้อมูลโดย หน่วยงานที่รับผิดชอบ (Admin Department)</td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="font-size:13px;" >  
                <p> ลงชื่อ  ______________________  ผู้ตรวจสอบ 
                    <br/>วันที่  ______ /_____ / _______   
                </p>  
            </td>
            <td colspan="2" align="center"  style="font-size:13px;">  
                <p> ลงชื่อ  ______________________     Administration  Manager 
                    <br/>วันที่  ______ /_____ / _______ 
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2"  align="center" style="font-size:13px;">ส่วนนี้กรอกข้อมูลโดย CEO</td>
            <td colspan="2"  align="center" style="font-size:13px;">ส่วนนี้กรอกข้อมูลโดย เจ้าหน้าที่บัญชี</td>
        </tr>
        <tr> 
            <td colspan="2" align="center" style="font-size:13px;" >  
                <p>  ( )อนุมัติ  ( ) ไม่อนุมัติ  หมายเหตุ _________________
                    <br/>   ลงชื่อ  ______________________    CEO
                    <br/>วันที่  ______ /_____ / _______ 
                </p>
            </td>
            <td colspan="2" align="center" style="font-size:13px;" >  
                <p> 
                    ลงชื่อ  ______________________    เจ้าหน้าที่บัญชี 
                    <br/>วันที่  ______ /_____ / _______ 
                </p>
            </td>
        </tr> 
        <tr>
            <td  colspan="4" align="right" style="font-size:12px;" >  ปริ้นโดย   :  <?php echo  $USERNAME;?>  เวลาปริ้น  :   <?php echo date("d/m/Y H:i:s");?> </td>
        </tr>
    </table> 
</body>