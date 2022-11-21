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
    $item           = $_GET["item"];
 
  

    $query_assest   = " SELECT  * from TB_ASSESTUSED a 
                            inner join TB_ASSEST b on a.ASSESTID = b.ASSESTID
                            inner join TB_USER c on a.USERID = c.USERID  
                            WHERE a.ASSESTID = '". $item."'
                            ORDER BY  c.USERID  DESC "; 

    $getDetail      = $conn_1->query($query_assest);
    $detail_data    = $getDetail->fetch();


    $query_assestone   = " SELECT  a.ITEMNAME , a.DETAIL ,a.SERIAL_NUMBER,a.PO_ID,a.CREATE_DATE ,a.CREATE_BY,a.RECEIVE_DATE,b.CATEGORIESNAME ,a.ASSEST_NUMBER, a.MOBILE, a.ASSEST_NO, a.STATUS
                        from TB_ASSEST a   
                        inner join TB_CATEGORIES b on a.CATEGORIESID = b.CATEGORIESID 
                        WHERE a.ASSESTID = '". $item."'"; 

    $getDetailone      = $conn_1->query($query_assestone);
    $detailone_data    = $getDetailone->fetch();
    if($detailone_data["STATUS"] == "2"){
        $status__name        = "ทรัพย์สินว่าง";
    }elseif($detailone_data["STATUS"] == "3"){
        $status__name        = "ตัดจำหน่ายทรัพย์สินทิ้ง";
    }elseif($detailone_data["STATUS"] == "4"){
        $status__name        = "โอนย้ายทรัพย์สิน";
    }else{
        $status__name        = "รับมอบทรัพย์สิน";
    }

?>

<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?> 
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-8 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">    รายการทรัพย์สิน  :  <?php echo $detailone_data["ITEMNAME"]?>  </h3>
                        </div> 
                        <div class="card-body"> 
                            <table class="table" id="table1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> EMPLOYEE</th>
                                        <th>วันที่เริ่มใช้</th>
                                        <th>วันที่สิ้นสุด</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php   
                                        $no=1;
                                        foreach ($conn_1->query($query_assest) as $assest) {  
                                             
                                            if(isset($assest["DATE_EM_STOP"] )){
                                                $date_em_stop   = date("d/m/Y", strtotime($assest["DATE_EM_STOP"]));
                                            }else{ $date_em_stop   =" <i class=\"fas fa-toggle-on\" style=\"color:#66BFBF; font-size:30px;\"></i>";}
                                            
                                    ?>
                                        <tr>
                                            <td><?php echo $no;?> </td>
                                            <td><?php echo $assest["FULLNAME"];?></td>
                                            <td><?php echo  date("d/m/Y", strtotime($assest["CREATE_DATE"]));?> </td>
                                            <td><?php echo  $date_em_stop;?></td>  
                                        </tr>
                                    <?php $no++;} ?>
                                </tbody>
                            </table>
                        </div>
                    </div> 
   
                </section>
                <section class="col-lg-4 connectedSortable">  
                <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">    รายการทรัพย์สิน  :  <?php echo $detailone_data["ITEMNAME"]?>  </h3>
                        </div> 
                        <div class="card-body"> 
                            <div class="row form-group">
                                <div class="col-sm-12">   ASSEST NUMBER  :  <?php echo $detailone_data["ASSEST_NUMBER"]?></div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12">  ประเภททรัพย์สิน  :  <?php echo $detailone_data["CATEGORIESNAME"]?></div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12">  มือถือ  :  <?php echo $detailone_data["MOBILE"]?></div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12">  รายละเอียด  : <?php echo $detailone_data["DETAIL"]?></div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12"> FIXED NUMBER : <?php echo $detailone_data["ASSEST_NO"]?> </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-sm-12"> SERIAL NUMBER : <?php echo $detailone_data["SERIAL_NUMBER"]?> </div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12">PO :  <?php echo $detailone_data["PO_ID"]?></div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12"> CREATE DATE : <?php echo    date("d/m/Y H:i:s", strtotime($detailone_data["CREATE_DATE"]))?></div>
                            </div> 
                            <div class="row form-group">
                                <div class="col-sm-12"> CREATE BY: <?php echo $detailone_data["CREATE_BY"];?></div>
                            </div>  
                            <div class="row form-group">
                                <div class="col-sm-12"> RECEIVE DATE : <?php echo date("d/m/Y", strtotime($detailone_data["RECEIVE_DATE"]));?> </div>
                            </div>  
                        </div>
                        <div class="card-footer  text-center">
                            <h3 class="card-title">    STATUS   :  <?php echo $status__name ;?>  </h3>
                        </div>
                    </div> 
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