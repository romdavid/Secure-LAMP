<?php
	if (!($_POST['usr'] and $_POST['pwd'])) {
		header('location: signup.html');
		exit(0);
	}
	use PHPMailer\PHPMailer\PHPMailer;
	require '..backend/vendor/autoload.php';
	$config = parse_ini_file('../private/config.ini');
	$host = 'localhost:3306';
	$username = $config['username'];
	$password = $config['password'];
	$dbname = $config['dbname'];
	$connect = mysqli_connect($host, $username, $password, $dbname) or die ("cannot connect");
	
	$myfirst = $_POST['first'];
	$mylast = $_POST['last'];
	$mybirth = $_POST['birth'];
	$myemail = $_POST['email'];
	$myusername = $_POST['usr'];
	$mypassword = $_POST['pwd'];
	$q1 = $_POST['q1'];
	$q2 = $_POST['q2'];
	$ans1 = $_POST['ans1'];
	$ans2 = $_POST['ans2'];
	$token = md5(md5($myemail).rand(10,99999999)).rand(10,99999999);
	$link = "https://localhost/activate.php?t=".$token."";
	
	$query = "INSERT INTO login VALUES(NULL,'$myusername','$mypassword',0,'$token','$myemail');";
	$query .= "INSERT INTO info VALUES(NULL,'$myfirst','$mylast','$mybirth',0,NULL,'$q1','$q2','$ans1','$ans2')";
	if (mysqli_multi_query($connect, $query)) {
		mysqli_next_result($connect);
	} else {
		header('location: signup.html');
		exit(0);
	}
	
	//Create a new PHPMailer instance
	$mail = new PHPMailer();
	$mail->CharSet = "utf-8";
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	//or more succinctly:
	$mail->Host = 'tls://smtp.gmail.com:587';
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	$mail->SMTPOptions = array(
	'ssl' => array(
	    'verify_peer' => false,
	    'verify_peer_name' => false,
	    'allow_self_signed' => true
	    )
	);
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "dave454x@gmail.com";
	//Password to use for SMTP authentication 
	$mail->Password = $config['email'];
	//Set who the message is to be sent from
	$mail->setFrom('dave454x@gmail.com', 'Pros.com');
	//Set who the message is to be sent to
	$mail->addAddress($myemail);
	//Set the subject line
	$mail->Subject = 'Verify your Pros account';
	$mail->isHTML(true);                   //Set email format to HTML
	$mail->Body    = '<b>Verify your account by clicking the button below</b>
			  <br><br>
			  <form method="post" action='.$link.'>
			  <input type="submit" value="Verify Account" /></center>
			  </form>';

	//send the message, check for errors
	if ($mail->send()) {
	    	echo "Message sent!";
		header('location: verify-account.html');
	} else {
	    	echo "Mailer Error: " . $mail->ErrorInfo;
	    	header('location: signup.html');
	}

?>
