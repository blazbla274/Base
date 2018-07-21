<?php

    session_start();
	require_once "connect.php";
	
	$id = $_SESSION['id'];
	
	if((isset($_SESSION['permision']))&&($_SESSION['permision'] < 3)) {
	    
        if ((isset($_POST['name']))&&
    	(isset($_POST['surname']))&&
	    (isset($_POST['old']))&&
	    (isset($_POST['email']))&&
		(isset($_POST['address']))&&
		(isset($_POST['country']))) {

		    $name = $_POST['name'];
			$surname = $_POST['surname'];
			$old = $_POST['old'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$country = $_POST['country'];
			$phone = $_POST['phone'];
			
			/*ŁĄCZENIE SIĘ Z BAZĄ ABY DODAĆ KONSUMENTA*/
		
	        $polonczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	        mysqli_query($polonczenie, "SET CHARSET utf8");
	        mysqli_query($polonczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

			if($polonczenie->connect_errno!=0) {
				
				$_SESSION['statement'] = "Database connecting error.";
			} else {
				
				$time = date('Y-m-d H:i:s');
				$sql = "INSERT INTO customers VALUES (NULL, '$name', '$surname','$email', '$phone', '$old','$address','$country', 'unknown', '0', '$id',  '$time', '$time')"; //wstawiam do bazy klienta
			   
			    if($rezulatat=@$polonczenie->query($sql)){
					
					$sql = "INSERT INTO log (content, responsibleUserId, time) VALUES ('Successful customer  insert: $name $surname', '$id', '$time')"; //wpis do log serwera
			        if($rezulatat=@$polonczenie->query($sql)); else $_SESSION['statement'] = "Database connecting error.";
				} else {
					$sql = "INSERT INTO log (content, responsibleUserId, time) VALUES ('Database connecting error to insert: $name $surname', '$id', '$time')"; //wpis do log serwera
			        if($rezulatat=@$polonczenie->query($sql)); else $_SESSION['statement'] = "Database connecting error to insert";
				}
				
				$sql = "SELECT * FROM customers WHERE email='$email' AND addTime='$time'"; //pobieram id dodanego klienta
				if($rezulatat=@$polonczenie->query($sql)){
					
				    $wiersz = $rezulatat->fetch_assoc();
			        $customerId = $wiersz['id'];
				}
			
				$sql = "UPDATE users SET lastAddCustomerId='$customerId' WHERE id='$id'"; //zapisuje informacje o użytkowniku który wykonał dodawanie
				$rezulatat=@$polonczenie->query($sql);
				
				$polonczenie->close();
			}
		}		

	} else {
		
		$_SESSION['statement'] = "You have no permision.";
		
		/*ŁĄCZENIE SIĘ Z BAZĄ ABY POWIADOMIĆ O PRÓBIE DODANIA*/
		$polonczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	    mysqli_query($polonczenie, "SET CHARSET utf8");
	    mysqli_query($polonczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

		if($polonczenie->connect_errno!=0) {
				
			$_SESSION['statement'] = "Database connecting error.";
		} else {
				
			$sql = "SELECT name FROM users WHERE id='$id'"; //pobieram id użytkownika prubującego wykonać nieudaną operacje
			if($rezulatat=@$polonczenie->query($sql)){
				$wiersz = $rezulatat->fetch_assoc();
		        $userName = $wiersz['name'];
			}
				
			$copy = "<span> $userName </span>";
			$sql = "INSERT INTO log (content, responsibleUserId, time) VALUES ('Failed Insert, $copy have no permision to insert customer.', '$id', '$time')"; //wpis do log serwera
		    if($rezulatat=@$polonczenie->query($sql));
			$polonczenie->close();
		}
		
	}
	
	$_SESSION['navigationOverlap'] = 1;
	header('Location: /base/loged.php');
?>