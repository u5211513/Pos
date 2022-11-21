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
    
    $query_com      = " SELECT  *  from  TB_COMPANY";  
    $query_status   = " SELECT  *  from  TB_STATUS";  
    $query_cat      = " SELECT  *  from  TB_CATEGORIES";  
    $query_dept      = " SELECT  *  from  TB_DEPT";  
    $query_position = " SELECT  *  from  TB_POSITION";  
    $query_application  = " SELECT  *  from  TB_APPLICATION";  
     

    if(isset($_GET["actionMode"]) == "Delete"){
        
        $sql_pay        = " DELETE FROM TB_APPLICATION WHERE  APP_ID = '".$_GET["APP_ID"]."'"; 
        
        $query_del       = $conn_1->prepare($sql_pay);
        $query_del->execute(['APP_ID']); 
        
        echo "<script>alert('Delete Success.');</script>";
        echo "<script>location.replace('frm_AllMaster.php');</script>";
    }
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
                                <div class="col-sm-6"><h3 class="card-title">  POSITION </h3></div>
                                <div class="col-sm-6 text-right">  
                                    <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal_position" class="btn btn-warning"> ADD  </button>
                                </div>
                            </div> 
                        </div> 
                        <div class="card-body"> 
                            <table class="table table-hover" id="table1">
                                <thead>
                                    <tr>
                                        <th>   POSTION NAME</th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($conn_1->query($query_position) as $position) {?>
                                        <tr>
                                            <td><?php echo $position["POSITION_NAME"];?></td> 
                                            <td> 
                                                <div class="row">
                                                    <div class="col-sm-6"><i class="fas fa-edit editposition_data"  name="edit"  value="Edit" id="<?php echo $position["POSITIONID"]; ?>" title="แก้ไข Assest" style="color:#dc3545; font-size:20px;" type="button"></i></div>
                                                    <div class="col-sm-6"><a href="frm_Assest_addApplication.php?position=<?php echo $position["POSITIONID"];?>&Name=<?php echo $position["POSITION_NAME"];?>"> <i class="fas fa-folder-plus addApp_data"  name="add"  value="AddApplication" id="<?php echo $position["POSITIONID"]; ?>"   style="color:#007bff; font-size:25px;" type="button" title="เพิ่ม Application"></i> </a></div>
                                                </div> 
                                            </td>
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
                            <div class="row">
                                <div class="col-sm-6"><h3 class="card-title">   APPLICATION </h3></div>
                                <div class="col-sm-6 text-right">  
                                    <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal_application" class="btn btn-warning"> ADD  </button>
                                </div>
                            </div> 
                        </div> 
                        <div class="card-body"> 
                            <table class="table table-hover" id="table2">
                                <thead>
                                    <tr>
                                        <th>  APPLICATION NAME</th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($conn_1->query($query_application) as $app) {?>
                                        <tr>
                                            <td><?php echo $app["APP_NAME"];?></td> 
                                            <td> 
                                                <div class="row">
                                                    <div class="col-sm-6"> 
                                                        <i class="fas fa-edit editapp_data"  name="edit"  value="Edit" id="<?php echo $app["APP_ID"]; ?>" title="แก้ไข APPLICATION" style="color:#dc3545; font-size:20px;" type="button"></i>
                                                    </div>
                                                    <div class="col-sm-6"> 
                                                        <a href="frm_AllMaster.php?APP_ID=<?php echo $app["APP_ID"]; ?>&actionMode=Delete"> <i class="fas fa-trash-alt" style="color:#dc3545; font-size:20px;"></i>  </a>
                                                    </div>
                                                </div> 
                                            </td>
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

     

    <div id="add_data_Modal_position" class="modal fade ">  
        <div class="modal-dialog modal-lg">  
            <div class="modal-content">  
                <div class="modal-header">   
                    <h4 class="modal-title">  POSITION </h4>  
                </div>  
                <div class="modal-body">  
                    <form method="post" id="insert_position">  
                        <div class="row form-group">
                            <div class="col-sm-12"> 
                                <input type="text"  id="positionname" name="positionname"  placeholder="POSITION" class="form-control rounded-0 form-control-border">
                            </div> 
                        </div>  
                        <input type="hidden" name="mode" id="mode"  value="ADDPOSITION"/>  
                        <input type="hidden" name="POSITIONID" id="POSITIONID"/>  
                        <input type="submit" name="insert_ps" id="insert_ps" value="Insert" class="btn btn-success" />  
                    </form>   
                </div>  
                <div class="modal-footer">  
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
            </div>  
        </div>  
    </div> 

    <div id="add_data_Modal_application" class="modal fade ">  
        <div class="modal-dialog modal-lg">  
            <div class="modal-content">  
                <div class="modal-header">   
                    <h4 class="modal-title">  APPLICATION </h4>  
                </div>  
                <div class="modal-body">  
                    <form method="post" id="insert_application">  
                        <div class="row form-group">
                            <div class="col-sm-12"> 
                                <input type="text"  name="app_name" id="app_name" placeholder="APPLICATION" class="form-control rounded-0 form-control-border">
                                
                            </div> 
                        </div>  
                        <input type="hidden" name="mode" id="mode"  value="ADD_APPLICATION"/>  
                        <input type="hidden" name="APP_ID" id="APP_ID"/>  
                        <input type="submit" name="insert_a" id="insert_a" value="Insert" class="btn btn-success" />  
                    </form>  
                </div>  
                <div class="modal-footer">  
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
            </div>  
        </div>  
    </div> 

</div> 
<?php require('frm_footer.php'); ?>
<script> 
    $('#insert_position').on("submit", function(event){  
        event.preventDefault();  
        if($('#position').val() == ""){  
            alert("ใส่รายละเอียด");  
        }  else   {  
            $.ajax({  
                url:"../processControl/frm_process_addmaster.php",  
                method:"POST",  
                data:$('#insert_position').serialize(),  
                beforeSend:function(){  
                    $('#insert_ps').val("Process...");  
                },  
                success:function(data){ 
                    $('#insert_position')[0].reset();  
                    $('#add_data_Modal+prosition').modal('hide');  
                    location.reload();
                }  
            });  
        }  
    });
    
    $('#insert_application').on("submit", function(event){   
        event.preventDefault();  
        if($('#app_name').val() == ""){  
            alert("ใส่รายละเอียด");  
        }  else   {  
            $.ajax({  
                url:"../processControl/frm_process_addmaster.php",  
                method:"POST",  
                data:$('#insert_application').serialize(),  
                beforeSend:function(){  
                    $('#insert_a').val("Process...");  
                },  
                success:function(data){ 
                    $('#insert_application')[0].reset();  
                    $('#add_data_Modal_application').modal('hide');  
                    location.reload();
                }  
            });  
        }  
    });
 
    $(document).on('click', '.editposition_data', function(){   
        var POSITIONID = $(this).attr("id"); 
        $.ajax({  
            url:"../processControl/frm_process_fetch.php",  
            method:"POST",  
            data:{POSITIONID:POSITIONID},  
            dataType:"json",  
            success:function(data){  
                $('#positionname').val(data.POSITION_NAME);   
                $('#POSITIONID').val(data.POSITIONID);  
                $('#insert_ps').val("Update");  
                $('#add_data_Modal_position').modal('show');  
            }  
        });  
    });
  
    $(document).on('click', '.editapp_data', function(){   
        var APP_ID = $(this).attr("id"); 
        $.ajax({  
            url:"../processControl/frm_process_fetch.php",  
            method:"POST",  
            data:{APP_ID:APP_ID},  
            dataType:"json",  
            success:function(data){  
                $('#app_name').val(data.APP_NAME);   
                $('#APP_ID').val(data.APP_ID);  
                $('#insert_a').val("Update");  
                $('#add_data_Modal_application').modal('show');  
            }  
        });  
    });

    $(function() { 
        $("#table1").DataTable({    
            "lengthMenu": [[10, 20, 50, 100,200, -1], [10,20,50, 100,200, "All"]], 
            "lengthChange": true, 
            "autoWidth": false, 
            "paging": true, 
            "ordering": true,
            "info": false,  
            "searching": true,
             
        }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)'); 

        $("#table2").DataTable({    
            "lengthMenu": [[10, 20, 50, 100,200, -1], [10,20,50, 100,200, "All"]], 
            "lengthChange": true, 
            "autoWidth": false, 
            "paging": true, 
            "ordering": true,
            "info": false,  
            "searching": true,
             
        }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)'); 
    }); 
    
</script>
  