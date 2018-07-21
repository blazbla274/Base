<?php

    session_start();
   
	if((isset($_GET['size']))&&($_SESSION['connected'] == 1)){
		
		$_SESSION['baseSizeRange'] = $_GET['size'];
	}
	
    if((isset($_GET['from']))&&($_SESSION['connected'] == 1)&&($_GET['from'] == 3)){
		
		$_SESSION['navigationOverlap'] = 3;
	}
	echo "";

?>