<?php
	session_start();
	if (is_null($_SESSION['first']) or is_null($_SESSION['last']) or is_null($_SESSION['logins']) or is_null($_SESSION['prev_login']) or is_null($_SESSION['mfa'])) {
		session_destroy();
		header('Location: index.php');
		exit(0);
	}
?>


<html>
   <head>
      <title>Pros</title>
   </head>
	
   <body>
      <center><h1>Pros</h1></center><br>
      
      <p>Hi, <?=$_SESSION['first']?> <?=$_SESSION['last']?>, you've logged in <?=$_SESSION['logins']?> time(s).</p>
      <p>Last login date: <?=$_SESSION['prev_login']?></p><br>
      
      <! company_confidential_file.txt >
      <p><center><strong>Download Now</strong></center></p>
      	
      	<center>
		<form method="post" action=download.php>
		<input type="submit" value="Download" />
		</form>
		</center>
      
      <br><br><br><br><br>
      
      <?php 
		if(isset($_POST['logout'])) {
			session_unset();
			session_destroy();
			header('Location: index.php');
			exit(0);
		}
      ?>
   
      <form method="post" >
      
      <center><input type="submit" name="logout" value="Logout"/></center><br><br>
      
      </form>	
   </body>
</html>

