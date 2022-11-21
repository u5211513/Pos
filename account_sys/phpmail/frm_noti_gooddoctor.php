<?php 
    $token          = "PTRfgveRqXc1y0sDIIgC1DByxaHWYRf1i8zG2En9NYX"; 
    $res            = "Auto";
    $message = 
            " \n" . 
            " Good Doctor  \n" . 
            "Send  mail success   \n" .  
            "Date : ". date("d/m/Y H:i:s") ."\n" . 
            "By :" . $res  ;  

    line_notify_ABANDON($token, $message);
    function line_notify_ABANDON($token, $message){
        $line_api        = $token;  
        $mms            =  trim($message);   
        $chonn = curl_init(); 
        curl_setopt( $chonn, CURLOPT_URL, "https://notify-api.line.me/api/notify");  
        curl_setopt( $chonn, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt( $chonn, CURLOPT_SSL_VERIFYPEER, 0);  
        curl_setopt( $chonn, CURLOPT_POST, 1); 
        curl_setopt( $chonn, CURLOPT_POSTFIELDS, "message=$mms"); 
        curl_setopt( $chonn, CURLOPT_FOLLOWLOCATION, 1); 
        $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$line_api.'', );
        curl_setopt($chonn, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt( $chonn, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec( $chonn );  
        if(curl_error($chonn))  { 
            echo 'error:' . curl_error($chonn); 
        }  else { 
            $result_ = json_decode($result, true); 
        } 
        curl_close( $chonn );   
    }
	 
	
?>