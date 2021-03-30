<?php
	use PHPMailer\PHPMailer\PHPMailer;
	require "vendor/autoload.php";
	class Email {
		function __construct() { 
			$this->config = parse_ini_file('../private/config.ini');
			//Create a new PHPMailer instance
			$this->mail = new PHPMailer();
			//Tell PHPMailer to use SMTP
			$this->mail->isSMTP();
			//or more succinctly:
			$this->mail->Host = 'tls://smtp.gmail.com:587';
			//Whether to use SMTP authentication
			$this->mail->SMTPAuth = true;
			$this->mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
				)
			);
			//Username to use for SMTP authentication - use full email address for gmail
			$this->mail->Username = $this->config['from'];
			//Password to use for SMTP authentication
			$this->mail->Password = $this->config['email'];
			//Set who the message is to be sent from
			$this->mail->setFrom($this->config['from'], 'Pros.com');
			//Set who the message is to be sent to
			$this->mail->isHTML(true);                   //Set email format to HTML
		}
		
		function send_email($to, $subject, $body) {
			$this->mail->addAddress($to);
			$this->mail->Subject = $subject;
			$this->mail->Body    = $body;
			//send the message, check for errors
			if ($this->mail->send()) {
				//echo "Message sent!";
				return true;
			} else {
				//echo "Mailer Error: " . $this->mail->ErrorInfo;
				return false;
			}
		}
	}
?>
