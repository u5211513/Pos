<?php
	$serverName_1 		= '192.168.2.117';
	$userName_1 		= 'dev';
	$userPassword_1 	= 'P@ssw0rd22';
	$dbName_1 			= 'dev';
	
	date_default_timezone_set('Asia/Bangkok');
	try{
		$conn_1 = new PDO("sqlsrv:server=$serverName_1 ; Database = $dbName_1", $userName_1, $userPassword_1);
		$conn_1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "OK Connect" .$dbName;
	}
	catch(Exception $e_1){
		die(print_r($e_1->getMessage()));
		//echo "No Connect"  .$dbName;
	}

	  
	//////////////////////////////////////////////

	$serverName 	= '192.168.2.106';
	$userName 		= 'sa';
	$userPassword 	= 'saf2007';
	$dbName 		= 'All_Fascino';
	
	try{
		$conn = new PDO("sqlsrv:server=$serverName ; Database = $dbName", $userName, $userPassword);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "OK Connect" .$dbName;
	}
	catch(Exception $e){
		die(print_r($e->getMessage()));
		//echo "No Connect"  .$dbName;
	}



	$serverName_hq 		= '192.168.2.106';
	$userName_hq 		= 'sa';
	$userPassword_hq 	= 'saf2007';
	$dbName_hq 			= 'HQ_Data';
	
	try{
		$conn_hq = new PDO("sqlsrv:server=$serverName_hq ; Database = $dbName_hq", $userName_hq, $userPassword_hq);
		$conn_hq->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//echo "OK Connect" .$dbName;
	}
	catch(Exception $e){
		die(print_r($e->getMessage()));
		//echo "No Connect"  .$dbName;
	}
 




?>
 