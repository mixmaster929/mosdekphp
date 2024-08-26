<?php

require_once('includes/mailer.php');

$arr = [
    "to" => "prid_outing@hotmail.com",
    "toName" => "Zain",
    "subject" => "test from bestilling mossdekk",
    "body" => "Halla din shcmuck ;)"
];

$m = mailSend($arr);

echo $m;

?>