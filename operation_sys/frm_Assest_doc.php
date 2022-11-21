<?php 
    ob_start();
    session_start();  
    error_reporting(E_ALL ^ E_NOTICE);
    include("../inc/fun_connect.php"); 
    include("../inc/fun_main.php");
    $USERID         = $_SESSION["USERID"];
    $USERNAME       = $_SESSION["USERNAME"];

    
    $doccc_d        = $_GET["docccc"];
    $query_docc     = " SELECT * from TB_ASSESTDOC  WHERE ASSESTDOC_ID = '".$doccc_d."' ";
    $getDoc          = $conn_1->query($query_docc);
    $doc_data        = $getDoc->fetch();

    $userrr__d      = $doc_data["USERID"];
 
    $query_user       = " SELECT  *  from  TB_USER a  
                            inner join TB_COMPANY b on a.COMPANYID = b.COMPANYID 
                            inner join TB_DEPT c on a.DEPTID = c.DEPTID
                            WHERE   a.USERID = '".$userrr__d."'";  
    $getUser          = $conn_1->query($query_user);
    $user_data        = $getUser->fetch();
 

   
    
    if($doc_data["CUT"] == "Y"){ 
        $doc    = " เอกสารสงคืนทรัพย์สิน / อุปกรณ์สานักงาน  ";
    }else{ 
        $doc    =  " ใบตัดจำหน่าย / โอนย้ายทรัพย์สิน";
    }


    $query_used     = " SELECT  * from TB_ASSESTUSED a inner join TB_ASSESTDOC b on a.USERID = b.USERID   
                        WHERE  a.USERID = '". $userrr__d."'  and  a.ASSESTDOC_ID = '".$doccc_d."' and a.ASSESTDOC_ID = b.ASSESTDOC_ID 
                        ORDER BY ASSEST_USEDID DESC"; 
 
    
    $query_user_po      =  " SELECT  *  from  TB_USER a  inner join TB_POSITION b on a.POSITIONID = b.POSITIONID
                            inner join  TB_APP_POSITION c on a.POSITIONID = c.POSITIONID 
                            inner join TB_APPLICATION d on c.APP_ID = d.APP_ID  
                            where  a.USERID = '". $userrr__d."'  ";
   
   
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

    .fonttop1{
        font-family: 'Kanit';
		font-size: 18px;
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
                <?php    
                
                if($doc_data["DATE_IN"] != ""){
                    echo  " เอกสารส่งคืนทรัพย์สิน / อุปกรณ์สานักงาน  ";
                }else{
                    echo  " เอกสารรับมอบทรัพย์สิน / อุปกรณ์สานักงาน  ";
                } 
                ?> 
            </td>
            <td align="right" > <span class="fonttop"> Doc No. : <?php echo $doc_data["ASSESTDOC_NO"];?></span></td>
        </tr>
        <tr>
            <td colspan="4" style="padding-top:20px;"> </td>
        </tr>
       
            <tr>
                <td> รหัสพนักงาน  : </td>
                <td><u> <?php echo $user_data["IDNO"];?></u></td>
                <td>ชื่อ - นามสกุล : </td>
                <td><u> <?php echo $user_data["FULLNAME"];?></u></td>
            </tr>
            <tr>
                <td> บริษัทตามโครงสร้าง : </td>
                <td><u><?php echo $user_data["COMPANY_NAME"];?></u></td>
                <td> แผนก :  </td>
                <td><u> <?php echo $user_data["DEPT_NAME"];?></u></td>
            </tr>
            <tr>
                <td>ตําแหน่ง  :</td>
                <td><u><?php echo $user_data["POSITION"];?></u></td>
                <td>รหัสตําแหน่ง : </td>
                <td><u><?php echo "";?></u></td>
            </tr>
            <tr>
                <td> Branch (Only Operation)  : </td>
                <td><u><?php  echo "";?></u></td>
                <td>วันที่เริ่มงาน :  </td>
                <td><u><?php  echo  date("d/m/Y", strtotime($user_data["DATE_START"]));?></u></td>
            </tr>
            <tr>
                <td colspan="4"  style="padding-top:20px;"><u>   ทรัพย์สินที่เบิก </u></td>
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
                <td colspan="4" style="padding-top:20px;"> </td>
            </tr>
            <tr>
                <td> ผลการตรวจสอบ :  </td>
                <td> ___ สมบูรณ์ใช้งานได้ตามปกติ </td>
                <td>___ ชำรุดเสียหาย</td>
                <td>มูลค่าประเมิน  _______ บาท </td>
            </tr>
            <tr>
                <td colspan="4">หมายเหตุ  :  
                <p> _____________________________________________________________________________</p>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:50px;"> 
                    
                <p> ทั้งนี้ ข้าพเจ้าได้รับทราบถึงความเสียหาย  /ไม่เสียหายของทรัพย์สินที่ส่งมอบตามรายละเอียดข้างต้น
                    แล้วโดยเมื่อบริษัทประเมินมูลค่าความเสียหายที่เกิดขึ้นแก่ทรัพย์สินที่ส่งมอบแล้วเสร็จ ข้าพเจ้ายินยอมชดใช้คืนตามความเสยหายที่เกิดขึ้นจริงแก่บริษัทจนเสร็จสิ้น 
                </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center" > 
                    <br/>
                    <p> ลงชื่อ  ______________________     
                    <br/>วันที่  ______ /_____ / _______     
                    <br/> ผู้ส่งมอบ </p>
                </td>
                <td colspan="2" align="center" > 
                    <br/>
                    <p> ลงชื่อ  ______________________     
                    <br/> วันที่  ______ /_____ / _______    
                    <br/>  ผู้รับมอบ </p>
                </td>
            </tr>
            <tr> 
                <td colspan="2" align="center" >  
                    <p> ลงชื่อ  ______________________    
                    <br/> วันที่  ______ /_____ / _______    
                    <br/>ผู้ตรวจสอบ </p>
                </td>
                <td colspan="2" align="center" > 
                   
                   </td>
            </tr>
        
        <tr>
            <td  colspan="4" align="right" style="font-size:12px;" >  ปริ้นโดย   :  <?php echo  $USERNAME;?>  เวลาปริ้น  :   <?php echo date("d/m/Y H:i:s");?> </td>
        </tr>
    </table> 

    <?php   if($doc_data["DATE_IN"] == ""){?>
        <div class="page-break"></div>
        <br/> 
        <table width="900px" align="center" style="line-height:30px;" class="fontall">
            <tr>
                <td> <img src="../dist/img/fashof.jpg" width="180"></td>
                <td  colspan="2" align="center"> เอกสารสิทธิ์การเขา้ใชง้านของแผนก IT
                    
                </td>
                <td align="right" > <span class="fonttop"> Doc No. : <?php echo $doc_data["ASSESTDOC_NO"];?></span></td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:20px;"> </td>
            </tr>
        
            <tr>
                <td> รหัสพนักงาน  : </td>
                <td><u> <?php echo $user_data["IDNO"];?></u></td>
                <td>ชื่อ - นามสกุล : </td>
                <td><u> <?php echo $user_data["FULLNAME"];?></u></td>
            </tr>
            <tr>
                <td> บริษัทตามโครงสร้าง : </td>
                <td><u><?php echo $user_data["COMPANY_NAME"];?></u></td>
                <td> แผนก :  </td>
                <td><u> <?php echo $user_data["DEPT_NAME"];?></u></td>
            </tr>
            <tr>
                <td>ตําแหน่ง  :</td>
                <td><u><?php echo $user_data["POSITION"];?></u></td>
                <td>รหัสตําแหน่ง : </td>
                <td><u><?php echo "";?></u></td>
            </tr>
            <tr>
                <td> Branch (Only Operation)  : </td>
                <td><u><?php  echo "";?></u></td>
                <td>วันที่เริ่มงาน :  </td>
                <td><u><?php  echo  date("d/m/Y", strtotime($user_data["DATE_START"]));?></u></td>
            </tr> 
            <tr>
                <td colspan="4" align="left">
                    <hr/><p class="fonttop1">   โปรแกรม </p>
                    <?php foreach ($conn_1->query($query_user_po) as $app_position) {?>
                        
                        <span style="padding-left:20px;"> ____ <?php echo  $app_position["APP_NAME"]?>   </span>
                    <?php } ?>
                </td>
            </tr> 
            <tr>
                <td colspan="4" style="padding-top:20px;"> อื่นๆ   :  _________________________________________________________________________________ </td>
            </tr>
            <tr>
                <td colspan="4">    <br/>    </td>
            </tr>
            <tr>
                <td colspan="2">  เข้าเครื่องคอมพิวเตอร์  Username :  __________________________  </td> 
                <td colspan="2">  Password : __________________________</td>
                
            </tr> 
            <tr> 
                <td colspan="2"> สำหรับ Email  Username   _________________________________   </td>
                <td colspan="2"> Password : __________________________</td>
            </tr> 
            <tr> 
                <td colspan="2"> สำหรับเข้าใช้งาน AX  Username   _____________________________   </td>
                <td colspan="2"> Password : __________________________</td>
            </tr> 
            <tr>
                <td colspan="4">หมายเหตุ  :  
                <p> ____________________________________________________________________________________________________</p>
                <p> ____________________________________________________________________________________________________</p>
                </td>
            </tr> 
            <tr>
                <td colspan="2" align="center" > 
                    <br/>
                    <p> ลงชื่อ  ______________________     
                    <br/>วันที่  ______ /_____ / _______     
                    <br/> ผู้ส่งมอบ </p>
                </td>
                <td colspan="2" align="center" > 
                    <br/>
                    <p> ลงชื่อ  ______________________     
                    <br/> วันที่  ______ /_____ / _______    
                    <br/>  ผู้รับมอบ </p>
                </td>
            </tr> 
            <tr>
                <td colspan="2" align="center" > 
                    <br/>
                    <p> ลงชื่อ  ______________________     
                    <br/>วันที่  ______ /_____ / _______     
                    <br/> ผู้ติดตั้ง </p>
                </td>
                <td colspan="2" align="center" > 
                    
                </td>
            </tr> 
            
            <tr>
                <td  colspan="4" align="right" style="font-size:12px;" >  ปริ้นโดย   :  <?php echo  $USERNAME;?>  เวลาปริ้น  :   <?php echo date("d/m/Y H:i:s");?> </td>
            </tr>
        </table> 
    <?php } ?>
</body>