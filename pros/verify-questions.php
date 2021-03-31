<?php
	require '../backend/db.php';
	require '../backend/mail.php';
	
	$sent = false;
	if ($_POST['ans1'] and $_POST['ans2']) {
		session_start();
		$id = $_SESSION['id'];
		if ($id) {
			$db = new Database;
			if ($db->verify_answers($id, $_POST['ans1'], $_POST['ans2'])) {
				$myemail = $db->get_email($id);
				$email = new Email;
				$data = $db->get_usertoken($myemail);
				if ($data) {
					$user = $data[0];
					$token = $data[1];
					$link = "https://localhost/changepassword.php?t=$token";
					$subject = 'Forgot Username/Password';
					$body = '<b>Click the button below to reset your password</b>
						   	<br><br>
						   	Your Username: '.$user.'
						   	<br><br>
						   	<form method="post" action='.$link.'>
						   	<input type="submit" value="Reset Password" />
						   	</form>';
					if ($email->send_email($myemail, $subject, $body)) {
						$sent = true;
						session_unset();
						session_destroy();
					}
				} 
			} 
		}
	}
	
	if ($sent === false) {
		header("location: forgotuser.html");
		exit(0);
	}
	
	
	
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Username or Password</title>
</head>
	<body>
	
	<h1>Email Sent</h1>
	<br>
	<p><b>Check your E-mail to get username/reset password</b></p>

	</body>
</html>
