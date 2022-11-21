<?php 
    $actions        = $aaction;
    $fscode         = $branch_code;
    $datetime_cur   = $date_cur;
    $userID         = $userId;
    $userName       = $username;  

   $sel_in                 = " INSERT INTO TB_USERLOG (USERID , USERNAME, FSCODE ,DATECUR ,ACTIONS , IPADDRESS ) ";
   $sel_in                 .= " VALUES('".$userID."' ,'".$userName."' ,'".$fscode."' , '".$datetime_cur."' ,'".$actions ."' ,'". $ipaddress."')";
   $res_insert             = $conn_1->query($sel_in);



?>