<?php
session_start();
include('includes/functions.php');

if(isset($_GET['p'])) {
	$p = htmlspecialchars($_GET['p']);
	if($p == '') { $page = 'home'; }
	else { $page = $p; }
}else { $page = 'home'; }

include('header.php');

include('footer.php');

?>