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
    
    $query_action   = " SELECT * from TB_ACTION  a  
                        inner join TB_DEPT b on a.A_DEP = b.DEPTID  where a.USERID = '".$USERID."'";  

    if(isset($_GET["DEPID"])){ 
        $query_dep      = " SELECT * from TB_DEPT   where DEPTID = '".$_GET["DEPID"]."'";  
        $getDep         = $conn_1->query($query_dep);
        $dep_data       = $getDep->fetch();

        $query_assest     = " SELECT  * from TB_ASSESTUSED a 
                        inner join TB_ASSEST b on a.ASSESTID = b.ASSESTID
                        inner join TB_USER c on a.USERID = c.USERID
                        inner join TB_DEPT d on d.DEPTID = c.DEPTID
                        inner join TB_STATUS e on a.STATUS = e.STATUSID
                        WHERE  d.DEPTID = '".$_GET["DEPID"]."'
                        ORDER BY ASSEST_USEDID DESC"; 
 
    }
   
?>

<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?>
    
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-4 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">  ASSEST  LIST  </h3>  
                        </div> 
                        <div class="card-body"> 
                            <?php foreach ($conn_1->query($query_action) as $abydep) {     ?>
                                <div class="row form-group">
                                    <div class="col-sm-8">
                                        <a href="?DEPID=<?php echo $abydep["DEPTID"]?>">  <?php echo $abydep["DEPT_NAME"]?> </a>
                                    </div> 
                                    <div class="col-sm-4 text-right">
                                        <a href="?DEPID=<?php echo $abydep["DEPTID"]?>"> <i class="fas fa-folder-open"  style="color:#007bff; font-size:25px;" ></i></a>
                                    </div> 
                                </div>
                                <hr/> 
                            <?php } ?>
                           
                        </div>
                    </div>  
                </section>
                <?php  if(isset($_GET["DEPID"])){?>
                    <section class="col-lg-8 connectedSortable">  
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">   VIEW   <?php echo $dep_data["DEPT_NAME"] . "" ;?> </h3>  
                            </div> 
                            <div class="card-body">   
                                <table class="table" id="table1" style="font-size:13px;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> </th> 
                                            <th>STATUS</th> 
                                            <th>ITEMNAME</th> 
                                            <th>DETAIL</th> 
                                            <th> EMPLOYEE </th>  
                                            <th> </th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php   
                                            $no=1;
                                            foreach ($conn_1->query($query_assest) as $assest) { 
                                                $query_user     = " SELECT  b.FULLNAME from TB_ASSESTUSED a inner join TB_USER b on a.USERID = b.USERID   
                                                                    WHERE a.ASSESTID = '".$assest["ASSESTID"]."'   ORDER BY ASSEST_USEDID DESC";  
                                                $getUser          = $conn_1->query($query_user);
                                                $user_data        = $getUser->fetch();
                                                
                                                if($assest["STATUS"] == "2"){
                                                    $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#66BFBF; font-size:30px;\"></i>";
                                                    $employee   = "";
                                                }elseif($assest["STATUS"] == "3"){
                                                    $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#000000; font-size:30px;\"></i>";
                                                    $employee   = "";
                                                }elseif($assest["STATUS"] == "4"){
                                                    $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#FFE162; font-size:30px;\"></i>";
                                                    $employee   = $user_data["FULLNAME"];
                                                }else{
                                                    $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#FF4A4A; font-size:30px;\"></i>";
                                                    $employee   = $user_data["FULLNAME"];
                                                }
                                        ?>
                                            <tr>
                                                <td><?php echo $no;?> </td>
                                                <td><?php echo $icon ;?> </td> 
                                                <td><?php echo $assest["STATUS_NAME"];?></td> 
                                                <td><?php echo $assest["ITEMNAME"];?></td> 
                                                <td><?php echo $assest["DETAIL"];?></td> 
                                                <td><?php echo $employee?></td> 
                                                <td> 
                                                    <a href="frm_Assest_detail.php?Name=<?php echo $assest["ITEMNAME"];?>&item=<?php echo $assest["ASSESTID"]?>">    
                                                        <i class="fas fa-folder-open"  style="color:#28a745; font-size:25px;" ></i> 
                                                    </a>
                                                </td> 
                                                 
                                            </tr>
                                        <?php $no++;} ?>
                                    </tbody>
                                </table>   
                            </div>
                        </div>  
                    </section>
                <?php } ?>
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
    
    
    $(document).on('click', '.view_data', function(){  
        var employee_id = $(this).attr("id");  
        if(employee_id != '')  
        {  
            $.ajax({  
                url:"select.php",  
                method:"POST",  
                data:{employee_id:employee_id},  
                success:function(data){  
                    $('#employee_detail').html(data);  
                    $('#dataModal').modal('show');  
                }  
            });  
        }            
    });  

</script>