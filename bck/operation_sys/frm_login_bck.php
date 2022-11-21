<?php  $part  = "../"; ?>
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
        <div class="login-logo">
            <!-- <h1 class="m-0" style="color:#F66545;">  F A S C I N O </h1>  -->
        </div> 
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg" style="height: 10px;">     </p> 
                <h1 class="m-5" style="color:#F66545;">  F A S C I N O  </h1> 
                <p class="login-box-msg" style="height: 40px;">     </p> 
                <p class="login-box-msg">   เข้าสู่ระบบ    </p> 
                <form action="../processControl/frm_process_member.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control"  name="password" id="password" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" name="mode" id="mode" value="Login">Log In</button>
                        </div> 
                    </div>
                </form>
                <p class="login-box-msg" style="height: 50px;"> </p>  
            </div>
        </div>
    </div>
    <script src="<?php echo $part;?>plugins/jquery/jquery.min.js"></script> 
    <script src="<?php echo $part;?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script> 
    <script src="<?php echo $part;?>dist/js/adminlte.min.js"></script>
</body> 
</html>
<script>  
    $("#bt_send").click(function() {  
        let username        = $('#username').val().trim(); 
        let password        = $('#password').val().trim();  
        var myData = '&username=' + username +
        '&password=' + password + 
        '&mode=Login' 
        jQuery.ajax({
            url: "../processControl/frm_process_payment.php",
            data: myData,
            type: "POST",
            dataType: "json", 
            success: function(data) {    
                    
            } 
        }); 
    });
</script>