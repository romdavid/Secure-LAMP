<?php
	if (!$_POST['usr'] or !$_POST['pwd']) {
		header('location: signup.html');
		exit(0);
	}
	use PHPMailer\PHPMailer\PHPMailer;
	require 'vendor/autoload.php';
	
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
	$token = md5($myemail).rand(10,99999999);
	$link = "https://localhost/test.php?key=".$myemail."&amp;token=".$token."";
	
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
	$email_cred = $config['email'];
	
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "dave454x@gmail.com";
	//Password to use for SMTP authentication
	$mail->Password = $email_cred;
	//Set who the message is to be sent from
	$mail->setFrom('dave454x@gmail.com', 'Pros.com');
	//Set who the message is to be sent to
	$mail->addAddress($myemail);

	//Set the subject line
	$mail->Subject = 'Verify your Pros account';
	$mail->isHTML(true);                   //Set email format to HTML
	$mail->Body    = '<b>Verify your account by clicking the link below</b>
			  <br><br>
			  <form method="post" action='.$link.'>
			  <input type="submit" value="Verify" /></center>
			  </form>';

	//send the message, check for errors
	if ($mail->send()) {
		mysqli_query($connect, "INSERT INTO login VALUES(NULL,'$myusername','$mypassword',0,'$token', '$myemail')");
		mysqli_query($connect, "INSERT INTO info VALUES(NULL,'$myfirst','$mylast','$mybirth',0,NULL)");
	    	echo "Message sent!";
	} else {
	    	echo "Mailer Error: " . $mail->ErrorInfo;
	}

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
