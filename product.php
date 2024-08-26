<?php

$con = dbCon();

if(!isset($_GET['pID'])) { header('location:?p=home'); exit; }
$tyreID = (int)p($_GET['pID']);

$q = mysqli_query($con, "SELECT * FROM shop_tyres WHERE id=$tyreID");
if(mysqli_num_rows($q) == 0) { header('location:?p=home');  exit; }
	
$f = mysqli_fetch_array_n($q, MYSQLI_ASSOC);
$tyrePrice = $f['price'];
$tyreBrand = $f['brand'];
$tyreModel = $f['model'];
$tyreSize = $f['size'];
$fuel = $f['fuel'];
$grip = $f['grip'];
$noise = $f['noise'];


?>
<div class="container-fluid content" style="background-color:#fcfcfc; height:100%;">
	<!--<div class="row h-100 " style="padding:70px 10px 10px 10px; ">
			<div class="col-4 text-center" style="border:1px solid #000; overflow:auto;">
			
				
			</div><div class="col-8" style="border:1px solid #000;">
			 
			</div>
	
	</div>
	-->
	<div class="row" style="height:100%; padding:70px 10px 20px 10px;">
		<div class="col-5 align-self-center text-center p-3" style="overflow:auto;">
			<img src="images/autobutler.png" style="user-select:none; max-width:90%; border-radius:3px; box-shadow:0px 2px 2px #ccc;"/>
		</div>
		<div class="col-7 " style="border:0px solid #000; overflow:auto;">
			<div class="card ">
				<div class="card-body">
					<h5 class="card-title" ><?php echo $tyreBrand.'&nbsp;'.$tyreModel.'&nbsp;'.$tyreSize; ?></h5>
					<p class="card-text" style="color:#555; font-size:14px;">På lager | Levering ca. 3-5 dager</p>
					<hr>
					<div class="text-center" style="font-size:18px;">Resultater fra EU måling</div>
					<div class="row text-center" style="color:#333;">
						<div class="col" style="">
							<img src="images/fuel.jpg" style=" opacity:0.8;"/>
							<br> Drivstofforbruk <br>
							<span style="font-size:30px; text-transform:uppercase; font-weight:bold;"><?php echo $fuel; ?></span>
						</div>
						<div class="col" style="">
							<img src="images/grip.jpg" style=" opacity:0.8;"/>
							<br> Våtgrep <br>
							<span style="font-size:30px; text-transform:uppercase; font-weight:bold;"><?php echo $grip; ?></span>
						</div>
						<div class="col" style="">
							<img src="images/noise.jpg" style=" opacity:0.8;"/>
							<br> Støynivå <br>
							<span style="font-size:30px; text-transform:uppercase; font-weight:bold;"><?php echo $noise; ?>dB</span>
						</div>
					</div>
					<hr>
					<div style="margin-bottom:20px; font-size:18px;">Nå: kr <?php echo $tyrePrice; ?>,00 / stk</div>
					<button href="#" class="btn btn-primary" data-toggle="modal" data-target="#buyTyreModal" data-price="<?php echo $tyrePrice; ?>" data-tyreid="<?php echo $tyreID; ?>">Buy Tyre</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include('buyTyreModal.php'); ?>