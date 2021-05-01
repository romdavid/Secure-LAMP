<?php
   session_start();
   if (is_null($_SESSION['first']) or is_null($_SESSION['last']) or is_null($_SESSION['logins']) or is_null($_SESSION['prev_login'])) {
      session_destroy();
      header('Location: index.php');
      exit(0);
   }
?>


<!DOCTYPE html>
<html>
   <head>
      <title>Multi-factor Authentication</title>
   </head>
	
   <body>

   <h1>Multi-factor Authentication</h1><br>

   <div class="container">
      
      <h3>Check E-mail and enter 5 digit number</h3>

    <form method='post' action='verify-mfa.php'>
        <input type="number" name="c1" min="0" max="9" required>
        - <input type="number" name="c2" min="0" max="9" required>
        - <input type="number" name="c3" min="0" max="9" required>
        - <input type="number" name="c4" min="0" max="9" required>
        - <input type="number" name="c5" min="0" max="9" required>
        <br><br><br><input type="submit" name="sub" value="Submit">
    </form>
      
    <br><br>
    <A HREF="index.php">Back to Sign-in</A>

   </div>

   </body>
</html>
