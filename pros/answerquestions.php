<?php
	require "../backend/db.php";
	
	$email_exists = false;
	if ($_POST['email']) {
		$db = new Database;
		$data = $db->get_questions($_POST['email']);
		if ($data) {
			$email_exists = true;
			session_start();
			$q1 = $data[0];
			$q2 = $data[1];
			$_SESSION['id'] = $data[2];
		}
	}
	
	if ($email_exists === false) {
                header("location: forgotuser.html");
                exit(0);
	}
?>


<html>
   <head>
      <title>Forgot Username or Password</title>
      <link rel="stylesheet" href="style.css">
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
      <script src="script.js" async></script>
   </head>
	
   <body>
   <center>
      <h1>Answer Security Questions</h1>

      <div class="container">

         <form method="post" action="verify-questions.php" autocomplete="off">
      
		     <?php echo $q1."<br>" ?><br><input type="text" name="ans1" maxlength="30" required><br><br>
		  
		     <?php echo $q2."<br>" ?><br><input type="text" name="ans2" maxlength="30" required><br><br>
		
			 <!-- <input type="hidden" name="id" value="<?php$id?>"/> -->
			 <input type="hidden" id="id" name="id" value="12">
		     <center>
		        <input type="submit" name="sub" value="Submit Answers" onclick="return Validate()"/>
		     </center><br><br>

		        <A HREF="index.php">Back to login page</A>
      
         </form>

      </div>

   </center>
   </body>
</html>
