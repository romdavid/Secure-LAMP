<html>
   <head>
      <title>Welcome to Pros.com</title>
   </head>
	
   <body>
      <h1>Welcome!</h1>
      
      <p>Sign in below</p>
   
      <form method="post" action="login.php">
      
      Username: <input type="text" name="usr"><br><br>
      
      Password: <input type="password" name="pwd"><br><br>
      
      <p style="color:#FF0000";> <?php
	
	if($_GET["msg"] && $_GET["msg"] == 'failed') {
		echo "Wrong Username or Password<br>";
		echo "(".$_GET["msg"].")";
	}
      
      ?></p>

      <center><input type="submit" name="sub" value="Login"/></center><br><br>
      
      </form>	
      
      <A HREF="signup.html">Don't have an account?</A>
   </body>
</html>	
