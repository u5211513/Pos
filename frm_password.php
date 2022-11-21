<?php  
    ob_start();
    session_start();  
    @error_reporting(E_ALL ^ E_NOTICE);
 
    include("inc/fun_connect.php"); 
    if($_SESSION["USERID"] == ""){
		echo "<script>alert('Please Log In.');</script>";
		echo "<script>location.replace('../frm_login.php');</script>";
    }
    $part  = "";  
    $USERNAME   = $_SESSION["USERNAME"];
    $USERID     = $_SESSION["USERID"];
    $query_oper = " SELECT * from TB_USER where   USERID = '".$USERID."'";   
    $getRes     = $conn_1->query($query_oper);
    $user_data  = $getRes->fetch();
    $DEP        = $user_data["DEPARTCODE"];
    $USERNAMES  = $user_data["USERNAME"];

    if($DEP == 01){
        $url        = "operation_sys/index.php";
    }elseif($DEP == 03){
        $url        = "administrator_sys/index.php";
    }elseif($DEP == 02){
        $url        =  "account_sys/index.php";
    }
 
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>  F A S C I N O </title>   
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">  
    <link rel="stylesheet" href="<?php echo $part;?>plugins/fontawesome-free/css/all.min.css"> 
    <link rel="stylesheet" href="<?php echo $part;?>plugins/icheck-bootstrap/icheck-bootstrap.min.css"> 
    <link rel="stylesheet" href="<?php echo $part;?>dist/css/adminlte.min.css"> 
</head>
<style>
    body{ 
        font-family: 'Kanit';
        font-size:16px;
    }
</style> 
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary"> 
            <div class="card-body">
                <p class="login-box-msg" style="height: 10px;">     </p> 
                <h1 class="m-4" style="color:#F66545;">  F A S C I N O  </h1> 
                <p class="login-box-msg" style="height: 40px;">     </p> 
                <p class="login-box-msg">    เปลี่ยนรหัสผ่าน    </p> 
                <form action="processControl/frm_process_member.php" method="post">
                    <div class="input-group mb-3">
                        <input type="hidden" name="username" id="username" value="<?php echo $USERNAME;?>">
                        <input type="hidden" name="userid" id="userid" value="<?php echo $USERID;?>"> 
                        <input type="password" class="form-control"  name="password" id="password" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control"  name="con_password" id="con_password" placeholder="Confirm Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block"  name="mode" id="mode" value="Ch_password" >Change password</button>
                        </div> 
                    </div><br/>
                </form>  
                    <div class="row form-group">
                        <div class="col-6">
                           <a href="<?php echo $url;?>"   class="btn btn-success btn-block">   <i class="fas fa-reply"></i>  หน้าแรก  </a>
                        </div> 
                    </div> 
            </div> 
        </div>
    </div> 
    <script src="<?php echo $part;?>plugins/jquery/jquery.min.js"></script> 
    <script src="<?php echo $part;?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script> 
    <script src="<?php echo $part;?>dist/js/adminlte.min.js"></script>
</body> 
</html>