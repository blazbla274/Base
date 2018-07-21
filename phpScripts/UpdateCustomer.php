<?php

    session_start();
	require_once "connect.php";
	
	$id = $_SESSION['id'];
	$time = date('Y-m-d H:i:s');
	
	
	if((isset($_SESSION['permision']))&&($_SESSION['permision'] < 3)&&($_SESSION['connected'] == 1)&&(isset($_POST['idPOST']))) {
		
		/*£¥CZENIE SIÊ Z BAZ¥ ABY ZMODYFIKOWAÆ KONSUMENTA*/
		
	    $polonczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	    mysqli_query($polonczenie, "SET CHARSET utf8");
	    mysqli_query($polonczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
		
		if($polonczenie->connect_errno!=0) {
		
			$_SESSION['statement'] = "Database connecting error.";
			
		} else {
			
			$idPOST = $_POST['idPOST'];
			$name = $_POST['name'];
			$surname = $_POST['surname'];
			$old = $_POST['old'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$address = $_POST['address'];
			$country = $_POST['country'];
			
			/*WALIDACJA DANYCH WEJŒCIOWYCH*/
			/*POCZ¥TEK*/
			$validation_error = "";
			
			$copy = "";
			if($name != "") {
				$column0 = "name='$name',";
				$copy = $name." ";
			} else {$column0 = "";}
			
			if($surname != "") {
				$column1 = "surname='$surname',";
				$copy = $copy.$surname;
			} else {$column1 = "";}
			
			
			$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);	
			if($email != "") {
				if(!((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false)||($emailB != $email))) {
					$column2 = "email='$email',";
				} else {
                    $column2 = "";
				    $validation_error = $validation_error." invalid email";					
				}
			} else {
				$column2 = "";
			} 
			
			if(($phone > 99999999)&&($phone < 1000000000)) {                     
				$column3 = "phone='$phone',";
			} else if($phone == "") {
				$column3 = "";
			} else {
				$column3 = "";
				
				if($validation_error != "") {
					$validation_error = $validation_error.",";
				}
				$validation_error = $validation_error." invalid phone number";
			}
			
			if($old != "") {
				$column4 = "old='$old',";
			} else {$column4 = "";}
			
			if($address != "") {
				$column5 = "address='$address',";
			} else {$column5 = "";}
			
			if($country != "") {
				$column6 = "country='$country',";
			} else {$column6 = "country='Poland',";}
			/*KONIEC*/
			
			
			$sql = "UPDATE customers SET $column0 $column1 $column2 $column3 $column4 $column5 $column6 addedByUserId='$id' WHERE id='$idPOST'"; //modyfikacja w bazie danych\
		
			if ($rezulatat=@$polonczenie->query($sql)) {
			
			    if($validation_error == "") {
					$sql = "INSERT INTO log (content, responsibleUserId, time) VALUES ('Successful update: $copy', '$id', '$time')"; //wpis do log serwera
					if($rezulatat=@$polonczenie->query($sql)); else $_SESSION['statement'] = "Database connecting error.";
				} else {
				    $sql = "INSERT INTO log (content, responsibleUserId, time) VALUES ('Failed update: $validation_error', '$id', '$time')"; //wpis do log serwera
					if($rezulatat=@$polonczenie->query($sql)); else $_SESSION['statement'] = "Database connecting error.";	
				}
			} else {
				
		    	$sql = "INSERT INTO log (content, responsibleUserId, time) VALUES ('Database connecting error to Update: $copy', '$id', '$time')"; //wpis do log serwera
			    if($rezulatat=@$polonczenie->query($sql)); else $_SESSION['statement'] = "Database connecting error to insert";
			}
		}
		

	} else if(isset($_SESSION['id'])) {
		
		$_SESSION['statement'] = "You have no permision.";
		
		/*£¥CZENIE SIÊ Z BAZ¥ ABY POWIADOMIÆ O PRÓBIE DODANIA*/
		$polonczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	     mysqli_query($polonczenie, "SET CHARSET utf8");
	    mysqli_query($polonczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

		if($polonczenie->connect_errno!=0) { //wpis o nieudanej próbie
				
			$_SESSION['statement'] = "Database connecting error.";
		} else {
				
			$sql = "SELECT name FROM users WHERE id='$id'"; //pobieram id u¿ytkownika prubuj¹cego wykonaæ nieudan¹ operacje
			if($rezulatat=@$polonczenie->query($sql)){
				$wiersz = $rezulatat->fetch_assoc();
		        $userName = $wiersz['name'];
			$rezulatat->free_result();
			}
				
			$copy = "<span> $userName </span>";
			$sql = "INSERT INTO log (content, responsibleUserId, time) VALUES ('Failed Insert, $copy have no permision to insert customer.', '$id', '$time')"; //wpis do log serwera
			if($rezulatat=@$polonczenie->query($sql));
			$polonczenie->close();
		}
		
	} 
	
	$_SESSION['navigationOverlap'] = 3;
	header('Location: /base/loged.php');
?>