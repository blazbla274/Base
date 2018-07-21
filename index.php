<?php
    
	session_start();

	
	if((isset($_SESSION['connected']))&&($_SESSION['connected'] == 1)) {
		
	    session_unset();
	} 
	
?>

<html>

<head>
    <meta charset="utf-8"/>
    <title>Your company base</title>
    <meta name="discription" content=""/>
    <meta name="keywords" content=""/>
    <meta http-eguip="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="stylesheet" href="style.css" type="text/css"/>
	<link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">

</head>

<body>

    <div class="LoginContainer">
	
	    <div class="logo">
		</div>
		
		<form action="loged.php" method="POST">
		
		    <h1>
		        <?php
		            if(isset($_SESSION['error']))
		            echo $_SESSION['error'];
		        ?>
		    </h1>
			
            <input type="text" name="login" placeholder="login"><br>
            <input type="password" name="password" placeholder="password"><br>
            <input type="submit" value="Push">
			
        </form>
		
	</div>
	
	<?php
	
	unset($_SESSION['error']);
	?>
</body>

</html>