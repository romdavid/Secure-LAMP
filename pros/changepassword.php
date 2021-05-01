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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="changepassword.js" async></script>
</head>
<body>

<h1>Change Password</h1>
<div class="container">
    <form method="post" action="reset.php" autocomplete="off">

        <center>
        Enter Username:<br>
        <input type="text" name="usr" maxlength="20" required><br><br>

        New Password:<br>
        <input type="password" id="newPassword" name="newPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" maxlength="30" required/>
        <br><center>Show Password</center><input type="checkbox" onclick="myPassFunction()">

        <br>
        <div id="message">
            <h3>Password must contain the following:</h3>
            <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
            <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
            <p id="number" class="invalid">A <b>number</b></p>
            <p id="length" class="invalid">Minimum <b>8 characters</b></p>
        </div>
        <br>

        Confirm Password:<br>
        <input type="password" id="confirmPassword" name="confirmPassword" title="Confirm new password" maxlength="30" required/>
        <br><center>Show Password</center><input type="checkbox" onclick="myConfirmFunction()"><br><br>

            <div class="g-recaptcha" data-sitekey="6LcO-YMaAAAAAKm34qo23ZWWaIky9nkU7G19ZDKK"></div>

        <p class="form-actions">
            <input type="submit" value="Change Password" onclick="return Validate()"/>
        </p>

        </center>
        <A HREF="index.php">Back to login page</A>

    </form>
</div>

<!--
<div id="message">
    <h3>Password must contain the following:</h3>
    <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
    <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
    <p id="number" class="invalid">A <b>number</b></p>
    <p id="length" class="invalid">Minimum <b>8 characters</b></p>
</div>
-->

</body>
</html>
