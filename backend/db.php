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
		
		function escape($input) {
			return mysqli_real_escape_string($this->connect, $input);
		}
		
		function verify_user_email($user, $email) {
			$result = mysqli_query($this->connect, "SELECT id FROM login WHERE username='$user'");
                        if ($result) {
                                $user_id = mysqli_fetch_assoc($result)[id];
                        	$result = mysqli_query($this->connect, "SELECT id FROM login WHERE email='$email'");
                        	if ($result) {
                                	$email_id = mysqli_fetch_assoc($result)[id];
					if ($email_id === $user_id) {
						return true;
					}
				}
			}
			return false;
		}

		function get_active_id($username, $password) {
			$result = mysqli_query($this->connect, "SELECT id FROM login WHERE password='$password' AND username='$username' AND activated=1");
			if ($result) {
				$id = mysqli_fetch_assoc($result)['id'];
				return $id;
			} else {
				return false;
			}
		}
		
		function get_info($id) {
			$result = mysqli_query($this->connect, "SELECT first,last,logins,DATE_FORMAT(prev_login,'%b %d, %Y') AS formatted_date FROM info WHERE id=$id");
			if ($result) {
				$data = mysqli_fetch_assoc($result);
				return $data;
			} else {
				return false;
			}
		}
		
		function update_info($id) {
			return mysqli_query($this->connect, "UPDATE info SET logins=logins+1,prev_login=CURDATE() where id=$id");
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
		
		function activate($id) {
			return mysqli_query($this->connect, "UPDATE login SET activated=1 WHERE id='$id'");
		}
		
		function create_user($user,$pass,$token,$email,$first,$last,$birth,$q1,$q2,$ans1,$ans2) {
			$query = "INSERT INTO login VALUES(NULL,'$user','$pass',0,'$token','$email');";
			$query .= "INSERT INTO info VALUES(NULL,'$first','$last','$birth',0,NULL,'$q1','$q2','$ans1','$ans2')";
			if (mysqli_multi_query($this->connect, $query)) {
				return mysqli_next_result($this->connect);
			} else {
				return false;
			}
		}
		
	}
?>
