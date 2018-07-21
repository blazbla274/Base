<?php //skrypt zwraca wici¹ga z bazy zadan¹ iloœæ rekordów

    session_start();
	
	if(isset($_SESSION['connected'])&&($_SESSION['connected'] == 1)&&(isset($_GET['size']))&&(isset($_SESSION['permision']))&&($_SESSION['permision'] <= 4)) {
		
		$size = $_GET['size'];
		require_once "connect.php";
		
	    $polonczenie = @new mysqli($host,$db_user,$db_password,$db_name);
	    mysqli_query($polonczenie, "SET CHARSET utf8");
	    mysqli_query($polonczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

		if($polonczenie->connect_errno!=0) {
			
			$_SESSION['statement'] = "Database connecting error.";
		} else {
			
			$sql = "SELECT id FROM customers";
			
			$obj = array();

			for($i = 1; $i <= $size; $i++) {

				$sql = "SELECT * FROM customers WHERE id='$i'";
				if($rezulatat=@$polonczenie->query($sql)){
					
					$exist = mysqli_num_rows($rezulatat);
					if($exist > 0) {
						
						$wiersz = $rezulatat->fetch_assoc();
						
						$name = $wiersz['name'];
						$surname = $wiersz['surname'];
						$email = $wiersz['email'];
						$phone = $wiersz['phone'];
						$old = $wiersz['old'];
						$address = $wiersz['address'];
						$country = $wiersz['country'];
					} else {
						
						$name = "";
						$surname = "";
						$email = "";
						$phone = "";
						$old = "";
						$address = "";
						$country = "";
						
					}
					
					$index = $i - 1; 
				    $obj[$index] = array(
				    "id" => $i,
				    "name" => $name,
				    "surname" => $surname,
				    "email" => $email,
					"phone" => $phone,
				    "old" => $old,
				    "address" => $address,
				    "country" => $country,
				    );
					$rezulatat->free_result();
				}
            
			}
           
		   echo json_encode($obj);
		   $polonczenie->close();
		}
		
	} else echo "";
	

//header('Location: loged.php');
?>