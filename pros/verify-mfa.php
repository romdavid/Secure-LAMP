<?php
    require "../backend/db.php";

    if (is_null($_POST['c1']) or is_null($_POST['c2']) or is_null($_POST['c3']) or is_null($_POST['c4']) or is_null($_POST['c5'])) {
	header('location: index.php');
	exit(0);
    }

    session_start();
    if (is_null($_SESSION['first']) or is_null($_SESSION['last']) or is_null($_SESSION['logins']) or is_null($_SESSION['prev_login'])) {
	session_destroy();
	header('Location: index.php');
	exit(0);
    }

    $db = new Database;
    $mfa = $_POST['c1'].$_POST['c2'].$_POST['c3'].$_POST['c4'].$_POST['c5'];
    $id = $db->verify_mfa($mfa);

    if ($id) {

	// Store info in session variable
	$_SESSION['mfa'] = $mfa;
	// Update user info and destroy mfa code
	$db->update_info($id);
	$db->destroy_mfa($mfa);
	header('location: pros.php');

    } else {

        $db->destroy_mfa($mfa);
        session_unset();
        session_destroy();
	header("location: index.php?msg=failed-mfa");
    }
?>
