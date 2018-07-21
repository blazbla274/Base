<?php

    session_start();
	
	if((isset($_SESSION['connected']))&&($_SESSION['connected'] == 1)) goto connected;
	
	if ((isset($_POST['login']))&&(isset($_POST['password']))) {
			
		$login = $_POST['login'];
		$_SESSION['login'] = $_POST['login'];
		$adPassword = $_POST['password'];
			
		if((strlen($adPassword) < 1)||(strlen($login) < 1)) {
			
			$_SESSION['connected'] = 0;
			$_SESSION['error'] = "Wpisz zmienne.";
			header('Location: index.php');
		} 
			
	} else {
		
		header('Location: index.php');
		exit();
	}
	
	require_once "connect.php";

	
	$polonczenie = @new mysqli($host,$db_user,$db_password,$db_name);

	if($polonczenie->connect_errno!=0) {
		
		$_SESSION['connected'] = 0;
		$_SESSION['error'] = "Nie można nawiązać połączenia.";
		header('Location: index.php');
		exit();
	}
	else if(!isset($_SESSION['error'])) {
		
		mysqli_query($polonczenie, "SET CHARSET utf8");
	    mysqli_query($polonczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
		
		$sql = "SELECT * FROM users WHERE name='$login'";
			   
			if($rezulatat=@$polonczenie->query($sql))
			{
			   $wiersz = $rezulatat->fetch_assoc();
			   $_SESSION['id'] = $wiersz['id'];
			   $rightPassword = $wiersz['password'];
			   $_SESSION['last_login'] = $wiersz['lastLogin'];
			   $last_added = $wiersz['lastAddCustomerId'];
			   $_SESSION['permision'] = $wiersz['permision'];
			
			   $rezulatat->free_result();
			}
		
	    if($rightPassword != $adPassword) {
		
		    $_SESSION['error'] = "Błędne dane logowania.";
		    header('Location: index.php');
		} else {
			
			$time = date('Y-m-d H:i:s');
		    $sql = "UPDATE users SET lastLogin='$time' WHERE name='$login'";
		    $rezulatat=@$polonczenie->query($sql);
			$_SESSION['navigationOverlap'] = 0;
			$_SESSION['baseSizeRange'] = 0; //odpowiada za iloś ładowanych rekordów w przeglądzie bazy 
			$_SESSION['connected'] = 1; 
			
			//wyciągnięcie z bazy imienia i nazwiska do last_added_customer_id
			if ($last_added > 0) {
				
			    $sql = "SELECT * FROM customers WHERE id='$last_added'";
			   
				if($rezulatat=@$polonczenie->query($sql))
				{
				   $wiersz = $rezulatat->fetch_assoc();
				   $last_added_name = $wiersz['name'];
				   $last_added_surname =  $wiersz['surname'];
				   
				   $_SESSION['last_added_customer'] = $last_added_name." ".$last_added_surname;
				
				   $rezulatat->free_result();
				}
			} else {
				
				$_SESSION['last_added_customer'] = "You didn't added any customers";
			}
		}
		
	$polonczenie->close();
    }
	
connected:
?>

<html>

<head>
    <meta charset="utf-8"/>
    <title>Your company base</title>
    <meta name="discription" content=""/>
    <meta name="keywords" content=""/>
    <meta http-eguip="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="stylesheet" href="style.css" type="text/css"/>
	<link rel="stylesheet" href="css/fontello.css" type="text/css"/>
	
	<link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,500|Rubik:500|Titillium+Web" rel="stylesheet">
	
	<script src="js/option.js"></script>
	<script src="js/log.js"></script>
	<script src="js/contentChanger.js"></script>
    <script src="js/showBase.js"></script>
	<script src="js/customerUpdateMenager.js"></script>
    <script src="js/sendingMailMenager.js"></script>
</head>

<body style="background-image: none; background-color: #b6c4db;" onload="log(), logSlide(), loadBase('load'), contentChanger(<?php echo $_SESSION['navigationOverlap'] ?>)">

	<div class="menuBar">
	
	    <div class="logoLoged">
		
		    <span>C</span>ompany <span>Base</span>
			
		</div>
		
		<div>
		
			<form class="search">
				<input type="text" name="search" placeholder="Search the bases">
			</form>
			
		</div>
		
	    <div class="menuBarBox">
		
		    <div><a href="index.php">Logout</a></div>
            
			<div onclick="optionToggle(300)"></div>
			
		</div>
		
		
	</div>
	
	
	<div class="navigation">
		
		<div class="navBox navBoxFirst" onclick="contentChanger(1)">
			
			Add customer
			
		</div>
			
		<div class="navBox" onclick="contentChanger(2)">
			
			Statistic
			
		</div>
		
		<div class="navBox" onclick="contentChanger(3)">
			
			Show Base
			
		</div>
		
		<div class="navBox" onclick="contentChanger(4)">
			
			Send e-mail's
			
		</div>
		
	</div>
	
	<div class="log" id="logBox">
	
	    <div class="topBarLog" onclick="logToggle(285,35)">Log
	    </div>
	   
	    <div class="logStatement">
	        
			<?php
			    for($i = 1; $i <= 10; $i++) {
					
			    echo "<div id='logStatement$i'>";
			    echo "</div>";
				}
			?>
			
	    </div>
	   
	</div>
	
	<div class="content">
	    
		<div class="welcome" id="contentBox0">
		    
			<h1>Welcome</h1>
			
			I added a modul of sendin mail's. kappa
		</div>
		
		<div class="addCustomerContainer" id="contentBox1">  
		    
			<h1>Add customer</h1>
			<form method="POST" action="phpScripts/addCustomerContainer.php">
			
			    <input type="text" name="name" placeholder="name">
				<input type="text" name="surname" placeholder="surname"> 
				<input type="text" name="old" placeholder="old" style="width: 35px"> 
				<input type="text" name="email" placeholder="email" style="width: 337px"> <br/>
				<input type="text" name="phone" placeholder="phone" style="width: 130px">
				<input type="text" name="address" placeholder="address" style="width: 350px"> 
				<input type="text" name="country" placeholder="country" style="width: 268px">
				
				<input type="submit" value="Add" class="addCustomerButton">
				
			</form>
			
		</div>
	    
		<div class="statistic" id="contentBox2">
		    
			<h1>Statistic</h1>
			
		</div>
		
		<div class="showBase" id="contentBox3">
		    
			<h1></h1>
			
			<table>
				<tr>
			    	<td class="baseId tabeTitle">Id</td>
					<td class="name tabeTitle">Name</td>
					<td class="surname tabeTitle">Surname</td>
					<td class="email tabeTitle">e-mail</td>
					<td class="phone tabeTitle">Phone</td>
					<td class="old tabeTitle">30</td>
					<td class="address tabeTitle">Adrress</td>
					<td class="country tabeTitle">Country</td>
				</tr>
			</table>
			
			<div class="showBaseTable">
				<table>
					
					<?php 
					
					for($i = 1; $i <= 100; $i++){
						
echo<<<END

                    <tr>
					    <td id="baseId$i" class="baseId" onclick="idSet($i)">$i</td>
						<td id="baseName$i" class="name"></td>
						<td id="baseSurname$i"class="surname"></td>
						<td id="baseEmail$i"class="email"></td>
						<td id="basePhone$i"class="phone"></td>
						<td id="baseOld$i"class="old"></td>
						<td id="baseAddress$i"class="address"></td>
						<td id="baseCountry$i"class="country"></td>
					</tr>
	
END;

	                }
					?>
					
				</table>
			</div>
			
			<div class="tableOption">
			
			    <span id="baseSizeRangeBaner"><?php echo $_SESSION['baseSizeRange'];?></span>
			    <input type="range" id="baseSizeRange" name="points" onchange="rangeUpdate(value)" value="<?php echo $_SESSION['baseSizeRange'];?>" min="0" max="100">
			
			    <div>
		
					<label>
						<input type="checkbox" id="RedHighlightCheckbox" type="checkbox">
						<span>Mark the blank</span>
					</label>

				</div>
				
				<div class="tableOptionSubmit" onclick="loadBase('click')">Load
				    
					<div class="refreshIco">
					    <a href="loged.php"><i class="icon-loop"></i></a>
					</div>
					
				</div>
				
			</div>
			
			<div style="clear: both"></div>
			
			<table>
                <form action="phpScripts/UpdateCustomer.php" method="POST">		
					<tr class="noindend">
						<td id="baseIdChange" class="baseId"><input name="idPOST" type="text" value="1" id="idIndexToChange" readonly></td>
						<td id="baseNameChange" class="name"><input name="name" type="text"></td>
						<td id="baseSurnameChange"class="surname"><input name="surname" type="text"></td>
						<td id="baseEmailChange"class="email"><input name="email" type="text"></td>
						<td id="baseEmailChange"class="phone"><input name="phone" type="text"></td>
						<td id="baseOldChange"class="old"><input name="old" type="text"></td>
						<td id="baseAddressChange"class="address"><input name="address" type="text"></td>
						<td id="baseCountryChange"class="country"><input name="country"type="text"></td>
						<td class="baseSubmit" id="dfg"><input type="submit" value="Change"></td>
					</tr>
                </form> 				
		    </table>
			
		</div>
		
		<div class="sendEmails" id="contentBox4">
		    
			<h1>Send e-mail's</h1>
			<form method="POST" action="phpScripts/sendEmails.php">
			    
                <div>
                    
                    <input type="text" name="topic" placeholder="topic"><br/>
                    <textarea cols="90" rows="8" name="contents" placeholder="contents"></textarea> <br/>
                    <input type="submit" value="Send" class="addCustomerButton"><input readonly value="Remember, to set you'r preference!">
            
                </div>
				
    
                <div class="sendingOption">
                     
                    <h1>Send preference</h1>
                    <span>This mail will be  send to: </span>
                    
                    <label>
                        <input type="checkbox" name="newsletterOn" id="newsletterOn" checked="true" onclick="sendingOptionGuardian('newsletter','newsletterOn')" >who has activ newsletter 
                    </label>
                    
                    <label>
                        <input type="checkbox" name="newsletterOff" id="newsletterOff" onclick="sendingOptionGuardian('newsletter','newsletterOff')">to everyone
                    </label>
                    
                    
                    <label>
                        
                        <input type="checkbox" unchecked onclick="customGroupToogle(100)">custom group
                        
                    </label>
                    
                    <div id="sendingMailPerference">
                        
                        <span>Livd:</span>
                        <div>
                            <span>in Poland</span><input type="checkbox" name="liveInPoland" id="mailLivd1" onclick="sendingOptionGuardian('mailLivd','1')">
                            <input type="checkbox" name="live" id="mailLivd2" checked="true" onclick="sendingOptionGuardian('mailLivd','2')">
                            <input type="checkbox" name="liveInAbroad" id="mailLivd3" onclick="sendingOptionGuardian('mailLivd','3')"><span>abroad</span>   
                        </div>
                        
                        <span>Old:</span>
                        <div>
                            <span>Minors</span><input type="checkbox" name="oldMinors" id="mailOld1" onclick="sendingOptionGuardian('mailOld','1')">
                            <input type="checkbox" name="old" id="mailOld2" checked="true" onclick="sendingOptionGuardian('mailOld','2')">
                            <input type="checkbox" name="oldAdults" id="mailOld3" onclick="sendingOptionGuardian('mailOld','3')"><span>Adults</span>   
                        </div>
                        
                    </div>
                    
                    
                    <input>

                </div>
    
			</form>
			    <?php
				    if(isset($_SESSION['sendEmailStatement'])) echo "<span style='color: red;'>".$_SESSION['sendEmailStatement']."</span>";
				    ?>
        </div>
		
	</div>
	
	<div class="option" id="option">
	    
		<h2><?php echo $_SESSION['login'];?></h2>
		
		<div class="optionInfoStatus">
		    
			<div onclick="optionChanger('optioInfo')" id="optioInfoBorder">
			Information
			</div>
			
			<div onclick="optionChanger('optionStatus')" id="optionStatusBorder">
			Activity
			</div>
			
		</div>
		
		
		<div class="optionContainer">
		    
			<div class="info" id="optioInfo">
			    
				<span><?php echo "Permision: ".$_SESSION['permision'];?></span>
				
			</div>
			
			<div class="status" id="optionStatus">
			
			    <div>Last login:
				</div>
				<span><?php echo $_SESSION['last_login'];?></span>
				
				<div>Last added person:
				</div>
				<span><?php echo $_SESSION['last_added_customer'];?></span>
				
			</div>
			
		</div>
	
	</div>
	
</body>

</html>