<?php 

$props = array("name", "firstname", "email" , 
		"message", "secureimage");
$valide = false;
foreach ($props as $value) 
{
	if( ! isset($_POST[$value]) || strlen( (string) $_POST[$value] ) < 2)
	{
		die("fault");
	}
}

$crypto = $_POST["secureimage"];
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
$securimage = new Securimage();
if( ! $securimage->check($crypto))
{
	die("fault");
}
$name = $_POST["name"];
$firstname = $_POST["firstname"];
$from = $_POST["email"];
$message = $_POST["message"];

require_once 'lib/phpMailer/class.phpmailer.php';
include_once 'client/config_thelia.php';
require_once 'classes/PDOThelia.class.php';
$q = "SELECT valeur FROM variable WHERE nom='emailcontact';";
$pdo = PDOThelia::getInstance();
$to = $pdo->query($q);
$to = $to->fetchColumn(0);
$mailer = new PHPMailer();
$mailer->IsMail();
$mailer->CharSet = "UTF-8";
$mailer->From = $from;
$mailer->FromName = "$firstname $name";
$mailer->Subject = "Message de $firstname $name";
$mailer->AddAddress($to, "Contact Ketmie");
$mailer->Body = "<div>" . nl2br($message) . "</div>";
$mailer->AltBody = $message;
$mailer->Send();
die("success");
?>