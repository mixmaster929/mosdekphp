<?php
	
	if(isset($_SESSION['customerID'])) {
		$customerID = (int)$_SESSION['customerID'];
		$loginStyle = 'style="display:none;"';
		$logoutStyle = '';
	}else {
		$loginStyle = '';
		$logoutStyle = 'style="display:none;"';
	}
?>

<!--<div class="navTop" style="">
	<div class="navTopContainer" style="">
		<div class="row" style="height:100%; margin:0;">
			<div class="col-sm-3 logoContainer" style="">
				<img class="navTopImg" src="images/autobutler.png" style=" " />
			</div><div class="col-sm-9 menuContainer" style="">
				<div style="padding-top:2px; text-transform:uppercase; color:#ddd; text-align:right; background:none; height:100%;">
					<a href="?p=home"><div class="menuTxt homeMenu" style="">Home</div></a>
					<div class="menuTxt" style="">Product Details</div>
					<div class="menuTxt" style="">Login</div>
					<div class="menuTxt menuBars" style=""><i class="fa fa-bars"></i></div>
				</div>
			</div>
		</div>
	</div>
</div>
-->

<nav class="navbar navbar-expand-md navbar-dark md-dark bg-custom">
  <a class="navbar-brand" href="#">
	 <img class="navTopImg" src="images/autobutler.png"  alt="">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="menuTxt nav-link" href="index.php">Hovedside <span class="sr-only">(current)</span></a>
      </li>
		  <!-- <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:#fff;">
          Velg Tjeneste
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#warehouseModal" data-type="tyreChange" data-title="Tyre Change" data-price=0>Dekkskift</a>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#warehouseModal" data-type="tyreBalancing" data-title="Tyre Balancing" data-price=0>Balansering av hjul</a>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#warehouseModal" data-type="tyreChangeDekkhotell" data-title="Dekkskift" data-price=0>Dekkskift Dekkhotell Kunde</a>
		      <a class="dropdown-item" href="#" data-toggle="modal" data-target="#warehouseModal" data-type="tyreRepair" data-title="Tyre Puncture Repair" data-price=0>Punktering</a>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#warehouseModal" data-type="lokasjon" data-title="Velg lokasjon" data-price=0>Velg Lokajon</a>
        </div>
      </li> -->
	   <!-- <li class="nav-item active" <?php echo $loginStyle; ?>>
        <a class="menuTxt nav-link" href="?p=" data-toggle="modal" data-target="#registerModal" data-type="register" data-title="Registration" >Ny Kunde </a>
      </li>  -->
	   <!-- <li class="nav-item active" <?php echo $loginStyle; ?>>
        <a class="menuTxt nav-link" href="?p=" data-toggle="modal" data-target="#loginModal" data-type="login" data-title="Login">Logg inn </a>
      </li> -->
	  <li class="nav-item active" <?php echo $logoutStyle; ?>>
        <a class="menuTxt nav-link" href="customer/">Customer</a>
      </li>
	   <li class="nav-item active" >
        <a class="menuTxt nav-link" href="?p=aboutUs#om-oss">Om Oss </a>
      </li>
	   <li class="nav-item active" >
        <a class="menuTxt nav-link" href="?p=pris">Priser </a>
      </li>
	  <li class="nav-item active">
        <a class="menuTxt nav-link" href="?p=contact">Kontakt </a>
      </li>
	  <li class="nav-item active" <?php echo $logoutStyle; ?>>
        <a class="menuTxt nav-link" href="javascript:logout();">Logout</a>
      </li>
	</ul>
	  <!--
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
	-->
  </div>
</nav>

<?php include('warehouseModal.php'); ?>
