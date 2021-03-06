<?php
	session_start();
	if (is_null($_SESSION['first'])) {
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
      
      <p>Hi, <?=$_SESSION['first']?> <?=$_SESSION['last']?>, you've logged in <?=$_SESSION['logins']?> times</p>
      <p>Last login date: <?=$_SESSION['prev_login']?></p><br>
      
      <! company_confidential_file.txt >
      <p><center><strong>Download Now</strong></center></p>
      
      <center><a href="test.php" download>
      Company File
      </a></center><br><br><br><br><br>
      
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

