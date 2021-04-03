<?php
    require '../backend/db.php';
   
    $activated = false;
    if ($_GET['t']) {
        $db = new Database;
        $token = $_GET['t'];
        $id = $db->verify_token($token);
        
        if ($id) {
            if ($db->activate($id)) { 
                $activated = true;
                $db->destroy_token($token);
                $message = "<b><span>&#9989;</span> Your account has been activated, you can now login to your account!</b>";
            }
        }
    }

    if ($activated == false) {
        $message = "<b><span>&#10060;</span> Could not validate your account</b>";
    }
?>


<html>
   <head>
      <title>Account Activation</title>
   </head>
	
   <body>
      <h1>Account Activation</h1>
   
      <p><?php echo $message; ?></p>

      <br>

      <A HREF="index.php">Go to Login</A>
   </body>
</html>	
