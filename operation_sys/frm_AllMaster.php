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
                                <div class="col-sm-6"><h3 class="card-title">  COMPANY  </h3></div>
                                <div class="col-sm-6 text-right">  
                                    <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal_com" class="btn btn-warning"> ADD  </button>
                                </div>
                            </div> 
                        </div> 
                        <div class="card-body"> 
                            <table class="table  table-hover">
                                <thead>
                                    <tr>
                                        <th> COMPANY NAME</th>
                                        <th> STATUS </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($conn_1->query($query_com) as $company) {?>
                                        <tr>
                                            <td><?php echo $company["COMPANY_NAME"];?></td>
                                            <td><?php echo $company["COMPANY_STATUS"];?>  </td>
                                            <td> 
                                                <i class="fas fa-edit editcom_data"  name="edit"  value="Edit" id="<?php echo $company["COMPANYID"]; ?>" title="แก้ไข COMPANY" style="color:#dc3545; font-size:20px;" type="button"></i>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div> 
 
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6"><h3 class="card-title">  CATEGORIES </h3></div>
                                <div class="col-sm-6 text-right">  
                                    <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal" class="btn btn-warning"> ADD  </button>
                                </div>
                            </div> 
                        </div> 
                        <div class="card-body"> 
                            <table class="table table-hover" id="cat_table">
                                <thead>
                                    <tr>
                                        <th> STATUS NAME</th>
                                        <th> SATTUS </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($conn_1->query($query_cat) as $cat) {?>
                                        <tr>
                                            <td><?php echo $cat["CATEGORIESNAME"];?></td>
                                            <td><?php echo $cat["CATEGORIES_STATUS"];?>  </td>
                                            <td>   
                                                <i class="fas fa-edit editc_data"  name="edit"  value="Edit" id="<?php echo $cat["CATEGORIESID"]; ?>" title="แก้ไข CATEGORIES" style="color:#dc3545; font-size:20px;" type="button"></i>
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
                                <div class="col-sm-6"><h3 class="card-title">  STATUS  ASSEST</h3></div>
                                <div class="col-sm-6 text-right">  
                                    <button type="button" name="add" id="add" data-toggle="modal" data-target="#add_data_Modal_status" class="btn btn-warning"> ADD  </button>
                                </div>
                            </div> 
                        </div> 
                        <div class="card-body"> 
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>  STATUS NAME</th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($conn_1->query($query_status) as $status) {?>
                                        <tr>
                                            <td><?php echo $status["STATUS_NAME"];?></td> 
                                            <td> 
                                                <i class="fas fa-edit edit_data"  name="edit"  value="Edit" id="<?php echo $status["STATUSID"]; ?>" title="แก้ไข Assest" style="color:#dc3545; font-size:20px;" type="button"></i>
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

    <div id="add_data_Modal" class="modal fade ">  
        <div class="modal-dialog modal-lg">  
            <div class="modal-content">  
                <div class="modal-header">   
                    <h4 class="modal-title">   ADD  CATEGORIES</h4>  
                </div>  
                <div class="modal-body">  
                    <form method="post" id="insert_cat">  
                        <div class="row form-group">
                            <div class="col-sm-12"> 
                                <input type="text"  id="cat" name="cat"  placeholder="ADD  CATEGORIES" class="form-control rounded-0 form-control-border">
                            </div> 
                        </div>  
                        <input type="hidden" name="mode" id="mode"  value="ADDCate"/> 
                        <input type="hidden" name="CATEGORIESID" id="CATEGORIESID"/>   
                        <input type="submit" name="insert_c" id="insert_c" value="Insert" class="btn btn-success" />  
                    </form>  
                </div>  
                <div class="modal-footer">  
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
            </div>  
        </div>  
    </div>
    
    <div id="add_data_Modal_com" class="modal fade ">  
        <div class="modal-dialog modal-lg">  
            <div class="modal-content">  
                <div class="modal-header">   
                    <h4 class="modal-title">   ADD  COMPANY</h4>  
                </div>  
                <div class="modal-body">  
                    <form method="post" id="insert_com">  
                        <div class="row form-group">
                            <div class="col-sm-12"> 
                                <input type="text"  id="com" name="com"  placeholder="ADD  COMPANY" class="form-control rounded-0 form-control-border">
                            </div> 
                        </div>  
                        <input type="hidden" name="mode" id="mode"  value="ADDCOMPANY"/>  
                        <input type="hidden" name="COMPANYID" id="COMPANYID"/>  
                        <input type="submit" name="insert_company" id="insert_company" value="Insert" class="btn btn-success" />  
                    </form>  
                </div>  
                <div class="modal-footer">  
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
            </div>  
        </div>  
    </div>

    <div id="add_data_Modal_status" class="modal fade ">  
        <div class="modal-dialog modal-lg">  
            <div class="modal-content">  
                <div class="modal-header">   
                    <h4 class="modal-title">   ADD  STATUS</h4>  
                </div>  
                <div class="modal-body">  
                    <form method="post" id="insert_status">  
                        <div class="row form-group">
                            <div class="col-sm-12"> 
                                <input type="text"  id="status" name="status"  placeholder="ADD  STATUS" class="form-control rounded-0 form-control-border">
                            </div> 
                        </div>  
                        <input type="hidden" name="mode" id="mode"  value="ADDSTATUS"/>  
                        <input type="hidden" name="STATUSID" id="STATUSID"/>  
                        <input type="submit" name="insert_ss" id="insert_ss" value="Insert" class="btn btn-success" />  
                    </form>  
                </div>  
                <div class="modal-footer">  
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
            </div>  
        </div>  
    </div>

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
    
    $('#insert_cat').on("submit", function(event){  
        event.preventDefault();  
        if($('#cat').val() == ""){  
            alert("ใส่รายละเอียด");  
        }  else   {  
            $.ajax({  
                url:"../processControl/frm_process_addmaster.php",  
                method:"POST",  
                data:$('#insert_cat').serialize(),  
                beforeSend:function(){  
                    $('#insert_c').val("Process...");  
                },  
                success:function(data){ 
                    //alert(data);  
                    $('#insert_cat')[0].reset();  
                    $('#add_data_Modal').modal('hide');  
                    location.reload();
                }  
            });  
        }  
    }); 

    $('#insert_com').on("submit", function(event){  
        event.preventDefault();  
        if($('#com').val() == ""){  
            alert("ใส่รายละเอียด");  
        }  else   {  
            $.ajax({  
                url:"../processControl/frm_process_addmaster.php",  
                method:"POST",  
                data:$('#insert_com').serialize(),  
                beforeSend:function(){  
                    $('#insert_company').val("Process....");  
                },  
                success:function(data){  
                    $('#insert_com')[0].reset();  
                    $('#add_data_Modal').modal('hide');  
                    location.reload();
                }  
            });  
        }  
    }); 

    $('#insert_status').on("submit", function(event){  
        event.preventDefault();  
        if($('#status').val() == ""){  
            alert("ใส่รายละเอียด");  
        }  else   {  
            $.ajax({  
                url:"../processControl/frm_process_addmaster.php",  
                method:"POST",  
                data:$('#insert_status').serialize(),  
                beforeSend:function(){  
                    $('#insert_ss').val("Process");  
                },  
                success:function(data){ 
                    $('#insert_status')[0].reset();  
                    $('#add_data_Modal').modal('hide');  
                    location.reload();
                }  
            });  
        }  
    });

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

  
    $(document).on('click', '.edit_data', function(){   
        var STATUSID = $(this).attr("id"); 
        $.ajax({  
            url:"../processControl/frm_process_fetch.php",  
            method:"POST",  
            data:{STATUSID:STATUSID},  
            dataType:"json",  
            success:function(data){  
                $('#status').val(data.STATUS_NAME);   
                $('#STATUSID').val(data.STATUSID);  
                $('#insert_ss').val("Update");  
                $('#add_data_Modal_status').modal('show');  
                //console.log(data);
                
            }  
        });  
    });  
     
    $(document).on('click', '.editc_data', function(){   
        var CATEGORIESID = $(this).attr("id"); 
        $.ajax({  
            url:"../processControl/frm_process_fetch.php",  
            method:"POST",  
            data:{CATEGORIESID:CATEGORIESID},  
            dataType:"json",  
            success:function(data){  
                $('#cat').val(data.CATEGORIESNAME);   
                $('#CATEGORIESID').val(data.CATEGORIESID);  
                $('#insert_c').val("Update");  
                $('#add_data_Modal').modal('show');  
                console.log(data);
                
            }  
        });  
    });  

    $(document).on('click', '.editcom_data', function(){   
        var COMPANYID = $(this).attr("id"); 
        $.ajax({  
            url:"../processControl/frm_process_fetch.php",  
            method:"POST",  
            data:{COMPANYID:COMPANYID},  
            dataType:"json",  
            success:function(data){ 
                
                $('#com').val(data.COMPANY_NAME);   
                $('#COMPANYID').val(data.COMPANYID);  
                $('#insert_company').val("Update");  
                $('#add_data_Modal_com').modal('show');  
                //console.log(data);
                
            }  
        });  
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
</script>
  