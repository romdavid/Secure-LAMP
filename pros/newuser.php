<?php
	
	$config = parse_ini_file('../private/config.ini');
	$host = 'localhost:3306';
	$username = $config['username'];
	$password = $config['password'];
	$dbname = $config['dbname'];
	//$tblname = $config['dbtable'];
	$connect = mysqli_connect($host, $username, $password, $dbname) or die ("cannot connect");
	
	$myfirst = $_POST['first'];
	$mylast = $_POST['last'];
	$mybirth = $_POST['birth'];
	$myemail = $_POST['email'];
	$myusername = $_POST['usr'];
	$mypassword = $_POST['pwd'];
	
	mysqli_query($connect, "INSERT INTO login VALUES(NULL,'$myusername','$mypassword')");
	mysqli_query($connect, "INSERT INTO info VALUES(NULL,'$myfirst','$mylast','$mybirth','$myemail',0,NULL)");
	

?>

<html>
   <head>
      <title>Confirm E-mail</title>
   </head>
	
   <body>
      <h1>Confirm E-mail</h1>
      
      <p>To finish making your account please confirm your e-mail address</p>
   	
      <br>
      
      <A HREF="index.html">Back to Login</A>
   </body>
</html>	
