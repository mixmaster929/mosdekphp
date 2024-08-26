<?php
$con = dbCon();
$alertMsg = null;

if(isset($_POST['submit']) && ($_POST['username'] != '') && ($_POST['pass'] != '')) {
	$username = htmlspecialchars($_POST['username']);
	$pass = md5(htmlspecialchars($_POST['pass']));
	$q = mysqli_query($con, "SELECT * FROM shop_customers WHERE `regNr`='$username' AND `password`='$pass' ");
	if(mysqli_num_rows($q) == 0) { 
		$alertMsg = '<div class="alert alert-danger" role="alert">
			  Invalid username or password
			</div>';
	}
	else {
		$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
		$customerID = $f['id'];
		
		//setCookie('a', 1, time()+3600);
		$_SESSION['customerID'] = $customerID;
		header('location:index.php');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tyre Shop</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>

	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<link rel="stylesheet" href="../css/bootstrap.css" >
<script src="../js/bootstrap.bundle.js"></script>
<script src="../js/customScript.js"></script>
<link rel="stylesheet" href="../css/customStyle.css">


</head>
<body class="">

<?php 

//include('navTop.php');

?>

<div class="container-fluid" style="background-color:#fcfcfc;">
	
		<?php if($adminLoggedIn == 1) {
				include('navTop.php');
				include('sideNav.php'); ?>
			<div class="container-fluid contentContainer" style="background-color:#f7f7f7; padding:10px; width: calc(100% - 235px); min-height:calc(100vh - 30px); margin-top:30px; position:absolute; right:0; top:0;">
		<?php if(file_exists($page.'.php')) { include($page.'.php'); } else { include('home.php'); } 
			} ?>
			</div>
	
</div>

