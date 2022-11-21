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
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"> 
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
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
                    <?php if($DEPARTCODE == 01){ ?>
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">
                                <i class="far fa-edit nav-icon"></i>
                                <p>  <span class="right">  ฟอร์มการแจ้งยอดเงิน </span> </p>
                            </a> 
                        </li> 
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tree"></i>
                                <p> Report 
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a> 
                            <ul class="nav nav-treeview">  
                                <li class="nav-item">
                                    <a href="frm_report_payment.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p> รายงานการส่งยอดขาย</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php }elseif($DEPARTCODE == 02){?>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tree"></i>
                                <p> Report 
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a> 
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="index.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p> รายงานยอดขายและเงินรับ</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="frm_report_payment.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>รายงานแจ้งยอดขาย</p>
                                    </a>
                                </li>
                            </ul>
                        </li> 
                    <?php }elseif($DEPARTCODE == 03){?>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tree"></i>
                                <p> Report 
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a> 
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="index.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p> 11111</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="frm_report_payment.php" class="nav-link">
                                        <i class="fas fa-copy nav-icon"></i>
                                        <p>2222</p>
                                    </a>
                                </li>
                            </ul>
                        </li> 
                    <?php } ?>  
                <?php } ?>
            </ul>
        </nav> 
    </div> 
</aside>