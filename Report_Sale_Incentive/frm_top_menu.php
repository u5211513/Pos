<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h6 class="m-0">   สวัสดี  :  <?php echo $FULLNAME;?>  </h6>
            </div>
            <div class="col-sm-6">  
                <ol class="breadcrumb float-sm-right"> 
                    <li class="breadcrumb-item h6"><a href="index.php">หน้าแรก </a></li> 
                    <!-- <li class="breadcrumb-item h6"><a href="index.php"> เปลี่ยนรหัสผ่าน </a></li>  -->
                    <li class="breadcrumb-item h6"><a href="../processControl/frm_process_member.php?mode=logoff&USERID=<?php echo $_SESSION["USERID"];?>&USERNAME=<?php echo $_SESSION["USERNAME"];?>"> ออกจากระบบ </a> </li> 
                </ol>   
            </div>
        </div>
    </div>
</div>