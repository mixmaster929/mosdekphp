<?php
session_start();
include('../includes/functions.php');

$adminLoggedIn = 0;

if(!isset($_SESSION['customerID'])) { include('header.php'); include('customerLogin.php'); exit; }
else {
	$adminLoggedIn = 1;
}

if(isset($_GET['p'])) {
	$p = htmlspecialchars($_GET['p']);
	if($p == '') { $page = 'home'; }
	else { $page = $p; }
}else { $page = 'home'; }

include('header.php');

//include('footer.php');

?>