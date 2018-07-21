<?php //skrypt zwraca kontent względem ostatniego elementu tablicy log w bazie na podstawie podanego id elementu

    session_start();
	
	if(isset($_SESSION['connected'])&&($_SESSION['connected'] == 1)&&(isset($_GET['id']))) {
		
		$id = $_GET['id'];
		require_once "connect.php";
		
	    $polonczenie = @new mysqli($host,$db_user,$db_password,$db_name);

		if($polonczenie->connect_errno!=0) {
			
			$_SESSION['statement'] = "Database connecting error.";

		    $t = new DateTime();
			$print = $t->format('H:i:s');
			echo "<font>$print</font> Database connecting error.";
		} else {
			
			mysqli_query($polonczenie, "SET CHARSET utf8");
	        mysqli_query($polonczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
			
			$sql = "SELECT id FROM log";
			if($rezulatat=@$polonczenie->query($sql)){
			
			    $last_id = mysqli_num_rows($rezulatat);
			}
			
			$z = $last_id - $id + 1;
			
			
		    $sql = "SELECT * FROM log WHERE id='$z'";
			if($rezulatat=@$polonczenie->query($sql)){
				
				$wiersz = $rezulatat->fetch_assoc();
			    $content = $wiersz['content'];
				$timeHistory = $wiersz['time'];
				
				$t = new DateTime($timeHistory);
				$print = $t->format('H:i:s');
			    echo "<font>$print</font> $content";
			}
           
		   $polonczenie->close();
		}
		
	} else echo "";
	

//header('Location: loged.php');
?>