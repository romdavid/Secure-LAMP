<?php
    session_start();
	if (is_null($_SESSION['first']) or is_null($_SESSION['last']) or is_null($_SESSION['logins']) or is_null($_SESSION['prev_login'])) {
		session_destroy();
		header('Location: index.php');
		exit(0);
    }
    
    $file = '../private/company_confidential_file.txt';
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit(0);
?>
