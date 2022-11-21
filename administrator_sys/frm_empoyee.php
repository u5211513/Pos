<?php
@error_reporting(E_ALL ^ E_NOTICE);
ob_start();
session_start();
if ($_SESSION["USERID"] == "") {
    echo "<script>alert('Please Log In.');</script>";
    echo "<script>location.replace('frm_login.php');</script>";
}
require("frm_heard.php");
include("../inc/fun_connect.php");
include("frm_member.php");
require("../frm_left_top.php");

$date_start     = date("Y-m-d", strtotime("-7 days"));
$date_stop      = date("Y-m-d");
//$query = " SELECT  *   from TB_USER  ";
$query_used     = " SELECT  * from TB_USER a inner join TB_COMPANY b on a.COMPANYID = b.COMPANYID 
                    inner join TB_DEPT c on a.DEPTID = c.DEPTID  
                    inner join TB_POSITION d on a.POSITIONID = d.POSITIONID 
                    ORDER BY a.USERID DESC"; 
 

if (isset($_GET["action"]) == "suppend") {
    $STATUS         = 'N';
    $USERID         = $_GET["USERID"];
    $sql_up         = " UPDATE TB_USER SET STATUS = '" . $STATUS . "'   WHERE USERID = '" . $USERID . "'";
    $stmt_up        = $conn_1->prepare($sql_up);
    $stmt_up->execute(['STATUS', 'USERID']);
    echo "<script>location.replace('frm_empoyee.php');</script>";
}
?>
<div class="content-wrapper" style="font-size:13px ;">
    <?php include("frm_top_menu.php"); ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="col-lg-12  connectedSortable">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title h4"> รายชื่อพนักงานทั้งหมด </h3>
                        </div>
                        <div class="card-body h6">
                            <div class="row form-group">
                                <table width=100% class="table table-bordered table-striped table-responsive" id="table1">
                                    <thead>
                                        <tr>
                                            <th> #</th>
                                            <th  width="200">Company</th>
                                            <th width="300">ชื่อ - สกุล</th>
                                            <th>Username</th>
                                            <th>Password </th>
                                            <th> Department </th>
                                            <th> สาขา</th>
                                            <th width="200">ตำแหน่งงาน</th>
                                            <th>สถานะ</th>
                                            <th class="text-center">การจัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $aa = 1; 
                                        foreach ($conn_1->query($query_used) as $row) { ?>
                                            <tr>
                                                <td><?php echo $aa . "."; ?>
                                                    <input type="hidden" name="USERID" id="<?php echo $row["USERID"] ?>" value="<?php echo $row["USERID"]; ?>">
                                                    <input type="hidden" name="STATUS" id="STATUS" value="<?php echo $row["STATUS"]; ?>">
                                                </td>
                                                <td><?php echo $row["COMPANY_NAME"];?></td>
                                                <td><?php echo $row["FULLNAME"];?></td>
                                                <td><?php echo $row["USERNAME"];?></td>
                                                <td><?php echo $row["PASSWORD"];?></td>
                                                <td><?php echo $row["DEPT_NAME"];?></td>
                                                <td><?php echo $row["FSCODE"];?></td>
                                                <td><?php echo $row["POSITION_NAME"];?></td>
                                                <td class="text-center"><?php echo $row["STATUS"];?></td>
                                                <td width="300">
                                                    <div class="row form-group">
                                                        <div class="col-lg-6">
                                                            <a href="frm_empoyee.php?action=suppend&USERID=<?php echo $row["USERID"] ?>">
                                                                <i class="fas fa-times" title="ระงับ"></i> ระงับ
                                                            </a>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <a href="index.php?action=EDIT&USER_ID=<?php echo $row["USERID"] ?>">
                                                                <i class="fas fa-edit" title="แก้ไข" ></i> แก้ไข
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php $aa++;
                                        } ?>
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
    </a>
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
 