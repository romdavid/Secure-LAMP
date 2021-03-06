<?php
	if (!$_POST['usr'] and !$_POST['pwd']) {
		if ($_POST['sub']) {
			header("location: index.php?msg=failed");
			exit(0);
		}
		header('location: index.php');
		exit(0);
	}
	$config = parse_ini_file('../private/config.ini');
	$host = 'localhost:3306';
	$username = $config['username'];
	$password = $config['password'];
	$dbname = $config['dbname'];
	$tblname = $config['dbtable'];
	$connect = mysqli_connect($host, $username, $password, $dbname) or die ("cannot connect");
	
	$myusername = $_POST['usr'];
	$mypassword = $_POST['pwd'];
	
	//$myusername = stripslashes($myusername):
	//$mypassword = stripslashes($mypassword);
	//$myusername = mysqli_real_escape_string($myusername);
	//$mypassword = mysqli_real_escape_string($mypassword);
	
	$sql = "SELECT id FROM login WHERE password='$mypassword' AND username='$myusername'";
	$result = mysqli_query($connect, $sql);
	$count = mysqli_num_rows($result);
	
	if ($count == 1) {
		session_start();
			
		$id = mysqli_fetch_assoc($result)['id'];
		$result = mysqli_query($connect, "SELECT first,last,logins,DATE_FORMAT(prev_login,'%b %d, %Y') AS formatted_date FROM info WHERE id=$id");
		$output = mysqli_fetch_assoc($result);
		
		
		$prev_login = $output['formatted_date'];
		if (is_null($prev_login)) {
			$prev_login = "This is your first login!";
		}
		
		// Store info in session variable
		$_SESSION['first'] = $output['first'];
		$_SESSION['last'] = $output['last'];
		$_SESSION['logins'] = intval($output['logins']+1);
		$_SESSION['prev_login'] = $prev_login;
	
		
		//echo "<br><strong>LOGIN SUCCESS!!</strong><br><br>";
		//echo "Hi, ".$output['first'] ." ". $output['last'] .", you have logged in ". intval($output['logins']+1) ." time(s)<br>";
		//echo "Last login date: ". $prev_login;
		
		mysqli_query($connect, "UPDATE info SET logins=logins+1,prev_login=CURDATE() where id=$id");
		header('location: pros.php');
		
	} else {
	
		echo "Sorry but login failed :(";
		//session_unset();
		//session_destroy();
		header("location:index.php?msg=failed");
		
	}

?>

