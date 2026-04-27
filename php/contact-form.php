<?php
namespace PortoContactForm;

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'php-mailer/src/PHPMailer.php';
require 'php-mailer/src/SMTP.php';
require 'php-mailer/src/Exception.php';

// Step 1 - Enter your email address below.
$email = 'aaronbalolong49@gmail.com';

// If the e-mail is not working, change the debug option to 2 | $debug = 2;
$debug = 0;

// If contact form don't has the subject input change the value of subject here
$subject = ( isset($_POST['subject']) ) ? $_POST['subject'] : 'Define subject in php/contact-form.php line 29';

$message = '';

foreach($_POST as $label => $value) {
	$label = ucwords($label);

	// Use the commented code below to change label texts. On this example will change "Email" to "Email Address"

	// if( $label == 'Email' ) {               
	// 	$label = 'Email Address';              
	// }

	// Checkboxes
	if( is_array($value) ) {
		// Store new value
		$value = implode(', ', $value);
	}

	$message .= $label.": " . nl2br(htmlspecialchars($value, ENT_QUOTES)) . "<br>";
}

$mail = new PHPMailer(true);

try {

	$mail->SMTPDebug = $debug;                                 // Debug Mode

	// Step 2 (Optional) - If you don't receive the email, try to configure the parameters below:

	$mail->IsSMTP();                                         // Set mailer to use SMTP
	$mail->Host = 'tanzadoctors.com';				       // Specify main and backup server
	$mail->SMTPAuth = true;                                  // Enable SMTP authentication
	$mail->Username = 'trialmis@tanzadoctors.com';                    // SMTP username
	$mail->Password = 'pRR@G(g^NI+@dYy8';                              // SMTP password
	$mail->SMTPSecure = 'ssl';                               // Enable encryption, 'ssl' also accepted
	$mail->Port = 465;   								       // TCP port to connect to

	$mail->AddAddress($email);	 						       // Add another recipient

	// From - Name
	$fromName = ( isset($_POST['name']) ) ? $_POST['name'] : 'Website User';
	$mail->SetFrom('trialmis@tanzadoctors.com', $fromName);

	// Reply To
	if( isset($_POST['email']) && !empty($_POST['email']) ) {
		$mail->AddReplyTo($_POST['email'], $fromName);
	}

	$mail->IsHTML(true);                                       // Set email format to HTML

	$mail->CharSet = 'UTF-8';

	$mail->Subject = $subject;
	$mail->Body    = $message;

	// Step 3 - If you don't want to attach any files, remove that code below
	if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
		$mail->AddAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
	}

	$mail->Send();
	$arrResult = array ('response'=>'success');

} catch (Exception $e) {
	$arrResult = array ('response'=>'error','errorMessage'=>$e->errorMessage());
} catch (\Exception $e) {
	$arrResult = array ('response'=>'error','errorMessage'=>$e->getMessage());
}

if ($debug == 0) {
	echo json_encode($arrResult);
}