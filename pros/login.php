<?php
	require "../backend/db.php";
	
	if (!($_POST['usr'] and $_POST['pwd'])) {
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
		
		$db->update_info($id);
		header('location: pros.php');
		
	} else {
		//echo "Sorry but login failed :(";
		header("location: index.php?msg=failed");
	}

?>

