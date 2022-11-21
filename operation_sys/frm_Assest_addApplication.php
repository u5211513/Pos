<?php  
    ob_start();
    session_start();  
    @error_reporting(E_ALL ^ E_NOTICE);
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
    
    
    $query_application  = " SELECT  *  from  TB_APPLICATION";  
     
    $query_app_position  = " SELECT  *  from  TB_APP_POSITION  a  inner join TB_POSITION b on a.POSITIONID = b.POSITIONID inner join TB_APPLICATION c on a.APP_ID = c.APP_ID   WHERE a.POSITIONID = '".$_GET["position"]."' ";  
   
 ?>

<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?>
    
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-6 connectedSortable">  
                    <form id="frm_img" name="frm_img" method="post" action="../processControl/frm_process_application.php" enctype="multipart/form-data">
                        <div class="card card-primary">
                            <div class="card-header"> 
                                <div class="row">
                                    <div class="col-sm-6"><h3 class="card-title">  เพิ่มโปรแกรมสำหรับตำแหน่ง :   <?php echo $_GET["Name"];?> </h3></div>
                                    <div class="col-sm-6 text-right">  
                                        <input type="hidden"name="" id="" value="<?php echo ""?>"> 
                                    </div>
                                </div> 
                            </div> 
                            <div class="card-body"> 
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th> </th>
                                            <th> APPLICATION  </th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($conn_1->query($query_application) as $app) {?>
                                            <tr>
                                                <td> <input type="checkbox" name="APP_ID[]" id="APP_ID[]" value="<?php echo $app["APP_ID"]?>"  style="width:30px; height:30px;"></td>
                                                <td><?php echo  $app["APP_NAME"]?> </td> 
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table> 
                                <hr/>
                                <div class="row form-group">
                                    <div class="col-sm-12 text-center"> 
                                        <input type="submit" name="mode" id="mode" value="SAVE" class="btn btn-danger">
                                        <input type="hidden" name="POS_ID" id="POS_ID" value="<?php echo $_GET["position"]?>">  
                                        <input type="hidden" name="name" id="name" value="<?php echo $_GET["Name"]?>" >  
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>  
                </section>
                
                <section class="col-lg-6 connectedSortable"> 
                    <div class="card card-info">
                        <div class="card-header"> 
                            <div class="row">
                                <div class="col-sm-6">  APPLICATION   ที่ใช้งานได้ </div>
                                <div class="col-sm-6 text-right">   
                                </div>
                            </div> 
                        </div> 
                        <div class="card-body"> 
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th> APPLICATION </th>
                                        <th>  STATUS </th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($conn_1->query($query_app_position) as $app_position) {?>
                                        <tr> 
                                            <td><?php echo  $app_position["APP_NAME"]?> </td> 
                                            <td><?php echo  $app_position["APP_STATUS"]?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table> 
                            </div>
                    </div>
                                            
                </section>
            </div>   
        </div>
    </section> 
</div> 
<?php require('frm_footer.php'); ?>
 