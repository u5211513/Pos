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
    $query_dept     = " SELECT  *  from  TB_DEPT";  
     
    $query_B = " SELECT BANKID, BANKNAME   from TB_BANKOFSTATION     where  FSCODE =  '".$FSCODE."'   ORDER BY FSCODE ASC";  
    $sql_bank = $conn_1->query( $query_B ); 
     
    $query_T = " SELECT  TOPICREMARK, TOPICID from TB_TOPICREMARK   WHERE  STATUS = 'Y' ORDER BY TOPICID DESC";  
    $sql_topic = $conn_1->query( $query_T ); 

    $query_assest      = " SELECT  * from TB_ASSEST a 
                            inner join TB_COMPANY b on a.COMPANY_ID = b.COMPANYID
                            inner join TB_CATEGORIES c on a.CATEGORIESID = c.CATEGORIESID
                            inner join TB_STATUS d on a.STATUS = d.STATUSID 
                            ORDER BY a.ASSESTID DESC "; 
?>

<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?>
    
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">  ASSEST  LIST  </h3> 
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-2 text-right">  
                                    <a href="frm_Asset_add.php"> <button type="button" name="add" id="add" class="btn btn-warning"> เพิ่มทรัพย์สิน  </button></a> 
                                </div>
                                <div class="col-sm-2 text-right">  
                                    <a href="frm_Assest_tranfer.php"> <button type="button" name="add" id="add" class="btn btn-warning">โอนย้าย </button></a> 
                                </div>
                                <div class="col-sm-2 text-right">  
                                    <a href="frm_Assest_cut.php"> <button type="button" name="add" id="add" class="btn btn-warning"> ตัดจำหน่าย </button></a> 
                                </div>
                            </div>
                        </div> 
                        <div class="card-body"> 
                            <table class="table" id="table1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> </th>
                                        <th>ASSEST NO. IT</th>
                                        <th>FIXED ASSEST  </th> 
                                        <th>COMPANY NAME</th>
                                        <th>STATUS</th>
                                        <th>CATEGORIESID</th>
                                        <th>ASSEST NAME</th> 
                                        <th>DETAIL</th> 
                                        <th>EMPLOYEE </th> 
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  
                                        $employeeU  = "";
                                        $no=1;
                                        foreach ($conn_1->query($query_assest) as $assest) {
                                            if($assest["CLOSE_DATE"] == ""){
                                                $closeDate      = "";
                                            }else{
                                                $closeDate      = date("d/m/Y", strtotime($assest["CLOSE_DATE"]));
                                            }
 
                                            $query_user     = " SELECT  b.FULLNAME from TB_ASSESTUSED a inner join TB_USER b on a.USERID = b.USERID   
                                                            WHERE a.ASSESTID = '".$assest["ASSESTID"]."'";  
                                            $getUser          = $conn_1->query($query_user);
                                            $user_data        = $getUser->fetch();
                                            
                                             

                                            if(isset($user_data["FULLNAME"]) && $assest["STATUS"] <> "2"){
                                               
                                                $employeeU   = $user_data["FULLNAME"];
                                            }else{
                                                $employeeU   = "-";
                                            }
                                            
                                            
                                            
                                            if($assest["STATUS"] == "2"){
                                                $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#66BFBF; font-size:30px;\"></i>";
                                                
                                            }elseif($assest["STATUS"] == "3"){
                                                $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#000000; font-size:30px;\"></i>";
                                               // $employee   =  "";
                                            }elseif($assest["STATUS"] == "4"){
                                                $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#FFE162; font-size:30px;\"></i>";
                                                //$employee   = "";
                                            }elseif($assest["STATUS"] == "1"){
                                                $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#FF4A4A; font-size:30px;\"></i>";
                                                //$employee   = isset($user_data["FULLNAME"]);
                                            }
                                            // else{
                                            //     $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#FF4A4A; font-size:30px;\"></i>";
                                            //     $employee   =  $user_data["FULLNAME"];
                                            // }
                                    ?>
                                        <tr>
                                            <td><?php echo $no;?> </td>
                                            <td><?php echo $icon ;?> </td>
                                            <td><?php echo $assest["ASSEST_NUMBER"];?> </td>
                                            <td><?php echo $assest["ASSEST_NO"];?></td> 
                                            <td><?php echo $assest["COMPANY_NAME"];?></td>
                                            <td><?php echo $assest["STATUS_NAME"];?></td>
                                            <td><?php echo $assest["CATEGORIESNAME"];?> </td>
                                            <td><?php echo $assest["ITEMNAME"];?></td> 
                                            <td><?php echo $assest["DETAIL"];?></td> 
                                            <td><?php echo $employeeU;?></td> 
                                            <td>
                                                <div class="row">
                                                    <div class="col-sm-6"> <a href="frm_Assest_detail.php?Name=<?php echo $assest["ITEMNAME"];?>&item=<?php echo $assest["ASSESTID"]?>"><i class="fas fa-folder-open" title="รายละเอียดคนใช้ <?php echo $assest["ITEMNAME"];?> " style="color:#17a2b8; font-size:20px;"></i>  </a></div>
                                                    <div class="col-sm-6">
                                                        <a href="frm_Asset_add.php?Name=<?php echo $assest["ITEMNAME"];?>&item=<?php echo $assest["ASSESTID"]?>">
                                                            <i class="fas fa-edit" title="รายละเอียด Assest" style="color:#dc3545; font-size:20px;"></i> 
                                                        </a> 
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php $no++;} ?>
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