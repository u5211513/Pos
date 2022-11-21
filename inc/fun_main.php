<?php 
    function monthDiff($b_date, $e_date){ 
        $c_date = ((strtotime($e_date) - strtotime($b_date)));
        if(count(explode(".", $c_date)) >= 1){ $c_date++; }
        return (int)($c_date);
    }

    function sum_date($b_date, $e_date){
		$c_date = (strtotime($e_date) - strtotime($b_date)) / (60*60*24);
		if(count(explode(".", $c_date)) > 1){ $c_date++; }
		return (int)($c_date);
	}

    function selectedOption($value1, $value2){
		if($value1 == $value2){
			return "selected";
		}
	}
?>