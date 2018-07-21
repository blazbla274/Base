<?php 
    
	session_start();
	require_once "connect.php";
	
	$id = $_SESSION['id'];
	$time = date('Y-m-d H:i:s');
	
    echo $_POST['contents']; 
	if((isset($_SESSION['permision']))&&($_SESSION['permision'] < 3)) {
		
		if((isset($_POST['topic'])) && (($_POST['topic'] != "") || ($_POST['contents'] != ""))) {
            
            if(isset($_POST['live'])) { //wyciaganie checkboxow
                $prefCountry = false;
            } else if (isset($_POST['liveInPoland'])) {
                $prefCountry = 'Poland';
            } else {
                $prefCountry = 'Abroad';
            }
            
            if(isset($_POST['old'])) {
                $prefOld = false;
            } else if (isset($_POST['oldMinors'])) {
                $prefOld = "Minors";
            } else {
                $prefOld = "Adults";
            }

            if(isset($_POST['newsletterOn'])) {
                $prefNewslatter = true;
            } else {
                $prefNewslatter = false;
            }
            
			$polonczenie = @new mysqli($host,$db_user,$db_password,$db_name);
			mysqli_query($polonczenie, "SET CHARSET utf8");
			mysqli_query($polonczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
			
			if($polonczenie->connect_errno!=0) {
			
				$_SESSION['statement'] = "Database connecting error.";
				
			} else {
				
				$sql = "SELECT id FROM customers"; //pobieram rozmiar bazy 
				
				if($rezultat=@$polonczenie->query($sql)) {
					
					$baseSize = mysqli_num_rows($rezultat);
				}

				$failedSend = 0;
				$SuccessSend = 0;
                
                $MailsMeetCri = 0;
                $MailsUnMeetCri = 0;
				for($i = 1; $i <= $baseSize; $i++) {
					
					$sql = "SELECT * FROM customers WHERE id='$i'";
					if ($rezultat=@$polonczenie->query($sql)) {
					
						$wiersz = $rezultat->fetch_assoc();
						
						$email = $wiersz['email'];
                        $old = $wiersz['old'];
						$country = $wiersz['country'];
						$newslatter = $wiersz['newsletter'];
                        //podjecie decyzji o wyslaniu lub nie maila
                        
                        $flag = true;
                        
                        //newslatter
                        if($prefNewslatter){
                            
                            if($newslatter != 1) $flag = false;
                        } 
                        
                        //narodowasc
                        if ($prefCountry) {
                            
                            if($prefCountry != $country) $flag = false;
                            if(($prefCountry == "Abroad")&&($country != "Poland")) $flag = true;
                            
                        }
                        
                        //wiek
                        if ($prefOld) {
                            
                            if(($old < 18) && ($prefOld == "Minors")  ||  (($old >= 18) && ($prefOld == "Adults")));
                            else $flag = false;
                        }
                        
                        
                        if($flag) {
                           //mail'e spelniajace kryteria
                            $MailsMeetCri++;
                                
                            $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);	//sprawdzenie poprawnoœci emaila
                            if(($email != "")&&(!((filter_var($emailB,FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)))) {
                                
                                if (mail($email, $_POST['topic'], $_POST['contents'], "From: newsletter@comrekt.pl")) {
                                    
									$SuccessSend++;
                                } else {
                                    
                                    $failedSend++;
                                    $_SESSION['sendEmailStatement'] = "Failed send:".$failedSend."e-mail's";	
                                } 
                            } else {
                                
                                $failedSend++;
                                $_SESSION['sendEmailStatement'] = "Failed send: ".$failedSend." mail's";
                            }
                        } else {
                            //mail'e nie spelniajace kryteriow
                            $MailsUnMeetCri++;
                        }
					}
					
				}
				
                if($failedSend == 0) $_SESSION['sendEmailStatement'] = "";				
                
                //wpis do log serwera
                $sql = "SELECT name FROM users WHERE id='$id'"; //pobieram id u¿ytkownika prubuj¹cego wykonaæ nieudan¹ operacje
		    	if($rezulatat=@$polonczenie->query($sql)){
				    $wiersz = $rezulatat->fetch_assoc();
		            $userName = $wiersz['name'];
			    }
				
		    	$copy = "<a> $userName </a>";
                $copy1 = "<a> $MailsMeetCri </a>";
                $copy2 = "<span> $MailsUnMeetCri </span>";
                $copy3 = "<a> $SuccessSend </a>";
                $copy4 = "<span> $failedSend </span>";
                
                $sql = "INSERT INTO log (content, responsibleUserId, time) VALUES ('Mails were sent by $copy. 
                        Mails meeting the requirements: $copy1, not meet: $copy2. Success send: $copy3, fails: $copy4', '$id', '$time')"; //wpis do log serwera
				if($rezulatat=@$polonczenie->query($sql)); else $_SESSION['statement'] = "Database connecting error.";
			}
		} else {
			
			$_SESSION['sendEmailStatement'] = "You didn't insert content";
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
			}
				
			$copy = "<span> $userName </span>";
			$sql = "INSERT INTO log (content, responsibleUserId, time) VALUES ('Failed sending mail's, $copy have no permision.', '$id', '$time')"; //wpis do log serwera
			if($rezulatat=@$polonczenie->query($sql));
			$polonczenie->close();
		}
		
	} 
	
	$_SESSION['navigationOverlap'] = 4;
	header('Location: /base/loged.php');
?>