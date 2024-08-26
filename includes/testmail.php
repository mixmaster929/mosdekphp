<?php

include('mailer.php');

$arr = array();
$arr['to'] = "saad@autobutler.no"; //'dekkhotell.autobutler@gmail.com';
$arr['toName'] = "Saad Rafe Mushtaq";
$arr['subject'] = 'Successfully place your order';
$arr['body'] = "TeSTING NTINESTING ";
$mail = mailSend($arr);
echo $mail;
					
					
?>