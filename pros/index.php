<html>
   <head>
      <title>Welcome to Pros.com</title>
      <link rel="stylesheet" href="style.css">
   </head>
	
   <body>

   <h1>Welcome!</h1>

   <div class="container">
      
      <h2>Sign in below</h2>
   
      <form method="post" action="login.php" autocomplete="off">
      
      Username: <input type="text" name="usr" maxlength="20" required><br><br>
      
      Password: <input type="password" name="pwd" maxlength="30" required><br><br>

      <p style="color:#FF0000";> <?php
	
		if($_GET["msg"] && $_GET["msg"] == 'failed') {
			echo "Wrong Username or Password<br>";
		}
      
      ?></p>

      <center><input type="submit" name="sub" value="Login"/></center><br><br>
      
      </form>	
      
      <A HREF="signup.html">Don't have an account? Sign up</A><br><br>
      <A HREF="forgotuser.html">Forgot Username and Password?</A>

   </div>

   </body>
</html>	
