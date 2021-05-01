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
			$user = $this->escape($user);
			$email = $this->escape($email);
			$result = mysqli_query($this->connect, "SELECT id FROM login WHERE username='$user'");
                        if (mysqli_num_rows($result) == 1) {
                                $user_id = mysqli_fetch_assoc($result)[id];
                        	$result = mysqli_query($this->connect, "SELECT id FROM login WHERE email='$email'");
                        	if (mysqli_num_rows($result) == 1) {
                                	$email_id = mysqli_fetch_assoc($result)[id];
					if ($email_id === $user_id) {
						return true;
					}
				}
			}
			return false;
		}

		function get_active_id($username, $password) {
			$username = $this->escape($username);
			$password = $this->escape($password);
			$result = mysqli_query($this->connect, "SELECT id FROM login WHERE password='$password' AND username='$username' AND activated=1");
			if ($result) {
				$id = mysqli_fetch_assoc($result)['id'];
				return $id;
			} else {
				return false;
			}
		}

		function create_mfa($id) {
			$id = $this->escape($id);
			$code = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
			if (mysqli_query($this->connect, "UPDATE login SET mfa='$code' WHERE id='$id'")) {
        			return $code;
			} else {
			        return false;
    			}
		}

		function verify_mfa($code) {
			$code = $this->escape($code);
			$result = mysqli_query($this->connect, "SELECT id FROM login WHERE mfa='$code'");
			if (mysqli_num_rows($result) == 1) {
			        return mysqli_fetch_assoc($result)['id'];
			} else {
				return false;
			}
		}

		function destroy_mfa($code) {
			$code = $this->escape($code);
			return mysqli_query($this->connect, "UPDATE login SET mfa=NULL WHERE mfa='$code'");
		}

		function get_info($id) {
			$id = $this->escape($id);
			$result = mysqli_query($this->connect, "SELECT first,last,logins,DATE_FORMAT(prev_login,'%b %d, %Y') AS formatted_date FROM info WHERE id='$id'");
			if ($result) {
				$data = mysqli_fetch_assoc($result);
				return $data;
			} else {
				return false;
			}
		}
		
		function update_info($id) {
			$id = $this->escape($id);
			return mysqli_query($this->connect, "UPDATE info SET logins=logins+1,prev_login=CURDATE() where id='$id'");
		}
		
		function destroy_token($token) {
			$token = $this->escape($token);
			return mysqli_query($this->connect, "UPDATE login SET token=NULL WHERE token='$token'");
		}
		
		function get_usertoken($email) {
			$email = $this->escape($email);
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
			$token = $this->escape($token);
			$result = mysqli_query($this->connect, "SELECT id FROM login WHERE token='$token'");
			if ($result) {
				$id = mysqli_fetch_assoc($result)['id'];
				return $id;
			} else {
				return false;
			}
		}
		
		function verify_token_user($token, $username) {
			$token = $this->escape($token);
			$username = $this->escape($username);
			$result = mysqli_query($this->connect, "SELECT username FROM login WHERE token='$token'");
			if ($result) {
				$user = mysqli_fetch_assoc($result)['username'];
				return $user === $username;
			} else {
				return false;
			}
		}
		
		function verify_answers($id, $ans1, $ans2) {
			$id = $this->escape($id);
			$ans1 = $this->escape($ans1);
			$ans2 = $this->escape($ans2);
			$result = mysqli_query($this->connect, "SELECT answer1,answer2 FROM info WHERE id='$id'");
			if ($result) {
				$answers = mysqli_fetch_assoc($result);
				if ($answers['answer1'] === $ans1 and $answers['answer2'] === $ans2) {
					return true;
				}
			}
			return false;
		}
		
		function get_questions($mail) {
			$mail = $this->escape($mail);
			$result = mysqli_query($this->connect, "SELECT id FROM login WHERE email='$mail'");
			if (mysqli_num_rows($result) == 1) {
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
			$id = $this->escape($id);
			$result = mysqli_query($this->connect, "SELECT email FROM login WHERE id='$id'");
			if ($result) {
				$email = mysqli_fetch_assoc($result)['email'];
				return $email;
			} else {
				return false;
			}
		}
		
		function reset_password($user, $newpassword) {
			$user = $this->escape($user);
			$newpassword = $this->escape($newpassword);
			return mysqli_query($this->connect, "UPDATE login SET password='$newpassword' WHERE username='$user'");
		}
		
		function activate($id) {
			$id = $this->escape($id);
			return mysqli_query($this->connect, "UPDATE login SET activated=1 WHERE id='$id'");
		}
		
		function create_user($user,$pass,$token,$email,$first,$last,$birth,$q1,$q2,$ans1,$ans2) {
			$user = $this->escape($user);
			$pass = $this->escape($pass);
			$token = $this->escape($token);
			$email = $this->escape($email);
			$first = $this->escape($first);
			$last = $this->escape($last);
			$q1 = $this->escape($q1);
			$q2 = $this->escape($q2);
			$ans1 = $this->escape($ans1);
			$ans2 = $this->escape($ans2);
			$query = "INSERT INTO login VALUES(NULL,'$user','$pass',0,'$token','$email',NULL);";
			$query .= "INSERT INTO info VALUES(NULL,'$first','$last','$birth',0,NULL,'$q1','$q2','$ans1','$ans2')";
			if (mysqli_multi_query($this->connect, $query)) {
				return mysqli_next_result($this->connect);
			} else {
				return false;
			}
		}
		
	}
?>
