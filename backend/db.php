<?php
	class Database {
		function __construct() {
			$config = parse_ini_file('../private/config.ini');
			$host = 'localhost:3306';
			$username = $config['username'];
			$password = $config['password'];
			$dbname = $config['dbname'];
			$this->connect = mysqli_connect($host, $username, $password, $dbname) or die ("cannot connect");
		}
		
		function destroy_token($token) {
			return mysqli_query($this->connect, "UPDATE login SET token=NULL WHERE token='$token'");
		}
		
		function get_usertoken($email) {
			$result = mysqli_query($this->connect, "SELECT username FROM login WHERE email='$email'");
			
			if ($result) {
				$username = mysqli_fetch_assoc($result)['username'];
				$token = md5(md5($email).rand(10,99999999)).rand(10,99999999);
				mysqli_query($this->connect, "UPDATE login SET token='$token' WHERE username='$username'");
				return [$username, $token];
				
			} else {
				return false;
			}
		}
		
		function verify_token($token) {
			$result = mysqli_query($this->connect, "SELECT id FROM login WHERE token='$token'");
			if ($result) {
				$id = mysqli_fetch_assoc($result)['id'];
				return $id;
			} else {
				return false;
			}
		}
		
		function verify_token_user($token, $username) {
			$result = mysqli_query($this->connect, "SELECT username FROM login WHERE token='$token'");
			if ($result) {
				$user = mysqli_fetch_assoc($result)['username'];
				return $user === $username;
			} else {
				return false;
			}
		}
		
		
		function verify_answers($id, $ans1, $ans2) {
			$result = mysqli_query($this->connect, "SELECT answer1,answer2 FROM info WHERE id='$id'");
			if ($result) {
				$answers = mysqli_fetch_assoc($result);
				if ($answers['answer1'] === $ans1 and $answers['answer2'] === $ans2) {
					return true;
				}
			}
			return false;
		}
		
		function get_questions($email) {
			$result = mysqli_query($this->connect, "SELECT id FROM login WHERE email='$email'");
			if ($result) {
				$id = mysqli_fetch_assoc($result)['id'];
				$result = mysqli_query($this->connect, "SELECT question1,question2 FROM info WHERE id='$id'");
				if ($result) {
					$data = mysqli_fetch_assoc($result);
					return [$data['question1'], $data['question2'], $id];
				}
			}
			return false;
		}
		
		function get_email($id) {
			$result = mysqli_query($this->connect, "SELECT email FROM login WHERE id='$id'");
			if ($result) {
				$email = mysqli_fetch_assoc($result)['email'];
				return $email;
			} else {
				return false;
			}
		}
		
		function reset_password($user, $newpassword) {
			return mysqli_query($this->connect, "UPDATE login SET password='$newpassword' WHERE username='$user'");
		}
		
	}
?>
