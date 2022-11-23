
<div class="preloader flex-column justify-content-center align-items-center"> </div> 
<nav class="main-header navbar navbar-expand navbar-white navbar-light"> 
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <h1 class="m-0" style="color:#F66545;">  F A S C I N O </h1> 
        </li> 
    </ul>  
</nav> 

<aside class="main-sidebar sidebar-dark-primary elevation-4"> 
    <span class="brand-text font-weight-light"></span>  
    <div class="sidebar"> 
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image"></div>
            <div class="info"></div>
        </div>  
        <nav class="mt-2 font-15">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"> 
                <li class="nav-item menu-open">
                    <a href="index.php" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>  MENU  </p>   <!--<i class="right fas fa-angle-left"></i>-->
                    </a> 
                </li> 
                <?php if($_SESSION["USERNAME"] == ""){?>
                    <li class="nav-item">
                        <a href="frm_login.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>  <span class="right">  เข้าระบบ </span> </p>
                        </a> 
                    </li> 
                <?php }else{   ?>  
                        <?php if($DEPARTCODE == 01){  //OP =  กรอก Payment?> 
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-tree"></i>
                                    <p> REPORT
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a> 
                                <ul class="nav nav-treeview">   
                                    <li class="nav-item">
                                        <a href="../operation_sys/frm_report_payment.php" class="nav-link">
                                            <i class="fas fa-copy nav-icon"></i>
                                            <p> รายงานการส่งยอดขาย</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="../Report_Sale_Incentive/" class="nav-link">
                                            <i class="fas fa-copy nav-icon"></i>
                                            <p> รายงาน Sale Incentive</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="../Report_PO_PA/" class="nav-link">
                                            <i class="fas fa-copy nav-icon"></i>
                                            <p> รายงานเปรียบเทียบการสั่ง PO PA</p>
                                        </a>
                                    </li>
                                </ul>
                            </li> 
                    <?php }elseif($DEPARTCODE == 02){  //  Account?>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tree"></i>
                                <p> Report 
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a> 
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../account_sys/index.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p> รายงานยอดขายและเงินรับ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../account_sys/frm_report_payment.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>รายงานแจ้งยอดขาย</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                        <a href="../Report_Sale_Incentive/" class="nav-link">
                                            <i class="fas fa-copy nav-icon"></i>
                                            <p> รายงาน Sale Incentive</p>
                                        </a>
                                    </li>
                                <!-- <li class="nav-item">
                                    <a href="frm_report_POSApp.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>POS รายงานยอดขาย  APP </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="frm_report_POSStock.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>POS รายงานสต๊อกคงเหลือ </p>
                                    </a>
                                </li> -->
                            </ul>
                        </li> 
                        <li class="nav-item">
                            <a href="../account_sys/frm_addpayment.php" class="nav-link">
                                <i class="far fa-edit nav-icon"></i>
                                <p>  <span class="right">  ฟอร์มการแจ้งยอดเงินสาขา </span> </p>
                            </a> 
                        </li> 
                        <li class="nav-item">
                            <a href="../account_sys/frm_add_banktostation.php" class="nav-link">
                            <i class="nav-icon fas fa-columns"></i>
                                <p>  <span class="right">  เพิ่มธนาคารสำหรับสาขา </span> </p>
                            </a> 
                        </li> 
                        <li class="nav-item">
                            <a href="../account_sys/frm_addTopicRemark.php" class="nav-link">
                                <i class="far fa-edit nav-icon"></i>
                                <p>  <span class="right">  เพิ่มหัวข้อหมายเหตุ </span> </p>
                            </a> 
                        </li> 
                    <?php }elseif($DEPARTCODE == 03){ //administrator?>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tree"></i>
                                <p> ข้อมูลพนักงาน
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a> 
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../administrator_sys/frm_empoyee.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p> List รายชื่อพนักงาน</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../administrator_sys/frm_create_empoyee.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>เพิ่มข้อมูลพนักงาน</p>
                                    </a>
                                </li>
                            </ul>
                        </li> 
                        <li class="nav-item">
                            <a href="#" class="nav-link">  
                                <i class="fas fa-users-cog"></i>
                                <p>  Setting Mail
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a> 
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="../administrator_sys/frm_maillist.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>  เพิ่มเมล์   </p>
                                    </a>
                                </li> 
                            </ul>
                        </li> 

                        <!-- <li class="nav-item">
                            <a href="../administrator_sys/frm_config_mail.php" class="nav-link">
                                <i class="far fa-edit nav-icon"></i>
                                <p>  <span class="right">  การตั้งค่าส่งเมล์ </span> </p>
                            </a> 
                        </li> -->
                        <li class="nav-item">
                            <a href="../operation_sys/frm_Assest_reportDoc.php" class="nav-link">
                                <i class="fas fa-copy nav-icon"></i>
                                <p>  <span class="right"> รายงานเลขที่เอกสาร </span> </p>
                            </a>
                        </li> 
                    <?php }elseif($DEPARTCODE == "DP01"){ // Admin IT?>
                        <li class="nav-item">
                            <a href="frm_Assest_list.php" class="nav-link">
                                <i class="nav-icon fas fa-tree"></i>
                                <p> ASSEST 
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a> 
                            <ul class="nav nav-treeview"> 
                                <li class="nav-item">
                                    <a href="frm_Asset_add.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p> เพิ่มรายการ ทรัพย์สิน </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="frm_Assest_list.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p> รายการทรัพย์สินทั้งหมด </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="../operation_sys/frm_Assest_reportDoc.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p> รายงานเลขที่เอกสาร </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="frm_ALLMaster.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p> COMPANY</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="frm_Department.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>DEPT</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="frm_ApplicationPosition.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>POSITION &  APPLICATION</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="frm_ALLMaster.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>CATEGORIES</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="frm_ALLMaster.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>STATUS</p>
                                    </a>
                                </li>
                            </ul> 
                        </li>
                        <li class="nav-item">
                            <a href="../operation_sys/frm_employee.php" class="nav-link">
                                <i class="far fa-edit nav-icon"></i>
                                <p>  <span class="right">  รายชื่อพนักงาน  </span> </p>
                            </a> 
                        </li>   
                    <?php }else{
                            if($DEPARTCODE == 05){
                        ?> 
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-tree"></i>
                                    <p> ASSEST 
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a> 
                                <ul class="nav nav-treeview">   
                                    <li class="nav-item">
                                        <a href="../operation_sys/frm_Assest_byDepartment.php" class="nav-link">
                                            <i class="fas fa-copy nav-icon"></i>
                                            <p> รายการทรัพย์สิน </p>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </li>
                    <?php } } ?>
                    <li class="nav-item">
                        <a href="../frm_password.php" class="nav-link">
                            <i class="far fa-compass nav-icon"></i>
                            <p>  <span class="right">  เปลี่ยนรหัสผ่าน </span> </p>
                        </a> 
                    </li>
                <?php } ?>
            </ul>
        </nav> 
    </div> 
</aside>