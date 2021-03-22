<?php
	if ($_GET['t']) {
		$token = $_GET['t'];
		$config = parse_ini_file('../private/config.ini');
		$host = 'localhost:3306';
		$username = $config['username'];
		$password = $config['password'];
		$dbname = $config['dbname'];
		$connect = mysqli_connect($host, $username, $password, $dbname) or die ("cannot connect");
		
		$result = mysqli_query($connect, "SELECT id FROM login WHERE token='$token';");
		$count = mysqli_num_rows($result);
		
		if ($count == 1) {
			echo "Your account is activated!!<br>";
			$id = mysqli_fetch_assoc($result)['id'];
			$message = "<b><span>&#9989;</span> You can now login to your account!</b>";
			mysqli_query($connect, "UPDATE login SET activated=1 WHERE id='$id'");
		} else {
			$message = "<b><span>&#10060;</span> Could not validate your account</b>";
		}
	} else {
		$message = "<b><span>&#10060;</span> Could not validate your account</b>";
	}
?>

<html>
<html>
   <head>
      <title>Account Activation</title>
   </head>
	
   <body>
      <h1>Account Activation</h1>
   
      <p><?php echo $message; ?></p>

      <br>

      <A HREF="index.php">Go to Login</A>
   </body>
</html>	
