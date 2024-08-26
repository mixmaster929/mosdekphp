<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mailSend($arr, $file=null, $pdf=null) {
	
	$to = $arr['to'];
	$toName = $arr['toName'];
	$subject = $arr['subject'];
	$body = $arr['body'];
	$location = $arr['location'];
	
	
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
$mail = new PHPMailer(true);                             // Passing `true` enables exceptions

try {
    //Server settings
    $mail->SMTPDebug = false;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.proisp.no';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'post@mossdekk.no';                 // SMTP username
    $mail->Password = 'qvG0I%n_E($t';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 25;                                    // TCP port to connect to
		
	//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
    /*$mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'mukim15@gmail.com';                 // SMTP username
    $mail->Password = 'allah@mohammad';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
	*/
	
    //Recipients
    if($location == 'Mutto'){
        $mail->setFrom('post@mutto.no', 'MUTTO - Mobility Service');
        $mail->addAddress($to, $toName);
    }else{
        $mail->setFrom('post@mossdekk.no', 'MossDekk - Dekkhotell');
        $mail->addAddress($to, $toName);  
    }
  //  $mail->addAddress('ellen@example.com');               // Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //  $mail->addCC('cc@example.com');
  //  $mail->addBCC('bcc@example.com');

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	if($file != null) {
		$mail->AddAttachment($pdf['source'], $name = $pdf['name'],  $encoding = 'base64', $type = 'application/pdf');
	}
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
	//echo $mail;
    return true;
} catch (Exception $e) {
	//echo $e;
    return false;
    //echo 'Mailer Error: ' . $mail->ErrorInfo;
}
}



?>