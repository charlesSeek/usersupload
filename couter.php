<?php
	for ($i = 1; $i <= 100; $i++) {
    	if (is_triple($i)&&is_fiver($i))
    		echo $i.":"."triplefiver\n";
    	if (is_triple($i)&&!is_fiver($i))
    		echo $i.":"."triple\n";
    	if (!is_triple($i)&&is_fiver($i))
    		echo $i.":"."fiver\n";
	}
	function is_triple($num){
		if ($num % 3 == 0)
			return true;
		else
			return false;
	}
	function is_fiver($num){
		if ($num % 5 == 0)
			return true;
		else
			return false;
		
	}
?>