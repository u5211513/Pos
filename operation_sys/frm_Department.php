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
                <section class="col-lg-12 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            
                            <div class="row">
                                <div class="col-sm-6"><h3 class="card-title">   DEPARTMENT </h3></div>
                                <div class="col-sm-6 text-right">  
                                    <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal_dep" class="btn btn-warning"> ADD  </button>
                                </div>
                            </div> 
                        </div> 
                        <div class="card-body"> 
                            <table class="table table-hover" id="table1">
                                <thead>
                                    <tr>
                                        <th>  DEPARTMENT</th> 
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($conn_1->query($query_dept) as $dept) {?>
                                        <tr>
                                            <td><?php echo $dept["DEPT_NAME"];?></td> 
                                            <td><i class="fas fa-edit editdep_data"  name="edit"  value="Edit" id="<?php echo $dept["DEPTID"]; ?>" title="แก้ไข Assest" style="color:#dc3545; font-size:20px;" type="button"></i>  </td>
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

     

    

    <div id="add_data_Modal_dep" class="modal fade ">  
        <div class="modal-dialog modal-lg">  
            <div class="modal-content">  
                <div class="modal-header">   
                    <h4 class="modal-title">  DEPARTMENT </h4>  
                </div>  
                <div class="modal-body">  
                    <form method="post" id="insert_dep">  
                        <div class="row form-group">
                            <div class="col-sm-12"> 
                                <input type="text"  id="depname" name="depname"  placeholder="DEPARTMENT" class="form-control rounded-0 form-control-border">
                            </div> 
                        </div>  
                        <input type="hidden" name="mode" id="mode"  value="ADDDEP"/>  
                        <input type="hidden" name="DEPTID" id="DEPTID"/>  
                        <input type="submit" name="insert_d" id="insert_d" value="Insert" class="btn btn-success" />  
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
    $('#insert_dep').on("submit", function(event){  
        event.preventDefault();  
        if($('#depname').val() == ""){  
            alert("ใส่รายละเอียด");  
        }  else   {  
            $.ajax({  
                url:"../processControl/frm_process_addmaster.php",  
                method:"POST",  
                data:$('#insert_dep').serialize(),  
                beforeSend:function(){  
                    $('#insert_d').val("Process...");  
                },  
                success:function(data){ 
                    $('#insert_dep')[0].reset();  
                    $('#add_data_Modal_dep').modal('hide');  
                    location.reload();
                }  
            });  
        }  
    });

   
    $(document).on('click', '.editdep_data', function(){   
        var DEPTID = $(this).attr("id"); 
        $.ajax({  
            url:"../processControl/frm_process_fetch.php",  
            method:"POST",  
            data:{DEPTID:DEPTID},  
            dataType:"json",  
            success:function(data){  
                $('#depname').val(data.DEPT_NAME);   
                $('#DEPTID').val(data.DEPTID);  
                $('#insert_d').val("Update");  
                $('#add_data_Modal_dep').modal('show');  
                //console.log(data);
                
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
 
    }); 
 
</script>
  