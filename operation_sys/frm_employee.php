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
    
    //$query_user      =  " SELECT  * from TB_USER a inner join TB_COMPANY b on a.COMPANYID = b.COMPANYID  ORDER BY a.USERID DESC"; ;   
    $query_user     = " SELECT  * from TB_USER a inner join TB_COMPANY b on a.COMPANYID = b.COMPANYID 
                    inner join TB_DEPT c on a.DEPTID = c.DEPTID  
                    inner join TB_POSITION d on a.POSITIONID = d.POSITIONID 
                    ORDER BY a.USERID DESC"; 
?>
<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?>
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">   EMPLOYEE LIST  </h3>
                        </div> 
                        <div class="card-body"> 
                            <table class="table" id="table1">
                                <thead>
                                    <tr>
                                        <th> COMPANY NAME </th>
                                        <th> ID  </th>
                                        <th> NAME </th>
                                        <th> DEPARTMENT</th> 
                                        <th> POSITION</th>
                                        <th> START DATE </th>
                                        <th> </th>
                                        <th> </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php   foreach ($conn_1->query($query_user) as $emp) {
                                            if($emp["DATE_START"] == ""){
                                                $date_start     = "";
                                            }else{   $date_start     =  date("d/m/Y",strtotime($emp["DATE_START"])); }

                                            $query_used     = " SELECT  * from TB_ASSESTDOC   WHERE USERID = '". $emp["USERID"]."'"; 
                                            $getDoc          = $conn_1->query($query_used);
                                            $doc_data        = $getDoc->fetch();
                                        ?>
                                        <tr>
                                            <td><?php echo $emp["COMPANY_NAME"];?></td>
                                            <td><?php echo $emp["IDNO"];?> </td>
                                            <td><?php echo $emp["FULLNAME"];?></td>
                                            <td><?php echo $emp["DEPT_NAME"];?></td>
                                            <td><?php echo $emp["POSITION_NAME"];?></td>
                                            <td><?php echo $date_start;?></td>
                                            <td><?php echo "";?></td>
                                            <td><?php echo "";?></td> 
                                            <td>
                                                <div class="row">
                                                    <div class="col-sm-6"> <a href="frm_Assest_outin.php?Name=<?php echo $emp["FULLNAME"];?>&IDNO=<?php echo $emp["IDNO"];?>&IDD=<?php echo $emp["USERID"]?>"><i class="fas fa-folder-open" title="จัดการ Assest" style="color:#17a2b8; font-size:20px;"></i> </a> </div>
                                                    <div class="col-sm-6">  </div>
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
                </section>
            </div>   
        </div>
    </section> 
</div> 
<?php require('frm_footer.php'); ?>
<script>
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