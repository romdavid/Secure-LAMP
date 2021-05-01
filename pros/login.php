<?php
	require "../backend/db.php";
	require "../backend/mail.php";
	
	if (is_null($_POST['usr']) or is_null($_POST['pwd'])) {
		if ($_POST['sub']) {
			header("location: index.php?msg=failed");
			exit(0);
		}
		header('location: index.php');
		exit(0);
	}
	
	$db = new Database;
	$myusername = $_POST['usr'];
	$mypassword = sha1($_POST['pwd']);
	
	$id = $db->get_active_id($myusername, $mypassword);
	
	if ($id) {
		session_start();
		$mail = new Email;
		$data = $db->get_info($id);
		
		$prev_login = $data['formatted_date'];
		if (is_null($prev_login)) {
			$prev_login = "This is your first login!";
		}
		
		// Store info in session variable
		$_SESSION['first'] = $data['first'];
		$_SESSION['last'] = $data['last'];
		$_SESSION['logins'] = intval($data['logins']+1);
		$_SESSION['prev_login'] = $prev_login;
		
		// Create mfa code
	        $mfa = $db->create_mfa($id);
		if ($mfa === false) {
			header("location: index.php");
			exit(0);
		}

		// Send code to mail
	        $my_email = $db->get_email($id);
	        if  ($my_email === false) {
        	    header("location: index.php");
	            exit(0);
	        }
	        $subject = 'Multi-factor Authentication';
        	$body = '<b>Enter code into website to login</b>
                	<br><br>
	                Access Code: '.$mfa;

	        if ($mail->send_email($my_email, $subject, $body) === false) {
	            header("location: index.php");
        	    exit(0);
	        }

		header('location: mfa.php');

	} else {

		header("location: index.php?msg=failed");
	}

?>

