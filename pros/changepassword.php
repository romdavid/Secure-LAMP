<?php
	require '../backend/db.php';
	
	if ($_GET['t']) {
		$db = new Database;
		$id = $db->verify_token($_GET['t']);
		if ($id == false) {
			header("location: forgotuser.html");
			exit(0);
		}
		session_start();
		$_SESSION['token'] = $_GET['t'];

	} else {
		header("location: forgotuser.html");	
		exit(0);
	}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" async></script>
</head>
<body>

<h1>Change Password</h1>
<div class="container">
    <form method="post" action="reset.php" autocomplete="off">

        Enter Username:<br>
        <input type="text" name="usr" maxlength="15" required><br><br>

        New Password:
        <input type="password" id="newPassword" name="newPassword" title="New password" maxlength="30" required/>
        <br><center>Show Password</center><input type="checkbox" onclick="myPassFunction()"><br><br>

        Confirm Password:
        <input type="password" id="confirmPassword" name="confirmPassword" title="Confirm new password" maxlength="30" required/>
        <br><center>Show Password</center><input type="checkbox" onclick="myConfirmFunction()"><br>

        <p class="form-actions">
            <input type="submit" value="Change Password"/>
        </p>
    </form>


	<A HREF="index.php">Back to login page</A>

</div>
</body>
</html>
