<?php

    session_start();
	
	
    if((isset($_SESSION['baseSizeRange']))&&($_SESSION['connected'] == 1)) {
		
		echo $_SESSION['baseSizeRange'];
	}
?>