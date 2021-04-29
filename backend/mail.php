<?php
    use PHPMailer\PHPMailer\PHPMailer;
    require 'vendor/autoload.php';

    class Email {

        function __construct() {
            $config = parse_ini_file('../private/config.ini');
            $this->mail = new PHPMailer(true);

            // Specify the SMTP settings.
            $this->mail->isSMTP();
            $this->mail->setFrom($config['from'], 'Pros.com');
            $this->mail->Username   = $config['smtp-user'];
            $this->mail->Password   = $config['smtp-pass'];
            $this->mail->Host       = 'email-smtp.us-west-1.amazonaws.com';
            $this->mail->Port       = 587;
            $this->mail->SMTPAuth   = true;
            $this->mail->SMTPSecure = 'tls';
            $this->mail->isHTML(true);
        }

        function send_email($to, $subject, $body) {
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;

            if ($this->mail->Send()) {
                //echo "Email sent!";
		return true;
            } else {
                //echo "Email not sent! {$this->mail->ErrorInfo}";
            	return false;
	    }
        }
    }
?>
