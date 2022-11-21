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

    $query_asdoc      = " SELECT a.ASSESTDOC_NO ,a.ASSESTDOC_ID ,a.DATE_OUT ,b.STATUS, c.STATUS_NAME, a.USEROUT_ID, a.USERID  
                                from  TB_ASSESTDOC a  
                                inner join TB_ASSESTUSED b on b.ASSESTDOC_ID = a.ASSESTDOC_ID
                                inner join TB_STATUS c on b.STATUS = c.STATUSID  
                                GROUP BY a.ASSESTDOC_NO,a.ASSESTDOC_ID ,a.DATE_OUT ,b.STATUS, c.STATUS_NAME, a.USEROUT_ID , a.USERID  "; 
                          
?>
<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?> 
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> เลขที่ใบเอกสารทรัพย์สินทั้งหมด   </h3> 
                            <div class="row">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-2 text-right"></div>
                                <div class="col-sm-2 text-right"></div>
                            </div>
                        </div> 
                        <div class="card-body"> 
                            <table class="table" id="table1">
                                <thead>
                                    <tr>
                                        <th>#</th> 
                                        <th>  </th>
                                        <th>สถานะทรัพย์สิน</th>  
                                        <th>ASSEST NO </th>
                                        <th>วันที่ออก</th>
                                        <th>คนทำออก </th> 
                                        <th>พนักงาน </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php   
                                        $no=1;
                                        foreach ($conn_1->query($query_asdoc) as $asdoc) { 
                                            $query_user     = " SELECT * from TB_USER  WHERE USERID = '".$asdoc["USEROUT_ID"]."'";  
                                            $getUser          = $conn_1->query($query_user);
                                            $user_data        = $getUser->fetch(); 

                                            if(isset($asdoc["USERID"])){
                                                $query_userused     = " SELECT * from TB_USER  WHERE USERID = '".$asdoc["USERID"]."'";  
                                                $getUserused          = $conn_1->query($query_userused);
                                                $userused_data        = $getUserused->fetch(); 

                                                $name_used            = $userused_data["FULLNAME"];
                                            }
                                            


                                            if($asdoc["STATUS"] == "2"){
                                                $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#66BFBF; font-size:30px;\"></i>"; 
                                                $urll       = "frm_Assest_doc.php";
                                            }elseif($asdoc["STATUS"] == "3"){
                                                $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#000000; font-size:30px;\"></i>"; 
                                                $urll       = "frm_Assest_cutdoc.php";
                                            }elseif($asdoc["STATUS"] == "4"){
                                                $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#FFE162; font-size:30px;\"></i>"; 
                                                $urll       = "frm_Assest_cutdoc.php";
                                            }else{
                                                $icon       =" <i class=\"fas fa-toggle-on\" style=\"color:#FF4A4A; font-size:30px;\"></i>"; 
                                                $urll       = "frm_Assest_doc.php";
                                            }
                                    ?>
                                        <tr>
                                            <td><?php echo $no;?> </td> 
                                            <td><?php echo $icon;?> </td> 
                                            <td><?php echo $asdoc["STATUS_NAME"];?></td>
                                            <td><?php echo $asdoc["ASSESTDOC_NO"];?></td>
                                            <td><?php echo date("d/m/Y H:i:s", strtotime($asdoc["DATE_OUT"]));?></td>
                                            <td><?php echo  $user_data["FULLNAME"];?> </td> 
                                            <td><?php echo  $name_used;?> </td>  
                                            <td>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <a href="frm_Assest_DocDetail.php?DocID=<?php echo $asdoc["ASSESTDOC_ID"];?>&Doc=<?php echo $asdoc["ASSESTDOC_NO"];?>"><i class="fas fa-folder-open" title="รายละเอียด <?php echo $asdoc["ASSESTDOC_NO"];?> " style="color:#17a2b8; font-size:20px;"></i>  </a>
                                                    </div>
                                                    <div class="col-sm-6"> <a href="<?php echo $urll;?>?docccc=<?php echo $asdoc["ASSESTDOC_ID"];?>"><i class="fas fa-print" title="ปริ้นใบทรัพย์สิน" style="color:#17a2b8; font-size:20px;"></i> </a></div>
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