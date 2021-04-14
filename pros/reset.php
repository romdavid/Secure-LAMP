<?php
	require "../backend/db.php";
	
	$reset = false;
	if ($_POST['usr'] and $_POST['newPassword'] and $_POST['confirmPassword']) {
		if ($_POST['newPassword'] === $_POST['confirmPassword']) {
			
			session_start();
			$db = new Database;
			if ($_SESSION['token']) {
				if ($db->verify_token_user($_SESSION['token'], $_POST['usr'])) {
					
					if ($db->reset_password($_POST['usr'], sha1($_POST['newPassword']))) {
						$reset = true;
						$db->destroy_token($_SESSION['token']);
						session_unset();
						session_destroy();
					}
				}
			}
		}		
	} 
	if ($reset === false) {
		header("location: forgotuser.html");
		exit(0);
	}

?>

<!DOCTYPE html>
<html>
<html>
   <head>
      <title>Password Reset</title>
   </head>
	
   <body>
      <h1>Password Reset</h1>
   
      <p><b><span>&#9989;</span> Your password was reset!</b></p>

      <br>

      <A HREF="index.php">Go to Login</A>
   </body>
</html>	
