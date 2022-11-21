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
    $item           = $_GET["DocID"];
    $docc           = $_GET["Doc"];
 
  

    // $query_assest   = " SELECT  * from TB_ASSESTUSED a 
    //                         inner join TB_ASSEST b on a.ASSESTID = b.ASSESTID
    //                         inner join TB_USER c on a.USERID = c.USERID 
    //                         inner join TB_ASSESTDOC d on a.USERID = d.USERID 
    //                         WHERE a.ASSESTID = '". $item."'
    //                         ORDER BY  c.USERID  DESC "; 
    // $getDetail      = $conn_1->query($query_assest);
    // $detail_data    = $getDetail->fetch();

    $query_assest   = " SELECT  c.ITEMNAME, c.SERIAL_NUMBER ,c.ASSEST_NO , c.RECEIVE_DATE ,c.DETAIL from TB_ASSESTDOC a 
                            inner join TB_ASSESTUSED b on a.ASSESTDOC_ID = b.ASSESTDOC_ID 
                            inner join TB_ASSEST c on c.ASSESTID = b.ASSESTID 
                            WHERE a.ASSESTDOC_ID = '". $item."'
                            ORDER BY  a.ASSESTDOC_ID  DESC "; 
    $getDetail      = $conn_1->query($query_assest);
    $detail_data    = $getDetail->fetch();



    // $query_assestone   = " SELECT  a.ITEMNAME , a.DETAIL ,a.SERIAL_NUMBER,a.PO_ID,a.CREATE_DATE ,a.CREATE_BY,a.RECEIVE_DATE,b.CATEGORIESNAME 
    //                     from TB_ASSEST a   
    //                     inner join TB_CATEGORIES b on a.CATEGORIESID = b.CATEGORIESID 
    //                     WHERE a.ASSESTID = '". $item."'"; 

    // $getDetailone      = $conn_1->query($query_assestone);
    // $detailone_data    = $getDetailone->fetch();

    
?>

<div class="content-wrapper" style="font-size:15px ;">
    <?php  require("frm_top_menu.php");?> 
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="div_frmkey">
                <section class="col-lg-12 connectedSortable">  
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">    เอกสารทรัพย์สิน  :  <?php echo $docc;?>  </h3>
                        </div> 
                        <div class="card-body"> 
                            <table class="table" id="table1">
                                <thead>
                                    <tr>
                                        <th>#</th> 
                                        <th>NAME </th>
                                        <th>SERIAL NUMBER  </th>
                                        <th>ASSEST NO</th>   
                                        <th>DETAIL </th>
                                        <th>RECEIVE DATE </th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php   
                                        $no=1;
                                        foreach ($conn_1->query($query_assest) as $assest) {    ?>
                                        <tr>
                                            <td><?php echo $no;?> </td>
                                            <td><?php echo $assest["ITEMNAME"];?></td> 
                                            <td><?php echo $assest["SERIAL_NUMBER"];?> </td>
                                            <td><?php echo $assest["ASSEST_NO"];?></td>  
                                            <td><?php echo $assest["DETAIL"];?></td> 
                                            <td><?php echo date("d/m/Y", strtotime($assest["RECEIVE_DATE"]));?></td> 
                                             
                                        </tr>
                                    <?php $no++;} ?>
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