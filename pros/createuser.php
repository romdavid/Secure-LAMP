<?php
	require "../backend/db.php";
	require "../backend/mail.php";

	if (!($_POST['firstname'] and $_POST['lastname'] and $_POST['email'] and $_POST['topic1'] and $_POST['topic2'] and $_POST['first'] and $_POST['second'] and $_POST['usr'] and $_POST['pwd'])) {
		
		header('location: signup.html');
		exit(0);
	}
	
	$db = new Database;
	
	$myusername = $_POST['usr'];
	$myemail = $_POST['email'];
	if ($db->verify_user_email($myusername, $myemail) === false) {
		header('location: signup.html');
		exit(0);
	}
	
	$myfirst = $_POST['firstname'];
	$mylast = $_POST['lastname'];
	$mybirth = $_POST['birth'];
	$mypassword = sha1($_POST['pwd']);
	$q1 = $_POST['topic1'];
	$q2 = $_POST['topic2'];
	$ans1 = sha1($_POST['first']);
	$ans2 = sha1($_POST['second']);
	$token = md5(md5($myemail).rand(10,99999999)).rand(10,99999999);
	$address = parse_ini_file('../private/config.ini')['address'];
	$link = "https://".$address."/activate.php?t=".$token."";
	
	if (!($db->create_user($myusername,$mypassword,$token,$myemail,$myfirst,$mylast,$mybirth,$q1,$q2,$ans1,$ans2))) {
		header('location: signup.html');
		exit(0);
	}
	
	$email = new Email;
	$subject = 'Verify your Pros account';
	$body = '<b>Verify your account by clicking the button below</b>
			<br><br>
			<form method="post" action='.$link.'>
			<input type="submit" value="Verify Account" /></center>
			</form>';

	if ($email->send_email($myemail, $subject, $body)) {
	    //echo "Message sent!";
		header('location: verify-account.html');
	} else {
    	//echo "Mailer Error: " . $mail->ErrorInfo;
    	header('location: signup.html');
	}

?>
