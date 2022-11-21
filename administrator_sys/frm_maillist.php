<?php  
    ob_start();
    session_start();  
    error_reporting(E_ALL ^ E_NOTICE);
    if($_SESSION["USERID"] == ""){
		echo "<script>alert('Please Log In.');</script>";
		echo "<script>location.replace('../frm_login.php');</script>";
    }
  
    require("frm_heard.php"); 
    require("../inc/fun_connect.php"); 
    include("frm_member.php");
    require("../frm_left_top.php"); 

    $date_backday   =  date("Y-m-d" ,strtotime("-1days"));
    $date_cur       =  date("Y-m-d");

    $USERID         = $_SESSION["USERID"];
    $USERNAME       = $_SESSION["USERNAME"];
    $username       = $USERNAMES;
    
    $query_mail      = " SELECT  *  from  TB_MAILSEND ORDER BY MAIL_STATUS DESC";  
     

    
 ?>

<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?>
    
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-6 connectedSortable">   
                    <div class="card card-primary">
                        <div class="card-header"> 
                            <div class="row">
                                <div class="col-sm-6"><h3 class="card-title">   MAIL LIST </h3></div>
                                <div class="col-sm-6 text-right">   </div>
                            </div> 
                        </div> 
                        <div class="card-body"> 
                            <table class="table table-hover" >
                                <thead>
                                    <tr>
                                        <th> EMAIL</th>
                                        <th> STATUS</th>
                                        <th> </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($conn_1->query($query_mail) as $mail) {
                                            if($mail["MAIL_STATUS"] == "Y"){
                                                $dd     = "N";
                                            }else{  
                                                $dd     = "Y";
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo $mail["MAIL_NAME"];?></td> 
                                            <td><?php echo $mail["MAIL_STATUS"];?> </td>
                                            <td><a href="../processControl/frm_process_mail.php?MAILID=<?php echo $mail["MAILID"];?>&actionMode=Delete"> <i class="fas fa-trash-alt" style="color:#dc3545; font-size:20px;"></i>  </a></td>
                                            <td><a href="../processControl/frm_process_mail.php?MAILID=<?php echo $mail["MAILID"];?>&actionMode=Suspend&status=<?php echo $dd;?>"> <i class="fas fa-times" title="ระงับการส่ง"></i> ระงับการส่ง</a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>  
                </section>
                
                <section class="col-lg-6 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header"> 
                            <div class="row form-group">
                                <div class="col-sm-12"><h3 class="card-title">   เพิ่มเมล์   </h3></div> 
                            </div> 
                        </div> 
                        <form id="frm_img" name="frm_img" method="post" action="../processControl/frm_process_mail.php" enctype="multipart/form-data">
                            <div class="card-body"> 
                                <div class="row form-group">
                                    <div class="col-sm-3">  รายการเมล์ </div>
                                    <div class="col-sm-6 "> 
                                        <?php for($ii =0; $ii <= 5; $ii++){?> 
                                            <p><input type="email" name="mail[]" id="mail[]" class="form-control" placeholder="MAIL....."> </p> 
                                        <?php } ?>
                                    </div>
                                </div> 
                                <div class="row form-group"> 
                                    <div class="col-sm-12 "> <input type="submit" name="mode" id="mode" value="SAVE" class="btn btn-danger btn-block">  </div>
                                </div>
                            </div>
                        </form>
                    </div>  
                </section>
            </div>   
        </div>
    </section> 
</div> 
<?php require('frm_footer.php'); ?> 